@extends('layout.layout')

@section('content')
    {{-- <span>this is received_good</span> --}}
    @if($errors->any())
        <script>
            $(document).ready(function(e){
                $('#add_car').show();
            })
        </script>
    @endif
    <div class="flex justify-between">
        <div class="flex">

            {{-- <div class="flex {{ $main->duration ? 'invisible pointer-events-none' : '' }}"> --}}

            @if (($main->status != 'complete') && $status != 'view')
            <input type="text" id="docu_ipt" class="w-80 h-1/2 min-h-12 shadow-lg border-slate-400 border rounded-xl pl-5 focus:border-b-4 focus:outline-none" placeholder="PO/POI/TO Document...">
            <button  class="h-12 bg-amber-400 text-white px-8 ml-8 rounded-lg hover:bg-amber-500" id="search_btn" hidden>Search</button>
            @endif
            @if (count($driver) > 0)
                <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600" id="driver_info" title="View Car Info"><i class='bx bx-id-card'></i></button>
            @else
                @if (!isset($status))
                    <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600" id="add_driver" title="Add Car Info"><i class='bx bx-car'></i></button>
                @endif
            @endif
        </div>
        <div class="flex">
            <div class="">
                <span class=" mt-2 -translate-x-6  mr-3" >Document No : <b class="text-xl" id="doc_no">{{ $main->document_no ?? '' }}</b></span>
                @if (dc_staff())
                    <span class=" mt-2 -translate-x-6  ms-3" >Source : <b class="text-xl" id="source">{{ $main->source_good->name ?? '' }}</b></span>
                @elseif (!dc_staff() && $main->vendor_name)
                    <span class=" mt-2 -translate-x-6  ms-3" >Vendor : <b class="text-xl" id="vendor">{{ $main->vendor_name ?? '' }}</b></span>
                @endif
            </div>
            @if ($main->status == 'complete')
                <span class="text-emerald-600 font-bold text-3xl ms-40 underline">Complete</span>
                <a href="{{ route('complete_doc_print',['id'=>$main->id]) }}" target="_blank" title="print"><button type="button" class="bg-rose-400 text-white text-xl h-10 px-3 rounded-lg ms-4 hover:bg-rose-600 hover:text-white"><i class='bx bxs-printer'></i></button></a>
            @endif
            @if ($status != 'view' && $cur_driver->start_date)
            <button class="h-12 bg-sky-300 hover:bg-sky-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg mr-1  {{ $main->status == 'complete' ? 'hidden' : '' }}" id="confirm_btn">Continue</button>
            <button class="h-12 bg-emerald-300 hover:bg-emerald-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg  {{ $main->status == 'complete' ? 'hidden' : '' }}" id="finish_btn">Complete</button>
            @endif
        </div>
        <?php
                $total_sec    = get_done_duration($main->id);
        ?>

        <span class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2" id="time_count">
            @if ($main->status == 'complete')
            {{ $main->total_duration }}
            @else
            {{ (isset($status) && $status == 'view') ? ($main->total_duration) : (isset($cur_driver) ? cur_truck_dur($cur_driver->id) : '00:00:00') }}
            @endif
        </span>

    </div>
    <input type="hidden" id="view_" value="{{ isset($status) ? $status : '' }}">
    @if($status != 'view')
        <input type="text" id="bar_code" class=" border mt-1 rounded-lg shadow-lg" value="" >
        <span class="ms-1">previous scanned barcode : <b id="prev_scan">{{ Session::get('first_time_search_'.$main->id) }}</b></span>
        <input type="hidden" id="finished" value="{{ $main->status == 'complete' ? true : false }}">
    @endif
    {{-- @if (isset($status) && $status != 'view') --}}
        <input type="hidden" id="cur_truck" value="{{ $cur_driver->id ?? '' }}">
    {{-- @endif --}}
    <div class="grid grid-cols-2 gap-2">
    <div class="mt-5 border border-slate-400 rounded-md main_product_table" style="min-height: 83vh;max-height:83vh;width:100%;overflow-x:hidden;overflow-y:auto">
            <div class="border border-b-slate-400 h-10 bg-sky-50">
                <span class="font-semibold leading-9 ml-3">
                    List Of Products
                </span>
            </div>
            @if($main->status != 'complete')
            <input type="hidden" id="started_time" value="{{ isset($cur_driver->start_date) ? ($cur_driver->start_date.' '.$cur_driver->start_time) : ''}}">
            {{-- <input type="hidden" id="duration" value="{{ $total_sec ?? 0 }}"> --}}
            <input type="hidden" id="receive_id" value="{{ $main->id }}">
            @endif
            <div class="main_table">
                <table class="w-full" class="main_tb_body">
                    <thead>
                        <tr class="h-10">
                            <th class="border border-slate-400 border-t-0 border-l-0"></th>
                            <th class="border border-slate-400 border-t-0 w-8"></th>
                            <th class="border border-slate-400 border-t-0">Document No</th>
                            <th class="border border-slate-400 border-t-0"><span>Box Barcode</span>
                                <a href="../product_pdf/{{ $main->id }}" target="_blank"><i class='bx bx-download ms-1 hover:text-amber-500'></i></a>
                            </th>
                            <th class="border border-slate-400 border-t-0">Product Name</th>
                            <th class="border border-slate-400 border-t-0">Quantity</th>
                            <th class="border border-slate-400 border-t-0">Scanned</th>
                            <th class="border border-slate-400 border-t-0 border-r-0">Remaining</th>
                        </tr>
                    </thead>
                    <input type="hidden" id="doc_total" value="{{ count($document) }}">

                            <?php
                                $i = 0;
                            ?>
                            @foreach($document as $item)
                                @if (  count(search_pd($item->id)) > 0)
                                    <tbody class="main_body">
                                        @foreach (search_pd($item->id) as $key=>$tem)

                                            <?php
                                                $color = check_color($tem->id);
                                            ?>
                                            <tr class="h-10">
                                                @if ($key == 0)
                                                    <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                                                        {{-- <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc {{ scan_zero($item->id) ? '' : 'hidden ' }}" data-doc="{{ $item->document_no }}"><i class='bx bx-minus'></i></button> --}}
                                                    </td>
                                                    <td class="ps-2 border border-slate-400 border-t-0  doc_times">{{ $i+1 }}</td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ $item->document_no }}</td>
                                                @else
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0 "></td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                                                @endif
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} px-2 bar_code">{{ $tem->bar_code }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }}">{{ $tem->supplier_name }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">{{ $tem->qty }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">
                                                    <div class="main_scan">
                                                        {{ $tem->scanned_qty }}
                                                        @if (!dc_staff() && $cur_driver->start_date)
                                                            <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' data-index="{{ $key }}" title="add quantity"></i>
                                                        @endif
                                                    </div>
                                                    <input type="hidden" class="w-[80%] real_scan" data-id="{{ $tem->id }}" data-old="{{ $tem->scanned_qty }}" value="{{ $tem->scanned_qty }}">
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">{{ $tem->qty - $tem->scanned_qty }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                        <?php $i++ ?>
                                @endif
                            @endforeach

                            <input type="hidden" id="count" value="{{ $i }}">

                </table>
            </div>

        </div>
        <div class="mt-5 grid grid-rows-2 gap-2" style="max-height: 83vh;width:100%; overflow:hidden">
            <div class="border border-slate-400 rounded-md overflow-y-auto overflow-x-hidden main_product_table" style="max-height: 42.5vh;width:100%;">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products
                    </span>
                </div >
                <div class="scan_parent">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity</th>
                            </tr>
                        </thead>
                            <?php $i=0 ?>
                            @if(count($scan_document) > 0)

                                @foreach ($scan_document as $item)
                            @if (count(search_scanned_pd($item->id))>0)
                            <?php
                                $i++;
                            ?>
                                <tbody class="scan_body" >
                                @foreach (search_scanned_pd($item->id) as $index=>$tem)
                                <?php
                                            $color = check_scanned_color($tem->id);
                                            $scanned[]  = $tem->bar_code;
                                            ?>
                                            {{-- @if ($tem->id == get_latest_scan_pd($main->id))
                                            <tr class="h-10">
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest"></td>
                                                @endif
                                                        <td class="ps-2 border border-slate-400 border-t-0  {{ $color }} latest" >{{ $tem->bar_code }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} latest">{{ $tem->supplier_name }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} latest border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                            </tr>

                                            @else --}}
                                                <tr class="h-10 scanned_pd_div">
                                                    @if ($index == 0)
                                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $item->document_no }}</td>
                                                    @else
                                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                    @endif
                                                            <td class="ps-2 border border-slate-400 border-t-0  {{ $color }}">{{ $tem->bar_code }}</td>
                                                            <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">{{ $tem->supplier_name }}</td>
                                                            <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                                </tr>
                                            {{-- @endif --}}
                                            @endforeach
                                        </tbody>

                            @endif
                                @endforeach
                            @endif


                    </table>
                </div>
            </div>
            <input type="hidden" id="user_role" value="{{ getAuth()->role }}">
            <div class="border border-slate-400 rounded-md overflow-x-hidden overflow-y-auto main_product_table" style="max-height: 42.5vh;width:100%">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products (excess / shortage)
                    </span>
                </div>
                <div class="excess_div">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0 w-8"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity</th>
                            </tr>
                        </thead>

                            <?php $i=0 ?>
                            @foreach ($document as $item)
                            @if (count(search_excess_pd($item->id))>0)
                            <?php
                                $i++;
                            ?>
                                <tbody class="excess_body" >
                                @foreach (search_excess_pd($item->id) as $index=>$tem)
                                <?php
                                            ?>
                                            <tr class="h-10">
                                                <td class="ps-1 border border-slate-400 border-t-0 border-l-0">
                                                    @can('adjust-excess')
                                                        @if ($main->status == 'complete'  && ($tem->qty < $tem->scanned_qty))
                                                            <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed" data-id="{{ $tem->id }}"><i class='bx bx-minus'></i></button>
                                                        @endif
                                                    @endcan
                                                </td>
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif
                                                        <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->bar_code }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}
                                                            <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1  bg-emerald-400 rounded-lg px-1 text-white hover:bg-emerald-600 remark_ic'></i>
                                                        </td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-r-0 {{ $tem->scanned_qty > $tem->qty ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tem->scanned_qty - $tem->qty }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                            @endif
                                @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Decision Modal --}}
 {{-- <div class="hidden" id="decision">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                <div
                    class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Choose Document No &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold"
                            onclick="$('#decision').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-4">
                        <span class="">Product Code တူ Document များရှိပါသည်။ မည်သည့် Document တွင် ပေါင်းထည့်ချင်လဲ ရွေးပါ</span>
                    </div>
                    <div class="decision_model">

                    </div>
                </div>
            </div>
        </div>
</div>
</div> --}}
{{-- End Modal --}}
 {{-- Car info Modal --}}
 <div class="hidden" id="car_info">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                <div
                    class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Car Info &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold"
                            onclick="$('#car_info').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="grid grid-cols-2 gap-5 border-b-2 border-slate-600">
                        <div class="flex flex-col">
                            <span class="mb-4 text-xl">Vendor Name      </span>
                            <span class="mb-4 text-xl ">Branch      </span>
                        </div>
                        <div class="flex flex-col">
                            <b class="mb-4 text-xl">:&nbsp;{{ $main->vendor_name ?? '' }}</b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $main->user->branch->branch_name }}</b>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-7  mt-2">
                        @foreach ($driver as $index=>$item)

                        <div class="grid grid-cols-2 gap-5">
                            <div class="flex flex-col ps-4">
                                <span class="mb-4 text-xl">Driver's No     </span>
                                <span class="mb-4 text-xl">Driver's Name     </span>
                                <span class="mb-4 text-xl">Driver's Phone No </span>
                                <span class="mb-4 text-xl">Driver's NRC No </span>
                                <span class="mb-4 text-xl">Truck's No        </span>
                                <span class="mb-4 text-xl">Truck's Type      </span>
                                <span class="mb-4 text-xl">Gate      </span>
                                <span class="mb-4 text-xl">Scanned Qty     </span>
                            </div>
                            <div class="flex flex-col">
                                <b class="mb-4 text-xl">:&nbsp;{{ $index+1 }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->driver_name }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->ph_no }} </b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->nrc_no }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->truck_no }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->truck->truck_name }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->gate == 0 ? getAuth()->branch->branch_name.' Gate' : $item->gates->name }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $item->scanned_goods ?? 0 }}</b>
                            </div>
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
{{-- End Modal --}}

