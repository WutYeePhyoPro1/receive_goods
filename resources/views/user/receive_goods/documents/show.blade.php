@extends('layout.layout')


@section('content')
    <!-- MAIN CONTENT CONTAINER -->
  
    <div class="md:w-[80%] pb-16 px-4 pt-4 mx-auto">
        @if (Session::has('fails'))
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm" role="alert">
                <div class="mt-0.5">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>

                <div class="flex-1 text-sm font-medium">
                    {{ Session::get('fails') }}
                </div>

                <button type="button"
                    class="ml-3 inline-flex h-5 w-5 items-center justify-center rounded text-red-500 hover:bg-red-100 hover:text-red-700"
                    onclick="this.closest('[role=alert]').remove()"
                    aria-label="Close">
                    &times;
                </button>
            </div>
        @endif

        <form id="rg_form" action="" method="POST">
            @csrf
            <!-- UNIFIED CARD CONTAINER -->
            <div id="btn_status"></div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs">
                
                <!-- HEADER SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-receipt text-amber-500 text-base'></i> Purchase Order (PO)
                        </h2>
                    </div>

                    <!-- 3-Column Compact Grid for Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2 items-end">
                        
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_code" readonly id="vendor_code" class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" placeholder="VEN-999999" value="{{ $po_document->vendor_code }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Name <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_name" readonly id="vendor_name" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Vendor Name" value="{{ $po_document?->vendor?->vendor_name }}">
                        </div>

                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO No <span class="text-red-600">*</span></label>
                            <select id="po_no" name="po_no" class="w-full h-8 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                {{--
                                <option value="" disabled selected>Choose PO No:</option>
                                 @foreach($documents as $document)
                                <option value="{{ $document->document_no }}">{{ $document->document_no }}</option>
                                @endforeach
                                --}}
                                <option value="{{ $po_document->po_no }}" selected>{{ $po_document->po_no }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO Date <span class="text-red-600">*</span></label>
                            <input name="po_date" readonly id="purchasedate" type="date" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" value="{{ $po_document->purchasedate }}">
                        </div>
                        <div>
                            <label  class="block font-medium text-slate-500 mb-0.5">Branch <span class="text-red-600">*</span></label>
                                <select
                                    name="branch_id"
                                    class="w-full h-8 px-2 border border-slate-300 rounded
                                        bg-slate-100 text-slate-500
                                        cursor-not-allowed
                                        appearance-none
                                        focus:outline-none"
                                >

                                    {{-- <option selected value="{{ $userdata->branch->id }}">{{ $userdata->branch->branch_name }}</option> --}}
                                    <option selected value="{{ $po_document->branch_id }}">{{ $po_document->branch->branch_name }}</option>
                                </select>
                        </div>



                    </div>
                </div>

                <!-- UTILITY DIVIDER -->
                <div class="border-t border-dashed border-slate-200 my-1"></div>

                <!-- PRODUCT DETAILS SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-box text-amber-500 text-base'></i> Product Details
                            <div id="product_error" class="text-red-500 text-[10px] mt-0.5"></div>
                        </h3>
                    </div>

                    <!-- STICKY HEADER & SCROLLABLE TABLE CONTAINER -->
                    <div class="border border-slate-200 rounded overflow-hidden max-h-[320px] overflow-y-auto overflow-x-auto relative shadow-inner">
                        <table id="productTable" class="w-full text-left border-collapse">
                            <!-- Frozen Table Header -->
                            <thead class="sticky top-0 bg-slate-100 z-10 border-b border-slate-200 shadow-sm">
                                <tr class="text-slate-600 font-semibold uppercase text-[11px] tracking-wider whitespace-nowrap">
                                    <th class="py-2 px-3 w-auto">No</th>
                                    <!-- <th class="py-2 px-3 w-auto text-center">Select</th> -->
                                    <th class="py-2 px-3 w-auto">Product Code</th>
                                    <th class="py-2 px-3 w-auto">Unit</th>
                                    <th class="py-2 px-3 w-auto text-right">PO Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">GR Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Price</th>
                                    <th class="py-2 px-3 w-auto text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                      
                            </tbody>
                        </table>
                    </div>

                    <!-- TOTAL AMOUNT DISPLAY SUMMARY -->
                    <div class="mt-3 flex justify-end">
                        <div class="bg-slate-50 border border-slate-200 rounded flex items-center divide-x divide-slate-200 overflow-hidden">
                            <span class="px-3 py-1.5 font-bold uppercase text-slate-500 text-[10px] tracking-wider">Total Amount</span>
                            <span id="total_amount" class="px-4 py-1.5 font-extrabold text-sm text-slate-800 bg-amber-50/50">{{-- 1,055.00 --}}</span>
                            <input id="total_amount_input" name="total_amount" type="hidden" value="{{-- $po_document->totalamount --}}" />
                        </div>
                    </div>

                </div>

               
                <div class="flex items-center gap-2 p-4">

                    <button type="button" class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium"
                    onclick="window.location.href='{{ route('rg_documents') }}'"
                    >
                        Back
                    </button>


                </div>
            </div>
        </form>

    </div>


  
    @push('js')
    <script src="{{ asset('assets/libs/flatpickrv4/flatpickr.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            flatpickr("#purchasedate", {
                dateFormat: "Y-m-d",
                // minDate: "today",
                clickOpens: false ,
                maxDate: new Date().fp_incr(30)
            });

            flatpickr("#delivery_date", {
                // defaultDate: new Date(),
                dateFormat: "Y-m-d",
                // minDate: "today",
                maxDate: new Date().fp_incr(30)
            });


            // $("#purchasedate").flatpickr({
            //     dateFormat: "Y-m-d",
            //     minDate: "today",
            //     maxDate: new Date().fp_incr(30)
            // });
        });

        // ... rest of your existing AJAX code ...
    </script>
        <script type="text/javascript">
            </script>

    @endpush
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickrv4/flatpickr.min.css') }}">
@endsection