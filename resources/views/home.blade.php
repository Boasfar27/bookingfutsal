@extends('layouts.app')

@section('title', 'Platform Booking Futsal Terbaik di Indonesia')

@section('content')
<!-- Hero Section - Mirip ayo.co.id -->
<section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="15" cy="15" r="1"/><circle cx="45" cy="15" r="1"/><circle cx="15" cy="45" r="1"/><circle cx="45" cy="45" r="1"/></g></svg></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center bg-yellow-500/20 rounded-full px-4 py-2 mb-8 backdrop-blur-sm border border-yellow-400/30">
                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-yellow-100 font-semibold text-sm">#1 Platform Booking Futsal Terpercaya</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                Super Sport<br/>
                <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent">
                    Community App
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-4xl mx-auto leading-relaxed">
                Platform all-in-one untuk sewa lapangan, cari lawan sparring, atau cari kawan main bareng. 
                <span class="text-yellow-300 font-semibold">Olahraga makin mudah dan menyenangkan!</span>
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('fields.index') }}" class="group bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-yellow-500/25">
                    <span class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Temukan Lapangan
                    </span>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:bg-white hover:text-blue-800 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 hover:scale-105 shadow-lg">
                        Daftar Gratis
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:bg-white hover:text-blue-800 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 hover:scale-105 shadow-lg">
                        Dashboard Saya
                    </a>
                @endguest
            </div>

            <!-- Features Quick Access -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                            <circle cx="10" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Sewa Lapangan</h3>
                    <p class="text-blue-200 text-sm">Booking lapangan futsal berkualitas dengan mudah dan cepat</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Main Bareng</h3>
                    <p class="text-blue-200 text-sm">Cari kawan seperjuangan untuk main futsal bareng</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Sparring</h3>
                    <p class="text-blue-200 text-sm">Tantang tim lain dan asah kemampuan bermain futsal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Bottom -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" class="w-full h-20 fill-white">
            <path d="M0,120 C480,50 960,50 1440,120 L1440,120 L0,120 Z"></path>
        </svg>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-blue-700 mb-2">{{ $stats['total_fields'] }}+</div>
                <div class="text-gray-600 font-medium">Lapangan Partner</div>
            </div>
            
            <div class="group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-yellow-600 mb-2">{{ $stats['total_bookings'] }}+</div>
                <div class="text-gray-600 font-medium">Total Booking</div>
            </div>
            
            <div class="group">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">{{ $stats['active_users'] }}+</div>
                <div class="text-gray-600 font-medium">User Aktif</div>
            </div>
            
            <div class="group">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2">4.8</div>
                <div class="text-gray-600 font-medium">Rating Rata-rata</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Kenapa Pilih <span class="bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">FutsalPro</span>?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Dengan FutsalPro, pengalaman booking futsal Anda akan lebih mudah, cepat, dan menyenangkan
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Booking Real-time</h3>
                <p class="text-gray-600 leading-relaxed">Sistem booking real-time yang memastikan slot yang Anda pilih benar-benar tersedia. Tidak ada double booking!</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pembayaran Mudah</h3>
                <p class="text-gray-600 leading-relaxed">Upload bukti transfer dengan mudah. Sistem verifikasi otomatis untuk konfirmasi booking yang lebih cepat.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lapangan Berkualitas</h3>
                <p class="text-gray-600 leading-relaxed">Semua lapangan mitra telah melalui verifikasi kualitas. Fasilitas lengkap dan standar internasional.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Support 24/7</h3>
                <p class="text-gray-600 leading-relaxed">Tim support kami siap membantu Anda 24/7. Ada masalah? Kami akan selesaikan dengan cepat.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lokasi Strategis</h3>
                <p class="text-gray-600 leading-relaxed">Lapangan tersebar di lokasi strategis dengan akses mudah. Parkir luas dan transportasi umum terdekat.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Laporan & Statistik</h3>
                <p class="text-gray-600 leading-relaxed">Track aktivitas futsal Anda dengan laporan dan statistik lengkap. Pantau progress dan pencapaian Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Cara <span class="bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">Booking</span> di FutsalPro
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Hanya 3 langkah mudah untuk booking lapangan futsal impian Anda
            </p>
        </div>

        <!-- Steps -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <!-- Step 1 -->
            <div class="text-center group">
                <div class="relative mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pilih Lapangan</h3>
                <p class="text-gray-600 leading-relaxed">Cari dan pilih lapangan futsal sesuai lokasi, harga, dan fasilitas yang Anda inginkan</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center group">
                <div class="relative mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pilih Waktu</h3>
                <p class="text-gray-600 leading-relaxed">Tentukan tanggal dan jam bermain sesuai jadwal Anda. Lihat ketersediaan secara real-time</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center group">
                <div class="relative mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-400 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bayar & Main</h3>
                <p class="text-gray-600 leading-relaxed">Upload bukti pembayaran, konfirmasi booking, dan siap bermain futsal dengan seru!</p>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-16">
            <a href="{{ route('fields.index') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Mulai Booking Sekarang
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="py-20 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Apa Kata <span class="text-yellow-400">Kawan FutsalPro</span>?
            </h2>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Ribuan pemain futsal sudah merasakan kemudahan booking di FutsalPro
            </p>
        </div>

        <!-- Testimonials -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">AH</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Ahmad Hidayat</h4>
                        <p class="text-blue-200 text-sm">Tim Garuda FC</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-blue-100 leading-relaxed italic">
                    "FutsalPro membuat booking lapangan jadi super mudah! Tim kami sekarang rutin main setiap minggu tanpa ribet cari lapangan."
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">SR</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Sari Rahayu</h4>
                        <p class="text-blue-200 text-sm">Tim Putri Nusantara</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-blue-100 leading-relaxed italic">
                    "Sebagai tim putri, kami butuh lapangan yang bersih dan aman. FutsalPro selalu memberikan rekomendasi lapangan terbaik!"
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">BW</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Budi Wicaksono</h4>
                        <p class="text-blue-200 text-sm">Pemilik Lapangan</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-blue-100 leading-relaxed italic">
                    "Sejak bergabung dengan FutsalPro, lapangan saya jadi lebih ramai. Sistem booking-nya sangat membantu mengelola jadwal."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-yellow-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Siap Mulai Petualangan Futsal Anda?
        </h2>
        <p class="text-xl text-yellow-100 mb-10 leading-relaxed">
            Bergabunglah dengan ribuan pemain futsal lainnya dan nikmati pengalaman booking yang tak terlupakan!
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="bg-white hover:bg-gray-100 text-yellow-600 font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Daftar Gratis Sekarang
                </a>
                <a href="{{ route('fields.index') }}" class="bg-yellow-700 hover:bg-yellow-800 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Lihat Lapangan
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="bg-white hover:bg-gray-100 text-yellow-600 font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Dashboard Saya
                </a>
                <a href="{{ route('fields.index') }}" class="bg-yellow-700 hover:bg-yellow-800 text-white font-bold px-8 py-4 rounded-xl text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Booking Sekarang
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection