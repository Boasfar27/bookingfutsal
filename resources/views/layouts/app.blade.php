<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FutsalPro') }} - @yield('title', 'Platform Booking Lapangan Futsal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                    <circle cx="10" cy="12" r="3" fill="currentColor"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">FutsalPro</span>
                        </a>
                    </div>



                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                            Beranda
                        </a>
                        <a href="{{ route('fields.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('fields.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            Lapangan
                        </a>

                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Daftar
                            </a>
                        @else
                            <div class="relative group">
                                <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Booking Saya</a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="md:hidden hidden pb-4">
                    <div class="space-y-1">
                        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Beranda</a>
                        <a href="{{ route('fields.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Lapangan</a>
                        
                        @guest
                            <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Masuk</a>
                            <a href="{{ route('register') }}" class="block bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-base font-medium">Daftar</a>
                        @else
                            <div class="border-t border-gray-200 pt-4">
                                <div class="px-3 py-2 text-sm font-medium text-gray-500">{{ Auth::user()->name }}</div>
                                <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                                <a href="{{ route('bookings.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Booking Saya</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        @endguest
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
    
    <!-- Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html> 