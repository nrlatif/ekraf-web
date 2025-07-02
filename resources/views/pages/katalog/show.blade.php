@extends('layouts.app')
@section('title', $katalog->title)

@section('content')
    <!-- Banner -->
    <div class="relative h-44 md:h-64 bg-center bg-cover flex items-center"
        style="background-image: url('{{ asset('assets/img/Katalog.png') }}');">
        <div class="bg-black bg-opacity-50 w-full h-full absolute top-0 left-0"></div>
        <div class="relative z-10 text-white text-left px-6 md:px-12">
            <p class="mt-2 text-base md:text-lg">
                <a href="/" class="hover:underline">Home</a> > <a href="/katalog" class="hover:underline">Katalog</a> >
                {{ $katalog->title }}
            </p>
            <h1 class="text-3xl md:text-5xl font-bold">{{ $katalog->title }}</h1>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="max-w-6xl mx-auto py-10 px-6">
        <div class="grid md:grid-cols-2 gap-8 mb-8 items-start">
            <div class="bg-white rounded-2xl p-6 shadow border text-gray-700">
                <h2 class="text-lg font-bold text-orange-600 mb-2">{{ $katalog->title }}</h2>
                <p class="text-sm leading-relaxed mb-6">
                    {!! $katalog->content !!}
                </p>

                <h3 class="text-md font-bold text-orange-600 mb-1 mt-4">Harga</h3>
                <p class="text-sm mb-4">
                    {{ $katalog->harga ? 'Rp ' . number_format($katalog->harga, 0, ',', '.') . ' / set' : '-' }}</p>

                <h3 class="text-orange-500 font-bold text-md mb-2">Lain-lain</h3>
                <ul class="text-gray-700 text-sm list-disc ml-5">
                    @if ($katalog->no_hp)
                        <li>WhatsApp: +62{{ $katalog->no_hp }}</li>
                    @endif
                    @if ($katalog->instagram)
                        <li>Instagram: {{ $katalog->instagram }}</li>
                    @endif
                </ul>
            </div>

            <div>
                <img src="{{ asset('storage/' . $katalog->produk) }}" alt="{{ $katalog->title }}"
                    class="rounded-2xl shadow w-full object-cover">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="https://wa.me/62{{ $katalog->no_hp }}" target="_blank"
                class="flex items-center justify-center bg-white border rounded-xl p-4 shadow hover:shadow-md transition">
                <i class="fas fa-phone-alt text-orange-500 text-xl mr-2"></i>
                <span class="text-sm font-medium">Contact Person</span>
            </a>

            @php
                $marketplaceLink = null;
                $marketplaceLabel = null;
                $marketplaceIcon = null;

                if ($katalog->shopee) {
                    $marketplaceLink = $katalog->shopee;
                    $marketplaceLabel = 'Shopee';
                    $marketplaceIcon = 'fas fa-store text-orange-500';
                } elseif ($katalog->tokopedia) {
                    $marketplaceLink = $katalog->tokopedia;
                    $marketplaceLabel = 'Tokopedia';
                    $marketplaceIcon = 'fas fa-shopping-cart text-green-500';
                } elseif ($katalog->lazada) {
                    $marketplaceLink = $katalog->lazada;
                    $marketplaceLabel = 'Lazada';
                    $marketplaceIcon = 'fas fa-shopping-basket text-purple-600';
                }
            @endphp

            @if ($marketplaceLink)
                <a href="{{ $marketplaceLink }}" target="_blank"
                    class="flex items-center justify-center bg-white border rounded-xl p-4 shadow hover:shadow-md transition">
                    <i class="{{ $marketplaceIcon }} text-xl mr-2"></i>
                    <span class="text-sm font-medium">{{ $marketplaceLabel }}</span>
                </a>
            @endif
        </div>



    </div>

    <!-- Produk Lainnya -->
    <section class="max-w-7xl mx-auto pb-12 px-6">
        <h2 class="text-center text-orange-500 font-semibold text-md mb-8">Produk Dari Lainnya</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
            @foreach ($others as $kat)
                <a href="{{ route('katalog.show', $kat->slug) }}">
                    <div
                        class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition transform hover:scale-105 duration-300">
                        <img src="{{ asset('storage/' . $kat->produk) }}" alt="{{ $kat->title }}"
                            class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-base font-bold text-orange-600 mb-1">{{ $kat->title }}</h3>
                            <p class="text-xs text-gray-500 mb-2">
                                {{ $kat->harga ? 'Rp ' . number_format($kat->harga, 0, ',', '.') : '' }}</p>
                            <p class="text-xs text-gray-600 mb-3">Produk yang dijual UMKM Itu</p>
                            <span class="inline-block bg-gray-100 text-[10px] px-2 py-1 rounded-full">
                                {{ $kat->subSektor->title ?? '-' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
