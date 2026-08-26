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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
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

        .detail-table {
            margin-top: 5px;
            border-top: 1px dotted #000;
        }

        .detail-table td {
            padding: 1px 2px;
            vertical-align: top;
        }

        .value {
            padding-left: 3px;
        } 

        .product-table {
            margin-top: 8px;
            page-break-inside: auto;
        }

        .product-table thead {
            display: table-header-group;
        }

        .product-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
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

        .product-spacer td {
            padding: 0;
            line-height: 0;
            font-size: 0;
        }

        .summary {
            border-top: 1px dotted #555;
            border-bottom: 1px dotted #555;
        }
        .summary td {
            padding: 5px 3px;
        }
        .sum-label {
            width: 78%;
        }
        .sum-colon {
            width: 3%;
            text-align: center;
        }
        .sum-value {
            text-align: right;
            font-weight: bold;
        }
        .remark td {
            padding: 4px 3px;
        }

        .signatures {
            width: 100%;
            margin-top: 34px;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
        }

        .signature-cell {
            width: 33.33%;
            padding: 5px 6px 4px;
            vertical-align: top;

            border-top: 1px dotted #555;
            border-bottom: 1px dotted #555;
        }

        .signature-left {
            border-left: 1px dotted #555;
            border-right: 1px dotted #555;
        }

        .signature-middle {
            border-right: 1px dotted #555;
        }

        .signature-right {
            border-right: 1px dotted #555;
        }


        /* Inner signature table */
        .signature-info {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
        }

        .signature-info td {
            padding: 0;
            vertical-align: middle;
        }

        .signature-label {
            width: 40%;
            text-align: left;
            white-space: nowrap;
            padding-right: 3px !important;
        }

        .signature-value {
            width: 60%;
            text-align: center;
            white-space: nowrap;
            padding-bottom: 2px !important;
        }

        .signature-name-cell {
            border-bottom: 1px dotted #555;
            padding-bottom: 2px;
        }


        /* Row height */
        .signature-info tr td {
            height: 25px;
        }


        /* Approval note */
        .approval-note {
            width: 100%;
            margin-top: 2px;
            border-collapse: collapse;
        }

        .approval-note td {
            padding: 0;
            border: 0;
            text-align: right;
            font-size: 10px;
            white-space: nowrap;
        }
        .footer {
            margin-top: 5px;
        }

        .myanmarfonts{
            font-family: Tharlon, sans-serif !important;
        }


        .barcode-container {
            text-align: right;
            /* padding: 6px 0px; */
            padding-bottom: 6px;
        }
        .po-barcode {
            width: 55mm;
            height: 6mm;
        }

        /* QR Code (Positioned Right Side like Image) */
        .qrcode-container {
            position: absolute;
            top: 28mm;
            right: 10mm;
        }
        .po-qrcode {
            width: 16mm;
            height: 16mm;
        }
    </style>
</head>
<body>
{{-- Header --}}

<div class="barcode-container">
    <img src="{{ public_path($po_document->barcode_path) }}" class="po-barcode" alt="Barcode">
</div>

<!-- QR Code Absolute Position (Top-Right under barcode) -->
<div class="qrcode-container">
    <img src="{{ public_path($po_document->qr_code_path) }}" class="po-qrcode" alt="QR Code">
</div>

<table class="header" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
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
                PRO 1 GLOBAL COMPANY LIMITED ({{ $po_document->branch->branch_name }})
            </h2>

            <!-- <div>
                Ma.8/6, Theik Pan Rd, Bet: 62 & 63 St.,
                Chanmyathazi Tsp., Mandalay, Myanmar
            </div> -->
            <div>
                {{ 
                    $po_document->branch?->branch_address
                    ?? 'No.76, Lanthit Street, Near Arleing Ngar Sint Pagoda,
                    Insein Township, Yangon, Myanmar' 
                }}
            </div>

            <table style="margin-top:8px;">
                <tr>
                    <td width="33%">Tel.</td>
                    <td width="33%">Fax</td>
                    <td width="33%">Tax ID</td>
                </tr>
            </table>
        </td>

        <td width="20%" align="right" style="vertical-align: top; text-align: right;">
            <span style="display: inline-block; white-space: nowrap;">
                Page 1/1
            </span>
        </td>
    </tr>
</table>

<h2 class="text-center">
    Purchase Order (PO)
</h2>

{{-- DETAIL --}}
<table class="detail-table">

    <tr>
        <td class="label" width="15%">Vendor Code</td>
        <td class="value" width="45%">: {{ $po_document->vendor_code }}</td>

        <td class="label" width="15%">Doc.No.</td>
        <td class="value" width="25%">: {{ $po_document->document_no }}</td>
    </tr>

    <tr>
        <td class="label">Vendor Name</td>
        <td class="value">: {{ $po_document?->vendor?->vendor_name }}</td>

        <td class="label">Doc.Date</td>
        <td class="value">: 
            {{ \Carbon\Carbon::parse($po_document->purchasedate)->format('Y-m-d') }}
        </td>
    </tr>

    <tr>
        <td class="label">Address</td>
        <td class="value">
            : {{ $po_document?->vendor?->vendor_address }}  
            <!-- Lorem Ipsum is simply dummy text of the printing and typesetting industry. -->
        </td>

        <td class="label">PR Doc No.</td>
        <td class="value">: {{-- $po_document?->document_no --}}</td>
    </tr>

    <tr>
        <td class="label">Tel.</td>
        <td class="value">: {{ $po_document?->vendor?->vendor_ph }}</td>

        <td class="label">PR Aproval Date</td>
        <td class="value">: {{-- $po_document->delivery_date --}}</td>
    </tr>

    <tr>
        <td class="label">Fax</td>
        <td class="value">: {{-- 951-245401 --}}</td>

         <td class="label">Credit Term</td>
        <td class="value">: {{ $po_document->creditday }}</td>
    </tr>

    <tr>
        <td></td>
        <td></td>

        <td class="label">Delivery Date</td>
        <td class="value">: {{-- $receive_good_document->document->creditday --}}</td>
    </tr>

