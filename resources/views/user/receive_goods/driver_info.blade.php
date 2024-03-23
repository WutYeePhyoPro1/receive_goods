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

            <form action="{{ isset($main) ? route('store_car_info') : route('store_doc_info') }}" id="driver_form" method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($main) || !dc_staff())
                        <input type="hidden" name="{{ isset($main) ? 'main_id' : '' }}" value="{{ isset($main) ? $main->id : ''  }}">
                    <div class="grid grid-cols-2 gap-5 my-5">


                        <div class="flex flex-col px-10 relative truck_div">
                            <label for="truck_no">Truck No<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="truck_no" id="truck_no" class="mt-3 border-2 border-slate-600 rounded-t-lg ps-5 py-2 focus:border-b-4 focus:outline-none truck_div" value="{{ old('truck_no') }}" placeholder="xx-xxxx" autocomplete="off">

                                <ul class="2xl:w-[89.5%] w-[85%] bg-white shadow-lg max-h-40 overflow-auto absolute car_auto truck_div" style="top: 100%">
                                </ul>
                            @error('truck_no')
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

                    @if(dc_staff())
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="driver_nrc">Driver NRC<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="driver_nrc" id="driver_nrc" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('driver_nrc') }}" placeholder="nrc...">
                            @error('driver_nrc')
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
                    @endif
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="truck_type">Type of Truck<span class="text-rose-600">{{ dc_staff() ? '*' : '' }}</span> :</label>
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

                        @if (dc_staff() || gate_exist(getAuth()->branch_id))
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
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">


                        <div class="grid grid-cols-3 gap-10  mx-10">
                            <div class="flex flex-col">
                                <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex" onclick="$('#img1').click()" title="image 1"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">1</span></div>

                            </div>
                            <div class="flex flex-col">
                                <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex" onclick="$('#img2').click()" title="image 2"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">2</span></div>

                            </div>
                            <div class="flex flex-col">
                                <div class="w-24  mx-auto text-center py-5 text-2xl font-semibold font-serif cursor-pointer hover:bg-slate-100 rounded-lg shadow-xl img_btn flex" onclick="$('#img3').click()" title="image 3"><small class="ms-5 -translate-y-1">Image</small><span class="translate-y-2">3</span></div>
                            </div>
                            @error('atLeastOne')
                                <small class="text-rose-400 -translate-y-7 ms-12">{{ $message }}</small>
                            @enderror
                        </div>
                            <input type="file" class="car_img" accept="image/*" name="image_1" hidden value="null" id="img1">
                            <input type="file" class="car_img" accept="image/*" name="image_2" hidden value="null" id="img2">
                            <input type="file" class="car_img" accept="image/*" name="image_3" hidden value="null" id="img3">

                        <div class="">
                            <button type="{{ isset($main) || dc_staff() ? 'submit' : 'button' }}" id="{{ isset($main) || dc_staff() ? '' : 'deci_btn' }}" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                        </div>
                    </div>

                @else

                        <div class="grid grid-cols-2 gap-5 my-5">

                            <div class="flex flex-col px-10">
                                <label for="source">Source<span class="text-rose-600">*</span> :</label>
                                <Select name="source" id="source" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                    <option value="">Choose Source</option>
                                    @foreach ($source as $index=>$item)
                                        <option value="{{ $item->id }}" {{ old('source') == $item->id || $index == 0 ? 'selected' : '' }}>{{ $item->name }}</option>
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
                                    @foreach ($branch as $index=>$item)
                                        <option value="{{ $item->id }}" {{ old('branch') == $item->id || $index == 0 ? 'selected' : '' }}>{{ $item->branch_name }}</option>
                                    @endforeach
                                </Select>
                                @error('branch')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                            </div>
                        </div>


                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="">

                        </div>
                        <div class="">
                            <button type="{{ isset($main) || dc_staff() ? 'submit' : 'button' }}" id="{{ isset($main) || dc_staff() ? '' : 'deci_btn' }}" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                        </div>
                    </div>

                @endif

            </form>


        </fieldset>
    </div>

    <div class="hidden" id="deci_model" >
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 " style="z-index:99999 !important">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                        <div class="flex px-4 py-2 justify-center items-center min-w-80 ">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Save နှိပ်ပြီး အချိန်စမှတ်မလားရွေးချယ်ပေးပါ &nbsp;<span
                                    id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                                onclick="$('#deci_model').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-20">
                        <button class="bg-emerald-300 pt-2 pb-3 px-3  rounded-lg save_btn" value="count">Save ပြီးတာနဲ့ အချိန် စ မှတ်ပါမည်</button>
                        <button class="bg-sky-500 pt-2 pb-3 px-3  rounded-lg save_btn" value="no_count">Save ပြီး Scan ဖတ်တော့မှ အချိန် စ မှတ်ပါမည်</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden" id="prew_img" >
        <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75 " style="z-index:99999 !important">
            <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8 relative" style="max-height: 600px;">
                <!-- Modal content -->
                <div class="card rounded">
                        <div class="flex px-4 py-2 justify-center items-center min-w-80 ">
                            <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl"><span
                                    id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 hidden svgclass">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                            <button type="button" class="text-rose-600 font-extrabold absolute top-0 right-0"
                                onclick="$('#prew_img').hide()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                </div>
                <div class="card-body">
                    <img src="" id="pr_im" alt="" style="width: 800px">
                </div>
            </div>
        </div>
    </div>

    @push('js')

        <script>
            $(document).ready(function(e){
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

                $(document).on('click','#deci_btn',function(e){
                    $('#deci_model').show();
                })


                $(document).on('click','.save_btn',function(e){
                    var action = $(this).val();

                    $('#driver_form').append('<input type="hidden" name="action" value="'+action+'">')
                    $('#driver_form').submit();
                })

                $(document).on('change','.car_img',function(e){
                    $index = $('.car_img').index($(this));
                    $('#pree_'+$index).remove();
                    $('.img_btn').eq($index).addClass('bg-emerald-200').after(`
                        <span class="hover:underline cursor-pointer mt-3 img_preview" id="pree_${$index}" data-index="${$index}" style="margin-left:35%">preivew</span>
                    `);
                })

                $(document).on('click','.img_preview',function(e){
                    $index = $(this).data('index');
                    $file  =  $('.car_img').eq($index).get(0);
                    if ($file && $file.files && $file.files[0]) {
                        var file = $file.files[0];
                        var imageUrl = URL.createObjectURL(file);
                        $('#pr_im').attr('src', imageUrl);
                    }
                    $('#prew_img').show();
                    return;
                    $("#pr_im").src(URL.createObjectURL($('.car_img').eq($index).target.files[0]))

                })
            })
        </script>
    @endpush
@endsection
