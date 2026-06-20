@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    @php 
    $manager = isManager($receive_good_document);
    @endphp
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

        @if (Session::has('success'))
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm" role="alert">
                <div class="mt-0.5">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>

                <div class="flex-1 text-sm font-medium">
                    {{ Session::get('success') }}
                </div>

                <button type="button"
                    class="ml-3 inline-flex h-5 w-5 items-center justify-center rounded text-green-500 hover:bg-green-100 hover:text-green-700"
                    onclick="this.closest('[role=alert]').remove()"
                    aria-label="Close">
                    &times;
                </button>
            </div>
        @endif


        <form id="rg_form" action="{{ route('rg_approve_form', $receive_good_document->id ) }}" method="POST">
            @csrf
            <!-- UNIFIED CARD CONTAINER -->
            <div id="btn_status"></div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs overflow-hidden">
                
                <input type="hidden" id="receive_id" value="{{-- $good_receive->id --}}">
                <!-- HEADER SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-receipt text-amber-500 text-base'></i> Receive Goods Header
                        </h2>
                    </div>

                    <!-- 3-Column Compact Grid for Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2 items-end">

                        <div class="md:col-span-3">
                            <div class="flex flex-col gap-0.5 rounded-sm border border-slate-200 bg-slate-100/80 px-3 py-1.5 text-xs text-slate-700 sm:flex-row sm:items-center sm:gap-2">
                                <span class="shrink-0 font-bold">
                                    Scanned No:
                                </span>

                                <span class="break-all font-semibold text-slate-800 sm:truncate">
                                    {{ $good_receive->document_no }}
                                </span>
                            </div>

                            <input type="hidden" name="scan_document_no" value="{{ $good_receive->document_no }}">
                            <input type="hidden" name="scan_id" value="{{ $good_receive->id }}">
                        </div>
                        
                        <!-- Row 1 -->
                        <!-- <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Scan Document No. <span class="text-red-600">*</span></label>
                            <input type="text" name="scan_document_no" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No..." value="{{ $good_receive->document_no }}">
                            <input type="text" name="scan_id" readonly hidden class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No..." value="{{ $good_receive->id }}">
                        </div> -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Document Date<span class="text-red-600">*</span></label>
                            <input name="form_doc_date" id="form_doc_date" type="date" class="w-full h-8 px-2 bg-slate-50s border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="{{ $receive_good_document->date ?? $receive_good_document->created_at->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_code" readonly id="vendor_code" class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" placeholder="VEN-999999" value="{{ $receive_good_document->vendor_code }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Name <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_name" readonly id="vendor_name" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Vendor Name" value="{{ $receive_good_document?->vendor?->vendor_name }}">
                        </div>

                        <!-- Row 2 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO No <span class="text-red-600">*</span>
                            <button
                                type="button"
                                class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $receive_good_document->po_no }}')"
                                title="Copy"
                            >
                                <i class="fa-regular fa-copy"></i>
                            </button>

                            @php
                                $status = strtolower($receive_good_document?->document?->status ?? 'default');
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4 {{ $statusClasses[$status] }}">
                                {{ $receive_good_document?->document?->status }}
                            </span>

                            </label>
                            <select id="po_no" name="po_no" class="w-full h-8 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                {{--
                                <option value="" disabled selected>Choose PO No:</option>
                                 @foreach($documents as $document)
                                <option value="{{ $document->document_no }}">{{ $document->document_no }}</option>
                                @endforeach
                                --}}
                                <option value="{{ $receive_good_document->po_no }}" selected>{{ $receive_good_document->po_no }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO Date <span class="text-red-600">*</span></label>
                            <input name="po_date" readonly id="purchasedate" type="date" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" value="{{ $receive_good_document?->document?->purchasedate }}">
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
                                    <option selected value="{{ $receive_good_document->branch_id }}">{{ $receive_good_document->branch->branch_name }}</option>
                                </select>
                        </div>

                        <!-- Row 3 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Note <span class="text-red-600">*</span> <span id="delivery_note_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="text" name="delivery_note" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Delivery Note" value="{{ $receive_good_document->delivery_note }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Date <span class="text-red-600">*</span> <span id="delivery_date_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="date" name="delivery_date" id="delivery_date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="{{ $receive_good_document->delivery_date }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Ship By <span class="text-red-600">*</span> <span id="ship_by_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select name="ship_by" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                    <option value="">Choose a Transportation</option>
                                    @foreach($transportations as $transportation)
                                        <option value="{{ $transportation->transp_code }}" {{ $transportation->transp_code == $receive_good_document->ship_by ? 'selected' : '' }}>{{ $transportation->transp_name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <!-- Row 4 (Remark spans 2 columns, Checkboxes group occupies 1) -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Remark <span class="text-red-600">*</span> <span id="receive_type_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select name="receive_type" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                            <option value="">Choose a remark type</option>
                                @foreach($receives as $receive)
                                    <option value="{{ $receive->remark_id }}" {{ $receive->remark_id == $receive_good_document->receive_type ? 'selected' : '' }}>{{ $receive->remark_type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 self-end">
                            <label class="block font-medium text-slate-500 mb-0.5"> </label>
                            <input type="text" name="remark" readonly id="remark" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Enter remarks details here..." value="{{ $receive_good_document->remark }}">
                        </div>
                        
                        {{-- Checkbox Controls Alignment --}}
                        <div class="md:col-span-3">
                            <div class="grid grid-cols-1 gap-3 pt-4s sm:grid-cols-2 xl:grid-cols-3 items-end xl:items-centers">

                                <!-- <label class="flex min-h-9 items-center gap-2 cursor-pointer font-medium text-slate-600">
                                    <input type="checkbox" id="receive_all" class="h-4 w-4 rounded accent-amber-500">
                                    <span>Select All</span>
                                </label> -->

                                <div>
                                    <label class="block font-medium text-slate-500 mb-0.5">GR By <span class="text-red-600">*</span> <span id="ship_by_error" class="text-red-500 text-[10px] ml-1"></span></label>
                                    <select id="gr_by" name="gr_by" class="w-full h-8 px-2s border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                            <option value="">Choose GR Staff</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id == $receive_good_document->gr_by ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                                

                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <label class="flex min-h-9 items-center gap-2 cursor-pointer font-medium text-slate-600 sm:shrink-0">
                                        <input name="r008" type="checkbox" class="h-4 w-4 rounded accent-amber-500"
                                            {{ $receive_good_document->r008 ? 'checked' : '' }}>
                                        <span>R008</span>

                                        <button
                                            type="button"
                                            class="ml-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                            onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $receive_good_document->receive_good_files->where('name','R008')->first()?->file }}')"
                                            title="Copy"
                                        >
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </label>

                                    {{-- @dd($receive_good_document->r008_document()) --}}
                                    <input type="text"
                                        readonly
                                        class="h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-centers text-sm text-slate-500 cursor-not-alloweds cursor-pointer"
                                        value="{{ $receive_good_document->receive_good_files->where('name','R008')->first()?->file }}"
                                        @if($receive_good_document->r008_document()?->id)
                                        onClick="window.open('{{ route('r008s.show',$receive_good_document->r008_document()?->id) }}', '_blank')"
                                        @endif
                                        >
                                </div>

                                <div class="flex min-w-0 flex-col gap-2 sm:col-span-2 sm:flex-row sm:items-center xl:col-span-1">
                                 
                                    <span class="text-sm font-medium text-slate-600 sm:shrink-0">
                                        RG No:    
                                        <button
                                            type="button"
                                            class="ml-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                            onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $receive_good_document->receive_good_files->first()?->file }}')"
                                            title="Copy"
                                        >
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </span>

                                    <input type="text"
                                        readonly
                                        value="{{ $receive_good_document->receive_good_files->first()?->file }}"
                                        class="h-9 w-full min-w-0 rounded-lg border border-blue-300 bg-blue-100 px-3 font-bold tracking-wide text-blue-700 focus:outline-none">

                                    @php
                                        $status = strtolower($receive_good_document->status ?? 'default');
                                    @endphp
                                    @if($status == 'cancel')
                                    <span class="inline-flex w-fit items-center rounded-full px-2.5 py-0.5 text-xs font-medium sm:shrink-0 {{ $statusClasses[$status] ?? $statusClasses['default'] }}">
                                        {{ $receive_good_document->status }}
                                    </span>
                                    @endif

                                    
                                </div>

                            </div>
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
                                    <th class="py-2 px-3 w-auto">Product Name</th>
                                    <th class="py-2 px-3 w-auto">Unit</th>
                                    <th class="py-2 px-3 w-auto text-right">PO Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">GR Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Price</th>
                                    <th class="py-2 px-3 w-auto text-right">Disc.</th>
                                    <th class="py-2 px-3 w-auto text-right">Amount</th>
                                    <th class="py-2 px-3 w-40">Remark</th>
                                    <th class="py-2 px-3 w-auto text-rights">R008 Dam.</th>
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
                            <input id="total_amount_input" name="total_amount" type="hidden" value="{{-- $receive_good_document->totalamount --}}" />
                        </div>
                    </div>

                </div>
               
                <div class="flex items-center gap-2 p-4">

                    <button type="button" class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium"
                    onclick="window.location.href='{{ route('rg_documents') }}'"
                    >
                        Back
                    </button>

                    @if($receive_good_document->status !== "Cancel")
                    <button type="button" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-amber-700 text-white text-[12px] font-medium shadow-sm"
                    onClick="window.open('{{ route('rg_documents.print-pdf', $receive_good_document->id) }}', '_blank')"
                    >
                        Print PDF
                    </button>
                         {{--
                    <button type="submit" id="saveBtn" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-amber-700 text-white text-[12px] font-medium shadow-sm">
                        Save
                    </button>
                     --}}

                    @if($receive_good_document->r008 && !($receive_good_document->receive_good_files->where('name','R008')->first()?->file))
                    <button type="button" id="r008Btn" class="h-9 px-4 rounded-lg bg-blue-500 hover:bg-blue-700 text-white text-[12px] font-medium shadow-sm"
                        {{-- onClick="window.location.href = '{{ route('r008_rg',$receive_good_document->id) }}';" --}}
                            onClick="window.open('{{ route('r008_rg', $receive_good_document->id) }}', '_blank')"
                    >
                        Create R008
                    </button>
                    @endif
                    @endif

                    @if($manager && $receive_good_document->status !== "Cancel")
                    <!-- <button type="button" id="approveBtn" class="h-9 px-4 rounded-lg bg-red-500 hover:bg-red-700 text-white text-[12px] font-medium shadow-sm" value="Cancel"  name="status"
                    >
                        Cancel
                    </button> -->
                    @endif

                    @if(!$manager && !$receive_good_document->receive_good_reject)
                    <button type="button" id="cancelRequestBtn" class="h-9 px-4 rounded-lg bg-red-300 hover:bg-red-400 text-white text-[12px] font-medium shadow-sm" value="Cancel"  name="status">
                        Send Cancel Request
                    </button>
                    @endif
                </div>

                <div class="border-t border-gray-100 bg-neutral-50 p-5">
                       @if($manager || true)
                        <div class="mt-5s mb-3">
                            <!-- Reject Request with remark -->
                            @if($receive_good_document->receive_good_reject)
                                @php
                                    $rejectRequest = $receive_good_document->receive_good_reject;
                                @endphp

                                <div class="rounded-lg border border-red-200 bg-white shadow-sm">
                                    <div class="flex flex-col gap-2 border-b border-red-100 bg-red-50 px-4 py-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="bx bx-error-circle text-lg text-red-500"></i>
                                            <div>
                                                <div class="text-sm font-bold text-red-800">RG Cancel Request</div>
                                                <div class="text-[11px] text-red-600">
                                                    Requested by {{ $rejectRequest->user?->name ?? '-' }}
                                                    @if($rejectRequest->created_at)
                                                        on {{ $rejectRequest->created_at->format('Y-m-d H:i A') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($rejectRequest->approved_user_id)
                                            <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex w-fit items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                                Pending Manager Review
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 p-4 md:grid-cols-3">
                                        <div>
                                            <label class="block font-medium text-slate-500 mb-0.5">RG No</label>
                                            <input type="text"
                                                readonly
                                                class="h-8 w-full rounded border border-blue-300 bg-blue-100 px-3 text-sm font-bold tracking-wide text-blue-700 focus:outline-none"
                                                value="{{ $receive_good_document->receive_good_files->first()?->file }}">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block font-medium text-slate-500 mb-0.5">Cancel Reason</label>
                                            <div class="min-h-8 rounded border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm leading-5 text-slate-700">
                                                {{ $rejectRequest->remark }}
                                            </div>
                                        </div>

                                        @if($rejectRequest->image)
                                            <div class="md:col-span-3">
                                                <label class="block font-medium text-slate-500 mb-1">Attached File</label>
                                                <a href="{{ asset('storage/' . $rejectRequest->image) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 rounded border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                                    <i class="bx bx-paperclip text-base text-slate-500"></i>
                                                    View attachment
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!$rejectRequest->approved_user_id)
                                        <div class="flex flex-col gap-2 border-t border-slate-100 px-4 py-3 sm:flex-row sm:justify-end">
                                            <button type="submit"
                                                form="rg_cancel_request_reject_form"
                                                class="h-8 rounded border border-red-300 bg-white px-4 text-xs font-medium text-red-600 hover:bg-red-50">
                                                Reject Request
                                            </button>
                                            <button type="submit"
                                                form="rg_cancel_request_approve_form"
                                                class="h-8 rounded bg-red-500 px-4 text-xs font-medium text-white shadow-sm hover:bg-red-600">
                                                Approve Cancel Request
                                            </button>
                                        </div>
                                    @else
                                        <div class="border-t border-slate-100 px-4 py-2 text-xs text-slate-500">
                                            Approved by {{ $rejectRequest->approved_user?->name ?? '-' }}
                                            @if($rejectRequest->approved_datetime)
                                                on {{ \Carbon\Carbon::parse($rejectRequest->approved_datetime)->format('Y-m-d H:i A') }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500">
                                    No RG cancel request.
                                </div>
                            @endif
                        </div>
                        @endif
                    <div class="grid grid-cols-1 gap-6 text-sm leading-7 md:grid-cols-3">

                        @if($receive_good_document->rejected_by)
                            <div class="md:col-span-3">
                                <div class="relative rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-800">
                                    <p>
                                        This form was cancelled by
                                        <span class="font-bold">"{{ $receive_good_document->rejected->name }}"</span>.
                                    </p>

                                    <button type="button"
                                        class="absolute right-3 top-2 text-red-700 hover:text-red-900"
                                        aria-label="Close">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Prepared By --}}
                        <div class="space-y-1">
                            <div class="text-gray-600">Prepared By</div>
                            <div class="font-semibold text-blue-900">{{ $receive_good_document->user->name }}</div>
                            <div class="font-semibold text-blue-900">({{ $receive_good_document->user->department->name }})</div>
                            <div class="font-semibold text-blue-900">{{ $receive_good_document->created_at->format('Y-m-d H:i:s A')  }}</div>
                        </div>

                        {{-- Checked By --}}
                        <!-- <div class="space-y-1">
                            <div class="text-gray-600">Checked By Category Supervisor</div>
                            <div class="font-semibold text-blue-900">Daw Hla Hla</div>
                            <div class="font-semibold text-blue-900">(Inventory Control Department)</div>
                            <div class="font-semibold text-blue-900">2026-06-10 10:00 AM</div>
                        </div> -->

                        {{-- Approved By --}}
                        <!-- <div class="space-y-1">
                            <div class="text-gray-600">Approved By Merchandising Manager</div>
                            <div class="font-semibold text-blue-900">U Aung Aung</div>
                            <div class="font-semibold text-blue-900">(Merchandising Department)</div>
                            <div class="font-semibold text-blue-900">2026-06-10 10:15 AM</div>
                        </div> -->

                    </div>

                 
                </div>

            </div>
        </form>

        @if($manager && $receive_good_document->receive_good_reject && !$receive_good_document->receive_good_reject->approved_user_id)
            <form id="rg_cancel_request_approve_form" action="{{ route('receive_good_rejects_approve_form', $receive_good_document->receive_good_reject->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="status" value="Approve">
            </form>

            <form id="rg_cancel_request_reject_form" action="{{ route('receive_good_rejects_approve_form', $receive_good_document->receive_good_reject->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="status" value="Reject">
            </form>
        @endif

        <div id="rgCancelRequestModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 px-4">
            <div class="w-full max-w-md rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <h3 class="mb-0 text-sm font-bold text-slate-700">RG Cancel Request</h3>
                    <button type="button" class="text-xl leading-none text-slate-400 hover:text-slate-700" data-close-rg-cancel>
                        &times;
                    </button>
                </div>

                <form id="rg_cancel_form" action="{{ route('receive_good_rejects.store') }}" method="POST" class="px-4 py-3">
                    @csrf

                    <div id="errormessages" class="my-2">
                        <!-- <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Holy smokes!</strong>
                            <span class="block sm:inline">Something seriously bad happened.</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div> -->
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium text-slate-500 mb-0.5">RG No</label>
                        <input type="text"
                            readonly
                            class="h-8 w-full rounded border border-blue-300 bg-blue-100 px-3 text-sm font-bold tracking-wide text-blue-700 focus:outline-none"
                            value="{{ $receive_good_document->receive_good_files->first()?->file }}">
                        <input type="text"
                            hidden
                            name="receive_good_document_id"
                            class="h-8 w-full rounded border border-blue-300 bg-blue-100 px-3 text-sm font-bold tracking-wide text-blue-700 focus:outline-none"
                            value="{{ $receive_good_document->receive_good_files->first()?->receive_good_document_id }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-slate-500 mb-0.5">Cancel Remark <span class="text-red-600">*</span></label>
                        <textarea 
                            id="cancel_remark"
                            name="remark"
                            rows="4"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                            placeholder="Enter cancel request reason...">{{ old('remark') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-3">
                        <button type="button" class="h-8 rounded border border-slate-300 bg-white px-4 text-xs font-medium text-slate-700 hover:bg-slate-100" data-close-rg-cancel>
                            Close
                        </button>
                        <button type="submit" class="h-8 rounded bg-red-500 px-4 text-xs font-medium text-white shadow-sm hover:bg-red-600">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

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


            flatpickr("#form_doc_date", {
                dateFormat: "Y-m-d",
                // minDate: "today",
                maxDate: new Date().fp_incr(30)
            });

            $('#cancelRequestBtn').on('click', function () {
                $('#rgCancelRequestModal').removeClass('hidden').addClass('flex');
            });

            $('[data-close-rg-cancel]').on('click', function () {
                $('#rgCancelRequestModal').addClass('hidden').removeClass('flex');
            });

            $('#rgCancelRequestModal').on('click', function (e) {
                if (e.target === this) {
                    $(this).addClass('hidden').removeClass('flex');
                }
            });
        });

        // ... rest of your existing AJAX code ...
    </script>
    <script type="text/javascript">
        var token = $("meta[name='__token']").attr('content');
        const recieve_id = $('#receive_id').val();

        // console.log(formatComma(10000000));

        $('#po_no').change(function(){
            const getpono = $(this).val();
            $('#productTable tbody').html('');

            $.ajax({
                url: `/receive_po`,
                type: 'POST',
                data: {
                    _token: token,
                    purchaseno: getpono,
                    id: recieve_id,
                },
                dataType:"json",
                success:function(response){
                    console.log(response);
                    let data = response.data;

                    let document = data.document;
                    let products = data.products;

                    $('#vendor_code').val(document.vendor_code);
                    $('#vendor_name').val(document.vendor_name);
                    $('#purchasedate').val(document.purchasedate);
                    // $('#total_amount').text(formatComma(document.total_amount));
                    $('#remark').val(document.remark);


                    products.forEach((product,idx) => {
                        let html = `
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                                <td class="py-1.5 px-3 text-center">
                                    <input name="product_code[]" type="checkbox" id="pickup_${product.bar_code}" class="receive_barcode accent-amber-500 rounded" value="${product.bar_code}">
                                </td>
                                <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.bar_code}</td>
                                <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">${product.unit}</span></td>
                                <td class="py-1.5 px-3 text-right font-medium">${product.remaining_qty}</td>
                                <td class="py-1.5 px-3 text-right">
                                    <div id="gr_view_${product.bar_code}" class="w-24  ms-auto">
                                        <span>${product.remaining_qty}<span>
                                    </div>

                                    <div id="gr_edit_${product.bar_code}" hidden class="w-24  ms-auto">
                                        <input type="number" name="gr_qty[]" id="gr_qty_${product.bar_code}" disabled class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.remaining_qty}">

                                        <input name="product_name[]" type="hidden" value="${product.supplier_name}" disabled />
                                        <input name="unit[]" type="hidden" value="${product.unit}" disabled />
                                        <input name="po_qty[]" type="hidden" value="${product.remaining_qty}" disabled />
                                        <input name="price[]" type="hidden" value="${product.price}" disabled />
                                        <input name="amount[]" id="amount_${product.bar_code}_input" type="hidden" value="${product.amount}" disabled />
                                        <input name="product_id[]" type="hidden" value="${product.id}" disabled />
                                    </div>
                                </td>
                                <td class="py-1.5 px-3 text-right text-slate-500">${formatComma(product.price)}</td>
                                <td id="amount_${product.bar_code}" class="py-1.5 px-3 text-right font-medium text-slate-700">${formatComma(product.amount)}</td>
                            </tr>
                        `;
                        $('#productTable tbody').append(html);

                        $(`#gr_qty_${product.bar_code}`).on('input', function() {

                            var qty = parseInt($(this).val()) || 0;
                            var poqty = parseInt(product.remaining_qty);
                            var price = product.price;
                            var amount = qty * price;
                            console.log(amount);

                            if(qty > poqty){
                                $(this).val(poqty)
                                $(`#amount_${product.bar_code}`).html(formatComma(product.amount));
                                $(`#amount_${product.bar_code}_input`).val(product.amount);
                            }else{
                                $(`#amount_${product.bar_code}`).html(formatComma(amount));
                                $(`#amount_${product.bar_code}_input`).val(amount);
                            }
                            calculateTotalAmount()
                        });

                        $(`#pickup_${product.bar_code}`).change(function(){
                            var isChecked = $(this).prop("checked") === true ? true : false;

                            let grView = $(`#gr_view_${product.bar_code}`);
                            let grEdit = $(`#gr_edit_${product.bar_code}`);
                            let qtyInput = $(`#gr_qty_${product.bar_code}`);

                            if(isChecked){
                                grView.hide();
                                grEdit.show();

                                qtyInput.prop('disabled', false);
                                grEdit.find('input').prop('disabled',false);
                            }else{
                                grView.show();
                                grEdit.hide();

                                qtyInput.prop('disabled', true);
                                grEdit.find('input').prop('disabled',true);
                            }
                            calculateTotalAmount();
                        });
                    });
            

                }
                
            })

        });

        $('#receive_all').change(function () {

            let isChecked = $(this).prop('checked');

            $('.receive_barcode')
                .prop('checked', isChecked)
                .trigger('change');

        });

        function validateForm() {

            let deliveryNote = $('[name="delivery_note"]').val().trim();
            let deliveryDate = $('[name="delivery_date"]').val();
            let shipBy = $('[name="ship_by"]').val();
            let receiveType = $('[name="receive_type"]').val();
            let receive_barcodes_input =  $('.receive_barcode:checked');

            let isValid = true;

            // clear old errors
            $('.text-red-500').text('');
            $('.gr_qty').removeClass('border-red-500');


            if(deliveryNote === ''){
                $('#delivery_note_error').text('Delivery Note Required');
                isValid = false;
            }

            if(deliveryDate === ''){
                $('#delivery_date_error').text('Delivery Date Required');
                isValid = false;
            }
        
            if(shipBy === '' || shipBy == null){
                $('#ship_by_error').text('Ship By Required');
                isValid = false;
            }

            if(receiveType === '' || receiveType == null){
                $('#receive_type_error').text('Remark Required');
                isValid = false;
            }

            // GR Qty
            if(receive_barcodes_input.length == 0){
                $('#product_error').text('Please check at least one product.');
                isValid = false;
            }

            receive_barcodes_input.each(function(){

                let row = $(this).closest('tr');

                let qtyInput = row.find('.gr_qty');

                let qty = qtyInput.val();

                // console.log(qty);
                if(qty === '' || qty <= 0){

                    qtyInput.removeClass('border-slate-300').addClass('border-red-500');

                    // row.find('.qty-error').text('Invalid qty');

                    isValid = false;
                }

            });

            return isValid;
        }

        function calculateTotalAmount() {

            let total = 0;

            $('.receive_barcode:checked').each(function(){

                let row = $(this).closest('tr');

                let qty = parseFloat(row.find('.gr_qty').val()) || 0;
                let price = parseFloat(row.find('[name="price[]"]').val()) || 0;


                // let price = parseFloat(
                //     row.find('.price').data('price')
                // ) || 0;

                total += qty * price;

            });

            $('#total_amount').text(formatComma(total));
            $('#total_amount_input').val(total)
        }

        // let isSubmitting = false;
        // $('#rg_form').submit(function(e){

        //     e.preventDefault();
        //     if (isSubmitting) return;

        //     if(validateForm() || false){

        //         Swal.fire({
        //             icon: "question",
        //             text: "Are you sure to save RG to ERP?",
        //             showCancelButton: true,
        //         }).then((result) => {
        //             if(result.isConfirmed)
        //             {

        //                 isSubmitting = true;                            
        //                 $(".fullloader").removeClass("hidden");
        //                 // Swal.disableButtons();
        //                 $('#saveBtn').prop('disabled', true)

        //                 console.log('submit');

        //                 // form submit here
        //                 // console.log($('#rg_form').serialize());

        //                 $.ajax({
        //                     url:"{{ route('save_rg') }}",
        //                     type:"POST",
        //                     dataType: "json",
        //                     data:$("#rg_form").serialize(),
        //                     success:function(response){
        //                         console.log(response);

        //                         const data = response;

        //                         if(data.success){
        //                             Swal.fire({
        //                                 icon: "success",
        //                                 title: "RG saved successfully!",
        //                                 text: data.message,
        //                             });
                                    
        //                             const receive_good_document = data.data;
        //                             if(receive_good_document.r008){
        //                                 // window.location.href = `/receive_goods/rg_documents/${receive_good_document.id}`
        //                             }else{
        //                                 window.location.href="{{ route('rg_documents') }}"
        //                             }

        //                         }else{
        //                             Swal.fire({
        //                                 icon: "error",
        //                                 title: "RG Save Error!!",
        //                                 text: "Something went wrong while saving the RG.",
        //                             });

        //                             isSubmitting = false;
        //                             $(".fullloader").addClass("hidden");
        //                         }
        //                     },
        //                     error:function(response){
        //                         console.log("Error: ",response);

        //                         Swal.fire({
        //                             icon: "error",
        //                             title: "RG Save Error!!",
        //                             text: "Something went wrong while saving the RG.",
        //                         });

        //                         isSubmitting = false;
        //                         $(".fullloader").addClass("hidden");
        //                     },
        //                     // complete:function(resopnse){
        //                     //     isSubmitting = false;
                        
        //                     //     $(".fullloader").addClass("hidden");
        //                     //     console.log('complete');
        //                     // }
        //                 });

        //             }
        //         })
        //     }

        // });



        function showProducts(){
            const products = @json($receive_good_document->receive_good_products);

            products.forEach((product,idx) => {
                    // let key = `${product.bar_code}_${product.price}`;
                let key = `${product.bar_code}_${idx}`;
                
                $('#total_amount').text(formatComma({{ $receive_good_document->total_amount }}));

                let html = `
                    <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                        <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                        {{-- <td class="py-1.5 px-3 text-center">
                            <input name="product_code[]" type="checkbox" id="pickup_${product.bar_code}" class="receive_barcode accent-amber-500 rounded" value="${product.bar_code}">
                        </td>
                        --}}
                        <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.product_code}</td>
                        <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.product_name}</td>
                        <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">${product.unit}</span></td>
                        <td class="py-1.5 px-3 text-right font-medium">${product.po_qty}</td>
                        <td class="py-1.5 px-3 text-right">
                            <div id="gr_view_${product.bar_code}" class="w-24  ms-auto">
                                <span>${product.gr_qty}<span>
                            </div>

                            <div id="gr_edit_${product.bar_code}" hidden class="w-24  ms-auto">
                                <input type="number" name="gr_qty[]" id="gr_qty_${product.bar_code}" disabled class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.remaining_qty}">

                                <input name="product_name[]" type="hidden" value="${product.supplier_name}" disabled />
                                <input name="unit[]" type="hidden" value="${product.unit}" disabled />
                                <input name="po_qty[]" type="hidden" value="${product.remaining_qty}" disabled />
                                <input name="price[]" type="hidden" value="${product.price}" disabled />
                                <input name="amount[]" id="amount_${product.bar_code}_input" type="hidden" value="${product.amount}" disabled />
                                <input name="product_id[]" type="hidden" value="${product.id}" disabled />
                            </div>
                        </td>
                        <td class="py-1.5 px-3 text-right text-slate-500">${formatComma(product.price)}</td>
                        <td class="py-1.5 px-3 text-right font-medium">0</td>
                        <td id="amount_${product.bar_code}" class="py-1.5 px-3 text-right font-medium text-slate-700">${formatComma(product.amount)}</td>

                        <td class="py-1.5 px-3">
                            <div id="lineremark_view_${key}" class="w-40 ms-auto line_view">
                                <span>${product.remark ?? ''}<span>
                            </div>

                            <div id="lineremark_edit_${key}" hidden class="w-40 ms-auto line_edit">
                                <input type="text" name="line_remark[]" class="w-40 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" disabled/>
                            </div>
                        </td>
                        <td class="py-1.5 px-3 text-right font-medium">${product.r8damqty}</td>
                    </tr>
                `;
                $('#productTable tbody').append(html);


            });
        }
        showProducts();

        let isSubmitting = false;
        $('#approveBtn').click(function(e){

            e.preventDefault();
            if (isSubmitting) return;


            Swal.fire({
                icon: "question",
                text: "Are you sure want to Cancel RG?",
                showCancelButton: true,
            }).then((result) => {
                if(result.isConfirmed)
                {

                    isSubmitting = true;                            
                    $(".fullloader").removeClass("hidden");
                    // Swal.disableButtons();
                    $('#approveBtn').prop('disabled', true)

                    var btn = $(this).val();
                    $('#btn_status').append('<input type="hidden" name="status" value="' + btn + '" /> ');

                    $('#rg_form').submit();
                }
            })
        });

    
    const cancel_remark = document.querySelector('#cancel_remark');
    const errormessages = document.querySelector('#errormessages');
    $(document).on('submit', '#rg_cancel_form', function (e) {
        e.preventDefault();

        console.log('cancel form submit event attached');

        const getinputval = cancel_remark.value.trim();

        if(!getinputval){
            newErrorMessage('Cancel Remark is required!');
            return;
        }

        $('#rg_cancel_form').submit();
    });

    let newErrorMessage = (msg)=>{
        const div = document.createElement('div');
        div.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative';
        div.innerHTML  = `
            <span class="block sm:inline">${msg}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        `;
        errormessages.appendChild(div);
    }
    // <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    // <strong class="font-bold">Holy smokes!</strong>
    // <span class="block sm:inline">Something seriously bad happened.</span>
    // <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
    //     <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
    // </span>
    // </div>

        
    </script>

    <script type="text/javascript">
        function copyDocumentNo(button, targetId) {
            const text = targetId.trim();
                const icon = button.children[0];


            navigator.clipboard.writeText(text).then(() => {
                // icon.className = 'fa-solid fa-check text-green-600';
                $(icon).html('<i class="fa-solid fa-check text-green-600"></i>');

                setTimeout(() => {
                    $(icon).html('<i class="fa-regular fa-copy"></i>');
                }, 1500);
            });
        }
    </script>
    @endpush
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickrv4/flatpickr.min.css') }}">
@endsection
