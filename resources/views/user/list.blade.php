@extends('layout.layout')

@section('content')
    <div class="m-5">
        <div class="grid grid-cols-2 gpan-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label for="doc_name">Document Number :</label>
                    <input type="text" id="doc_name" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2">
                </div>
                <button class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
            </div>
            <div class="">
            </div>
        </div>

        <div class="">
            <table class="w-full mt-4">
                <thead>
                    <tr class="">
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">Status</th>
                        <th class="py-2 bg-slate-400 border">Document</th>
                        <th class="py-2 bg-slate-400 border">Driver Name</th>
                        <th class="py-2 bg-slate-400 border">Truck No</th>
                        <th class="py-2 bg-slate-400 border">Type Of Truck</th>
                        <th class="py-2 bg-slate-400 border">Remain Goods</th>
                        <th class="py-2 bg-slate-400 border">Exceed Goods</th>
                        <th class="py-2 bg-slate-400 border">Supplier</th>
                        <th class="py-2 bg-slate-400 border">Start Date</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400 {{ $item->status == 'complete' ? 'text-green-600' : 'text-amber-600' }}">{{ $item->status }}</td>
                            <td class="h-10 text-center border border-slate-400">
                                {{ $item->document_no }} &nbsp;

                                @if ($item->status == 'incomplete')
                                    <i class='bx bx-message-square-edit text-amber-600 cursor-pointer ms-3 text-lg edit_view' data-id="{{ $item->id }}" style="transform: translateY(2px)"></i>
                                @endif
                            </td>

                            <td class="h-10 text-center border border-slate-400">{{ $item->car_info->driver_name }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->car_info->truck_no }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->car_info->type_truck }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->remaining_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->exceed_qty }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->vendor_name }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->start_date }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function(){
                $(document).on('click','.edit_view',function(e){
                    $id = $(this).data('id');

                    $.ajax({
                        url : 'edit_goods/'+$id,
                        type: 'get',
                        success: function(res){
                            window.location.href = 'receive_goods/'+$id;
                        }
                    })
                })
            })
        </script>
    @endpush
@endsection
