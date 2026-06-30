@extends('layout.layout')

@section('css')
    <!-- <style>
        @media (max-width: 767px) {
            .content {
                left: 0;
                top: 96px;
                width: 100%;
                padding-bottom: 150px;
            }

            .side_bar {
                top: 12px;
                left: 12px;
                width: calc(100% - 24px);
                overflow-x: auto;
                border-radius: 12px;
                padding: 8px;
            }

            .side_bar:hover {
                width: calc(100% - 24px);
            }

            .sidebar_body {
                display: flex;
                gap: 8px;
                width: max-content;
                padding: 0;
            }

            .sidebar_items {
                width: 54px;
                flex: 0 0 54px;
                margin-bottom: 0;
            }

            .sidebar_text > span,
            .side_bar:hover .sidebar_text > span {
                display: none;
            }

            .footer {
                height: auto;
                min-height: 76px;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px 12px;
                font-size: 12px;
            }

            .logo {
                margin-left: 0;
                line-height: 32px;
            }

            .logo::before {
                display: none;
            }

            .logo > img {
                height: 24px;
                margin-top: 4px;
            }

            .footer .flexv {
                width: 100%;
                overflow-x: auto;
                line-height: 24px !important;
            }
        }
    </style> -->
@endsection

@section('content')
    <div class="px-4 py-2 sm:px-5">
        <form action="{{ route('list') }}" method="Get">
        <div class="rounded-xl border border-slate-200 bg-white/80 shadow-sm">
            <div class="flex items-center justify-center border-b border-slate-100 px-5 py-2">
                <div class="flex items-center gap-2">
                    <i class='bx bx-list-ul text-xl text-amber-500'></i>
                    <h2 class="mb-0 text-sm font-bold tracking-wide text-slate-700">
                        Scanned Document List
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-7">
                {{-- <div class="flex flex-col">
                    <label for="doc_name">Document Number :</label>
                    <input type="text" name="doc" id="doc_name" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2">
                </div> --}}

                <input type="hidden" id="user_role" value="{{ getAuth()->role }}">
                    <div class="flex flex-col">
                        <label for="branch" class="text-sm font-medium text-slate-700">Choose Branch :</label>
                        <Select name="branch" id="branch" class="mt-2 h-10 w-full rounded-md border border-slate-300 bg-white px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" style="appearance: none;">
                            <option value="">Choose Branch</option>
                            @foreach ($branch as $item)
                                <option value="{{ $item->id }}" {{ request('branch') == $item->id ? 'selected' : '' }}>{{ $item->branch_name }}</option>
                            @endforeach
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="status" class="text-sm font-medium text-slate-700">Choose Status :</label>
                        <Select name="status" id="status" class="mt-2 h-10 w-full rounded-md border border-slate-300 bg-white px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" style="appearance: none;">
                            <option value="">Choose Status</option>
                            <option value="complete" {{ request('status')== 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="incomplete" {{ request('status')== 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="from_date" class="text-sm font-medium text-slate-700">From Date :</label>
                        <input type="date" name="from_date" id="from_date" class="mt-2 h-10 w-full rounded-md border border-slate-300 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" value="{{ request('from_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="to_date" class="text-sm font-medium text-slate-700">To Date :</label>
                        <input type="date" name="to_date" id="to_date" class="mt-2 h-10 w-full rounded-md border border-slate-300 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" value="{{ request('to_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="search" class="text-sm font-medium text-slate-700">Choose Search Method :</label>
                        <Select name="search" id="search" class="mt-2 h-10 w-full rounded-md border border-slate-300 bg-white px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" style="appearance: none;">
                            <option value="" selected>Choose Method</option>
                            <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                            <option value="truck_no" {{ request('search')=='truck_no' ? 'selected' : '' }}>Truck No</option>
                            <option value="driver_name" {{ request('search')=='driver_name' ? 'selected' : '' }}>Driver_name</option>
                            <option value="docu_dt" {{ request('search')=='docu_dt' ? 'selected' : '' }}>PO/POI/TO</option>
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="search_data" class="text-sm font-medium text-slate-700">Search Data :</label>
                        <input type="text" name="search_data" id="search_data" class="mt-2 h-10 w-full rounded-md border border-slate-300 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300" value="{{ request('search_data') ?? '' }}">
                    </div>

                <div class="flex items-end">
                    <button class="h-10 w-full rounded-lg bg-amber-400 px-4 font-medium transition hover:bg-amber-600 hover:text-white">Search</button>
                </div>
            </div>
        </div>
        </form>
        @if (Session::has('error'))
        <div class="mt-3 rounded-r-md border-l-4 border-rose-600 bg-rose-100 py-2 pr-3">
            <small class="ms-5 text-rose-600">{{ Session::get('error') }}</small>
        </div>
        @endif


        <div class="mt-4 overflow-x-auto rounded-lg border border-slate-300 bg-white shadow-sm">
            <table class="min-w-[1100px] w-full text-sm">
                <thead>
                    <tr class="">
                        <th class="w-12 whitespace-nowrap bg-slate-400 px-3 py-3 text-center"></th>
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Branch</th>
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Status</th>
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Document</th>
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Source</th>
                        <th class="min-w-56 border border-slate-500 bg-slate-400 px-3 py-3 text-center">Supplier Name</th>
                        {{-- <th class="py-2 bg-slate-400 border">PO QTY</th>
                        <th class="py-2 bg-slate-400 border">Remain QTY</th>
                        <th class="py-2 bg-slate-400 border">Exceed QTY</th> --}}
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Start Date</th>
                        @can('management-document')
                        <th class="whitespace-nowrap border border-slate-500 bg-slate-400 px-3 py-3 text-center">Action</th>
                        @endcan
                        <th class="whitespace-nowrap bg-slate-400 px-3 py-3 text-center">Total Unload Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="odd:bg-white even:bg-slate-50">
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">{{ $item->branches->branch_name }}</td>
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center font-medium {{ $item->status == 'complete' ? 'text-green-600' : 'text-amber-600' }}">{{ $item->status }}</td>
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">
                                <input type="hidden" class="check_empty" value="{{ check_empty($item->id) }}">
                                <span class="cursor-pointer hover:underline hover:font-semibold {{ $item->status == 'complete' ? 'text-emerald-600' : '' }}" onclick="$(this).parent().find('.view_goods').click();">{{ $item->document_no }}</span> &nbsp;
                                <i class='bx bxs-show text-amber-400 cursor-pointer ms-3 text-lg view_goods hidden' title="view Document" onclick="javascript:window.location.href = '/view_goods/'+{{$item->id}}"></i>
                                @can('add-new-truck')
                                    {{-- @if ($item->status == 'incomplete')
                                        <i class='bx bx-message-square-edit text-sky-600 cursor-pointer ms-3 text-lg edit_view' title="add truck" data-id="{{ $item->id }}" style="transform: translateY(2px)"></i>
               
                                    @endif --}}
                                    @if (count(truck_arrive($item->id)) > 0)
                                        @foreach (truck_arrive($item->id) as $tem)
                                            <i class='bx bxs-group text-rose-400 cursor-pointer ms-3 text-lg' title="{{ $tem->truck_no }}" onclick="javascript:window.location.href = '/join_receive/'+{{$item->id}}+'/'+{{ $tem->id }}"></i>
                                        @endforeach
                                    @elseif($item->status == 'incomplete')
                                        <i class='bx bx-message-square-edit text-sky-600 cursor-pointer ms-3 text-lg edit_view' title="add truck" data-id="{{ $item->id }}" style="transform: translateY(2px)"></i>
                                    @endif
                                @endcan

                            </td>
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">{{ $item->source_good->name }}</td>
                            <td class="h-11 border border-slate-300 px-3 text-center">{{ strlen($item->vendor_name) > 50 ? substr($item->vendor_name,0,50).'...' : $item->vendor_name }}</td>
                            {{-- <td class="h-10 text-center border border-slate-400">{{ get_total_qty($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->remaining_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->exceed_qty }}</td> --}}
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">{{ $item->start_date.' '.$item->start_time }}</td>
                            @can('management-document')
                                <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">
                                    @if ($item->status != 'complete')
                                        <button class="bg-rose-500 hover:bg-rose-700 px-1 rounded-md mr-1 del_doc_btn" data-id="{{ $item->id }}"><i class='bx bxs-trash-alt text-white mt-1'></i></button>
                                    @endif
                                    {{-- <button class="bg-sky-500 hover:bg-sky-700 px-1 rounded-md mr-1 edit_btn" onclick="window.location.href = '/edit/'+{{$item->id}}"><i class='bx bxs-edit text-white mt-1'></i></button> --}}
                                </td>
                            @endcan
                            <td class="h-11 whitespace-nowrap border border-slate-300 px-3 text-center">{{ $item->total_duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (request('search') || request('search_data') || request('branch') || request('status') || request('from_date') || request('to_date'))
        <div class="mt-3">
            <button class="rounded-md bg-sky-600 px-3 py-2 text-white transition hover:bg-sky-700" onclick="javascirpt:window.location.href = 'list'">Back to Default</button>
        </div>
        @endif
        <div class="mt-6 flex overflow-x-auto bg-white text-xs sm:justify-center">
            {{ $data->appends(request()->query())->links() }}
    </div>
    </div>

    @push('js')
        <script>

            $(document).ready(function(){
                $token = $('meta[name=__token]').attr('content');
                $(document).on('click','.edit_view',function(e){
                    $role = $('#user_role').val();
                    $id = $(this).data('id');
                    $empty = $(this).parent().find('.check_empty').val();
                    if($empty)
                    {
                        Swal.fire({
                            icon  : 'error',
                            title : 'Warning',
                            text  : 'Receive Goods မှာ မပြီးပြတ်သေးတဲ့ document ရှိနေပါသည်။ continue/complete အရင်နှိပ်ပါ'
                        })
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'truck car အသစ် ရိုက်ထည်လိုပါသလား',
                            showCancelButton:true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                        }).then((result)=>{
                            if(result.isConfirmed)
                            {
                                if($role == 2 || $role == 3){
                                    window.location.href = 'car_info/'+$id;
                                }else{
                                    window.location.href = 'receive_goods/'+$id;
                                }
                            } else {
                                window.location.href = 'receive_goods/'+$id;
                            }
                        })
                    }
                })

                $(document).on('click','.del_doc_btn',function(e){
                    $id = $(this).data('id');

                    Swal.fire({
                        icon : 'question',
                        text: 'Are You Sure',
                        showCancelButton:true,
                        confirmButtonText: 'Yes',
                        cancelButtonText : 'No',
                    }).then((result)=>{
                        if(result.isConfirmed)
                        {

                            $.ajax({
                                url : "{{ route('del_reg') }}",
                                type: 'POST',
                                data: {_token:$token,id:$id},
                                success: function(res){
                                    window.location.reload();
                                }
                            })
                        }
                    })
                })
            })
        </script>
    @endpush
@endsection
