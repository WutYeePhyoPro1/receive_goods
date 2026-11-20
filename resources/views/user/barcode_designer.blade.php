@extends('layout.layout')

@section('css')
    <style>
        #barcode-designer {
            --designer-ink: #172033;
            --designer-muted: #64748b;
            --designer-border: #e2e8f0;
            color: var(--designer-ink);
            font-family: Arial, sans-serif;
            padding: 18px 20px 34px;
        }

        #barcode-designer * {
            box-sizing: border-box;
        }

        #barcode-designer .designer-header,
        #barcode-designer .designer-actions,
        #barcode-designer .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        #barcode-designer .designer-header {
            margin-bottom: 20px;
        }

        #barcode-designer h1 {
            margin: 0;
            font-size: 25px;
            font-weight: 700;
        }

        #barcode-designer .subtitle {
            color: var(--designer-muted);
            margin: 5px 0 0;
            font-size: 14px;
        }

        #barcode-designer .designer-actions {
            flex-wrap: wrap;
        }

        #barcode-designer button {
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
        }

        #barcode-designer .action-button {
            padding: 10px 15px;
            font-weight: 600;
        }

        #barcode-designer .print-button {
            background: #f59e0b;
            color: #fff;
        }

        #barcode-designer .reset-button {
            background: #f1f5f9;
            color: #334155;
        }

        #barcode-designer .designer-grid {
            display: grid;
            grid-template-columns: minmax(190px, 230px) minmax(300px, 1fr) minmax(210px, 260px);
            gap: 16px;
            align-items: start;
        }

        #barcode-designer .designer-panel {
            background: #fff;
            border: 1px solid var(--designer-border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
        }

        #barcode-designer .panel-heading {
            margin: 0 0 13px;
            font-size: 15px;
            font-weight: 700;
        }

        #barcode-designer .panel-hint {
            color: var(--designer-muted);
            font-size: 12px;
            line-height: 1.5;
            margin: -5px 0 13px;
        }

        #barcode-designer .palette-item {
            width: 100%;
            margin: 0 0 8px;
            padding: 10px 11px;
            border: 1px solid var(--designer-border);
            background: #f8fafc;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 9px;
            text-align: left;
        }

        #barcode-designer .palette-item:hover {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        #barcode-designer .palette-item i {
            color: #d97706;
            font-size: 18px;
        }

        #barcode-designer .canvas-panel {
            min-width: 0;
        }

        #barcode-designer .canvas-toolbar {
            color: var(--designer-muted);
            font-size: 12px;
            margin: -4px 0 12px;
        }

        #barcode-designer .template-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 0 0 14px;
        }

        #barcode-designer .template-control {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
        }

        #barcode-designer .template-control input {
            width: 82px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 7px 8px;
            color: #172033;
            font: inherit;
        }

        #barcode-designer .template-control input:focus {
            outline: 2px solid #fbbf24;
            border-color: #d97706;
        }

        #barcode-designer .canvas-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 22px;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            min-height: 310px;
        }

        #barcode-designer .label-canvas {
            position: relative;
            flex: 0 0 auto;
            overflow: hidden;
            background-color: #fff;
            background-image:
                linear-gradient(to right, rgba(100, 116, 139, .16) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(100, 116, 139, .16) 1px, transparent 1px),
                linear-gradient(to right, transparent calc(50% - .5px), rgba(217, 119, 6, .3) calc(50% - .5px), rgba(217, 119, 6, .3) calc(50% + .5px), transparent calc(50% + .5px)),
                linear-gradient(to bottom, transparent calc(50% - .5px), rgba(217, 119, 6, .3) calc(50% - .5px), rgba(217, 119, 6, .3) calc(50% + .5px), transparent calc(50% + .5px));
            background-size: 10% 10%, 10% 10%, 100% 100%, 100% 100%;
            background-position: 0 0, 0 0, center, center;
            background-repeat: repeat, repeat, no-repeat, no-repeat;
            outline: 1px solid #cbd5e1;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .12);
        }

        #barcode-designer .canvas-empty {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
        }

        #barcode-designer .canvas-empty[hidden] {
            display: none;
        }

        #barcode-designer .canvas-element {
            position: absolute;
            min-width: 0;
            min-height: 0;
            padding: 0;
            overflow: hidden;
            border: 1px solid transparent;
            cursor: grab;
            touch-action: none;
            user-select: none;
            overflow-wrap: anywhere;
        }

        #barcode-designer .canvas-element.is-dragging {
            cursor: grabbing;
            z-index: 2;
            will-change: left, top;
        }

        #barcode-designer .canvas-element:hover {
            border-color: #fbbf24;
        }

        #barcode-designer .canvas-element.is-selected {
            border: 1px dashed #d97706;
            background-color: rgba(255, 251, 235, .35);
        }

        #barcode-designer .canvas-element[data-type="productName"] {
            font-size: 16px;
            font-weight: 700;
        }

        #barcode-designer .canvas-element[data-type="barcode"] {
            padding: 0;
            background: #fff;
        }

        #barcode-designer .canvas-element[data-type="barcode"] svg {
            display: block;
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        #barcode-designer .canvas-element[data-type="barcodeValue"] {
            text-align: center;
            font-family: monospace;
            font-size: 13px;
            letter-spacing: 1px;
        }

        #barcode-designer .canvas-element[data-type="date"],
        #barcode-designer .canvas-element[data-type="time"] {
            font-size: 12px;
        }

        #barcode-designer .canvas-element[data-type="text"] {
            font-size: 14px;
        }

        #barcode-designer .barcode-error {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 8px;
            color: #b91c1c;
            background: #fef2f2;
            font-size: 11px;
            text-align: center;
        }

        #barcode-designer .property-label {
            display: block;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            margin: 14px 0 6px;
        }

        #barcode-designer .property-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 9px 10px;
            color: #172033;
            font: inherit;
        }

        #barcode-designer .property-input:focus {
            outline: 2px solid #fbbf24;
            border-color: #d97706;
        }

        #barcode-designer input[type="range"] {
            width: 100%;
            accent-color: #d97706;
        }

        #barcode-designer .remove-button {
            width: 100%;
            margin-top: 16px;
            padding: 9px;
            color: #b91c1c;
            background: #fef2f2;
            font-weight: 600;
        }

        #barcode-designer .selection-empty {
            color: var(--designer-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        @media (max-width: 1050px) {
            #barcode-designer .designer-grid {
                grid-template-columns: minmax(160px, 210px) minmax(300px, 1fr);
            }

            #barcode-designer .properties-panel {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 700px) {
            #barcode-designer {
                padding: 12px 4px 30px;
            }

            #barcode-designer .designer-header {
                align-items: flex-start;
                flex-direction: column;
            }

            #barcode-designer .designer-grid {
                grid-template-columns: 1fr;
            }

            #barcode-designer .properties-panel {
                grid-column: auto;
            }

            #barcode-designer .palette-list {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 6px;
            }

            #barcode-designer .palette-item {
                margin-bottom: 0;
            }

            #barcode-designer .canvas-wrap {
                min-height: 230px;
                padding: 12px;
            }
        }

    </style>
