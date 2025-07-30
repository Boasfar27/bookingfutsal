<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FutsalPro') }} - @yield('title', 'Platform Booking Lapangan Futsal Terbaik')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Alpine.js untuk interaktif -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    
    <!-- Custom styles untuk color scheme biru-kuning -->
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-yellow: #fbbf24;
            --dark-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --accent-yellow: #f59e0b;
            --bg-gray: #f8fafc;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 9.95c5.16-.21 9-4.4 9-9.95V7L12 2z"/>
                                    <circle cx="12" cy="13" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12 9v8M9 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-800 bg-clip-text text-transparent">FutsalPro</span>
                                <div class="text-xs text-gray-500 font-medium">Platform Booking Futsal</div>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                            Beranda
                        </a>
                        <a href="{{ route('fields.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('fields.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                            Sewa Lapangan
                        </a>
                        <a href="#" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-all duration-200">
                            Tentang Kami
                        </a>
                        <a href="#" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-all duration-200">
                            Kontak
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="hidden md:flex items-center space-x-3">
                        @guest
                            <a href="{{ route('login') }}" class="text-blue-700 hover:text-blue-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Daftar
                            </a>
                        @else
                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-3 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-blue-700 font-semibold">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                                        <a href="/admin" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Admin Panel
                                        </a>
                                    @endif
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"></path>
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profil
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7"></path>
                                        </svg>
                                        Booking Saya
                                    </a>
                                    <div class="border-t border-gray-100 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <!-- Mobile Navigation -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 right-0 bg-white border-t border-gray-200 shadow-lg lg:hidden z-40">
                            <div class="px-4 pt-4 pb-6 space-y-2">
                                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg text-base font-semibold transition-colors {{ request()->routeIs('home') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    Beranda
                                </a>
                                <a href="{{ route('fields.index') }}" class="block px-4 py-3 rounded-lg text-base font-semibold transition-colors {{ request()->routeIs('fields.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    Sewa Lapangan
                                </a>
                                <a href="#" class="block px-4 py-3 rounded-lg text-base font-semibold text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                                    Tentang Kami
                                </a>
                                <a href="#" class="block px-4 py-3 rounded-lg text-base font-semibold text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                                    Kontak
                                </a>
                                
                                @guest
                                    <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                                        <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg text-base font-semibold text-blue-700 hover:bg-blue-50 transition-colors">
                                            Masuk
                                        </a>
                                        <a href="{{ route('register') }}" class="block px-4 py-3 rounded-lg text-base font-semibold bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-center">
                                            Daftar
                                        </a>
                                    </div>
                                @else
                                    <div class="border-t border-gray-200 mt-4 pt-4">
                                        <div class="flex items-center px-4 py-3 mb-3 bg-blue-50 rounded-lg">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-base font-semibold text-blue-700">{{ Auth::user()->name }}</div>
                                                <div class="text-sm text-blue-600">{{ Auth::user()->email }}</div>
                                            </div>
                                        </div>
                                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                                            <a href="/admin" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">Admin Panel</a>
                                        @endif
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">Dashboard</a>
                                        <a href="#" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">Profil</a>
                                        <a href="#" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">Booking Saya</a>
                                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-base font-medium text-red-600 hover:bg-red-50 transition-colors">
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mx-4 mt-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mx-4 mt-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                    <circle cx="10" cy="12" r="3" fill="currentColor"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold">FutsalPro</span>
                        </div>
                        <p class="text-gray-400 mb-4">Platform booking lapangan futsal online terdepan di Indonesia. Booking mudah, bermain seru!</p>
                        <div class="flex space-x-4">
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="{{ route('fields.index') }}" class="text-gray-400 hover:text-white transition-colors">Daftar Lapangan</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cara Booking</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Bantuan</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Kontak Kami</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Pusat Bantuan</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} FutsalPro. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

                    @stack('scripts')
</body>
</html> 