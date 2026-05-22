@extends('layout.layout')


@section('content')
      <!-- MAIN CONTENT CONTAINER -->
    <div class="md:w-[80%] pb-16 px-4 pt-4 mx-auto">
        <!-- UNIFIED CARD CONTAINER -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 text-slate-800 text-xs">
            
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
                        <label class="block font-medium text-slate-500 mb-0.5">Portal Document No.</label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Doc No...">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Vendor Code <span class="text-[10px] text-slate-400 font-normal">(Read Only)</span></label>
                        <input type="text" readonly class="w-full h-8 px-2 bg-slate-50 border border-slate-200 rounded text-slate-500 cursor-not-allowed" value="VND-9831">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Vendor Name</label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Vendor Name">
                    </div>

                    <!-- Row 2 -->
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">PO No</label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="PO Number">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">PO Date</label>
                        <input type="date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Branch</label>
                        <select class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                            <option>Branch Alpha</option>
                            <option>Branch Beta</option>
                        </select>
                    </div>

                    <!-- Row 3 -->
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Delivery Note</label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Delivery Note">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Delivery Date</label>
                        <input type="date" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Ship By</label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Carrier / Method">
                    </div>

                    <!-- Row 4 (Remark spans 2 columns, Checkboxes group occupies 1) -->
                    <div>
                        <label class="block font-medium text-slate-500 mb-0.5">Remark</label>
                        <select class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500 bg-white">
                            <option>Product Damage</option>
                            <option>Product Shortage</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 ">
                        <label class="block font-medium text-slate-500 mb-0.5"> </label>
                        <input type="text" class="w-full h-8 px-2 border border-slate-300 rounded focus:outline-none focus:border-amber-500" placeholder="Enter remarks details here...">
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
                    <table class="w-full text-left border-collapse">
                        <!-- Frozen Table Header -->
                        <thead class="sticky top-0 bg-slate-100 z-10 border-b border-slate-200 shadow-sm">
                            <tr class="text-slate-600 font-semibold uppercase text-[11px] tracking-wider whitespace-nowrap">
                                <th class="py-2 px-3 w-auto">No</th>
                                <th class="py-2 px-3 w-auto text-center">Select</th>
                                <th class="py-2 px-3 w-auto">Product Code</th>
                                <th class="py-2 px-3 w-auto">Unit</th>
                                <th class="py-2 px-3 w-auto text-right">PO Qty</th>
                                <th class="py-2 px-3 w-auto text-right">Physical Qty</th>
                                <th class="py-2 px-3 w-auto text-right">Price</th>
                                <th class="py-2 px-3 w-auto text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                                <!-- Row 1 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                            <!-- Row 2 Template -->
                            <tr class="hover:bg-slate-50 transition-colors whitespace-nowrap">
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
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- TOTAL AMOUNT DISPLAY SUMMARY -->
                <div class="mt-3 flex justify-end">
                    <div class="bg-slate-50 border border-slate-200 rounded flex items-center divide-x divide-slate-200 overflow-hidden">
                        <span class="px-3 py-1.5 font-bold uppercase text-slate-500 text-[10px] tracking-wider">Total Amount</span>
                        <span class="px-4 py-1.5 font-extrabold text-sm text-slate-800 bg-amber-50/50">1,055.00</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('js')
        <script>
        
        </script>
    @endpush
@endsection
