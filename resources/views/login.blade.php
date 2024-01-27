<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('image/background_img/package.png') }}">
    <title>Receive Goods</title>
</head>
<style>
    body {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 650px;
        min-height: 100vh;
        background-color: rgb(250, 164, 72);
        position: relative
    }

    .bg_img_div {
        width: 100%;
        overflow: hidden
    }

    .bg_img {
        object-fit: fill;
        width: 180%;
        height: 100%;
        transform: translateX(-22%);
    }

    .div_container {
        z-index: 1;
        width: 60%;
        height: 55%;
        border: solid 1px rgb(0, 0, 0, 0.1);
        box-shadow: 2px 2px 4px rgba(226, 226, 226, 0.1), -2px -2px 4px rgba(133, 133, 133, 0.1);
        display: grid;
        grid-template-columns: 50% 50%;
        border-radius: 20px;
        overflow: hidden;
    }

    .auth_div {
        /* background-image: url('/image/background_img/goods_scan.jpg');
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    border-left: 1px solid rgb(187, 187, 187);
    filter: brightness(50%); */
        position: relative;
    }

    .back_img>img {
        position: absolute;
        height: 100%;
        width: 100%;
        object-fit: cover;
        filter: brightness(30%)
    }

    .auth_item {
        /*  child_div       */
        padding-top: 20px;
        text-align: center;
        font-size: 1.4rem;
        color: white;
        filter: brightness(100%) !important;
    }

    .inpt_item {
        color: white;
        filter: brightness(100%) !important;
    }

    .inp_item>input:focus {
        outline: none;
    }

    #forklift {
        position: absolute;
        width: 20%;
        right: 0;
        bottom: 0;
    }

    #package {
        position: absolute;
        width: 10%;
        left: 15%;
        top: 5%;
    }

    #login_btn {
        padding: 10px 18px;
        background-color: rgb(255, 188, 62);
        border-radius: 4px;
        font-weight: 700;
        cursor: pointer;
    }

    #login_btn:hover{
        background-color: rgb(255, 201, 100);

    }
</style>

<body>
    <div class="div_container">
        <div class="bg_img_div" style="">
            <img class="bg_img" src="{{ asset('image/background_img/goods_receive.webp') }}">
        </div>
        <div class="auth_div">
            <div class="back_img">
                <img src="{{ asset('image/background_img/goods_scan.jpg') }}" alt="">
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="auth_item" style="">
                    <span>Login Page</span>
                    <div class="" style="margin-top: 20px">
                        @error('employee_code')
                            <small style="color: red;">credental do no match</small>
                        @enderror
                    </div>
                </div>

                <div class="inp_item" style="position: relative;margin: 30px 0 0 50px;display:flex;flex-direction:column">
                    <label for="employee_code" style="color: white;margin-bottom:10px">Employee Number :</label>
                    <input type="text" name="employee_code" id="employee_code" style="width: 70%;height:37px;border-radius:0 10px 10px 0;padding:0 0 0 5px;"
                        placeholder="employee name..." value="">
                </div>
                <div class="inp_item" style="position: relative;margin: 30px 0 0 50px;display:flex;flex-direction:column">
                    <label for="password" style="color: white;margin-bottom:10px">Password :</label>
                    <input type="password" id="password" name="password" style="width: 70%;height:37px;border-radius:0 10px 10px 0;padding:0 0 0 5px"
                        placeholder="password..." value="">
                </div>
                <div class="" style="position: relative;margin: 30px 0 0 60%;">
                    <button type="submit" id="login_btn" style="">LOGIN</button>
                </div>
            </form>
        </div>
    </div>

    <img id="forklift" src="{{ asset('image/background_img/forklift.png') }}" style="background-color: transparent;"
        alt="">
    <img id="package" src="{{ asset('image/background_img/package.png') }}" style="background-color: transparent;"
        alt="">

</body>

</html>
