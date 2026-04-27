<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!$request->user()->hasRole('visitor')) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Access denied. You do not have permission to access the client area.',
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Verify login via VTTU external API
     */
    public function verifyLoginByUsernameAndToken(Request $request): RedirectResponse
    {
        $username = $request->query('username');
        $token = $request->query('token');

        if (!$username || !$token) {
            return redirect('https://info.vttu.edu.vn/')->with('error', 'Thiếu thông tin xác thực.');
        }

        // Kiểm tra nếu user đã login với đúng username
        if (Auth::check() && Auth::user()->username === $username) {
            if (!Auth::user()->hasRole('visitor')) {
                return redirect('/topsecret/dashboard');
            }
            return redirect('/');
        }

        // Gọi API xác thực của VTTU
        $apiUrl = 'https://info.vttu.edu.vn/api/verify_token.php';
        
        $response = Http::withoutVerifying()->get($apiUrl, [
            'username' => $username,
            'token' => $token,
        ]);

        // Kiểm tra response
        if ($response->successful() && strtolower(trim($response->body())) === 'ok') {
            $user = User::where('username', $username)->first();
            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();

                // Redirect based on role
                if (!$user->hasRole('visitor')) {
                    return redirect('/topsecret/dashboard');
                }
            
                return redirect('/');
            }

            return redirect('https://info.vttu.edu.vn/')->with('error', 'Tài khoản không tồn tại trong hệ thống.');
        }

        return redirect('https://info.vttu.edu.vn/')->with('error', 'Xác thực thất bại từ hệ thống xác thực.');
    }
}
