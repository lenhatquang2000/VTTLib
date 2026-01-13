<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClientLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!$request->user()->hasRole('visitor')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access denied. You do not have permission to access the client area.',
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }
}
