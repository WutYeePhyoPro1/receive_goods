<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    @php
        $sheetWidth = 112.0;
        $labelWidth = (float) $template->layout['width'];
        $labelHeight = (float) $template->layout['height'];
        $rowsPerSheet = $labelHeight <= 12.5 ? 2 : 1;
        $labelsPerSheet = 3 * $rowsPerSheet;
        $verticalSpace = 28 - $labelHeight * $rowsPerSheet;
        $verticalMarginTop = $verticalSpace / 2;
        $verticalMarginBottom = $verticalSpace / 2;
        $sheetHeight = 28.0;
        $labelWidthCss = number_format($labelWidth, 3, '.', '');
        $labelHeightCss = number_format($labelHeight, 3, '.', '');
        $rowsPerSheetCss = $rowsPerSheet;
        $sheetWidthCss = number_format($sheetWidth, 3, '.', '');
        $sheetHeightCss = number_format($sheetHeight, 3, '.', '');
    @endphp
    <style>
        @page {
            size: {{ $sheetWidthCss }}mm {{ $sheetHeightCss }}mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        .label-sheet {
            display: grid;
            grid-template-columns: repeat(3, {{ $labelWidthCss }}mm);
            grid-template-rows: repeat({{ $rowsPerSheetCss }}, {{ $labelHeightCss }}mm);
            column-gap: 4mm;
            justify-content: space-between;
            padding: {{ number_format($verticalMarginTop, 3, '.', '') }}mm 0 {{ number_format($verticalMarginBottom, 3, '.', '') }}mm;
            width: {{ $sheetWidthCss }}mm;
            height: {{ $sheetHeightCss }}mm;
            overflow: hidden;
            page-break-after: always;
        }

        .label-sheet.last {
            page-break-after: auto;
        }

        .label-cell {
            position: relative;
            width: {{ $labelWidthCss }}mm;
            height: {{ $labelHeightCss }}mm;
            min-width: 0;
            min-height: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .label-cell.bar-21-label {
            overflow: visible;
        }

        .label-cell.bar-11-label {
            overflow: visible;
        }

        .template-layout {
            position: relative;
            width: {{ $labelWidthCss }}mm;
            height: {{ $labelHeightCss }}mm;
        }

        .bar-11-label .template-layout {
            transform: translateY(-1mm);
        }

        .bar-11-second-label .template-layout {
            transform: translate(1mm, -1mm);
        }

        .bar-11-third-label .template-layout {
            transform: translate(-2mm, -1mm);
        }

        .bar-21-label .template-layout {
            transform: translateY(-1mm);
        }

        .bar-21-first-column-label .template-layout {
            transform: translate(3mm, -1mm);
        }

        .bar-21-second-column-label .template-layout {
            transform: translate(1mm, -1mm);
        }

        .bar-21-third-column-label .template-layout {
            transform: translate(-2mm, -1mm);
        }

        .bar-11-first-label .element:not(.rectangle-element) {
            transform: translateX(3mm);
        }

        .bar-11-first-label .rectangle-element {
            transform: translateX(3mm);
        }

        .element {
            position: absolute;
            overflow: hidden;
            box-sizing: border-box;
        }

        .rectangle-element {
            border: 1px solid #000;
        }

        .text-element {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .text-element span {
            width: 100%;
        }

        .barcode-element {
            overflow: visible;
        }

        .barcode-element svg {
            position: absolute;
            top: 0;
            left: 50%;
            width: 100%;
            max-width: 100%;
            height: 100%;
            transform: translateX(-50%);
            shape-rendering: crispEdges;
        }

        @media screen {
            body {
                background: #d1d5db;
            }

            .label-sheet {
                background: #fff;
                margin: 12px auto;
                box-shadow: 0 1px 5px rgba(0, 0, 0, .3);
            }
        }
    </style>
</head>

<body>
    @for ($sheetIndex = 0; $sheetIndex < ceil($quantity / $labelsPerSheet); $sheetIndex++)
        <div class="label-sheet {{ $sheetIndex === ceil($quantity / $labelsPerSheet) - 1 ? 'last' : '' }}">
            @for ($slotIndex = 0; $slotIndex < $labelsPerSheet; $slotIndex++)
                @if ($sheetIndex * $labelsPerSheet + $slotIndex < $quantity)
            <div class="label-cell {{ $template->name === 'Bar 1.1' ? 'bar-11-label' : '' }} {{ $template->name === 'Bar 1.1' && $slotIndex === 0 ? 'bar-11-first-label' : '' }} {{ $template->name === 'Bar 1.1' && $slotIndex === 1 ? 'bar-11-second-label' : '' }} {{ $template->name === 'Bar 1.1' && $slotIndex === 2 ? 'bar-11-third-label' : '' }} {{ $template->name === 'Bar 2.1' ? 'bar-21-label' : '' }} {{ $template->name === 'Bar 2.1' && in_array($slotIndex, [0, 3], true) ? 'bar-21-first-column-label' : '' }} {{ $template->name === 'Bar 2.1' && in_array($slotIndex, [1, 4], true) ? 'bar-21-second-column-label' : '' }} {{ $template->name === 'Bar 2.1' && in_array($slotIndex, [2, 5], true) ? 'bar-21-third-column-label' : '' }}">
                        <div class="template-layout">
                            @foreach ($template->layout['elements'] as $element)
                                @php
                                    $elementX = (float) $element['x'];
                                    $elementWidth = (float) $element['width'];
                                    $elementY = (float) $element['y'];
                                    $elementHeight = (float) $element['height'];
                                    $fontSizeValue = (float) ($element['fontSize'] ?? 0);
                                    $left = number_format(
                                        $labelWidth * $elementX / 100,
                                        3,
                                        '.',
                                        '',
                                    );
                                    $top = number_format($elementY, 3, '.', '');
                                    $width = number_format($elementWidth, 3, '.', '');
                                    $height = number_format($elementHeight, 3, '.', '');
                                    $style = "left:{$left}mm;top:{$top}%;width:{$width}%;height:{$height}%;";
                                @endphp
                                @if ($element['type'] === 'barcode')
                                    @php
                                        $barcodeTop = number_format(
                                            ($labelHeight * (float) $element['y']) / 100,
                                            3,
                                            '.',
                                            '',
                                        );
                                        $barcodeWidth = number_format(
                                            ($labelWidth * (float) $element['width']) / 100,
                                            3,
                                            '.',
                                            '',
                                        );
                                        $barcodeHeight = number_format(
                                            ($labelHeight * (float) $element['height']) / 100,
                                            3,
                                            '.',
                                            '',
                                        );
                                    @endphp
                                    <div class="element barcode-element"
                                        style="left: {{ $left }}mm; top: {{ $barcodeTop }}mm; width: {{ $barcodeWidth }}mm; height: {{ $barcodeHeight }}mm; padding: 0;">
                                        {!! $barcodeHtml !!}</div>
                                @elseif ($element['type'] === 'rectangle')
                                    <div class="element rectangle-element" style="{{ $style }}"></div>
                                @else
                                    @php
                                        $text = match ($element['type']) {
                                            'productName' => $product->supplier_name,
                                            'barcodeValue' => $product->bar_code,
                                            'date' => $printedAt->format('d/m/Y'),
                                            'time' => $printedAt->format('h:i A'),
                                            default => $element['text'],
                                        };
                                        $fontSize = number_format($fontSizeValue, 2, '.', '');
                                        $verticalAlign = match ($element['verticalAlign'] ?? 'top') {
                                            'center' => 'center',
                                            'bottom' => 'flex-end',
                                            default => 'flex-start',
                                        };
                                        $textStyle =
                                            $style .
                                            "display:flex;flex-direction:column;justify-content:{$verticalAlign};" .
                                            "font-size:{$fontSize}pt;text-align:{$element['align']};" .
                                            "font-weight:{$element['fontWeight']};";
                                    @endphp
                                    <div class="element text-element" style="{{ $textStyle }}"
                                        data-fit-font="{{ $fontSize }}"><span>{{ $text }}</span></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    @endfor
    @if ($autoPrint ?? false)
        <script>
            function fitTextElements() {
                document.querySelectorAll('[data-fit-font]').forEach((element) => {
                    const initialSize = Number(element.dataset.fitFont);
                    const content = element.firstElementChild;
                    let low = 10;
                    let high = Math.max(low, Math.floor(initialSize * 10));

                    while (low < high) {
                        const size = Math.ceil((low + high) / 2);
                        element.style.fontSize = `${size / 10}pt`;
                        if (content &&
                            content.scrollWidth <= element.clientWidth &&
                            content.scrollHeight <= element.clientHeight) {
                            low = size;
                        } else {
                            high = size - 1;
                        }
                    }

                    element.style.fontSize = `${low / 10}pt`;
                });
            }

            window.addEventListener('load', async function() {
                if (document.fonts?.ready) await document.fonts.ready;
                fitTextElements();
                window.setTimeout(function() {
                    window.focus();
                    window.print();
                }, 250);
            });
        </script>
    @endif
</body>

</html>
