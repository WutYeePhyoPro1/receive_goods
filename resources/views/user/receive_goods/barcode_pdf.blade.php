<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: 110mm 26.924mm; margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif; color: #000; }
        .sheet { position: relative; width: 110mm; height: 26.924mm; overflow: hidden; page-break-after: always; }
        .sheet.last { page-break-after: auto; }
        .label { position: absolute; width: 34mm; height: 20.49mm; padding: .7mm 1mm; overflow: hidden; }
        .bar2 .label { height: 10.245mm; padding: .25mm 1mm; }
        .name { height: 7mm; overflow: hidden; font-size: 7pt; line-height: 8pt; font-weight: 500; }
        .bar1 .name { height: 5.2mm; font-size: 6.3pt; line-height: 7pt; }
        .name.long { font-size: 6pt; line-height: 6.5pt; }
        .name.very-long { font-size: 5.2pt; line-height: 5.7pt; }
        .bar2 .name { height: 2.5mm; font-size: 4.2pt; line-height: 4.5pt; }
        .bar2 .name.long { font-size: 3.7pt; line-height: 4pt; }
        .bar2 .name.very-long { font-size: 3.2pt; line-height: 3.5pt; }
        .barcode { width: 31mm; height: 9mm; overflow: hidden; margin: .5mm auto 0; }
        .barcode > div { margin-left: auto; margin-right: auto; }
        .bar1 .barcode { height: 7.5mm; margin-top: .6mm; }
        .bar2 .barcode { height: 3.8mm; margin-top: .1mm; }
        .code-row { width: 31mm; margin: .2mm auto 0; font-size: 5.8pt; line-height: 6.2pt; font-weight: 400; white-space: nowrap; }
        .code { display: inline-block; width: 25mm; text-align: center; letter-spacing: .1mm; }
        .unit { float: right; font-size: 5pt; font-weight: 400; }
        .date { margin-top: .4mm; font-size: 4.8pt; line-height: 5.2pt; font-weight: 400; white-space: nowrap; text-align: center; }
        .bar1 .date { margin-top: .15mm; font-size: 4.2pt; line-height: 4.6pt; }
        .bar2 .code-row { font-size: 4pt; line-height: 4.2pt; }
        .bar2 .code { width: 25mm; }
        .bar2 .unit, .bar2 .date { margin-top: 0; font-size: 3.7pt; line-height: 4pt; }
        .label.first .barcode, .label.first .code-row { margin-left: 0; margin-right: auto; }
        .label.last .barcode, .label.last .code-row { margin-left: auto; margin-right: 0; }
        .bar1 .label.first .barcode, .bar1 .label.first .code-row,
        .bar1 .label.last .barcode, .bar1 .label.last .code-row { margin-left: auto; margin-right: auto; }
        .bar2 .label.last .barcode, .bar2 .label.last .code-row { margin-left: auto; margin-right: auto; }
        .bar3 .name { height: 5mm; font-size: 6pt; line-height: 6.5pt; }
        .bar3 .barcode { height: 7mm; margin-top: .2mm; }
        .bar3 .code-row { font-size: 5.5pt; line-height: 6pt; }
        .bar3 .date { margin-top: .2mm; font-size: 4.5pt; line-height: 5pt; }
        .checks { white-space: nowrap; margin-top: .4mm; }
        .box-large { display: inline-block; width: 13mm; height: 3.5mm; border: .4mm solid #000; }
        .box-small { display: inline-block; width: 3mm; height: 3mm; margin-left: 1mm; border: .4mm solid #000; }
    </style>
</head>
<body>
@php
    $perPage = $type === 2 ? 6 : 3;
    $items = range(1, $quantity);
    $nameLength = \Illuminate\Support\Str::length($product->supplier_name);
    $nameClass = $nameLength > 65 ? 'very-long' : ($nameLength > 45 ? 'long' : '');
@endphp
@foreach(array_chunk($items, $perPage) as $page)
    <div class="sheet {{ $type === 2 ? 'bar2' : 'bar' . $type }} {{ $loop->last ? 'last' : '' }}">
        @foreach($page as $index => $unused)
            @php
                // 34mm sticker + 3.1mm physical die-cut gap. Start at the
                // paper's left edge so the unused 1.8mm remains on the right.
                $column = $index % 3;
                $row = $type === 2 ? intdiv($index, 3) : 0;
                $left = $column * 37.1;
                if ($type === 2 && $column === 2) {
                    $left += 1.5;
                }
                $baseTop = $type === 2 ? 0.3 : ($type === 1 ? 1.5 : 3.217);
                $top = $baseTop + ($row * 10.245);
                $edgeClass = $column === 0 ? 'first' : ($column === 2 ? 'last' : 'middle');
            @endphp
            <div class="label {{ $edgeClass }}" style="left: {{ $left }}mm; top: {{ $top }}mm;">
                <div class="name {{ $nameClass }}">{{ \Illuminate\Support\Str::limit($product->supplier_name, 77) }}</div>
                <div class="barcode">{!! $barcodeHtml !!}</div>
                <div class="code-row"><span class="code">{{ $product->bar_code }}</span><span class="unit">{{ $product->unit }}</span></div>
                @if($type === 3)
                    <div class="checks"><span class="box-large"></span><span class="box-small"></span></div>
                @endif
                <div class="date">{{ $printedAt }}</div>
            </div>
        @endforeach
    </div>
@endforeach
</body>
</html>
