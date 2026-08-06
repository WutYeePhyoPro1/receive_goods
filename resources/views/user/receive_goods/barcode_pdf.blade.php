<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: 110mm 26.924mm; margin: 0; }
        * { box-sizing: border-box; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; color: #000; }
        .sheet { position: relative; width: 110mm; height: 26.924mm; overflow: hidden; page-break-after: always; }
        .sheet.last { page-break-after: auto; }
        .label { position: absolute; width: 34.6mm; height: 20.49mm; padding: .7mm 1.2mm; overflow: hidden; }
        .bar2 .label { height: 20.49mm; padding-top: .7mm; padding-bottom: .5mm; }
        .name { height: 7mm; overflow: hidden; font-size: 7pt; line-height: 8pt; font-weight: 700; }
        .bar2 .name { height: 5mm; font-size: 5.8pt; line-height: 6.2pt; }
        .barcode { width: 31mm; height: 9mm; overflow: hidden; margin: .5mm auto 0; }
        .barcode > div { margin-left: auto; margin-right: auto; }
        .bar2 .barcode { height: 6mm; margin-top: .2mm; }
        .code-row { width: 31mm; margin: 0 auto; font-size: 6.5pt; line-height: 7pt; font-weight: 700; white-space: nowrap; }
        .code { display: inline-block; width: 25mm; text-align: center; letter-spacing: .15mm; }
        .unit { float: right; font-size: 5.5pt; }
        .date { margin-top: .5mm; font-size: 5.3pt; line-height: 5.5pt; font-weight: 700; white-space: nowrap; text-align: center; }
        .bar2 .code-row { font-size: 5.2pt; line-height: 5.5pt; }
        .bar2 .code { width: 25mm; }
        .bar2 .unit, .bar2 .date { font-size: 4.7pt; line-height: 5pt; }
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
    $perPage = 3;
    $items = range(1, $quantity);
@endphp
@foreach(array_chunk($items, $perPage) as $page)
    <div class="sheet {{ $type === 2 ? 'bar2' : 'bar' . $type }} {{ $loop->last ? 'last' : '' }}">
        @foreach($page as $index => $unused)
            @php
                $left = $index * 37.7;
                $top = 3.217;
            @endphp
            <div class="label" style="left: {{ $left }}mm; top: {{ $top }}mm;">
                <div class="name">{{ \Illuminate\Support\Str::limit($product->supplier_name, 77) }}</div>
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
