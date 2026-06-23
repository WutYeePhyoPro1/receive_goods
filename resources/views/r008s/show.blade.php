@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    @php 
    $manager = isManager($r008_document);
    $approver = isAuthorizedUser($r008_document);
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

        <form id="r008_form" action="{{ route('r8_dapprove_form', $r008_document->id ) }}" method="POST">
            @csrf
            <div id="btn_status"></div>
            <!-- UNIFIED CARD CONTAINER -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs overflow-hidden">
                
                <!-- HEADER SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-receipt text-amber-500 text-base'></i> Show R008
                        </h2>
                    </div>

                    <!-- 4-Column Compact Grid for Inputs -->
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4 xl:items-end">

                        {{-- Document Date --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Document Date <span class="text-red-600">*</span>
                                <span id="document_date_error" class="ml-1 text-[10px] text-red-500"></span>
                            </label>

                            <input type="date"
                                name="document_date"
                                id="document_date"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-300 px-2 text-sm focus:border-amber-500 focus:outline-none"
                                value="{{ $r008_document->document_date }}">
                        </div>

                        {{-- Vendor Code --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Vendor Code <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                name="vendor_code"
                                readonly
                                id="vendor_code"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm text-slate-500 cursor-not-allowed"
                                placeholder="VEN-999999"
                                value="{{ $r008_document->vendor_code }}">
                        </div>

                        {{-- Vendor Name --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Vendor Name <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                name="vendor_name"
                                readonly
                                id="vendor_name"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm text-slate-500 cursor-not-allowed"
                                placeholder="Vendor Name"
                                value="{{ $r008_document->vendor->vendor_name }}">
                        </div>

                        {{-- Product Type --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Product Type <span class="text-red-600">*</span>
                                <span id="product_type_error" class="ml-1 text-[10px] text-red-500"></span>
                            </label>

                            <select name="product_type"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-300 bg-white px-2 text-sm focus:border-amber-500 focus:outline-none">
                                <option value="Local" {{ $r008_document->product_type == 'Local' ? 'selected' : '' }}>Local</option>
                                <option value="Import" {{ $r008_document->product_type == 'Import' ? 'selected' : '' }}>Import</option>
                            </select>
                        </div>

                        {{-- Receive Doc No --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Receive Doc. No <span class="text-red-600">*</span>
                                <span id="rg_no_error" class="ml-1 text-[10px] text-red-500"></span>

                                <button
                                    type="button"
                                    class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                    onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $r008_document->rg_no }}')"
                                    title="Copy"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>


                                @php
                                    $status = strtolower($r008_document->receive_good_document()->status ?? 'Default');
                                @endphp
                                @if($status == 'cancel')
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4 {{ $statusClasses[$status] }}">
                                    {{ $r008_document->receive_good_document()->status }}
                                </span>
                                @endif
                            </label>

                            <select id="rg_no"
                                name="rg_no"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-300 bg-white px-2 text-sm focus:border-amber-500 focus:outline-none">
                                <option value="{{ $r008_document->rg_no }}">{{ $r008_document->rg_no }}</option>
                            </select>
                        </div>

                        {{-- Invoice No --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Invoice No <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                name="invoice_no"
                                readonly
                                id="invoice_no"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm text-slate-500 cursor-not-allowed"
                                value="{{ $r008_document->receive_good_document()->delivery_note }}">
                        </div>

                        {{-- PO No --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                PO No <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                name="po_no"
                                readonly
                                id="po_no"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm text-slate-500 cursor-not-allowed"
                                value="{{ $r008_document->receive_good_document()->po_no }}">
                        </div>

                        {{-- Truck Container No --}}
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Truck/Container No.
                                <span id="truck_container_no_error" class="ml-1 text-[10px] text-red-500"></span>
                            </label>

                            <input type="text"
                                name="truck_container_no"
                                class="h-9 w-full min-w-0 rounded-lg border border-slate-300 px-2 text-sm focus:border-amber-500 focus:outline-none"
                                value="{{ $r008_document->truck_container_no }}">
                        </div>

                        {{-- Remark --}}
                        <div class="min-w-0 sm:col-span-2 xl:col-span-4">
                            <label class="mb-1 block text-sm font-medium text-slate-500">
                                Remark
                            </label>

                            <textarea name="remark"
                                rows="3"
                                class="w-full min-w-0 rounded-lg border border-slate-300 px-2 py-2 text-sm focus:border-amber-500 focus:outline-none">{{ $r008_document->remark }}</textarea>
                        </div>

                        {{-- R008 No --}}
                        <div class="min-w-0 sm:col-span-2 xl:col-span-4">
                            <div class="grid grid-cols-1 gap-3 xl:grid-cols-3">
                                <div class="min-w-0 xl:col-span-1">
                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-[auto_minmax(0,1fr)_auto] sm:items-center">
                                        <span class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                            R008 No:
                                            <button
                                                type="button"
                                                class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                                onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $r008_document->r008_files->first()?->file  }}')"
                                                title="Copy"
                                            >
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </span>

                                        <input type="text"
                                            readonly
                                            value="{{ $r008_document->r008_files->first()?->file }}"
                                            class="h-9 w-full min-w-0 rounded-lg border border-blue-300 bg-blue-100 px-3 font-bold tracking-wide text-blue-700 focus:outline-none">

                                        @php
                                            $status = strtolower($r008_document->status ?? 'default');
                                        @endphp
                                        @if($status == 'cancel')
                                        <span class="inline-flex w-fit items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses[$status] ?? $statusClasses['default'] }}">
                                            {{ $r008_document->status }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="hidden xl:block xl:col-span-2"></div>
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
                                    <th class="py-2 px-3 w-auto text-center">Status</th>
                                    <th class="py-2 px-3 w-auto">Product Code</th>
                                    <th class="py-2 px-3 w-auto">Product Name</th>
                                    <th class="py-2 px-3 w-auto text-right">RG Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Physical Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Diff.</th>
                                    <th class="py-2 px-3 w-auto text-right">Big Damage Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Small Damage Qty</th>
                                    <th class="py-2 px-3 w-auto text-left">Remark</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                            
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="flex items-center gap-2 p-4">

                    <button type="button" class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium"
                    onclick="window.location.href='{{ route('r008s.index') }}'"
                    >
                        Back
                    </button>

                    @if($r008_document->status !== "Cancel")
                    <button type="button" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-blue-700 text-white text-[12px] font-medium shadow-sm"
                    onClick="window.open('{{ route('r008s.print-pdf', $r008_document->id) }}', '_blank')"

                    >
                        Print PDF
                    </button>

                    {{-- 
                    <button type="submit" id="saveBtn" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-blue-700 text-white text-[12px] font-medium shadow-sm">
                        Save
                    </button>
                    --}}
                    @endif

                    @if($approver && $r008_document->status !== "Cancel")
                    <button type="button" id="approveBtn" class="h-9 px-4 rounded-lg bg-red-500 hover:bg-red-700 text-white text-[12px] font-medium shadow-sm" value="Cancel"  name="status"
                    >
                        Cancel
                    </button>
                    @endif

                </div>
                
                <div class="border-t border-gray-100 bg-neutral-50 p-5">
                    <div class="grid grid-cols-1 gap-6 text-sm leading-7 md:grid-cols-3">

                        @if($r008_document->rejected_by)
                            <div class="md:col-span-3">
                                <div class="relative rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-800">
                                    <p>
                                        This form was cancelled by
                                        <span class="font-bold">"{{ $r008_document->rejected->name }}"</span>.
                                        {{ $r008_document->rejected_at ? \Carbon\Carbon::parse($r008_document->rejected_at)->format('Y-m-d h:i:s A') : '-' }}
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
                            <div class="font-semibold text-blue-900">{{ $r008_document->user->name }}</div>
                            <div class="font-semibold text-blue-900">({{ $r008_document->user->department->name }})</div>
                            <div class="font-semibold text-blue-900">{{ $r008_document->created_at->format('Y-m-d H:i:s A')  }}</div>
                        </div>

                    </div>
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

            flatpickr("#document_date", {
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
            var token = $("meta[name='__token']").attr('content');
            const recieve_id = $('#receive_id').val();

            // console.log(formatComma(10000000));
 
            $('#rg_no').change(function(){
                const getrgno = $(this).val();
                $('#productTable tbody').html('');

                console.log(getrgno);
                $.ajax({
                    url: `/receive_r008`,
                    type: 'POST',
                    data: {
                        _token: token,
                        rg_no: getrgno,
                        id: recieve_id,
                    },
                    dataType:"json",
                    success:function(response){
                        console.log(response);
                        let data = response.data;

                        let rg_document = data.rg_document;
                        let rg_products = data.rg_products;
                        let statuses = data.statuses;

                        $('#vendor_code').val(rg_document.vendor_code);
                        $('#vendor_name').val(rg_document.vendor.vendor_name);
                        $('#invoice_no').val(rg_document.delivery_note);
                        $('#po_no').val(rg_document.po_no);



                        rg_products.forEach((product,idx) => {
                            let html = `
                                <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                                    <td class="py-1.5 px-3">
                                        <select id="status_ids" name="status_ids[]" class="status_ids w-[200px] h-6 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                            <option value="" disabled selected>--Choose Status:--</option>
                                            ${
                                                statuses.map((status)=>{
                                                    return `
                                                    <option value="${status.subjectr008_id}">${status.subjectr008_name}</option>
                                                    `
                                                })
                                            }
                                        </select>
                                    </td>
                                    <td class="py-1.5 px-3 text-center hidden">
                                        <input name="product_code[]" type="checkbox" id="pickup_${product.product_code}" class="receive_barcode accent-amber-500 rounded" value="${product.product_code}" checked>
                                    </td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.product_code}</td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">
                                        ${product.product_name}
                                        <input type="hidden" name="product_name[]" value="${product.product_name}" />
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-medium">
                                        ${product.po_qty}
                                        <input type="hidden" name="gr_qty[]" value="${product.po_qty}" />
                                    </td>
                                    <td class="py-1.5 px-3 text-right">
                                        <div id="physical_view_${product.product_code}" class="w-24  ms-auto">
                                            <span>${product.gr_qty}<span>
                                        </div>
                                        <div id="physical_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                            <input type="hidden" name="physical_qty[]" id="gr_qty_${product.gr_qty}" class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.gr_qty}">
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-medium">
                                        ${Math.abs(product.gr_qty - product.po_qty)}
                                        <input type="hidden" name="diff[]" value="${product.gr_qty - product.po_qty}" />
                                    </td>
                                    <td class="py-1.5 px-3 text-right">
                                        <div id="bd_view_${product.product_code}" class="w-24  ms-auto hidden">
                                            <span>${product.bdqty}<span>
                                        </div>
                                        <div id="bd_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                            <input type="hidden" name="bd_qty[]" id="bd_edit_${product.gr_qty}" class="bd_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${0}">
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3 text-right">
                                        <div id="sd_view_${product.product_code}" class="w-24  ms-auto hidden">
                                            <span>${product.sdqty}<span>
                                        </div>
                                        <div id="bd_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                            <input type="hidden" name="sd_qty[]" id="sd_edit_${product.gr_qty}" class="sd_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${0}">
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3">
                                        <div id="lineremark_view_${product.product_code}" class="w-40 ms-auto line_view hidden">
                                            <span>${product.remark ?? ''}<span>
                                        </div>

                                        <div id="lineremark_edit_${product.product_code}" hiddens class="w-40 ms-auto line_edit">
                                            <input type="hidden" name="line_remark[]" class="w-40 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500"/>
                                        </div>
                                    </td>
                                    
                                </tr>
                            `;
                            $('#productTable tbody').append(html);

                            // $(`#gr_qty_${product.bar_code}`).on('input', function() {

                            //     var qty = parseInt($(this).val()) || 0;
                            //     var poqty = parseInt(product.remaining_qty);
                            //     var price = product.price;
                            //     var amount = qty * price;
                            //     console.log(amount);

                            //     if(qty > poqty){
                            //         $(this).val(poqty)
                            //         $(`#amount_${product.bar_code}`).html(formatComma(product.amount));
                            //         $(`#amount_${product.bar_code}_input`).val(product.amount);
                            //     }else{
                            //         $(`#amount_${product.bar_code}`).html(formatComma(amount));
                            //         $(`#amount_${product.bar_code}_input`).val(amount);
                            //     }
                            //     calculateTotalAmount()
                            // });

                            // $(`#pickup_${product.bar_code}`).change(function(){
                            //     var isChecked = $(this).prop("checked") === true ? true : false;

                            //     let grView = $(`#gr_view_${product.bar_code}`);
                            //     let grEdit = $(`#gr_edit_${product.bar_code}`);
                            //     let qtyInput = $(`#gr_qty_${product.bar_code}`);

                            //     if(isChecked){
                            //         grView.hide();
                            //         grEdit.show();

                            //         qtyInput.prop('disabled', false);
                            //         grEdit.find('input').prop('disabled',false);
                            //     }else{
                            //         grView.show();
                            //         grEdit.hide();

                            //         qtyInput.prop('disabled', true);
                            //         grEdit.find('input').prop('disabled',true);
                            //     }
                            //     calculateTotalAmount();
                            // });
                        });
              

                    }
                    
                })

            });
            @if(isset($rg_no))
            // $('#rg_no').trigger('change');
            @endif


            function validateForm() {
                let rg_no = $('[name="rg_no"]').val()?.trim() || '';
                let receive_barcodes_input =  $('.receive_barcode:checked');
                // console.log(receive_barcodes_input.length); return '';

                let isValid = true;

                // clear old errors
                $('.text-red-500').text('');
                $('.gr_qty').removeClass('border-red-500');
                $('.status_ids').removeClass('border-red-500');


                if(rg_no === ''){
                    console.log("RG empty")
                    $('#rg_no_error').text('RG NO. Required.');
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
                    let statusInput = row.find('.status_ids');
                    

                    // let qty = qtyInput.val();
                    let status = statusInput.val() || '';
                    console.log(status);


                    // if(qty === '' || qty <= 0){

                    //     qtyInput.removeClass('border-slate-300').addClass('border-red-500');

                    //     // row.find('.qty-error').text('Invalid qty');

                    //     isValid = false;
                    // }

                    if(status === ''){
                        statusInput.removeClass('border-slate-300').addClass('border-red-500');

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
            // $('#r008_form').submit(function(e){

            //     e.preventDefault();
            //     if (isSubmitting) return;

            //     if(false || validateForm()){

            //         Swal.fire({
            //             icon: "question",
            //             text: "Are you sure to save R008?", // to Defective Product 246
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
            //                     url:"{{ route('r008s.store') }}",
            //                     type:"POST",
            //                     dataType: "json",
            //                     data:$("#r008_form").serialize(),
            //                     success:function(response){
            //                         console.log(response);

            //                         const data = response;

            //                         if(data.success){
            //                             Swal.fire({
            //                                 icon: "success",
            //                                 title: "R008 saved successfully!",
            //                                 text: data.message,
            //                             });
            //                             window.location.href="{{ route('r008s.index') }}"

            //                         }else{
            //                             Swal.fire({
            //                                 icon: "error",
            //                                 title: "R008 Save Error!!",
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
            //                             title: "R008 Save Error!!",
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

            (function showProducts(){
                const products = @json($r008_document->r008_products);
                const statuses = @json($statuses)

                products.forEach((product,idx) => {

                    let html = `
                        <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                            <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                            <td class="py-1.5 px-3">
                                <select id="status_ids" name="status_ids[]" class="status_ids w-[200px] h-6 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                    <option value="" disabled selected>--Choose Status:--</option>
                                    ${
                                        statuses.map((status)=>{
                                            return `
                                            <option value="${status.subjectr008_id}" ${ status.subjectr008_id == product.status_id ? 'selected' : '' }>${status.subjectr008_name}</option>
                                            `
                                        })
                                    }
                                </select>
                            </td>
                            <td class="py-1.5 px-3 text-center hidden">
                                <input name="product_code[]" type="checkbox" id="pickup_${product.product_code}" class="receive_barcode accent-amber-500 rounded" value="${product.product_code}" checked>
                            </td>
                            <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.product_code}</td>
                            <td class="py-1.5 px-3 font-mono font-medium text-slate-700">
                                ${product.product_name}
                                <input type="hidden" name="product_name[]" value="${product.product_name}" />
                            </td>
                            <td class="py-1.5 px-3 text-right font-medium">
                                ${product.gr_qty}
                                <input type="hidden" name="gr_qty[]" value="${product.gr_qty}" />
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <div id="physical_view_${product.product_code}" class="w-24  ms-auto">
                                    <span>${product.physical_qty}<span>
                                </div>
                                <div id="physical_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                    <input type="hidden" name="physical_qty[]" id="gr_qty_${product.physical_qty}" class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.gr_qty}">
                                </div>
                            </td>
                            <td class="py-1.5 px-3 text-right font-medium">
                                ${Math.abs(product.physical_qty - product.gr_qty)}
                                <input type="hidden" name="diff[]" value="${product.physical_qty - product.gr_qty}" />
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <div id="bd_view_${product.product_code}" class="w-24  ms-auto">
                                    <span>${product.bdqty}<span>
                                </div>
                                <div id="bd_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                    <input type="hidden" name="bd_qty[]" id="bd_edit_${product.gr_qty}" class="bd_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${0}">
                                </div>
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <div id="sd_view_${product.product_code}" class="w-24  ms-auto">
                                    <span>${product.sdqty}<span>
                                </div>
                                <div id="bd_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                    <input type="hidden" name="sd_qty[]" id="sd_edit_${product.gr_qty}" class="sd_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${0}">
                                </div>
                            </td>
                            <td class="py-1.5 px-3">
                                <div id="lineremark_view_${product.product_code}" class="w-40 ms-auto line_view">
                                    <span>${product.remark ?? ''}<span>
                                </div>

                                <div id="lineremark_edit_${product.product_code}" hiddens class="w-40 ms-auto line_edit">
                                    <input type="hidden" name="line_remark[]" class="w-40 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500"/>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#productTable tbody').append(html);


                });
            })()



            let isSubmitting = false;
            $('#approveBtn').click(function(e){

                e.preventDefault();
                if (isSubmitting) return;


                Swal.fire({
                    icon: "question",
                    text: "Are you sure want to Cancel R008?",
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

                        $('#r008_form').submit();
                    }
                })
            });

           
        </script>
    @endpush
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickrv4/flatpickr.min.css') }}">
@endsection