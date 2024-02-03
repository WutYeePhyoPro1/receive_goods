@extends('layout.layout')

@section('content')
    <div class="m-5">

        <div class="">
            <div class="">
                <span class=" -translate-x-6  mr-3" >Document No : <b class="text-xl" id="doc_no">{{ $reg->document_no ?? '' }}</b></span>
                @if ($detail == 'doc')
                    <span class=" -translate-x-6  ms-3" >Source : <b class="text-xl" id="source">{{ $reg->source_good->name ?? '' }}</b></span>
                    <span class="ml-5  tracking-wider select-none" id="time_count">Total Duration : <b class="text-xl" id="source">{{ get_all_duration($reg->id) }}</b></span>
                    <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600" id="driver_info"><i class='bx bx-id-card'></i></button>
                @elseif ($detail == 'truck')
                    <span class=" -translate-x-6  ms-3" >Truck No : <b class="text-xl" id="source">{{ $driver->truck_no ?? '' }}</b></span>
                    <span class=" -translate-x-6  ms-3" >Truck Type : <b class="text-xl" id="source">{{ $driver->truck->truck_name ?? '' }}</b></span>
                    <span class=" -translate-x-6  ms-3" >Arrived At : <b class="text-xl" id="source">{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></span>
                    <span class=" -translate-x-6  ms-3" >Unload Duration : <b class="text-xl" id="source">{{ $driver->duration ?? '' }}</b></span>

            @elseif ($detail == 'document')
                | <span class=" -translate-x-6  ms-3" >PO/TO Document : <b class="text-xl" id="source">{{ $document->document_no ?? '' }}</b></span>
                | <span class=" -translate-x-6  ms-3" >Total Cateogry : <b class="text-xl" id="source">{{ get_category($document->id) ?? '' }}</b></span>
                | <span class=" -translate-x-6  ms-3" >Total Product Qty : <b class="text-xl" id="source">{{ get_doc_total_qty($document->id,'all') ?? '' }}</b></span>
                | <span class=" -translate-x-6  ms-3" >Total Unloaded Product Qty : <b class="text-xl" id="source">{{ get_doc_total_qty($document->id,'unloaded') ?? '' }}</b></span>
            @endif
            <?php
                switch ($detail)
                {
                    case 'doc'      : $id = $reg->id;break;
                    case 'truck'    : $id = $driver->id;break;
                    case 'document' : $id = $document->id;break;
                    default         : $id = '';break;
                }
                $excel_url = "/detail_excel_export/".$id.'/'.$detail;
                switch ($detail)
                {
                    case 'doc'      :  $print_url = "/truck_detail_pdf/".$id;break;
                    case 'truck'    :  $print_url = "/truck_detail_pdf/".$id;break;
                    case 'document' :  $print_url = "/document_detail_pdf/".$id;break;
                    default         :  $print_url = request()->url();break;
                }


            ?>
            <button type="button" class="bg-sky-400 text-white text-xl h-10 px-3 rounded-lg ms-4 mt-9 hover:bg-sky-600 hover:text-white" onclick="javascript:window.location.href='{{ $excel_url }}'"><i class='bx bx-export'></i></button>
            <a href="{{ $print_url }}" target="_blank"><button type="button" class="bg-rose-400 text-white text-xl h-10 px-3 rounded-lg ms-4 mt-9 hover:bg-rose-600 hover:text-white"><i class='bx bxs-printer'></i></button></a>
            </div>

            @if ($detail != 'document')
                <table class="w-full mt-4">
                    <thead>
                        <tr class="">
                            @if ($detail == 'doc')
                                <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                                <th class="py-2 bg-slate-400 border">Document No</th>
                                <th class="py-2 bg-slate-400  border">Bar Code</th>
                                <th class="py-2 bg-slate-400  border">Product Name</th>
                                <th class="py-2 bg-slate-400  border">Total Qty</th>
                                <th class="py-2 bg-slate-400  border">Scanned Qty</th>
                                <th class="py-2 bg-slate-400  rounded-tr-md">Created At</th>
                            @elseif ($detail == 'truck')
                                <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                                <th class="py-2 bg-slate-400 border">Document No</th>
                                <th class="py-2 bg-slate-400  border">Bar Code</th>
                                <th class="py-2 bg-slate-400  border">Product Name</th>
                                <th class="py-2 bg-slate-400  rounded-tr-md">Unloaded Qty</th>

                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @if ($detail == 'doc')
                            <?php
                            $i = 0;
                        ?>
                            @foreach($document as $item)
                            @if (count(search_pd($item->id)) > 0)
                                <tbody class="main_body">

                                    @foreach (search_pd($item->id) as $key=>$tem)

                                        <?php
                                            $color = check_color($tem->id);
                                        ?>
                                        <tr class="h-10">
                                        @if ($key == 0)

                                                <td class="ps-2 border border-slate-400 border-t-0  doc_times">{{ $i+1 }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ $item->document_no }}</td>
                                            @else
                                                <td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
                                                <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                                            @endif
                                            <td class="ps-2 border border-slate-400 border-t-0 px-2 bar_code">{{ $tem->bar_code }}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 qty">{{ $tem->qty }}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 scanned_qty">{{ $tem->scanned_qty }}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 ">{{ $tem->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                    <?php $i++ ?>
                            @endif

                        @endforeach
                        @elseif ( $detail == 'truck')
                                <?php
                                $i = 0;
                            ?>
                            @foreach ($document as $item)
                            @if (count(get_truck_product($item,$driver->id)) > 0)
                                @foreach (get_truck_product($item,$driver->id) as $key=>$tem)
                                <tr class="h-10">
                                    @if ($key == 0)

                                            <td class="ps-2 border border-slate-400 border-t-0  doc_times">{{ $i+1 }}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ getDocument($item)->document_no }}</td>
                                        @else
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                                        @endif
                                        <td class="ps-2 border border-slate-400 border-t-0 px-2 bar_code">{{ $tem->product->bar_code }}</td>
                                        <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->product->supplier_name }}</td>
                                        <td class="ps-2 border border-slate-400 border-t-0 qty">{{ $tem->product->scanned_qty }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <?php
                            $i++;
                        ?>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            @else
                @foreach ($truck as $item)
                <div class="mt-5">
                    <span class="p-2 px-16 shadow-xl border-2 border-slate-100">{{ $item->truck_no }}</span>
                </div>
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border" style="">Product Code</th>
                            <th class="py-2 bg-slate-400  border">Supplier Name</th>
                            <th class="py-2 bg-slate-400  rounded-tr-md">Unloaded Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (get_product_per_truck($item->id,$document->id) as $index=>$tem)
                            <tr>
                                <td class="ps-2 border border-slate-400 border-t-0 py-1 text-center ">{{ $index+1 }}</td>
                                <td class="ps-2 border border-slate-400 border-t-0 py-1 text-center ">{{ $tem->product->bar_code }}</td>
                                <td class="ps-2 border border-slate-400 border-t-0 py-1 text-center ">{{ $tem->product->supplier_name }}</td>
                                <td class="ps-2 border border-slate-400 border-t-0 py-1 text-center ">{{ $tem->scanned_qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            @endif
        </div>
    </div>
   @if ($detail == 'doc')
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
                                <b class="mb-4 text-xl">:&nbsp;{{ $reg->vendor_name ?? '' }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $reg->user->branch->branch_name }}</b>
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
                                    <span class="mb-4 text-xl">Scanned Qty      </span>
                                </div>
                                <div class="flex flex-col">
                                    <b class="mb-4 text-xl">:&nbsp;{{ $index+1 }}</b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->driver_name }}</b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->ph_no }} </b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->nrc_no }}</b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->truck_no }}</b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->truck->truck_name }}</b>
                                    <b class="mb-4 text-xl">:&nbsp;{{ $item->gates->name }}</b>
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
   @endif

    @push('js')
        <script>

            $(document).ready(function(){
                $(document).on('click','#driver_info',function(e){
                    $('#car_info').toggle();
                })
            })
        </script>
    @endpush
@endsection
