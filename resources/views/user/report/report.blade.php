@extends('layout.layout')

@section('content')
    <div class="m-5">
        <form action="" method="Get">
        <div class="grid grid-cols-7 gap-4">
                <input type="hidden" id="user_role" value="{{ getAuth()->role }}">

                    <div class="flex flex-col">
                        <label for="from_date">From Date :</label>
                        <input type="date" name="from_date" id="from_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('from_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="to_date">To Date :</label>
                        <input type="date" name="to_date" id="to_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('to_date') ?? '' }}">
                    </div>
                    @if ($report == 'finish')
                    <div class="flex flex-col">
                        <label for="branch">Choose Branch :</label>
                        <Select name="branch" id="branch" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="">Choose Branch</option>
                            @foreach ($branch as $item)
                                <option value="{{ $item->id }}" {{ request('branch') == $item->id ? 'selected' : '' }}>{{ $item->branch_name }}</option>
                            @endforeach
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="status">Choose Status :</label>
                        <Select name="status" id="status" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="">Choose Status</option>
                            <option value="complete" {{ request('status')== 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="incomplete" {{ request('status')== 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                        <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="" selected>Choose Method</option>
                            <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                            <option value="truck_no" {{ request('search')=='truck_no' ? 'selected' : '' }}>Truck No</option>
                            <option value="driver_name" {{ request('search')=='driver_name' ? 'selected' : '' }}>Driver_name</option>
                        </Select>
                    </div>

                    @elseif ($report == 'product')
                        <div class="flex flex-col">
                            <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                            <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose Method</option>
                                <option value="main_no" {{ request('search')=='main_no' ? 'selected' : '' }}>Document No(RG)</option>
                                <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                                <option value="product_code" {{ request('search')=='product_code' ? 'selected' : '' }}>Product Code</option>
                            </Select>
                        </div>
                    @elseif($report == 'truck')

                        <div class="flex flex-col">
                            <label for="gate">Choose Gate :</label>
                            <Select name="gate" id="gate" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Gate</option>
                                @foreach ($gate as $item)
                                    <option value="{{ $item->id }}" {{ request('gate') == $item->id ? 'selected' : '' }}>{{ $item->name.'('.$item->branches->branch_name.')' }}</option>
                                @endforeach
                            </Select>
                        </div>

                        <div class="flex flex-col">
                            <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                            <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose Method</option>
                                <option value="main_no" {{ request('search')=='main_no' ? 'selected' : '' }}>Document No(RG)</option>
                                <option value="product_code" {{ request('search')=='product_code' ? 'selected' : '' }}>Scanned Bar Code</option>
                                <option value="truck_no" {{ request('search')=='truck_no' ? 'selected' : '' }}>Truck No</option>
                                <option value="driver_name" {{ request('search')=='driver_name' ? 'selected' : '' }}>Driver Name</option>
                            </Select>
                        </div>
                    @elseif ($report == 'remove')
                        <div class="flex flex-col">
                            <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                            <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose Method</option>
                                <option value="main_no" {{ request('search')=='main_no' ? 'selected' : '' }}>Document No(RG)</option>
                                <option value="product_code" {{ request('search')=='product_code' ? 'selected' : '' }}>Bar Code</option>
                                <option value="user" {{ request('search')=='user' ? 'selected' : '' }}>User Name</option>
                            </Select>
                        </div>
                    @elseif ($report == 'po_to')
                        <div class="flex flex-col">
                            <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                            <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose Method</option>
                                <option value="main_no" {{ request('search')=='main_no' ? 'selected' : '' }}>Document No(RG)</option>
                                <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                                <option value="product_code" {{ request('search')=='product_code' ? 'selected' : '' }}>Bar Code</option>
                            </Select>
                        </div>
                    @elseif ($report == 'shortage' || $report == 'print' || $report == 'man_add')
                    @if ($report == 'shortage')
                        <div class="flex flex-col">
                            <label for="action" class="whitespace-nowrap">Choose excess/shortage :</label>
                            <Select name="action" id="action" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose excess/shortage</option>
                                <option value="excess" {{ request('action')=='excess' ? 'selected' : '' }}>Excess</option>
                                <option value="shortage" {{ request('action')=='shortage' ? 'selected' : '' }}>Shortage</option>
                            </Select>
                        </div>
                    @endif


                        <div class="flex flex-col">
                            <label for="search" class="whitespace-nowrap">Choose Search Method :</label>
                            <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="" selected>Choose Method</option>
                                <option value="main_no" {{ request('search')=='main_no' ? 'selected' : '' }}>Document No(RG)</option>
                                <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                                <option value="product_code" {{ request('search')=='product_code' ? 'selected' : '' }}>Bar Code</option>
                            </Select>
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <label for="search_data">Search Data :</label>
                        <input type="text" name="search_data" id="search_data" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('search_data') ?? '' }}">
                    </div>

                <div class="">
                    <button type="submit" class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
                    @if (($report == 'product' && count($product) > 0) || ($report == 'shortage' && count($data) > 0) || ($report == 'finish' && count($data) > 0) || ($report == 'truck' && count($truck) > 0) || ($report == 'remove' && count($data) > 0) || ($report == 'po_to' && count($docs) > 0) || ($report == 'print' && count($data) > 0) || ($report == 'man_add' && count($data) > 0))
                        <button type="button" class="bg-sky-400 text-white text-xl h-10 w-[20%] rounded-lg ms-4 mt-9 hover:bg-sky-600 hover:text-white" title="export excel" onclick="$('#excel_form').submit()"><i class='bx bx-export'></i></button>
                    @endif
                </div>
            </div>
        </form>

        @if (Session::has('error'))
        <div class="bg-rose-200 mt-3 border-l-4 border-rose-600 py-2">
            <small class="text-rose-600 ms-5">{{ Session::get('error') }}</small>
        </div>
        @endif

        <div class="">
            <table class="w-full mt-4">
                <thead>
                    @if ($report == 'product')
                            @if (dc_staff() || getAuth()->can('user-management'))
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
                        @if (dc_staff() || getAuth()->can('user-management'))
                                    <tr>
                                        <th class="py-2 bg-slate-400  rounded-tl-md w-10" rowspan="2"></th>
                                        <th class="py-2 bg-slate-400 border" rowspan="2">Truck No</th>
                                        <th class="py-2 bg-slate-400 border" rowspan="2">Driver Name</th>
                                        <th class="py-2 bg-slate-400 border" rowspan="2">Truck Type</th>
                                        <th class="py-2 bg-slate-400  border" colspan="3">Scan(Count) Qty</th>
                                        <th class="py-2 bg-slate-400  border" rowspan="2">Unloaded Goods</th>
                                        <th class="py-2 bg-slate-400  border" rowspan="2">Gate</th>
                                        <th class="py-2 bg-slate-400  border" rowspan="2">Duration</th>
                                        <th class="py-2 bg-slate-400  rounded-tr-md" rowspan="2">Arrived At</th>
                                    </tr>
                                    <tr>
                                        <th class="py-2 bg-slate-400  rounded-tl-md w-10">L</th>
                                        <th class="py-2 bg-slate-400 border w-10">M</th>
                                        <th class="py-2 bg-slate-400 border w-10">S</th>

                                    </tr>
                                @else
                                    <tr>
                                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                                        <th class="py-2 bg-slate-400 border">Truck No</th>
                                        <th class="py-2 bg-slate-400 border">Driver Name</th>
                                        <th class="py-2 bg-slate-400 border">Truck Type</th>
                                        <th class="py-2 bg-slate-400  border">Scan(Count) Qty</th>
                                        <th class="py-2 bg-slate-400  border">Unloaded Goods</th>
                                        <th class="py-2 bg-slate-400  border">Gate</th>
                                        <th class="py-2 bg-slate-400  border">Duration</th>
                                        <th class="py-2 bg-slate-400  rounded-tr-md">Arrived At</th>
                                    </tr>
                                @endif
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
                                <th class="py-2 bg-slate-400 border">excess Qty</th>
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
                        @foreach ($product as $item)
                            @if (dc_staff() || getAuth()->can('user-management'))
                                @if(!request('search')  && !request('search_data') && !request('from_date') && !request('to_date'))
                                    <tr>
                                        <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
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
                                        <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
                                        <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no }}</td>
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
                                        <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
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
                                        <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
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
                        @foreach ($data as $item)
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
                            @foreach ($truck as $item)
                            @if (dc_staff() || getAuth()->can('user-management'))
                                        <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_truck/{{ $item->id }}'">
                                            <td class="h-10 text-center border border-slate-400">{{ $truck->firstItem()+$loop->index  }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->truck_no }}</td>
                                            <td class="h-10 text-center border border-slate-400 ">{{ $item->driver_name }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->truck->truck_name ?? '' }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scan_count_truck($item->id,'L') }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scan_count_truck($item->id,'M') }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scan_count_truck($item->id,'S') }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->gate == 0 ? getAuth()->branch->branch_name.' Gate' : $item->gates->name }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                                            <td class="h-10 text-center border border-slate-400">{!! $item->start_date . "&nbsp;&nbsp;&nbsp;" . $item->start_time !!}</td>
                                        </tr>
                                    @else
                                        <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_truck/{{ $item->id }}'">
                                            <td class="h-10 text-center border border-slate-400">{{ $truck->firstItem()+$loop->index  }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->truck_no }}</td>
                                            <td class="h-10 text-center border border-slate-400 ">{{ $item->driver_name }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->truck->truck_name ?? '' }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scan_count_truck($item->id,'S') }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->gate == 0 ? getAuth()->branch->branch_name.' Gate' : $item->gates->name }}</td>
                                            <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                                            <td class="h-10 text-center border border-slate-400">{!! $item->start_date . "&nbsp;&nbsp;&nbsp;" . $item->start_time !!}</td>
                                        </tr>
                                    @endif
                            @endforeach
                    @elseif($report == 'remove')
                        @foreach ($data as $item)
                            <tr>
                                <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->product->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->remove_qty }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->user->name }}</td>
                            </tr>
                        @endforeach
                    @elseif ($report == 'po_to')
                        @foreach ($docs as $item)
                            <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_document/{{ $item->id }}'">
                                <td class="h-10 text-center border border-slate-400">{{ $docs->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ get_truck_count($item->id) }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ get_category($item->id) }}</td>
                            </tr>
                        @endforeach
                    @elseif ($report == 'shortage')
                        @foreach ($data as $item)
                            <tr class="">
                                <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->supplier_name }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ ($item->qty > $item->scanned_qty) ? ($item->qty - $item->scanned_qty) : '' }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ ($item->qty < $item->scanned_qty) ? ($item->scanned_qty - $item->qty) : '' }}</td>
                                <td class="h-10 text-center border border-slate-400 ">
                                    @if ($item->remark)
                                        <i class='bx bx-message-rounded-dots cursor-pointer text-xl mr-1 rounded-lg px-1 text-white bg-sky-400 hover:bg-sky-600 remark_ic' data-pd="{{ $item->bar_code }}" data-id="{{ $item->id }}"></i>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @elseif($report == 'print')
                    @foreach ($data as $item)
                        <tr class="">
                                <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->product->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->quantity }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ 'Bar '.$item->bar_type }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->reasons->reason ?? '' }}</td>
                        </tr>
                    @endforeach
                    @elseif($report == 'man_add')
                        @foreach ($data as $item)
                            <tr class="">
                                    <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->received->document_no }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->product->doc->document_no }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->product->bar_code }}</td>
                                    <td class="h-10 text-center border border-slate-400 ">{{ $item->added_qty }}</td>
                                    <td class="h-10 text-center border border-slate-400 ">{{ $item->user->name }}</td>
                            </tr>
                        @endforeach
                    @endif
                    @if($report == 'product')
                        <tbody>
                            <tr>
                                <td colspan="{{ dc_staff() ? '7' : '5' }}"></td>
                                <td class="h-10 text-center border border-slate-400">Total Scanned Qty : <b>{{ $all_sum['scanned_sum'] }}</b></td>
                                @if(!(!request('search')  && !request('search_data') && !request('from_date') && !request('to_date')))
                                    <td class="h-10 text-center border border-slate-400">Total Qty : <b>{{ $all_sum['qty_sum'] }}</b></td>
                                @endif
                                <td></td>
                            </tr>
                        </tbody>
                    @endif
                </tbody>
            </table>

        </div>
        <form action="{{ route('excel_export') }}" id="excel_form" method="POST">
            @csrf
            <input type="hidden" name="report" value="{{ $report }}">
            <input type="hidden" name="{{ request('branch') ? 'branch' : '' }}" value="{{ request('branch') ? request('branch') : '' }}">
            <input type="hidden" name="{{ request('status') ? 'status' : '' }}" value="{{ request('status') ? request('status') : '' }}">
            <input type="hidden" name="{{ request('from_date') ? 'from_date' : '' }}" value="{{ request('from_date') ? request('from_date') : '' }}">
            <input type="hidden" name="{{ request('to_date') ? 'to_date' : '' }}" value="{{ request('to_date') ? request('to_date') : '' }}">
            <input type="hidden" name="{{ request('search') ? 'search' : '' }}" value="{{ request('search') ? request('search') : '' }}">
            <input type="hidden" name="{{ request('search_data') ? 'search_data' : '' }}" value="{{ request('search_data') ? request('search_data') : '' }}">
        </form>
        @if (request('search') || request('search_data')  || request('branch') || request('gate') || request('status') || request('from_date') || request('to_date'))
        <div class="mt-2">
            <button class="bg-sky-600 text-white px-3 py-2 rounded-md" onclick="javascript:window.location.href = '{{$url}}';">Back to Default</button>
        </div>
        @endif
        <div class="flex justify-center text-xs mt-2 bg-white mt-6">
            @if ($report == 'product')
                {{ $product->appends(request()->query())->links() }}
            @elseif ($report == 'truck')
                {{ $truck->appends(request()->query())->links() }}
            @elseif ($report == 'finish' || $report == 'remove' || $report == 'shortage' || $report == 'print' || $report == 'man_add')
                {{ $data->appends(request()->query())->links() }}
            @elseif ($report == 'po_to')
                {{ $docs->appends(request()->query())->links() }}

            @endif
    </div>
    </div>
    {{-- Start Model --}}
        <div class="hidden" id="remark_model">
            <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
                <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                    <!-- Modal content -->
                    <div class="card rounded">
                        <div
                            class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                            <div class="flex px-4 py-2 justify-between items-center min-w-80">
                                <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Remark for &nbsp;<b id="remark_item"></b>&nbsp;<span
                                        id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hidden svgclass">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                                <button type="button" class="text-rose-600 font-extrabold"
                                    onclick="$('#remark_model').hide()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 flex flex-col" id="remark_card_body">
                            {{-- <textarea cols="50" class="ps-1" id="ipt_remark" rows="5"></textarea>
                            <small class="ml-2" id="op_count">0/500</small> --}}
                        </div>
                    </div>
                </div>
        </div>
        </div>
    {{-- End Model --}}
    @push('js')
        <script>

            $(document).ready(function(){
                $(document).on('click','.remark_ic',function(e)
                {
                    $pd_code = $(this).data('pd');
                    $id      = $(this).data('id');
                    $('#remark_item').text(' "'+$pd_code+'"');

                    $.ajax({
                        url : "/ajax/show_remark/"+$id,
                        beforeSend:function(){
                            $('#remark_card_body').html('');
                        },
                        success:function(res){
                            $list = '';
                            if(res != '')
                            {
                                $list = `
                                <div class="" style="width: 500px;hyphens:auto;word-break:normal">
                                <span>${res}</span>
                            </div>
                                `;
                            }
                            $('#remark_card_body').append($list);
                        }
                    })
                    $('#remark_model').show();
                })
            })
        </script>
    @endpush
@endsection
