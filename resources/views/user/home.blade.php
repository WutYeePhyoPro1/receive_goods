@extends('layout.layout')

@section('content')
    <span class="">this is dashboard</span>
    @push('js')
        <script type="module">
            $(document).ready(function(){
                console.log('yes');
            })
        </script>
    @endpush
@endsection
