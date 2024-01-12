@extends('layout.layout')

@section('content')
    {{-- <span>this is received_good</span> --}}
    <div class="flex justify-between">
        <div class="flex {{ $data->duration ? 'invisible pointer-events-none' : '' }}">
            <input type="text" id="docu_ipt" class="w-80 min-h-12 shadow-lg border-slate-400 border rounded-xl pl-5 focus:border-b-4 focus:outline-none">
            <button  class="h-12 bg-amber-400 text-white px-8 ml-8 rounded-lg hover:bg-amber-500" id="search_btn">Search</button>
            <button class="h-12 bg-teal-400 text-white px-4 rounded-md ml-2 text-2xl hover:bg-teal-600" id="driver_info"><i class='bx bx-id-card'></i></button>
        </div>
        <div class="flex">
            <span class=" mt-2 -translate-x-6 hidden 2xl:block" >Vendor Name : <b class="text-2xl" id="vendor_name">{{ $data->vendor_name ?? '' }}</b></span>
            @if (!$data->edit_duration)
                <button class="h-12 bg-sky-300 hover:bg-sky-600 text-white px-16 tracking-wider font-semibold rounded-lg" id="{{ $data->duration ? 'finish_btn' : 'confirm_btn' }}">{{ $data->duration ? 'Finish' : 'Confirm' }}</button>
            @endif
        </div>
        <?php
            if($data->duration && !$data->edit_duration && $data->edit_start_time)
            {
                list($hour, $min, $sec) = explode(':', $data->duration);
                $total_sec    = $hour*3600 + $min*60 + $sec;
                $edit_start = strtotime($data->edit_start_time);
                $diff = strtotime(\Carbon\Carbon::now()) - $edit_start;
                $combine = $total_sec + $diff;
                $hour   = (int)($combine / 3600);
                $min    = (int)(($combine % 3600) / 60);
                $sec    = (int)(($combine % 3600) % 60);
                $sec_pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
            }elseif($data->edit_duration){
                $sec_pass = get_duration($data->id);
            }else{
                $sec_pass  = $data->duration;
            }
        ?>

        <span class="mr-0 text-5xl font-semibold tracking-wider select-none text-amber-400 whitespace-nowrap" id="time_count">{{ $data->duration ? $sec_pass : $pass }}</span>

    </div>
    <input type="hidden" id="bar_code" value="" >
    <input type="hidden" id="finished" value="{{ $data->status == 'complete' ? true : false }}">
    <div class="grid grid-cols-2 gap-2">
    <div class="mt-5 border border-slate-400 rounded-md main_product_table" style="min-height: 83vh;max-height:83vh;width:100%;overflow-x:hidden;overflow-y:auto">
            <div class="border border-b-slate-400 h-10 bg-sky-50">
                <span class="font-semibold leading-9 ml-3">
                    List Of Products
                </span>
            </div>
            <input type="hidden" id="started_time" value="{{ $data->duration ? $data->edit_start_time : $data->start_date.' '.$data->start_time }}">
            <input type="hidden" id="duration" value="{{ $total_sec ?? 0 }}">
            <input type="hidden" id="receive_id" value="{{ $data->id }}">
            <div class="main_table">
                <table class="w-full" class="main_tb_body">
                    <thead>
                        <tr class="h-10">
                            <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                            <th class="border border-slate-400 border-t-0">Document No</th>
                            <th class="border border-slate-400 border-t-0">Box Barcode</th>
                            <th class="border border-slate-400 border-t-0">Product Name</th>
                            <th class="border border-slate-400 border-t-0">Quantity</th>
                            <th class="border border-slate-400 border-t-0">Scanned</th>
                            <th class="border border-slate-400 border-t-0 border-r-0">Remaining</th>
                        </tr>
                    </thead>


                            <?php
                                $i = 0;
                            ?>
                            @foreach($document as $item)
                                @if (count(search_pd($item->id)) > 0)
                                    <tbody class="main_body">
                                        @foreach (search_pd($item->id) as $key=>$tem)

                                            <?php
                                                $color = check_color($tem->id);
                                            ?>
                                            <tr class="h-10">
                                                @if ($key == 0)
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0 doc_times">{{ $i+1 }}</td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 doc_no">{{ $item->document_no }}</td>
                                                @else
                                                    <td class="ps-2 border border-slate-400 border-t-0 border-l-0 doc_times"></td>
                                                    <td class="ps-2 border border-slate-400 border-t-0 doc_no"></td>
                                                @endif
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} px-2 bar_code">{{ $tem->bar_code }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }}">{{ $tem->supplier_name }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} qty">{{ $tem->qty }}</td>
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} scanned_qty">{{ $tem->scanned_qty }}</td>
                                                <input type="hidden" class="real_scan" value="{{ $tem->scanned_qty }}">
                                                <td class="ps-2 border border-slate-400 border-t-0 color_add {{ $color }} border-r-0 remain_qty">{{ $tem->qty - $tem->scanned_qty }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                        <?php $i++ ?>
                                @endif
                            @endforeach

                            <input type="hidden" id="count" value="{{ $i }}">

                </table>
            </div>

        </div>
        <div class="mt-5 grid grid-rows-2 gap-2" style="max-height: 83vh;width:100%; overflow:hidden">
            <div class="border border-slate-400 rounded-md overflow-y-auto overflow-x-hidden main_product_table" style="max-height: 42.5vh;width:100%;">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products
                    </span>
                </div >
                <div class="scan_parent">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity(Box)</th>
                            </tr>
                        </thead>
                            <?php $i=0 ?>
                            @foreach ($document as $item)
                            @if (count(search_scanned_pd($item->id))>0)
                            <?php
                                $i++;
                            ?>
                                <tbody class="scan_body" >
                                @foreach (search_scanned_pd($item->id) as $index=>$tem)
                                <?php
                                            $color = check_scanned_color($tem->id);
                                            $scanned[]  = $tem->bar_code;
                                            ?>
                                            <tr class="h-10">
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif
                                                        <td class="ps-2 border border-slate-400 border-t-0  {{ $color }}">{{ $tem->bar_code }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 {{ $color }}">{{ $tem->supplier_name }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 {{ $color }} border-r-0">{{ $tem->scanned_qty > $tem->qty ? $tem->qty : $tem->scanned_qty  }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                            @endif
                                @endforeach


                    </table>
                </div>
            </div>
            <div class="border border-slate-400 rounded-md overflow-x-hidden overflow-y-auto main_product_table" style="max-height: 42.5vh;width:100%">
                <div class="border border-b-slate-400 h-10 bg-sky-50">
                    <span class="font-semibold leading-9 ml-3">
                        List Of Scanned Products (excess)
                    </span>
                </div>
                <div class="excess_div">
                    <table class="w-full">
                        <thead>
                            <tr class="h-10">
                                <th class="border border-slate-400 border-t-0 w-8 border-l-0"></th>
                                <th class="border border-slate-400 border-t-0">Document No</th>
                                <th class="border border-slate-400 border-t-0">Box Barcode</th>
                                <th class="border border-slate-400 border-t-0">Product Name/Supplier Name</th>
                                <th class="border border-slate-400 border-t-0 border-r-0">Quantity(Box)</th>
                            </tr>
                        </thead>

                            <?php $i=0 ?>
                            @foreach ($document as $item)
                            @if (count(search_excess_pd($item->id))>0)
                            <?php
                                $i++;
                            ?>
                                <tbody class="excess_body" >
                                @foreach (search_excess_pd($item->id) as $index=>$tem)
                                <?php
                                            ?>
                                            <tr class="h-10">
                                                @if ($index == 0)
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $i }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0">{{ $item->document_no }}</td>
                                                @else
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                                @endif
                                                        <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->bar_code }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0">{{ $tem->supplier_name }}</td>
                                                        <td class="ps-2 border border-slate-400 border-t-0 border-r-0">{{ $tem->scanned_qty - $tem->qty  }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                            @endif
                                @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
 {{-- Product Modal --}}
 <div class="hidden" id="showProductModal">
    <div class="flex items-center fixed inset-0 justify-center z-50 bg-gray-500 bg-opacity-75">
        <div class="bg-gray-100 rounded-md shadow-lg overflow-y-auto p-4 sm:p-8" style="max-height: 600px;">
            <!-- Modal content -->
            <div class="card rounded">
                <div
                    class="card-header border-2 rounded min-w-full sticky inset-x-0 top-0 backdrop-blur backdrop-filter">
                    <div class="flex px-4 py-2 justify-between items-center min-w-80">
                        <h3 class="font-bold text-gray-50 text-slate-900 ml-5 sm:flex font-serif text-2xl">Car Info &nbsp;<span
                                id="show_doc_no"></span>&nbsp;<svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 hidden svgclass">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>&nbsp;<span id="show_adjust_doc_no"></span></h3>

                        <button type="button" class="text-rose-600 font-extrabold"
                            onclick="$('#showProductModal').hide()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-4">
                   <div class="grid grid-cols-2 gap-5">
                        <div class="flex flex-col">
                            <span class="mb-4 text-xl">Driver's Name     </span>
                            <span class="mb-4 text-xl">Driver's Phone No </span>
                            <span class="mb-4 text-xl">Driver's NRC No </span>
                            <span class="mb-4 text-xl">Truck's No        </span>
                            <span class="mb-4 text-xl">Truck's Type      </span>
                            <span class="mb-4 text-xl ">Branch      </span>
                            <span class="mb-4 text-xl 2xl:hidden">Vendor Name      </span>
                        </div>
                        <div class="flex flex-col">
                            <b class="mb-4 text-xl">:&nbsp;{{ $driver->driver_name }}</b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $driver->ph_no }} </b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $driver->nrc_no }}</b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $driver->truck_no }}</b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $driver->type_truck }}</b>
                            <b class="mb-4 text-xl">:&nbsp;{{ $data->user->branch->branch_name }}</b>
                            <b class="mb-4 text-xl 2xl:hidden">:&nbsp;{{ $data->vendor_name ?? '' }}</b>
                        </div>
                   </div>
                </div>
            </div>
        </div>
