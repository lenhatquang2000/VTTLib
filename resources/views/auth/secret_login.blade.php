<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Restricted Access') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,700|inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bg-grid {
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>

<body class="h-full bg-gray-950 text-gray-200 antialiased overflow-hidden relative font-sans">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-grid"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent"></div>

    <!-- Ambient Glow -->
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none">
    </div>

    <!-- Language Switcher -->
    <div class="absolute top-4 right-4 z-20 flex items-center space-x-3 font-mono text-xs">
         <a href="{{ route('lang.switch', 'vi') }}" class="{{ app()->getLocale() == 'vi' ? 'text-indigo-400 border-b border-indigo-400' : 'text-gray-500 hover:text-gray-300' }} transition">VI</a>
         <span class="text-gray-800">|</span>
         <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-indigo-400 border-b border-indigo-400' : 'text-gray-500 hover:text-gray-300' }} transition">EN</a>
    </div>

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 relative z-10">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm text-center">
            <div
                class="inline-flex items-center justify-center p-3 rounded-full bg-indigo-500/10 text-indigo-400 mb-6 ring-1 ring-indigo-500/20 shadow-[0_0_15px_rgba(99,102,241,0.15)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h2 class="mt-2 text-center text-3xl font-bold tracking-tight text-white font-mono uppercase">TOP_SECRET_LEVEL_4</h2>
            <p class="mt-2 text-center text-sm text-gray-500 font-mono">
                {{ __('Authorized Personnel Only. Identify yourself.') }}
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="glass-panel p-8 rounded-2xl shadow-2xl shadow-black/50">
                <form class="space-y-6" action="{{ route('agent.login.store') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email"
                            class="block text-xs font-mono font-medium leading-6 text-gray-400 uppercase tracking-wider">{{ __('Agent Email') }}</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-lg border-0 bg-gray-900/50 py-2.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 placeholder-gray-600 transition-all duration-200"
                                placeholder="agent@vttlib.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-xs font-mono font-medium leading-6 text-gray-400 uppercase tracking-wider">{{ __('Access Code') }}</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border-0 bg-gray-900/50 py-2.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 placeholder-gray-600 transition-all duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-300 hover:shadow-[0_0_20px_rgba(79,70,229,0.3)] font-mono tracking-wide uppercase">
                            {{ __('Authenticate') }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-gray-800 pt-6">
                    <p class="text-center text-xs text-gray-600 font-mono uppercase">
                        {{ __('System ID') }}: <span class="text-indigo-400/70">{{ Str::upper(Str::random(12)) }}</span>
                        <br>
                        {{ __('Connection') }}: <span class="text-green-500/70 animate-pulse">{{ __('SECURE ENCRYPTED') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>