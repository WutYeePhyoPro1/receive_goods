@extends('layout.layout')

@section('content')
<span class="px-5 text-2xl font-serif underline">Today's Report</span>
    <div class="grid grid-cols-5 gap-5 px-5">
        <div class=" min-h-60 rounded-xl shadow-2xl border border-slate-600  mt-10 relative" style="background-color: rgba(131, 131, 131, 0.12)">
            <div class="">
                <span class="text-xl font-serif float-right mr-3 mt-2">Total Scanned Products</span>
            </div>
            <div class="absolute w-full h-full" style="z-index: -1">
                <i class='bx bxs-package text-slate-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline " style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-slate-600' : 'cursor-default' }} " title="{{ $products }}" @can('view-detail-report')
                    onclick="javascript:window.location.href = '/product_list'"
                @endif  >{{ strlen($products) < 6 ? $products : substr($products,0,4).'..' }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-amber-600  mt-10 relative" style="background-color: rgba(189, 190, 121, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">PO/TO Documents</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bx-file text-amber-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-amber-200' : 'cursor-default' }}" @can('view-detail-report')
                     onclick="javascript:window.location.href = 'po_to_list'" @endif>{{ $po }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-emerald-600  mt-10 relative" style="background-color: rgba(121, 190, 173, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Completed Documents</span>
            </div>
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">(REG)</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-folder text-emerald-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-emerald-600' : 'cursor-default' }}"  @can('view-detail-report') onclick="jsvascript:window.location.href = '/finished_documents'" @endcan>{{ $com_doc }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-sky-600  mt-10 relative" style="background-color: rgba(121, 151, 190, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Unloading Trucks</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-truck text-sky-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-sky-600' : 'cursor-default' }}"  @can('view-detail-report')
                     onclick="javascript:window.location.href = '/truck_list'" @endcan>{{ $cars }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-rose-600  mt-10 relative" style="background-color: rgba(190, 121, 121, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Adjust(-) Products</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-trash-alt text-rose-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-rose-600' : 'cursor-default' }}" @can('view-detail-report')
                     onclick="javascript:window.location.href = 'remove_list'" @endcan>{{ $del }}</span>
            </div>
        </div>
        <div class=""></div>
        <div class=""></div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-emerald-600  mt-10 relative" style="background-color: rgba(121, 190, 173, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Non Scanned Product</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bx-scan text-emerald-100 text-9xl'></i>
                <div class="text-rose-200 text-9xl font-semibold" style="transform: translate(20px,-75px) rotate(28deg)">/</div>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-emerald-600' : 'cursor-default' }}" title="{{ $non_scan}}"   @can('view-detail-report') onclick="jsvascript:window.location.href = '/man_add'" @endcan>{{ $non_scan }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-sky-600  mt-10 relative" style="background-color: rgba(121, 151, 190, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">BarCode Printed Products</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-printer text-sky-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-sky-600' : 'cursor-default' }}" title="{{ $print }}" @can('view-detail-report')
                     onclick="javascript:window.location.href = '/print_list'" @endcan>{{ strlen($print) < 6 ? $print : substr($print,0,4).'..' }}</span>
            </div>
        </div>
        <div class=" min-h-60 rounded-xl shadow-2xl border border-rose-600  mt-10 relative" style="background-color: rgba(190, 121, 121, 0.12)">
            <div class="flex justify-end w-full">
                <span class="text-xl font-serif mr-3 mt-2">Shortage Products (Completed)</span>
            </div>
            <div class="absolute w-full h-full top-0" style="z-index: -1">
                <i class='bx bxs-folder-minus text-rose-100 text-9xl'></i>
            </div>
            <div class="text-center absolute underline" style="right: 0;left:0; top:30%">
                <span class="text-7xl select-none {{ getAuth()->role != 2 ? 'cursor-pointer hover:text-rose-600' : 'cursor-default' }}" title="{{ $shortage }}" @can('view-detail-report')
                     onclick="javascript:window.location.href = '/shortage_list'" @endcan>{{ strlen($shortage) < 6 ? $shortage : substr($shortage,0,4).'..' }}</span>
            </div>
        </div>
    </div>

    @push('js')
        <script >
            $(document).ready(function(){
                var updateListHTML = `
                    <div id="update-container" style="text-align: left; margin-left: 20px;">
                        <ol>
                            <li>1. Type of truck တွင် motocycle ကို‌‌ ‌ရွေးပါက truck no အား အများဆုံး ၆ လုံးမှ ၁၀လုံးအထိ ရိုက်နိုင်ခြင်း။</li><br>
                            <li>2. barcode အား Scan မဖတ်လိုပါက barcode ဘေးရှိ minus အား နှိပ်ခြင်းဖြင့် excess/storage ထဲသို့ auto ရောက်သွားခြင်း။</li><br>
                            <li>3. dashboard ရဲ့ total scanned products မှာ not scan qty ထပ်ထည့်ထားခြင်း။</li><br>
                            <li>4. continuous နှိပ်ပြီးပါက car info ထပ်ဖြည့်စရာမလိုအောင် လုပ်ထားပေးခြင်း။</li><br>
                            <li>5. တူညီသော barcode ကို Scan ဖတ်ပါက Scan Count ကို ပြပေးခြင်း။</li><br>
                            <li>6. copy button များအား ပြန်ပြင်ထားခြင်း။</li><br>
                            <li>7. တူညီသော barcode ကို Scan ဖတ်ပါက ရပ်ချင်တာကို ရပ်ထား ပြီး ဖတ်ချင်တာကို ဖတ်လို့ရနိုင်ခြင်း။</li><br>
                            <li>8. Scan qty မမှန်သော issue ကိုဖြေရှင်းပေးထားခြင်း။</li><br>
                            <li>9. DC တွင် Document duplicate ဖြစ်နေသော issue ကို ဖြေရှင်းပေးထားခြင်း။</li><br>
                            <li>10. Product name ကို စာလုံးရေ40လျှင် စာတစ်ကြောင်းသတ်မှတ်ပေး၍ Bar 2 မညီသည့်Issue ကိုဖြေရှင်းထားခြင်း။</li><br>
                            <li>11. Product name တိုသည်ဖြစ်စေ၊ရှည်သည်ဖြစ်စေ Bar 2ကို အဆင်ပြေစွာPrintထုတ်နိုင်ခြင်း။</li>
                        </ol>
                    </div>
                `;

                Swal.fire({
                    icon: 'info',
                    title: "ပြင်ဆင်ထားသော အချက်များနှင့် အသစ်ထည့်ထား သော အချက်များ",
                    html: updateListHTML, 
                    width: '1000px',
                });

            })
        </script>
    @endpush
@endsection
