@extends('layout.layout')

@section('content')
    <div class="m-5">
        <form action="@if ($report == 'product')
        {{ route('product_list') }}
        @elseif ($report == 'finish')
        {{ route('finished_documents') }}
        @endif" method="Get">
        <div class="grid grid-cols-7 gap-4">
                <input type="hidden" id="user_role" value="{{ getAuth()->role }}">
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
                        <label for="from_date">From Date :</label>
                        <input type="date" name="from_date" id="from_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('from_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="to_date">To Date :</label>
                        <input type="date" name="to_date" id="to_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('to_date') ?? '' }}">
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
                    @endif

                    <div class="flex flex-col">
                        <label for="search_data">Search Data :</label>
                        <input type="text" name="search_data" id="search_data" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('search_data') ?? '' }}">
                    </div>

                <div class="">
                    <button type="submit" class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
                    <button type="button" class="bg-sky-400 text-white text-xl h-10 w-[20%] rounded-lg ms-4 mt-9 hover:bg-sky-600 hover:text-white" onclick="$('#excel_form').submit()"><i class='bx bx-export'></i></button>
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
                    <tr class="">
                        @if ($report == 'product')
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border">REG Document</th>
                            <th class="py-2 bg-slate-400 border">PO/TO Document</th>
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
                            <th class="py-2 bg-slate-400  rounded-tr-md">Arrived At</th>
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
                        @foreach ($product as $item)
                            <tr>
                                <td class="h-10 text-center border border-slate-400">{{ $product->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->doc->received->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->doc->document_no }}</td>
                                <td class="h-10 text-center border border-slate-400 ">{{ $item->bar_code }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->qty }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->scanned_qty }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->created_at->format('Y-m-d')}}</td>
                            </tr>
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
                                <tr class="hover:bg-slate-200 cursor-pointer" onclick="javascript:window.location.href = 'detail_truck/{{ $item->id }}'">
                                    <td class="h-10 text-center border border-slate-400">{{ $truck->firstItem()+$loop->index  }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->truck_no }}</td>
                                    <td class="h-10 text-center border border-slate-400 ">{{ $item->driver_name }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->truck->truck_name }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ get_scanned_qty($item->id) }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->gates->name }}</td>
                                    <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                                    <td class="h-10 text-center border border-slate-400">{!! $item->start_date . "&nbsp;&nbsp;&nbsp;" . $item->start_time !!}</td>
                                </tr>
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
            @elseif($report == 'truck')
                {{ $truck->appends(request()->query())->links() }}
            @endif
    </div>
    </div>

    @push('js')
        <script>

            $(document).ready(function(){

            })
        </script>
    @endpush
@endsection
