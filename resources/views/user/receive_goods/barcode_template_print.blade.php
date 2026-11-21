<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    @php
        $labelWidth = (float) $template->layout['width'];
        $labelHeight = (float) $template->layout['height'];
        $sheetWidth = $labelWidth * 3 + 12;
        $sheetHeight = $labelHeight * 2 + 2;
        $labelWidthCss = number_format($labelWidth, 3, '.', '');
        $labelHeightCss = number_format($labelHeight, 3, '.', '');
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
            grid-template-rows: repeat(2, {{ $labelHeightCss }}mm);
            column-gap: 4mm;
            padding: 1mm 2mm;
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
            min-width: 0;
            min-height: 0;
            overflow: hidden;
        }

        .label-cell:first-child,
        .label-cell:nth-child(4) {
            left: 1mm;
        }

        .template-layout {
            position: relative;
            width: {{ $labelWidthCss }}mm;
            height: {{ $labelHeightCss }}mm;
        }

        .element {
            position: absolute;
            overflow: hidden;
            box-sizing: border-box;
        }

        .text-element {
            line-height: 1.15;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .barcode-element svg {
            display: block;
            width: 90%;
            height: 100%;
            max-width: 100%;
            margin-inline: auto;
            shape-rendering: crispEdges;
        }

        .label-cell:nth-child(3) .barcode-element svg,
        .label-cell:nth-child(6) .barcode-element svg {
            transform: translateX(-1mm);
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
    @for ($sheetIndex = 0; $sheetIndex < ceil($quantity / 6); $sheetIndex++)
        <div class="label-sheet {{ $sheetIndex === ceil($quantity / 6) - 1 ? 'last' : '' }}">
            @for ($slotIndex = 0; $slotIndex < 6; $slotIndex++)
                @if ($sheetIndex * 6 + $slotIndex < $quantity)
                    <div class="label-cell">
                        <div class="template-layout">
                            @foreach ($template->layout['elements'] as $element)
                                @php
                                    $left = number_format((float) $element['x'], 3, '.', '');
                                    $top = number_format((float) $element['y'], 3, '.', '');
                                    $width = number_format((float) $element['width'], 3, '.', '');
                                    $height = number_format((float) $element['height'], 3, '.', '');
                                    $style = "left:{$left}%;top:{$top}%;width:{$width}%;height:{$height}%;";
                                @endphp
                                @if ($element['type'] === 'barcode')
                                    <div class="element barcode-element"
                                        style="left: 0; top: {{ $top }}%; width: 100%; height: {{ $height }}%; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        {!! $barcodeHtml !!}</div>
                                @else
                                    @php
                                        $text = match ($element['type']) {
                                            'productName' => $product->supplier_name,
                                            'barcodeValue' => $product->bar_code,
                                            'date' => $printedAt->format('d/m/Y'),
                                            'time' => $printedAt->format('h:i A'),
                                            default => $element['text'],
                                        };
                                        $fontSize = number_format((float) $element['fontSize'], 2, '.', '');
                                        $textStyle =
                                            $style .
                                            "font-size:{$fontSize}pt;text-align:{$element['align']};" .
                                            "font-weight:{$element['fontWeight']};";
                                    @endphp
                                    <div class="element text-element" style="{{ $textStyle }}"
                                        data-fit-font="{{ $fontSize }}">{{ $text }}</div>
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
                    let low = 10;
                    let high = Math.max(low, Math.floor(initialSize * 10));

                    while (low < high) {
                        const size = Math.ceil((low + high) / 2);
                        element.style.fontSize = `${size / 10}pt`;
                        if (element.scrollWidth <= element.clientWidth &&
                            element.scrollHeight <= element.clientHeight) {
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
