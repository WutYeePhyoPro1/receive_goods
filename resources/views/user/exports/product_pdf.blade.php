<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receive Goods(product)</title>
    <style>
    body{
        overflow-x: hidden
    }

    table{
        width: 100%;
        border-collapse: collapse;
    }
    table, td, th {
    border: 1px solid rgb(0,0,0);
    }

    table th{
        height: 40px
    }

    table td{
        padding: 10px 0;
        text-align: center;
    }

    #back_img{
        position: fixed;
        object-fit: cover;
        width: 90%;
        top: 30%;
        bottom: 0;
        left: 10%;
        right: 0;
        opacity: 0.15;
    }
    </style>
</head>
<body>
    <div class="" style="background-image: url('{{ public_path('storage/background_img/finallogo.png') }}') ;background-attachment: fixed;background-position: center;background-size: 600px 300px;background-repeat: no-repeat;opacity:0.1">

        {{-- <img id="back_img" src="{{ public_path('image/background_img/finallogo.png') }}" alt=""> --}}
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th style="padding: 0 5px">No</th>
                    <th style="padding: 0 10px">Product Code</th>
                    <th>Product Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index=>$item)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $item->bar_code }}</td>
                        <td>{{ $item->supplier_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
