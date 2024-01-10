@extends('layout.layout')

@section('content')
<div class="w-[30%] min-h-80 rounded-xl shadow-2xl  mx-auto mt-10" style="background-color: rgb(255, 255, 255,0.2)">
        <span class="">this is dashboard</span>

    </div>
    @push('js')
        <script type="module">
            $(document).ready(function(){
                console.log('yes');
            })
        </script>
    @endpush
@endsection
