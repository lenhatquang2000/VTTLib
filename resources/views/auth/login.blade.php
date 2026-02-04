<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }} - VTTLib</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        input:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
    </style>
</head>

<body class="h-full antialiased font-sans">
    
    <!-- Language Switcher -->
    <div class="absolute top-6 right-8 flex items-center space-x-4">
         <a href="{{ route('lang.switch', 'vi') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'vi' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }} transition-colors uppercase">VI</a>
         <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
         <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }} transition-colors uppercase">EN</a>
    </div>

    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}" class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-100">
                    V
                </a>
            </div>
            <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900 font-display">
                {{ __('Welcome Back') }}
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                {{ __('Sign in to access your library account.') }}
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="login-card px-8 py-10 rounded-[2.5rem]">
                <form class="space-y-6" action="{{ route('client.login.store') }}" method="POST">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="username" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] pl-1 block">
                            {{ __('Username') }}
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-slate-900 text-sm font-medium outline-none border-transparent focus:border-indigo-500 focus:bg-white transition-all duration-200"
                                placeholder="{{ __('Tên tài khoản') }}">
                        </div>
                        @error('username')
                            <p class="mt-1.5 text-[11px] text-rose-500 font-bold pl-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] pl-1 block">
                            {{ __('Password') }}
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-slate-900 text-sm font-medium outline-none border-transparent focus:border-indigo-500 focus:bg-white transition-all duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-[11px] text-rose-500 font-bold pl-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center items-center rounded-2xl bg-indigo-600 px-4 py-4 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:shadow-indigo-200 transition-all duration-300 transform active:scale-95">
                            {{ __('Sign In') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </form>

                <div class="mt-10 flex flex-col items-center">
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-4">Secured by VTTLib</p>
                    <div class="flex space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                    </div>
                </div>
            </div>
            
            <p class="mt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} VTTLib System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>