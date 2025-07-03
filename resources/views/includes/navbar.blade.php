<div class="w-full">
    <div class="sticky top-0 z-50 flex justify-between items-center py-3 px-4 lg:px-14 bg-gradient-to-r from-yellow-300 via-yellow-400 to-orange-500 shadow-md">
        <!-- Logo dan Search -->
        <div class="flex items-center gap-6 w-full">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/img/LogoEkraf.png') }}" alt="Logo" class="w-20">
            </a>

            <!-- Search Bar -->
            <div class="relative w-full max-w-md">
                <input type="text" placeholder="Cari..."
                    class="bg-gradient-to-r from-yellow-200 via-yellow-300 to-orange-200 text-green-900 placeholder-green-700 font-medium rounded-full px-4 py-2 pl-10 w-full text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-white border border-white">
                <span class="absolute inset-y-0 left-3 flex items-center">
                    <img src="{{ asset('assets/img/search.png') }}" alt="search" class="w-4 opacity-70">
                </span>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <div class="hidden lg:flex items-center gap-6 font-medium text-sm tracking-wide">
            <a href="{{ route('landing') }}" 
               class="{{ request()->routeIs('landing') ? 'text-white underline' : 'text-green-900 hover:text-white hover:underline' }}">
                HOME
            </a>
            <a href="{{ route('katalog') }}" 
               class="{{ request()->routeIs('katalog*') ? 'text-white underline' : 'text-green-900 hover:text-white hover:underline' }}">
                KATALOG
            </a>
            <a href="{{ route('artikel') }}" 
               class="{{ request()->routeIs('artikel*') ? 'text-white underline' : 'text-green-900 hover:text-white hover:underline' }}">
                ARTIKEL
            </a>
            <a href="{{ route('kontak') }}" 
               class="{{ request()->routeIs('kontak') ? 'text-white underline' : 'text-green-900 hover:text-white hover:underline' }}">
                KONTAK
            </a>
            
            @auth
                @if(auth()->user()->level_id === 1 || auth()->user()->level_id === 2)
                    <a href="{{ route('filament.admin.pages.dashboard') }}" 
                       class="bg-white text-orange-600 px-4 py-2 rounded-full hover:bg-orange-50 transition">
                        <i class="fas fa-tachometer-alt mr-1"></i>DASHBOARD
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-green-900 hover:text-white hover:underline">
                        <i class="fas fa-sign-out-alt mr-1"></i>LOGOUT
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-white text-orange-600 px-4 py-2 rounded-full hover:bg-orange-50 transition">
                    <i class="fas fa-sign-in-alt mr-1"></i>LOGIN
                </a>
            @endauth
        </div>

        <!-- Tombol menu (mode mobile) -->
        <button class="lg:hidden text-white text-2xl ml-4 focus:outline-none" id="menu-toggle">
            ☰
        </button>
    </div>

    <!-- Menu responsive (hidden by default) -->
    <div id="menu" class="lg:hidden hidden flex-col bg-white px-6 py-4 text-sm font-medium shadow-md">
        <a href="{{ route('landing') }}" 
           class="py-2 {{ request()->routeIs('landing') ? 'text-orange-600 font-semibold' : 'text-gray-700 hover:text-orange-600' }}">
            <i class="fas fa-home mr-2"></i>HOME
        </a>
        <a href="{{ route('katalog') }}" 
           class="py-2 {{ request()->routeIs('katalog*') ? 'text-orange-600 font-semibold' : 'text-gray-700 hover:text-orange-600' }}">
            <i class="fas fa-book mr-2"></i>KATALOG
        </a>
        <a href="{{ route('artikel') }}" 
           class="py-2 {{ request()->routeIs('artikel*') ? 'text-orange-600 font-semibold' : 'text-gray-700 hover:text-orange-600' }}">
            <i class="fas fa-newspaper mr-2"></i>ARTIKEL
        </a>
        <a href="{{ route('kontak') }}" 
           class="py-2 {{ request()->routeIs('kontak') ? 'text-orange-600 font-semibold' : 'text-gray-700 hover:text-orange-600' }}">
            <i class="fas fa-phone mr-2"></i>KONTAK
        </a>
        
        @auth
            @if(auth()->user()->level_id === 1 || auth()->user()->level_id === 2)
                <a href="{{ route('filament.admin.pages.dashboard') }}" 
                   class="py-2 text-orange-600 border-t border-gray-200 mt-2 pt-4">
                    <i class="fas fa-tachometer-alt mr-2"></i>DASHBOARD
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" 
                        class="w-full text-left py-2 text-red-600 hover:text-red-800">
                    <i class="fas fa-sign-out-alt mr-2"></i>LOGOUT
                </button>
            </form>
        @else
            <div class="border-t border-gray-200 mt-2 pt-4">
                <a href="{{ route('login') }}" 
                   class="block py-2 text-orange-600 hover:text-orange-800 font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>LOGIN
                </a>
            </div>
        @endauth
    </div>
</div>
