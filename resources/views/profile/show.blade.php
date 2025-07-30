@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<!-- Profile Header -->
<section class="bg-gradient-to-br from-blue-600 to-blue-700 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <nav class="text-blue-200 text-sm mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="mx-2">›</span>
                <span class="text-white">Profil Saya</span>
            </nav>
            
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-white shadow-lg">
            @else
                <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                    <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
            @endif
            
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $user->name }}</h1>
            <p class="text-blue-100 text-lg">{{ $user->email }}</p>
            
            <div class="mt-6">
                <a href="{{ route('profile.edit') }}" class="bg-white hover:bg-gray-100 text-blue-700 font-semibold px-6 py-3 rounded-xl transition-colors shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Profile Info -->
            <div class="lg:col-span-2">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Informasi Personal</h2>
                        <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <p class="text-gray-900 font-semibold">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                            <p class="text-gray-900 font-semibold">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Status Email</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Bergabung Sejak</label>
                            <p class="text-gray-900 font-semibold">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Pengaturan Akun</h2>
                    
                    <div class="space-y-4">
                        <!-- Change Password -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Ubah Password</h3>
                                    <p class="text-gray-600 text-sm">Update password akun Anda</p>
                                </div>
                            </div>
                            <button onclick="openPasswordModal()" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                Ubah
                            </button>
                        </div>

                        <!-- Google Account -->
                        @if($user->google_id)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Akun Google</h3>
                                        <p class="text-gray-600 text-sm">Terhubung dengan Google</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Terhubung
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl p-6 shadow-lg mb-6">
                    <h3 class="font-bold text-gray-900 mb-4">Statistik Saya</h3>
                    
                    @php
                        $userBookings = \App\Models\Booking::where('user_id', $user->id);
                        $totalBookings = $userBookings->count();
                        $activeBookings = $userBookings->whereIn('status', ['pending', 'confirmed'])->where('booking_date', '>=', now()->toDateString())->count();
                        $totalSpent = \App\Models\Payment::whereHas('booking', function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })->where('status', 'verified')->sum('amount');
                    @endphp
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Booking</span>
                            <span class="font-bold text-gray-900">{{ $totalBookings }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Booking Aktif</span>
                            <span class="font-bold text-gray-900">{{ $activeBookings }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Pengeluaran</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Dashboard</span>
                        </a>
                        
                        <a href="{{ route('bookings.index') }}" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition-colors group">
                            <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Booking Saya</span>
                        </a>
                        
                        <a href="{{ route('fields.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Cari Lapangan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closePasswordModal(event)">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Ubah Password</h3>
            <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
            </div>
            
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordModal').classList.add('flex');
}

function closePasswordModal(event) {
    if (!event || event.target === event.currentTarget) {
        document.getElementById('passwordModal').classList.add('hidden');
        document.getElementById('passwordModal').classList.remove('flex');
    }
}
</script>
@endpush
@endsection