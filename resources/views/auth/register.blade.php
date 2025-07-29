@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                    <circle cx="10" cy="12" r="3" fill="currentColor"/>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Buat akun baru
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Masuk di sini
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('register.store') }}" method="POST">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="name" 
                       name="name" 
                       type="text" 
                       value="{{ old('name') }}"
                       required 
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('name') border-red-500 @enderror"
                       placeholder="Nama lengkap Anda">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" 
                       name="email" 
                       type="email" 
                       value="{{ old('email') }}"
                       required 
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                       placeholder="Alamat email">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative">
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required 
                           class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                           placeholder="Password minimal 8 karakter">
                    <button type="button" 
                            id="toggle-password"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="eye-open" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           required 
                           class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                           placeholder="Ulangi password">
                    <button type="button" 
                            id="toggle-password-confirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="eye-open-conf" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed-conf" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms & Conditions -->
            <div class="flex items-center">
                <input id="terms" 
                       name="terms" 
                       type="checkbox" 
                       required
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('terms') border-red-500 @enderror">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    Saya setuju dengan 
                    <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a> 
                    dan 
                    <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                </label>
            </div>
            @error('terms')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Daftar Sekarang
                </button>
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">Atau</span>
                </div>
            </div>

            <!-- Google Register Button -->
            <div>
                <a href="{{ route('google.login') }}" 
                   class="group relative w-full flex justify-center items-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Daftar dengan Google
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        // Password field
        const toggleButton = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        
        toggleButton.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        });

        // Password confirmation field
        const toggleButtonConf = document.getElementById('toggle-password-confirmation');
        const passwordInputConf = document.getElementById('password_confirmation');
        const eyeOpenConf = document.getElementById('eye-open-conf');
        const eyeClosedConf = document.getElementById('eye-closed-conf');
        
        toggleButtonConf.addEventListener('click', function() {
            if (passwordInputConf.type === 'password') {
                passwordInputConf.type = 'text';
                eyeOpenConf.classList.remove('hidden');
                eyeClosedConf.classList.add('hidden');
            } else {
                passwordInputConf.type = 'password';
                eyeOpenConf.classList.add('hidden');
                eyeClosedConf.classList.remove('hidden');
            }
        });
    });
</script>
@endpush 