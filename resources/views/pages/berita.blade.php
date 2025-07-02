@extends('layouts.app')
@section('title', 'EKRAF KUNINGAN')
@section('content')
    <div class="relative h-44 md:h-15 bg-center bg-cover flex items-center"
        style="background-image: url('{{ asset('assets/img/BGKontak.png') }}');">
        <div class="bg-black bg-opacity-50 w-full h-full absolute top-0 left-0"></div>
        <div class="relative z-10 text-white text-left px-6 md:px-12">
            <p class="mt-2 text-base md:text-lg">
                <a href="/" class="hover:underline">Home</a> > Artikel
            </p>
            <h1 class="text-3xl md:text-5xl font-bold">ARTIKEL</h1>
        </div>
    </div>
    <!-- Swiper Slider -->
    <div class="swiper mySwiper mt-9">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <a href="{{ route('artikels.show', $banner->artikel->slug) }}" class="block">
                        <div class="relative flex flex-col justify-end p-3 h-64 rounded-xl overflow-hidden"
                            style="background-image: url('{{ asset('storage/' . $banner->artikel->thumbnail) }}'); background-size: cover; background-position: center;">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="relative z-10 mb-3 pl-2">
                                <div class="bg-primary text-white text-xs rounded-lg w-fit px-3 py-1 font-normal">
                                    {{ $banner->artikel->artikelkategori->title }}
                                </div>
                                <p class="text-2xl font-semibold text-white mt-1">{{ $banner->artikel->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <img src="{{ asset('storage/' . $banner->artikel->author->avatar) }}" alt="author"
                                        class="w-5 h-5 rounded-full">
                                    <p class="text-white text-xs">{{ $banner->artikel->author->name }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Berita Unggulan -->
    <div class="flex flex-col px-4 md:px-10 lg:px-14 mt-10">
        <div class="flex flex-col md:flex-row justify-between items-center w-full mb-6">
            <div class="font-bold text-2xl text-center md:text-left">
                <p>Berita Unggulan</p>
                <p>Untuk Kamu</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($featureds as $featured)
                <a href="{{ route('artikels.show', $featured->slug) }}">
                    <div class="bg-white border border-slate-200 p-3 rounded-xl shadow-sm hover:shadow-md hover:border-primary transition duration-300 ease-in-out"
                        style="height: 100%">

                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="Thumbnail"
                                class="w-full h-40 object-cover rounded-md">
                        </div>

                        <span
                            class="bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full mb-2 inline-block">
                            {{ $featured->artikelkategori->title ?? 'Kategori' }}
                        </span>

                        <p class="font-bold text-base mb-1 text-gray-900 line-clamp-2">
                            {{ $featured->title }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($featured->created_at)->format('d F Y') }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>


    <!-- Berita Terbaru -->
    <div class="flex flex-col px-4 md:px-10 lg:px-14 mt-10">
        <div class="flex flex-col md:flex-row justify-between items-center w-full mb-6">
            <div class="font-bold text-2xl text-center md:text-left">
                <p>Berita Terbaru</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @if (isset($artikels[0]))
                <!-- Berita Utama -->
                <div class="col-span-12 lg:col-span-7">
                    <a href="{{ route('artikels.show', $artikels[0]->slug) }}"
                        class="block bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $artikels[0]->thumbnail) }}" alt="berita utama"
                                class="w-full h-64 object-cover rounded-t-xl">
                            <span
                                class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                {{ $artikels[0]->artikelkategori->title ?? 'Kategori' }}
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-xl mb-2 text-gray-900 leading-snug line-clamp-2">
                                {{ $artikels[0]->title }}
                            </p>
                            <p class="text-slate-600 text-sm mb-2 leading-relaxed line-clamp-2">
                                {!! Str::limit(strip_tags($artikels[0]->body), 90) !!}
                            </p>
                            <p class="text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($artikels[0]->created_at)->format('d F Y') }}
                            </p>
                        </div>
                    </a>
                </div>
            @endif

            <!-- List Berita Lainnya -->
            <div class="col-span-12 lg:col-span-5 flex flex-col gap-4">
                @foreach ($artikels->skip(1)->take(3) as $artikel)
                    <a href="{{ route('artikels.show', $artikel->slug) }}"
                        class="flex gap-3 border border-slate-200 p-3 rounded-xl shadow-sm hover:border-primary hover:shadow-md transition duration-300 bg-white">
                        <div class="relative w-1/3">
                            <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="berita"
                                class="rounded-xl h-24 w-full object-cover">
                            <span
                                class="absolute top-1 left-1 bg-orange-500 text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow-sm">
                                {{ $artikel->artikelkategori->title ?? 'Kategori' }}
                            </span>
                        </div>
                        <div class="w-2/3">
                            <p class="font-semibold text-sm text-gray-900 leading-tight mb-1 line-clamp-2">
                                {{ $artikel->title }}
                            </p>
                            <p class="text-slate-500 text-xs leading-snug mb-1 line-clamp-2">
                                {!! Str::limit(strip_tags($artikel->body), 70) !!}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($artikel->created_at)->format('d F Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>



    <!-- Author -->
    <div class="flex flex-col px-4 md:px-10 lg:px-14 mt-12">
        <div class="flex flex-col md:flex-row justify-between items-center w-full mb-8">
            <div class="font-bold text-2xl text-center md:text-left leading-tight">
                <p>Kenali Author</p>
                <p>Terbaik Dari Kami</p>
            </div>
            <a href="register.html"
                class="bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded-full text-white font-semibold mt-4 md:mt-0 shadow hover:shadow-md transition">
                Gabung Menjadi Author
            </a>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach ($authors as $author)
                <a href="{{ route('author.show', $author->username) }}"
                    class="border border-slate-200 bg-white p-6 rounded-2xl flex flex-col items-center text-center shadow-sm hover:shadow-md hover:border-primary transition duration-300 ease-in-out">
                    <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}"
                        class="rounded-full w-24 h-24 object-cover mb-4 border-2 border-primary shadow">
                    <p class="font-semibold text-lg text-gray-800">{{ $author->name }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $author->artikel->count() }} Berita</p>
                </a>
            @endforeach
        </div>
    </div>


    <!-- Pilihan Author -->
    <div class="flex flex-col px-4 md:px-10 lg:px-14 mt-10 mb-10">
        <div class="flex flex-col md:flex-row justify-between items-center w-full mb-6">
            <div class="font-bold text-2xl text-center md:text-left">
                <p>Pilihan Author</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($artikels as $artikel)
                <a href="{{ route('artikels.show', $artikel->slug) }}">
                    <div
                        class="bg-white border border-slate-200 p-3 rounded-xl shadow-sm hover:shadow-md hover:border-primary transition duration-300 ease-in-out h-full overflow-hidden">

                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="{{ $artikel->title }}"
                                class="w-full h-40 object-cover rounded-md">
                        </div>

                        <span
                            class="bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full mb-2 inline-block">
                            {{ $artikel->artikelkategori->title ?? 'Kategori' }}
                        </span>

                        <p class="font-bold text-base mb-1 text-gray-900 line-clamp-2">
                            {{ $artikel->title }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($artikel->created_at)->format('d F Y') }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection
