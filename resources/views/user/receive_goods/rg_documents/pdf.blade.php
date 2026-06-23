<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Goods Receive</title>
    <style>
        @page {
            margin: 10mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.15;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right !important;
        }

        .bold {
            font-weight: bold;
        }

        .company-header td {
            vertical-align: top;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
        }

        .page-no {
            text-align: right;
            white-space: nowrap;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0;
        }

        .detail-table {
            margin-top: 5px;
        }

        .detail-table td {
            padding: 1px 2px;
            vertical-align: top;
        }

        .label {
            width: 90px;
            white-space: nowrap;
        }

        .value {
            padding-left: 3px;
        }

        .product-table {
            margin-top: 8px;
            min-height: 600px;
        }

        .product-table th {
            border-top: 1px dotted #000;
            border-bottom: 1px dotted #000;
            padding: 3px;
            text-align: left;
            font-weight: bold;
        }

        .product-table td {
            padding: 3px;
        }

        .qty {
            text-align: right;
        }

        .total-row td {
            border-top: 1px solid #000;
            font-weight: bold;
            padding-top: 5px;
        }

        .remark-section {
            margin-top: 8px;
        }

        .remark-title {
            font-weight: bold;
            display: inline-block;
            width: 55px;
        }

        .signature-table {
            margin-top: 25px;
            border: 1px dotted #000;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            border-right: 1px dotted #000;


        }

        .signature-line {
            height: 25px;
        }

        .date-line {
            margin-top: 3px;
        }

        .footer {
            margin-top: 10px;
        }

        .footer td {
            vertical-align: top;
        }

        .user-label{
            border-bottom: 1px dotted #000;
            margin: 0px 20px;
            padding: 0px 40px;
        }
    </style>
</head>
<body>
{{-- Header --}}
<table class="header">
    <tr>
        <td width="15%">
            <!-- <img src="{{ public_path('image/logo.png') }}"
                 style="width:80px;"> -->
            <div class="logo">
                <img src="{{ public_path('image/background_img/finallogo.png') }}" width="150px" alt="">
                <!-- <div class="logo_content">
                    <span>PRO 1 Global Home Center</span>
                </div> -->
            </div>
        </td>

        <td width="70%" class="text-center">
            <h2 style="margin:0;">
                PRO 1 GLOBAL COMPANY LIMITED ({{ $receive_good_document->branch->branch_name }})
            </h2>

            <!-- <div>
                Ma.8/6, Theik Pan Rd, Bet: 62 & 63 St.,
                Chanmyathazi Tsp., Mandalay, Myanmar
            </div> -->
            <div>
                No.76, Lanthit Street, Near Arleing Ngar Sint Pagoda,<brs>
                    Insein Township, Yangon, Myanmar
            </div>

            <table style="margin-top:8px;">
                <tr>
                    <td width="33%">Tel.</td>
                    <td width="33%">Fax</td>
                    <td width="33%">Tax ID</td>
                </tr>
            </table>
        </td>

        <td width="15%" class="text-right">
            Page 1/1
        </td>
    </tr>
</table>

<h2 class="text-center">
    Goods Receive
</h2>

{{-- DETAIL --}}
<table class="detail-table">

    <tr>
        <td class="label">Vendor Code</td>
        <td class="value">: {{ $receive_good_document->vendor_code }}</td>

        <td class="label">Doc.No.</td>
        <td class="value">: {{ $receive_good_document->receive_good_files->first()->file }}</td>
    </tr>

    <tr>
        <td class="label">Vendor Name</td>
        <td class="value">: {{ $receive_good_document?->vendor?->vendor_name }}</td>

        <td class="label">Doc.Date</td>
        <td class="value">: {{-- 29/05/2026 --}} {{ $receive_good_document->created_at->format('Y-m-d') }}</td>
    </tr>

    <tr>
        <td class="label">Address</td>
        <td class="value">
            : {{ $receive_good_document?->vendor?->vendor_address }}
        </td>

        <td class="label">PO No.</td>
        <td class="value">: {{ $receive_good_document->po_no }}</td>
    </tr>

    <tr>
        <td class="label">Tel.</td>
        <td class="value">: {{ $receive_good_document?->vendor?->vendor_ph }}</td>

        <td class="label">Delivery Note</td>
        <td class="value">: {{ $receive_good_document->delivery_note }}</td>
    </tr>

    <tr>
        <td class="label">Fax</td>
        <td class="value">: {{-- 951-245401 --}}</td>

        <td class="label">Delivery Date</td>
        <td class="value">: {{ $receive_good_document->delivery_date }}</td>
    </tr>

    <tr>
        <td></td>
        <td></td>

        <td class="label">Credit Term</td>
        <td class="value">: {{ $receive_good_document->document->creditday }}</td>
    </tr>

</table>

{{-- PRODUCT TABLE --}}
<table class="product-table">

    <thead>
        <tr>
            <th width="6%">No.</th>
            <th width="20%">Product Code</th>
            <th>Product Name</th>
            <th width="8%">Unit</th>
            <th width="10%" class="text-right">Qty</th>
            <th width="15%">Remark</th>
        </tr>
    </thead>

    <tbody>

        <!-- <tr>
            <td>1</td>
            <td>1101020016005</td>
            <td>TIGER Shear 700</td>
            <td>PC</td>
            <td class="qty">24.00</td>
            <td></td>
        </tr>

        <tr>
            <td>2</td>
            <td>1101020016006</td>
            <td>TIGER Shear 702</td>
            <td>PC</td>
            <td class="qty">24.00</td>
            <td></td>
        </tr> -->

        @foreach($receive_good_document->receive_good_products as $idx=>$product)
            <tr>
                <td>{{ ++$idx }}</td>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->unit }}</td>
                <td class="qty text-right">{{ $product->gr_qty }}</td>
                <td></td>
            </tr>
        @endforeach

        <tr class="total-row">
            <td colspan="4" class="text-right">
                Total Quantity :
            </td>

            <td class="qty">
                {{ $receive_good_document->receive_good_products->sum('gr_qty') }}
            </td>

            <td></td>
        </tr>

    </tbody>

</table>

{{-- REMARK --}}
<div class="remark-section">
    <span class="remark-title">Remark :</span>

        {{ $receive_good_document->remark }}
</div>

{{-- SIGNATURE --}}
<table class="signature-table">

    <tr>
        <td class="bold">Record By</td>
        <td class="bold">Received By</td>
        <td class="bold">Checked By</td>
    </tr>

    <tr>
        <td><span  class="user-label">{{ $receive_good_document->user->name }}</span></td>

        @php
            $grByEmployee = $employees->get($receive_good_document->gr_by);
        @endphp
        @if($grByEmployee?->employeename)
            <td><span class="user-label">{{ $grByEmployee?->employeename }}</span></td>
        @else
            <td>.........,..........,.........,.........</td>
        @endif

        <td>.........,..........,.........,.........</td>
    </tr>

    <tr>
        <td class="signature-line">
            ........./........../.........
        </td>

        <td class="signature-line">
            ........./........../.........
        </td>

        <td class="signature-line">
            ........./........../.........
        </td>
    </tr>

</table>

{{-- FOOTER --}}
<table class="footer text-right">
    <tr>
        <td width="33%"></td>

        <td width="33%" >
            Print by : {{ $userdata->name }}<brs>
        </td>
        <td width="33%">
            {{ now() }}
        </td>
    </tr>
</table>


</body>
</html>