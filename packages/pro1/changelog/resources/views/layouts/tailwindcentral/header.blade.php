<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>System Change Log</title>
        <meta charseet="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}"/>

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


        <!-- Lightbox2 css1 js1  -->
        <link href="{{ asset('vendor/pro1/changelog/assets/libs/fancybox/fancybox.css') }}" type="text/css" rel="stylesheet"/>
        

        <!-- custom css css1 -->
        <link href="{{ asset('vendor/pro1/changelog/assets/dist/css/style.css') }}" rel="stylesheet" type="text/css"/>

        @yield('css')
    </head>
    <body>