{{-- Add Car Modal --}}
@if (!isset($status))
<div class="hidden" id="add_car">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                <div
                    class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Car Info &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold"
                            onclick="$('#add_car').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('store_car_info') }}" method="POST">
                        @csrf
                            <input type="hidden" name="{{ isset($main) ? 'main_id' : '' }}" value="{{ isset($main) ? $main->id : ''  }}">
                            <div class="grid grid-cols-2 gap-5 my-5">
                                <div class="flex flex-col px-10 relative ">
                                    <label for="truck_no">Truck No<span class="text-rose-600">*</span> :</label>
                                    <input type="text" name="truck_no" id="truck_no" class=" truck_div mt-3 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('truck_no') }}" placeholder="truck..." autocomplete="off">
                                        <ul class="truck_div w-[77%] bg-white shadow-lg max-h-40 overflow-auto absolute car_auto" style="top: 100%">
                                        </ul>
                                    @error('truck_no')
                                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="flex flex-col px-10">
                                    <label for="driver_phone">Driver Phone<span class="text-rose-600">*</span> :</label>
                                    <input type="number" name="driver_phone" id="driver_phone" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('driver_phone') }}" placeholder="09*********">
                                    @error('driver_phone')
                                    <small class="text-rose-500 ms-1">{{ $message }}</small>
                                @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5 my-5">
                                <div class="flex flex-col px-10">
                                    <label for="driver_nrc">Driver NRC<span class="text-rose-600">*</span> :</label>
                                    <input type="text" name="driver_nrc" id="driver_nrc" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('driver_nrc') }}" placeholder="nrc...">
                                    @error('driver_nrc')
                                    <small class="text-rose-500 ms-1">{{ $message }}</small>
                                @enderror
                                </div>

                                <div class="flex flex-col px-10">
                                    <label for="driver_name">Driver Name<span class="text-rose-600">*</span> :</label>
                                    <input type="text" name="driver_name" id="driver_name" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" placeholder="name..." value="{{ old('driver_name') }}">
                                    @error('driver_name')
                                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 my-5">
                                <div class="flex flex-col px-10">
                                    <label for="truck_type">Type of Truck<span class="text-rose-600">*</span> :</label>
                                    <Select name="truck_type" id="truck_type" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                        <option value="">Choose Type of Truck</option>
                                        @foreach ($truck as $item)
                                            <option value="{{ $item->id }}" {{ old('truck_type') == $item->id ? 'selected' : '' }}>{{ $item->truck_name }}</option>
                                        @endforeach
                                    </Select>
                                    @error('truck_type')
                                    <small class="text-rose-500 ms-1">{{ $message }}</small>
                                @enderror
                                </div>

                                <?php
                                    $dc = [17,19,20];
                                ?>
                                @if (in_array(getAuth()->branch_id,$dc))
                                    <div class="flex flex-col px-10">
                                        <label for="gate">Gate<span class="text-rose-600">*</span> :</label>
                                        <Select name="gate" id="gate" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                            <option value="">Choose Gate</option>
                                            @foreach ($gate as $item)
                                                <option value="{{ $item->id }}" {{ old('gate') == $item->id ? 'selected' : '' }}>{{ $item->name.'('.$item->branches->branch_name.')' }}</option>
                                            @endforeach
                                        </Select>
                                        @error('gate')
                                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                                    @enderror
                                    </div>
                                @endif

                            </div>
                        <div class="grid grid-cols-2 gap-5 my-5">

                            <div class="">

                            </div>
                            <div class="">
                                <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</div>
