@extends('layout.layout')

@section('content')
<div class="px-20 mt-20">
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
        <fieldset class="mt-3 border border-slate-500 rounded-md p-5">
            <legend class="px-4 text-2xl font-serif"> Driver Info </legend>

            <form action="{{ route('store_car_info') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="driver_name">Driver Name :</label>
                        <input type="text" name="driver_name" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('driver_name')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex flex-col px-10">
                        <label for="driver_phone">Driver Phone :</label>
                        <input type="number" name="driver_phone" id="driver_phone" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('driver_phone')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="driver_nrc">Driver NRC :</label>
                        <input type="text" name="driver_nrc" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('driver_nrc')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="flex flex-col px-10">
                        <label for="truck_no">Truck No :</label>
                        <input type="text" name="truck_no" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('truck_no')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="truck_type">Type of Truck :</label>
                        <input type="text" name="truck_type" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('truck_type')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="">
                        <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>

    @push('js')
        <script type="module">
            $(document).on('keypress','#driver_phone',function(e){
                let filter = true;

                if($(this).val().length < 11){
                    if  ( e.keyCode >=48 && e.keyCode <= 57){
                        filter = true;
                    }else{
                        filter = false;
                    }
                }else{
                    filter = false;
                }

                if(!filter){
                    e.preventDefault();
                }
            })
        </script>
    @endpush
@endsection
