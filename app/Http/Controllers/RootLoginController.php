<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RootLoginController extends Controller
{
    public function create()
    {
        return view('auth.root_login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!$request->user()->hasRole('root')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access denied. High-level authorization required.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('root.users.index'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match root records.',
        ]);
    }
}
