{{$cur_driver}}
<div class="main_table">
    <table class="w-full" class="main_tb_body">
        <thead>
            <tr class="h-10">
                <th class="border border-slate-400 border-t-0 border-l-0"></th>
                <th class="border border-slate-400 border-t-0 w-8"></th>
                <th class="border border-slate-400 border-t-0">Document No</th>
                <th class="border border-slate-400 border-t-0"><span>Box Barcode</span>
                    <a href="../product_pdf/{{ $main->id }}" target="_blank"><i
                            class='bx bx-download ms-1 hover:text-amber-500'></i></a>
                </th>
                <th class="border border-slate-400 border-t-0">Product Name</th>
                <th class="border border-slate-400 border-t-0">Quantity</th>
                <th class="border border-slate-400 border-t-0">Scanned</th>
                <th class="border border-slate-400 border-t-0 border-r-0">Remaining</th>
            </tr>
        </thead>
        {{-- <input type="hidden" id="doc_total" value="{{ count($document) }}">
        <?php
        $i = 0;
        $j = 0;
        ?>
        @foreach ($document as $item)
        @if (count(search_pd($item->id)) > 0)
        <tbody class="main_body">
            @foreach (search_pd($item->id) as $key => $tem)
            <?php
            $color = check_color($tem->id);
            ${'id' . $key} = $key;
            ?>
            <tr class="h-10">
                @if ($key == 0)
                <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                    @if ((!dc_staff() && $cur_driver && getAuth()->id == $cur_driver->user_id) || dc_staff())
                    <button class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc {{ scan_zero($item->id) ? '' : 'hidden ' }}" data-doc="{{ $item->document_no }}"><i class='bx bx-minus'></i></button>
                    @endif
                </td>
                <td class="ps-2 border border-slate-400 border-t-0  doc_times">{{ $i+1 }}</td>
                <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ $item->document_no }}</td>
                @else
                <td class="ps-2 border border-slate-400 border-t-0 border-l-0 "></td>
                <td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
                <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                @endif

                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} px-2 bar_code">{{ $tem->bar_code }}</td>
                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }}">{{ $tem->supplier_name }}</td>
                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">
                    <span class="cursor-pointer hover:underline hover:font-semibold sticker select-none" data-index="{{ $j }}">{{$tem->qty }}</span>
                    <input type="hidden" class="pd_unit" value="{{ $tem->unit }}">
                    <input type="hidden" class="pd_name" value="{{ $tem->supplier_name }}">
                    <input type="hidden" class="pd_id" value="{{ $tem->id }}">
                    <div class='px-5 bar_stick1 hidden'>{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,50 ) !!}</div>
                    <div class='px-5 bar_stick2 hidden'>{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,22 ) !!}</div>
                    <div class='px-5 bar_stick3 hidden'>{!! DNS1D::getBarcodeHTML( $tem->bar_code ?? '1' , 'C128' ,2,50 ) !!}</div>
                </td>

                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">
                    <div class="main_scan">
                        {{ $tem->scanned_qty }}
                        @if (isset($cur_driver->start_date))
                        <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' data-index="{{ $j }}" title="add quantity"></i>
                        @endif
                    </div>
                    <input type="hidden" class="w-[80%] real_scan border border-slate-400 rounded-md" data-id="{{ $tem->id }}" data-old="{{ $tem->scanned_qty }}" value="{{ $tem->scanned_qty }}">
                </td>
                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">{{ $tem->qty - $tem->scanned_qty }}</td>
            </tr>
            <?php
            $j++;
            ?>
            @endforeach
        </tbody>
        <?php $i++; ?>
        @endif
        @endforeach
        <input type="hidden" id="count" value="{{ $i }}"> --}}

        <input type="hidden" id="doc_total" value="{{ count($document) }}">
        <?php
        $i = 0;
        $j = 0;
        ?>
        @foreach ($document as $item)
        @if (count(search_pd($item->id)) > 0)
        <tbody class="main_body">
            @foreach (search_pd($item->id) as $key => $tem)
            <?php
            $color = check_color($tem->id);
            ${'id' . $key} = $key;
            ?>
            <tr class="h-10 main_pd_div">
                @if ($key == 0)
                <td class="ps-1 border border-slate-400 border-t-0 border-l-0 w-8">
                    @if (
                    (!dc_staff() && $cur_driver && getAuth()->id == $cur_driver->user_id) ||
                    (!dc_staff() && $driver_last && getAuth()->id == $driver_last->scan_user_id) ||
                    dc_staff())
                    <button
                        class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_doc {{ scan_zero($item->id) ? '' : 'hidden ' }}"
                        data-doc="{{ $item->document_no }}"><i
                            class='bx bx-minus'></i></button>
                    @endif
                </td>
                <td class="ps-2 border border-slate-400 border-t-0 doc_times">
                    {{ $i + 1 }}
                </td>
                {{-- <td class="ps-2 border border-slate-400 border-t-0 doc_no">
                                                    <div class="container">
                                                        <span id="doc-no-{{ $item->document_no }}">{{ $item->document_no }}</span>
                <button id="btn-copy-doc-{{ $item->document_no }}" class="copy-button" onclick="copyText('doc-no-{{ $item->document_no }}', 'btn-copy-doc-{{ $item->document_no }}')">
                    <i class="fa-solid fa-copy"></i>
                </button>
</div>
</td> --}}
<td class="td-container ps-2 border border-slate-400 border-t-0 doc_no">
    <span>{{ $item->document_no }}</span>
    <button data-copy-id="{{ $item->document_no }}" class="copy-button">
        <i class="fas fa-copy"></i>
    </button>
