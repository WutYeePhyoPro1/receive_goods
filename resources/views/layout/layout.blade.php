<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="__token" content="{{ csrf_token() }}">
    <title>Receive Goods</title>
    <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">
    <link rel="icon" href="{{ asset('image/background_img/package.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    {{-- <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.bootstrap3.min.css
    " rel="stylesheet">
    <link href="
    https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css
    " rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

</head>

<body>
    <div class="side_bar">
        <ul class="sidebar_body">
            <li class="sidebar_items" onclick="javascript:window.location.href='/home'">
                @if (request()->is('home') ||
                        request()->is('product_list*') ||
                        request()->is('finished_documents*') ||
                        request()->is('truck_list*') ||
                        request()->is('remove_list') ||
                        request()->is('shortage_list') ||
                        request()->is('detail_truck*') ||
                        request()->is('po_to_list*') ||
                        request()->is('detail_document*') ||
                        request()->is('detail_doc*'))
                    <div class=""
                        style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                    </div>
                @endif
                <div class="sidebar_text">
                    <i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span>
                </div>
            </li>

            @can('barcode-scan')
                <li class="sidebar_items" onclick="javascript:window.location.href='/car_info'">
                    @if (request()->is('receive_good*') || request()->is('car_info*') || request()->is('view_goods*'))
                        <div class=""
                            style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                        </div>
                    @endif
                    <div class="sidebar_text">
                        <i class='bx bxs-truck' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                        <span>Receive Goods</span>
                    </div>
                </li>
            @endcan

            <li class="sidebar_items" onclick="javascript:window.location.href='/list'">
                @if (request()->is('list'))
                    <div class=""
                        style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                    </div>
                @endif
                <div class="sidebar_text">
                    <i class='bx bx-list-ul' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                    <span>List</span>
                </div>
            </li>

            @can('user-management')
                <li class="sidebar_items relative" id="user_lay">
                    @if (request()->is('user*') ||
                            request()->is('role*') ||
                            request()->is('permission*') ||
                            request()->is('gate*') ||
                            request()->is('car_type*'))
                        <div class=""
                            style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                        </div>
                    @endif
                    <div class="sidebar_text">
                        <i class='bx bxs-user-rectangle' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                        <span>User</span>
                    </div>
                    <ul class=" rounded-lg shadow-lg w-full p-2 absolute user_div" style="">
                        @can('user-management')
                            <li class="p-2 mt-1 hover:bg-amber-500 {{ request()->is('user*') ? 'bg-amber-500' : '' }}"
                                onclick="javascript:window.location.href='/user'">User</li>
                        @endcan
                        @can('role-management')
                            <li class="p-2 mt-1 hover:bg-amber-500 {{ request()->is('role*') ? 'bg-amber-500' : '' }}"
                                onclick="javascript:window.location.href='/role'">Role</li>
                        @endcan
                        @can('permission-management')
                            <li class="p-2 mt-1 hover:bg-amber-500 {{ request()->is('permission*') ? 'bg-amber-500' : '' }}"
                                onclick="javascript:window.location.href='/permission'">Permission</li>
                            <li class="p-2 mt-1 hover:bg-amber-500 {{ request()->is('gate*') ? 'bg-amber-500' : '' }}"
                                onclick="javascript:window.location.href='/gate'">Gate</li>
                            <li class="p-2 mt-1 hover:bg-amber-500 {{ request()->is('car_type*') ? 'bg-amber-500' : '' }}"
                                onclick="javascript:window.location.href='/car_type'">Car Type</li>
                        @endcan
                    </ul>
                </li>
            @endcan

            <li class="sidebar_items" onclick="this.childNodes[1].click()">
                @if (dc_staff())
                    <a href="{{ asset('image/received_goods_user_guide_dc.pdf') }}" target="_blank" id="user_guide"
                        hidden></a>
                @else
                    <a href="{{ asset('image/received_good_user_guide.pdf') }}" target="_blank" id="user_guide"
                        hidden></a>
                @endif
                <div class="sidebar_text">
                    <i class='bx bxs-book-content' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                    <span>User's Guide</span>
                </div>
            </li>

            <li style="margin:50px 0;border-bottom:1px solid rgb(110, 109, 109)">
            </li>

            <li class="sidebar_items" style="margin-bottom:10px" onclick="$('#log_out').submit()">
                <div class="sidebar_text">
                    <i class='bx bx-log-out-circle' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                    <span>Log Out</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" id="log_out">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <div class="content pb-16">
        @yield('content')
    </div>
    <div class="footer flex justify-between">
        <div class="logo">
            <img src="{{ asset('image/background_img/finallogo.png') }}" alt="">
            <div class="logo_content">
                <span>PRO 1 Global Home Center</span>
            </div>
        </div>
        <div class="flexv whitespace-nowrap" style="line-height: 60px">
            <span class="mr-4"><i class='bx bxs-user-account mr-1' style="transform: translateY(2px)"></i> User :
                {{ getAuth()->name }}</span> |&nbsp;&nbsp;
            <span class="mr-4"><i class='bx bx-user-voice mr-1' style="transform: translateY(2px)"></i> Role :
                {{ getAuth()->roleName() }}</span> |&nbsp;&nbsp;
            <span class="mr-4 relative"><i class='bx bx-store-alt mr-1' style="transform: translateY(2px)"></i> Branch :
                <span
                    class=" {{ count(multi_br()) > 1 ? 'bg-amber-200 p-2 cursor-pointer rounded ch_br' : '' }} ">{{ getAuth()->branch->branch_name }}</span>
                @if (count(multi_br()) > 1)
                    <ul class="absolute max-h-48 w-32 bg-rose-600 hidden rounded-lg shadow-lg" id="change_br"
                        style="bottom:155%;left:45%;overflow-y:auto">
                        @foreach (multi_br() as $item)
                            @if (getAuth()->branch_id != $item->branch_id)
                                <li class="ps-2 bg-amber-200 hover:bg-white cursor-pointer py-0"
                                    onclick="javascript:window.location.href='/change_branch/{{ $item->branch_id }}'">
                                    {{ $item->branch->branch_name }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </span> |&nbsp;&nbsp;
            {{-- <span class="mr-4"><i class='bx bx-signal-5 mr-1' style="transform: translateY(2px)"></i> Server Link :
                   @if (isset($_SERVER['SERVER_ADDR']))
                        {{ $_SERVER['SERVER_ADDR'] }}
                   @else
                       server is not set.
                   @endif
               </span> |&nbsp;&nbsp; --}}
            <span class="mr-4"><i class='bx bx-server mr-1' style="transform: translateY(2px)"></i> IP Computer :
                {{ request()->ip() }}</span>
        </div>
    </div>
</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js" integrity="sha512-k2wo/BkbloaRU7gc/RkCekHr4IOVe10kYxJ/Q8dRPl7u3YshAQmg3WfZtIcseEk+nGBdK03fHBeLgXTxRmWCLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/js/all.min.js
        "></script>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js
        "></script>
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script> --}}

