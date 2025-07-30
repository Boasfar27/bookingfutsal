@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<!-- Edit Profile Header -->
<section class="bg-gradient-to-br from-blue-600 to-blue-700 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <nav class="text-blue-200 text-sm mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="mx-2">›</span>
                <a href="{{ route('profile.show') }}" class="hover:text-white transition-colors">Profil Saya</a>
                <span class="mx-2">›</span>
                <span class="text-white">Edit Profil</span>
            </nav>
            
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Edit Profil</h1>
            <p class="text-blue-100 text-lg">Perbarui informasi profil Anda</p>
        </div>
    </div>
</section>

<!-- Edit Profile Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <!-- Form Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Personal</h2>
                </div>
                
                <!-- Form Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Avatar Upload -->
                        <div class="lg:col-span-1">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    @if($user->avatar)
                                        <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg">
                                    @else
                                        <div id="avatar-preview" class="w-32 h-32 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center border-4 border-gray-200 shadow-lg">
                                            <span class="text-white text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    
                                    <label for="avatar-input" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full cursor-pointer shadow-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </label>
                                    
                                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-4">
                                    Klik ikon kamera untuk mengubah foto profil
                                </p>
                                
                                @error('avatar')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Form Fields -->
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Phone (optional) -->
                                <div class="md:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon (Opsional)</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('phone') border-red-500 @enderror"
                                           placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Account Status Info -->
                                <div class="md:col-span-2">
                                    <div class="bg-blue-50 p-4 rounded-xl">
                                        <h4 class="font-medium text-blue-900 mb-2">Status Akun</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-blue-700">Email</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-blue-700">Bergabung</span>
                                                <span class="text-blue-900 font-medium">{{ $user->created_at->format('d M Y') }}</span>
                                            </div>
                                            @if($user->google_id)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-blue-700">Google Account</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Terhubung
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            Batal
                        </a>
                        
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors font-medium shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Change Password Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
            </div>
            
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">
                        <!-- Current Password -->
                        <div class="md:col-span-2">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini *</label>
                            <input type="password" id="current_password" name="current_password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru *</label>
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="md:col-span-2">
                            <div class="bg-yellow-50 p-4 rounded-xl">
                                <h4 class="font-medium text-yellow-900 mb-2">Syarat Password:</h4>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>• Minimal 8 karakter</li>
                                    <li>• Kombinasi huruf dan angka</li>
                                    <li>• Disarankan menggunakan karakter khusus</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            
            // Create new img element or update existing one
            if (preview.tagName === 'DIV') {
                // Replace div with img
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg';
                preview.parentNode.replaceChild(img, preview);
            } else {
                // Update existing img
                preview.src = e.target.result;
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection