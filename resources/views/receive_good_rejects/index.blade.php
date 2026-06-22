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
                        Receive Good Cancel Requests
                    </h2>
                </div>
            </div>

            <!-- SEARCH FILTER -->
            <form action="{{ route('r008s.index') }}" method="GET">
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

                            <th class="px-4 py-3 text-left font-bold">
                                Doc. No
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Status
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Branch
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Requested By
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Requested At
                            </th>

                            <!-- <th class="px-4 py-3 text-left font-bold">
                                Approved By
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Approved At
                            </th> -->

                        </tr>

                    </thead>

                    <!-- TABLE BODY -->
                    <tbody class="divide-y divide-slate-100 text-[13px] text-slate-700">
                        @foreach ($data as $idx=>$item)
                        <tr class="hover:bg-amber-50/40 transition whitespace-nowrap cursor-pointer"  onClick='window.location.href = "{{ route("detail_rg",$item->receive_good_document_id) }}"' >
                            <td class="px-4 py-3 font-medium text-slate-500">
                                {{$idx + $data->firstItem()}}
                            </td>

                            <td class="px-4 py-3 font-semibold text-blue-700">
                                {{ $item->receive_good_document->receive_good_files->first()?->file  }}
                                <button
                                    type="button"
                                    class="ms-2 inline-flex items-center text-gray-400 hover:text-blue-600"
                                    onclick="event.stopPropagation(); copyDocumentNo(this, '{{ $item->receive_good_document->receive_good_files->first()?->file }}')"
                                    title="Copy"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>

                            </td>

                            <td class="px-4 py-3">

                                @php
                                    $status = strtolower($item->status ?? 'Default');
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ms-4s {{ $statusClasses[$status] ??  $statusClasses['default']}}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->branch->branch_name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->user->name }}
                            </td>


                            <td class="px-4 py-3">
                                {{ $item->created_at->format('Y-m-d, H:i:s A') }}
                            </td>


                        </tr>
                        @endforeach
                       

                    </tbody>

                </table>
                {{-- $data->links('pagination::tailwind') --}}
                {{ $data->links('vendors.pagination.custom-rg') }}
            </div>
        </div>

    </div>
    @push('js')
        <script type="text/javascript">
           
        </script>
    @endpush
@endsection



<!-- php artisan vendor:publish --tag=laravel-pagination -->