@endsection

@section('content')
    <main id="barcode-designer">
        <header class="designer-header">
            <div>
                <h1>Barcode Label Designer</h1>
                <p class="subtitle">Drag fields onto the label, arrange them, then print your design.</p>
            </div>
            <div class="designer-actions">
                <button type="button" class="action-button reset-button" id="reset-label">Reset layout</button>
                <button type="button" class="action-button print-button" id="print-label"><i class="bx bx-printer"></i>
                    Print label</button>
            </div>
        </header>

        <div class="designer-grid">
            <section class="designer-panel">
                <h2 class="panel-heading">Elements</h2>
                <p class="panel-hint">Drag an element onto the label or click to add it.</p>
                <div class="palette-list">
                    <button type="button" class="palette-item" draggable="true" data-element-type="productName"><i
                            class="bx bx-package"></i> Product name</button>
                    <button type="button" class="palette-item" draggable="true" data-element-type="barcode"><i
                            class="bx bx-barcode"></i> Barcode (Code 128)</button>
                    <button type="button" class="palette-item" draggable="true" data-element-type="barcodeValue"><i
                            class="bx bx-hash"></i> Barcode value</button>
                    <button type="button" class="palette-item" draggable="true" data-element-type="date"><i
                            class="bx bx-calendar"></i> Date</button>
                    <button type="button" class="palette-item" draggable="true" data-element-type="time"><i
                            class="bx bx-time"></i> Time</button>
                    <button type="button" class="palette-item" draggable="true" data-element-type="text"><i
                            class="bx bx-text"></i> Text</button>
                </div>
            </section>

            <section class="designer-panel canvas-panel">
                <h2 class="panel-heading">Label preview <span id="label-size-readout"
                        style="font-size:12px;font-weight:400;color:#64748b">70 × 42 mm</span></h2>
                <div class="template-controls" aria-label="Label dimensions">
                    <label class="template-control" for="label-width">Width
                        <input id="label-width" type="number" min="10" max="300" step="0.1" value="70"
                            inputmode="decimal">
                        mm
                    </label>
                    <label class="template-control" for="label-height">Height
                        <input id="label-height" type="number" min="10" max="300" step="0.1" value="42"
                            inputmode="decimal">
                        mm
                    </label>
                </div>
                <p class="panel-hint">Element positions and widths scale proportionally with the label dimensions.</p>
                <p class="canvas-toolbar">10% placement grid with center guides. Select an item to edit it; drag to reposition.</p>
                <div class="canvas-wrap">
                    <div class="label-canvas" id="label-canvas" aria-label="Barcode label canvas">
                        <div class="canvas-empty" id="canvas-empty">Drop elements here</div>
                    </div>
                </div>
            </section>

            <section class="designer-panel properties-panel">
                <h2 class="panel-heading">Properties</h2>
                <div id="element-properties" class="selection-empty">Select an element on the label to edit its content and
                    width.</div>
            </section>
        </div>
    </main>