@endif
{{-- End Modal --}}
   {{-- Decision Modal --}}
   <div class="hidden" id="alert_model">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 ">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80 ">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Cursor ထွက်နေပါသဖြင့် scan ဖတ်လို့ရမည် မဟုတ်ပါ &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                            onclick="$('#alert_model').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

            </div>
        </div>
</div>
</div>
{{-- End Modal --}}

@if (!dc_staff())
    {{-- Auth Modal --}}
    <div class="hidden" id="pass_con">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div
                        class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                        <div class="flex px-4 py-2 justify-between items-center min-w-80">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Authorize Confirmation &nbsp;<span
                                    id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold"
                                onclick="$('#pass_con').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <form id="auth_con_form">
                                <div class=" my-1">
                                    <div class="text-center">
                                        <small class="text-rose-500 w-full ms-1 error_msg underline"></small>
                                    </div>
                                    <div class="flex flex-col px-10 relative ">
                                        <label for="employee_code">Employee Id<span class="text-rose-600">*</span> :</label>
                                        <input type="text" name="employee_code" id="employee_code" class=" mt-2 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('employee_code') }}" placeholder="employee code" autocomplete="off">
                                            <small class="text-rose-500 ms-1 error_msg"></small>

                                    </div>

                                    <div class="flex flex-col px-10 mt-4">
                                        <label for="pass">Password<span class="text-rose-600">*</span> :</label>
                                        <input type="password" name="pass" id="pass" class="mt-2 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('pass') }}" placeholder="">
                                        <small class="text-rose-500 ms-1 error_msg"></small>

                                    </div>
                                </div>
                                <input type="hidden" id="index">
                            <div class="grid grid-cols-2 gap-5 my-5">

                                <div class="">

                                </div>
                                <div class="">
                                    <button type="button" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10" id="auth_con">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </div>
    {{-- End Modal --}}

