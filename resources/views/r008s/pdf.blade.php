<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Goods Receiving Issue (R008)</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
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
            min-height:50px;
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
                PRO 1 GLOBAL COMPANY LIMITED ({{ $r008_document->branch->branch_name }})
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
    Goods Receiving Issue (R008)
</h2>

{{-- Document Info --}}
<table class="detail-table">
    <tr>
        <td width="20%"><strong>To</strong></td>
        <td width="40%">{{ $r008_document->vendor->vendor_name }}</td>

        <td width="15%"><strong>Doc. Date</strong></td>
        <td width="25%">{{ $r008_document->document_date }}</td>
    </tr>

    <tr>
        <td><strong>Subject</strong></td>
        <td>Goods Receiving Issue (R008)</td>

        <td><strong>Receive Doc. No.</strong></td>
        <td>{{ $r008_document->rg_no }}</td>
    </tr>

    <tr>
        <td><strong>Product Type</strong></td>
        <td>{{ $r008_document->product_type }}</td>

        <td><strong>Doc. No.</strong></td>
        <td>{{ $r008_document->r008_files->first()->file }}</td>
    </tr>

    <tr>
        <td><strong>Truck / Container No.</strong></td>
        <td>{{ $r008_document->truck_container_no }}</td>

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
<table id="productTable"  class="product-table mt-10">
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
        <!-- <tr>
            <td>Product not according to PO</td>
            <td>1101020016005</td>
            <td>TIGER Shear 700</td>

            <td class="text-right">32</td>
            <td class="text-right">24</td>
            <td class="text-right">-8</td>

            <td class="text-right">0</td>
            <td class="text-right">0</td>

            <td></td>
        </tr> -->
        @php
            $statuses = collect($statuses)->keyBy('subjectr008_id');
        @endphp
        @foreach($r008_document->r008_products as $product)
        <tr>
            <td>{{ $statuses->get($product->status_id)->subjectr008_name }}</td>
            <td>{{ $product->product_code }}</td>
            <td>{{ $product->product_name }}</td>

            <td class="text-right">{{ $product->gr_qty }}</td>
            <td class="text-right">{{ $product->physical_qty }}</td>
            <td class="text-right">{{ $product->diff }}</td>

            <td class="text-right">{{ $product->bdqty }}</td>
            <td class="text-right">{{ $product->sdqty }}</td>

            <td>{{ $product->remark }}</td>
        </tr> 
        @endforeach

    </tbody>
</table>

{{-- Remark --}}
<div class="remark-box mt-20">
    <strong style="display: inline-block;margin-right: 50px;">Remark</strong>

    {{ $r008_document->remark }}

    <!-- <div style="margin-top:10px;">
        BAHOINV-006565
    </div>

    <div style="margin-top:20px;">
        POMDY1260519-0013
    </div> -->
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
        <td>{{ $r008_document->created_at->format('Y-m-d H:i:s') }}</td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>{{ $r008_document->user->name }}</td>
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
            Print By : {{ $userdata->name }}
        </td>

        <td width="20%" class="text-right">
            {{ now() }}
        </td>
    </tr>
</table>


<script type="text/javascript">
        $('#productTable tbody').html('');

</script>
</body>
</html>