<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('image/background_img/package.png') }}">
    <title>Receive Goods</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-amber-400">
    <main class="relative flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="relative z-10 grid w-full max-w-5xl overflow-hidden rounded-2xl border border-black/10 bg-white/10 shadow-2xl backdrop-blur-sm md:min-h-[520px] md:grid-cols-2">
            <div class="hidden min-h-[520px] overflow-hidden md:block">
                <img class="h-full w-full scale-125 object-cover object-center" src="{{ asset('image/background_img/goods_receive.webp') }}" alt="">
            </div>

            <div class="relative min-h-[520px] overflow-hidden">
                <img class="absolute inset-0 h-full w-full object-cover brightness-[.35]" src="{{ asset('image/background_img/goods_scan.jpg') }}" alt="">

                <form action="{{ route('login') }}" method="POST" class="relative z-10 flex h-full flex-col justify-center px-6 py-10 sm:px-10 lg:px-12">
                    @csrf

                    <div class="text-center">
                        <h1 class="text-2xl font-semibold text-white sm:text-3xl">Login Page</h1>
                        <div class="mt-4 min-h-5">
                            @error('employee_code')
                                <small class="font-medium text-red-300">credental do no match</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 space-y-6">
                        <div>
                            <label for="employee_code" class="mb-2 block text-sm font-medium text-white sm:text-base">Employee Number :</label>
                            <input type="text" name="employee_code" id="employee_code" class="h-11 w-full rounded-r-xl border border-white/20 bg-white px-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-200/40" placeholder="employee name..." value="">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-white sm:text-base">Password :</label>
                            <input type="password" id="password" name="password" class="h-11 w-full rounded-r-xl border border-white/20 bg-white px-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-200/40" placeholder="password..." value="">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="rounded-md bg-amber-300 px-6 py-3 text-sm font-bold text-slate-900 shadow-md transition hover:bg-amber-200 focus:outline-none focus:ring-4 focus:ring-amber-100">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>

        <img class="pointer-events-none absolute bottom-0 right-0 hidden w-32 select-none sm:block md:w-44 lg:w-60" src="{{ asset('image/background_img/forklift.png') }}" alt="">
        <img class="pointer-events-none absolute left-4 top-4 w-16 select-none sm:left-[15%] sm:top-[5%] sm:w-24 md:w-28" src="{{ asset('image/background_img/package.png') }}" alt="">
    </main>
</body>

</html>