</td>
@else
<td class="ps-2 border border-slate-400 border-t-0 border-l-0 "></td>
<td class="ps-2 border border-slate-400 border-t-0 doc_times"></td>
<td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
@endif

<td
    class="td-barcode-container ps-2 border border-slate-400 border-t-0 color_add {{ $color }} px-2 bar_code">
    {{-- @if (!$color) --}}
    <button
        class="bg-rose-400 hover:bg-rose-700 text-white px-1 rounded-sm del_barcode"
        data-barcode="{{ $tem->bar_code }}" data-id="{{ $tem->id }}">
        <i class="bx bx-minus"></i>
    </button>
    {{-- @endif --}}


    {{-- <span data-barCode="{{ $tem->bar_code }}">{{ $tem->bar_code }}</span>
    <button data-copy-id="{{ $tem->bar_code }}" data-barCode="{{ $tem->bar_code }}" class="copy-button-barcode">
        <i class="fas fa-copy"></i>
    </button> --}}
    <span>{{ $tem->bar_code }}</span>
    <button data-copy-id="{{ $tem->bar_code }}" class="copy-button-barcode">
        <i class="fas fa-copy"></i>
    </button>

</td>
<td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }}">
    {{ $tem->supplier_name }}
</td>
<td
    class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">
    <span
        class="cursor-pointer hover:underline hover:font-semibold sticker select-none"
        data-index="{{ $j }}">{{ $tem->qty }}</span>

    <div style="font-family: 'Courier New', Courier, monospace;font-weight:light">
        <input type="hidden" class="pd_unit" value="{{ $tem->unit }}">
        <input type="hidden" class="pd_name" value="{{ $tem->supplier_name }}">
        <input type="hidden" class="pd_id" value="{{ $tem->id }}">
    </div>
    <div class='px-5 bar_stick1 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 50) !!}</div>
    <div class='px-5 bar_stick2 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 26) !!}</div>
    <div class='px-5 bar_stick3 hidden'>{!! DNS1D::getBarcodeHTML($tem->bar_code ?? '1', 'C128', 2, 50) !!}</div>
</td>
<td
    class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">
    <div class="main_scan">
        {{ $tem->scanned_qty }}
        @if (!$driver_last)
        {{-- <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan' data-index="{{ $j }}" title="add quantity"></i> --}}
        @else
        @if (isset($driver_last->start_date))
        <i class='bx bx-key float-end mr-2 cursor-pointer text-xl change_scan'
            data-index="{{ $j }}" title="add quantity"></i>
        @endif
        @endif
    </div>
    <input type="hidden"
        class="w-[80%] real_scan border border-slate-400 rounded-md"
        data-id="{{ $tem->id }}" data-old="{{ $tem->scanned_qty }}"
        value="{{ $tem->scanned_qty }}">
</td>
<td
    class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">
    {{ $tem->qty - $tem->scanned_qty }}
</td>
</tr>
<?php
$j++;
?>
@endforeach
</tbody>
<?php $i++; ?>
@endif
@endforeach
<input type="hidden" id="count" value="{{ $i }}">
<tbody class="search_main_body">
</tbody>
</table>
</div>