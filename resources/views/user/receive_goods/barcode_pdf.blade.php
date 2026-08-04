<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: 110mm 26.924mm; margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; color: #000; }
        .sheet { width: 110mm; height: 26.924mm; overflow: hidden; page-break-after: always; }
        .sheet:last-child { page-break-after: auto; }
        table { width: 110mm; height: 26.924mm; table-layout: fixed; border-collapse: collapse; }
        td { width: 36.666mm; padding: 1.2mm 1.5mm; vertical-align: top; overflow: hidden; }
        .bar2 td { height: 13.462mm; padding-top: .7mm; padding-bottom: .5mm; }
        .name { height: 7mm; overflow: hidden; font-size: 7pt; line-height: 8pt; font-weight: 700; }
        .bar2 .name { height: 4mm; font-size: 5.4pt; line-height: 5.8pt; }
        .barcode { width: 31mm; height: 9mm; overflow: hidden; margin: .5mm auto 0; }
        .barcode svg { display: block; width: 31mm !important; height: 9mm !important; }
        .bar2 .barcode { height: 4.2mm; margin-top: 0; }
        .bar2 .barcode svg { height: 4.2mm !important; }
        .code-row { width: 31mm; margin: 0 auto; font-size: 6.5pt; line-height: 7pt; font-weight: 700; white-space: nowrap; }
        .code { display: inline-block; width: 25mm; text-align: center; letter-spacing: .15mm; }
        .unit { float: right; font-size: 5.5pt; }
        .date { margin-top: .5mm; font-size: 5.3pt; line-height: 5.5pt; font-weight: 700; white-space: nowrap; text-align: center; }
        .bar2 .code-row { font-size: 4.6pt; line-height: 5pt; }
        .bar2 .code { width: 25mm; }
        .bar2 .unit, .bar2 .date { font-size: 4.2pt; line-height: 4.4pt; }
        .checks { white-space: nowrap; margin-top: .4mm; }
        .box-large { display: inline-block; width: 13mm; height: 3.5mm; border: .4mm solid #000; }
        .box-small { display: inline-block; width: 3mm; height: 3mm; margin-left: 1mm; border: .4mm solid #000; }
    </style>
</head>
<body>
@php
    $perPage = $type === 2 ? 6 : 3;
    $items = range(1, $quantity);
@endphp
@foreach(array_chunk($items, $perPage) as $page)
    <div class="sheet {{ $type === 2 ? 'bar2' : 'bar' . $type }}">
        <table>
            @foreach(array_chunk($page, 3) as $row)
                <tr>
                    @foreach($row as $unused)
                        <td>
                            <div class="name">{{ \Illuminate\Support\Str::limit($product->supplier_name, 77) }}</div>
                            <div class="barcode">{!! $barcodeSvg !!}</div>
                            <div class="code-row"><span class="code">{{ $product->bar_code }}</span><span class="unit">{{ $product->unit }}</span></div>
                            @if($type === 3)
                                <div class="checks"><span class="box-large"></span><span class="box-small"></span></div>
                            @endif
                            <div class="date">{{ $printedAt }}</div>
                        </td>
                    @endforeach
                    @for($empty = count($row); $empty < 3; $empty++)<td></td>@endfor
                </tr>
            @endforeach
        </table>
    </div>
@endforeach
</body>
</html>
