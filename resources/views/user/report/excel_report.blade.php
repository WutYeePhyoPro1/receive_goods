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
                <tr class="">
                    @if ($report == 'product')
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">Document</th>
                        <th class="py-2 bg-slate-400 border">Product</th>
                        <th class="py-2 bg-slate-400 border">Total Qty</th>
                        <th class="py-2 bg-slate-400  border">Scanned Qty</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Created At</th>
                    @elseif ($report == 'finish')
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">Document</th>
                        <th class="py-2 bg-slate-400 border">Source</th>
                        <th class="py-2 bg-slate-400 border">Total Truck</th>
                        <th class="py-2 bg-slate-400  border">Total Qty</th>
                        <th class="py-2 bg-slate-400  border">Total Scanned Qty</th>
                        <th class="py-2 bg-slate-400  border">Duration</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Created At</th>
                    @elseif($report == 'truck')
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">Truck No</th>
                        <th class="py-2 bg-slate-400 border">Driver Name</th>
                        <th class="py-2 bg-slate-400 border">Truck Type</th>
                        <th class="py-2 bg-slate-400  border">Loaded Goods</th>
                        <th class="py-2 bg-slate-400  border">Gate</th>
                        <th class="py-2 bg-slate-400  border">Duration</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Created At</th>
                    @elseif($report == 'remove')
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border">Document No</th>
                            <th class="py-2 bg-slate-400 border">Product Code</th>
                            <th class="py-2 bg-slate-400 border">Removed Qty</th>
                            <th class="py-2 bg-slate-400 rounded-tr-md">By User</th>
                    @endif

                </tr>
            </thead>
            <tbody>
                @if ($report == 'product')
                    @foreach ($all as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ $item->bar_code }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->scanned_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                        </tr>
                    @endforeach
                @elseif ($report == 'finish')
                    @foreach ($all as $item)
                        <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_doc/{{ $item->id }}'">
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
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
                        @foreach ($all as $item)
                            <tr >
                                <td class="h-10 text-center border border-slate-400">{{ $truck->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->truck_no }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->driver_name }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->truck->truck_name }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->gates->name }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                            </tr>
                        @endforeach
                @elseif($report == 'remove')
                    @foreach ($all as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->received->document_no }}</td>
                            <td class="h-10 text-center border border-slate-400 ">{{ $item->product->bar_code }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->remove_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->user->name }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>
