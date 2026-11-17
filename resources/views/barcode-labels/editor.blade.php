<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Barcode Label Designer</title>

    @vite(['resources/js/app.js', 'resources/js/barcode-label.js'])

    <style>
        .designer {
            display: grid;
            grid-template-columns: 340px minmax(0, 1fr);
            gap: 24px;
            padding: 24px;
        }

        .settings-panel {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .settings-panel label {
            display: block;
            font-size: 13px;
            margin: 12px 0 5px;
        }

        .settings-panel input,
        .settings-panel select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .setting-group {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
        }

        .preview-panel {
            background: #f1f3f5;
            padding: 24px;
            overflow: auto;
            border-radius: 8px;
        }

        .label-sheet {
            position: relative;
            background: white;
            border: 1px dashed #aaa;
            margin: 30px auto;
            box-sizing: border-box;
            overflow: hidden;
        }

        .label-element {
            position: absolute;
            box-sizing: border-box;
            white-space: nowrap;
            line-height: 1.15;
        }

        #barcodePreview {
            position: absolute;
            overflow: hidden;
        }

        #barcodePreview svg {
            display: block;
            width: 100%;
            height: 100%;
            preserveAspectRatio: xMidYMid meet;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            cursor: pointer;
            padding: 10px 16px;
        }

        .setting-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        @media (max-width: 900px) {
            .designer {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .settings-panel,
            .preview-heading,
            .no-print {
                display: none !important;
            }

            .designer,
            .preview-panel {
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                background: white !important;
                overflow: visible !important;
            }

            .label-sheet {
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>

<body>

    @php
        $fieldNames = [
            'product_name' => 'Product Name',
            'sku' => 'SKU',
            'pc' => 'PC / Quantity',
            'date' => 'Date',
            'time' => 'Time',
        ];

        $sample = [
            'product_name' => 'Premium Coffee Beans',
            'sku' => 'COF-00125',
            'barcode' => 'COF00125',
            'pc' => '00125',
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i'),
        ];
    @endphp

    <div class="designer">

        <form class="settings-panel" method="POST"
            action="{{ $template ? route('barcode-labels.update', $template) : route('barcode-labels.store') }}"
            id="designerForm">

            @csrf

            @if ($template)
                @method('PUT')
            @endif

            <h2>Label Designer</h2>

            @if (session('success'))
                <p style="color:green">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div style="color:red">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <label>Template Name</label>
            <input type="text" name="name" value="{{ old('name', $template?->name ?? 'Default Label') }}"
                required>

            <h3>Paper Settings</h3>

            <div class="setting-row">
                <div>
                    <label>Width (mm)</label>
                    <input type="number" id="labelWidth" name="width_mm"
                        value="{{ old('width_mm', $template?->width_mm ?? 50) }}" min="10" max="300"
                        step="0.1">
                </div>

                <div>
                    <label>Height (mm)</label>
                    <input type="number" id="labelHeight" name="height_mm"
                        value="{{ old('height_mm', $template?->height_mm ?? 30) }}" min="10" max="300"
                        step="0.1">
                </div>
            </div>

            <h3>Sample Product Data</h3>

            <label>Product Name</label>
            <input id="dataProduct" value="{{ $sample['product_name'] }}">

            <label>SKU</label>
            <input id="dataSku" value="{{ $sample['sku'] }}">

            <label>Barcode Value</label>
            <input id="dataBarcode" value="{{ $sample['barcode'] }}">

            <label>PC / Quantity</label>
            <input id="dataPc" value="{{ $sample['pc'] }}">

            <label>Date</label>
            <input id="dataDate" value="{{ $sample['date'] }}">

            <label>Time</label>
            <input id="dataTime" value="{{ $sample['time'] }}">

            <h3>Barcode Settings</h3>

            <div class="setting-group">
                <label>Barcode Type</label>

                <select data-setting="barcode.type">
                    <option value="CODE128">Code 128</option>
                </select>

                <div class="setting-row">
                    <div>
                        <label>X Position (mm)</label>
                        <input type="number" data-setting="barcode.x" value="{{ $settings['barcode']['x'] }}">
                    </div>

                    <div>
                        <label>Y Position (mm)</label>
                        <input type="number" data-setting="barcode.y" value="{{ $settings['barcode']['y'] }}">
                    </div>
                </div>

                <div class="setting-row">
                    <div>
                        <label>Width (mm)</label>
                        <input type="number" data-setting="barcode.width_mm"
                            value="{{ $settings['barcode']['width_mm'] }}">
                    </div>

                    <div>
                        <label>Height (mm)</label>
                        <input type="number" data-setting="barcode.height_mm"
                            value="{{ $settings['barcode']['height_mm'] }}">
                    </div>
                </div>
            </div>

            <h3>Text Position Settings</h3>

            @foreach ($fieldNames as $key => $label)
                @php
                    $field = $settings['fields'][$key];
                @endphp

                <div class="setting-group">

                    <strong>{{ $label }}</strong>

                    <label>
                        <input type="checkbox" data-setting="fields.{{ $key }}.enabled" style="width:auto"
                            {{ $field['enabled'] ? 'checked' : '' }}>
                        Show field
                    </label>

                    <div class="setting-row">
                        <div>
                            <label>X (mm)</label>
                            <input type="number" step="0.1" data-setting="fields.{{ $key }}.x"
                                value="{{ $field['x'] }}">
                        </div>

                        <div>
                            <label>Y (mm)</label>
                            <input type="number" step="0.1" data-setting="fields.{{ $key }}.y"
                                value="{{ $field['y'] }}">
                        </div>
                    </div>

                    <label>Font Size (px)</label>
                    <input type="number" data-setting="fields.{{ $key }}.font_size"
                        value="{{ $field['font_size'] }}">

                    <label>Alignment</label>
                    <select data-setting="fields.{{ $key }}.align">
                        <option value="left" @selected($field['align'] === 'left')>
                            Left
                        </option>

                        <option value="center" @selected($field['align'] === 'center')>
                            Center
                        </option>

                        <option value="right" @selected($field['align'] === 'right')>
                            Right
                        </option>
                    </select>

                    <label>
                        <input type="checkbox" data-setting="fields.{{ $key }}.bold" style="width:auto"
                            {{ $field['bold'] ? 'checked' : '' }}>
                        Bold text
                    </label>

                </div>
            @endforeach

            <input type="hidden" name="settings" id="settingsJson">

            <div class="actions no-print">
                <button type="submit">Save Template</button>
                <button type="button" onclick="printLabel()">
                    Print Label
                </button>
            </div>

        </form>

        <div class="preview-panel">

            <h2 class="preview-heading">Live Preview</h2>

            <div id="labelSheet" class="label-sheet">

                <div id="barcodePreview"></div>

                @foreach ($fieldNames as $key => $label)
                    <div id="preview-{{ $key }}" class="label-element"></div>
                @endforeach

            </div>

            <p class="preview-heading">
                Adjust the settings to update the label preview.
            </p>

        </div>

    </div>

    <script>
        window.barcodeLabelInitialSettings =
            @json($settings);
    </script>

</body>

</html>