@endif
    @push('js')
        <script >
            $(document).ready(function(e){

                var token = $("meta[name='__token']").attr('content');
                $finish = $('#finished').val();
                $status = $('#view_').val();
                $role = $('#user_role').val();
                $all_begin = $('#started_time').val();
                $count = parseInt($('#count').val()) || 0;
                $cur_id = $('#cur_truck').val() ?? '';
                $dc_staff = "{{ getAuth()->branch_id}}";
                $dc_staff = $dc_staff.includes([17,19,20]) ? true: false;

                function reload_page(){
                    $('.main_table').load(location.href + ' .main_table');
                    $('.scan_parent').load(location.href + ' .scan_parent', function() {
                        $('.excess_div').load(location.href + ' .excess_div', function() {
                            $('.scanned_pd_div').eq(0).find('td').addClass('latest');
                        });
                    });
                }
                // $('.real_scan').eq(0).attr('type','text');

                $(document).on('click','#driver_info',function(e){
                    $('#car_info').toggle();
                })

                if($status != 'view')
                {

                $(document).on('click','#add_driver',function(e){
                    $('#add_car').toggle();
                })

                if(!$finish)
                {
                    $(document).on('click','.change_scan',function(e){
                        $id     = $(this).data('index');
                        $('#index').val($id);
                        $('#employee_code').val('');
                        $('#pass').val('');
                        $('.error_msg').text('');
                        $('.error_msg').eq(0).parent().removeClass('bg-rose-200 pb-1');
                        $('#pass_con').show();
                    })

                    $(document).on('click','#auth_con',function(e){
                        $index  = $('#index').val();
                        $data = $('#auth_con_form').serialize();

                        $notempty = false;
                        if($('#employee_code').val() == '')
                        {
                            $notempty = true;
                            $('.error_msg').eq(1).text('Please Fill Employee Code');
                        }
                        if($('#pass').val() == '')
                        {
                            $notempty = true;
                            $('.error_msg').eq(2).text('Please Fill Password');
                        }
                        if(!$notempty)
                        {
                            $.ajax({
                                url : "{{route('pass_vali')}}",
                                type: 'POST',
                                data:{_token:token,data:$data},
                                beforeSend:function(res){
                                    $('.error_msg').eq(0).parent().removeClass('bg-rose-200 pb-1');
                                    $('.error_msg').text('');
                                },
                                success:function(res){

                                    $('#pass_con').hide();
                                    $('.main_scan').eq($index).attr('hidden',true);
                                    $('.real_scan').eq($index).attr('type','text');
                                    $('.real_scan').eq($index).attr('data-auth',res.id);
                                },
                                error:function(){
                                    $('.error_msg').eq(0).text('Credential Does Not Match!!');
                                    $('.error_msg').eq(0).parent().addClass('bg-rose-200 pb-1');
                                    $('#employee_code').val('');
                                    $('#pass').val('');
                                }
                            })
                        }
                    })

                    $(document).on('blur','.real_scan',function(e){
                        $val    = $(this).val();
                        $old    = $(this).data('old');
                        $pd_id  = $(this).data('id');
                        $auth   = $(this).data('auth');
                        if($old >= $val)
                        {
                            $(this).val($old);
                            $('.main_scan').eq($index).attr('hidden',false);
                            $('.real_scan').eq($index).attr('type','hidden');
                        }else{
                            $add_val = $val - $old ;
                            Swal.fire({
                                icon : 'question',
                                text : `${$add_val}ခု ပေါင်းထည့်မှာ သေချာပါသလား`,
                            showCancelButton:true,
                                confirmButtonText: 'Yes',
                                cancelButtonText : 'No',
                            }).then((result)=>{
                                if(result.isConfirmed)
                                {
                                    $.ajax({
                                        url : "{{ route('add_product') }}",
                                        type: 'POST',
                                        data: {_token:token,data:$add_val,car_id:$cur_id,product:$pd_id,auth:$auth},
                                        success:function(res)
                                        {
                                            reload_page();
                                        }
                                    })
                                }
                            })
                        }
                    })
                }

                if(!$finish && ($role == 2 || $role == 3))
                {
                    $(document).on('keypress', '#docu_ipt', function(e) {
                    if (e.keyCode === 13) {
                        e.preventDefault();
                        $('#search_btn').click();
                        $(this).val('');
                    }
                });

                $(document).on('click','#search_btn',function(e){
                    let id = $('#receive_id').val();
                        let val = $('#docu_ipt').val();
                        $this = $('#docu_ipt');
                        $vendor = $('#vendor_name').text();
                        $.ajax({
                            url     : "{{ route('search_doc') }}",
                            type    : 'POST',
                            data    :  {_token:token,data:val,id:id},
                            success : function(res){
                                if($vendor == ''){
                                    $('#vendor_name').text(res[0].vendorname);
                                }
                                $list = '<tbody class="main_body">';
                                for($i = 0 ; $i < res.length ; $i++)
                                {
                                    if($i == 0){
                                        $list += `
                                        <tr class="h-10">
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                                                        <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc hidden"  data-doc="${res[$i].purchaseno}"><i class='bx bx-minus'></i></button>
                                            </td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0">${Math.floor($count+1)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0">${res[$i].purchaseno}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0  px-2 bar_code">${res[$i].productcode}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0">${res[$i].productname}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 qty">${parseInt(res[$i].goodqty)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 scanned_qty">0</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-r-0 remain_qty">${parseInt(res[$i].goodqty)}</td>
                                        </tr>
                                    `;
                                    $count++;
                                    }else{
                                        $list += `
                                        <tr class="h-10">
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8"></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 "></td>
                                            <td class="ps-2 border border-slate-400 border-t-0  px-2 bar_code">${res[$i].productcode}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 ">${res[$i].productname}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 qty">${parseInt(res[$i].goodqty)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 scanned_qty">0</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-r-0 remain_qty">${parseInt(res[$i].goodqty)}</td>
                                        </tr>
                                        `;
                                    }

                                }
                                $list += `</tbody>`;
                                $length = $('.main_body').length;
                                window.location.reload();
                                // if($length > 0){
                                //     $('.main_body').eq($length-1).after($list);
                                // }else{
                                //     $('.main_table').load(location.href + ' .main_table');

                                // }
                            },
                            error   : function(xhr,status,error){
                                if(xhr.status == 400){
                                    Swal.fire({
                                    icon:'error',
                                    title: 'Warning',
                                    text: 'Doucment တခုကို နှစ်ကြိမ်ထည့်ခွင့်မရှိပါ'
                                })
                                }else if(xhr.status == 404){
                                    Swal.fire({
                                    icon:'error',
                                    title: 'Warning',
                                    text: 'Document မတွေ့ပါ'
                                })
                                }

                            },
                            complete:function(){
                                $this.val('');
                            }
                        })
                })
                var key = '';
                    $(document).on('keypress',function(e){

                        $doc_ipt = e.target.matches('input');
                        $bar_ipt = $('#bar_code').val();
                        if(!$doc_ipt)
                        {
                            if (e.key === 'Enter' && $bar_ipt != '') {
                                if($all_begin != ''  || !$dc_staff )
                                {
                                    $('#bar_code').val(key);
                                    $('#bar_code').trigger('barcode_enter');
                                }else{
                                    Swal.fire({
                                        icon : 'error',
                                        title: 'Warning',
                                        text : 'ကားအချက်အလက် ဖြည့်ပြီးမှ scan ဖတ်နိုင်ပါမည်',
                                        // showConfirmButton:false
                                    })
                                    setTimeout(() => {
                                        Swal.close();
                                    }, 2000);
                                }
                                $('#bar_code').val('');
                                key = '';
                            } else {
                                if(e.key != 'Enter')
                                {
                                    console.log(e.key);
                                    key += e.key;
                                    $('#bar_code').val(key);
                                }
                            }
                        }
                    });

                    $(document).on('barcode_enter','#bar_code',function(e){
                        $val  = $(this).val();

                        $recieve_id = $('#receive_id').val();
                        $this       = $(this);
                        // $cur_id     = $('#cur_truck').val() ?? '';
                        $code       =  $val.replace(/\D/g, '');
                        if($val){
                            $.ajax({
                                url : "{{ route('barcode_scan') }}",
                                type: 'POST',
                                data: {_token:token , data:$val,id:$recieve_id,car : $cur_id},
                                success:function(res){

                                    // if(res.msg == 'decision')
                                    // {
                                    //     $('.decision_model').html('');
                                    //     $list = `<input type="hidden" id="scan_qty" value="${res.qty}">`;
                                    //     for($i = 0 ; $i < res.doc.length ; $i++)
                                    //     {
                                    //         $list +=`
                                    //         <div data-id="${res.ids[$i]}" class="text-center mb-4 shadow-lg rounded-md border border-slate-200 py-3 cursor-pointer hover:bg-slate-200 decision_doc">
                                    //             <span>${res.doc[$i]}</span>
                                    //         </div>
                                    //         `;
                                    //     }
                                    //     $('.decision_model').append($list);
                                    //     $('#decision').show();
                                    // }else{

                                        // $('.bar_code').each((i,v)=>{
                                            // if($(v).text() == $code){
                                                // $scan   = parseInt($(v).parent().find('.scanned_qty').text());
                                                // $real_scan = parseInt($(v).parent().find('.real_scan').val());
                                                // $remain = parseInt($(v).parent().find('.remain_qty').text());
                                                // $qty    = parseInt($(v).parent().find('.qty').text());
                                                // $(v).parent().find('.scanned_qty').text($scan+1 >= $qty ? $qty : Math.floor($scan + res.scanned_qty));
                                                // $(v).parent().find('.remain_qty').text($remain-res.scanned_qty <= 0 ? 0 : Math.floor($remain - res.scanned_qty));
                                                // $(v).parent().find('.real_scan').val(Math.floor($real_scan+1));
                                                // if($scan+res.scanned_qty > 0 && $scan+res.scanned_qty < $qty){
                                                //     console.log('yes');
                                                //     $(v).parent().find('.color_add').each((i,v)=>{
                                                //         $(v).removeClass('bg-amber-200 text-amber-600');
                                                //         $(v).addClass('bg-amber-200 text-amber-600');
                                                //     })

                                                // }else if($scan+res.scanned_qty == $qty){
                                                //     $no = 0;
                                                //     $doc= '';
                                                //     $parent = $(v).parent().parent();
                                                //     $(v).parent().parent().find('tr').each((i,v)=>{
                                                //         if(i == 0){
                                                //             $no = $(v).find('.doc_times').text();
                                                //             $doc = $(v).find('.doc_no').text();
                                                //         }
                                                //         return false;
                                                //     })
                                                //     $(v).parent().remove();
                                                //     $parent.find('tr').each((i,v)=>{
                                                //         if(i == 0){
                                                //             $(v).find('.doc_times').text($no);
                                                //             $(v).find('.doc_no').text($doc);
                                                //         }
                                                //         return false;
                                                //     })
                                                //     if($parent.find('tr').length == 0){
                                                //         $parent.remove()
                                                //     }
                                                //     $('.main_body').each((i,v)=>{
                                                //         $(v).find('tr').eq(0).find('td').eq(0).text(i+1);
                                                //     })
                                                // }
                                                // return false;
                                            // }
                                        // })

                                        if($all_begin == '')
                                        {
                                            window.location.reload();
                                        }
                                        $('#prev_scan').text(res.pd_code);
                                        reload_page();
                                    // }
                                },
                                error : function(xhr,status,error){
                                    $msg = xhr.responseJSON.message;
                                    if($msg == 'Server Time Out Please Try Again')
                                    {
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Server Time Out Please Try Again'
                                        });
                                    }else if($msg == 'Not found'){
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Bar Code Not found'
                                        });
                                    }else if($msg == 'dublicate')
                                    {
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Doucment တခုကို နှစ်ကြိမ်ထည့်ခွင့်မရှိပါ'
                                        });
                                    }else if($msg == 'doc not found')
                                    {
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Doucment မရှိပါ'
                                        });
                                    }

                                    setTimeout(() => {
                                        Swal.close();
                                        }, 3000);
                                },
                                complete:function(){
                                    $this.val('');
                                }

                            })
                        }
                    })

                    $(document).on('click','.decision_doc',function(e)
                    {
                        $id = $(this).data('id');
                        $qty= $('#scan_qty').val();

                        $.ajax({
                            url : "{{ route('add_product_qty') }}",
                            type: "POST",
                            data: {_token:token,id:$id,qty:$qty},
                            success: function(res){
                                $('.scan_parent').load(location.href + ' .scan_parent');
                                $('.excess_div').load(location.href + ' .excess_div');
                            },
                            complete: function(){
                                $('#decision').hide();
                            }
                        })
                    })
                }

                if(!$finish && ($role == 2 || $role == 3) && ($all_begin != '' || !$dc_staff)){
                    window.addEventListener('focus', function() {
                        $('#alert_model').hide();
                    });

                    window.addEventListener('blur', function() {
                        $('#alert_model').show();

                    });

                }


                if(!$finish && ($role == 2 || $role == 3) && ($all_begin != '')){
                    setInterval(() => {
                        time_count();
                    }, 1000);



                    function time_count(){
                        let time = new Date($('#started_time').val()).getTime();
                        // let duration = ($('#duration').val() * 1000);
                        let duration = 0;
                        let now  = new Date().getTime();
                        let diff = Math.floor(now - time + duration);
                        let hour = Math.floor(diff / (60*60*1000));
                        let min = Math.floor((diff % (60 * 60 * 1000)) / (60 * 1000));
                        let sec = Math.floor((diff % (60 * 60 * 1000)) % (60 * 1000) / (1000));

                        $('#time_count').text(hour.toString().padStart(2, '0') + ':' + min.toString().padStart(2, '0') + ':' + sec.toString().padStart(2, '0'));
                    }


                $(document).on('click','.del_doc',function(e){
                    $val = $(this).data('doc');
                    $id = $('#receive_id').val();
                    $this = $(this);
                    Swal.fire({
                        icon : 'info',
                        title: 'Are You Sure?',
                        showCancelButton:true,
                        confirmButtonText:'Yes',
                        cancelButtonText: "No",
                    }).then((result)=>{
                        if(result.isConfirmed)
                        {
                            $.ajax({
                                url : "{{ route('del_doc') }}",
                                type: 'POST',
                                data: {_token:token , data:$val , id : $id},
                                success: function(res){
                                    $this.parent().parent().parent().remove();
                                },
                                error: function(xhr,status,error)
                                {

                                }
                            })
                        }
                    })


                })

            }


            $(document).on('click','#confirm_btn',function(e){
                    $id = $('#receive_id').val();
                    $.ajax({
                        url : "{{ route('confirm') }}",
                        type: 'POST',
                        data:{_token : token , id :$id},
                        success:function(res){
                            location.href = '/list';
                        },
                        error:function(xhr,status,error){
                            Swal.fire({
                                    icon : 'error',
                                    title: 'truck duration မှာ 24 hr ကျော်သွားပါသဖြင့် save မရပါ။'
                                })
                        }
                    })

                })

                $(document).on('click','#finish_btn',function(e){
                    // console.log('yes');
                    // return;
                    $finish = true;
                    $id = $('#receive_id').val();
                    $doc_count = $('#doc_total').val();
                   $('.remain_qty').each((i,v)=>{

                    if(parseInt($(v).text()) > 0){
                        $finish = false;
                        return false;
                    }
                   })

                   if(!$finish)
                   {

                        Swal.fire({
                            'icon'      : 'info',
                            'title'     : 'Are You Sure?',
                            'text'      : 'Remaining QTY ကျန်နေပါသေးသည်?Complete လုပ်ဖို့သေချာပါသလား?',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText:  'No'
                        }).then((result)=>{
                            if(result.isConfirmed){
                                finish($id);
                            }
                        })
                   }else if($doc_count < 1){
                    Swal.fire({
                            'icon'      : 'error',
                            'title'     : 'Warning',
                            'text'      : 'Document မရှိလျှင် Complete လုပ်ခွင့်မပေးပါ',
                        })
                   }else{
                    finish($id);
                   }
                })

                function finish($id)
                {
                    $.ajax({
                            url : "/finish_goods/"+$id,
                            type: 'get',
                            success: function(res){
                                location.href = '/list';
                            },
                            error : function(xhr,status,error){
                                Swal.fire({
                                    icon : 'error',
                                    title: 'truck duration မှာ 24 hr ကျော်သွားပါသဖြင့် save မရပါ။'
                                })
                            }
                        })
                }

            }
            if(!$finish && $role !=2){
                        $(document).on('click','.del_exceed',function(e){
                            $id = $(this).data('id');
                            $.ajax({
                                url: "{{ route('del_exceed') }}",
                                type: 'POST',
                                data: {_token : token , id : $id},
                                success: function(res){
                                    console.log('success');
                                    $('.scan_parent').load(location.href + ' .scan_parent');
                                    $('.excess_div').load(location.href + ' .excess_div');
                                }
                            })
                        })
                    }
            })
        </script>
    @endpush
@endsection
