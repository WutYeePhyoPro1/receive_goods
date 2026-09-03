@extends('layout.layout')

@section('content')

    <div class="rg-readable md:w-[100%] mx-auto px-4 pt-4 pb-10">
        <!-- PAGE CARD -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="px-5 py-2.5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <i class='bx bx-list-ul text-amber-500 text-xl'></i>
                    <h2 class="text-sm font-bold text-slate-700 tracking-wide mb-0">
                        Purchase Orders (PO)
                    </h2>
                </div>

                <!-- <button type="button" id="open_po_print_modal"
                    class="group inline-flex h-9 items-center gap-2 rounded-lg bg-amber-500 px-4 text-[12px] font-bold text-white shadow-sm shadow-amber-200 transition hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-md">
                    <i class='bx bx-printer text-base transition group-hover:scale-110'></i>
                    PO Print
                </button> -->
            </div>

            <!-- SEARCH FILTER -->
            <form action="{{ route('documents.index') }}" method="GET">
                <div class="p-4 border-b border-slate-100 bg-slate-50/40">

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">

                        <!-- Document No -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Document No (PO)
                            </label>

                            <input
                                type="text"
                                name="form_doc_no"
                                class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                placeholder="Search Document..."
                                value="{{ request('form_doc_no') }}"
                            />
                        </div>

                        <!-- From Date -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                From Date
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                value="{{ request('start_date') }}"
                            />
                        </div>

                        <!-- To Date -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                To Date
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                value="{{ request('end_date') }}"
                            >
                        </div>

                        <div>
                        
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Branch
                            </label>
                            <select
                                    name="branch_id"
                                    class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                >
                                    <option value="" selected disabled>Choose User Branch</option>
                                    @foreach($assigned_branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request()->get('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <!-- Branch -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Status
                            </label>
                            <div class="flex gap-2">
                                @php
                                    $statuses = ['Pending RG','PO Partial','Already RG']
                                @endphp
                                <select
                                        name="status"
                                        class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                        focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                        focus:border-amber-500 bg-white"
                                    >
                                        <option value="" selected disabled>Choose Status</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request()->get('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                </select>

                                <button
                                    type="submit"
                                    class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-amber-600
                                        text-white text-[12px] font-semibold shadow-sm
                                        whitespace-nowrap transition"
                                >
                                    Search
                                </button>

                                @if(request()->query())
                                    <a href="{{ url()->current() }}"
                                        style="text-decoration: none;"
                                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-400 bg-slate-500 text-white shadow-sm transition hover:bg-slate-600 hover:text-white"
                                        title="Reset"
                                    >
                                        <i class="bx bx-refresh text-lg"></i>
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>
            </form>

            <!-- TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full text-sm border-collapse">

                    <!-- TABLE HEAD -->
                    <thead class="bg-slate-100 border-b border-slate-200">

                        <tr class="text-[11px] uppercase tracking-wider text-slate-600 whitespace-nowrap">

                            <th class="px-4 py-3 text-left font-bold">No</th>

                            <th class="px-4 py-3 text-left font-bold">
                                Document No.
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Status
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Purchase Date
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Branch
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Vendor Code
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Vendor Name
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Credit(Day)
                            </th>

                             <th class="px-4 py-3 text-left font-bold">
                                Amount
                            </th>

                        </tr>

                    </thead>

                    <!-- TABLE BODY -->
                    <tbody class="divide-y divide-slate-100 text-[13px] text-slate-700">
                        @foreach ($data as $idx=>$item)
                            <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer"  onClick='window.location.href = "{{ route("documents.show",$item->id) }}"' >

                                <td class="px-4 py-3 font-medium text-slate-500">
                                    {{$idx + $data->firstItem()}}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->document_no }}

                                    <button
                                        type="button"
                                        class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                        onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $item->document_no }}')"
                                        title="Copy"
                                    >
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $status = strtolower($item->status ?? 'Default');
                                    @endphp
                                     <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4 {{ $statusClasses[$status] }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->purchasedate }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->branch?->branch_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->vendor_code }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->vendor_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->creditday }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ number_format($item->total_amount,2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
                {{-- $data->links('pagination::tailwind') --}}
                {{ $data->appends(request()->all())->links('vendors.pagination.custom-rg') }}
            </div>

        </div>

    </div>

    {{-- PO Print UI prototype. AJAX search/sync will be connected in the next step. --}}
    <div id="po_print_modal" class="fixed inset-0 z-[1000] hidden items-center justify-center p-3 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="po_print_title">
        <div id="po_print_backdrop" class="absolute inset-0 bg-slate-950/60 backdrop-blur-[2px]"></div>

        <div id="po_print_panel" class="relative flex max-h-[92vh] w-full max-w-6xl scale-95 flex-col overflow-hidden rounded-2xl border border-white/20 bg-white opacity-0 shadow-2xl transition duration-200">
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 px-5 py-4 text-white sm:px-6">
                <div class="pointer-events-none absolute -right-12 -top-16 h-44 w-44 rounded-full bg-amber-400/20 blur-2xl"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-lg shadow-amber-950/20">
                            <i class='bx bx-printer text-2xl'></i>
                        </div>
                        <div>
                            <h2 id="po_print_title" class="mb-0 text-base font-bold tracking-wide sm:text-lg">Find &amp; Print Purchase Order</h2>
                            <p class="mb-0 mt-0.5 text-[11px] text-slate-300 sm:text-xs">Document No ဖြင့် PO ကိုရှာပြီး Print ထုတ်နိုင်ပါသည်</p>
                        </div>
                    </div>
                    <button type="button" data-close-po-modal class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/20 hover:text-white" aria-label="Close">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto bg-slate-50 p-4 sm:p-6">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <label for="po_print_search" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">Document No (PO)</label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <div class="relative flex-1">
                            <i class='bx bx-receipt absolute left-3.5 top-1/2 -translate-y-1/2 text-xl text-slate-400'></i>
                            <input type="text" id="po_print_search" value="POWHMLD260715-0005" autocomplete="off"
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-10 font-mono text-[13px] font-semibold uppercase tracking-wide text-slate-700 outline-none transition placeholder:font-sans placeholder:font-normal placeholder:normal-case placeholder:tracking-normal focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                                placeholder="e.g. POWHMLD260715-0005">
                            <button type="button" id="clear_po_print_search" class="absolute right-2.5 top-1/2 hidden h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700" title="Clear">
                                <i class='bx bx-x text-lg'></i>
                            </button>
                        </div>
                        <button type="button" id="search_po_for_print"
                            class="inline-flex h-11 min-w-32 items-center justify-center gap-2 rounded-xl bg-amber-500 px-5 text-[12px] font-bold text-white shadow-sm shadow-amber-200 transition hover:bg-amber-600 disabled:cursor-not-allowed disabled:opacity-60">
                            <i class='bx bx-search-alt-2 text-lg'></i><span>Search PO</span>
                        </button>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-1 text-[10px] text-slate-400">
                        <span class="inline-flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Local database ကိုအရင်ရှာမည်</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>မတွေ့ပါက Source မှ Sync ပြုလုပ်မည်</span>
                    </div>
                </div>

                <div id="po_print_loading" class="mt-4 hidden rounded-xl border border-blue-100 bg-blue-50 px-4 py-5 text-center">
                    <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600"><i class='bx bx-loader-alt animate-spin text-xl'></i></div>
                    <p class="mb-0 text-[12px] font-semibold text-blue-800">Purchase Order ရှာဖွေနေပါသည်...</p>
                    <p class="mb-0 mt-1 text-[10px] text-blue-500">Document database နှင့် မူလ Source ကို စစ်ဆေးနေပါသည်</p>
                </div>

                <div id="po_print_result" class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3 sm:px-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="mb-0 text-[13px] font-bold text-slate-700">Search Result</h3>
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">1 PO Found</span>
                            </div>
                            <p class="mb-0 mt-1 text-[10px] text-slate-400">Print မထုတ်မီ PO အချက်အလက်များကို စစ်ဆေးပါ</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1.5 text-[10px] font-semibold text-blue-700"><i class='bx bx-cloud-download'></i> Newly Synced</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[920px] border-collapse text-left">
                            <thead class="border-b border-slate-200 bg-slate-100/80">
                                <tr class="whitespace-nowrap text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3">Document Date</th>
                                    <th class="px-4 py-3">Document No.</th>
                                    <th class="px-4 py-3">Branch</th>
                                    <th class="px-4 py-3">Vendor ID</th>
                                    <th class="px-4 py-3">Vendor Name</th>
                                    <th class="px-4 py-3 text-right">Credit (Day)</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                    <th class="sticky right-0 bg-slate-100 px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-[12px] text-slate-700">
                                <tr class="whitespace-nowrap transition hover:bg-amber-50/50">
                                    <td class="px-4 py-4 text-center text-slate-400">1</td>
                                    <td class="px-4 py-4">15/07/2026</td>
                                    <td class="px-4 py-4"><span id="po_print_result_no" class="rounded bg-amber-50 px-2 py-1 font-mono font-bold text-amber-800 ring-1 ring-inset ring-amber-200">POWHMLD260715-0005</span></td>
                                    <td class="px-4 py-4">WH-Mingalardon</td>
                                    <td class="px-4 py-4 font-medium">F-0009</td>
                                    <td class="px-4 py-4 font-medium">Farmer Phoyarzar</td>
                                    <td class="px-4 py-4 text-right">30</td>
                                    <td class="px-4 py-4 text-right font-bold text-slate-800">1,250,000.00</td>
                                    <td class="sticky right-0 bg-white px-4 py-3 text-center group-hover:bg-amber-50">
                                        <button type="button" id="sample_print_po" class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-slate-800 px-3.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-amber-500">
                                            <i class='bx bx-printer text-sm'></i> Print
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-white px-5 py-3 sm:px-6">
                <p class="mb-0 hidden text-[10px] text-slate-400 sm:block"><i class='bx bx-info-circle mr-1'></i>Esc key နှိပ်၍လည်း ပိတ်နိုင်ပါသည်</p>
                <button type="button" data-close-po-modal class="ml-auto h-9 rounded-lg border border-slate-300 bg-white px-4 text-[12px] font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-800">Close</button>
            </div>
        </div>
    </div>
    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                const $modal = $('#po_print_modal');
                const $panel = $('#po_print_panel');
                const $input = $('#po_print_search');
                const $loading = $('#po_print_loading');
                const $result = $('#po_print_result');
                const $searchButton = $('#search_po_for_print');

                function openPoPrintModal() {
                    $modal.removeClass('hidden').addClass('flex');
                    $('body').addClass('overflow-hidden');
                    window.requestAnimationFrame(function () {
                        $panel.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                        $input.trigger('focus').trigger('select');
                    });
                }

                function closePoPrintModal() {
                    $panel.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                    window.setTimeout(function () {
                        $modal.removeClass('flex').addClass('hidden');
                        $('body').removeClass('overflow-hidden');
                    }, 180);
                }

                function toggleClearButton() {
                    $('#clear_po_print_search').toggleClass('hidden', !$input.val()).toggleClass('flex', !!$input.val());
                }

                function showSampleSearchResult() {
                    const documentNo = $input.val().trim().toUpperCase();
                    if (!documentNo) {
                        $input.addClass('border-red-400 ring-4 ring-red-50').trigger('focus');
                        return;
                    }

                    $input.removeClass('border-red-400 ring-4 ring-red-50').val(documentNo);
                    $result.addClass('hidden');
                    $loading.removeClass('hidden');
                    $searchButton.prop('disabled', true).find('span').text('Searching...');

                    window.setTimeout(function () {
                        $('#po_print_result_no').text(documentNo);
                        $loading.addClass('hidden');
                        $result.removeClass('hidden');
                        $searchButton.prop('disabled', false).find('span').text('Search PO');
                    }, 700);
                }

                $('#open_po_print_modal').on('click', openPoPrintModal);
                $('[data-close-po-modal], #po_print_backdrop').on('click', closePoPrintModal);
                $('#search_po_for_print').on('click', showSampleSearchResult);
                $('#clear_po_print_search').on('click', function () { $input.val('').trigger('focus'); toggleClearButton(); });
                $input.on('input', toggleClearButton).on('keydown', function (event) {
                    if (event.key === 'Enter') showSampleSearchResult();
                });
                $(document).on('keydown', function (event) {
                    if (event.key === 'Escape' && $modal.hasClass('flex')) closePoPrintModal();
                });
                $('#sample_print_po').on('click', function () {
                    alert('UI Sample only — Print Page route ကို နောက်တစ်ဆင့်တွင် ချိတ်ဆက်ပါမည်။');
                });

                toggleClearButton();
            });
        </script>
    @endpush
@endsection



<!-- php artisan vendor:publish --tag=laravel-pagination -->

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.css') }}">
@endsection
