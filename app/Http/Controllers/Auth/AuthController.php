<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended($this->getDashboardUrl(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        // Check if user has too many login attempts (Max 5 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan masuk. Silakan coba lagi dalam $seconds detik.");
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            }

            // Update last login
            $user->update(['last_login_at' => now()]);

            RateLimiter::clear($throttleKey);

            return redirect()->intended($this->getDashboardUrl($user));
        }

        RateLimiter::hit($throttleKey, 60); // Lock for 60 seconds after 5 fails

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Get user's role-specific dashboard URL
     */
    protected function getDashboardUrl($user)
    {
        return match ($user->role?->name) {
            'admin' => route('admin.dashboard'),
            'pelapor' => route('reporter.dashboard'),
            'teknisi' => route('technician.dashboard'),
            'supervisor' => route('admin.dashboard'),
            default => route('login'),
        };
    }
}
