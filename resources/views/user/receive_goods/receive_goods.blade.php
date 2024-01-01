@extends('layout.layout')

@section('content')
    {{-- <span>this is received_good</span> --}}
    <div class="flex justify-between">
        <div class="flex">
            <input type="text" class="w-80 min-h-12 shadow-lg border-slate-400 border rounded-xl pl-5 focus:border-b-4 focus:outline-none">
            <button class="h-12 bg-amber-400 text-white px-8 ml-8 rounded-lg hover:bg-amber-500">Search</button>
        </div>
        <button class="h-12 bg-sky-300 hover:bg-sky-600 text-white px-16 tracking-wider font-semibold rounded-lg">Confirm</button>
        <span class="mr-20 text-5xl font-semibold tracking-wider select-none text-amber-400" id="time_count">{{ $pass }}</span>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div class="mt-5 border border-slate-400 rounded-md" style="min-height: 85vh;max-height:85vh;width:100%;overflow-x:hidden;overflow-y:auto">
            <div class="border border-b-slate-400 h-10 bg-sky-50">
                <span class="font-semibold leading-9 ml-3">
                    List Of Products
                </span>
            </div>
            <input type="hidden" id="started_time" value="{{ $data->start_date.' '.$data->start_time }}">
                <table class="w-full">
                    <thead>
                        <tr class="h-10">
                            <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                            <th class="border border-slate-400 border-t-0">Document No</th>
                            <th class="border border-slate-400 border-t-0">Box Barcode</th>
                            <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                            <th class="border border-slate-400 border-t-0">Quantity(Box)</th>
                            <th class="border border-slate-400 border-t-0">Scanned(BOX)</th>
                            <th class="border border-slate-400 border-t-0 border-r-0">Remaining</th>
                        </tr>
                    </thead>
                    <div class="main_tb_body">
                        <tbody class="main_body">
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">1</td>
                                <td class="ps-2 border border-slate-400 border-t-0 ">POI123412-001</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">12324464561</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">Opple Light</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">7</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">4</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600 border-r-0">3</td>
                            </tr>
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-rose-100 text-rose-600">123212364561</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-rose-100 text-rose-600">Opple heavy</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-rose-100 text-rose-600">10</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-rose-100 text-rose-600">0</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-rose-100 text-rose-600 border-r-0">10</td>
                            </tr>
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">1232409194561</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">Opple feathur</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">5</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">5</td>
                                <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600 border-r-0">0</td>
                            </tr>
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">2</td>
                                <td class="ps-2 border border-slate-400 border-t-0">POI123412-002</td>
                                <td class="ps-2 border border-slate-400 border-t-0">12324464561</td>
                                <td class="ps-2 border border-slate-400 border-t-0">Opple Light</td>
                                <td class="ps-2 border border-slate-400 border-t-0">7</td>
                                <td class="ps-2 border border-slate-400 border-t-0">4</td>
                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0">3</td>
                            </tr>
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0">123212364561</td>
                                <td class="ps-2 border border-slate-400 border-t-0">Opple heavy</td>
                                <td class="ps-2 border border-slate-400 border-t-0">10</td>
                                <td class="ps-2 border border-slate-400 border-t-0">4</td>
                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0">6</td>
                            </tr>
                            <tr class="h-10">
                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0"></td>
                                <td class="ps-2 border border-slate-400 border-t-0">1232409194561</td>
                                <td class="ps-2 border border-slate-400 border-t-0">Opple feathur</td>
                                <td class="ps-2 border border-slate-400 border-t-0">5</td>
                                <td class="ps-2 border border-slate-400 border-t-0">3</td>
                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0">2</td>
                            </tr>
                        </tbody>
                    </div>
                </table>

        </div>
        <div class="mt-5 grid grid-rows-2 gap-2" style="max-height: 85vh;width:100%; overflow:hidden">
            <div class="border border-slate-400 rounded-md" style="max-height: 42.5vh;width:100%">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products
                    </span>
                </div>
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity(Box)</th>
                            </tr>
                        </thead>
                        <div class="scan_tb_body">
                            <tbody class="scan_body">
                                <tr class="h-10">
                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0">1</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0">POI123412-001</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">1232409194561</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600">Opple feathur</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-emerald-100 text-emerald-600 border-r-0">5</td>
                                </tr>
                                <tr class="h-10">
                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                    <td class="ps-2 border border-slate-400 border-t-0"></td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">12324464561</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600">Opple Light</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 bg-amber-100 text-amber-600 border-r-0">4</td>
                                </tr>
                            </tbody>
                        </div>
                    </table>
            </div>
            <div class="border border-slate-400 rounded-md" style="max-height: 42.5vh;width:100%">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products (excess)
                    </span>
                </div>
                <div class="">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity(Box)</th>
                            </tr>
                        </thead>
                        <div class="excess_tb_body">
                            <tbody class="exceed_body">
                                <tr class="h-10">
                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0">1</td>
                                    <td class="ps-2 border border-slate-400 border-t-0 ">POI123412-001</td>
                                    <td class="ps-2 border border-slate-400 border-t-0">12324464561</td>
                                    <td class="ps-2 border border-slate-400 border-t-0">Opple Light</td>

                                    <td class="ps-2 border border-slate-400 border-t-0 border-r-0">3</td>
                                </tr>
                            </tbody>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
 {{-- Product Modal --}}
 <div class="hidden" id="showProductModal">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                <div
                    class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                    <div class="flex px-4 py-2 justify-between items-center bg-cyan-500 ">
                        <h3 class="font-bold text-gray-50 ml-5 sm:flex">All Products &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold"
                            onclick="$('#showProductModal').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @can('send-to-adjust-stock')
                        <div id="completed" class="hidden">
                            <div class="flex sm:items-center ml-3">
                                <div class="p-4">
                                    <textarea class="rounded-md border border-gray-200 shadow" name="bracc_remark" id="bracc_remark" cols="20"
                                        rows="3"></textarea>
                                </div>
                                <div class="my-3 sm:flex sm:w-50">
                                    <button type="button" id="sendBtn"
                                        class="inline-flex items-center justify-center rounded border border-transparent bg-teal-600 px-3 py-2 text-sm font-bold text-white shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto mr-2"
                                        value="4">
                                        Confirm Send Adjust
                                    </button>

                                    <button type="button"
                                        class="inline-flex items-center justify-center rounded border border-transparent bg-yellow-600 px-3 py-2 text-sm font-bold text-white shadow-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:w-auto mr-2"
                                        onclick="closeModal('#showProductModal')">
                                        Cancel
                                    </button>

                                </div>
                            </div>
                        </div>
                    @endcan

                </div>
                <div class="card-body pt-4">
                    <table class="min-w-full d-table border-spacing-y-2 mx-auto border-separate">
                        <thead>
                            <tr
                                class="shadow border-l-2 rounded-l-md border-r-2 rounded-r-md mt-1 border-gray-500 py-2 text-cyan-600 text-center">
                                <th scope="col" colspan="4"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-xs font-semibold ">
                                    Product Detail</th>
                                <th scope="col" colspan="4"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-xs font-semibold">
                                    Difference Value</th>
                                <th scope="col" colspan="3"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-xs font-semibold">
                                    Selected Difference </th>
                                <th scope="col"
                                    class=" whitespace-nowrap top-0 z-10 py-2 px-4 text-xs font-semibold">
                                    Remark by Branch Account</th>
                            </tr>
                            <tr class="bg-cyan-600 ">
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    #</th>
                                {{-- <th scope="col" class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">Document No</th> --}}
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Product Code</th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Product Name</th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    System Qty</th>


                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    User </th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Br Mng </th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Op Analysis </th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Br Acc </th>

                                <th scope="col"
                                    class=" whitespace-nowrap top-0 z-10 py-2 px-4 text-right text-xs font-semibold text-gray-700">
                                    Diff Qty</th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-right text-xs font-semibold text-gray-700">
                                    Diff Amt </th>
                                <th scope="col"
                                    class="whitespace-nowrap top-0 z-10 py-2 px-4 text-left text-xs font-semibold text-gray-700">
                                    Status </th>

                                <th scope="col"
                                    class=" whitespace-nowrap top-0 z-10 py-2 pl-4 text-left text-xs font-semibold text-gray-700">
                                    Remark</th>
                            </tr>

                        </thead>
                        <tbody id="product_tbody">


                        </tbody>
                    </table>
                    <div class="flex justify-center text-xs mt-2">
                        Total <span class="text-red-600 px-2" id="total_products"></span>Record
                        {{-- <input type="hidden" id="main_doc_id" > --}}
                        {{-- <input type="hidden" id="total_diff_amt" > --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Modal --}}
    @push('js')
        <script  type="module">
            $(document).ready(function(e){
                // $('#showProductModal').show();

                // console.log(Math.floor(2-1));

                function time_count(){
                    $time = new Date($('#started_time').val()).getTime();
                    $now  = new Date().getTime();
                    $diff = Math.floor($now - $time);
                    // $hour =
                }
            })
        </script>
    @endpush
@endsection
