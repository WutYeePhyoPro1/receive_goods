<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Goods Receive</title>
    <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>
<body>
        <div class="side_bar">
            <ul class="sidebar_body">
                <li class="sidebar_items" onclick="javascript:window.location.href='/home'">
                    @if (request()->is('home'))
                        <div class="" style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                        </div>
                    @endif
                    <div class="sidebar_text" >
                        <i class='bx bxs-dashboard'></i>
                        <span>Dashboard</span>
                    </div>
                </li>

                <li class="sidebar_items" onclick="javascript:window.location.href='/list'">
                    @if (request()->is('list'))
                        <div class="" style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                        </div>
                    @endif
                    <div class="sidebar_text">
                        <i class='bx bx-list-ul' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                        <span>List</span>
                    </div>
                </li>

                <li class="sidebar_items" onclick="javascript:window.location.href='/car_info'">
                    @if (request()->is('receive_good'))
                    <div class="" style="height:40px;background-color: rgb(255, 255, 255);width: 5px;position: absolute;top: 4px;left: -10px;">
                    </div>
                @endif
                    <div class="sidebar_text">
                        <i class='bx bxs-truck' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                        <span>Receive Goods</span>
                    </div>
                </li>

                <li style="margin:50px 0;border-bottom:1px solid rgb(110, 109, 109)">

                </li>

                <li class="sidebar_items" style="margin-bottom:10px">
                    <div class="sidebar_text" onclick="$('#log_out').submit()">
                        <i class='bx bx-log-out-circle' style="font-size: 2rem;margin: 10px 0 0 10px;"></i>
                        <span>Log Out</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" id="log_out">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer flex justify-between">
            <div class="logo">
                <img src="{{ asset('image/background_img/finallogo.png') }}" alt="">
                <div class="logo_content">
                    <span>Company&nbsp;&nbsp; : &nbsp;&nbsp;PRO 1 Global Home Center</span>
                </div>
            </div>
            <div class="flexv whitespace-nowrap" style="line-height: 60px">
                <span class="mr-4"><i class='bx bxs-user-account mr-1' style="transform: translateY(2px)"></i> User : {{ getAuth()->name }}</span> |&nbsp;&nbsp;
                <span class="mr-4"><i class='bx bx-signal-5 mr-1' style="transform: translateY(2px)"></i> Server Link :
                   @if(isset($_SERVER['SERVER_ADDR']))
                       Server IP Address: {{ $_SERVER['SERVER_ADDR'] }}
                   @else
                       server is not set.
                   @endif
               </span> |&nbsp;&nbsp;
                 <span class="mr-4"><i class='bx bx-server mr-1' style="transform: translateY(2px)"></i> IP Computer : {{ request()->ip()}}</span>
            </div>
        </div>
</body>
    <script type="module">
        $(document).ready(function(e){
        })
    </script>
        @stack('js')

</html>
