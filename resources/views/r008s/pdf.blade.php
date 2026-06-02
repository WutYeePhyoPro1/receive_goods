<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Goods Receiving Issue (R008)</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#000;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .border{
            border:1px solid #000;
        }

        .mt-10{
            margin-top:10px;
        }

        .mt-20{
            margin-top:20px;
        }

        .header td{
            vertical-align:top;
        }

        .detail-table td{
            padding:4px;
        }

        .product-table th,
        .product-table td{
            border:1px solid #000;
            padding:4px;
        }

        .remark-box{
            border:1px solid #000;
            min-height:120px;
            padding:10px;
        }

        .signature-table td{
            border:1px solid #000;
            height:40px;
            text-align:center;
        }

        .footer{
            margin-top:15px;
        }
    </style>
</head>
<body>

{{-- Header --}}
<table class="header">
    <tr>
        <td width="15%">
            <img src="{{ public_path('image/logo.png') }}"
                 style="width:80px;">
        </td>

        <td width="70%" class="text-center">
            <h2 style="margin:0;">
                PRO 1 GLOBAL COMPANY LIMITED Theik Pan
            </h2>

            <div>
                Ma.8/6, Theik Pan Rd, Bet: 62 & 63 St.,
                Chanmyathazi Tsp., Mandalay, Myanmar
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
    Goods Receiving Issue (R008)
</h2>

{{-- Document Info --}}
<table class="detail-table">
    <tr>
        <td width="50%"><strong>To</strong></td>
        <td width="50%">BJ DEVELOPMENT COMPANY LIMITED</td>

        <td width="50%"><strong>Doc. Date</strong></td>
        <td width="50%">29/05/2026</td>
    </tr>

    <tr>
        <td><strong>Subject</strong></td>
        <td>Goods Receiving Issue (R008)</td>

        <td><strong>Receive Doc. No.</strong></td>
        <td>RGMDY1260529-0010</td>
    </tr>

    <tr>
        <td><strong>Product Type</strong></td>
        <td>Local</td>

        <td><strong>Doc. No.</strong></td>
        <td>R008RGMDY1260529-0004</td>
    </tr>

    <tr>
        <td><strong>Truck / Container No.</strong></td>
        <td></td>

        <td><strong>Receive Date</strong></td>
        <td></td>
    </tr>

    <tr>
        <td><strong>In Date</strong></td>
        <td></td>

        <td><strong>Leave Date</strong></td>
        <td></td>
    </tr>
</table>

<p class="text-center mt-20">
    Please give attention to this issue and inform us about your
    settlement as soon as possible.
</p>

{{-- Product Table --}}
<table class="product-table mt-10">
    <thead>
        <tr>
            <th rowspan="2">Reason</th>
            <th rowspan="2">Product Code</th>
            <th rowspan="2">Product Name</th>

            <th colspan="3">Product Qty</th>

            <th colspan="2">Damage</th>

            <th rowspan="2">Remark</th>
        </tr>

        <tr>
            <th>In Doc.</th>
            <th>Actual</th>
            <th>Diff.</th>

            <th>Big</th>
            <th>Small</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>Product not according to PO</td>
            <td>1101020016005</td>
            <td>TIGER Shear 700</td>

            <td class="text-right">32</td>
            <td class="text-right">24</td>
            <td class="text-right">-8</td>

            <td class="text-right">0</td>
            <td class="text-right">0</td>

            <td></td>
        </tr>
    </tbody>
</table>

{{-- Remark --}}
<div class="remark-box mt-20">
    <strong>Remark</strong>

    <div style="margin-top:10px;">
        BAHOINV-006565
    </div>

    <div style="margin-top:20px;">
        POMDY1260519-0013
    </div>
</div>

<p class="mt-10">
    Please be informed that Pro1 reserves rights to handle your
    product(s) unless you kindly take back within 45 days from this
    document date.
</p>

{{-- Signature --}}
<table class="signature-table mt-20">
    <tr>
        <td width="33%"></td>
        <td width="34%"></td>
        <td width="33%"></td>
    </tr>

    <tr>
        <td><strong>RG Staff</strong></td>
        <td><strong>RG Supervisor</strong></td>
        <td><strong>Branch Mgr./Assist. Branch Mgr.</strong></td>
    </tr>

    <tr>
        <td>5/29/2026 11:13:03 AM</td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>Chaw Pyae Thandar</td>
        <td></td>
        <td></td>
    </tr>
</table>

{{-- Footer --}}
<table class="footer">
    <tr>
        <td width="55%">
            <strong>
                For further information, please contact our RG Department
            </strong>
        </td>

        <td width="25%">
            Print By : Chaw Pyae Thandar
        </td>

        <td width="20%" class="text-right">
            02/06/2026 09:20
        </td>
    </tr>
</table>

</body>
</html>