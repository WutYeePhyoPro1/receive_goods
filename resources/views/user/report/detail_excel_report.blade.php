<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>receive_goods</title>
</head>
<style>
    body{
        margin: 0;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .wrapper{
        width: 100%;
        margin-top: 10px
    }

    .real_tb{
        width: 100%;
        border-collapse: collapse;
    }
    .real_tb, td, .t_head {
    border: 1px solid rgb(0,0,0);
    }

    .t_head{
        height: 40px
    }

    .real_tb td{
        padding: 10px 0;
        text-align: center;
    }

    #back_img{
        position: fixed;
        object-fit: cover;
        width: 90%;
        top: 30%;
        bottom: 0;
        left: 10%;
        right: 0;
        opacity: 0.2;
    }

    .footer{
        position: fixed;
        bottom: 0;
        left: 0;
    }

</style>
<body >

        <div class="wrapper">
            {{-- <div class="" style="width: 95%;padding:20px;display:flex;justify-content:space-between">
                    <span class="" >Document No : <b class="" >{{ $reg->document_no ?? '' }}</b></span>
                    <span class="" >Truck No : <b>{{ $driver->truck_no ?? '' }}</b></span>
                    <span class=" " >Truck Type : <b>{{ $driver->truck->truck_name ?? '' }}</b></span>
                    <span class=" " >Arrived At : <b>{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></span>
                    <span class=" " >Unload Duration : <b>{{ $driver->duration ?? '' }}</b></span>
            </div> --}}
            @if ($action == 'print')
                <img id="back_img" src="{{ public_path('storage/background_img/finallogo.png') }}" alt="">
            @endif
            <table class="real_tb" style="width:  100%;padding:20px 0">
                @if($detail == 'truck')
                    <tr class="">
                        <th class="t_head">Document No :</th>
                        <th class="t_head">Truck No :</th>
                        <th class="t_head">Bar Truck Type :</th>
                        <th class="t_head">Arrived At :</th>
                        <th class="t_head">Unload Duration :</th>
                    </tr>
                    <tr>
                        <th class="t_head"> <b class="" >{{ $reg->document_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->truck_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->truck->truck_name ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></th>
                        <th class="t_head"><b>{{ $driver->duration ?? '' }}</b></th>
                    </tr>
                @elseif($detail == 'document')
                    <tr class="">
                        <th class="t_head">Document No :</th>
                        <th class="t_head">PO/TO Document :</th>
                        <th class="t_head">Total Cateogry :</th>
                        <th class="t_head">Total Product Qty :</th>
                        <th class="t_head">Total Unloaded Product Qty :</th>
                    </tr>
                    <tr>
                        <th class="t_head"><b>{{ $reg->document_no ?? '' }}</b></th>
                        <th class="t_head"> <b class="" >{{ $document->document_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_category($document->id) ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_doc_total_qty($document->id,'all') ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_doc_total_qty($document->id,'unloaded') ?? '' }}</b></th>
                    </tr>
                @endif

            </table>
            @if ($detail == 'truck')
            <table class="real_tb" style="width: 100%;">
                    <thead>

                        <tr class="">
                                <th class="t_head" style="min-width:20px"></th>
                                <th class="t_head">Document No</th>
                                <th class="t_head">Bar Code</th>
                                <th class="t_head">Product Name</th>
                                <th class="t_head">Unloaded Qty</th>
                        </tr>
                    </thead>
                    <tbody>


                                <?php
                                $i = 0;
                            ?>
                            @foreach ($document as $item)
                                @if (count(get_truck_product($item,$driver->id)) > 0)
                                    @foreach (get_truck_product($item,$driver->id) as $key=>$tem)
                                    <tr class="h-10">
                                        @if ($key == 0)

                                                <td class="">{{ $i+1 }}</td>
                                                <td class="">{{ getDocument($item)->document_no }}</td>
                                            @else
                                                <td class=""></td>
                                                <td class=""></td>
                                            @endif
                                            <td class="">{{ $tem->product->bar_code }}</td>
                                            <td class="">{{ $tem->product->supplier_name }}</td>
                                        <td class="">{{ $tem->scanned_qty }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            <?php
                            $i++;
                        ?>
                        @endforeach
                    </tbody>
                </table>
                @elseif( $detail == 'document' )
                @foreach ($truck as $item)
                <div class="" style="">
                    @if ($action == 'print')
                        <span class="" style="padding: 10px 30px;box-shadow:2px 2px 5px rgb(0, 0, 0,0.5)">Truck No :&nbsp;&nbsp;<b>{{ $item->truck_no }}</b></span>
                    @else
                        <table class="real_tb" style="width:  100%;padding:20px 0;margin-top:20px">
                            <thead>
                                <tr>
                                    <th class="t_head">Truck No :&nbsp;&nbsp;<b>{{ $item->truck_no }}</b></th>
                                </tr>
                            </thead>
                        </table>
                    @endif
                </div>
                <table class="real_tb" style="width:  100%;padding:20px 0;">
                    <thead>
                        <tr>
                            <th class="t_head" style="min-width: 30px"></th>
                            <th class="t_head" style="">Product Code</th>
                            <th class="t_head">Supplier Name</th>
                            <th class="t_head">Unloaded Qty</th>
                            <th class="t_head">Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (get_product_per_truck($item->id,$document->id) as $index=>$tem)
                            <tr>
                                <td class=" ">{{ $index+1 }}</td>
                                <td class=" ">{{ $tem->product->bar_code }}</td>
                                <td class=" ">{{ $tem->product->supplier_name }}</td>
                                <td class=" ">{{ $tem->scanned_qty }}</td>
                                <td class="">{{ $tem->truck->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
                @endif

            @if ($action == 'print' && $detail == 'truck')
                <div class="footer" >
                    <span style="text-decoration: underline">Created By</span>
                    <div class="" style="margin-top: 4px">
                        <span>{{ $driver->user->name }}</span>
                    </div>
                    <div class="" style="margin-top: 4px">
                        <span>{{ $driver->user->employee_code }}</span>
                    </div>
                    <br>
                    <div class="">
                        <span>----------------------</span>
                    </div>
                </div>
            @endif
        </div>
</body>
</html>
