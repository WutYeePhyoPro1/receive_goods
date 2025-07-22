 <div class="excess_div">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0 w-8"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity</th>
                            </tr>
                        </thead>

                        <?php $i = 0; ?>

                        {{-- @foreach ($document as $item)
                                @if (count(search_excess_pd($item->id)) > 0)
                                <?php
                                $i++;
                                ?>
                                    <tbody class="excess_body">
                                    @foreach (search_excess_pd($item->id) as $index => $tem)
                                    <?php
                                    ?>
                                    <tr class="h-10">
                                        <td class="ps-1 border border-slate-400 border-t-0 border-l-0">
                                            @can('adjust-excess')
                                                @if ($main->status == 'complete' && $tem->qty < $tem->scanned_qty)
                                                    <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed" data-id="{{ $tem->id }}"><i class='bx bx-minus'></i></button>
                                                @endif
                                            @endcan
                                        </td>
                                        @if ($index == 0)
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                <td class="td-container ps-2 border border-slate-400 border-t-0 border-l-0">
                                                        <span id="excess-doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                                                        <button id="excess-btn-copy-doc-{{ $item->document_no }}" class="excess-copy-button">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                </td>
                                        @else
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                        @endif
                                                <td class="td-barcode-container ps-2 border border-slate-400 border-t-0">
                                                        <span id="excess-bar-code-{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
                                                        <button id="excess-btn-copy-bar-{{ $tem->bar_code }}" class="excess-copy-button-barcode">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}
                                                    <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1 rounded-lg px-1 text-white {{ !isset($tem->remark) ? 'bg-emerald-400 hover:bg-emerald-600' : 'bg-sky-400 hover:bg-sky-600' }} remark_ic' data-pd="{{ $tem->bar_code }}" data-id="{{ $tem->id }}" data-eq="{{ $index }}"></i>
                                                </td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-r-0 {{ $tem->scanned_qty > $tem->qty ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tem->scanned_qty - $tem->qty }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>

                                @endif
                            @endforeach --}}

                        @foreach ($document as $item)
                            @php
                                $searchResult = search_excess_pd($item->id);
                            @endphp

                            @if (count($searchResult) > 0)
                                <?php $i++; ?>
                                <tbody class="excess_body">
                                    @foreach ($searchResult as $index => $tem)
                                        <tr class="h-10">
                                            <td class="ps-1 border border-slate-400 border-t-0 border-l-0">
                                                @can('adjust-excess')
                                                    @if ($main->status == 'complete' && $tem->qty < $tem->scanned_qty)
                                                        <button
                                                            class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_exceed"
                                                            data-id="{{ $tem->id }}">
                                                            <i class='bx bx-minus'></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </td>
                                            @if ($index == 0)
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0">
                                                    {{ $i }}</td>
                                                <td
                                                    class="td-container ps-2 border border-slate-400 border-t-0 border-l-0">
                                                    <span>{{ $item->document_no }}</span>
                                                    <button data-copy-id="{{ $item->document_no }}"
                                                        class="excess-copy-button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                            @else
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                            @endif
                                            <td class="td-barcode-container ps-2 border border-slate-400 border-t-0">
                                                <span>{{ $tem->bar_code }}</span>
                                                <button data-copy-id="{{ $tem->bar_code }}"
                                                    class="excess-copy-button-barcode">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                            <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}
                                                <i class='bx bx-message-rounded-dots cursor-pointer float-end text-xl mr-1 rounded-lg px-1 text-white {{ !isset($tem->remark) ? 'bg-emerald-400 hover:bg-emerald-600' : 'bg-sky-400 hover:bg-sky-600' }} remark_ic'
                                                    data-pd="{{ $tem->bar_code }}" data-id="{{ $tem->id }}"
                                                    data-eq="{{ $index }}"></i>
                                            </td>
                                            <td
                                                class="ps-2 border border-slate-400 border-t-0 border-r-0 {{ $tem->scanned_qty > $tem->qty ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $tem->scanned_qty - $tem->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        @endforeach
                        <tbody class="excess_scan_body"></tbody>
                    </table>
                </div>