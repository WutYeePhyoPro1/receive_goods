@extends('layout.layout')

@section('content')

    <div class="md:w-[95%] mx-auto px-4 pt-4 pb-10">
        <!-- PAGE CARD -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class='bx bx-list-ul text-amber-500 text-xl'></i>
                    <h2 class="text-sm font-bold text-slate-700 tracking-wide">
                        Receive Goods Documents
                    </h2>
                </div>
            </div>

            <!-- SEARCH FILTER -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/40">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">

                    <!-- Document No -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                            Document No (RG | PO)
                        </label>

                        <input
                            type="text"
                            placeholder="Search Document..."
                            class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                focus:border-amber-500 bg-white"
                        >
                    </div>

                    <!-- From Date -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                            From Date
                        </label>

                        <input
                            type="date"
                            class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                focus:border-amber-500 bg-white"
                        >
                    </div>

                    <!-- To Date -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                            To Date
                        </label>

                        <input
                            type="date"
                            class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                focus:border-amber-500 bg-white"
                        >
                    </div>

                    <!-- Branch -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                            Branch
                        </label>

                        <div class="flex gap-2">

                            <input
                                type="text"
                                readonly
                                value="User Branch"
                                class="w-full h-9 px-3 bg-slate-100 border border-slate-300
                                    rounded-lg text-[13px] text-slate-500 cursor-not-allowed"
                            >

                            <button
                                type="button"
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

            <!-- TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full text-sm border-collapse">

                    <!-- TABLE HEAD -->
                    <thead class="bg-slate-100 border-b border-slate-200">

                        <tr class="text-[11px] uppercase tracking-wider text-slate-600 whitespace-nowrap">

                            <th class="px-4 py-3 text-left font-bold">No</th>

                            <th class="px-4 py-3 text-left font-bold">
                                RG Document No
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                PO Doc No
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Invoice No
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Document Date
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Vendor Name
                            </th>

                        </tr>

                    </thead>

                    <!-- TABLE BODY -->
                    <tbody class="divide-y divide-slate-100 text-[13px] text-slate-700">

                        <!-- ROW -->
                        <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer">

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

                        <!-- ROW -->
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

                        </tr>

                        <!-- ROW -->
                        <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer">

                            <td class="px-4 py-3 font-medium text-slate-500">
                                3
                            </td>

                            <td class="px-4 py-3 font-semibold text-blue-700">
                                RGMM250527-0003
                            </td>

                            <td class="px-4 py-3">
                                POHTY1260318-0003
                            </td>

                            <td class="px-4 py-3">
                                INV-2026052703
                            </td>

                            <td class="px-4 py-3">
                                2026-05-27
                            </td>

                            <td class="px-4 py-3">
                                Best Supplier Enterprise
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between bg-white">

                <!-- LEFT -->
                <div class="text-[12px] text-slate-500">
                    Showing
                    <span class="font-semibold text-slate-700">1</span>
                    to
                    <span class="font-semibold text-slate-700">10</span>
                    of
                    <span class="font-semibold text-slate-700">120</span>
                    entries
                </div>

                <!-- RIGHT -->
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

            </div>

        </div>

    </div>
    @push('js')
        <script type="text/javascript">
           
        </script>
    @endpush
@endsection