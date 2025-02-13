@extends('layout.layout')
<style>
    #resultCount {
        margin-top: 10px;
        font-weight: bold;
        color: rgb(214, 42, 11);
        display: none;
    }

    #back {
        display: none;
    }

    .td-container,
    .td-barcode-container {
        position: relative;
        height: 100%;
    }


    .copy-button,
    .scan-copy-button,
    .excess-copy-button,
    .copy-button-barcode,
    .scan-copy-button-barcode,
    .excess-copy-button-barcode {
        position: absolute;
        bottom: 2px;
        right: 2px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;

    }

    .copy-button,
    .scan-copy-button,
    .excess-copy-button,
    .copy-button-barcode,
    .scan-copy-button-barcode,
    .excess-copy-button-barcode,
    i {
        font-size: 10px;
        color: black;
    }

    .pause_scan {
        cursor: pointer;
    }
</style>
@if (session('error_message'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Error',
                icon: 'error',
                text: '{{ session('error_message') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@section('content')
    @if ($errors->any())
        <script>
            $(document).ready(function(e) {
                $('#add_car').show();
            })
        </script>
    @endif
    <div class="flex justify-between">
        <div class="flex">
            {{-- <div class="flex {{ $main->duration ? 'invisible pointer-events-none' : '' }}"> --}}

            @if ($main->status != 'complete' && $status != 'view')
                <input type="text" id="docu_ipt"
                    class="w-80 h-1/2 min-h-12 shadow-lg border-slate-400 border rounded-xl pl-5 focus:border-b-4 focus:outline-none"
                    placeholder="PO/POI/TO Document...">
                <button class="h-12 bg-amber-400 text-white px-8 ml-8 rounded-lg hover:bg-amber-500" id="search_btn"
                    hidden>Search</button>
            @endif
            @if (count($driver) > 0)
                <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600" id="driver_info"
                    title="View Car Info"><i class='bx bx-id-card mt-2'></i></button>
            @else
                @if (dc_staff())
                    <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600"
                        id="add_driver" title="Add Car Info"><i class='bx bx-car mt-2'></i></button>
                @endif
            @endif

            @if (image_exist($main->id))
                <button class="h-12 bg-amber-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-amber-600"
                    id="show_image" title="Show Image"><i class='bx bxs-image mt-2'></i></button>
            @endif
            @if ($status == 'edit')
                <button class="h-12 bg-sky-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-sky-600" id="edit_image"
                    title="Edit Image" onclick="$('#car_choose').show()"><i class='bx bxs-image-add mt-2 ms-1'></i></button>
            @endif
        </div>
        <div class="flex">
            <div class="flex flex-col">
                <span class=" mt-2 -translate-x-6  mx-3">Document No : <b class="text-xl"
                        id="doc_no">{{ $main->document_no ?? '' }}</b></span>
                @if (dc_staff())
                    <span class=" mt-2 -translate-x-6  ms-3">Source : <b class="text-xl"
                            id="source">{{ $main->source_good->name ?? '' }}</b></span>
                @elseif (!dc_staff() && $main->vendor_name)
                    <span class=" mt-2 -translate-x-6  ms-3">Vendor : <b class="text-xl"
                            id="vendor">{{ $main->vendor_name ?? '' }}</b></span>
                @endif
            </div>
            @if ($main->status == 'complete')
                <span class="text-emerald-600 font-bold text-3xl ms-40 underline">Complete</span>
                <!-- <a href="{{ route('complete_doc_print', ['id' => $main->id]) }}" target="_blank" title="print"><button type="button" class="bg-rose-400 text-white text-xl h-10 px-3 rounded-lg ms-4 hover:bg-rose-600 hover:text-white"><i class='bx bxs-printer'></i></button></a> -->
            @endif

            @if ($cur_driver)
                @if (
                    $status != 'view' &&
                        isset($cur_driver->start_date) &&
                        ($main->user_id == getAuth()->id || $cur_driver->user_id == getAuth()->id))
                    <button
                        class="h-12 bg-sky-300 hover:bg-sky-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg mr-1  {{ $main->status == 'complete' ? 'hidden' : '' }}"
                        id="confirm_btn">Continue</button>
                    <button
                        class="h-12 bg-emerald-300 hover:bg-emerald-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg  {{ $main->status == 'complete' ? 'hidden' : '' }}"
                        id="finish_btn">Complete</button>
                @elseif(!isset($cur_driver->start_date) && !dc_staff() && $status == 'scan' && $main->status != 'complete')
                    <button
                        class="h-12 bg-rose-300 hover:bg-rose-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg"
                        id="start_count_btn">Start Count</button>
                @endif
            @elseif($driver_last)
                {{-- @if ($status != 'view' && isset($driver_last->start_date) && ($main->scan_user_id == getAuth()->id || $driver_last->scan_user_id == getAuth()->id)) --}}
                @if ($status != 'view' && $driver_last->scan_user_id == getAuth()->id)
                    <button
                        class="h-12 bg-sky-300 hover:bg-sky-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg mr-1  {{ $main->status == 'complete' ? 'hidden' : '' }}"
                        id="confirm_btn">Continue</button>
                    <button
                        class="h-12 bg-emerald-300 hover:bg-emerald-600 text-white px-10 2xl:px-16 tracking-wider font-semibold rounded-lg  {{ $main->status == 'complete' ? 'hidden' : '' }}"
                        id="finish_btn">Complete</button>
                @endif
            @endif



        </div>
        <?php
        $total_sec = get_done_duration($main->id);
        ?>

        @if ($cur_driver)
            <span
                class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2"
                id="time_count">
                @if ($main->status == 'complete')
                    {{ $main->total_duration }}
                @else
                    {{ isset($status) && $status == 'view' ? $main->total_duration : (isset($cur_driver) ? cur_truck_dur($cur_driver->id) : '00:00:00') }}
                @endif
            </span>
        @elseif($driver_last)
            @if (isset($status) && $status == 'view')
                <span
                    class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2">
                    {{ $main->total_duration }}
                </span>
            @elseif(isset($status) &&
                    $status == 'scan' &&
                    $main->status == 'incomplete' &&
                    ($main->scan_user_id == getAuth()->id || $driver_last->scan_user_id == getAuth()->id))
                <span
                    class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2"
                    id="time_count_pause">
                    {{ $driver_last->duration }}
                </span>
            @elseif(request()->has('from_join'))
                <span
                    class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2">
                </span>
            @else
                <span
                    class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2">

                    {{ $driver_last->duration }}
                </span>
            @endif
        @else
            <span
                class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap ml-2 2xl:ml-2">
            </span>
        @endif

    </div>
    <input type="hidden" id="view_" value="{{ isset($status) ? $status : '' }}">
    <input type="hidden" id="wh_remark" value="{{ $main->remark }}">
    @if ($status != 'view')
        <div class="d-none">
            <input type="text" id="bar_code" class="pointer-events-none border mt-1 rounded-lg shadow-lg"
                value="">
            <span class="ms-1">previous scanned barcode : <b
                    id="prev_scan">{{ Session::get('first_time_search_' . $main->id) }}</b></span>
            <input type="hidden" id="finished" value="{{ $main->status == 'complete' ? true : false }}">
        </div>
    @endif

    {{-- @if (isset($status) && $status != 'view') --}}
    {{-- <input type="hidden" id="cur_truck" value="{{ $cur_driver->id ?? '' }}"> --}}
    <input type="hidden" id="cur_truck" value="{{ $cur_driver->id ?? ($driver_last->id ?? '') }}">


    {{-- @endif --}}

    <div class="flex flex-wrap -mx-2">
        <div class="w-1/2 px-2 mt-4">
            <div class="form-group flex items-center space-x-4">
                <select id="documentNoSelect" class="form-select block w-full mt-1">
                    <option value="">search document no</option>
                    @foreach ($scan_document_no as $documentNo)
                        <option id="documentNoInput" value="{{ $documentNo }}">{{ $documentNo }}</option>
                    @endforeach
                </select>
                <select id="barcodeSelect" class="form-select block w-full mt-1">
                    <option value="">search bar code</option>
                    @foreach ($document as $data)
                        @php
                            $barcodes = search_pd_barcode($data['id']);
                        @endphp
                        @foreach ($barcodes as $barcode)
                            <option value="{{ $barcode }}">{{ $barcode }}</option>
                        @endforeach
                    @endforeach
                </select>
                <input id="idInput" type="hidden" name="id" value="{{ $id }}">
                <button id="document_no_search"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
                <button id="back"
                    onclick="javascript:window.location.href = '{{ $page == 'receive' ? '/receive_goods/' : '/view_goods/' }}{{ $id }}'"
                    class="bg-blue-500 bg-big hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</button>
            </div>
            <p id="resultCount" class="mt-2">Not result found</p>
        </div>
        {{-- <div class="w-1/2 px-2 mt-4">
            <div class="form-group flex items-center space-x-4">
                <input type="text" id="searchInput" class="form-input block w-full mt-1 border border-gray-300 rounded p-2" placeholder="Search document no or bar code">
                <input id="idInput" type="hidden" name="id" value="{{ $id }}">
                <button id="searchButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
            <div id="suggestions" class="bg-white border border-gray-300 rounded shadow-lg"></div>
            <div id="searchResults" class="mt-4"></div>

        </div> --}}

        <div class="w-1/2 px-2 flex justify-end my-4">
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold rounded py-3 px-4 mr-2" data-bs-toggle="modal"
                data-bs-target="#trashProducts" style="position: relative">
                <i class="fa-solid fa-trash-can"></i>
                <span id="badage" class="text-center"
                    style="position: absolute;top:-12;right:-12;background-color:red;color:white;border-radius:40%;padding:2px 5px;border:solid 2px white;font-size:12px">0</span>
            </button>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal w-100" id="trashProducts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="height: 100vh;backdrop-filter:blur(5px)">
        <div class="modal-dialog modal-dialog-scrollable d-flex justify-content-center align-items-center"
            style="height: 80vh">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Unavailable Scanned Products</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="trash-products">
                        <img class="no_data_image" src="{{ asset('image/error_image/No data-cuate.png') }}"
                            alt="">
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2" style="margin-top:-50px">
        <div class="mt-5 border border-slate-400 rounded-md main_product_table"
            style="min-height: 83vh;max-height:83vh;width:100%;overflow-x:hidden;overflow-y:auto">
            <div class="border border-b-slate-400 h-10 bg-sky-50">
                <span class="font-semibold leading-9 ml-3">
                    List Of Products
                </span>
            </div>
            @if ($main->status != 'complete')

                @if ($status != 'view')
                    @if ($cur_driver)
                        <input type="hidden" id="started_time"
                            value="{{ isset($cur_driver->start_date) ? $cur_driver->start_date . ' ' . $cur_driver->start_time : '' }}">
                    @elseif($driver_last)
                        <input type="hidden" id="started_time_pause" value="{{ $driver_last->duration }}">
                    @endif
                @endif

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
                                <a href="../product_pdf/{{ $main->id }}" target="_blank"><i
                                        class='bx bx-download ms-1 hover:text-amber-500'></i></a>
                            </th>
                            <th class="border border-slate-400 border-t-0">Product Name</th>
                            <th class="border border-slate-400 border-t-0">Quantity</th>
                            <th class="border border-slate-400 border-t-0">Scanned</th>
                            <th class="border border-slate-400 border-t-0 border-r-0">Remaining</th>
                        </tr>
                    </thead>
                    {{-- <input type="hidden" id="doc_total" value="{{ count($document) }}">
                        <?php
                        $i = 0;
                        $j = 0;
                        ?>
                        @foreach ($document as $item)
                            @if (count(search_pd($item->id)) > 0)
                                <tbody class="main_body">
                                    @foreach (search_pd($item->id) as $key => $tem)
                                        <?php
                                        $color = check_color($tem->id);
                                        ${'id' . $key} = $key;
                                        ?>
                                        <tr class="h-10">
                                            @if ($key == 0)
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                                                @if ((!dc_staff() && $cur_driver && getAuth()->id == $cur_driver->user_id) || dc_staff())
                                                    <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc {{ scan_zero($item->id) ? '' : 'hidden ' }}" data-doc="{{ $item->document_no }}"><i class='bx bx-minus'></i></button>
                                                @endif
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
                                            <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">
                                                <span class="cursor-pointer hover:underline hover:font-semibold sticker select-none" data-index="{{ $j }}">{{$tem->qty }}</span>
                                                <input type="hidden" class="pd_unit" value="{{ $tem->unit }}">
                                                <input type="hidden" class="pd_name" value="{{ $tem->supplier_name }}">
                                                <input type="hidden" class="pd_id" value="{{ $tem->id }}">
                                                <div class='px-5 bar_stick1 hidden' >{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,50 ) !!}</div>
                                                <div class='px-5 bar_stick2 hidden' >{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,22 ) !!}</div>
                                                <div class='px-5 bar_stick3 hidden' >{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,50 ) !!}</div>
                                            </td>

                                            <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">
                                                <div class="main_scan">
                                                    {{ $tem->scanned_qty }}
                                                    @if (isset($cur_driver->start_date))
                                                        <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' data-index="{{ $j }}" title="add quantity"></i>
                                                    @endif
                                                </div>
                                                <input type="hidden" class="w-[80%] real_scan border border-slate-400 rounded-md" data-id="{{ $tem->id }}" data-old="{{ $tem->scanned_qty }}" value="{{ $tem->scanned_qty }}">
                                            </td>
                                            <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">{{ $tem->qty - $tem->scanned_qty }}</td>
                                        </tr>
                                        <?php
                                        $j++;
                                        ?>
                                    @endforeach
                                </tbody>
                                <?php $i++; ?>
                            @endif
                        @endforeach
                        <input type="hidden" id="count" value="{{ $i }}"> --}}

                    <input type="hidden" id="doc_total" value="{{ count($document) }}">
                    <?php
                    $i = 0;
                    $j = 0;
                    ?>
                    @foreach ($document as $item)
                        @if (count(search_pd($item->id)) > 0)
                            <tbody class="main_body">
                                @foreach (search_pd($item->id) as $key => $tem)
                                    <?php
                                    $color = check_color($tem->id);
                                    ${'id' . $key} = $key;
                                    ?>
                                    <tr class="h-10">
                                        @if ($key == 0)
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                                                @if (
                                                    (!dc_staff() && $cur_driver && getAuth()->id == $cur_driver->user_id) ||
                                                        (!dc_staff() && $driver_last && getAuth()->id == $driver_last->scan_user_id) ||
                                                        dc_staff())
                                                    <button
                                                        class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc {{ scan_zero($item->id) ? '' : 'hidden ' }}"
                                                        data-doc="{{ $item->document_no }}"><i
                                                            class='bx bx-minus'></i></button>
                                                @endif
                                            </td>
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_times">
                                                {{ $i + 1 }}</td>
                                            {{-- <td class="ps-2 border border-slate-400 border-t-0 doc_no">
                                                    <div class="container">
                                                        <span id="doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                                                        <button id="btn-copy-doc-{{ $item->document_no }}" class="copy-button" onclick="copyText('doc-no-{{ $item->document_no }}', 'btn-copy-doc-{{ $item->document_no }}')">
                                                            <i class="fa-solid fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td> --}}
                                            <td class="td-container ps-2 border border-slate-400 border-t-0 doc_no">
                                                <span>{{ $item->document_no }}</span>
                                                <button data-copy-id="{{ $item->document_no }}" class="copy-button">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                        @else
                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0 "></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                                        @endif

                                        <td
                                            class="td-barcode-container ps-2 border border-slate-400 border-t-0 color_add {{ $color }} px-2 bar_code">
                                            @if (!$color)
                                                <button
                                                    class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_barcode"
                                                    data-barcode="{{ $tem->bar_code }}" data-id = "{{ $tem->id }}">
                                                    <i class="bx bx-minus"></i>
                                                </button>
                                            @endif


                                            {{-- <span data-barCode="{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
                                                    <button data-copy-id="{{ $tem->bar_code }}" data-barCode="{{ $tem->bar_code }}" class="copy-button-barcode">
                                                        <i class="fas fa-copy"></i>
                                                    </button> --}}
                                            <span>{{ $tem->bar_code }}</span>
                                            <button data-copy-id="{{ $tem->bar_code }}" class="copy-button-barcode">
                                                <i class="fas fa-copy"></i>
                                            </button>

                                        </td>
                                        <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }}">
                                            {{ $tem->supplier_name }}</td>
                                        <td
                                            class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">
                                            <span
                                                class="cursor-pointer hover:underline hover:font-semibold sticker select-none"
                                                data-index="{{ $j }}">{{ $tem->qty }}</span>
                                            <input type="hidden" class="pd_unit" value="{{ $tem->unit }}">
                                            <input type="hidden" class="pd_name" value="{{ $tem->supplier_name }}">
                                            <input type="hidden" class="pd_id" value="{{ $tem->id }}">
                                            <div class='px-5 bar_stick1 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 50) !!}</div>
                                            <div class='px-5 bar_stick2 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 22) !!}</div>
                                            <div class='px-5 bar_stick3 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 50) !!}</div>
                                        </td>
                                        <td
                                            class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">
                                            <div class="main_scan">
                                                {{ $tem->scanned_qty }}
                                                @if (!$driver_last)
                                                    {{-- <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' data-index="{{ $j }}" title="add quantity"></i> --}}
                                                @else
                                                    @if (isset($driver_last->start_date))
                                                        <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan'
                                                            data-index="{{ $j }}" title="add quantity"></i>
                                                    @endif
                                                @endif
                                            </div>
                                            <input type="hidden"
                                                class="w-[80%] real_scan border border-slate-400 rounded-md"
                                                data-id="{{ $tem->id }}" data-old="{{ $tem->scanned_qty }}"
                                                value="{{ $tem->scanned_qty }}">
                                        </td>
                                        <td
                                            class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">
                                            {{ $tem->qty - $tem->scanned_qty }}</td>
                                    </tr>
                                    <?php
                                    $j++;
                                    ?>
                                @endforeach
                            </tbody>
                            <?php $i++; ?>
                        @endif
                    @endforeach
                    <input type="hidden" id="count" value="{{ $i }}">
                    <tbody class="search_main_body">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5 grid grid-rows-2 gap-2" style="max-height: 83vh;width:100%; overflow:hidden">
            <div class="border border-slate-400 rounded-md overflow-y-auto overflow-x-hidden main_product_table"
                style="max-height: 42.5vh;width:100%;">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products
                    </span>
                </div>
                {{-- {{ $product_barcode }} --}}
                <div class="scan_parent">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Scann Quantity</th>
                            </tr>
                        </thead>
                        <?php $i = 0; ?>
                        @if (count($scan_document) > 0)
                            @foreach ($scan_document as $item)
                                @if (count(search_scanned_pd($item->id)) > 0)
                                    <?php
                                    $i++;
                                    ?>
                                    <tbody class="scan_body">
                                        @foreach (search_scanned_pd($item->id) as $index => $tem)
                                            <?php
                                            $color = check_scanned_color($tem->id);
                                            $scanned[] = $tem->bar_code;
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
                                            {{-- <tr class="h-10 scanned_pd_div">
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 {{ check_all_scan($item->id) ? 'bg-green-200 text-green-600' : '' }}">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif
                                                <td class="ps-2 border border-slate-400 border-t-0  {{ $color }}">{{ $tem->bar_code }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">{{ $tem->supplier_name }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                            </tr> --}}
                                            <tr class="h-10 scanned_pd_div">
                                                @if ($index == 0)
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0">
                                                        {{ $i }}</td>

                                                    <td
                                                        class="td-container ps-2 border border-slate-400 border-t-0 border-l-0 {{ check_all_scan($item->id) ? 'bg-green-200 text-green-600' : '' }}">
                                                        {{-- {{ $item->document_no }} --}}

                                                        <span>{{ $item->document_no }}</span>
                                                        <button data-copy-id="{{ $item->document_no }}"
                                                            class="scan-copy-button">
                                                            <i class="fas fa-copy"></i>
                                                        </button>

                                                        {{-- <div class="container">
                                                            <span id="doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                                                            <button id="btn-copy-doc-{{ $item->document_no }}" class="copy-button" onclick="copyText('scan-doc-no-{{ $item->document_no }}', 'btn-copy-doc-{{ $item->document_no }}')">
                                                                <i class="fa-solid fa-copy"></i>
                                                            </button>
                                                        </div> --}}
                                                    </td>
                                                @else
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif

                                                <td
                                                    class="td-barcode-container ps-2 border border-slate-400 border-t-0  {{ $color }}">
                                                    @if (barcode_equal($product_barcode, $tem->bar_code))
                                                        <button class="pause_scan" id="{{ $tem->id }}"
                                                            data-status="{{ $tem->scann_pause }}"
                                                            data-bar_code="{{ $tem->bar_code }}"
                                                            data-po="{{ $item->document_no }}"><i
                                                                class='bx {{ $tem->scann_pause === 1 ? 'bx-play-circle' : 'bx-pause-circle' }} text-sm'></i></button>
                                                    @endif
                                                    <span
                                                        data-bar_code="{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
                                                    <button data-copy-id="{{ $tem->bar_code }}"
                                                        class="scan-copy-button-barcode">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>

                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">
                                                    {{ $tem->supplier_name }}</td>

                                                <td
                                                    class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">
                                                    {{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty }}
                                                </td>
                                                <td
                                                    class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0 no">
                                                    {{ $tem->scann_count }}</td>
                                            </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                @endif
                            @endforeach
                        @endif

                        <tbody class="search_scan_body"></tbody>

                    </table>
                </div>
            </div>
            <input type="hidden" id="user_role" value="{{ getAuth()->role }}">
            <div class="border border-slate-400 rounded-md overflow-x-hidden overflow-y-auto main_product_table"
                style="max-height: 42.5vh;width:100%">
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

                        <?php $i = 0; ?>

                        {{-- @foreach ($document as $item)
                                @if (count(search_excess_pd($item->id)) > 0)
                                <?php
                                $i++;
                                ?>
                                    <tbody class="excess_body">
                                    @foreach (search_excess_pd($item->id) as $index => $tem)
                                    <?php
                                    ?>
                                    <tr class="h-10">
                                        <td class="ps-1 border border-slate-400 border-t-0 border-l-0">
                                            @can('adjust-excess')
                                                @if ($main->status == 'complete' && $tem->qty < $tem->scanned_qty)
                                                    <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed" data-id="{{ $tem->id }}"><i class='bx bx-minus'></i></button>
                                                @endif
                                            @endcan
                                        </td>
                                        @if ($index == 0)
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                <td class="td-container ps-2 border border-slate-400 border-t-0 border-l-0">
                                                        <span id="excess-doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                                                        <button id="excess-btn-copy-doc-{{ $item->document_no }}" class="excess-copy-button">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                </td>
                                        @else
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                        @endif
                                                <td class="td-barcode-container ps-2 border border-slate-400 border-t-0">
                                                        <span id="excess-bar-code-{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
                                                        <button id="excess-btn-copy-bar-{{ $tem->bar_code }}" class="excess-copy-button-barcode">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}
                                                    <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1 rounded-lg px-1 text-white {{ !isset($tem->remark) ? 'bg-emerald-400 hover:bg-emerald-600' : 'bg-sky-400 hover:bg-sky-600' }} remark_ic' data-pd="{{ $tem->bar_code }}" data-id="{{ $tem->id }}" data-eq="{{ $index }}"></i>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0 {{ $tem->scanned_qty > $tem->qty ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tem->scanned_qty - $tem->qty }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>

                                @endif
                            @endforeach --}}

                        @foreach ($document as $item)
                            @php
                                $searchResult = search_excess_pd($item->id);
                            @endphp

                            @if (count($searchResult) > 0)
                                <?php $i++; ?>
                                <tbody class="excess_body">
                                    @foreach ($searchResult as $index => $tem)
                                        <tr class="h-10">
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0">
                                                @can('adjust-excess')
                                                    @if ($main->status == 'complete' && $tem->qty < $tem->scanned_qty)
                                                        <button
                                                            class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed"
                                                            data-id="{{ $tem->id }}">
                                                            <i class='bx bx-minus'></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </td>
                                            @if ($index == 0)
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">
                                                    {{ $i }}</td>
                                                <td
                                                    class="td-container ps-2 border border-slate-400 border-t-0 border-l-0">
                                                    <span>{{ $item->document_no }}</span>
                                                    <button data-copy-id="{{ $item->document_no }}"
                                                        class="excess-copy-button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                            @else
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                            @endif
                                            <td class="td-barcode-container ps-2 border border-slate-400 border-t-0">
                                                <span>{{ $tem->bar_code }}</span>
                                                <button data-copy-id="{{ $tem->bar_code }}"
                                                    class="excess-copy-button-barcode">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                            <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}
                                                <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1 rounded-lg px-1 text-white {{ !isset($tem->remark) ? 'bg-emerald-400 hover:bg-emerald-600' : 'bg-sky-400 hover:bg-sky-600' }} remark_ic'
                                                    data-pd="{{ $tem->bar_code }}" data-id="{{ $tem->id }}"
                                                    data-eq="{{ $index }}"></i>
                                            </td>
                                            <td
                                                class="ps-2 border border-slate-400 border-t-0 border-r-0 {{ $tem->scanned_qty > $tem->qty ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $tem->scanned_qty - $tem->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        @endforeach



                        <tbody class="excess_scan_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- Decision Modal --}}
    <div class="hidden" id="decision">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div
                        class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                        <div class="flex px-4 py-2 justify-between items-center min-w-80">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Choose
                                Document No &nbsp;<span id="show_doc_no"></span>&nbsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold" onclick="$('#decision').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-4">
                            <span class="">Product Code တူ Document များရှိပါသည်။ မည်သည့် Document တွင်
                                ပေါင်းထည့်ချင်လဲ ရွေးပါ</span>
                        </div>
                        <div class="decision_model">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Car Info
                                &nbsp;<span id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold" onclick="$('#car_info').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="grid grid-cols-2 gap-5 border-b-2 border-slate-600">
                            <div class="flex flex-col">
                                <span class="mb-4 text-xl">Vendor Name </span>
                                <span class="mb-4 text-xl ">Branch </span>
                                @if ($main->status == 'incomplete' || ($main->status == 'complete' && isset($main->remark)))
                                    <span class="mb-4 text-xl ">Remark </span>
                                @endif

                            </div>
                            <div class="flex flex-col mb-3">
                                <b class="mb-4 text-xl">:&nbsp;{{ $main->vendor_name ?? '' }}</b>
                                <b class="mb-4 text-xl">:&nbsp;{{ $main->user->branch->branch_name }}</b>
                                @if ($main->remark && $main->status == 'complete')
                                    <b class="mb-4 text-xl">:&nbsp;{{ $main->remark }}</b>
                                @elseif($main->status == 'incomplete')
                                    <textarea class="ps-1 rounded-lg border border-slate-600" id="all_remark" cols="30" rows="3"
                                        placeholder="remark...">{{ $main->remark }}</textarea>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-7  mt-2">
                            @foreach ($driver as $index => $item)
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="flex flex-col ps-4">
                                        <span class="mb-4 text-xl">Driver's No </span>
                                        <span class="mb-4 text-xl">Driver's Name </span>
                                        <span class="mb-4 text-xl">Driver's Phone No </span>
                                        <span class="mb-4 text-xl">Driver's NRC No </span>
                                        <span class="mb-4 text-xl">Truck's No </span>
                                        <span class="mb-4 text-xl">Truck's Type </span>
                                        <span class="mb-4 text-xl">Gate </span>
                                        <span class="mb-4 text-xl">Scanned Qty </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <b class="mb-4 text-xl">:&nbsp;{{ $index + 1 }}</b>
                                        <b class="mb-4 text-xl">:&nbsp;{{ $item->driver_name }}</b>
                                        <b class="mb-4 text-xl">:&nbsp;{{ $item->ph_no }} </b>
                                        <b class="mb-4 text-xl">:&nbsp;{{ $item->nrc_no }}</b>
                                        <b class="mb-4 text-xl">:&nbsp;{{ $item->truck_no }}</b>
                                        <b class="mb-4 text-xl">:&nbsp;{{ $item->truck->truck_name ?? '' }}</b>
                                        <b
                                            class="mb-4 text-xl">:&nbsp;{{ $item->gate == 0 ? getAuth()->branch->branch_name . ' Gate' : $item->gates->name }}</b>
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
    @if (dc_staff() && $status != 'view')
        <div class="hidden" id="add_car">
            <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
                <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                    <!-- Modal content -->
                    <div class="card rounded">
                        <div
                            class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                            <div class="flex px-4 py-2 justify-between items-center min-w-80">
                                <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Car Info
                                    &nbsp;<span id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hidden svgclass">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                                <button type="button" class="text-rose-600 font-extrabold"
                                    onclick="$('#add_car').hide()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <form action="{{ route('store_car_info') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="{{ isset($main) ? 'main_id' : '' }}"
                                    value="{{ isset($main) ? $main->id : '' }}">
                                <div class="grid grid-cols-2 gap-5 my-5">
                                    <div class="flex flex-col px-10">
                                        <label for="truck_type">Type of Truck<span class="text-rose-600">*</span>
                                            :</label>
                                        <Select name="truck_type" id="truck_type"
                                            class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2"
                                            style="appearance: none;">
                                            <option value="">Choose Type of Truck</option>
                                            @foreach ($truck as $item)
                                                {{-- <option value="{{ $item->id }}" {{ old('truck_type') == $item->id ? 'selected' : '' }}>{{ $item->truck_name }}</option> --}}

                                                <option value="{{ $item->id }}"
                                                    data-name="{{ $item->truck_name }}"
                                                    {{ old('truck_type') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->truck_name }}
                                                </option>
                                            @endforeach
                                        </Select>
                                        @error('truck_type')
                                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col px-10">
                                        <label for="driver_phone">Driver Phone<span class="text-rose-600">*</span>
                                            :</label>
                                        <input type="number" name="driver_phone" id="driver_phone"
                                            class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                            value="{{ old('driver_phone') }}" placeholder="09*********">
                                        @error('driver_phone')
                                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-5 my-5">
                                    <div class="flex flex-col px-10">
                                        <label for="driver_nrc">Driver NRC<span class="text-rose-600">*</span> :</label>
                                        <input type="text" name="driver_nrc" id="driver_nrc"
                                            class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                            value="{{ old('driver_nrc') }}" placeholder="nrc...">
                                        @error('driver_nrc')
                                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col px-10">
                                        <label for="driver_name">Driver Name<span class="text-rose-600">*</span> :</label>
                                        <input type="text" name="driver_name" id="driver_name"
                                            class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                            placeholder="name..." value="{{ old('driver_name') }}">
                                        @error('driver_name')
                                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-5 my-5">

                                    <div class="flex flex-col px-10 relative truck_div">
                                        <label for="truck_no">Truck No<span class="text-rose-600">*</span> :</label>
                                        <input type="text" name="truck_no" id="truck_no"
                                            class=" truck_div mt-3 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                            value="{{ old('truck_no') }}" placeholder="truck..." autocomplete="off">
                                        <ul class="truck_div w-[77%] bg-white shadow-lg max-h-40 overflow-auto absolute car_auto"
                                            style="top: 100%">
                                        </ul>
                                        <span id="truck_alert" class="text-rose-500 hidden">Please first choose type of
                                            truck</span>
                                        @error('truck_no')
                                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <?php
                                    $dc = [17, 19, 20];
                                    ?>
                                    @if (dc_staff() && $main->user_id == getAuth()->id)
                                        <div class="flex flex-col px-10">
                                            <label for="gate">Gate<span class="text-rose-600">*</span> :</label>
                                            <Select name="gate" id="gate"
                                                class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2"
                                                style="appearance: none;">
                                                <option value="">Choose Gate</option>
                                                @foreach ($gate as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('gate') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name . '(' . $item->branches->branch_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </Select>
                                            @error('gate')
                                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    @endif


                                </div>
                                <div class="grid grid-cols-2 gap-5 my-5">

                                    <div class="grid grid-cols-3 gap-10  mx-10">
                                        <div class="flex flex-col">
                                            <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex"
                                                onclick="$('#img1').click()" title="image 1"><small
                                                    class="ms-5 -translate-y-1">Image</small><span
                                                    class="translate-y-2">1</span></div>

                                        </div>
                                        <div class="flex flex-col">
                                            <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex"
                                                onclick="$('#img2').click()" title="image 2"><small
                                                    class="ms-5 -translate-y-1">Image</small><span
                                                    class="translate-y-2">2</span></div>

                                        </div>
                                        <div class="flex flex-col">
                                            <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex"
                                                onclick="$('#img3').click()" title="image 3"><small
                                                    class="ms-5 -translate-y-1">Image</small><span
                                                    class="translate-y-2">3</span></div>
                                        </div>

                                        @error('atLeastOne')
                                            <small
                                                class="text-rose-400 -translate-y-7 ms-12 col-span-3">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <input type="file" class="car_img" accept="image/*" name="image_1" hidden
                                        id="img1">
                                    <input type="file" class="car_img" accept="image/*" name="image_2" hidden
                                        id="img2">
                                    <input type="file" class="car_img" accept="image/*" name="image_3" hidden
                                        id="img3">
                                    <div class="">
                                        <button type="submit"
                                            class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Decision Modal --}}
    <div class="hidden" id="alert_model">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 ">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80 ">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Cursor
                            ထွက်နေပါသဖြင့် scan ဖတ်လို့ရမည် မဟုတ်ပါ &nbsp;<span id="show_doc_no"></span>&nbsp;<svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                            onclick="$('#alert_model').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- End Modal

    {{-- Auth Modal --}}
    <div class="hidden" id="pass_con">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div
                        class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                        <div class="flex px-4 py-2 justify-between items-center min-w-80">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Authorize
                                Confirmation &nbsp;<span id="show_doc_no"></span>&nbsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold" onclick="$('#pass_con').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
                                    <input type="text" name="employee_code" id="employee_code"
                                        class=" mt-2 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                        value="{{ old('employee_code') }}" placeholder="employee code"
                                        autocomplete="off">
                                    <small class="text-rose-500 ms-1 error_msg"></small>

                                </div>

                                <div class="flex flex-col px-10 mt-4">
                                    <label for="pass">Password<span class="text-rose-600">*</span> :</label>
                                    <input type="password" name="pass" id="pass"
                                        class="mt-2 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none"
                                        value="{{ old('pass') }}" placeholder="">
                                    <small class="text-rose-500 ms-1 error_msg"></small>

                                </div>
                            </div>
                            <input type="hidden" type="text" id="index">
                            <div class="grid grid-cols-2 gap-5 my-5">

                                <div class="">

                                </div>
                                <div class="">
                                    <button type="button"
                                        class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10"
                                        id="auth_con">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}


    {{-- - Modal Start - --}}
    <div class="hidden" id="remark_model">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div
                        class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                        <div class="flex px-4 py-2 justify-between items-center min-w-80">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Remark for
                                &nbsp;<b id="remark_item"></b>&nbsp;<span id="show_doc_no"></span>&nbsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold"
                                onclick="$('#remark_model').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
    {{-- - Modal Start - --}}

    <div class="hidden" id="print_no">
        <div class="flex items-center  fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 ">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">

                    <div class="flex px-4 py-2 justify-between items-center max-w-50 ">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">print
                            ထုတ်ချင်သော အရေအတွက် ကို ရိုက်ထည့်ပါ (<b class="text-rose-600"> 500 ထက်ပို၍ မရပါ</b>)<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>



                        <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                            onclick="$('#print_no').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="">
                        <input type="hidden" id="print_eq">
                        <Select class="w-full border border-slate-300 py-3 ps-2 bg-white rounded-lg appearance-none"
                            id="bar_type">
                            <option value="1">Bar 1</option>
                            <option value="2">Bar 2</option>
                            <option value="3">Bar 3</option>
                        </Select>
                        <Select class="w-full border border-slate-300 py-3 ps-2 bg-white rounded-lg appearance-none mt-2"
                            id="reason">
                            @foreach ($reason as $item)
                                <option value="{{ $item->id }}">{{ $item->reason }}</option>
                            @endforeach
                        </Select>
                        <input type="number" id="print_count"
                            class="appearance-none w-full border-2 border-slate-300 rounded-lg min-h-12 mt-4 ps-2 focus:outline-none focus:border-sky-200 focus:border-3"
                            placeholder="500 ထက်မပိုပါနဲ့">
                        <button type="button" id="final_print"
                            class="bg-emerald-400 font-semibold text-slate-600 px-6 py-1 rounded-md duration-500 float-end mt-2 hover:bg-emerald-600 hover:text-white ">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- - Modal End - --}}

    {{-- Image Modal --}}
    <div class="hidden" id="image_model">
        <div class="flex items-center fixed inset-0 justify-center z-100 bg-gray-500 bg-opacity-75 "
            style="z-index:99999 !important">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80 ">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl"><span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                            onclick="$('#image_model').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="" id="image_container">
                    {{-- <div class="">
                    <span class="underline mb-4 text-xl font-serif tracking-wider">R4-0989</span>
                    <img src="{{ asset('image/background_img/finallogo.png') }}" class="mb-5 shadow-xl" alt="" style="width:700px">
                    <img src="{{ asset('image/background_img/forklift.png') }}" class="mb-5 shadow-xl" alt="" style="width:700px">
                    <img src="{{ asset('image/background_img/handshake.png') }}" class="mb-5 shadow-xl" alt="" style="width:700px">
                </div> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}

    {{-- start modal --}}

    <div class="hidden" id="prew_img">
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 "
            style="z-index:99999 !important">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                    <div class="flex px-4 py-2 justify-center items-center min-w-80 ">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl"><span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                            onclick="$('#prew_img').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <img src="" id="pr_im" alt="" style="width: 800px">
                </div>
            </div>
        </div>
    </div>
    {{-- end modal --}}

    @if ($status == 'edit')
        {{-- start modal --}}
        {{-- choose car modal --}}
        <div class="hidden" id="car_choose">
            <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 "
                style="z-index:99999 !important">
                <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative"
                    style="max-height: 600px;">
                    <!-- Modal content -->
                    <div class="card rounded">
                        <div class="flex px-4 py-2 justify-center items-center min-w-80 ">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl"><span
                                    id="show_doc_no"></span>Please Choose Your Car No&nbsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                                onclick="$('#car_choose').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-body car_choose_body">
                        @foreach ($driver as $item)
                            <div class="text-center bg-slate-100 py-1 font-semibold font-serif cursor-pointer hover:bg-slate-300 rounded border mb-2 car_no"
                                data-id="{{ $item->id }}" style="box-shadow: 4px 4px 4px rgb(0,0,0,0.2)">
                                {{ $item->truck_no }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- end modal --}}

        {{-- start modal --}}
        {{-- edit img & car no modal --}}
        <div class="hidden" id="change_img">
            <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 " style="">
                <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative"
                    style="max-height: 600px;">
                    <!-- Modal content -->
                    <div class="card rounded">
                        <div class="flex px-4 py-2 justify-center items-center min-w-80 ">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl"><span
                                    id="show_doc_no"></span>Edit Your Car No And Images&nbsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                                onclick="$('#change_img').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-body change_img_body">
                    </div>
                </div>
            </div>
        </div>
        {{-- end modal --}}
    @endif


    @push('js')
        <script>
            $(document).on('click',
                '.copy-button, .scan-copy-button, .excess-copy-button, .copy-button-barcode, .scan-copy-button-barcode, .excess-copy-button-barcode',
                function() {

                    const copyId = $(this).data('copy-id');
                    const buttonElement = this;
                    copyText(copyId, buttonElement);
                });


            $(document).on('click', '.pause_scan', function() {
                var $icon = $(this);
                var token = $("meta[name='__token']").attr('content');
                $pause_scan_id = $(this).attr('id');
                $pause_scan_barcode = $(this).data('bar_code');
                $pause_scan_pd = $(this).data('po');

                var currentStatus = $icon.data('status');
                var newStatusText = currentStatus === 1 ?
                    `Do you want to continue <b>${$pause_scan_barcode}</b> barcode from this <b>${$pause_scan_pd}</b> ? ` :
                    `Do you want to pause <b>${$pause_scan_barcode}</b> barcode from this <b>${$pause_scan_pd}</b> ? `;
                var successText = currentStatus === 1 ?
                    "The barcode scan has been continued." :
                    "The barcode scan has been paused.";

                Swal.fire({
                    title: 'Are you sure?',
                    html: newStatusText,
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/barcode_scan_pause/" + $pause_scan_id,
                            type: 'get',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: currentStatus === 1 ? 'Continued!' : 'Paused!',
                                    text: successText,
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseText, 'error');
                            }
                        });
                    }
                });
            });

            async function copyText(copyId, buttonElement) {
                try {
                    const textToCopy = copyId;
                    if (textToCopy) {
                        const tempInput = document.createElement('textarea');
                        tempInput.value = textToCopy;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        if (buttonElement) {
                            $(buttonElement).html('<i class="fas fa-check"></i>');
                            setTimeout(() => {
                                $(buttonElement).html('<i class="fas fa-copy"></i>');
                            }, 1000);
                        } else {
                            console.log('Button element is not provided');
                        }
                    } else {
                        console.log('No text to copy found.');
                    }
                } catch (err) {
                    console.log('Failed to copy: ', err);
                }
            }

            $(document).ready(function() {

                $("#badage").hide();
                var url = window.location.href;
                var segments = url.split("/");
                var lastSegment = segments.pop();

                $.ajax({
                    url: "{{ route('unavailable_scanned_products.get') }}",
                    type: "GET",
                    data: {
                        received_goods_id: lastSegment
                    },
                    success: function(res) {
                        res.forEach(data => {
                            $('.trash-products').append(
                                `<div class="text-center text-white bg-red-500 mb-4 shadow rounded-md border border-slate-200 py-3 cursor-pointer" data-barcode="${data.scanned_barcode}">${data.scanned_barcode}</div>`
                            );
                        });
                        $('#badage').text(res.length);
                        if (res.length > 0) {
                            $("#badage").show();
                            $(".no_data_image").hide();
                        }
                    },
                    error: function(err) {
                        console.log(err);

                    }
                });

                new TomSelect("#documentNoselect", {
                    selectOnTab: true
                });

                new TomSelect("#barcodeSelect", {
                    selectOnTab: true
                });

                var canAdjustExcess = @json(auth()->user()->can('adjust-excess'));
                var mainStatus = @json($main->status);

                $('#back').on('click', function() {
                    $.ajax({
                        url: window.location.href,
                    });
                });

                $('#document_no_search').click(function() {
                    var id = $('#idInput').val();
                    var documentNo = $('#documentNoSelect').val();
                    var barcodeNo = $('#barcodeSelect').val();
                    var pageUrl = '{{ $page == 'receive' ? '/receive_goods/' : '/view_goods/' }}';
                    $.ajax({
                        url: '/search_document_no',
                        type: 'GET',
                        data: {
                            id: id,
                            document_no: documentNo,
                            barcode_no: barcodeNo
                        },
                        success: function(response) {
                            var documents = response.documents;
                            var scanDocuments = response.scan_documents;
                            var excessDocuments = response.excess_documents;
                            var need_document_inform = response.need_document_inform;

                            if (documents.length === 0 && scanDocuments.length === 0 &&
                                excessDocuments.length === 0) {
                                window.location.href = pageUrl + id;
                            } else {
                                $('#resultCount').show();
                            }
                            var isEmptyDocuments = documents.length === 0 || documents.some(doc =>
                                doc.bar_code.length === 0);
                            var isEmptyScanDocuments = scanDocuments.length === 0 || scanDocuments
                                .some(doc => doc.bar_code.length === 0);
                            var isEmptyExcessDocuments = excessDocuments.length === 0 ||
                                excessDocuments.some(doc => doc.bar_code.length === 0);

                            if (!isEmptyDocuments || !isEmptyScanDocuments || !
                                isEmptyExcessDocuments) {
                                $('#resultCount').hide();
                                $('#back').show();
                                $('.main_body').empty();
                                $('.scan_body').empty();
                                $('.excess_body').empty();
                                $('.search_main_body').empty();
                                $('.search_scan_body').empty();
                                $('.excess_scan_body').empty();

                                if (!isEmptyDocuments) {
                                    for (var i = 0; i < documents.length; i++) {
                                        var document = documents[i];
                                        var barCodes = document.bar_code;
                                        var supplierNames = document.supplier_name;
                                        var qtys = document.qty;
                                        var scannedQtys = document.scanned_qty;
                                        var checkColor = document.check_color;
                                        var scanZero = document.scan_zero;
                                        var searchpdId = document.search_pd_id;
                                        var Unit = document.unit;
                                        var documentno = document.document_no;
                                        var barcodeHtmls = document
                                            .barcode_htmls; // New line to get barcode HTMLs
                                        var isDcStaff = need_document_inform.isDcStaff;
                                        var curDriver = need_document_inform.curDriver;
                                        var driverLast = need_document_inform.driver_last;
                                        var authId = need_document_inform.authId;
                                        var barcodeId = document.barcode_id
                                        var lastDriverStartDate = need_document_inform
                                            .driver_last_start_date;

                                        for (var j = 0; j < barCodes.length; j++) {
                                            var buttonHtml = '';
                                            if ((!isDcStaff && curDriver && authId === curDriver
                                                    .user_id) || (!isDcStaff && driverLast &&
                                                    authId === driverLast.scan_user_id) ||
                                                isDcStaff) {
                                                buttonHtml =
                                                    `<button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc" ${scanZero ? '' : 'hidden'} data-doc="${documentno}"><i class='bx bx-minus'></i></button>`;
                                            }
                                            var additionalIconHtml = '';
                                            if (lastDriverStartDate == null) {} else if (
                                                lastDriverStartDate.length != 0) {
                                                additionalIconHtml =
                                                    `<i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' id='${j}' data-index="${j}" title="add quantity"></i>`;
                                            }

                                            var barcodeHtml = barcodeHtmls[j] || {
                                                bar_stick1: '',
                                                bar_stick2: '',
                                                bar_stick3: ''
                                            };
                                            var buttonHtmltwo = '';
                                            if (!checkColor[j]) {
                                                buttonHtmltwo = `
                                                    <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_barcode" data-barcode="${barCodes[j]}" data-id = "${barcodeId[j]}" >
                                                    <i class="bx bx-minus"></i>
                                                    </button>
                                                    `;
                                            }
                                            var rowHtml = `<tr class="h-10">
                                                ${j === 0 ? `<td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">${buttonHtml}</td>` : `<td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8"></td>`}
                                                ${j === 0 ? `<td class="ps-2 border border-slate-400 border-t-0 doc_times">${i + 1}</td>` : '<td class="ps-2 border border-slate-400 border-t-0"></td>'}
                                                ${j === 0 ? `
                                                                                                            <td class="td-container ps-2 border border-slate-400 border-t-0 doc_no">
                                                                                                                <span>${document.document_no}</span>
                                                                                                                <button data-copy-id="${document.document_no}" class="copy-button">
                                                                                                                    <i class="fas fa-copy"></i>
                                                                                                                </button>
                                                                                                            </td>`
                                                : '<td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>'}
                                                <td class="td-barcode-container ps-2 border border-slate-400 border-t-0 color_add ${checkColor[j]} px-2 bar_code">
                                                    ${buttonHtmltwo}
                                                    <span>${barCodes[j]}</span>
                                                    <button data-copy-id="${barCodes[j]}" class="copy-button-barcode">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add ${checkColor[j]}">${supplierNames[j]}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add ${checkColor[j]} qty">
                                                    <span class="cursor-pointer hover:underline hover:font-semibold sticker select-none" data-index="${j}">${qtys[j]}</span>
                                                    <input type="hidden" class="pd_unit" value="${Unit[j]}">
                                                    <input type="hidden" class="pd_name" value="${supplierNames[j]}">
                                                    <input type="hidden" class="pd_id" value="${searchpdId[j]}">
                                                    <div class='px-5 bar_stick1 hidden'>${barcodeHtml.bar_stick1}</div>
                                                    <div class='px-5 bar_stick2 hidden'>${barcodeHtml.bar_stick2}</div>
                                                    <div class='px-5 bar_stick3 hidden'>${barcodeHtml.bar_stick3}</div>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add ${checkColor[j]} scanned_qty">
                                                    <div class="main_scan">
                                                        ${scannedQtys[j]}
                                                        ${additionalIconHtml}
                                                    </div>
                                                    <input type="hidden" class="w-[80%] real_scan border border-slate-400 rounded-md" data-id="${searchpdId[j]}" data-old="${scannedQtys[j]}" value="${scannedQtys[j]}">
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add ${checkColor[j]} border-r-0 remain_qty">${qtys[j] - scannedQtys[j]}</td>

                                            </tr>`;
                                            $('.search_main_body').append(rowHtml);
                                        }
                                    }
                                }
                                if (!isEmptyScanDocuments) {
                                    scanDocuments.forEach((scanDocument, i) => {
                                        let sanbarCodes = scanDocument.bar_code;
                                        let sansupplierNames = scanDocument.supplier_name;
                                        let sanqtys = scanDocument.qty;
                                        let sanscannedQtys = scanDocument.scanned_qty;
                                        let scanColor = scanDocument.scan_color;
                                        let allScanned = scanDocument.all_scanned;
                                        var scannCount = scanDocument.scann_count;
                                        var scannId = scanDocument.scan_id;
                                        var scannPause = scanDocument.scann_pause;
                                        var barcodEqual = scanDocument.barcode_equal;

                                        for (let j = 0; j < sanbarCodes.length; j++) {
                                            var buttonHtml = '';
                                            if (barcodEqual[j]) {
                                                buttonHtml = `
                                                <button class="pause_scan" id="${scannId[j]}" data-bar_code="${sanbarCodes[j]}" data-status="${scannPause[j]}" data-po="${scanDocument.document_no}"> <i class='bx ${scannPause[j] === 1 ? 'bx-play-circle' : 'bx-pause-circle'} text-sm'></i></button>
                                                    `;
                                            }

                                            let qty = Number(sanqtys[j]);
                                            let scannedQty = Number(sanscannedQtys[j]);
                                            let rowHtmltwo = `<tr class="h-10 scanned_pd_div">
                                                ${j === 0 ? `<td class="ps-2 border border-slate-400 border-t-0 ">${i + 1}</td>` : '<td class="ps-2 border border-slate-400 border-t-0"></td>'}
                                                ${j === 0 ? `<td class="td-container ps-2 border border-slate-400 border-t-0 border-l-0 ${allScanned ? 'bg-green-200 text-green-600' : ''}">
                                                                                                                    <span>${scanDocument.document_no}</span>
                                                                                                                    <button data-copy-id="${scanDocument.document_no}" class="scan-copy-button">
                                                                                                                        <i class="fas fa-copy"></i>
                                                                                                                    </button>
                                                                                                            </td>` : '<td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>'}
                                                <td class="td-barcode-container ps-2 border border-slate-400 border-t-0 ${scanColor[j]}">
                                                    ${buttonHtml}
                                                    <span>${sanbarCodes[j]}</span>
                                                    <button data-copy-id="${sanbarCodes[j]}" class="scan-copy-button-barcode" >
                                                        <i class="fas fa-copy"></i>
                                                    </button>

                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 ${scanColor[j]}">${sansupplierNames[j]}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 ${scanColor[j]} border-r-0 ">
                                                    ${scannedQty > qty ? qty : scannedQty}
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 ${scanColor[j]} border-r-0 no">${scannCount[j]}</td>
                                            </tr>`;
                                            $('.search_scan_body').append(rowHtmltwo);
                                        }
                                    });
                                }
                                if (!isEmptyExcessDocuments) {
                                    excessDocuments.forEach((excessDocument, i) => {
                                        let excessBarCodes = excessDocument.bar_code;
                                        let excessSupplierNames = excessDocument
                                            .supplier_name;
                                        let excessQtys = excessDocument.qty;
                                        let excessScannedQtys = excessDocument.scanned_qty;
                                        let excessRemarks = excessDocument.remark || [];
                                        for (let j = 0; j < excessBarCodes.length; j++) {
                                            let remainingQty = excessScannedQtys[j] -
                                                excessQtys[j];
                                            let excessQty = Number(excessQtys[j]);
                                            let excessScannedQty = Number(excessScannedQtys[
                                                j]);
                                            let quantityClass = remainingQty < 0 ?
                                                'text-rose-600' : 'text-emerald-600';
                                            let remarkClass = excessRemarks[j] ===
                                                undefined ?
                                                'bg-emerald-400 hover:bg-emerald-600' :
                                                'bg-sky-400 hover:bg-sky-600';
                                            let buttonHtml = '';
                                            if (canAdjustExcess && mainStatus ===
                                                'complete' && excessQty < excessScannedQty
                                            ) {
                                                buttonHtml =
                                                    `<button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed" data-id="${excessDocument.excess_id}"><i class='bx bx-minus'></i></button>`;
                                            }
                                            let rowHtmlthree = `<tr class="h-10">
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">${buttonHtml}</td>
                                                ${j === 0 ? `<td class="ps-2 border border-slate-400 border-t-0 border-l-0">${i + 1}</td>` : '<td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>'}
                                                ${j === 0 ? `<td class="td-container ps-2 border border-slate-400 border-t-0 border-l-0"}">
                                                                                                                    <span>${excessDocument.document_no}</span>
                                                                                                                    <button data-copy-id="${excessDocument.document_no}" class="excess-copy-button">
                                                                                                                        <i class="fas fa-copy"></i>
                                                                                                                    </button>
                                                                                                            </td>` : '<td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>'}
                                                <td class="td-barcode-container ps-2 border border-slate-400 border-t-0">
                                                    <span>${excessBarCodes[j]}</span>
                                                    <button data-copy-id="${excessBarCodes[j]}" class="excess-copy-button-barcode" >
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    </td>
                                                <td class="ps-2 border border-slate-400 border-t-0">${excessSupplierNames[j]}
                                                    <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1 rounded-lg px-1 text-white ${remarkClass} remark_ic' data-pd="${excessBarCodes[j]}" data-id="${excessDocument.excess_id}" data-eq="${j}"></i>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0 ${quantityClass}">${remainingQty} </td>
                                            </tr>`;
                                            $('.excess_scan_body').append(rowHtmlthree);
                                        }
                                    });
                                }
                            } else {
                                $('.search_main_body').empty();
                                $('.search_scan_body').empty();
                                $('.excess_scan_body').empty();
                                $('#back').hide();
                            }
                        },
                        error: function(xhr, status, error) {}
                    });
                });
                // $('#suggestions').hide();
                // $('#searchInput').on('input', function() {
                //     let query = $(this).val();
                //     if (query.length >= 1) {
                //         $.ajax({
                //             url: '/search_suggestions',
                //             type: 'GET',
                //             data: {
                //                 query: query
                //             },
                //             success: function(response) {
                //                 if (response.length > 0) {
                //                     $('#suggestions').show();
                //                     response.forEach(item => {
                //                         $('#suggestions').append(`<div class="p-2 cursor-pointer hover:bg-gray-200" data-value="${item}">${item}</div>`);
                //                     });
                //                 }
                //             }
                //         });
                //     } else {
                //         console.log('no');
                //         $('#suggestions').empty();
                //         $('#suggestions').hide();
                //     }
                // });

                $(document).on('click', '#suggestions div', function() {
                    let value = $(this).data('value');
                    $('#searchInput').val(value);
                    $('#suggestions').empty();
                });
            });

            $(document).ready(function(e) {

                var intervalID;
                var startedTimePause = localStorage.getItem('startedTimePause') || $('#started_time_pause').val();
                var startTime = localStorage.getItem('startTime') || Date.now();
                if (startedTimePause) {
                    var interval = 1000;

                    function timeToSeconds(time) {
                        var parts = time.split(':');
                        var hours = parseInt(parts[0], 10);
                        var minutes = parseInt(parts[1], 10);
                        var seconds = parseInt(parts[2], 10);
                        return (hours * 3600) + (minutes * 60) + seconds;
                    }

                    function secondsToTime(seconds) {
                        var hours = Math.floor(seconds / 3600);
                        seconds %= 3600;
                        var minutes = Math.floor(seconds / 60);
                        seconds %= 60;
                        return [hours, minutes, seconds].map(num => String(num).padStart(2, '0')).join(':');
                    }
                    var totalSeconds = timeToSeconds(startedTimePause);

                    function startInterval() {
                        intervalID = setInterval(function() {
                            totalSeconds += 1;
                            var updatedTime = secondsToTime(totalSeconds);
                            $('#time_count_pause').text(updatedTime);
                            localStorage.setItem('startedTimePause', updatedTime);
                        }, interval);
                    }

                    function stopInterval() {
                        if (intervalID) {
                            clearInterval(intervalID);
                        }
                    }
                    if (startedTimePause) {
                        startInterval();
                        localStorage.setItem('startTime', Date.now());
                    } else {
                        localStorage.removeItem('startedTimePause');
                        localStorage.removeItem('startTime');
                    }

                } else {
                    localStorage.removeItem('startedTimePause');
                    localStorage.removeItem('startTime');
                }


                var token = $("meta[name='__token']").attr('content');
                $finish = $('#finished').val();
                $status = $('#view_').val();
                $role = $('#user_role').val();
                $all_begin = $('#started_time').val();
                $count = parseInt($('#count').val()) || 0;
                $cur_id = $('#cur_truck').val() ?? '';
                $dc_staff = "{{ getAuth()->branch_id }}";
                $dc_staff = $dc_staff.includes([17, 19, 20]) ? true : false;

                function reload_page() {
                    $('.main_table').load(location.href + ' .main_table');
                    $('.scan_parent').load(location.href + ' .scan_parent', function() {
                        $('.excess_div').load(location.href + ' .excess_div', function() {
                            $('.scanned_pd_div').eq(0).find('td').addClass('latest');
                        });
                    });
                }
                // $('.real_scan').eq(0).attr('type','text');

                $(document).on('click', '#driver_info', function(e) {
                    $('#car_info').toggle();
                })

                $(document).on('click', '#show_image', function(e) {
                    $doc_id = '{{ $main->id }}';
                    $.ajax({
                        url: "{{ route('show_image') }}",
                        type: 'POST',
                        data: {
                            _token: token,
                            id: $doc_id
                        },
                        success: function(res) {
                            $list = '';
                            for ($i = 0; $i < res.truck.length; $i++) {
                                $list += `
                                <div class="">
                                    <span class="underline mb-4 text-xl font-serif tracking-wider">${res.truck[$i].truck_no}</span>
                                `;
                                for ($j = 0; $j < res.image.length; $j++) {
                                    if (res.truck[$i].id == res.image[$j].driver_info_id) {
                                        $list += `
                                            <img src="{{ asset('storage/${res.image[$j].file}') }}" class="mb-5 shadow-xl" alt="" style="width:700px">
                                        `;
                                    }
                                }
                                $list += '</div>';
                            }
                            $('#image_container').html('');
                            $('#image_container').append($list);
                            $('#image_model').show();
                        }
                    })

                })

                $(document).on('click', '.del_barcode', function(e) {
                    let barcode = $(this).data('barcode');
                    let product_id = $(this).data('id');

                    Swal.fire({
                        icon: 'warning',
                        text: 'Are you sure to remove for this product code?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtoText: 'No',
                        input: 'text',
                        inputPlaceholder: 'Enter Your Remark Here',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to write remark!'
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            //Swal.fire(`Your name is: ${result.value}`);
                            $.ajax({
                                url: '/barcode_not_scan',
                                method: 'POST',
                                data: {
                                    barcode: barcode,
                                    document_id: product_id,
                                    remark: result.value,
                                    _token: token
                                    // _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Your product code  was removed successfully.'
                                        // text: `Barcode: ${response.barcode}\nRemark: ${response.remark}\nRemark: ${response.document_id}`,
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', xhr.responseText, 'error');
                                }
                            });

                        }
                    })

                })

                if (!$finish) {
                    $(document).on('click', '.del_doc', function(e) {
                        $val = $(this).data('doc');
                        $id = $('#receive_id').val();
                        $this = $(this);
                        Swal.fire({
                            icon: 'info',
                            title: 'Are You Sure?',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: "No",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('del_doc') }}",
                                    type: 'POST',
                                    data: {
                                        _token: token,
                                        data: $val,
                                        id: $id
                                    },
                                    success: function(res) {
                                        $this.parent().parent().parent().remove();
                                        if (res.count == 1) {
                                            $('#vendor').parent().remove();
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        $msg = xhr.responseJSON.message;
                                        if ($msg == 'You Cannot Remove') {
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Scan ဖတ်ထားတာရှိတဲ့ အတွက်ကြောင့် Remove လုပ်ခွင့်မပေးပါ',
                                            })
                                        }
                                    }
                                })
                            }
                        })
                    })
                }

                if ($status != 'view') {

                    $(document).on('click', '#add_driver', function(e) {
                        $('#add_car').toggle();
                    })

                    $(document).on('click', '#start_count_btn', function(e) {
                        $id = '{{ $main->id }}';
                        $.ajax({
                            url: '/start_count/' + $id,
                            success: function(res) {
                                window.location.reload();
                            }
                        })
                    })

                    $(document).on('change', '.car_img', function(e) {
                        $index = $('.car_img').index($(this));
                        $('#pree_' + $index).remove();
                        $('.img_btn').eq($index).addClass('bg-emerald-200').after(`
                            <span class="hover:underline cursor-pointer mt-3 -translate-x-4 img_preview" id="pree_${$index}" data-index="${$index}" style="margin-left:35%">preivew</span>
                        `);
                    })

                    $(document).on('click', '.img_preview', function(e) {
                        $index = $(this).data('index');
                        $file = $('.car_img').eq($index).get(0);
                        if ($file && $file.files && $file.files[0]) {
                            var file = $file.files[0];
                            var imageUrl = URL.createObjectURL(file);
                            $('#pr_im').attr('src', imageUrl);
                        }
                        $('#prew_img').show();
                        return;
                        $("#pr_im").src(URL.createObjectURL($('.car_img').eq($index).target.files[0]))

                    })

                    if (!$finish) {
                        $(document).on('click', '.change_scan', function(e) {
                            $id = $(this).data('index');
                            $('#index').val($id);
                            $('#employee_code').val('');
                            $('#pass').val('');
                            $('.error_msg').text('');
                            $('.error_msg').eq(0).parent().removeClass('bg-rose-200 pb-1');
                            $('#pass_con').show();
                        })



                        // $(document).on('click','.sticker',function(e){
                        //     $('.bar_stick').remove();
                        //     $qty    = $(this).text();
                        //     $index  = $(this).data('index');
                        //     $pd_code= $(this).data('pd').toString();
                        //     $(this).parent().append(`
                //     `);
                        //     $('.sticker').eq($index).trigger('show_stick');



                        // })

                        $(document).on('click', '.sticker', function(e) {
                            $('#print_eq').val('');
                            $('#print_count').val('');
                            $('#print_no').show();
                            $('#print_eq').val($(this).data('index'));
                        })

                        $(document).on("input", '#print_count', function(e) {
                            $val = $(this).val();
                            $eq = $('#print_eq').val();
                            $qty = $('.sticker').eq($eq).text();
                            if ($val > 500) {
                                $(this).val(500);
                            } else if ($val > parseInt($qty)) {
                                $(this).val($qty);
                            }
                        })

                        $(document).on('click', '#final_print', function(e) {
                            $index = $('#print_eq').val();
                            $pd_code = $('.bar_code').eq($index).text();
                            $qty = $('#print_count').val();
                            $unit = $('.pd_unit').eq($index).val();
                            $name = $('.pd_name').eq($index).val();
                            $id = $('.pd_id').eq($index).val();
                            $type = $('#bar_type').val();
                            $reason = $('#reason').val();
                            if ($qty > 0 && $qty != '') {
                                $td = new Date();
                                $date = [String($td.getDate()).padStart(2, '0'), String($td.getMonth() + 1)
                                    .padStart(2, '0'), $td.getFullYear()
                                ].join('/');
                                $period = $td.getHours() > 12 ? 'PM' : 'AM';
                                $time = [(String($td.getHours()).padStart(2, '0') % 12 || 12), String($td
                                    .getMinutes()).padStart(2, '0'), String($td.getSeconds()).padStart(
                                    2, '0')].join(':');
                                $full_date = $date + ' ' + $time + ' ' + $period;

                                $.ajax({
                                    url: "{{ route('print_track') }}",
                                    type: 'POST',
                                    data: {
                                        _token: token,
                                        id: $id,
                                        qty: $qty,
                                        type: $type,
                                        reason: $reason
                                    },
                                    success: function(res) {}
                                })

                                const new_pr = window.open("", "", "width=900,height=600");
                                $name = $name.length > 80 ? $name.substring(0, 80) + '..' : $name;
                                $mar_top = $name.length > 50 ? 3 : ($name.length > 35 ? 10 : 30);


                                if ($type == 1) {
                                    $bar = $('.bar_stick1').eq($index).html();
                                    $name = $name.length > 80 ? $name.substring(0, 78) + '..' : $name;
                                    $name = $name.length > 60 ? $name.substring(0, 30) + "<br/>" + $name
                                        .substring(30, 60) + "<br/>" + $name.substring(60) :
                                        $name.length > 30 ? $name.substring(0, 30) + "<br/>" + $name.substring(
                                            30) : $name;
                                    {{-- console.log($name); --}}
                                    $mar_top = $name.length > 60 ? 3 :
                                        $name.length > 30 ? 10 : 30;
                                    new_pr.document.write(
                                        "<html><head><style>#per_div{display: grid;grid-template-columns:32% 32% 32%;margin-left:12px;padding-right:25px;gap:20px}"
                                    );

                                    new_pr.document.write(
                                        "</style></head><body><div id='per_div'>"
                                    )
                                    $color = 100;
                                    $margin = $pd_code.length > 11 ? 10 : 40;
                                    for ($i = 0; $i < $qty; $i++) {

                                        // new_pr.document.write(`
                                //     <div class="" style="padding-left: 18px;margin-top:${$mar_top}px;">
                                //         <div style="padding-left: 20px; padding-right: 20px;">
                                //             <small class="" style="word-break: break-all;font-size:0.9rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif;">${$name}</small>
                                //         </div>
                                //         <div style="margin-top:${ $margin }px; margin-left:${ $margin }px;margin-top:15px">${$bar}</div>
                                //         <div style="padding:5px 0;display:flex;flex-direction:column">
                                //              <b class="" style="letter-spacing:1px;margin: 0 0 0 60px;font-size:1rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif;"">${$pd_code}</b
                                //              >
                                //              <small class="" style="margin-left:230px;transform:translateY(-10px);font-size:1rem;font-family: Arial, Helvetica, sans-serif;"">${$unit}</small>
                                //             <small class="" style="margin: 0 0 0 20px;font-size:1rem;font-weight:700;font-family: Arial, Helvetica, sans-serif;"">${$full_date}</small>
                                //         </div>
                                //     </div>
                                // `);
                                        // $color = $color+10;

                                        new_pr.document.write(`
                                        <div class="" style="padding-left: 18px;margin-top:${$mar_top}px;">

                                                <small class="" style="word-break: break-all;font-size:0.9rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif">${$name}</small>

                                            <div style="margin-left:${ $margin }px;margin-top:15px">${$bar}</div>
                                            <div style="padding:5px 0;display:flex;flex-direction:column">
                                                <b class="" style="letter-spacing:1px;margin: 0 0 0 60px;font-size:1rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif;"">${$pd_code}</b
                                                >
                                                <small class="" style="margin-left:230px;transform:translateY(-10px);font-size:1rem;font-family: Arial, Helvetica, sans-serif;"">${$unit}</small>
                                                <small class="" style="margin: 0 0 0 20px;font-size:1rem;font-weight:700;font-family: Arial, Helvetica, sans-serif;"">${$full_date}</small>
                                            </div>
                                        </div>
                                    `);

                                    }
                                    new_pr.document.write("</div></body></html>");
                                } else if ($type == 2) {
                                    $bar = $('.bar_stick2').eq($index).html();
                                    $name = $name.length > 80 ? $name.substring(0, 78) + '..' : $name;
                                    $name = $name.length > 40 ? $name.substring(0, 40) + "<br/>" + $name
                                        .substring(40) : $name;
                                    $mar_top = $name.length > 40 ? 0 : 10;
                                    new_pr.document.write(

                                        "<html><head><style>#per_div{display: grid;grid-template-columns:32% 32% 32%;margin-left:35px;gap:10px}"
                                    );

                                    new_pr.document.write(
                                        "</style></head><body style='margin:0;padding:0'><div id='per_div'>"
                                    )
                                    for ($i = 0; $i < $qty; $i++) {
                                        new_pr.document.write(`

                                        <div class="" style="margin: ${$mar_top+12}px 10px 5px 2px;position:relative;">
                                             <small class="" style="word-break: break-all;font-size:0.65rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif">${$name}</small>

                                           <div style="position:absolute;right:70px;top:70px">
                                                <small class="" style="font-weight:700; font-family: Arial, Helvetica, sans-serif;">${$unit}</small>
                                            </div>
                                            <div style="margin-left:10px;margin-top:2px;padding:0">${$bar}</div>
                                            <div style="padding:5px 0;display:flex;flex-direction:column">
                                                <b class="" style="letter-spacing:1px;margin: 0 0 0 60px;font-size:0.7rem;font-weight:900;font-family: Arial, Helvetica, sans-serif;">${$pd_code}</b>
                                                <small class="" style="margin: 0 0 0 20px;font-size:0.7rem;font-weight:700;font-family: Arial, Helvetica, sans-serif;">${$full_date}</small>
                                            </div>
                                        </div>
                                `);
                                    }
                                    new_pr.document.write("</div></body></html>");
                                } else if ($type == 3) {
                                    $bar = $('.bar_stick3').eq($index).html();

                                    new_pr.document.write(
                                        "<html><head><style>#per_div{display: grid;grid-template-columns:33% 33% 33%;margin-left:30px;gap:10px}"
                                    );

                                    new_pr.document.write(
                                        "</style></head><body style='margin:0;padding:5px 0'><div id='per_div'>"
                                    )

                                    for ($i = 0; $i < $qty; $i++) {
                                        new_pr.document.write(`
                                    <div class="" style="padding: 20px 10px 5px 5px;position:relative;">

                                    <div style="padding-left: 20px; padding-right: 50px;">
                                        <small class="" style="font-size:0.8rem;font-weight:1000;font-family: Arial, Helvetica, sans-serif">${$name}</small>
                                    </div>
                                    <div style="position:absolute;right:50px;top:120px">
                                        <small class="" style="font-weight:700; font-family: "Times New Roman", Times, serif;">${$unit}</small>
                                    </div>
                                    <div style="padding-left:5px;margin-top:15">${$bar}</div>
                                    <div style="padding:5px 0;display:flex;flex-direction:column">
                                        <b class="" style="letter-spacing:1px;margin: 0 0 0 60px;font-size:1rem;font-weight:900">${$pd_code}</b>
                                        <div style="display:flex">
                                            <div style="width:100px;height:30px;border:solid 3px black"></div>
                                            <div style="width:20px;height:20px;border:solid 3px black;margin:10px 0 0 4px"></div>
                                            <div style="margin:15px 0 0 4px;font-weight:800">.............</div>
                                        </div>
                                        <small class="" style="margin: 0 0 0 20px;font-size:1rem;font-weight:700">${$full_date}</small>
                                    </div>
                                    </div>
                                    `);
                                    }
                                    new_pr.document.write("</div></body></html>");
                                }

                                new_pr.document.close();
                                new_pr.focus();
                                new_pr.onload = function() {
                                    new_pr.print();
                                    new_pr.close();
                                };
                                $('#print_no').hide();
                            }

                        })

                        $(document).on('click', '#auth_con', function(e) {
                            $index = $('#index').val();

                            $data = $('#auth_con_form').serialize();

                            $notempty = false;
                            if ($('#employee_code').val() == '') {
                                $notempty = true;
                                $('.error_msg').eq(1).text('Please Fill Employee Code');
                            }
                            if ($('#pass').val() == '') {
                                $notempty = true;
                                $('.error_msg').eq(2).text('Please Fill Password');
                            }
                            if (!$notempty) {
                                $.ajax({
                                    url: "{{ route('pass_vali') }}",
                                    type: 'POST',
                                    data: {
                                        _token: token,
                                        data: $data
                                    },
                                    beforeSend: function(res) {
                                        $('.error_msg').eq(0).parent().removeClass(
                                            'bg-rose-200 pb-1');
                                        $('.error_msg').text('');
                                    },
                                    success: function(res) {
                                        $('#pass_con').hide();


                                        $('.main_scan').eq($index).attr('hidden', true);
                                        $('.real_scan').eq($index).attr('type', 'number');
                                        $('.real_scan').eq($index).attr('data-auth', res.id);
                                    },
                                    error: function() {
                                        console.log('error')
                                        $('.error_msg').eq(0).text('Credential Does Not Match!!');
                                        $('.error_msg').eq(0).parent().addClass('bg-rose-200 pb-1');
                                        $('#employee_code').val('');
                                        $('#pass').val('');
                                    }
                                })
                            }
                        })

                        $(document).on('blur', '.real_scan', function(e) {
                            $val = $(this).val();
                            $old = $(this).data('old');
                            $pd_id = $(this).data('id');
                            $auth = $(this).data('auth');
                            if ($old >= $val) {
                                $(this).val($old);
                                $('.main_scan').eq($index).attr('hidden', false);
                                $('.real_scan').eq($index).attr('type', 'hidden');
                            } else {
                                $add_val = $val - $old;
                                Swal.fire({
                                    icon: 'question',
                                    text: `${$add_val}ခု ပေါင်းထည့်မှာ သေချာပါသလား`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "{{ route('add_product') }}",
                                            type: 'POST',
                                            data: {
                                                _token: token,
                                                data: $add_val,
                                                car_id: $cur_id,
                                                product: $pd_id,
                                                auth: $auth
                                            },
                                            success: function(res) {
                                                $('#back').hide();
                                                reload_page();
                                            }
                                        })
                                    }
                                })
                            }
                        })

                        $(document).on('keyup', '.real_scan', function(e) {
                            if (e.keyCode === 13 || e.keyCode === 27) {
                                this.blur();
                            }
                        })
                    }

                    if (!$finish && ($role == 2 || $role == 3)) {
                        $(document).on('keypress', '#docu_ipt', function(e) {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                                $('#search_btn').click();
                                $(this).val('');
                            }
                        });

                        $(document).on('click', '#search_btn', function(e) {
                            let id = $('#receive_id').val();
                            let val = $('#docu_ipt').val();
                            $this = $('#docu_ipt');
                            $vendor = $('#vendor_name').text();
                            $.ajax({
                                url: "{{ route('search_doc') }}",
                                type: 'POST',
                                data: {
                                    _token: token,
                                    data: val,
                                    id: id
                                },
                                success: function(res) {
                                    if ($vendor == '') {
                                        $('#vendor_name').text(res[0].vendorname);
                                    }
                                    $list = '<tbody class="main_body">';
                                    for ($i = 0; $i < res.length; $i++) {
                                        if ($i == 0) {
                                            $list += `
                                        <tr class="h-10">
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                                                        <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc"  data-doc="${res[$i].purchaseno}"><i class='bx bx-minus'></i></button>
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
                                        } else {
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
                                error: function(xhr, status, error) {
                                    if (xhr.status == 400) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Warning',
                                            text: 'Doucment တခုကို နှစ်ကြိမ်ထည့်ခွင့်မရှိပါ'
                                        })
                                    } else if (xhr.status == 404) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Warning',
                                            text: 'Document မတွေ့ပါ'
                                        })
                                    }

                                },
                                complete: function() {
                                    $this.val('');
                                }
                            })
                        })

                        var key = '';

                        $(document).on('keypress', function(e) {

                            $doc_ipt = e.target.matches('input') || e.target.matches('textarea');

                            $bar_ipt = $('#bar_code').val();
                            if (!$doc_ipt) {
                                if (e.key === 'Enter' && $bar_ipt != '') {
                                    if ($all_begin != '' || !$dc_staff) {
                                        $('#bar_code').val(key);
                                        $('#bar_code').trigger('barcode_enter');
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Warning',
                                            text: 'ကားအချက်အလက် ဖြည့်ပြီးမှ scan ဖတ်နိုင်ပါမည်',
                                            // showConfirmButton:false
                                        })
                                        setTimeout(() => {
                                            Swal.close();
                                        }, 2000);
                                    }
                                    $('#bar_code').val('');
                                    key = '';
                                } else {
                                    if (e.key != 'Enter') {
                                        key += e.key;
                                        $('#bar_code').val(key);
                                    }
                                }
                            }
                        });

                        $(document).on('barcode_enter', '#bar_code', function(e) {
                            $('#back').hide();
                            $val = $(this).val();
                            $recieve_id = $('#receive_id').val();
                            $this = $(this);
                            $cur_id = $('#cur_truck').val() ?? '';

                            $code = $val.replace(/\D/g, '');
                            if ($val) {
                                $.ajax({
                                    url: "{{ route('barcode_scan') }}",
                                    type: 'POST',
                                    data: {
                                        _token: token,
                                        data: $val,
                                        id: $recieve_id,
                                        car: $cur_id
                                    },
                                    success: function(res) {

                                        //$('.scanned_pd_div').eq(0).find('td').addClass('latest');
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

                                        if ($all_begin == '') {
                                            window.location.reload();
                                        }
                                        $('#prev_scan').text(res.pd_code);
                                        reload_page();
                                        // }
                                    },

                                    error: function(xhr, status, error) {
                                        $msg = xhr.responseJSON.message;
                                        console.log("Error");

                                        if ($msg == 'Server Time Out Please Try Again') {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Warning',
                                                text: 'Server Time Out Please Try Again'
                                            });
                                        } else if ($msg == 'Not found') {
                                            // Swal.fire({
                                            //     icon: 'error',
                                            //     title: 'Warning',
                                            //     text: 'Barcode Not found'
                                            // });\
                                            $badageCount = $('#badage').text();
                                            $badageCount = parseInt($badageCount) + 1;
                                            $('#badage').text($badageCount);
                                            $("#badage").show();
                                            $(".no_data_image").hide();
                                            $('.trash-products').append(
                                                `<div class="text-center text-white bg-red-500 mb-4 shadow rounded-md border border-slate-200 py-3 cursor-pointer" data-barcode="${$val}">${$val}</div>`
                                            );

                                            $.ajax({
                                                url: "{{ route('unavailable_scanned_products.create') }}",
                                                type: "POST",
                                                data: {
                                                    _token: token,
                                                    data: {
                                                        scanned_barcode: $val,
                                                        received_goods_id: $recieve_id
                                                    }
                                                },
                                                success: function(res) {
                                                    console.log(res);
                                                }
                                            });

                                        } else if ($msg == 'dublicate') {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Warning',
                                                text: 'Doucment တခုကို နှစ်ကြိမ်ထည့်ခွင့်မရှိပါ'
                                            });
                                        } else if ($msg == 'doc not found') {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Warning',
                                                text: 'Doucment မရှိပါ'
                                            });
                                        }

                                        // setTimeout(() => {
                                        //     Swal.close();
                                        //     }, 3000);
                                    },
                                    complete: function() {
                                        $this.val('');
                                    }

                                })
                            }

                        })

                        $(document).on('click', '.decision_doc', function(e) {
                            $id = $(this).data('id');
                            $qty = $('#scan_qty').val();

                            $.ajax({
                                url: "{{ route('add_product_qty') }}",
                                type: "POST",
                                data: {
                                    _token: token,
                                    id: $id,
                                    qty: $qty
                                },
                                success: function(res) {
                                    $('.scan_parent').load(location.href + ' .scan_parent');
                                    $('.excess_div').load(location.href + ' .excess_div');
                                },
                                complete: function() {
                                    $('#decision').hide();
                                }
                            })
                        })
                    }

                    if (!$finish && ($role == 2 || $role == 3) && ($all_begin != '' || !$dc_staff)) {
                        window.addEventListener('focus', function() {
                            $('#alert_model').hide();
                        });

                        window.addEventListener('blur', function() {
                            $('#alert_model').show();

                        });
                    }

                    if (!$finish && ($role == 2 || $role == 3) && ($all_begin != '')) {
                        setInterval(() => {
                            time_count();
                        }, 1000);

                        function time_count() {
                            let time = new Date($('#started_time').val()).getTime();
                            let duration = 0;
                            let now = new Date().getTime();
                            let diff = Math.floor(now - time + duration);
                            let hour = Math.floor(diff / (60 * 60 * 1000));
                            let min = Math.floor((diff % (60 * 60 * 1000)) / (60 * 1000));
                            let sec = Math.floor((diff % (60 * 60 * 1000)) % (60 * 1000) / (1000));
                            $('#time_count').text(hour.toString().padStart(2, '0') + ':' + min.toString().padStart(2,
                                '0') + ':' + sec.toString().padStart(2, '0'));
                        }
                    }

                    $(document).on('blur', '#all_remark', function(e) {
                        $val = $(this).val();
                        $id = $('#receive_id').val();
                        $type = 'all';
                        $this = $(this);
                        $.ajax({
                            url: "{{ route('store_remark') }}",
                            type: "POST",
                            data: {
                                _token: token,
                                data: $val,
                                id: $id,
                                type: $type
                            },
                            success: function(res) {
                                $this.addClass(' border-2 border-emerald-400');
                                $('#wh_remark').val($val);
                            }
                        })
                    })

                    function not_finish($id) {
                        var timeCountValue = $('#time_count').text() ? $('#time_count').text() : $('#time_count_pause')
                            .text();
                        $.ajax({
                            url: "{{ route('confirm') }}",
                            type: 'POST',
                            data: {
                                _token: token,
                                id: $id,
                                timecount: timeCountValue
                            },
                            success: function(res) {
                                if (localStorage.getItem('startedTimePause') !== null) {
                                    localStorage.removeItem('startedTimePause');
                                    localStorage.removeItem('startTime');
                                }
                                location.href = '/list';
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'truck duration မှာ 24 hr ကျော်သွားပါသဖြင့် save မရပါ။'
                                })
                            }
                        })
                    }


                    $(document).on('click', '#confirm_btn', function(e) {
                        $id = $('#receive_id').val();
                        $remark = $('#wh_remark').val();
                        if ($remark == '') {
                            Swal.fire({
                                icon: 'question',
                                title: 'Remark မထည့်ရသေးပါ continue လုပ်ဖို့သေချာပါသလား',
                                showCancelButton: true,
                                cancelButtonText: 'No',
                                confirmButtonText: 'Yes'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    if (startedTimePause) {
                                        stopInterval();
                                    }
                                    not_finish($id)
                                }
                            })
                        } else {
                            if (startedTimePause) {
                                stopInterval();
                            }
                            stopInterval();
                        }

                    })

                    function all_finish($finish, $id) {
                        if (!$finish) {

                            Swal.fire({
                                'icon': 'info',
                                'title': 'Are You Sure?',
                                'text': 'Remaining QTY ကျန်နေပါသေးသည်?Complete လုပ်ဖို့သေချာပါသလား?',
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (startedTimePause) {
                                        stopInterval();
                                    }
                                    finish($id);
                                }
                            })
                        } else if ($doc_count < 1) {
                            Swal.fire({
                                'icon': 'error',
                                'title': 'Warning',
                                'text': 'Document မရှိလျှင် Complete လုပ်ခွင့်မပေးပါ',
                            })
                        } else {
                            finish($id);
                        }
                        // aa
                    }

                    $(document).on('click', '#finish_btn', function(e) {
                        // console.log('yes');
                        // return;
                        $finish = true;
                        $id = $('#receive_id').val();
                        $doc_count = $('#doc_total').val();
                        $remark = $('#wh_remark').val();
                        $('.remain_qty').each((i, v) => {

                            if (parseInt($(v).text()) > 0) {
                                $finish = false;
                                return false;
                            }
                        })

                        if ($remark == '') {
                            Swal.fire({
                                icon: 'question',
                                title: 'Remark မထည့်ရသေးပါ finish လုပ်ဖို့သေချာပါသလား',
                                showCancelButton: true,
                                cancelButtonText: 'No',
                                confirmButtonText: 'Yes'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    all_finish($finish, $id);
                                }
                            })
                        } else {
                            all_finish($finish, $id);
                        }

                    })

                    function finish($id) {
                        $timeContValue = $('#time_count').text() ? $('#time_count').text() : $('#time_count_pause')
                            .text();
                        $.ajax({
                            url: "/finish_goods/" + $id + "/" + $timeContValue,
                            type: 'get',
                            success: function(res) {
                                if (localStorage.getItem('startedTimePause') !== null) {
                                    localStorage.removeItem('startedTimePause');
                                    localStorage.removeItem('startTime');
                                }
                                location.href = '/list';
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    // title: 'truck duration မှာ 24 hr ကျော်သွားပါသဖြင့် save မရပါ။'
                                    title: 'something wrong'
                                })
                            }
                        })
                    }

                }
                if (!$finish && $role != 2) {
                    $(document).on('click', '.del_exceed', function(e) {
                        $('#back').hide();
                        $id = $(this).data('id');
                        $.ajax({
                            url: "{{ route('del_exceed') }}",
                            type: 'POST',
                            data: {
                                _token: token,
                                id: $id
                            },
                            success: function(res) {

                                $('.scan_parent').load(location.href + ' .scan_parent');
                                $('.excess_div').load(location.href + ' .excess_div');
                            }
                        })
                    })
                }

                $(document).on('click', '.remark_ic', function(e) {
                    $pd_code = $(this).data('pd');
                    $id = $(this).data('id');
                    $eq = $(this).data('eq');
                    $('#remark_item').text(' "' + $pd_code + ' "');

                    $.ajax({
                        url: "/ajax/show_remark/" + $id,
                        beforeSend: function() {
                            $('#remark_card_body').html('');
                        },
                        success: function(res) {
                            $list = '';
                            if (res == '') {
                                $list = `
                            <textarea cols="50" class="ps-1" id="ipt_remark" rows="5" data-id="${ $id }" data-eq="${ $eq }"></textarea>
                            <small class="ml-2" id="op_count">0/500</small>
                            `;
                            } else {
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

                $max = 500;
                $(document).on('input', '#ipt_remark', function(e) {
                    e.preventDefault();

                    $len = $(this).val().length;

                    if ($len <= $max) {
                        if (e.ctrlKey && e.shiftKey && e.keyCode === 8) {
                            $('#op_count').html('0/500');
                        } else {
                            $list = `${$len}/500`;
                            $('#op_count').html($list);
                        }
                        $('#op_count').css('color', 'black');
                    } else {
                        if (e.keyCode !== 8 && !(e.ctrlKey && e.shiftKey && e.keyCode === 8)) {
                            $(this).val($(this).val().substring(0, $max));
                            $('#op_count').html($list);
                            $('#op_count').css('color', 'red');
                        } else {
                            var $list = `${$len}/500`;
                            $('#op_count').css('color', 'black');
                        }
                    }
                });

                $(document).on('paste', '#ipt_remark', function(e) {
                    $copyData = e.originalEvent.clipboardData || window.clipboardData;
                    $pastedData = $copyData.getData('text/plain');
                    $ava_len = $max - $('#ipt_remark').val().length;

                    if ($ava_len < $pastedData.length) {
                        $ins_txt = $pastedData.substring(0, $ava_len);
                        $val = $('#ipt_remark').val() + $ins_txt;
                        Swal.fire({
                            icon: 'question',
                            title: 'Copiedထားသော စာလုံး အရေအတွက် များနေပါတယ်',
                            // text: `Your Copied Text Length is ${$pastedData.length} and avaliable Length is ${$ava_len} your can only paste '${$ins_txt}'`,
                            text: `သင် copy ယူထားသော စာလုံးအရေအတွက်မှာ လိုအပ်သော စာလုံး အရေအတွက် ထက်မျာနေပါသဖြင့် "${$ins_txt}" အနေနဲ့ ဖြတ်တောက်မည်ကိုလက်ခံပါသလား? `,
                            showCancelButton: true,
                            cancelButtonText: 'No',
                            confirmButtonText: 'Yes',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#ipt_remark').val($val);
                                $('#op_count').html('500/500');
                                $('#op_count').css('color', 'red');
                            }
                        })
                    }
                    // console.log($pastedData.length);
                })

                $(document).on('blur', '#ipt_remark', function(e) {
                    $val = $(this).val();
                    $id = $(this).data('id');
                    $eq = $(this).data('eq');
                    $type = 'pd';
                    if ($val.length > 0) {
                        Swal.fire({
                            icon: 'question',
                            title: 'Save မှာသေချာပါသလား?',
                            showCancelButton: true,
                            cancelButtonText: 'No',
                            confirmButtonText: 'Yes',
                        }).then((v) => {
                            if (v.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('store_remark') }}",
                                    type: "POST",
                                    data: {
                                        _token: token,
                                        data: $val,
                                        id: $id,
                                        type: $type
                                    },
                                    success: function(res) {
                                        $('#remark_card_body').html('');
                                        $('#remark_card_body').append(`
                                        <div class="" style="width: 500px;hyphens:auto;word-break:normal">
                                            <span>${$val}</span>
                                        </div>
                                        `);
                                        $('.remark_ic').eq($eq).removeClass(
                                            'bg-emerald-400 hover:bg-emerald-600');
                                        $('.remark_ic').eq($eq).addClass(
                                            'bg-sky-400 hover:bg-sky-600');
                                    }
                                })
                            }
                        })
                    }
                })

                if ($status == 'edit') {
                    $(document).on('click', '.car_no', function() {
                        $index = $('.car_no').index(this);
                        $id = $(this).data('id');

                        $.ajax({
                            url: '/get_img/' + $id,
                            success: function(res) {
                                $length = res.image.length;
                                // console.log($length);
                                $color1 = $length > 0 ? 'bg-emerald-200' : 'hover:bg-slate-100'
                                $color2 = $length > 1 ? 'bg-emerald-200' : 'hover:bg-slate-100'
                                $color3 = $length > 2 ? 'bg-emerald-200' : 'hover:bg-slate-100'
                                $list = `
                                <div class="edit_con">
                                <form action="{{ route('update_image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="text-center">
                                        <input type="text" name="truck_no" class="ps-2 py-2 w-[80%] rounded border" value="${res.driver.truck_no}">
                                        <input type='hidden' name="driver_id" value="${res.driver.id}">
                                        <input type='hidden' name='reg_id' value="${res.driver.received_goods_id}">
                                    </div>
                                    <div class=" my-5">

                                        <div class="grid grid-cols-3 gap-10 col-span-2 mx-10">
                                            <div class="flex flex-col relative">
                                            <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer ${$color1} rounded-lg shadow-xl img_btn flex" onclick="$('#img1').click()" title="image 1"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">1</span></div>
                                `;
                                if ($length > 0) {
                                    $list += `
                                    <button type="button" class="bg-rose-500 hover:bg-rose-800 text-white w-6 rounded del_img" data-id="${res.image[0].id}" style="position:absolute;margin-left:73px;z-index:100000 !important">-</button>
                                    <div class="flex justify-center">
                                        <small class="cursor-pointer hover:underline view_image" data-id="${res.image[0].id}">view</small>
                                        <input type="hidden" name="image1" value="ok">
                                    </div>
                                    `;
                                    // <small class="cursor-pointer">preview</small>
                                }

                                $list += `
                                        </div>
                                        <div class="flex flex-col relative">
                                                <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer ${$color2} rounded-lg shadow-xl img_btn flex" onclick="$('#img2').click()" title="image 2"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">2</span></div>
                                `;

                                if ($length > 1) {
                                    $list += `
                                    <button type="button" class="bg-rose-500 hover:bg-rose-800 text-white w-6 rounded del_img" data-id="${res.image[1].id}" style="position:absolute;margin-left:73px;z-index:100000 !important">-</button>
                                    <div class="flex justify-center">
                                        <small class="cursor-pointer hover:underline view_image" data-id="${res.image[1].id}">view</small>
                                        <input type="hidden" name="image2" value="ok">
                                    </div>
                                    `;
                                }

                                $list += `
                                        </div>
                                        <div class="flex flex-col relative">
                                            <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer ${$color3} rounded-lg shadow-xl img_btn flex" onclick="$('#img3').click()" title="image 3"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">3</span></div>

                                `;

                                if ($length > 2) {
                                    $list += `
                                    <button type="button" class="bg-rose-500 hover:bg-rose-800 text-white w-6 rounded del_img" data-id="${res.image[2].id}" style="position:absolute;margin-left:73px;z-index:100000 !important">-</button>
                                    <div class="flex justify-center">
                                        <small class="cursor-pointer hover:underline view_image" data-id="${res.image[2].id}">view</small>
                                        <input type="hidden" name="image3" value="ok">
                                    </div>
                                    `;
                                }
                                $list += `
                                        </div>
                                            <input type="file" class="car_img" accept="image/*" name="image_1" hidden id="img1">
                                            <input type="file" class="car_img" accept="image/*" name="image_2" hidden id="img2">
                                            <input type="file" class="car_img" accept="image/*" name="image_3" hidden id="img3">
                                        <div class="col-span-3">
                                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                                `;

                                $(".change_img_body").html('');
                                $(".change_img_body").append($list);
                                $('#change_img').show();
                                $('#car_choose').hide();
                            }
                        })

                    })

                    $(document).on('click', '.view_image', function(e) {
                        $id = $(this).data('id');

                        $.ajax({
                            url: "{{ route('show_one') }}",
                            type: "POST",
                            data: {
                                _token: token,
                                id: $id
                            },
                            success: function(res) {
                                $list = '';
                                $list += `
                                <div class="">
                                    <img src="{{ asset('storage/${res}') }}" class="mb-5 shadow-xl" alt="" style="width:700px">
                                </div>
                                `;
                                $('#image_container').html('');
                                $('#image_container').append($list);
                                // $('#change_img').hide();
                                $('#image_model').show();
                            }
                        })
                    })

                    $(document).on('click', '.del_img', function() {
                        $id = $(this).data('id');

                        $.ajax({
                            url: '/del_one_img/' + $id,
                            success: function(res) {
                                window.location.reload();
                            }
                        })
                    })
                }

                $(document).on('change', '#truck_type', function(e) {
                    var selectedOption = $(this).find('option:selected');
                    var truckName = selectedOption.data('name');

                    if (truckName === "Motorcycle") {
                        $('#truck_no').attr('placeholder', '');
                    } else {
                        $('#truck_no').attr('placeholder', 'truck...');
                    }
                    // $vali   = $(this).find('option:selected').data('re');
                    // if($vali == 'car' && $('#tru_imp').hasClass('hidden'))
                    // {
                    //     $('#tru_imp').removeClass('hidden');

                    // }else if($vali == 'no_car' && !$('#tru_imp').hasClass('hidden'))
                    // {
                    //     $('#tru_imp').addClass('hidden');
                    // }
                    // $('#no_car').val($vali == 'car' ?  0 : 1);
                })
            })
        </script>
    @endpush
@endsection
