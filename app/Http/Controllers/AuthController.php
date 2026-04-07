<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Allow login if user is an admin or belongs to 'TI' / 'Teknologi Informasi' unit
            $isAdmin = $user->hasAnyRole(['admin', 'super-admin', 'super admin', 'administrator']);
            $isTI = $user->workUnit && (stripos($user->workUnit->name, 'TI') !== false || stripos($user->workUnit->name, 'Teknologi Informasi') !== false);

            if (!$isAdmin && !$isTI) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Hanya pengguna dari Unit Kerja TI atau Administrator yang diizinkan untuk login.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
