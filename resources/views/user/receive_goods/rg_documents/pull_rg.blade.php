@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    <div class="md:w-[80%] pb-16 px-4 pt-4 mx-auto">
        <form id="rg_form" action="" method="POST">
            @csrf
            <!-- UNIFIED CARD CONTAINER -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs">
                
                <input type="hidden" id="receive_id" value="{{ $good_receive->id }}">
                <!-- HEADER SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-receipt text-amber-500 text-base'></i> Receive Goods Header
                        </h2>
                    </div>

                    <!-- 3-Column Compact Grid for Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2 items-end">
                        
                        <!-- Row 1 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Scan Document No. <span class="text-red-600">*</span></label>
                            <input type="text" name="scan_document_no" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No..." value="{{ $good_receive->document_no }}">
                            <input type="text" name="scan_id" readonly hidden class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No..." value="{{ $good_receive->id }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_code" readonly id="vendor_code" class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" placeholder="VEN-999999" value="{{-- $good_receive->vendor_name --}}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Name <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_name" readonly id="vendor_name" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Vendor Name" value="{{-- $good_receive->vendor_name --}}">
                        </div>

                        <!-- Row 2 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO No <span class="text-red-600">*</span></label>
                            <select id="po_no" name="po_no" class="w-full h-8 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                <option value="" disabled selected>Choose PO No:</option>
                                @foreach($documents as $document)
                                <option value="{{ $document->document_no }}">{{ $document->document_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO Date <span class="text-red-600">*</span></label>
                            <input name="po_date" readonly id="purchasedate" type="date" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label  class="block font-medium text-slate-500 mb-0.5">Branch <span class="text-red-600">*</span></label>
                                <select
                                    id="branch_id"
                                    name="branch_id"
                                    class="w-full h-8 px-2 border border-slate-300 rounded
                                        bg-slate-100 text-slate-500
                                        cursor-not-allowed
                                        appearance-none
                                        focus:outline-none"
                                >
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $userdata->branch->id  ? "selected" : "" }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- Row 3 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Note <span class="text-red-600">*</span> <span id="delivery_note_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="text" name="delivery_note" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Delivery Note">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Date <span class="text-red-600">*</span> <span id="delivery_date_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="date" name="delivery_date" id="delivery_date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Ship By <span class="text-red-600">*</span> <span id="ship_by_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select name="ship_by" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                    <option value="">Choose a Transportation</option>
                                    @foreach($transportations as $transportation)
                                        <option value="{{ $transportation->transp_code }}">{{ $transportation->transp_name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <!-- Row 4 (Remark spans 2 columns, Checkboxes group occupies 1) -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Remark <span class="text-red-600">*</span> <span id="receive_type_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select name="receive_type" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                            <option value="">Choose a remark type</option>
                                @foreach($receives as $receive)
                                    <option value="{{ $receive->remark_id }}">{{ $receive->remark_type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 self-end">
                            <label class="block font-medium text-slate-500 mb-0.5"> </label>
                            <input type="text" name="po_remark" readonlys id="remark" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-alloweds" placeholder="Enter remarks details here...">
                        </div>
                        
                        <!-- Checkbox Controls Alignment -->

                        <div class="md:col-span-3">
                        <div class="grid grid-cols-3 gap-2 pt-4 items-center">
                            <label class="flex items-center gap-1.5 cursor-pointer font-medium text-slate-600">
                                <input type="checkbox" id="receive_all" class="w-3.5 h-3.5 accent-amber-500 rounded"> Select All
                            </label>
                            <div class="flex gap-2">
                                <label class="flex items-center gap-1.5 cursor-pointer font-medium text-slate-600">
                                    <input name="r008" type="checkbox" class="w-3.5 h-3.5 accent-amber-500 rounded"> R008
                                </label>
                                <div>
                                    <input type="text" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 text-center cursor-not-allowed" value="" title="">
                                </div>
                            </div>
                            <div></div>
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
                                    <th class="py-2 px-3 w-auto text-center">Select</th>
                                    <th class="py-2 px-3 w-auto">Product Code</th>
                                    <th class="py-2 px-3 w-auto">Product Name</th>
                                    <th class="py-2 px-3 w-auto">Unit</th>
                                    <th class="py-2 px-3 w-auto text-right">PO Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">GR Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Price</th>
                                    <th class="py-2 px-3 w-auto text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <!-- Row 1 Template -->
                                <!-- <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3 font-medium text-slate-400">1</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <input type="checkbox" class="accent-amber-500 rounded">
                                    </td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">PROD-10029</td>
                                    <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">PCS</span></td>
                                    <td class="py-1.5 px-3 text-right font-medium">50</td>
                                    <td class="py-1.5 px-3 text-right">
                                        <input type="number" class="w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="50">
                                    </td>
                                    <td class="py-1.5 px-3 text-right text-slate-500">12.00</td>
                                    <td class="py-1.5 px-3 text-right font-medium text-slate-700">600.00</td>
                                </tr> -->
                                <!-- Row 2 Template -->
                                <!-- <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3 font-medium text-slate-400">2</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <input type="checkbox" class="accent-amber-500 rounded">
                                    </td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">PROD-20384</td>
                                    <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">BOX</span></td>
                                    <td class="py-1.5 px-3 text-right font-medium">10</td>
                                    <td class="py-1.5 px-3 text-right">
                                        <input type="number" class="w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="10">
                                    </td>
                                    <td class="py-1.5 px-3 text-right text-slate-500">45.50</td>
                                    <td class="py-1.5 px-3 text-right font-medium text-slate-700">455.00</td>
                                </tr> -->
                            </tbody>
                        </table>
                        @include('components.loader')
                    </div>

                    <!-- TOTAL AMOUNT DISPLAY SUMMARY -->
                    <div class="mt-3 flex justify-end">
                        <div class="bg-slate-50 border border-slate-200 rounded flex items-center divide-x divide-slate-200 overflow-hidden">
                            <span class="px-3 py-1.5 font-bold uppercase text-slate-500 text-[10px] tracking-wider">Total Amount</span>
                            <span id="total_amount" class="px-4 py-1.5 font-extrabold text-sm text-slate-800 bg-amber-50/50">{{-- 1,055.00 --}}</span>
                            <input id="total_amount_input" name="total_amount" type="hidden" value="" />

                        </div>
                    </div>

                </div>

                <div class="flex items-center gap-2 p-4">

                    <button type="button" class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium"
                    onclick="window.location.href='{{ route('receive_goods', $good_receive->id) }}'"
                    >
                        Back
                    </button>

                    <button type="submit" id="saveBtn" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-blue-700 text-white text-[12px] font-medium shadow-sm">
                        Save
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

            var deliveryDatePicker = flatpickr("#delivery_date", {
                defaultDate: new Date(),
                dateFormat: "Y-m-d",
                // minDate: "today",
                maxDate: new Date().fp_incr(30)
            });


            // $("#purchasedate").flatpickr({
            //     dateFormat: "Y-m-d",
            //     minDate: "today",
            //     maxDate: new Date().fp_incr(30)
            // });

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
                    beforeSend:function(){
                        // $(".loader").addClass("show");
                        $(".loader").removeClass('hidden'); 
                    },
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
                        $('#branch_id').val(document.branch_id);

                        deliveryDatePicker.set('minDate', document.purchasedate);

                        products.forEach((product,idx) => {
                            // let key = `${product.bar_code}_${product.price}`;
                            let key = `${product.bar_code}_${idx}`;

                            let html = `
                                <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <input name="product_code[]" type="checkbox" id="pickup_${key}" class="receive_barcode accent-amber-500 rounded" value="${product.bar_code}">
                                    </td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.bar_code}</td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.supplier_name}</td>
                                    <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">${product.unit}</span></td>
                                    <td class="py-1.5 px-3 text-right font-medium">${product.remaining_qty}</td>
                                    <td class="py-1.5 px-3 text-right">
                                        <div id="gr_view_${key}" class="w-24  ms-auto">
                                            <span>${product.remaining_qty}<span>
                                        </div>

                                        <div id="gr_edit_${key}" hidden class="w-24  ms-auto">
                                            <input type="number" name="gr_qty[]" id="gr_qty_${key}" disabled class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.remaining_qty}">

                                            <input name="product_name[]" type="hidden" value="${product.supplier_name}" disabled />
                                            <input name="unit[]" type="hidden" value="${product.unit}" disabled />
                                            <input name="po_qty[]" type="hidden" value="${product.remaining_qty}" disabled />
                                            <input name="price[]" type="hidden" value="${product.price}" disabled />
                                            <input name="amount[]" id="amount_${key}_input" type="hidden" value="${product.price * product.remaining_qty}" disabled />
                                            <input name="product_id[]" type="hidden" value="${product.id}" disabled />
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3 text-right text-slate-500">${formatComma(product.price)}</td>
                                    <td id="amount_${key}" class="py-1.5 px-3 text-right font-medium text-slate-700">${formatComma(product.price * product.remaining_qty)}</td>
                                </tr>
                            `;
                            $('#productTable tbody').append(html);

                            $(`#gr_qty_${key}`).on('input', function() {

                                var qty = parseInt($(this).val()) || 0;
                                var poqty = parseInt(product.remaining_qty);
                                var price = product.price;
                                var amount = qty * price;
                                console.log(amount);

                                if(qty > poqty){
                                    $(this).val(poqty)
                                    $(`#amount_${key}`).html(formatComma(product.amount));
                                    $(`#amount_${key}_input`).val(product.amount);
                                }else{
                                    $(`#amount_${key}`).html(formatComma(amount));
                                    $(`#amount_${key}_input`).val(amount);
                                }
                                calculateTotalAmount()
                            });

                            $(`#pickup_${key}`).change(function(){
                                var isChecked = $(this).prop("checked") === true ? true : false;

                                let grView = $(`#gr_view_${key}`);
                                let grEdit = $(`#gr_edit_${key}`);
                                let qtyInput = $(`#gr_qty_${key}`);

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

                    },
                    complete:function(){
                        // $(".loader").removeClass("show");
                        $(".loader").addClass('hidden');
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

            let isSubmitting = false;
            $('#rg_form').submit(function(e){

                e.preventDefault();
                if (isSubmitting) return;

                if(validateForm() || false){

                    Swal.fire({
                        icon: "question",
                        text: "Are you sure to save RG to ERP?",
                        showCancelButton: true,
                    }).then((result) => {
                        if(result.isConfirmed)
                        {

                            isSubmitting = true;                            
                            $(".fullloader").removeClass("hidden");
                            // Swal.disableButtons();
                            $('#saveBtn').prop('disabled', true)

                            console.log('submit');

                            // form submit here
                            // console.log($('#rg_form').serialize());

                            $.ajax({
                                url:"{{ route('save_rg') }}",
                                type:"POST",
                                dataType: "json",
                                data:$("#rg_form").serialize(),
                                success:async function(response){
                                    console.log(response);

                                    const data = response;

                                    if(data.success){
                                        Swal.fire({
                                            icon: "success",
                                            title: "RG saved successfully!",
                                            text: data.message,
                                        });
                                        
                                        const receive_good_document = data.data;
                                        
                                        const recirectURL = "{{ route('rg_documents') }}";
                                        if(receive_good_document.r008){
                                            await Swal.fire({
                                                icon: "question",
                                                title: "Continue to R008?",
                                                text: "This RG includes an R008 document. Would you like to submit it to ERP now?",
                                                showCancelButton: true,
                                                confirmButtonText: "Submit now",
                                                cancelButtonText: "Later",
                                                reverseButtons: true,
                                            }).then((result) => {
                                                if(result.isConfirmed){
                                                    window.open(`/receive_goods/rg_documents/${receive_good_document.id}/print-pdf`, '_blank');
                                                    
                                                    // // window.location.href = `/receive_goods/rg_documents/${receive_good_document.id}`
                                                    window.location.href = `/receive_goods/rg_documents/${receive_good_document.id}/r008`;
                                                }
                                            })
                                          
                                        }
                                        window.open(`/receive_goods/rg_documents/${receive_good_document.id}/print-pdf`, '_blank');

                                        setTimeout(() => {                                            
                                            window.location.href="{{ route('rg_documents') }}";
                                        }, 3000);

                                    }else{
                                        Swal.fire({
                                            icon: "error",
                                            title: "RG Save Error!!",
                                            text: "Something went wrong while saving the RG.",
                                        });

                                        isSubmitting = false;
                                        $(".fullloader").addClass("hidden");
                                    }
                                },
                                error:function(response){
                                    console.log("Error: ",response);

                                    Swal.fire({
                                        icon: "error",
                                        title: "RG Save Error!!",
                                        text: "Something went wrong while saving the RG.",
                                    });

                                    isSubmitting = false;
                                    $(".fullloader").addClass("hidden");
                                },
                                // complete:function(resopnse){
                                //     isSubmitting = false;
                            
                                //     $(".fullloader").addClass("hidden");
                                //     console.log('complete');
                                // }
                            });

                        }
                    })
                }

            });


           

        });
        </script>


    @endpush
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickrv4/flatpickr.min.css') }}">
@endsection