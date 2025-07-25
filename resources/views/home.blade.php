@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Booking Lapangan Futsal 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-400">
                    Jadi Mudah!
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Platform booking lapangan futsal online terdepan di Indonesia. 
                Cari lapangan, pilih waktu, bayar mudah - semua dalam satu aplikasi!
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('fields.index') }}" class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Lihat Lapangan
                </a>
                @guest
                    <a href="{{ route('register') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-900 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300">
                        Daftar Sekarang
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-900 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300">
                        Dashboard Saya
                    </a>
                @endguest
            </div>
        </div>
    </div>
    
    <!-- Hero Image/Illustration -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-400 to-green-600 h-64 flex items-center justify-center">
                <div class="text-center text-white">
                    <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                        <circle cx="10" cy="12" r="3" fill="currentColor"/>
                    </svg>
                    <p class="text-2xl font-bold">Lapangan Premium</p>
                    <p class="text-green-100">Fasilitas lengkap & berkualitas</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kenapa Pilih FutsalPro?
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Kami menyediakan pengalaman booking lapangan futsal yang mudah, cepat, dan terpercaya
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Booking Real-time</h3>
                <p class="text-gray-600">
                    Lihat slot waktu yang tersedia secara real-time dan booking langsung tanpa menunggu konfirmasi manual
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pembayaran Mudah</h3>
                <p class="text-gray-600">
                    Upload bukti transfer dengan mudah dan dapatkan konfirmasi pembayaran dari admin dengan cepat
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lapangan Berkualitas</h3>
                <p class="text-gray-600">
                    Pilihan lapangan futsal dengan fasilitas lengkap dan standar internasional di lokasi strategis
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Cara Booking di FutsalPro
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Proses booking yang simpel dalam 3 langkah mudah
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pilih Lapangan</h3>
                <p class="text-gray-600">
                    Browse lapangan futsal yang tersedia, lihat foto, fasilitas, dan lokasi yang sesuai dengan kebutuhan Anda
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Tentukan Waktu</h3>
                <p class="text-gray-600">
                    Pilih tanggal dan jam bermain yang diinginkan. Sistem akan menampilkan slot waktu yang tersedia
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bayar & Main</h3>
                <p class="text-gray-600">
                    Lakukan pembayaran via transfer bank, upload bukti pembayaran, dan siap bermain!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_fields'] }}+</div>
                <div class="text-blue-200">Lapangan Partner</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_bookings'] }}+</div>
                <div class="text-blue-200">Total Booking</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['active_users'] }}+</div>
                <div class="text-blue-200">User Aktif</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">4.8</div>
                <div class="text-blue-200">Rating Rata-rata</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Siap Bermain Futsal Hari Ini?
        </h2>
        <p class="text-xl text-gray-300 mb-8">
            Bergabunglah dengan ribuan pemain futsal yang sudah mempercayai FutsalPro untuk booking lapangan mereka
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('fields.index') }}" class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                Mulai Booking Sekarang
            </a>
            @guest
                <a href="{{ route('register') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-gray-900 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300">
                    Daftar Gratis
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection 