@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<!-- Bookings Header -->
<section class="bg-gradient-to-br from-blue-600 to-blue-700 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <nav class="text-blue-200 text-sm mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="mx-2">›</span>
                <span class="text-white">Booking Saya</span>
            </nav>
            
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Booking Saya</h1>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                Kelola semua booking futsal Anda dengan mudah
            </p>
            
            <div class="mt-6">
                <a href="{{ route('fields.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Booking Baru
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Bookings Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Filter Booking</h2>
                    <p class="text-gray-600 text-sm">{{ $bookings->total() }} booking ditemukan</p>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <!-- Status Filter -->
                    <select class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterBookings(this.value)">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    
                    <!-- Date Filter -->
                    <input type="date" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterByDate(this.value)">
                </div>
            </div>
        </div>

        @if($bookings->count() > 0)
            <!-- Bookings List -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Booking Info -->
                                <div class="flex-1">
                                    <div class="flex items-start space-x-4">
                                        <!-- Field Image/Icon -->
                                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                                <circle cx="10" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Booking Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $booking->field->name }}</h3>
                                                <span class="px-3 py-1 text-xs font-medium rounded-full flex-shrink-0
                                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                       ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7"/>
                                                    </svg>
                                                    {{ $booking->booking_code }}
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"/>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center mt-3 text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $booking->field->location }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Price & Actions -->
                                <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col lg:items-end space-y-3">
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->duration_hours }} jam</div>
                                    </div>
                                    
                                    <!-- Payment Status -->
                                    @if($booking->payment)
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            {{ $booking->payment->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                               ($booking->payment->status === 'pending_verification' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $booking->payment->status === 'verified' ? 'Pembayaran Verified' : 
                                               ($booking->payment->status === 'pending_verification' ? 'Menunggu Verifikasi' : 'Belum Bayar') }}
                                        </span>
                                    @endif
                                    
                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bookings.show', $booking->id) }}" 
                                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            Detail
                                        </a>
                                        
                                        @if($booking->status === 'pending' && $booking->payment && $booking->payment->status === 'pending')
                                            <a href="{{ route('payments.show', $booking->id) }}" 
                                               class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Bayar
                                            </a>
                                        @endif
                                        
                                        @if($booking->canBeCancelled())
                                            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl p-12 text-center shadow-lg">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-4">Belum Ada Booking</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Anda belum memiliki booking apapun. Yuk mulai booking lapangan futsal favorit Anda!
                </p>
                
                <div class="space-y-4">
                    <a href="{{ route('fields.index') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl transition-colors shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Lapangan
                    </a>
                    
                    <div class="text-sm text-gray-500">
                        atau <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">kembali ke dashboard</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
function filterBookings(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url;
}

function filterByDate(date) {
    const url = new URL(window.location);
    if (date) {
        url.searchParams.set('date', date);
    } else {
        url.searchParams.delete('date');
    }
    window.location = url;
}
</script>
@endpush
@endsection