</table>

<div height="450">
<table class="product-table">

    <thead>
        <tr>
            <th width="6%">No.</th>
            <th width="20%">Product Code</th>
            <th>Product Name</th>
            <th width="10%" class="text-right" style="text-align: right;">Quantity</th>
            <th width="8%">Unit</th>
            <th class="text-right" style="text-align: right;">Price</th>
            <th class="text-right" style="text-align: right;">Discount</th>
            <th class="text-right" style="text-align: right;">Amount</th>
        </tr>
    </thead>

    <tbody>


        <!-- <tr>
            <td>1</td>
            <td>1101020016005</td>
            <td>TIGER Shear 700</td>
            <td class="qty">24.00</td>
            <td>PC</td>
            <td class="text-right">10,400.00</td>
            <td class="text-right">0.00</td>
            <td class="text-right">10,400.00</td>
        </tr> -->

        @foreach($po_document->purchase_order_items()->whereNotNull('listno')->orderBy('id','asc')->get() as $idx=>$product)
        <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
            <td class="py-1.5 px-3 font-medium text-slate-400">{{ ++$idx }}</td>
            <td class="py-1.5 px-3 font-mono font-medium text-slate-700">{{ $product->bar_code }}</td>
            <td class="py-1.5 px-3 font-medium text-slate-400">{{ $product->supplier_name }}</td>
            <td class="py-1.5 px-3 text-right font-medium">{{ number_format($product->qty) }}</td>
            <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">{{ $product->unit ?? 'PC' }}</span></td>
            <td class="py-1.5 px-3 text-right text-slate-500">{{ number_format($product->price,2) }}</td>
            <td class="py-1.5 px-3 text-right text-slate-500">{{ number_format(0.00) }}</td>
            <td class="py-1.5 px-3 text-right font-medium text-slate-700">{{ number_format($product->amount,2) }}</td>
        </tr>                              
        @endforeach

    </tbody>

</table>
</div>

<table class="summary">
    <tr>
        <td class="sum-label">Amount</td>
        <td class="sum-colon">:</td>
        <td class="sum-value">526,000.00</td>
    </tr>
    <tr>
        <td>Product Discount (Amount)</td>
        <td class="sum-colon">:</td>
        <td class="sum-value">0.00</td>
    </tr>
    <tr>
        <td>Base Amount</td>
        <td class="sum-colon">:</td>
        <td class="sum-value">526,000.00</td>
    </tr>
    <tr>
        <td>Tax Amount</td>
        <td class="sum-colon">:</td>
        <td class="sum-value">0.00</td>
    </tr>
    <tr>
        <td>Total Net Amount</td>
        <td class="sum-colon">:</td>
        <td class="sum-value">526,000.00</td>
    </tr>
</table>
<table class="remark">
    <tr>
        <td style="width: 80px">Remark</td>
        <td style="width: 9px">:</td>
        <td class="myanmarfonts">Stock Refill မြန်မာစာအစမ်း</td>
    </tr>
</table>

<table class="signatures">
    <tr>
        <td class="signature-cell signature-left">
            <table class="signature-info">
                <tr>
                    <td class="signature-label">Recorded By :</td>
                    <td class="signature-value signature-name-cell">Hnin Wai Phyo Hlaing</td>
                </tr>
                <tr>
                    <td class="signature-label">User ID :</td>
                    <td class="signature-value">003-000838</td>
                </tr>
                <tr>
                    <td class="signature-label">PC Name :</td>
                    <td class="signature-value">PRO1MER-028</td>
                </tr>
                <tr>
                    <td class="signature-label">Recorded Date :</td>
                    <td class="signature-value">24/08/2026 10:35 AM</td>
                </tr>
            </table>
        </td>

        <td class="signature-cell signature-middle">
            <table class="signature-info">
                <tr>
                    <td class="signature-label">Checked By :</td>
                    <td class="signature-value signature-name-cell">Wai Phyo Aung</td>
                </tr>
                <tr>
                    <td class="signature-label">User ID :</td>
                    <td class="signature-value">001-000063</td>
                </tr>
                <tr>
                    <td class="signature-label">PC Name :</td>
                    <td class="signature-value">DESKTOP-PAHFRBP</td>
                </tr>
                <tr>
                    <td class="signature-label">Checked Date :</td>
                    <td class="signature-value">24/08/2026 10:47 AM</td>
                </tr>
            </table>
        </td>

        <td class="signature-cell signature-right">
            <table class="signature-info">
                <tr>
                    <td class="signature-label">Approved By :</td>
                    <td class="signature-value signature-name-cell">Wai Phyo Aung</td>
                </tr>
                <tr>
                    <td class="signature-label">User ID :</td>
                    <td class="signature-value">001-000063</td>
                </tr>
                <tr>
                    <td class="signature-label">PC Name :</td>
                    <td class="signature-value">DESKTOP-PAHFRBP</td>
                </tr>
                <tr>
                    <td class="signature-label">Approved Date :</td>
                    <td class="signature-value">24/08/2026 10:49 AM</td>
                </tr>
            </table>

            <table class="approval-note">
                <tr>
                    <td>Approved via digital signature with authority</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="footer">
    <tr>
        <td style="width: 90px">Print By :</td>
        <td class="bold">Khine Mar Htun</td>
        <td class="text-right">24/08/2026 11:05</td>
    </tr>
</table>



</body>
</html>
