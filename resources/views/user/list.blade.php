@extends('layout.layout')

@section('content')
    <div class="m-5">
        <form action="{{ route('list') }}" method="Get">
        <div class="grid grid-cols-7 gap-4">
                {{-- <div class="flex flex-col">
                    <label for="doc_name">Document Number :</label>
                    <input type="text" name="doc" id="doc_name" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2">
                </div> --}}

                <input type="hidden" id="user_role" value="{{ getAuth()->role }}">
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

                    <div class="flex flex-col">
                        <label for="search_data">Search Data :</label>
                        <input type="text" name="search_data" id="search_data" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('search_data') ?? '' }}">
                    </div>

                <div class="">
                    <button class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
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
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">Branch</th>
                        <th class="py-2 bg-slate-400 border">Status</th>
                        <th class="py-2 bg-slate-400 border">Document</th>
                        <th class="py-2 bg-slate-400 border">Source</th>
                        {{-- <th class="py-2 bg-slate-400 border">PO QTY</th>
                        <th class="py-2 bg-slate-400 border">Remain QTY</th>
                        <th class="py-2 bg-slate-400 border">Exceed QTY</th> --}}
                        <th class="py-2 bg-slate-400 border">Start Date</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Total Unload Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ getAuth()->branch->branch_name }}</td>
                            <td class="h-10 text-center border border-slate-400 {{ $item->status == 'complete' ? 'text-green-600' : 'text-amber-600' }}">{{ $item->status }}</td>
                            <td class="h-10 text-center border border-slate-400">
                                <input type="hidden" class="check_empty" value="{{ check_empty($item->id) }}">
                                <span class="cursor-pointer hover:underline hover:font-semibold {{ $item->status == 'complete' ? 'text-emerald-600' : '' }}" onclick="$(this).parent().find('.view_goods').click();">{{ $item->document_no }}</span> &nbsp;
                                <i class='bx bxs-show text-amber-400 cursor-pointer ms-3 text-lg view_goods hidden' title="view Document" onclick="javascript:window.location.href = '/view_goods/'+{{$item->id}}"></i>
                                @can('add-new-truck')
                                    @if ($item->status == 'incomplete')
                                        <i class='bx bx-message-square-edit text-sky-600 cursor-pointer ms-3 text-lg edit_view' data-id="{{ $item->id }}" style="transform: translateY(2px)"></i>
                                    {{-- @else --}}
                                        {{-- <i class='bx bxs-folder text-emerald-400 cursor-pointer ms-3 text-lg' title="new truck" onclick="javascript:window.location.href = '/receive_goods/'+{{$item->id}}"></i> --}}
                                    @endif

                                    @if (count(truck_arrive($item->id)) > 0)
                                        @foreach (truck_arrive($item->id) as $tem)
                                            <i class='bx bxs-group text-rose-400 cursor-pointer ms-3 text-lg' title="{{ $tem->truck_no }}" onclick="javascript:window.location.href = '/join_receive/'+{{$item->id}}+'/'+{{ $tem->id }}"></i>
                                        @endforeach
                                    @endif
                                @endcan

                            </td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->source_good->name }}</td>
                            {{-- <td class="h-10 text-center border border-slate-400">{{ get_total_qty($item->id) }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->remaining_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->exceed_qty }}</td> --}}
                            <td class="h-10 text-center border border-slate-400">{{ $item->start_date.' '.$item->start_time }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->total_duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (request('search') || request('search_data') || request('branch') || request('status') || request('from_date') || request('to_date'))
        <div class="mt-2">
            <button class="bg-sky-600 text-white px-3 py-2 rounded-md" onclick="javascirpt:window.location.href = 'list'">Back to Default</button>
        </div>
        @endif
        <div class="flex justify-center text-xs mt-2 bg-white mt-6">
            {{ $data->appends(request()->query())->links() }}
    </div>
    </div>

    @push('js')
        <script>

            $(document).ready(function(){

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
                        if($role == 2 || $role == 3){
                            window.location.href = 'car_info/'+$id;
                        }else{
                            window.location.href = 'receive_goods/'+$id;
                        }
                    }
                })
            })
        </script>
    @endpush
@endsection
