@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    <div class="md:w-[80%] pb-16 px-4 pt-4 mx-auto">
        <form id="r008_form" action="" method="POST">
            @csrf
            <!-- UNIFIED CARD CONTAINER -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs">
                
                <!-- HEADER SECTION -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                        <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <i class='bx bx-receipt text-amber-500 text-base'></i> Create R008
                        </h2>
                    </div>

                    <!-- 3-Column Compact Grid for Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-2 items-end">
                        
                        <!-- Row 1 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Document Date <span class="text-red-600">*</span> <span id="document_date_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="date" name="document_date" id="document_date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_code" readonly id="vendor_code" class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" placeholder="VEN-999999" value="{{-- $good_receive->vendor_name --}}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Name <span class="text-red-600">*</span></label>
                            <input type="text" name="vendor_name" readonly id="vendor_name" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Vendor Name" value="{{-- $good_receive->vendor_name --}}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Product Type <span class="text-red-600">*</span> <span id="product_type_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select name="product_type" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                <!-- <option value="">Choose a remark type</option> -->
                                    <option value="Local">Local</option>
                                    <option value="Import">Import</option>
                            </select>
                        </div>

                        <!-- Row 2 -->

                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Receive Doc. No <span class="text-red-600">*</span><span id="rg_no_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <select id="rg_no" name="rg_no" class="w-full h-8 px-2 bg-slate-100  border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                {{-- @if(!isset($rg_no)) --}}
                                <option value="" disabled selected>Choose RG No:</option>
                                {{-- @endif --}}
                                @if(isset($rg_no))
                                    <option value="{{ $rg_no }}">{{ $rg_no }}</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Invoice No<span class="text-red-600">*</span></label>
                            <input  type="text"  name="invoice_no" readonly id="invoice_no"class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">PO No<span class="text-red-600">*</span></label>
                            <input  type="text"  name="po_no" readonly id="po_no"class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Truck/Container No. <span class="text-red-600"></span> <span id="truck_container_no_error" class="text-red-500 text-[10px] ml-1"></span></label>
                            <input type="text" name="truck_container_no" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="">
                        </div>

                        <div class="col-span-4">
                            <label class="block font-medium text-slate-500 mb-0.5">Remark <span class="text-red-600"></span></label>
                            <textarea name="remark" rows="3" class="w-full h-auto px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder=""></textarea>
                        </div>

                        <input type="hidden" id="branch_id" name="branch_id" value="{{ $userdata->branch->id }}" />


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
                                    <th class="py-2 px-3 w-auto">Action</th>
                                    <th class="py-2 px-3 w-auto">No</th>
                                    <th class="py-2 px-3 w-auto text-center">Status</th>
                                    <th class="py-2 px-3 w-auto">Product Code</th>
                                    <th class="py-2 px-3 w-auto">Product Name</th>
                                    <th class="py-2 px-3 w-auto text-right">RG Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Physical Qty</th>
                                    <th class="py-2 px-3 w-auto text-right">Diff.</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                            
                            </tbody>
                        </table>
                        @include('components.loader')
                    </div>

                </div>

                <div class="flex items-center gap-2 p-4">

                    <button type="button" class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium"
                    onclick="window.location.href='{{-- route('receive_goods', $good_receive->id) --}}'"
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

            flatpickr("#document_date", {
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
                    beforeSend:function(){
                        // $(".loader").addClass("show");
                        $(".loader").removeClass('hidden'); 
                    },
                    success:function(response){
                        console.log(response);
                        let data = response.data;

                        let rg_document = data.rg_document;
                        let rg_products = data.rg_products;
                        let statuses = data.statuses;

                        $('#vendor_code').val(rg_document.vendor_code);
                        $('#vendor_name').val(rg_document?.vendor?.vendor_name);
                        $('#invoice_no').val(rg_document.delivery_note);
                        $('#po_no').val(rg_document.po_no);

                        $('#branch_id').val(rg_document.branch_id);

                        rg_products.forEach((product,idx) => {
                            let html = `
                                <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3">
                                        <button
                                            type="button"
                                            class="h-8 w-8 rounded-md border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 inline-flex items-center justify-center  removeRow">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </td>
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
                                        <div id="physical_view_${product.product_code}" class="w-24  ms-auto hidden">
                                            <span>${product.gr_qty}<span>
                                        </div>
                                        <div id="physical_edit_${product.product_code}" hiddens class="w-24  ms-auto">
                                            <input type="type" name="physical_qty[]" id="physical_qty_${product.gr_qty}" class="physical_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.gr_qty}">
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-medium">
                                        <span id="diff_${product.product_code}" >${Math.abs(product.gr_qty - product.po_qty)}</span>
                                        <input type="hidden" id="diff_input_${product.product_code}" name="diff[]" value="${product.gr_qty - product.po_qty}" />
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


                            $('.physical_qty').on('input',function(){
                                console.log('hay');
                                var qty = parseInt($(this).val()) || 0;
                                var rgqty = parseInt(product.po_qty);

                                var diff = Math.abs(qty - rgqty);

                                let diffView = $(`#diff_${product.product_code}`);
                                let diffInput = $(`#diff_input_${product.product_code}`);

                                diffView.text(diff);
                                diffInput.val(qty - rgqty);
                            });
                            

                            $(document).on('click','.removeRow',function (e) {
                                $(this).parent('td').parent('tr').remove();
                            });
              
                        })
                    },
                    complete:function(){
                        // $(".loader").removeClass("show");
                        $(".loader").addClass('hidden');
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

            let isSubmitting = false;
            $('#r008_form').submit(function(e){

                e.preventDefault();
                if (isSubmitting) return;

                if(false || validateForm()){

                    Swal.fire({
                        icon: "question",
                        text: "Are you sure to save R008?", // to Defective Product 246
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
                                url:"{{ route('r008s.store') }}",
                                type:"POST",
                                dataType: "json",
                                data:$("#r008_form").serialize(),
                                success:function(response){
                                    console.log(response);

                                    const data = response;

                                    if(data.success){
                                        Swal.fire({
                                            icon: "success",
                                            title: "R008 saved successfully!",
                                            text: data.message,
                                        });
                                        window.location.href="{{ route('r008s.index') }}"

                                    }else{
                                        Swal.fire({
                                            icon: "error",
                                            title: "R008 Save Error!!",
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
                                        title: "R008 Save Error!!",
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

           
        </script>
    @endpush
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickrv4/flatpickr.min.css') }}">
@endsection