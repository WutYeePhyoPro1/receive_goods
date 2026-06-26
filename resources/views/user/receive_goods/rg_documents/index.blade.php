@extends('layout.layout')

@section('content')

    <div class="md:w-[100%] mx-auto px-4 pt-4 pb-10">
        <!-- PAGE CARD -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="px-5 py-1 border-b border-slate-100 flex items-center justify-center">
                <div class="flex items-center gap-2">
                    <i class='bx bx-list-ul text-amber-500 text-xl'></i>
                    <h2 class="text-sm font-bold text-slate-700 tracking-wide mb-0">
                        Receive Goods Documents
                    </h2>
                </div>
            </div>

            <!-- SEARCH FILTER -->
            <form action="{{ route('rg_documents') }}" method="GET">
                <div class="p-4 border-b border-slate-100 bg-slate-50/40">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">

                        <!-- Document No -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Document No (RG | PO)
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

                        <!-- Branch -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Branch
                            </label>

                            <div class="flex gap-2">

                                <select
                                    name="branch_id"
                                    class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                >
                                    <option value="" selected disabled>Choose User Branch</option>
                                </select>

                                <!-- <input
                                    type="text"
                                    readonly
                                    value="User Branch"
                                    class="w-full h-9 px-3 bg-slate-100 border border-slate-300
                                        rounded-lg text-[13px] text-slate-500 cursor-not-allowed"
                                > -->

                                <button
                                    type="submit"
                                    class="h-9 px-4 rounded-lg bg-amber-500 hover:bg-amber-600
                                        text-white text-[12px] font-semibold shadow-sm
                                        whitespace-nowrap transition"
                                >
                                    Search
                                </button>

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

                            <th class="px-4 py-3 text-left font-bold">Scan Document No.</th>

                            <th class="px-4 py-3 text-left font-bold">
                                RG Document No
                            </th>

                            <!-- <th class="px-4 py-3 text-left font-bold">
                                Status
                            </th> -->

                            <th class="px-4 py-3 text-left font-bold">
                                PO Doc No
                            </th>


                            <th class="px-4 py-3 text-left font-bold">
                                Document Date
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Vendor Name
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                R008
                            </th>
 
                            <th class="px-4 py-3 text-left font-bold">
                                R008 Doc No
                            </th>

                        </tr>

                    </thead>

                    <!-- TABLE BODY -->
                    <tbody class="divide-y divide-slate-100 text-[13px] text-slate-700">

                        <!-- <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer">

                            <td class="px-4 py-3 font-medium text-slate-500">
                                1
                            </td>

                            <td class="px-4 py-3 font-semibold text-blue-700">
                                RGMM250527-0001
                            </td>

                            <td class="px-4 py-3">
                                POHTY1260318-0001
                            </td>

                            <td class="px-4 py-3">
                                INV-2026052701
                            </td>

                            <td class="px-4 py-3">
                                2026-05-27
                            </td>

                            <td class="px-4 py-3">
                                ABC Trading Co., Ltd
                            </td>

                        </tr>

                        <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer">

                            <td class="px-4 py-3 font-medium text-slate-500">
                                2
                            </td>

                            <td class="px-4 py-3 font-semibold text-blue-700">
                                RGMM250527-0002
                            </td>

                            <td class="px-4 py-3">
                                POHTY1260318-0002
                            </td>

                            <td class="px-4 py-3">
                                INV-2026052702
                            </td>

                            <td class="px-4 py-3">
                                2026-05-27
                            </td>

                            <td class="px-4 py-3">
                                Myanmar Distribution Group
                            </td>

                        </tr> -->
                        @php 
                            
                        @endphp
                        @foreach ($data as $idx=>$item)
                            <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer"  onClick='window.location.href = "{{ route("detail_rg",$item->id) }}"' >

                                <td class="px-4 py-3 font-medium text-slate-500">
                                    {{$idx + $data->firstItem()}}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->good_receive->document_no }}
                                </td>

                                <td class="px-4 py-3 font-semibold text-blue-700">
                                    {{ $item->receive_good_files->first()->file }}

                                    <button
                                        type="button"
                                        class="ml-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                        onclick="event.stopPropagation(); copyDocumentNo(this, '{{  $item->receive_good_files->first()->file }}')"
                                        title="Copy"
                                    >
                                        <i class="fa-regular fa-copy"></i>
                                    </button>

                                    @php
                                        $status = strtolower($item->status ?? 'Default');
                                    @endphp
                                    @if($status == 'cancel')
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4 {{ $statusClasses[$status] }}">
                                        {{ $item->status }}
                                    </span>
                                    @endif
                                </td>


                                <td class="px-4 py-3">
                                    {{ $item->po_no }}

                                    <button
                                        type="button"
                                        class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                        onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $item->po_no }}')"
                                        title="Copy"
                                    >
                                        <i class="fa-regular fa-copy"></i>
                                    </button>

                                    @php
                                        $status = strtolower($item->document->status ?? 'default');
                                    @endphp
                                    <!-- <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4 {{ $statusClasses[$status] }}">
                                        {{ $item?->document?->status }}
                                    </span> -->
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->created_at->format("Y-m-d") }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item?->vendor?->vendor_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{-- $item->r008 --}}
                                    <input name="r008" type="checkbox" class="w-3.5 h-3.5 accent-amber-500 rounded" {{ $item->r008 ? 'checked' : '' }}>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->receive_good_files->where('name','R008')->first()?->file ?? "-" }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>
                {{-- $data->links('pagination::tailwind') --}}
                {{ $data->links('vendors.pagination.custom-rg') }}
            </div>

            <!-- PAGINATION -->
            <!-- <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between bg-white">

                <div class="text-[12px] text-slate-500">
                    Showing
                    <span class="font-semibold text-slate-700">1</span>
                    to
                    <span class="font-semibold text-slate-700">10</span>
                    of
                    <span class="font-semibold text-slate-700">120</span>
                    entries
                </div>

                <div class="flex items-center gap-1">

                    <button
                        class="h-8 px-3 border border-slate-300 rounded-md
                            text-[12px] text-slate-600 hover:bg-slate-100"
                    >
                        Prev
                    </button>

                    <button
                        class="h-8 min-w-[32px] px-2 rounded-md
                            bg-amber-500 text-white text-[12px] font-semibold"
                    >
                        1
                    </button>

                    <button
                        class="h-8 min-w-[32px] px-2 rounded-md border border-slate-300
                            text-[12px] text-slate-700 hover:bg-slate-100"
                    >
                        2
                    </button>

                    <button
                        class="h-8 min-w-[32px] px-2 rounded-md border border-slate-300
                            text-[12px] text-slate-700 hover:bg-slate-100"
                    >
                        3
                    </button>

                    <button
                        class="h-8 px-3 border border-slate-300 rounded-md
                            text-[12px] text-slate-600 hover:bg-slate-100"
                    >
                        Next
                    </button>

                </div>

            </div> -->

        </div>

    </div>
    @push('js')
        <script type="text/javascript">

        </script>
    @endpush
@endsection



<!-- php artisan vendor:publish --tag=laravel-pagination -->