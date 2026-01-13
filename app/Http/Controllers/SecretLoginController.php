<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SecretLoginController extends Controller
{
    public function create()
    {
        return view('auth.secret_login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!$request->user()->hasRole('admin')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access denied. Authorized personnel only.',
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended('/topsecret/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
