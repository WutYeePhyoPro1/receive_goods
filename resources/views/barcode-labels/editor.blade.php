<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Barcode Label Designer</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .designer-layout {
            display: grid;
            grid-template-columns: 280px minmax(300px, 1fr) 280px;
            gap: 20px;
            padding: 20px;
        }

        .designer-panel {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .designer-panel h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .designer-field {
            margin-bottom: 12px;
        }

        .designer-field label {
            display: block;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .designer-field input,
        .designer-field select {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 7px;
        }

        .designer-button {
            width: 100%;
            padding: 9px;
            border-radius: 5px;
            border: 0;
            margin-bottom: 8px;
            cursor: pointer;
            background: #2563eb;
            color: white;
        }

        .designer-button.secondary {
            background: #555;
        }

        .designer-button.danger {
            background: #dc2626;
        }

        .preview-area {
            position: relative;
            background: #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            overflow: hidden;
            height: calc(100vh - 170px);
            min-height: 400px;
        }

        #labelCanvas {
            background: white;
            border: 1px solid #999;
            box-shadow: 0 2px 8px #0002;
            flex-shrink: 0;
            overflow: hidden;
            position: absolute;
            left: 50%;
            top: 50%;
            transform-origin: center center;
        }

        .label-element {
            position: absolute;
            box-sizing: border-box;
            cursor: move;
            user-select: none;
            touch-action: none;
            border: 1px dashed transparent;
            overflow: visible;
        }

        .label-element.selected {
            border: 1px dashed #2563eb;
        }

        .label-element .element-content {
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .label-element.text-element .element-content {
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: normal;
            line-height: 1.2;
            width: 100%;
            height: 100%;
            overflow: hidden;
            box-sizing: border-box;
        }

        .label-element.barcode-element .element-content svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .resize-handle {
            position: absolute;
            width: 10px;
            height: 10px;
            right: -5px;
            bottom: -5px;
            background: #2563eb;
            border: 1px solid white;
            border-radius: 50%;
            cursor: nwse-resize;
            touch-action: none;
        }

        .element-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .element-list button {
            text-align: left;
            padding: 7px;
            border: 1px solid #ddd;
            background: #f9fafb;
            border-radius: 5px;
            cursor: pointer;
        }

        .element-list button.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .print-only {
            display: none;
        }

        #printLabel {
            display: none;
        }

        @media (max-width: 1000px) {
            .designer-layout {
                grid-template-columns: 1fr;
            }

            .preview-area {
                height: calc(100vh - 170px);
                min-height: 350px;
            }
        }

        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            body > * {
                display: none !important;
            }

            body > #printLabel {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                overflow: hidden !important;
                margin: 0 !important;
                padding: 0 !important;
                border: 0 !important;
                box-shadow: none !important;
                background: white !important;
            }

            .label-element {
                border: none !important;
            }

            .resize-handle {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    @php
        $isEdit = isset($template) && $template;
    @endphp

    <div class="p-4 bg-white border-b flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold">Barcode Label Designer</h1>
            <p class="text-sm text-gray-500">Drag and resize elements on your label.</p>
        </div>

        <a href="{{ route('barcode-labels.index') }}" class="text-blue-600">
            My Templates
        </a>
    </div>

    @if ($errors->any())
        <div class="m-4 p-3 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="m-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="designer-layout">

        {{-- LEFT PANEL --}}
        <div class="designer-panel">
            <h3>Label Settings</h3>

            <form id="templateForm" method="POST"
                action="{{ $isEdit ? route('barcode-labels.update', $template) : route('barcode-labels.store') }}">

                @csrf

                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="designer-field">
                    <label>Template Name</label>
                    <input type="text" name="name" value="{{ old('name', $template->name ?? 'My Label') }}"
                        required>
                </div>

                <div class="designer-field">
                    <label>Label Width (mm)</label>
                    <input type="number" id="labelWidth" name="width_mm" min="10" max="300" step="0.1"
                        value="{{ old('width_mm', $template->width_mm ?? 50) }}" required>
                </div>

                <div class="designer-field">
                    <label>Label Height (mm)</label>
                    <input type="number" id="labelHeight" name="height_mm" min="10" max="300" step="0.1"
                        value="{{ old('height_mm', $template->height_mm ?? 30) }}" required>
                </div>

                <hr class="my-4">

                <h3>Sample Data</h3>

                <div class="designer-field">
                    <label>Product Name</label>
                    <input id="sample_product_name" value="Sample Product">
                </div>

                <div class="designer-field">
                    <label>SKU</label>
                    <input id="sample_sku" value="10001">
                </div>

                <div class="designer-field">
                    <label>Barcode Value</label>
                    <input id="sample_barcode" value="100012345678">
                </div>

                <div class="designer-field">
                    <label>PC / Quantity</label>
                    <input id="sample_quantity" value="PC: 1">
                </div>

                <div class="designer-field">
                    <label>Date</label>
                    <input id="sample_date" value="Date: 2026-11-17">
                </div>

                <div class="designer-field">
                    <label>Time</label>
                    <input id="sample_time" value="Time: 10:30">
                </div>

                <input type="hidden" name="settings" id="settingsInput">

                <hr class="my-4">

                <button type="submit" class="designer-button">
                    Save Template
                </button>

                <button type="button" id="printButton" class="designer-button secondary">
                    Print Label
                </button>
            </form>
        </div>

        {{-- CENTER PREVIEW --}}
        <div>
            <div class="preview-area">
                <div id="labelCanvas"></div>
            </div>

            <p class="text-center text-sm text-gray-500 mt-3">
                Select an element, then drag it to move. Drag the blue corner to resize.
            </p>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="designer-panel">
            <h3>Elements</h3>

            <div class="element-list" id="elementList"></div>

            <button type="button" id="addTextButton" class="designer-button mt-3">
                + Add Text
            </button>

            <hr class="my-4">

            <h3>Selected Element</h3>

            <div id="noSelection" class="text-sm text-gray-500">
                Select an element on the label to edit its properties.
            </div>

            <div id="elementEditor" style="display:none">

                <div class="designer-field">
                    <label>Element Name</label>
                    <input id="editLabel" type="text">
                </div>

                <div class="designer-field">
                    <label>Text / Barcode Value</label>
                    <input id="editText" type="text">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="designer-field">
                        <label>X (mm)</label>
                        <input id="editX" type="number" step="0.1" min="0">
                    </div>

                    <div class="designer-field">
                        <label>Y (mm)</label>
                        <input id="editY" type="number" step="0.1" min="0">
                    </div>

                    <div class="designer-field">
                        <label>Width (mm)</label>
                        <input id="editWidth" type="number" step="0.1" min="1">
                    </div>

                    <div class="designer-field">
                        <label>Height (mm)</label>
                        <input id="editHeight" type="number" step="0.1" min="1">
                    </div>
                </div>

                <div id="textOptions">
                    <div class="designer-field">
                        <label>Font Size (px)</label>
                        <input id="editFontSize" type="number" min="1" max="100">
                    </div>

                    <div class="designer-field">
                        <label>Font Family</label>
                        <select id="editFontFamily">
                            <option value="sans-serif">Sans-serif</option>
                            <option value="serif">Serif</option>
                            <option value="monospace">Monospace</option>
                            <option value="Arial">Arial</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Playfair Display">Playfair Display</option>
                        </select>
                    </div>

                    <div class="designer-field">
                        <label>Alignment</label>
                        <select id="editAlign">
                            <option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                        </select>
                    </div>

                    <div class="designer-field">
                        <label>Font Weight</label>
                        <select id="editFontWeight">
                            <option value="300">Light</option>
                            <option value="400">Normal</option>
                            <option value="500">Medium</option>
                            <option value="600">Semi-bold</option>
                            <option value="700">Bold</option>
                            <option value="800">Extra-bold</option>
                            <option value="900">Black</option>
                        </select>
                    </div>
                </div>

                <div id="barcodeOptions">
                    <div class="designer-field">
                        <label>Bar Thickness</label>
                        <input id="editBarWidth" type="number" min="0.1" max="10" step="0.1">
                    </div>

                    <div class="designer-field">
                        <label>Barcode Format</label>
                        <select id="editFormat">
                            <option value="CODE128">CODE128</option>
                            <option value="CODE39">CODE39</option>
                            <option value="EAN13">EAN13</option>
                            <option value="EAN8">EAN8</option>
                            <option value="UPC">UPC</option>
                            <option value="ITF14">ITF14</option>
                        </select>
                    </div>

                </div>

                <div class="designer-field">
                    <label>
                        <input type="checkbox" id="editVisible" style="width:auto">
                        Visible
                    </label>
                </div>

                <button type="button" id="deleteElementButton" class="designer-button danger">
                    Delete Element
                </button>
            </div>
        </div>
    </div>

    <script>
        window.initialLabelSettings = @json($settings);
    </script>

</body>

</html>