<!-- Local JS files -->
<script src="{{ asset('js/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('js/jsbarcode/JsBarcode.all.min.js') }}"></script>
<script src="{{ asset('js/selectize/selectize.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>

<script>
    $(document).ready(function(e) {
        var token = $("meta[name='__token']").attr('content');
        $(document).on('keypress', '#driver_phone', function(e) {
            let filter = true;

            if ($(this).val().length < 11) {
                if (e.keyCode >= 48 && e.keyCode <= 57) {
                    filter = true;
                } else {
                    filter = false;
                }
            } else {
                filter = false;
            }

            if (!filter) {
                e.preventDefault();
            }
        })

        $(document).on('input', '#truck_no', function(e) {
            $val = $(this).val();
            let truckType = $('#truck_type').val();
            const alertSpan = document.getElementById('truck_alert');

            if (!truckType) {
                if ($val.length >= 2 && e.originalEvent.data != null) {
                    alertSpan.classList.remove('hidden');
                    if ($val.length == 2) {
                        $val = $val + '-';
                        $(this).val($val);
                    }
                } else {
                    alertSpan.classList.add('hidden');
                }
            } else {
                alertSpan.classList.add('hidden');
                if (truckType != 4) {
                    if ($val.length == 2 && e.originalEvent.data != null) {
                        $val = $val + '-';
                        $(this).val($val);
                    }
                }
            }

            // if($val.length == 2 && e.originalEvent.data != null)
            // {
            //     alertSpan.classList.remove('hidden');
            //     $val    = $val + '-';
            //     $(this).val($val);
            // } else {
            //     alertSpan.classList.add('hidden');
            // }

            if ($val != '') {
                $('.car_auto').html('');
                $.ajax({
                    url: "{{ route('search_car') }}",
                    type: 'POST',
                    data: {
                        _token: token,
                        data: $val
                    },
                    befroeSend: function() {
                        $('.car_auto').html('');
                    },
                    success: function(res) {
                        $list = '';
                        for ($i = 0; $i < res.length; $i++) {
                            $list += `
                                        <li class="ps-4 py-1 cursor-pointer hover:bg-slate-100 car_lists truck_div">${res[$i].car_no}</li>
                                    `;
                        }
                        $('.car_auto').append($list);
                    }
                })
            } else {
                $('.car_auto').html('');
            }
        })

        $('#truck_type').on('change', function() {
            $('#truck_no').val('');
        });

        $(document).on('keypress', '#truck_no', function(e) {
            $val = $(this).val();
            var truckType = $('#truck_type').find('option:selected').data('name');
            if (truckType !== "Motorcycle" && $val.length > 6) {
                e.preventDefault();
            } else if (truckType == "Motorcycle" && $val.length > 9) {
                e.preventDefault();
            }
        })


        $(document).on('click', document, function(e) {
            $link = e.target.matches('.truck_div');
            if ($link) {
                $('.car_auto').attr('hidden', false);
            } else {
                $('.car_auto').attr('hidden', true);
            }
        })

        $(document).on('click', '.car_lists', function(e) {
            $val = $(this).text();

            $.ajax({
                url: "{{ route('get_car') }}",
                type: 'POST',
                data: {
                    _token: token,
                    data: $val
                },
                success: function(res) {
                    $('#truck_no').val(res.car_no);
                    $('#driver_name').val(res.driver_name);
                    $('#truck_type option').each((i, v) => {
                        $(v).attr('selected', false);
                        if ($(v).val() == res.car_type) {
                            $(v).prop('selected', true);
                        }
                    });
                    $('.car_auto').html('');
                }
            })
        })

        $(document).on('click', '.ch_br', function(e) {
            $('#change_br').toggle();
        })

        $(document).on('click', function(e) {
            if (!e.target.matches('.ch_br')) {
                $('#change_br').hide();
            }
        })
    })
</script>
@stack('js')

</html>
