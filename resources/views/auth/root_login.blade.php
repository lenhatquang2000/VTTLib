<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Root Access - VTTLib</title>

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
            background-color: #0f172a;
            color: #f8fafc;
        }
        .login-card {
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
        }
    </style>
</head>

<body class="h-full antialiased font-sans">
    
    <!-- Header/Nav decoration -->
    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>

    <!-- Language Switcher -->
    <div class="absolute top-6 right-8 flex items-center space-x-4">
         <a href="{{ route('lang.switch', 'vi') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'vi' ? 'text-indigo-400' : 'text-slate-500 hover:text-slate-300' }} transition-colors uppercase">VI</a>
         <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
         <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'en' ? 'text-indigo-400' : 'text-slate-500 hover:text-slate-300' }} transition-colors uppercase">EN</a>
    </div>

    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-6">
                <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-xl shadow-indigo-900/20">
                    R
                </div>
            </div>
            <h2 class="text-center text-4xl font-black tracking-tight text-white font-display uppercase">
                ROOT <span class="text-indigo-400">ACCESS</span>
            </h2>
            <p class="mt-3 text-center text-sm text-slate-400 font-medium">
                {{ __('High-level system authorization terminal.') }}
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[420px]">
            <div class="login-card px-10 py-12 rounded-[3.5rem]">
                <form class="space-y-7" action="{{ route('root.login.store') }}" method="POST">
                    @csrf

                    <div class="space-y-2">
                        <label for="username" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] pl-1 block">
                            {{ __('System Identifier') }}
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-600 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            </span>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                class="block w-full rounded-2xl border border-slate-700 bg-slate-900/50 py-4 pl-12 pr-4 text-white text-sm font-medium outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all duration-200"
                                placeholder="{{ __('Enter Identifier') }}">
                        </div>
                        @error('username')
                            <p class="mt-2 text-[10px] text-rose-500 font-bold pl-1 uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] pl-1 block">
                            {{ __('Security Master Key') }}
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-600 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            </span>
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-2xl border border-slate-700 bg-slate-900/50 py-4 pl-12 pr-4 text-white text-sm font-medium outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-[10px] text-rose-500 font-bold pl-1 uppercase tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center items-center rounded-2xl bg-indigo-500 px-4 py-4 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-indigo-900/40 hover:bg-indigo-400 transition-all duration-300 transform active:scale-95">
                            {{ __('Authorized Execution') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </button>
                    </div>
                </form>

                <div class="mt-12 flex flex-col items-center">
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em] mb-4 text-center">Kernel Version 6.4.2 Stable</p>
                    <div class="flex space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-700 animate-pulse"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>