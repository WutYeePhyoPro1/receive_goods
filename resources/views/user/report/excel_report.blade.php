<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>receive_goods</title>
</head>
<body>
    <div class="">
        <table class="w-full mt-4">
            <thead>
                @if ($report == 'product')
                    @if (dc_staff())
                    <tr class="">
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10" rowspan="2"></th>
                        <th class="py-2 bg-slate-400 border" rowspan="2">REG Document</th>
                        <th class="py-2 bg-slate-400 border" rowspan="2">PO/TO Document</th>
                        <th class="py-2 bg-slate-400 border" rowspan="2">Product</th>
                        <th class="py-2 bg-slate-400 border" colspan="3">Scanned (Count) Qty</th>
                        <th class="py-2 bg-slate-400  border" rowspan="2">Scanned Qty</th>
                        <th class="py-2 bg-slate-400 border" rowspan="2">Product Qty</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md" rowspan="2">Created At</th>
                    </tr>
                    <tr class="">
                        <th class="py-2 bg-slate-400 border" >L</th>
                        <th class="py-2 bg-slate-400  border" >M</th>
                        <th class="py-2 bg-slate-400 border" >S</th>

                    </tr>
                @else
                    <tr class="">
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10" ></th>
                        <th class="py-2 bg-slate-400 border" >REG Document</th>
                        <th class="py-2 bg-slate-400 border" >PO/TO Document</th>
                        <th class="py-2 bg-slate-400 border" >Product</th>
                        <th class="py-2 bg-slate-400 border" >Scanned (Count) Qty</th>
                        <th class="py-2 bg-slate-400  border" >Scanned Qty</th>
                        <th class="py-2 bg-slate-400 border" >Product Qty</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md" >Created At</th>
                    </tr>
                @endif
            @elseif ($report == 'finish')
                <tr class="">
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document</th>
                    <th class="py-2 bg-slate-400 border">Source</th>
                    <th class="py-2 bg-slate-400 border">Total Truck</th>
                    <th class="py-2 bg-slate-400  border">Total Qty</th>
                    <th class="py-2 bg-slate-400  border">Total Scanned Qty</th>
                    <th class="py-2 bg-slate-400  border">Duration</th>
                    <th class="py-2 bg-slate-400  rounded-tr-md">Created At</th>
                <tr class="">
            @elseif($report == 'truck')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Truck No</th>
                    <th class="py-2 bg-slate-400 border">Driver Name</th>
                    <th class="py-2 bg-slate-400 border">Truck Type</th>
                    <th class="py-2 bg-slate-400  border">Loaded Goods</th>
                    <th class="py-2 bg-slate-400  border">Gate</th>
                    <th class="py-2 bg-slate-400  border">Duration</th>
                    <th class="py-2 bg-slate-400  rounded-tr-md">Arrived At</th>
                </tr>
            @elseif($report == 'remove')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document No</th>
                    <th class="py-2 bg-slate-400 border">Product Code</th>
                    <th class="py-2 bg-slate-400 border">Removed Qty</th>
                    <th class="py-2 bg-slate-400 rounded-tr-md">By User</th>
                </tr>
            @elseif($report == 'po_to')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document No(REG)</th>
                    <th class="py-2 bg-slate-400 border">Document No</th>
                    <th class="py-2 bg-slate-400 border">Truck Count</th>
                    <th class="py-2 bg-slate-400 rounded-tr-md">Products Categories</th>
                </tr>
            @elseif ($report == 'shortage')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document No(REG)</th>
                    <th class="py-2 bg-slate-400 border">Document No</th>
                    <th class="py-2 bg-slate-400 border">Product Code</th>
                    <th class="py-2 bg-slate-400 border">Supplier Name</th>
                    <th class="py-2 bg-slate-400 border">Shortage Qty</th>
                    <th class="py-2 bg-slate-400 border">Excess Qty</th>
                    <th class="py-2 bg-slate-400 rounded-tr-md">Remark</th>
                </tr>
            @elseif ($report == 'print')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document No(REG)</th>
                    <th class="py-2 bg-slate-400 border">Document No</th>
                    <th class="py-2 bg-slate-400 border">Product Code</th>
                    <th class="py-2 bg-slate-400 border">Quantity</th>
                    <th class="py-2 bg-slate-400 border">Type</th>
                    <th class="py-2 bg-slate-400 rounded-tr-md">Reason</th>
                </tr>
            @elseif ($report == 'man_add')
                <tr>
                    <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                    <th class="py-2 bg-slate-400 border">Document No(REG)</th>
                    <th class="py-2 bg-slate-400 border">Document No</th>
                    <th class="py-2 bg-slate-400 border">Product Code</th>
                    <th class="py-2 bg-slate-400 border">Added Qty</th>
                    <th class="py-2 bg-slate-400 rounded-tr-md">By User</th>
                </tr>
            @endif
            </thead>
            <tbody>
                @if ($report == 'product')
                    @foreach ($all as $index=>$item)
                           @if (dc_staff())
                                @if(!request('search')  && !request('search_data') && !request('from_date') && !request('to_date'))
                                    <tr>
                                        <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no ?? '' }}</td>
                                        <td class="h-10 text-center border border-slate-400 ">{{ $item->product->bar_code }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_scan_truck_pd($item->driver_info_id,$item->product_id,'L') }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_scan_truck_pd($item->driver_info_id,$item->product_id,'M') }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_scan_truck_pd($item->driver_info_id,$item->product_id,'S') }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->scanned_qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no ?? '' }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400 ">{{ $item->bar_code }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_per($item->id,'L') }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_per($item->id,'M') }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_per($item->id,'S') }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->scanned_qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                                    </tr>
                                @endif

                            @else
                                @if(!request('search')  && !request('search_data') && !request('from_date') && !request('to_date'))
                                    <tr>
                                        <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400 ">{{ $item->product->bar_code }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_scan_truck_pd($item->driver_info_id,$item->product_id,'S') }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->scanned_qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->created_at->format('Y-m-d')}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400 ">{{ $item->bar_code }}</td>
                                        <td class="h-10 text-center border border-slate-400 w-10">{{ get_per($item->id,'S') }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->scanned_qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->qty }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                                    </tr>
                                @endif
                            @endif
                    @endforeach
                @elseif ($report == 'finish')
                    @foreach ($all as $index=>$item)
                        <tr class="hover:bg-slate-200 cursor-pointer" >
                            <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ $item->source_good->name }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ get_total_truck($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ get_total_qty($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->total_duration }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                        </tr>
                    @endforeach
                @elseif($report == 'truck')
                    @foreach ($all as $index=>$item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $index+1 }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->truck_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->driver_name }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->truck ? $item->truck->truck_name : 'N/A' }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">
                                {{ $item->gates ? $item->gates->name : 'N/A' }}
                            </td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                @elseif($report == 'remove')
                    @foreach ($all as $index=>$item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->received->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ $item->product->bar_code }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->remove_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->user->name }}</td>
                        </tr>
                    @endforeach
                @elseif ($report == 'po_to')
                    @foreach ($all as $index=>$item)
                        <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_document/{{ $item->id }}'">
                            <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->received->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ get_truck_count($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ get_category($item->id) }}</td>
                        </tr>
                    @endforeach
                @elseif ($report == 'shortage')
                    @foreach ($all as $index=>$item)
                        <tr class="">
                            <td class="h-10 text-center border border-slate-400">{{ $index+1  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->bar_code }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->supplier_name }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ ($item->qty > $item->scanned_qty) ? ($item->qty - $item->scanned_qty) : '' }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ ($item->qty < $item->scanned_qty) ? ($item->scanned_qty - $item->qty) : '' }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ $item->remark }}</td>
                        </tr>
                    @endforeach
                @elseif($report == 'print')
                    @foreach ($all as $index=>$item)
                        <tr class="">
                                <td class="h-10 text-center border border-slate-400">{{ $index+1 }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->quantity }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ 'Bar '.$item->bar_type }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->reasons->reason ?? '' }}</td>
                        </tr>
                    @endforeach
                @elseif($report == 'man_add')
                    @foreach ($all as $index=>$item)
                        <tr class="">
                                <td class="h-10 text-center border border-slate-400">{{ $index+1 }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->added_qty }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->user->name }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>
