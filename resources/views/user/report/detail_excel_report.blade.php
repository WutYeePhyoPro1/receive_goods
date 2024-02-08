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
                        <th class="t_head">Document No </th>
                        <th class="t_head">Truck No </th>
                        <th class="t_head">Bar Truck Type </th>
                        <th class="t_head">Arrived At </th>
                        <th class="t_head">Scan Count </th>
                        <th class="t_head">Unload Duration </th>
                    </tr>
                    <tr>
                        <th class="t_head"> <b class="" >{{ $reg->document_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->truck_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->truck->truck_name ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></th>
                        <th class="t_head"><b>{{ $scan_track ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $driver->duration ?? '' }}</b></th>
                    </tr>
                @elseif($detail == 'document')
                    <tr class="">
                        <th class="t_head">Document No </th>
                        <th class="t_head">PO/TO Document </th>
                        <th class="t_head">Total Cateogry </th>
                        <th class="t_head">Total Product Qty </th>
                        <th class="t_head">Total Unloaded Product Qty </th>
                    </tr>
                    <tr>
                        <th class="t_head"><b>{{ $reg->document_no ?? '' }}</b></th>
                        <th class="t_head"> <b class="" >{{ $document->document_no ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_category($document->id) ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_doc_total_qty($document->id,'all') ?? '' }}</b></th>
                        <th class="t_head"><b>{{ get_doc_total_qty($document->id,'unloaded') ?? '' }}</b></th>
                    </tr>
                @elseif ($detail == 'doc')
                    <tr class="">
                        <th class="t_head">Document No </th>
                        <th class="t_head">Source </th>
                        <th class="t_head">Vendor Name </th>
                        <th class="t_head">Branch </th>
                        <th class="t_head">Total Duration </th>
                        <th class="t_head">Unloaded Truck </th>
                    </tr>
                    <tr>
                        <th class="t_head"><b>{{ $reg->document_no ?? '' }}</b></th>
                        <th class="t_head"> <b class="" >{{ $reg->source_good->name ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $reg->vendor_name ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $reg->branches->branch_name ?? '' }}</b></th>
                        <th class="t_head"><b>{{ $reg->total_duration ?? '' }}</b></th>
                        <th class="t_head"><b>{{ count($driver) ?? '' }}</b></th>
                    </tr>
                @elseif ($detail == 'scan')
                <tr class="">
                    <th class="t_head">Document No </th>
                    <th class="t_head">Truck No</th>
                </tr>
                <tr>
                    <th class="t_head"><b>{{ $scan_track[0]->driver->received->document_no ?? '' }}</b></th>
                    <th class="t_head"> <b class="" >{{ $scan_track[0]->driver->truck_no ?? '' }}</b></th>

                </tr>
                @endif
            </table>
            @if ($detail == 'doc')
            @foreach($driver as $item)
            <table class="real_tb" style="width:  100%;padding:20px 0">
                <thead>

                        <tr class="">
                            <th class="t_head">Driver Name </th>
                            <th class="t_head">Driver's Phone No </th>
                            <th class="t_head">Driver's NRC No</th>
                            <th class="t_head">Truck No </th>
                            <th class="t_head">Truck Type </th>
                            <th class="t_head">Gate </th>
                            <th class="t_head">Scanned Qty </th>
                        </tr>
                        <tr>
                            <th class="t_head"><b>{{ $item->driver_name ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->ph_no ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->nrc_no ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->truck_no ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->truck->truck_name ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->gates->name ?? '' }}</b></th>
                            <th class="t_head"><b>{{ $item->scanned_goods ?? '' }}</b></th>
                        </tr>
                    </thead>
                </table>
                    @endforeach
            @endif
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
                                            <td class="">{{ $tem->scanned_qty - get_remove_pd($tem->product_id)}}</td>
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
                @elseif ($detail == 'doc')

                <table class="real_tb" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="t_head" style="min-width: 30px"></th>
                                <th class="t_head">Document No</th>
                                <th class="t_head">Bar Code</th>
                                <th class="t_head">Product Name</th>
                                <th class="t_head">Total Qty</th>
                                <th class="t_head">Scanned Qty</th>
                                <th class="t_head">Shortage</th>
                                <th class="t_head">Surplus</th>
                                <th class="t_head">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($document as $index=>$item)
                                @if (  count(get_all_pd($item->id)) > 0)
                                    @foreach (get_all_pd($item->id) as $key=>$tem)
                                        <tr class="h-10">
                                            @if ($key == 0)
                                                <td class="">{{ $index+1 }}</td>
                                                <td class="">{{ $item->document_no }}</td>
                                            @else
                                                <td class=""></td>
                                                <td class=""></td>
                                            @endif
                                            <td class="">{{ $tem->bar_code }}</td>
                                            <td class="">{{ $tem->supplier_name }}</td>
                                            <td class="">{{ $tem->qty }}</td>
                                            <td class="">{{ $tem->scanned_qty }}</td>
                                            <td class="">{{ $tem->qty > $tem->scanned_qty ? $tem->qty-$tem->scanned_qty : '' }}</td>
                                            <td class="">{{ $tem->qty < $tem->scanned_qty ? $tem->scanned_qty - $tem->qty : '' }}</td>
                                            <td class="">{{ $tem->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    @elseif ( $detail == 'scan' )
                    <table class="real_tb" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="t_head"></th>
                                <th class="t_head">Bar Code</th>
                                <th class="t_head">Unit</th>
                                <th class="t_head">Per</th>
                                <th class="t_head">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scan_track as $index=>$item)
                                <tr class="h-10">
                                    <td class="ps-2 border border-slate-400 border-t-0  doc_times">{{ $index+1 }}</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ $item->product->bar_code }}</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 px-2 bar_code">{{ $item->unit }}</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 border-r-0 ">{{ $item->per }}</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 border-r-0 ">{{ $item->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