</div>
</div>
{{-- End Modal --}}
    @push('js')
        <script >
            $(document).ready(function(e){
                var token = $("meta[name='__token']").attr('content');
                $finish = $('#finished').val();

                if(!$finish){
                    setInterval(() => {
                        time_count();
                    }, 1000);


                    var key = '';
                    $(document).on('keypress input',function(e){

                        if (e.key === 'Enter') {

                            $('#bar_code').val(key);
                            $('#bar_code').trigger('barcode_enter');
                            key = '';
                        } else {
                            key += e.key;
                        }
                    });

                    $(document).on('barcode_enter','#bar_code',function(e){
                        $val  = $(this).val();
                        $recieve_id = $('#receive_id').val();
                        $this       = $(this);
                        $code       =  $val.replace(/\D/g, '');
                        if($val){
                            $.ajax({
                                url : "{{ route('barcode_scan') }}",
                                type: 'POST',
                                data: {_token:token , data:$val,id:$recieve_id},
                                success:function(res){
                                    $('.main_table').load(location.href + ' .main_table');
                                    // $('.bar_code').each((i,v)=>{
                                        // if($(v).text() == $code){
                                            // $scan   = parseInt($(v).parent().find('.scanned_qty').text());
                                            // $real_scan = parseInt($(v).parent().find('.real_scan').val());
                                            // $remain = parseInt($(v).parent().find('.remain_qty').text());
                                            // $qty    = parseInt($(v).parent().find('.qty').text());
                                            // $(v).parent().find('.scanned_qty').text($scan+1 >= $qty ? $qty : Math.floor($scan + res.scanned_qty));
                                            // $(v).parent().find('.remain_qty').text($remain-res.scanned_qty <= 0 ? 0 : Math.floor($remain - res.scanned_qty));
                                            // $(v).parent().find('.real_scan').val(Math.floor($real_scan+1));
                                            // if($scan+res.scanned_qty > 0 && $scan+res.scanned_qty < $qty){
                                            //     console.log('yes');
                                            //     $(v).parent().find('.color_add').each((i,v)=>{
                                            //         $(v).removeClass('bg-amber-200 text-amber-600');
                                            //         $(v).addClass('bg-amber-200 text-amber-600');
                                            //     })

                                            // }else if($scan+res.scanned_qty == $qty){
                                            //     $no = 0;
                                            //     $doc= '';
                                            //     $parent = $(v).parent().parent();
                                            //     $(v).parent().parent().find('tr').each((i,v)=>{
                                            //         if(i == 0){
                                            //             $no = $(v).find('.doc_times').text();
                                            //             $doc = $(v).find('.doc_no').text();
                                            //         }
                                            //         return false;
                                            //     })
                                            //     $(v).parent().remove();
                                            //     $parent.find('tr').each((i,v)=>{
                                            //         if(i == 0){
                                            //             $(v).find('.doc_times').text($no);
                                            //             $(v).find('.doc_no').text($doc);
                                            //         }
                                            //         return false;
                                            //     })
                                            //     if($parent.find('tr').length == 0){
                                            //         $parent.remove()
                                            //     }
                                            //     $('.main_body').each((i,v)=>{
                                            //         $(v).find('tr').eq(0).find('td').eq(0).text(i+1);
                                            //     })
                                            // }
                                            // return false;
                                        // }
                                    // })
                                    $('.scan_parent').load(location.href + ' .scan_parent');
                                    if(res.data.scanned_qty > res.data.qty){
                                        $('.excess_div').load(location.href + ' .excess_div');
                                    }

                                },
                                error : function(xhr,status,error){
                                    if(xhr.status == 500)
                                    {
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Server Time Out Please Contact SD Dep'
                                        });
                                    }else if(xhr.status == 404){
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'Warning',
                                            text : 'Bar Code Not found'
                                        });
                                    }
                                    setTimeout(() => {
                                        Swal.close();
                                        }, 2000);
                                },
                                complete:function(){
                                    $this.val('');
                                }

                            })
                        }
                    })

                    function time_count(){
                    let time = new Date($('#started_time').val()).getTime();
                    let duration = ($('#duration').val() * 1000);


                    let now  = new Date().getTime();
                    let diff = Math.floor(now - time + duration);
                    let hour = Math.floor(diff / (60*60*1000));
                    let min = Math.floor((diff % (60 * 60 * 1000)) / (60 * 1000));
                    let sec = Math.floor((diff % (60 * 60 * 1000)) % (60 * 1000) / (1000));

                    $('#time_count').text(hour.toString().padStart(2, '0') + ':' + min.toString().padStart(2, '0') + ':' + sec.toString().padStart(2, '0'));
                }

                $count = parseInt($('#count').val()) || 0;

                $(document).on('keypress', '#docu_ipt', function(e) {
                    if (e.keyCode === 13) {
                        e.preventDefault();
                        $('#search_btn').click();
                        $(this).val('');
                    }
                });

                $(document).on('click','#driver_info',function(e){
                    $('#showProductModal').toggle();
                })

                $(document).on('click','#search_btn',function(e){
                    let id = $('#receive_id').val();
                        let val = $('#docu_ipt').val();
                        $this = $('#docu_ipt');
                        $vendor = $('#vendor_name').text();
                        $.ajax({
                            url     : "{{ route('search_doc') }}",
                            type    : 'POST',
                            data    :  {_token:token,data:val,id:id},
                            success : function(res){
                                if($vendor == ''){
                                    $('#vendor_name').text(res[0].vendorname);
                                }
                                $list = '<tbody class="main_body">';
                                for($i = 0 ; $i < res.length ; $i++)
                                {
                                    if($i == 0){
                                        $list += `
                                        <tr class="h-10">
                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0">${Math.floor($count+1)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 ">${res[$i].purchaseno}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0  px-2 bar_code">${res[$i].productcode}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0">${res[$i].productname}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 qty">${parseInt(res[$i].goodqty)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 scanned_qty">0</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-r-0 remain_qty">${parseInt(res[$i].goodqty)}</td>
                                        </tr>
                                    `;
                                    $count++;
                                    }else{
                                        $list += `
                                        <tr class="h-10">
                                            <td class="ps-2 border border-slate-400 border-t-0 border-l-0"></td>
                                            <td class="ps-2 border border-slate-400 border-t-0 "></td>
                                            <td class="ps-2 border border-slate-400 border-t-0  px-2 bar_code">${res[$i].productcode}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 ">${res[$i].productname}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 qty">${parseInt(res[$i].goodqty)}</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 scanned_qty">0</td>
                                            <td class="ps-2 border border-slate-400 border-t-0 border-r-0 remain_qty">${parseInt(res[$i].goodqty)}</td>
                                        </tr>
                                        `;
                                    }

                                }
                                $list += `</tbody>`;
                                $length = $('.main_body').length;
                                if($length > 0){
                                    $('.main_body').eq($length-1).after($list);
                                }else{
                                    $('.main_table').load(location.href + ' .main_table');

                                }
                            },
                            error   : function(xhr,status,error){
                                console.log(xhr.status);
                                if(xhr.status == 500){
                                    Swal.fire({
                                    icon:'error',
                                    title: 'Warning',
                                    text: 'Doucment တခုကို နှစ်ကြိမ်ထည့်ခွင့်မရှိပါ'
                                })
                                }else if(xhr.status == 404){
                                    Swal.fire({
                                    icon:'error',
                                    title: 'Warning',
                                    text: 'Document မတွေ့ပါ'
                                })
                                }

                            },
                            complete:function(){
                                $this.val('');
                            }
                        })
                })

                $(document).on('click','#confirm_btn',function(e){
                    $id = $('#receive_id').val();
                    $.ajax({
                        url : "{{ route('confirm') }}",
                        type: 'POST',
                        data:{_token : token , id :$id},
                        success:function(res){
                            location.href = '/list';
                        },
                        error:function(xhr,status,error){
                            Swal.fire({
                                icon : 'error',
                                title: 'Warning',
                                text : 'ကျေးဇူးပြုပြီး doc တစောင် အနည်းဆုံးထည့်ပေးပါ'
                            })
                        }

                    })

                })

                $(document).on('click','#finish_btn',function(e){
                    $finish = true;
                    $id = $('#receive_id').val();
                   $('.remain_qty').each((i,v)=>{

                    if(parseInt($(v).text()) > 0){
                        Swal.fire({
                            icon : 'error',
                            title: 'Warning',
                            text : 'Scan ဖတ်ရန်ကျန်နေပါသေးသည်'
                        })
                        $finish = false;
                        return false;
                    }
                   })

                   if($finish)
                   {
                        $.ajax({
                            url : "/finish_goods/"+$id,
                            type: 'get',
                            success: function(res){
                                location.href = '/list';
                            }
                        })
                   }
                })
                }
                // console.log(Math.floor(2-1));
            })
        </script>
    @endpush
@endsection
