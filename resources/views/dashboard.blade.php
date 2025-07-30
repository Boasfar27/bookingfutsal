@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-br from-blue-600 to-blue-700 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Selamat datang, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                Kelola booking futsal Anda dan nikmati pengalaman bermain yang tak terlupakan
            </p>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        <p class="text-gray-600 text-sm">Total Booking</p>
                    </div>
                </div>
            </div>

            <!-- Active Bookings -->
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeBookings }}</p>
                        <p class="text-gray-600 text-sm">Booking Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Completed Bookings -->
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $completedBookings }}</p>
                        <p class="text-gray-600 text-sm">Selesai</p>
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        <p class="text-gray-600 text-sm">Total Pengeluaran</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('fields.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 p-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-blue-900">Cari Lapangan</h3>
                                    <p class="text-blue-700 text-sm">Temukan lapangan futsal terbaik</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('bookings.index') }}" class="group bg-gradient-to-br from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 p-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-yellow-900">Booking Saya</h3>
                                    <p class="text-yellow-700 text-sm">Lihat semua booking Anda</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Jadwal Mendatang</h2>
                        <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
                    </div>

                    @if($upcomingBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                                    <circle cx="10" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $booking->field->name }}</h3>
                                                <p class="text-gray-600 text-sm">{{ $booking->field->location }}</p>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="text-blue-600 text-sm font-medium">
                                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                                    </span>
                                                    <span class="text-gray-500 text-sm">
                                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <p class="text-gray-900 font-bold mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7"/>
                            </svg>
                            <p class="text-gray-500 mb-4">Belum ada booking mendatang</p>
                            <a href="{{ route('fields.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                                Booking Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Aktivitas Terbaru</h2>
                    
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">Booking {{ $booking->field->name }}</p>
                                        <p class="text-gray-600 text-sm">{{ $booking->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-6">
                    <div class="text-center">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h3 class="font-bold text-gray-900 mb-1">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.show') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Lihat Profil
                        </a>
                    </div>
                </div>

                <!-- Favorite Fields -->
                @if($favoriteFields->count() > 0)
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="font-bold text-gray-900 mb-4">Lapangan Favorit</h3>
                        <div class="space-y-3">
                            @foreach($favoriteFields as $field)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                            <circle cx="10" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $field->name }}</h4>
                                        <p class="text-gray-600 text-xs">{{ $field->bookings_count }}x booking</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection