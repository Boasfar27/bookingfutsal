<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is admin and redirect accordingly
            $user = Auth::user();
            if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto verify for simplicity
        ]);

        // Assign default 'user' role
        $user->assignRole('user');

        Auth::login($user);

        return redirect(route('dashboard'))
            ->with('success', 'Selamat datang di FutsalPro, ' . $user->name . '! Akun Anda berhasil dibuat.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'))
            ->with('success', 'Anda berhasil keluar. Sampai jumpa lagi!');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);

                // Check if user is admin and redirect accordingly
                if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
                    return redirect('/admin')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
                }

                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            // Check if user exists with this email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update existing user with Google ID
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                    'password' => Hash::make(uniqid()), // Random password since they'll use Google
                ]);

                // Assign default 'user' role
                $user->assignRole('user');
            }

            Auth::login($user);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang di FutsalPro, ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect(route('login'))
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}