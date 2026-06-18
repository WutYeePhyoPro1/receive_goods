@extends('layout.layout')

@php
    $historyGroups = [
        [
            'product_code' => '2000000653006',
            'product_name' => 'KZW Candlestick Shwe (Kyar)',
            'unit' => 'PC',
            'rows' => [
                ['rg_no' => 'RGDCMLD3260603-0002', 'qty' => 40.00, 'qty_rg' => 34.00, 'price' => 4656.00, 'net_amount' => 158304.00],
                ['rg_no' => 'RGWHMLD260601-0001', 'qty' => 48.00, 'qty_rg' => 8.00, 'price' => 4656.00, 'net_amount' => 37248.00],
                ['rg_no' => 'RGWHMLD260603-0003', 'qty' => 6.00, 'qty_rg' => 3.00, 'price' => 4656.00, 'net_amount' => 13968.00],
            ],
        ],
        [
            'product_code' => '2000000635194',
            'product_name' => 'KZW Ceramic Tay Taw White',
            'unit' => 'PC',
            'rows' => [
                ['rg_no' => 'RGWHMLD260603-0004', 'qty' => 6.00, 'qty_rg' => 4.00, 'price' => 6693.00, 'net_amount' => 26772.00],
                ['rg_no' => 'RGDCMLD3260603-0001', 'qty' => 48.00, 'qty_rg' => 42.00, 'price' => 6693.00, 'net_amount' => 281106.00],
            ],
        ],
        [
            'product_code' => '2000000215204',
            'product_name' => 'KZW Steel Vaccum-flask No-9',
            'unit' => 'PC',
            'rows' => [
                ['rg_no' => 'RGWHMLD260603-0001', 'qty' => 12.00, 'qty_rg' => 6.00, 'price' => 35890.00, 'net_amount' => 215340.00],
            ],
        ],
        [
            'product_code' => '2000000215198',
            'product_name' => 'KZW Steel Vaccum-flask No-9',
            'unit' => 'PC',
            'rows' => [
                ['rg_no' => 'RGWHMLD260603-0001', 'qty' => 12.00, 'qty_rg' => 6.00, 'price' => 31040.00, 'net_amount' => 186240.00],
            ],
        ],
    ];

    $totalQty = collect($historyGroups)->sum(fn ($group) => collect($group['rows'])->sum('qty'));
    $totalRgQty = collect($historyGroups)->sum(fn ($group) => collect($group['rows'])->sum('qty_rg'));
    $totalAmount = collect($historyGroups)->sum(fn ($group) => collect($group['rows'])->sum('net_amount'));
@endphp

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

            <div class="border-b border-dashed border-slate-200 p-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-[220px_minmax(0,1fr)_auto] md:items-end">
                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">Search By</label>
                        <select class="h-8 w-full rounded border border-slate-300 bg-white px-2 focus:border-amber-500 focus:outline-none">
                            <option>Product Code</option>
                            <option>RG No.</option>
                            <option>Product Name</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-0.5 block font-medium text-slate-500">Search</label>
                        <div class="relative">
                            <input type="text"
                                class="h-8 w-full rounded border border-slate-300 bg-white px-2 pr-9 focus:border-amber-500 focus:outline-none"
                                placeholder="Search PO history..." value="">
                            <i class='bx bx-search absolute right-2 top-1/2 -translate-y-1/2 text-lg text-slate-500'></i>
                        </div>
                    </div>

                    <button type="button"
                        class="h-8 rounded bg-amber-500 px-4 text-[12px] font-medium text-white shadow-sm hover:bg-amber-600">
                        Search
                    </button>
                </div>
            </div>

            <div class="p-4">
                <div class="mb-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="mb-0 flex items-center gap-2 text-sm font-bold text-slate-700">
                        <i class='bx bx-list-ul text-base text-amber-500'></i>
                        Approved PO History
                    </h3>

                    <div class="flex flex-wrap gap-2 text-[11px]">
                        <span class="rounded border border-slate-200 bg-slate-50 px-2 py-1 text-slate-600">PO Qty: <b>{{ number_format($totalQty) }}</b></span>
                        <span class="rounded border border-slate-200 bg-slate-50 px-2 py-1 text-slate-600">RG Qty: <b>{{ number_format($totalRgQty) }}</b></span>
                        <span class="rounded border border-amber-200 bg-amber-50 px-2 py-1 text-slate-700">Net Amount: <b>{{ number_format($totalAmount, 2) }}</b></span>
                    </div>
                </div>

                <div class="max-h-[440px] overflow-auto rounded border border-slate-200 shadow-inner">
                    <table class="min-w-[980px] w-full border-collapse text-left">
                        <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-100 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
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
                            @foreach ($historyGroups as $group)
                                @php
                                    $groupQty = collect($group['rows'])->sum('qty');
                                    $groupRgQty = collect($group['rows'])->sum('qty_rg');
                                    $groupAmount = collect($group['rows'])->sum('net_amount');
                                @endphp
                                <tr class="border-y border-slate-200 bg-slate-100/80 text-slate-700">
                                    <td class="px-3 py-1.5 text-center"><i class='bx bx-chevron-down text-base'></i></td>
                                    <td colspan="8" class="px-3 py-1.5 font-bold">
                                        Product Code: {{ $group['product_code'] }}
                                    </td>
                                </tr>

                                @foreach ($group['rows'] as $row)
                                    <tr class="whitespace-nowrap border-b border-slate-100 hover:bg-amber-50/40">
                                        <td class="px-3 py-1.5"></td>
                                        <td class="px-3 py-1.5 font-medium">{{ $row['rg_no'] }}</td>
                                        <td class="px-3 py-1.5 font-mono">{{ $group['product_code'] }}</td>
                                        <td class="px-3 py-1.5">{{ $group['product_name'] }}</td>
                                        <td class="px-3 py-1.5"><span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] text-slate-600">{{ $group['unit'] }}</span></td>
                                        <td class="px-3 py-1.5 text-right">{{ number_format($row['qty'], 2) }}</td>
                                        <td class="px-3 py-1.5 text-right">{{ number_format($row['qty_rg'], 2) }}</td>
                                        <td class="px-3 py-1.5 text-right">{{ number_format($row['price'], 2) }}</td>
                                        <td class="px-3 py-1.5 text-right font-medium">{{ number_format($row['net_amount'], 2) }}</td>
                                    </tr>
                                @endforeach

                                <tr class="border-b border-slate-200 bg-slate-50 text-[12px] font-semibold text-slate-700">
                                    <td colspan="5" class="px-3 py-1.5 text-right">Subtotal</td>
                                    <td class="px-3 py-1.5 text-right">{{ number_format($groupQty) }}</td>
                                    <td class="px-3 py-1.5 text-right">{{ number_format($groupRgQty) }}</td>
                                    <td class="px-3 py-1.5"></td>
                                    <td class="px-3 py-1.5 text-right">{{ number_format($groupAmount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                    <button type="button"
                        class="h-9 rounded-lg border border-slate-300 bg-white px-4 text-[12px] font-medium text-slate-700 hover:bg-slate-100"
                        onclick="window.location.href='{{ route('documents.show', $po_document->id) }}'">
                        Back
                    </button>

                    <div class="rounded border border-slate-200 bg-slate-50 px-3 py-2 text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Grand Total</span>
                        <span class="ml-3 text-sm font-extrabold text-slate-800">{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
