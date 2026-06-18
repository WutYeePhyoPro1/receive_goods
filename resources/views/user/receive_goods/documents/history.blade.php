@extends('layout.layout')

@section('content')
    <div class="mx-auto px-4 pt-4 pb-16 md:w-[90%] xl:w-[82%]">
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white text-xs text-slate-800 shadow-sm">
            <div class="border-b border-slate-100 p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="mb-0 flex items-center gap-2 text-sm font-bold text-slate-700">
                        <i class='bx bx-history text-base text-amber-500'></i>
                        PO History
                    </h2>

                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                            class="h-8 rounded bg-blue-500 px-3 text-[12px] font-medium text-white shadow-sm"
                            onclick="window.location.href='{{ route('documents.show', $po_document->id) }}'">
                            Purchase Order
                        </button>
                        <!-- <button type="button"
                            class="h-8 rounded bg-amber-500 px-3 text-[12px] font-medium text-white shadow-sm">
                            History
                        </button> -->
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">PO No</label>
                        <div class="h-8 truncate rounded border border-slate-300 bg-slate-50 px-2 leading-8 text-slate-700">
                            {{ $po_document->document_no }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">PO Date</label>
                        <div class="h-8 truncate rounded border border-slate-300 bg-slate-50 px-2 leading-8 text-slate-700">
                            {{ $po_document->purchasedate }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">Vendor</label>
                        <div class="h-8 truncate rounded border border-slate-300 bg-slate-50 px-2 leading-8 text-slate-700">
                            {{ $po_document?->vendor?->vendor_name ?? $po_document->vendor_name }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">Branch</label>
                        <div class="h-8 truncate rounded border border-slate-300 bg-slate-50 px-2 leading-8 text-slate-700">
                            {{ $po_document?->branch?->branch_name }}
                        </div>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('documents.history_po', $po_document->id) }}">
            <div class="border-b border-dashed border-slate-200 p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <!-- <div>
                        <label class="mb-0.5 block font-medium text-slate-500">Search By</label>
                        <select class="h-8 w-full rounded border border-slate-300 bg-white px-2 focus:border-amber-500 focus:outline-none">
                            <option>Product Code</option>
                            <option>RG No.</option>
                            <option>Product Name</option>
                        </select>
                    </div> -->

                    <div>
                        <!-- <label class="mb-0.5 block font-medium text-slate-500">Search</label> -->
                        <div class="flex gap-2">
                            <div class="w-full relative">
                                <input type="text"
                                    name="search_data"
                                    class="h-8 w-full rounded border border-slate-300 bg-white px-2 pr-9 focus:border-amber-500 focus:outline-none"
                                    placeholder="Search PO history..." value="{{ request('search_data') ?? '' }}">
                                <i class='bx bx-search absolute right-2 top-1/2 -translate-y-1/2 text-lg text-slate-500'></i>
                            </div>

                            <button type="submit"
                                class="h-8 rounded bg-amber-500 px-4 text-[12px] font-medium text-white shadow-sm hover:bg-amber-600">
                                Search
                            </button>
                            @if(request('search_data'))
                                <a
                                    href="{{ route('documents.history_po', $po_document->id) }}"
                                    class="inline-flex h-8 items-center rounded bg-slate-500 px-1 text-[12px] font-medium text-white hover:bg-slate-600 no-underline"
                                >
                                    <i class="bx bx-refresh text-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </form>

            <div class="p-4">
                <div class="mb-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="mb-0 flex items-center gap-2 text-sm font-bold text-slate-700">
                        <i class='bx bx-list-ul text-base text-amber-500'></i>
                        Approved PO History
                    </h3>

                    <!-- <div class="flex flex-wrap gap-2 text-[11px]">
                        <span class="rounded border border-slate-200 bg-slate-50 px-2 py-1 text-slate-600">PO Qty: <b>{{-- number_format($totalQty) --}}</b></span>
                        <span class="rounded border border-slate-200 bg-slate-50 px-2 py-1 text-slate-600">RG Qty: <b>{{-- number_format($totalRgQty) --}}</b></span>
                        <span class="rounded border border-amber-200 bg-amber-50 px-2 py-1 text-slate-700">Net Amount: <b>{{-- number_format($totalAmount, 2) --}}</b></span>
                    </div> -->
                    @php
                        $rgDocsCount = count($po_histories->pluck('receive_no')->unique()->values());
                    @endphp
                    <div class="flex flex-wrap gap-2 text-[11px]">
                        <span class="rounded border border-slate-200 bg-slate-50 px-2 py-1 text-slate-600">RG Docs: <b>{{ number_format($rgDocsCount) }}</b></span>
                    </div>
                </div>

                <div class="max-h-[440px] overflow-auto rounded border border-slate-200 shadow-inner">
                    <table class="min-w-[980px] w-full border-collapse text-left">
                        <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                            <tr class="whitespace-nowrap">
                                <th class="w-10 px-3 py-2"></th>
                                <th class="px-3 py-2">RG No.</th>
                                <th class="px-3 py-2">Product Code</th>
                                <th class="px-3 py-2">Product Name</th>
                                <th class="px-3 py-2">Unit</th>
                                <th class="px-3 py-2 text-right">Qty.</th>
                                <th class="px-3 py-2 text-right">Qty.RG</th>
                                <th class="px-3 py-2 text-right">Price</th>
                                <th class="px-3 py-2 text-right">Net Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white text-[12px] text-slate-700">
                            <!-- <tbody class="bg-white text-[12px] text-slate-700">
                                <tr class="border-y border-slate-200 bg-slate-100/80 text-slate-700">
                                    <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                    <td colspan="8" class="px-3 py-1.5 font-bold">Product Code: 2000000653006</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGDCMLD3260603-0002</td><td class="px-3 py-1.5">2000000653006</td><td class="px-3 py-1.5">KZW Candlestick Shwe (Kyar)</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">40.00</td><td class="px-3 py-1.5 text-right">34.00</td><td class="px-3 py-1.5 text-right">4,656.00</td><td class="px-3 py-1.5 text-right">158,304.00</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGWHMLD260601-0001</td><td class="px-3 py-1.5">2000000653006</td><td class="px-3 py-1.5">KZW Candlestick Shwe (Kyar)</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">48.00</td><td class="px-3 py-1.5 text-right">8.00</td><td class="px-3 py-1.5 text-right">4,656.00</td><td class="px-3 py-1.5 text-right">37,248.00</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGWHMLD260603-0003</td><td class="px-3 py-1.5">2000000653006</td><td class="px-3 py-1.5">KZW Candlestick Shwe (Kyar)</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">6.00</td><td class="px-3 py-1.5 text-right">3.00</td><td class="px-3 py-1.5 text-right">4,656.00</td><td class="px-3 py-1.5 text-right">13,968.00</td>
                                </tr>
                                <tr class="border-b border-slate-200 bg-slate-50 font-semibold">
                                    <td colspan="5"></td><td class="px-3 py-1.5 text-right">48</td><td class="px-3 py-1.5 text-right">45</td><td></td><td class="px-3 py-1.5 text-right">209520</td>
                                </tr>

                                <tr class="border-y border-slate-200 bg-slate-100/80 text-slate-700">
                                    <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                    <td colspan="8" class="px-3 py-1.5 font-bold">Product Code: 2000000635194</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGWHMLD260603-0004</td><td class="px-3 py-1.5">2000000635194</td><td class="px-3 py-1.5">KZW Ceramic Tay Taw White</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">6.00</td><td class="px-3 py-1.5 text-right">4.00</td><td class="px-3 py-1.5 text-right">6,693.00</td><td class="px-3 py-1.5 text-right">26,772.00</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGDCMLD3260603-0001</td><td class="px-3 py-1.5">2000000635194</td><td class="px-3 py-1.5">KZW Ceramic Tay Taw White</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">48.00</td><td class="px-3 py-1.5 text-right">42.00</td><td class="px-3 py-1.5 text-right">6,693.00</td><td class="px-3 py-1.5 text-right">281,106.00</td>
                                </tr>
                                <tr class="border-b border-slate-200 bg-slate-50 font-semibold">
                                    <td colspan="5"></td><td class="px-3 py-1.5 text-right">48</td><td class="px-3 py-1.5 text-right">46</td><td></td><td class="px-3 py-1.5 text-right">307878</td>
                                </tr>

                                <tr class="border-y border-slate-200 bg-slate-100/80 text-slate-700">
                                    <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                    <td colspan="8" class="px-3 py-1.5 font-bold">Product Code: 2000000215204</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGWHMLD260603-0001</td><td class="px-3 py-1.5">2000000215204</td><td class="px-3 py-1.5">KZW Steel Vaccum-flask No-9...</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">12.00</td><td class="px-3 py-1.5 text-right">6.00</td><td class="px-3 py-1.5 text-right">35,890.00</td><td class="px-3 py-1.5 text-right">215,340.00</td>
                                </tr>
                                <tr class="border-b border-slate-200 bg-slate-50 font-semibold">
                                    <td colspan="5"></td><td class="px-3 py-1.5 text-right">12</td><td class="px-3 py-1.5 text-right">6</td><td></td><td class="px-3 py-1.5 text-right">215340</td>
                                </tr>

                                <tr class="border-y border-slate-200 bg-slate-100/80 text-slate-700">
                                    <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                    <td colspan="8" class="px-3 py-1.5 font-bold">Product Code: 2000000215198</td>
                                </tr>
                                <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                    <td></td><td class="px-3 py-1.5">RGWHMLD260603-0001</td><td class="px-3 py-1.5">2000000215198</td><td class="px-3 py-1.5">KZW Steel Vaccum-flask No-9...</td><td class="px-3 py-1.5">PC</td><td class="px-3 py-1.5 text-right">12.00</td><td class="px-3 py-1.5 text-right">6.00</td><td class="px-3 py-1.5 text-right">31,040.00</td><td class="px-3 py-1.5 text-right">186,240.00</td>
                                </tr>
                            </tbody> -->

                            <tbody>
                                @php    
                                    $grandTotal = $po_histories->sum('approve_amount')
                                @endphp 

                                @foreach ($groupedHistories as $productCode => $histories)
                                    @php
                                        $first = $histories->first();
                                        $maxApproveQty = $histories->max(fn ($item) => (float) $item->approve_quantity);

                                        $totalApproveQty = $histories->sum(fn ($item) => (float) $item->approve_quantity);
                                        $totalReceiveQty = $histories->sum(fn ($item) => (float) $item->receive_quantity);
                                        $totalAmount = $histories->sum(fn ($item) =>
                                            (float) $item->receive_quantity * (float) $item->approve_price
                                        );
                                    @endphp
                                    <tr class="border-y border-slate-200 bg-slate-100/80 bg-amber-100/80s text-slate-700">
                                        <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                        <td colspan="8" class="px-3 py-1.5 font-bold">Product Code: {{ $productCode }}</td>
                                    </tr>
                                    @foreach ($histories as $item)
                                        <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                            <td></td>
                                            <td class="px-3 py-1.5">{{ $item->receive_no }}</td>
                                            <td class="px-3 py-1.5">{{ $item->product_code }}</td>
                                            <td class="px-3 py-1.5">{{ $item->product_name }}</td>
                                            <td class="px-3 py-1.5">PC</td>
                                            <td class="px-3 py-1.5 text-right">{{ number_format((float) $item->approve_quantity, 2) }}</td>
                                            <td class="px-3 py-1.5 text-right">{{ number_format((float) $item->receive_quantity, 2) }}</td>
                                            <td class="px-3 py-1.5 text-right">
                                                <!-- {{-- number_format((float) $item->approve_price, 2) --}} -->
                                                {{ number_format((float) $item->approve_price, 2) }}
                                            </td>
                                            <td class="px-3 py-1.5 text-right">
                                                <!-- {{-- number_format((float) $item->receive_quantity * (float) $item->approve_price, 2) --}} -->
                                                {{ number_format((float) $item->approve_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-b border-slate-200 bg-slate-50 font-semibold">
                                        <td colspan="5" class="px-3 py-1.5 text-right">Subtotal</td>
                                        <td class="px-3 py-1.5 text-right">
                                            <!-- {{-- number_format($totalApproveQty, 2) --}} -->
                                            {{ number_format($maxApproveQty, 2) }}
                                        </td>
                                        <td class="px-3 py-1.5 text-right">{{ number_format($totalReceiveQty, 2) }}</td>
                                        <td></td>
                                        <td class="px-3 py-1.5 text-right">{{ number_format($totalAmount, 2) }}</td>
                                    </tr>
                                    
         

                                @endforeach
                            
                            </tbody>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 flex justify-end">
                    <div class="rounded border border-slate-200 bg-slate-50 px-3 py-2 text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Grand Total</span>
                        <span class="ml-3 text-sm font-extrabold text-slate-800">{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                    <button type="button"
                        class="h-9 rounded-lg border border-slate-300 bg-white px-4 text-[12px] font-medium text-slate-700 hover:bg-slate-100"
                        onclick="window.location.href='{{ route('documents.show', $po_document->id) }}'">
                        Back
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
