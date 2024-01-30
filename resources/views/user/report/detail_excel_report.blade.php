<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>receive_goods</title>
</head>
<style>
    body{
        margin: 0;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .wrapper{
        width: 100%;
    }

    .real_tb{
        width: 100%;
        border-collapse: collapse;
    }
    .real_tb, td, .t_head {
    border: 1px solid rgb(0,0,0);
    }

    .t_head{
        height: 40px
    }

    .real_tb td{
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
        opacity: 0.2;
    }

</style>
<body >

        <div class="wrapper">
            {{-- <div class="" style="width: 95%;padding:20px;display:flex;justify-content:space-between">
                    <span class="" >Document No : <b class="" >{{ $reg->document_no ?? '' }}</b></span>
                    <span class="" >Truck No : <b>{{ $driver->truck_no ?? '' }}</b></span>
                    <span class=" " >Truck Type : <b>{{ $driver->truck->truck_name ?? '' }}</b></span>
                    <span class=" " >Arrived At : <b>{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></span>
                    <span class=" " >Unload Duration : <b>{{ $driver->duration ?? '' }}</b></span>
            </div> --}}
            @if ($action == 'print')
                <img id="back_img" src="{{ public_path('storage/background_img/finallogo.png') }}" alt="">
            @endif
            <table class="real_tb" style="width:  100%;padding:20px 0">
                <tr class="">
                    <th class="t_head">Document No :</th>
                    <th class="t_head">Truck No :</th>
                    <th class="t_head">Bar Truck Type :</th>
                    <th class="t_head">Arrived At :</th>
                    <th class="t_head">Unload Duration :</th>
                </tr>
                <tr>
                    <th class="t_head"> <b class="" >{{ $reg->document_no ?? '' }}</b></th>
                    <th class="t_head"><b>{{ $driver->truck_no ?? '' }}</b></th>
                    <th class="t_head"><b>{{ $driver->truck->truck_name ?? '' }}</b></th>
                    <th class="t_head"><b>{{ $driver->start_date .' '. date('g:i A',strtotime($driver->start_time)) }}</b></th>
                    <th class="t_head"><b>{{ $driver->duration ?? '' }}</b></th>
                </tr>
            </table>
            <table class="real_tb" style="width: 100%;">
                <thead>

                    <tr class="">
                            <th class="t_head" style="min-width:20px"></th>
                            <th class="t_head">Document No</th>
                            <th class="t_head">Bar Code</th>
                            <th class="t_head">Product Name</th>
                            <th class="t_head">Unloaded Qty</th>
                    </tr>
                </thead>
                <tbody>


                            <?php
                            $i = 0;
                        ?>
                        @foreach ($document as $item)
                            @if (count(get_truck_product($item,$driver->id)) > 0)
                                @foreach (get_truck_product($item,$driver->id) as $key=>$tem)
                                <tr class="h-10">
                                    @if ($key == 0)

                                            <td class="">{{ $i+1 }}</td>
                                            <td class="">{{ getDocument($item)->document_no }}</td>
                                        @else
                                            <td class=""></td>
                                            <td class=""></td>
                                        @endif
                                        <td class="">{{ $tem->product->bar_code }}</td>
                                        <td class="">{{ $tem->product->supplier_name }}</td>
                                    <td class="">{{ $tem->scanned_qty }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        <?php
                        $i++;
                    ?>
                        @endforeach
                </tbody>
            </table>
        </div>
</body>
</html>
