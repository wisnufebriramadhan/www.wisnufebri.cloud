<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show admin login page
     */
    public function showLogin()
    {
        // Generate captcha setiap load halaman
        session([
            'captcha_code' => strtoupper(Str::random(5))
        ]);

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        // 🔑 Key rate limit berbasis IP
        $key = 'admin-login:' . $request->ip();

        // 🚨 Cek rate limit
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ]);
        }

        // Hit limiter (1 attempt)
        RateLimiter::hit($key, 60);

        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'captcha'  => 'required',
        ]);

        // 🔐 Validasi captcha
        if ($request->captcha !== session('captcha_code')) {
            session([
                'captcha_code' => strtoupper(Str::random(5))
            ]);

            return back()->withErrors([
                'captcha' => 'Captcha salah'
            ]);
        }

        // 🔐 Attempt login
        if (! Auth::attempt($request->only('email', 'password'))) {
            session([
                'captcha_code' => strtoupper(Str::random(5))
            ]);

            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        // ✅ Login sukses → reset limiter
        RateLimiter::clear($key);

        $request->session()->regenerate();

        $user = Auth::user();

        // 🔒 Pastikan admin
        if (! $user || ! $user->isAdmin()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Anda bukan admin'
            ]);
        }

        // Bersihkan captcha
        session()->forget('captcha_code');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
