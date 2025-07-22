 <div class="scan_parent">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Scann Quantity</th>
                            </tr>
                        </thead>
                        <?php $i = 0; ?>
                        @if (count($scan_document) > 0)
                            @foreach ($scan_document as $item)
                                @if (count(search_scanned_pd($item->id)) > 0)
                                    <?php
                                    $i++;
                                    ?>
                                    <tbody class="scan_body">
                                        @foreach (search_scanned_pd($item->id) as $index => $tem)
                                            <?php
                                            $color = check_scanned_color($tem->id);
                                            $scanned[] = $tem->bar_code;
                                            ?>
                                            {{-- @if ($tem->id == get_latest_scan_pd($main->id))
                                                    <tr class="h-10">
                                                        @if ($index == 0)
                                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest">{{ $i }}</td>
                                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest">{{ $item->document_no }}</td>
                                                        @else
                                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest"></td>
                                                                <td class="ps-2 border border-slate-400 border-t-0 border-l-0 latest"></td>
                                                        @endif
                                                                <td class="ps-2 border border-slate-400 border-t-0  {{ $color }} latest" >{{ $tem->bar_code }}</td>
                                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} latest">{{ $tem->supplier_name }}</td>
                                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} latest border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                                    </tr>
                                                    @else --}}
                                            {{-- <tr class="h-10 scanned_pd_div">
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0 {{ check_all_scan($item->id) ? 'bg-green-200 text-green-600' : '' }}">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif
                                                <td class="ps-2 border border-slate-400 border-t-0  {{ $color }}">{{ $tem->bar_code }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">{{ $tem->supplier_name }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                            </tr> --}}
                                            <tr class="h-10 scanned_pd_div">
                                                @if ($index == 0)
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0">
                                                        {{ $i }}</td>

                                                    <td
                                                        class="td-container ps-2 border border-slate-400 border-t-0 border-l-0 {{ check_all_scan($item->id) ? 'bg-green-200 text-green-600' : '' }}">
                                                        {{-- {{ $item->document_no }} --}}

                                                        <span>{{ $item->document_no }}</span>
                                                        <button data-copy-id="{{ $item->document_no }}"
                                                            class="scan-copy-button">
                                                            <i class="fas fa-copy"></i>
                                                        </button>

                                                        {{-- <div class="container">
                                                            <span id="doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                                                            <button id="btn-copy-doc-{{ $item->document_no }}" class="copy-button" onclick="copyText('scan-doc-no-{{ $item->document_no }}', 'btn-copy-doc-{{ $item->document_no }}')">
                                                                <i class="fa-solid fa-copy"></i>
                                                            </button>
                                                        </div> --}}
                                                    </td>
                                                @else
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif

                                                <td
                                                    class="td-barcode-container ps-2 border border-slate-400 border-t-0  {{ $color }}">
                                                    @if (barcode_equal($product_barcode, $tem->bar_code))
                                                        <button class="pause_scan" id="{{ $tem->id }}"
                                                            data-status="{{ $tem->scann_pause }}"
                                                            data-bar_code="{{ $tem->bar_code }}"
                                                            data-po="{{ $item->document_no }}"><i
                                                                class='bx {{ $tem->scann_pause === 1 ? 'bx-play-circle' : 'bx-pause-circle' }} text-sm'></i></button>
                                                    @endif
                                                    <span
                                                        data-bar_code="{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
                                                    <button data-copy-id="{{ $tem->bar_code }}"
                                                        class="scan-copy-button-barcode">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>

                                                <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">
                                                    {{ $tem->supplier_name }}</td>

                                                <td
                                                    class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">
                                                    {{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty }}
                                                </td>
                                                <td
                                                    class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0 no">
                                                    {{ $tem->scann_count }}</td>
                                            </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                @endif
                            @endforeach
                        @endif

                        <tbody class="search_scan_body"></tbody>

                    </table>
                </div>