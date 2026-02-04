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
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!$request->user()->hasRole('root')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Access denied. High-level authorization required.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('root.users.index'));
        }

        throw ValidationException::withMessages([
            'username' => 'Thông tin đăng danh hoặc mật khẩu không chính xác.',
        ]);
    }
}
