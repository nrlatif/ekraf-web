<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ekraf') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        {{-- NAVBAR --}}
        <div class="w-full">
            <div class="sticky top-0 z-50 flex justify-between py-5 px-4 lg:px-14 bg-white shadow-sm">
                <div class="flex gap-10 w-full">
                    <!-- Logo dan Menu -->
                    <div class="flex items-center justify-between w-full lg:w-auto">
                        <!-- Logo -->
                        <a href="{{ url('/') }}">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-8 lg:w-10">
                                <p class="text-lg lg:text-xl font-bold">Kuningan Kreatif Galery</p>
                            </div>
                        </a>
                        <button class="lg:hidden text-primary text-2xl focus:outline-none" id="menu-toggle">
                            ☰
                        </button>
                    </div>

                    <!-- Menu Navigasi -->
                    <div id="menu"
                        class="hidden lg:flex flex-col lg:flex-row lg:items-center lg:gap-10 w-full lg:w-auto mt-5 lg:mt-0">
                        <ul class="flex flex-col lg:flex-row items-start lg:items-center gap-4 font-medium text-base w-full lg:w-auto">
                            <li><a href="{{ url('/') }}" class="text-primary hover:text-gray-600">Beranda</a></li>
                            <li><a href="{{ url('/gayahidup') }}" class="hover:text-primary">Gaya Hidup</a></li>
                            <li><a href="{{ url('/olahraga') }}" class="hover:text-primary">Olahraga</a></li>
                            <li><a href="{{ url('/kesehatan') }}" class="hover:text-primary">Kesehatan</a></li>
                            <li><a href="{{ url('/politik') }}" class="hover:text-primary">Politik</a></li>
                            <li><a href="{{ url('/pariwisata') }}" class="hover:text-primary">Pariwisata</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="/public/asset/js/swiper.js"></script>
    </body>
</html>
