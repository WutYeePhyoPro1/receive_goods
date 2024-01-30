@extends('layout.layout')

@section('content')
<div class="px-20 mt-20">
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
        <fieldset class="mt-3 border border-slate-500 rounded-md p-5">
            <legend class="px-4 text-2xl font-serif"> {{ isset($main) ? 'Driver Info' : 'Document Info' }} </legend>

            {{-- @if (isset($driver))
                <div class="text-center">
                    <select id="old_driver" class="px-3 min-w-[20%] h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                        <option value="">Choose Previous Car</option>
                        @foreach($driver as $item)
                            <option value="{{ $item->id }}">{{ $item->truck_no }}</option>
                        @endforeach

                    </select>
                </div>
            @endif --}}

            <form action="{{ isset($main) ? route('store_car_info') : route('store_doc_info') }}" method="POST">
                @csrf
                @if (isset($main))
                    <input type="hidden" name="{{ isset($main) ? 'main_id' : '' }}" value="{{ isset($main) ? $main->id : ''  }}">
                    <div class="grid grid-cols-2 gap-5 my-5">


                        <div class="flex flex-col px-10 relative truck_div">
                            <label for="truck_no">Truck No<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="truck_no" id="truck_no" class="mt-3 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none truck_div" value="{{ old('truck_no') }}" placeholder="truck..." autocomplete="off">

                                <ul class="2xl:w-[89.5%] w-[85%] bg-white shadow-lg max-h-40 overflow-auto absolute car_auto truck_div" style="top: 100%">
                                </ul>
                            @error('truck_no')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="flex flex-col px-10">
                            <label for="driver_phone">Driver Phone<span class="text-rose-600">*</span> :</label>
                            <input type="number" name="driver_phone" id="driver_phone" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('driver_phone') }}" placeholder="09*********">
                            @error('driver_phone')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="driver_nrc">Driver NRC<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="driver_nrc" id="driver_nrc" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('driver_nrc') }}" placeholder="nrc...">
                            @error('driver_nrc')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                        <div class="flex flex-col px-10">
                            <label for="driver_name">Driver Name<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="driver_name" id="driver_name" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" placeholder="name..." value="{{ old('driver_name') }}">
                            @error('driver_name')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="truck_type">Type of Truck<span class="text-rose-600">*</span> :</label>
                            <Select name="truck_type" id="truck_type" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Type of Truck</option>
                                @foreach ($truck as $item)
                                    <option value="{{ $item->id }}" {{ old('truck_type') == $item->id ? 'selected' : '' }}>{{ $item->truck_name }}</option>
                                @endforeach
                            </Select>
                            @error('truck_type')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                        <div class="flex flex-col px-10">
                            <label for="gate">Gate<span class="text-rose-600">*</span> :</label>
                            <Select name="gate" id="gate" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Gate</option>
                                @foreach ($gate as $item)
                                    <option value="{{ $item->id }}" {{ old('gate') == $item->id ? 'selected' : '' }}>{{ $item->name.'('.$item->branches->branch_name.')' }}</option>
                                @endforeach
                            </Select>
                            @error('gate')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>

                @else
                <div class="grid grid-cols-2 gap-5 my-5">

                        <div class="flex flex-col px-10">
                            <label for="source">Source<span class="text-rose-600">*</span> :</label>
                            <Select name="source" id="source" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Source</option>
                                @foreach ($source as $item)
                                    <option value="{{ $item->id }}" {{ old('source') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </Select>
                            @error('source')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                        <div class="flex flex-col px-10">
                            <label for="branch">branch<span class="text-rose-600">*</span> :</label>
                            <Select name="branch" id="branch" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose branch</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->id }}" {{ old('branch') == $item->id ? 'selected' : '' }}>{{ $item->branch_name }}</option>
                                @endforeach
                            </Select>
                            @error('branch')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-5 my-5">

                    <div class="">

                    </div>
                    <div class="">
                        <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>

    @push('js')
        <script>
            $(document).ready(function(){


                $(document).on('change','#old_driver',function(e){
                    $val = $(this).val();
                    // $('#truck_type option').each((i,v)=>{
                    //     if(i == 0){
                    //         console.log($(v).eq(i).text());

                    //     }
                    //         });
                    //         return;
                    $.ajax({
                        url : "/get_driver_info/"+$val,
                        type: 'GET',
                        success: function(res){
                            $('#driver_name').val(res.driver_name);
                            $('#driver_phone').val(res.ph_no);
                            $('#driver_nrc').val(res.nrc_no);
                            $('#truck_no').val(res.truck_no);
                            $('#truck_type option').each((i,v)=>{
                                $(v).attr('selected',false);
                                if($(v).val() == res.type_truck)
                                {
                                    $(v).prop('selected',true);
                                }
                            });

                        }
                    })
                })
            })
        </script>
    @endpush
@endsection
