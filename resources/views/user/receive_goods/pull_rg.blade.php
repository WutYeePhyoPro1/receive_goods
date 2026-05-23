@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    <div class="md:w-[80%] pb-16 px-4 pt-4 mx-auto">
        <form id="rg_form" action="" method="POST">
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
                            <label class="block font-medium text-slate-500 mb-0.5">Portal Document No. <span class="text-red-600">*</span></label>
                            <input type="text" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No..." value="{{ $good_receive->document_no }}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-red-600">*</span></label>
                            <input type="text" readonly id="vendor_code" class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" placeholder="VEN-999999" value="{{-- $good_receive->vendor_name --}}">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Vendor Name <span class="text-red-600">*</span></label>
                            <input type="text" readonly id="vendor_name" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Vendor Name" value="{{-- $good_receive->vendor_name --}}">
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
                            <input readonly id="purchasedate" type="date" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label  class="block font-medium text-slate-500 mb-0.5">Branch <span class="text-red-600">*</span></label>
                            <input type="text" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="User Branch" value="{{ $userdata->branch->branch_name }}">
                        </div>

                        <!-- Row 3 -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Note <span class="text-red-600">*</span></label>
                            <input type="text" name="delivery_note" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Delivery Note">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Delivery Date <span class="text-red-600">*</span></label>
                            <input type="date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Ship By <span class="text-red-600">*</span></label>
                            <select class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                                    <option value="">Choose a Transportation</option>
                                    @foreach($transportations as $transportation)
                                        <option value="{{ $transportation->transp_code }}">{{ $transportation->transp_name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <!-- Row 4 (Remark spans 2 columns, Checkboxes group occupies 1) -->
                        <div>
                            <label class="block font-medium text-slate-500 mb-0.5">Remark <span class="text-red-600">*</span></label>
                            <select class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                            <option value="">Choose a Transportation</option>
                                @foreach($receives as $receive)
                                    <option value="{{ $receive->remark_id }}">{{ $receive->remark_type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 self-end">
                            <label class="block font-medium text-slate-500 mb-0.5"> </label>
                            <input type="text" readonly id="remark" class="w-full h-8 px-2 bg-slate-50 border border-slate-300 rounded focus:outline-none focus:border-amber-500 cursor-not-allowed" placeholder="Enter remarks details here...">
                        </div>
                        
                        <!-- Checkbox Controls Alignment -->

                        <div class="md:col-span-3">
                        <div class="grid grid-cols-3 gap-2 pt-4 items-center">
                            <label class="flex items-center gap-1.5 cursor-pointer font-medium text-slate-600">
                                <input type="checkbox" class="w-3.5 h-3.5 accent-amber-500 rounded"> Select All
                            </label>
                            <div class="flex gap-2">
                                <label class="flex items-center gap-1.5 cursor-pointer font-medium text-slate-600">
                                    <input type="checkbox" class="w-3.5 h-3.5 accent-amber-500 rounded"> R008
                                </label>
                                <div>
                                    <input type="text" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 text-center cursor-not-allowed" value="R008-DOC-99" title="R008 Doc. No (Read Only)">
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
                    </div>

                    <!-- TOTAL AMOUNT DISPLAY SUMMARY -->
                    <div class="mt-3 flex justify-end">
                        <div class="bg-slate-50 border border-slate-200 rounded flex items-center divide-x divide-slate-200 overflow-hidden">
                            <span class="px-3 py-1.5 font-bold uppercase text-slate-500 text-[10px] tracking-wider">Total Amount</span>
                            <span id="total_amount" class="px-4 py-1.5 font-extrabold text-sm text-slate-800 bg-amber-50/50">{{-- 1,055.00 --}}</span>
                        </div>
                    </div>

                </div>

                <div class="flex items-center gap-2 p-4">

                    <button class="h-9 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-[12px] font-medium">
                        Cancel
                    </button>

                    <button type="submit" class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-blue-700 text-white text-[12px] font-medium shadow-sm">
                        Save
                    </button>

                </div>
            </div>
        </form>

    </div>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            var token = $("meta[name='__token']").attr('content');
            const recieve_id = $('#receive_id').val();


            $('#po_no').change(function(){
                const getpono = $(this).val();
                
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
                        $('#total_amount').text(document.total_amount);
                        $('#remark').val(document.remark);


                        products.forEach((product,idx) => {
                            let html = `
                                <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <td class="py-1.5 px-3 font-medium text-slate-400">${++idx}</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <input type="checkbox" id="pickup_${product.bar_code}" class="accent-amber-500 rounded">
                                    </td>
                                    <td class="py-1.5 px-3 font-mono font-medium text-slate-700">${product.bar_code}</td>
                                    <td class="py-1.5 px-3"><span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px]">${product.unit}</span></td>
                                    <td class="py-1.5 px-3 text-right font-medium">${product.qty}</td>
                                    <td class="py-1.5 px-3 text-right">
                                        <div id="gr_view_${product.bar_code}" class="w-24  ms-auto">
                                            <span>${product.qty}<span>
                                        </div>

                                        <div id="gr_edit_${product.bar_code}" hidden class="w-24  ms-auto">
                                            <input type="number" name="gr_qty[]" id="gr_qty_${product.bar_code}" class="gr_qty w-20 h-7 px-1.5 text-right border border-slate-300 rounded focus:outline-none focus:border-amber-500" value="${product.qty}">
                                        </div>
                                    </td>
                                    <td class="py-1.5 px-3 text-right text-slate-500">${product.price}</td>
                                    <td id="amount_${product.bar_code}" class="py-1.5 px-3 text-right font-medium text-slate-700">${product.amount}</td>
                                </tr>
                            `;
                            $('#productTable tbody').append(html);

                            $(`#gr_qty_${product.bar_code}`).on('input', function() {

                                var qty = parseInt($(this).val());
                                var poqty = parseInt(product.qty);
                                var price = product.price;
                                var amount = qty * price;
                                console.log(amount);

                                if(qty > poqty){
                                    $(this).val(poqty)
                                    $(`#amount_${product.bar_code}`).html(product.amount);
                                }else{
                                    $(`#amount_${product.bar_code}`).html(amount);
                                }
                            });

                            $(`#pickup_${product.bar_code}`).change(function(){
                                var isChecked = $(this).prop("checked") === true ? true : false;
                                console.log(isChecked);

                                let grView = $(`#gr_view_${product.bar_code}`);
                                let grEdit = $(`#gr_edit_${product.bar_code}`);
                                if(isChecked){
                                    grView.hide();
                                    grEdit.show();
                                }else{
                                    grView.show();
                                    grEdit.hide();
                                }
                            });


                    


                        });
              

                    }
                    
                })

            });


           
        </script>
    @endpush
@endsection
