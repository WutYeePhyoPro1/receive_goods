@extends('layout.layout')

@section('content')

    <div class="md:w-[100%] mx-auto px-4 pt-4 pb-10">
        @if (Session::has('fails'))
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm" role="alert">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                </div>
                <div class="flex-1 text-sm font-medium">
                    {{ Session::get('fails') }}
                </div>
                <button
                    type="button"
                    class="text-red-400 hover:text-red-600"
                    onclick="this.closest('[role=alert]').remove()"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm" role="alert">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div class="flex-1 text-sm font-medium">
                    {{ Session::get('success') }}
                </div>
                <button
                    type="button"
                    class="text-green-400 hover:text-green-600"
                    onclick="this.closest('[role=alert]').remove()"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

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
            <form action="{{ route('receive_good_rejects.index') }}" method="GET">
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
                         {{-- 
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
                         --}}

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                                Status
                            </label>

                            <div class="flex gap-2">

                                <select
                                    name="status"
                                    class="w-full h-9 px-3 border border-slate-300 rounded-lg text-[13px]
                                    focus:outline-none focus:ring-2 focus:ring-amber-400/30
                                    focus:border-amber-500 bg-white"
                                >
                                    <option value="" selected disabled>Choose Status</option>
                                    <option value="Pending Mgr Review">Pending Mgr Review</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
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

                                @if($status == 'pending mgr review')
                                    <svg class="inline text-rose-800"  xmlns="http://www.w3.org/2000/svg" width="18" height="18"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M8 11a1 1 0 1 0 0 2 1 1 0 1 0 0-2m4 0a1 1 0 1 0 0 2 1 1 0 1 0 0-2m4 0a1 1 0 1 0 0 2 1 1 0 1 0 0-2"></path><path d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2m0 18H4.91L6 18.43c.26-.37.23-.88-.06-1.22A7.98 7.98 0 0 1 4.01 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8Z"></path>
                                    </svg>
                                @endif
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
