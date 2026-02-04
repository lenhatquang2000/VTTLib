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
            },
            darkMode: 'class'
        }
    </script>
    <style>
        body {
            @apply bg-slate-50 text-slate-900 transition-colors duration-300;
        }
        .dark body {
            @apply bg-slate-950 text-slate-100;
        }
        .login-card {
            @apply bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl transition-all duration-300;
        }
        input:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
    </style>
</head>

<body class="h-full antialiased font-sans">
    
    <!-- Header/Nav decoration -->
    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-600"></div>

    <!-- Language Switcher -->
    <!-- Theme & Language Sync -->
    <div class="absolute top-6 right-8 flex items-center space-x-6">
         <!-- Language Switcher -->
         <div class="flex items-center space-x-3">
             <a href="{{ route('lang.switch', 'vi') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'vi' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }} transition-colors uppercase">VI</a>
             <span class="w-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
             <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black tracking-widest {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }} transition-colors uppercase">EN</a>
         </div>

         <span class="w-px h-4 bg-slate-200 dark:bg-slate-700"></span>

         <!-- Theme Toggle -->
         <button onclick="toggleTheme()" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm">
            <svg id="sun-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.95 16.95l.707.707M7.05 7.05l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            <svg id="moon-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
         </button>
    </div>

    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-6">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-100">
                    V
                </div>
            </div>
            <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900 dark:text-white font-display">
                {{ __('Hệ thống Quản lý') }}
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400">
                {{ __('Nhập thông tin định danh để tiếp tục.') }}
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="login-card px-8 py-10 rounded-[2.5rem]">
                <form class="space-y-6" action="{{ route('agent.login.store') }}" method="POST">
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
                                class="block w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 py-3.5 pl-11 pr-4 text-slate-900 dark:text-slate-100 text-sm font-medium outline-none border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 transition-all duration-200"
                                placeholder="{{ __('Tên tài khoản') }}">
                        </div>
                        @error('username')
                            <p class="mt-1.5 text-[11px] text-rose-500 font-bold pl-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] pl-1 block">
                            {{ __('Mật khẩu') }}
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 py-3.5 pl-11 pr-4 text-slate-900 dark:text-slate-100 text-sm font-medium outline-none border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 transition-all duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-[11px] text-rose-500 font-bold pl-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center items-center rounded-2xl bg-indigo-600 px-4 py-4 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:shadow-indigo-200 transition-all duration-300 transform active:scale-95">
                            {{ __('Đăng nhập hệ thống') }}
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
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcons();
        }

        function updateIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.getElementById('sun-icon').classList.toggle('hidden', !isDark);
            document.getElementById('moon-icon').classList.toggle('hidden', isDark);
        }

        // Init theme
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        updateIcons();
    </script>
</body>
</html>