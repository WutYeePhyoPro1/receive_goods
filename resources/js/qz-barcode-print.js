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

function splitName(value, maxChars, maxLines) {
    const words = clean(value).split(/\s+/).filter(Boolean);
    const lines = [];

    for (const word of words) {
        if (lines.length === 0 || `${lines[lines.length - 1]} ${word}`.trim().length > maxChars) {
            if (lines.length === maxLines) break;
            lines.push(word.slice(0, maxChars));
        } else {
            lines[lines.length - 1] += ` ${word}`;
        }
    }

    return lines.length ? lines : [''];
}

function text(x, y, value, font = '1', xScale = 1, yScale = 1) {
    return `TEXT ${x},${y},"${font}",0,${xScale},${yScale},"${clean(value)}"`;
}

function centeredText(labelX, y, value, fontWidth = 8, font = '1') {
    const labelWidth = dots(34);
    const estimatedWidth = clean(value).length * fontWidth;
    const x = labelX + Math.max(dots(2), Math.round((labelWidth - estimatedWidth) / 2));
    return text(x, y, value, font);
}

function barcode(labelX, y, height, value) {
    // Two printer dots per narrow module keeps Code 128 sharp at 203 DPI.
    return `BARCODE ${labelX + dots(4)},${y},"128",${height},0,0,2,2,"${clean(value)}"`;
}

function fullLabel(labelX, top, payload, type) {
    const lines = splitName(payload.name, 22, 2);
    const commands = [];

    lines.forEach((line, index) => {
        commands.push(text(labelX + dots(2.5), top + 4 + (index * 18), line, '1'));
    });

    const barcodeY = top + (lines.length > 1 ? 43 : 28);
    const barcodeHeight = type === 3 ? 45 : 55;
    commands.push(barcode(labelX, barcodeY, barcodeHeight, payload.barcode));
    commands.push(centeredText(labelX, barcodeY + barcodeHeight + 3, payload.barcode));
    commands.push(text(labelX + dots(30), barcodeY + barcodeHeight + 3, payload.unit, '1'));

    if (type === 3) {
        const boxY = barcodeY + barcodeHeight + 20;
        commands.push(`BOX ${labelX + dots(2.5)},${boxY},${labelX + dots(19)},${boxY + dots(3.5)},2`);
        commands.push(`BOX ${labelX + dots(20)},${boxY},${labelX + dots(23)},${boxY + dots(3)},2`);
    }

    commands.push(centeredText(labelX, top + dots(17.8), payload.printedAt, 8, '1'));
    return commands;
}

function halfLabel(labelX, top, payload) {
    const name = splitName(payload.name, 24, 1)[0];
    return [
        text(labelX + dots(2.5), top + 2, name, '1'),
        barcode(labelX, top + 20, 30, payload.barcode),
        centeredText(labelX, top + 52, payload.barcode, 8, '1'),
        text(labelX + dots(30), top + 52, payload.unit, '1'),
        centeredText(labelX, top + 65, payload.printedAt, 8, '1'),
    ];
}

function buildPage(payload, pageItems, type) {
    const labelXs = [0, dots(37.1), dots(74.2)];
    const centeredTop = dots(3.155);
    const commands = [
        'SIZE 110 mm,26.924 mm',
        'GAP 3.1 mm,0 mm',
        'DIRECTION 1',
        'REFERENCE 0,0',
        'CLS',
    ];

    pageItems.forEach((unused, index) => {
        const column = index % 3;
        const row = type === 2 ? Math.floor(index / 3) : 0;
        const top = centeredTop + (row * dots(10.245));
        commands.push(...(type === 2
            ? halfLabel(labelXs[column], top, payload)
            : fullLabel(labelXs[column], top, payload, type)));
    });

    commands.push('PRINT 1,1');
    return `${commands.join('\r\n')}\r\n`;
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
            format: 'plain',
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