@endsection

@push('js')
    <script>
        (() => {
            const designer = document.getElementById('barcode-designer');
            const canvas = document.getElementById('label-canvas');
            const canvasWrap = canvas.parentElement;
            const properties = document.getElementById('element-properties');
            const emptyMessage = document.getElementById('canvas-empty');
            const widthInput = document.getElementById('label-width');
            const heightInput = document.getElementById('label-height');
            const sizeReadout = document.getElementById('label-size-readout');
            const templateSize = { width: 70, height: 42 };
            const labels = {
                productName: 'Product name',
                barcode: 'Barcode (Code 128)',
                barcodeValue: 'Barcode value',
                date: 'Date',
                time: 'Time',
                text: 'Text',
            };
            const textTypes = ['productName', 'barcodeValue', 'date', 'time', 'text'];
            const defaults = {
                productName: {
                    text: 'Product Name',
                    x: 5,
                    y: 7,
                    width: 90,
                    height: 18,
                    align: 'left',
                    fontSize: 18,
                    fontWeight: 700,
                },
                barcode: {
                    text: '',
                    x: 14,
                    y: 27,
                    width: 55,
                    height: 28
                },
                barcodeValue: {
                    text: '123456789012',
                    x: 15,
                    y: 56,
                    width: 70,
                    height: 12,
                    align: 'center',
                    fontSize: 12,
                    fontWeight: 400,
                },
                date: {
                    text: '',
                    x: 8,
                    y: 73,
                    width: 40,
                    height: 12,
                    align: 'left',
                    fontSize: 10,
                    fontWeight: 400,
                },
                time: {
                    text: '',
                    x: 55,
                    y: 73,
                    width: 38,
                    height: 12,
                    align: 'left',
                    fontSize: 10,
                    fontWeight: 400,
                },
                text: {
                    text: 'Custom text',
                    x: 8,
                    y: 85,
                    width: 84,
                    height: 12,
                    align: 'left',
                    fontSize: 14,
                    fontWeight: 400,
                },
            };
            let elements = [];
            let selectedId = null;
            let nextId = 1;
            let activeDrag = null;
            let dragFrame = null;

            function resizeCanvas() {
                const styles = getComputedStyle(canvasWrap);
                const availableWidth = canvasWrap.clientWidth -
                    parseFloat(styles.paddingLeft) - parseFloat(styles.paddingRight);
                const maxHeight = window.innerWidth <= 700 ? 340 : 420;
                const scale = Math.min(
                    availableWidth / templateSize.width,
                    maxHeight / templateSize.height
                );
                canvas.style.width = `${Math.max(1, templateSize.width * scale)}px`;
                canvas.style.height = `${Math.max(1, templateSize.height * scale)}px`;
                canvas.querySelectorAll('.canvas-element').forEach(node => {
                    const element = elements.find(item => item.id === node.dataset.id);
                    if (element && textTypes.includes(element.type)) {
                        node.style.fontSize = `${element.fontSize * (96 / 72) *
                            (canvas.clientHeight / (templateSize.height * 96 / 25.4))}px`;
                    }
                });
            }

            function updateTemplateSize(event) {
                const input = event.currentTarget;
                const value = input.valueAsNumber;
                if (!input.validity.valid || !Number.isFinite(value)) return;

                const size = input === widthInput ? 'width' : 'height';
                templateSize[size] = Math.min(300, Math.max(10, value));
                sizeReadout.textContent = `${templateSize.width} × ${templateSize.height} mm`;
                resizeCanvas();
            }

            function restoreTemplateInput(event) {
                const input = event.currentTarget;
                if (!input.validity.valid || !Number.isFinite(input.valueAsNumber)) {
                    input.value = String(templateSize[input === widthInput ? 'width' : 'height']);
                }
            }

            [widthInput, heightInput].forEach(input => {
                input.addEventListener('input', updateTemplateSize);
                input.addEventListener('change', restoreTemplateInput);
                input.addEventListener('blur', restoreTemplateInput);
            });
            resizeCanvas();
            if ('ResizeObserver' in window) {
                new ResizeObserver(resizeCanvas).observe(canvasWrap);
            } else {
                window.addEventListener('resize', resizeCanvas);
            }

            function createElement(type, x = defaults[type].x, y = defaults[type].y) {
                const element = {
                    id: String(nextId++),
                    type,
                    ...defaults[type],
                    x,
                    y,
                };
                elements.push(element);
                selectedId = element.id;
                render();
            }

            function currentText(element) {
                if (element.type === 'date') return new Intl.DateTimeFormat(undefined, {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }).format(new Date());
                if (element.type === 'time') return new Intl.DateTimeFormat(undefined, {
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(new Date());
                return element.text;
            }

            function renderCanvas() {
                canvas.querySelectorAll('.canvas-element').forEach(item => item.remove());
                emptyMessage.hidden = elements.length > 0;
                elements.forEach(element => {
                    const node = document.createElement('div');
                    node.className = `canvas-element${element.id === selectedId ? ' is-selected' : ''}`;
                    node.dataset.type = element.type;
                    node.dataset.id = element.id;
                    node.style.left = `${element.x}%`;
                    node.style.top = `${element.y}%`;
                    node.style.width = `${element.width}%`;
                    node.style.height = `${element.height}%`;
                    node.setAttribute('aria-label', labels[element.type]);

                    if (element.type === 'barcode') {
                        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                        svg.setAttribute('preserveAspectRatio', 'none');
                        node.appendChild(svg);
                        try {
                            if (typeof window.JsBarcode !== 'function') throw new Error(
                                'JsBarcode failed to load.');
                            window.JsBarcode(svg, barcodeValue(), {
                                format: 'CODE128',
                                displayValue: false,
                                height: 54,
                                width: 1,
                                margin: 2
                            });
                        } catch (error) {
                            const message = document.createElement('span');
                            message.className = 'barcode-error';
                            message.textContent = error.message || 'Unable to generate barcode.';
                            node.replaceChildren(message);
                        }
                    } else {
                        node.textContent = currentText(element);
                        node.style.fontSize = `${element.fontSize * (96 / 72) *
                            (canvas.clientHeight / (templateSize.height * 96 / 25.4))}px`;
                        node.style.lineHeight = '1.15';
                        node.style.textAlign = element.align;
                        node.style.fontWeight = String(element.fontWeight);
                    }

                    node.addEventListener('click', event => {
                        event.stopPropagation();
                        selectedId = element.id;
                        render();
                    });
                    canvas.appendChild(node);
                });
            }

            function barcodeValue() {
                const valueElement = elements.find(element => element.type === 'barcodeValue');
                return valueElement ? valueElement.text.trim() : '123456789012';
            }

            function renderProperties() {
                const element = elements.find(item => item.id === selectedId);
                if (!element) {
                    properties.className = 'selection-empty';
                    properties.textContent = 'Select an element on the label to edit its content and width.';
                    return;
                }

                properties.className = '';
                properties.replaceChildren();
                const type = document.createElement('div');
                type.textContent = labels[element.type];
                type.style.fontWeight = '700';
                type.style.fontSize = '14px';
                properties.appendChild(type);

                if (['productName', 'barcodeValue', 'text'].includes(element.type)) {
                    const textLabel = document.createElement('label');
                    textLabel.className = 'property-label';
                    textLabel.htmlFor = 'selected-element-text';
                    textLabel.textContent = element.type === 'productName' ? 'Product name' : (element.type ===
                        'barcodeValue' ? 'Barcode value' : 'Text');
                    const input = document.createElement('input');
                    input.className = 'property-input';
                    input.id = 'selected-element-text';
                    input.type = 'text';
                    input.value = element.text;
                    input.addEventListener('input', () => {
                        element.text = input.value;
                        renderCanvas();
                    });
                    properties.append(textLabel, input);
                } else {
                    const hint = document.createElement('p');
                    hint.className = 'panel-hint';
                    hint.style.margin = '10px 0';
                    hint.textContent = element.type === 'barcode' ? 'Code 128 auto uses the barcode value element.' :
                        'This field displays the current local date or time.';
                    properties.appendChild(hint);
                }

                if (textTypes.includes(element.type)) {
                    const alignmentLabel = document.createElement('label');
                    alignmentLabel.className = 'property-label';
                    alignmentLabel.htmlFor = 'selected-element-alignment';
                    alignmentLabel.textContent = 'Text alignment';
                    const alignmentInput = document.createElement('select');
                    alignmentInput.className = 'property-input';
                    alignmentInput.id = 'selected-element-alignment';
                    [
                        ['left', 'Left'],
                        ['center', 'Center'],
                        ['right', 'Right'],
                    ].forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        alignmentInput.appendChild(option);
                    });
                    alignmentInput.value = element.align;
                    alignmentInput.addEventListener('change', () => {
                        element.align = alignmentInput.value;
                        renderCanvas();
                    });
                    properties.append(alignmentLabel, alignmentInput);

                    const fontSizeLabel = document.createElement('label');
                    fontSizeLabel.className = 'property-label';
                    fontSizeLabel.htmlFor = 'selected-element-font-size';
                    fontSizeLabel.textContent = `Font size (${element.fontSize} pt)`;
                    const fontSizeInput = document.createElement('input');
                    fontSizeInput.className = 'property-input';
                    fontSizeInput.id = 'selected-element-font-size';
                    fontSizeInput.type = 'number';
                    fontSizeInput.min = '1';
                    fontSizeInput.max = '72';
                    fontSizeInput.step = '1';
                    fontSizeInput.value = String(element.fontSize);
                    fontSizeInput.addEventListener('input', () => {
                        const value = fontSizeInput.valueAsNumber;
                        if (!fontSizeInput.validity.valid || !Number.isFinite(value)) return;
                        element.fontSize = Math.min(72, Math.max(1, value));
                        fontSizeLabel.textContent = `Font size (${element.fontSize} pt)`;
                        renderCanvas();
                    });
                    fontSizeInput.addEventListener('change', () => {
                        if (!fontSizeInput.validity.valid || !Number.isFinite(fontSizeInput.valueAsNumber)) {
                            fontSizeInput.value = String(element.fontSize);
                        }
                    });
                    properties.append(fontSizeLabel, fontSizeInput);
                }

                if (textTypes.includes(element.type)) {
                    const fontWeightLabel = document.createElement('label');
                    fontWeightLabel.className = 'property-label';
                    fontWeightLabel.htmlFor = 'selected-element-font-weight';
                    fontWeightLabel.textContent = 'Font weight';
                    const fontWeightInput = document.createElement('select');
                    fontWeightInput.className = 'property-input';
                    fontWeightInput.id = 'selected-element-font-weight';
                    [
                        ['300', 'Light'],
                        ['400', 'Normal'],
                        ['500', 'Medium'],
                        ['600', 'Semi-bold'],
                        ['700', 'Bold'],
                        ['800', 'Extra-bold'],
                        ['900', 'Black'],
                    ].forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        fontWeightInput.appendChild(option);
                    });
                    fontWeightInput.value = String(element.fontWeight);
                    fontWeightInput.addEventListener('change', () => {
                        element.fontWeight = Number(fontWeightInput.value);
                        renderCanvas();
                    });
                    properties.append(fontWeightLabel, fontWeightInput);
                }

                const widthLabel = document.createElement('label');
                widthLabel.className = 'property-label';
                widthLabel.htmlFor = 'selected-element-width';
                widthLabel.textContent = `Width (${Math.round(element.width)}% of label)`;
                const widthInput = document.createElement('input');
                widthInput.type = 'range';
                widthInput.min = '5';
                widthInput.max = '100';
                widthInput.value = String(element.width);
                widthInput.id = 'selected-element-width';
                widthInput.addEventListener('input', () => {
                    element.width = Number(widthInput.value);
                    element.x = Math.min(element.x, 100 - element.width);
                    widthLabel.textContent = `Width (${element.width}% of label)`;
                    renderCanvas();
                });
                properties.append(widthLabel, widthInput);

                const heightLabel = document.createElement('label');
                heightLabel.className = 'property-label';
                heightLabel.htmlFor = 'selected-element-height';
                heightLabel.textContent = `Height (${Math.round(element.height)}% of label)`;
                const heightInput = document.createElement('input');
                heightInput.type = 'range';
                heightInput.min = '5';
                heightInput.max = '100';
                heightInput.value = String(element.height);
                heightInput.id = 'selected-element-height';
                heightInput.addEventListener('input', () => {
                    element.height = Number(heightInput.value);
                    element.y = Math.min(element.y, 100 - element.height);
                    heightLabel.textContent = `Height (${element.height}% of label)`;
                    renderCanvas();
                });
                properties.append(heightLabel, heightInput);

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'remove-button';
                removeButton.textContent = 'Remove element';
                removeButton.addEventListener('click', () => {
                    elements = elements.filter(item => item.id !== selectedId);
                    selectedId = null;
                    render();
                });
                properties.appendChild(removeButton);
            }

            function render() {
                renderCanvas();
                renderProperties();
            }

            document.querySelectorAll('[data-element-type]').forEach(button => {
                button.addEventListener('click', () => createElement(button.dataset.elementType));
                button.addEventListener('dragstart', event => {
                    event.dataTransfer.setData('text/plain', `new:${button.dataset.elementType}`);
                    event.dataTransfer.effectAllowed = 'copy';
                });
            });

            function moveElement(drag, clientX, clientY) {
                const bounds = canvas.getBoundingClientRect();
                const maxX = Math.max(0, 100 - drag.element.width);
                const maxY = Math.max(0, 100 - drag.element.height);
                drag.element.x = Math.max(0, Math.min(
                    maxX,
                    drag.startLeft + ((clientX - drag.startX) / bounds.width) * 100
                ));
                drag.element.y = Math.max(0, Math.min(
                    maxY,
                    drag.startTop + ((clientY - drag.startY) / bounds.height) * 100
                ));
                drag.node.style.left = `${drag.element.x}%`;
                drag.node.style.top = `${drag.element.y}%`;
            }

            canvas.addEventListener('pointerdown', event => {
                if (event.button !== 0) return;
                const node = event.target.closest('.canvas-element');
                if (!node) return;

                const element = elements.find(item => item.id === node.dataset.id);
                if (!element) return;

                event.preventDefault();
                selectedId = element.id;
                canvas.querySelectorAll('.canvas-element').forEach(item => {
                    item.classList.toggle('is-selected', item === node);
                });
                node.classList.add('is-dragging');
                renderProperties();

                activeDrag = {
                    pointerId: event.pointerId,
                    element,
                    node,
                    startX: event.clientX,
                    startY: event.clientY,
                    startLeft: element.x,
                    startTop: element.y,
                    lastX: event.clientX,
                    lastY: event.clientY,
                };
                node.setPointerCapture(event.pointerId);
            });

            canvas.addEventListener('pointermove', event => {
                if (!activeDrag || event.pointerId !== activeDrag.pointerId) return;
                activeDrag.lastX = event.clientX;
                activeDrag.lastY = event.clientY;
                if (dragFrame !== null) return;

                dragFrame = requestAnimationFrame(() => {
                    dragFrame = null;
                    if (activeDrag) moveElement(activeDrag, activeDrag.lastX, activeDrag.lastY);
                });
            });

            function finishDrag(event) {
                if (!activeDrag || event.pointerId !== activeDrag.pointerId) return;
                if (dragFrame !== null) {
                    cancelAnimationFrame(dragFrame);
                    dragFrame = null;
                }
                moveElement(activeDrag, event.clientX, event.clientY);
                activeDrag.node.classList.remove('is-dragging');
                activeDrag = null;
            }

            canvas.addEventListener('pointerup', finishDrag);
            canvas.addEventListener('pointercancel', finishDrag);

            canvas.addEventListener('dragover', event => event.preventDefault());
            canvas.addEventListener('drop', event => {
                event.preventDefault();
                const payload = event.dataTransfer.getData('text/plain');
                const bounds = canvas.getBoundingClientRect();
                const xPosition = ((event.clientX - bounds.left) / bounds.width) * 100;
                const yPosition = ((event.clientY - bounds.top) / bounds.height) * 100;
                if (payload.startsWith('new:') && defaults[payload.slice(4)]) {
                    const type = payload.slice(4);
                    createElement(
                        type,
                        Math.max(0, Math.min(100 - defaults[type].width, xPosition - defaults[type].width / 2)),
                        Math.max(0, Math.min(100 - defaults[type].height, yPosition - defaults[type].height / 2))
                    );
                }
            });

            canvas.addEventListener('click', event => {
                if (event.target === canvas || event.target === emptyMessage) {
                    selectedId = null;
                    render();
                }
            });

            document.getElementById('reset-label').addEventListener('click', () => {
                elements = [];
                selectedId = null;
                nextId = 1;
                widthInput.value = '70';
                heightInput.value = '42';
                templateSize.width = 70;
                templateSize.height = 42;
                sizeReadout.textContent = '70 × 42 mm';
                resizeCanvas();
                Object.keys(defaults).forEach(type => elements.push({
                    id: String(nextId++),
                    type,
                    ...defaults[type]
                }));
                render();
            });
            function printLabel() {
                if (canvas.querySelector('.barcode-error')) {
                    window.alert('Fix the barcode value before printing this label.');
                    return;
                }

                const printFrame = document.createElement('iframe');
                printFrame.setAttribute('aria-hidden', 'true');
                printFrame.title = 'Barcode label print preview';
                printFrame.style.cssText =
                    'position:fixed;left:0;top:0;width:1px;height:1px;border:0;opacity:0;pointer-events:none;z-index:-1;';
                document.body.appendChild(printFrame);

                const width = templateSize.width;
                const height = templateSize.height;
                const printDocument = printFrame.contentDocument;
                const printWindow = printFrame.contentWindow;
                if (!printDocument || !printWindow) {
                    printFrame.remove();
                    window.alert('Unable to prepare the label for printing in this browser.');
                    return;
                }

                printDocument.open();
                printDocument.write(`<!doctype html>
                    <html><head><meta charset="utf-8"><title>Barcode label</title>
                    <style>
                        @page { size: ${width}mm ${height}mm; margin: 0; }
                        * { box-sizing: border-box; }
                        html, body { width: ${width}mm; height: ${height}mm; margin: 0; padding: 0; overflow: hidden; }
                        body { color: #000; background: #fff; font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .label-canvas { position: relative; display: block; width: ${width}mm; height: ${height}mm; overflow: hidden; border: 0; box-shadow: none; background: #fff; }
                        .canvas-element { position: absolute; min-width: 0; min-height: 0; padding: 0; overflow: hidden; border: 0; color: #000; font-family: Arial, sans-serif; line-height: 1.15; overflow-wrap: anywhere; }
                        .canvas-element[data-type="barcode"] { background: #fff; }
                        .canvas-element[data-type="barcode"] svg { display: block; width: 100%; height: 100%; overflow: visible; shape-rendering: crispEdges; }
                        .canvas-element[data-type="barcodeValue"] { text-align: center; font-family: monospace; letter-spacing: .25mm; }
                    </style></head><body></body></html>`);
                printDocument.close();

                const printCanvas = canvas.cloneNode(true);
                printCanvas.style.width = `${width}mm`;
                printCanvas.style.height = `${height}mm`;
                printCanvas.style.aspectRatio = 'auto';
                printCanvas.style.border = '0';
                printCanvas.style.boxShadow = 'none';
                printCanvas.querySelector('.canvas-empty')?.remove();
                printCanvas.querySelectorAll('.canvas-element').forEach(node => {
                    node.classList.remove('is-selected', 'is-dragging');
                    node.style.border = '0';
                    node.style.background = 'transparent';
                    const element = elements.find(item => item.id === node.dataset.id);
                    if (element && textTypes.includes(element.type)) {
                        node.style.fontSize = `${element.fontSize}pt`;
                        node.style.textAlign = element.align;
                        node.style.fontWeight = String(element.fontWeight);
                    }
                    node.querySelector('svg')?.setAttribute('preserveAspectRatio', 'none');
                });
                printDocument.body.appendChild(printCanvas);

                let cleaned = false;
                const cleanup = () => {
                    if (cleaned) return;
                    cleaned = true;
                    printFrame.remove();
                };
                printWindow.addEventListener('afterprint', cleanup, { once: true });
                window.setTimeout(() => {
                    try {
                        printWindow.focus();
                        printWindow.print();
                    } catch (error) {
                        console.error('Unable to print barcode label.', error);
                        window.alert('Unable to print the label. Check your browser print settings and try again.');
                        cleanup();
                    }
                }, 100);
                window.setTimeout(cleanup, 120000);
            }

            document.getElementById('print-label').addEventListener('click', printLabel);
            document.addEventListener('keydown', event => {
                if ((event.key === 'Delete' || event.key === 'Backspace') && selectedId && !['INPUT',
                        'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
                    elements = elements.filter(item => item.id !== selectedId);
                    selectedId = null;
                    render();
                }
            });

            Object.keys(defaults).forEach(type => elements.push({
                id: String(nextId++),
                type,
                ...defaults[type]
            }));
            render();
            window.setInterval(() => {
                if (elements.some(element => element.type === 'date' || element.type === 'time'))
            renderCanvas();
            }, 30000);
        })();
    </script>
@endpush
