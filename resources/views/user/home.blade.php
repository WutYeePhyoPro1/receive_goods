@extends('layout.layout')

@section('content')
<span class="px-5 text-2xl font-serif underline">Today's Report</span>
    <div class="grid grid-cols-5 gap-5 px-5">
        <div class=" min-h-60 rounded-xl shadow-2xl border border-slate-200  mt-10 relative" style="background-color: rgba(131, 131, 131, 0.12)">
            <div class="">
                <span class="text-xl font-serif float-right mr-3 mt-2">Total Scanned Products</span>
            </div>
            <div class="absolute w-full h-full" style="z-index: -1">
                <i class='bx bxs-package text-slate-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline " style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-slate-600' : 'cursor-default' }} " title="{{ $products }}" @if (getAuth()->role != 2)
                    onclick="javascript:window.location.href = '/product_list'"
                @endif  >{{ strlen($products) < 6 ? $products : substr($products,0,4).'..' }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-amber-200  mt-10 relative" style="background-color: rgba(189, 190, 121, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Total Documents</span>
            </div>
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">(RG)</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-folder-open text-amber-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-amber-600' : 'cursor-default' }}" onclick="jsvascript:window.location.href = '/list'">{{ $docs }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-emerald-200  mt-10 relative" style="background-color: rgba(121, 190, 173, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Finished Documents</span>
            </div>
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">(RG)</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-folder text-emerald-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-emerald-600' : 'cursor-default' }}"  onclick="jsvascript:window.location.href = '/finished_documents'">{{ $com_doc }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-sky-200  mt-10 relative" style="background-color: rgba(121, 151, 190, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Total Trucks Arrive</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-truck text-sky-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-sky-600' : 'cursor-default' }}" onclick="">{{ $cars }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-rose-200  mt-10 relative" style="background-color: rgba(190, 121, 121, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Total Removed Products</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-trash-alt text-rose-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-rose-600' : 'cursor-default' }}" onclick="">{{ $del }}</span>
            </div>
        </div>
    </div>

    @push('js')
        <script >
            $(document).ready(function(){
                // console.log('yes');
            })
        </script>
    @endpush
@endsection
