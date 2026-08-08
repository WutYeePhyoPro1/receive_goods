import qz from 'qz-tray';

const STORAGE_PRINTER = 'received_goods_barcode_printer';
const STORAGE_MODE = 'received_goods_barcode_print_mode';
const DOTS_PER_MM = 203 / 25.4;

window.qz = qz;

function dots(mm) {
    return Math.round(mm * DOTS_PER_MM);
}

function clean(value) {
    return String(value ?? '')
        .replace(/[\r\n\t]+/g, ' ')
        .replace(/"/g, "'")
        .trim();
}

function compactDate(value) {
    const normalized = clean(value);
    const match = normalized.match(/^(\d{2})\/(\d{2})\/(\d{4})\s+(\d{1,2}):(\d{2})(?::\d{2})?\s+(AM|PM)$/i);
    if (!match) return normalized.slice(0, 18);

    return `${match[1]}/${match[2]}/${match[3].slice(-2)} ${match[4]}:${match[5]} ${match[6].toUpperCase()}`;
}

function ascii(value) {
    return new TextEncoder().encode(value);
}

function concatBytes(parts) {
    const length = parts.reduce((total, part) => total + part.length, 0);
    const output = new Uint8Array(length);
    let offset = 0;

    parts.forEach((part) => {
        output.set(part, offset);
        offset += part.length;
    });

    return output;
}

function fontString(fontSize, fontWeight) {
    return `${fontWeight} ${fontSize}px Arial, Helvetica, sans-serif`;
}

function wrapText(value, maxWidth, fontSize, fontWeight, maxLines) {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    context.font = fontString(fontSize, fontWeight);
    const words = clean(value).split(/\s+/).filter(Boolean);
    const lines = [];

    words.forEach((word) => {
        if (lines.length === 0) {
            lines.push(word);
            return;
        }

        const candidate = `${lines[lines.length - 1]} ${word}`;
        if (context.measureText(candidate).width <= maxWidth) {
            lines[lines.length - 1] = candidate;
        } else if (lines.length < maxLines) {
            lines.push(word);
        }
    });

    return (lines.length ? lines : ['']).slice(0, maxLines);
}

function wrapAllText(value, maxWidth, fontSize, fontWeight) {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    context.font = fontString(fontSize, fontWeight);
    const words = clean(value).split(/\s+/).filter(Boolean);
    const lines = [];
    let current = '';

    const pushLongWord = (word) => {
        let segment = '';
        Array.from(word).forEach((character) => {
            const candidate = `${segment}${character}`;
            if (!segment || context.measureText(candidate).width <= maxWidth) {
                segment = candidate;
            } else {
                lines.push(segment);
                segment = character;
            }
        });
        current = segment;
    };

    words.forEach((word) => {
        const candidate = current ? `${current} ${word}` : word;
        if (context.measureText(candidate).width <= maxWidth) {
            current = candidate;
            return;
        }

        if (current) {
            lines.push(current);
            current = '';
        }

        if (context.measureText(word).width <= maxWidth) {
            current = word;
        } else {
            pushLongWord(word);
        }
    });

    if (current) {
        lines.push(current);
    }
    return lines.length ? lines : [''];
}

function fitCompleteText(value, maxWidth, maxFontSize, minFontSize, maxLines, maxTextHeight, fontWeight) {
    for (let fontSize = maxFontSize; fontSize >= minFontSize; fontSize -= 1) {
        const lines = wrapAllText(value, maxWidth, fontSize, fontWeight);
        if (lines.length <= maxLines && (lines.length * (fontSize + 2)) <= maxTextHeight) {
            return { fontSize, lines };
        }
    }

    return {
        fontSize: minFontSize,
        lines: wrapAllText(value, maxWidth, minFontSize, fontWeight),
    };
}

function bitmapText(x, y, value, options = {}) {
    const fontSize = options.fontSize || 11;
    const fontWeight = options.fontWeight || 400;
    const width = Math.max(8, options.width || dots(31));
    const height = Math.max(8, options.height || fontSize + 4);
    const widthBytes = Math.ceil(width / 8);
    const canvasWidth = widthBytes * 8;
    const canvas = document.createElement('canvas');
    canvas.width = canvasWidth;
    canvas.height = height;

    const context = canvas.getContext('2d', { willReadFrequently: true });
    context.fillStyle = '#fff';
    context.fillRect(0, 0, canvasWidth, height);
    context.fillStyle = '#000';
    context.font = fontString(fontSize, fontWeight);
    context.textBaseline = 'top';
    context.fillText(clean(value), 0, 0, width);

    const pixels = context.getImageData(0, 0, canvasWidth, height).data;
    // Gainscha TSPL BITMAP uses cleared bits for black and set bits for white.
    const bitmap = new Uint8Array(widthBytes * height);
    bitmap.fill(0xFF);

    for (let row = 0; row < height; row += 1) {
        for (let column = 0; column < canvasWidth; column += 1) {
            const pixel = ((row * canvasWidth) + column) * 4;
            const luminance = (pixels[pixel] * 0.299) + (pixels[pixel + 1] * 0.587) + (pixels[pixel + 2] * 0.114);
            if (pixels[pixel + 3] > 0 && luminance < 170) {
                bitmap[(row * widthBytes) + Math.floor(column / 8)] &= ~(0x80 >> (column % 8));
            }
        }
    }

    return concatBytes([
        ascii(`BITMAP ${x},${y},${widthBytes},${height},0,`),
        bitmap,
        ascii('\r\n'),
    ]);
}

function barcode(labelX, y, height, value) {
    // Two printer dots per narrow module is the reliable minimum at 203 DPI.
    return `BARCODE ${labelX + dots(1.5)},${y},"128",${height},0,0,2,2,"${clean(value)}"`;
}

function fullLabel(labelX, top, payload, type) {
    const left = labelX + dots(1.5);
    const textWidth = dots(31);
    const isBar1 = type === 1;
    const fittedName = fitCompleteText(payload.name, textWidth, isBar1 ? 14 : 13, 8, 4, 44, 500);
    const nameFontSize = fittedName.fontSize;
    const nameTop = top + 2;
    const nameLineHeight = nameFontSize + 2;
    const lines = fittedName.lines;
    const commands = [];

    lines.forEach((line, index) => {
        commands.push(bitmapText(left, nameTop + (index * nameLineHeight), line, {
            width: textWidth,
            fontSize: nameFontSize,
            fontWeight: 500,
        }));
    });

    const barcodeY = nameTop + (lines.length * nameLineHeight) + 2;
    const barcodeHeight = type === 3 ? 30 : 50;
    const detailsY = barcodeY + barcodeHeight + 3;
    commands.push(ascii(`${barcode(labelX, barcodeY, barcodeHeight, payload.barcode)}\r\n`));
    commands.push(bitmapText(left, detailsY, payload.barcode, {
        width: dots(25),
        fontSize: isBar1 ? 13 : 12,
        fontWeight: 500,
    }));
    commands.push(bitmapText(labelX + dots(27.5), detailsY, payload.unit, {
        width: dots(4),
        fontSize: isBar1 ? 12 : 11,
        fontWeight: 500,
    }));

    if (type === 3) {
        const boxY = detailsY + 14;
        commands.push(ascii(`BOX ${left},${boxY},${labelX + dots(18)},${boxY + dots(3.5)},2\r\n`));
        commands.push(ascii(`BOX ${labelX + dots(20)},${boxY},${labelX + dots(23)},${boxY + dots(3)},2\r\n`));
    }

    const dateY = type === 3 ? detailsY + 44 : detailsY + 17;
    commands.push(bitmapText(left, dateY, compactDate(payload.printedAt), {
        width: textWidth,
        fontSize: isBar1 ? 12 : 11,
        fontWeight: 400,
    }));
    return commands;
}

function halfLabel(labelX, top, payload) {
    const left = labelX + dots(1.5);
    const textWidth = dots(31);
    const fittedName = fitCompleteText(payload.name, textWidth, 12, 7, 3, 27, 500);
    const nameLines = fittedName.lines;
    const nameFontSize = fittedName.fontSize;
    const nameLineHeight = nameFontSize + 2;
    const hasMultipleNameLines = nameLines.length > 1;
    const commands = [];

    nameLines.forEach((line, index) => {
        commands.push(bitmapText(left, top + (index * nameLineHeight), line, {
            width: textWidth,
            height: nameLineHeight,
            fontSize: nameFontSize,
            fontWeight: 500,
        }));
    });

    const barcodeY = top + (nameLines.length * nameLineHeight) + 1;
    const barcodeHeight = hasMultipleNameLines ? 24 : 34;
    const detailsY = barcodeY + barcodeHeight + 2;
    const dateY = top + 70;
    commands.push(ascii(`${barcode(labelX, barcodeY, barcodeHeight, payload.barcode)}\r\n`));
    commands.push(bitmapText(left, detailsY, payload.barcode, {
        width: dots(25),
        height: hasMultipleNameLines ? 11 : 15,
        fontSize: hasMultipleNameLines ? 9 : 11,
        fontWeight: 500,
    }));
    commands.push(bitmapText(labelX + dots(27.5), detailsY, payload.unit, {
        width: dots(4),
        height: hasMultipleNameLines ? 11 : 15,
        fontSize: hasMultipleNameLines ? 9 : 11,
        fontWeight: 500,
    }));
    commands.push(bitmapText(left, dateY, compactDate(payload.printedAt), {
        width: textWidth,
        height: 11,
        fontSize: 9,
        fontWeight: 400,
    }));
    return commands;
}

function buildPage(payload, pageItems, type) {
    // Keep a printable quiet area at each sticker's right edge, especially column three.
    const labelXs = [dots(-1.5), dots(35.6), dots(73.2)];
    const centeredTop = dots(3.155);
    const commands = [ascii([
        'SIZE 110 mm,26.924 mm',
        'GAP 3.1 mm,0 mm',
        'DIRECTION 1',
        'REFERENCE 0,0',
        'CLS',
    ].join('\r\n') + '\r\n')];

    pageItems.forEach((unused, index) => {
        const column = index % 3;
        const row = type === 2 ? Math.floor(index / 3) : 0;
        const top = centeredTop + (row * dots(10.245));
        commands.push(...(type === 2
            ? halfLabel(labelXs[column], top, payload)
            : fullLabel(labelXs[column], top, payload, type)));
    });

    commands.push(ascii('PRINT 1,1\r\n'));
    return concatBytes(commands);
}

function buildPrintData(payload) {
    const type = Number(payload.type);
    const quantity = Number(payload.quantity);
    const perPage = type === 2 ? 6 : 3;
    const data = [];

    for (let offset = 0; offset < quantity; offset += perPage) {
        const count = Math.min(perPage, quantity - offset);
        data.push({
            type: 'raw',
            format: 'command',
            flavor: 'hex',
            data: buildPage(payload, Array(count).fill(null), type),
        });
    }

    return data;
}

async function connect() {
    if (!qz.websocket.isActive()) {
        await qz.websocket.connect({ retries: 2, delay: 1 });
    }
}

async function listPrinters() {
    await connect();
    const printers = await qz.printers.find();
    return printers.filter((name) => /gainscha|ge-?2406|gs-?2406/i.test(name));
}

function getPrinter() {
    return localStorage.getItem(STORAGE_PRINTER) || '';
}

function setPrinter(name) {
    localStorage.setItem(STORAGE_PRINTER, name);
}

function getMode() {
    return localStorage.getItem(STORAGE_MODE) || 'browser';
}

function setMode(mode) {
    localStorage.setItem(STORAGE_MODE, mode === 'direct' ? 'direct' : 'browser');
}

async function print(payload) {
    const printer = getPrinter();
    if (!printer) throw new Error('Barcode printer မရွေးရသေးပါ။');

    await connect();
    const exactPrinter = await qz.printers.find(printer);
    const config = qz.configs.create(exactPrinter, { encoding: 'UTF-8', copies: 1 });
    await qz.print(config, buildPrintData(payload));
}

async function testPrint(printer) {
    setPrinter(printer);
    await print({
        name: 'RECEIVED GOODS TEST',
        barcode: '8850106476627',
        unit: 'PC',
        printedAt: new Date().toLocaleString('en-GB'),
        quantity: 3,
        type: 1,
    });
}

window.receivedGoodsQz = {
    connect,
    listPrinters,
    getPrinter,
    setPrinter,
    getMode,
    setMode,
    print,
    testPrint,
};
