<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-600 tracking-tight">VTTLib</a>
                <div class="hidden md:flex ml-10 space-x-8">
                    <a href="#"
                        class="text-gray-900 border-b-2 border-indigo-500 px-1 pt-1 pb-4 text-sm font-medium">{{ __('Home') }}</a>
                    <a href="#"
                        class="text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent px-1 pt-1 pb-4 text-sm font-medium">{{ __('Books') }}</a>
                    <a href="#"
                        class="text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent px-1 pt-1 pb-4 text-sm font-medium">{{ __('Authors') }}</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 mr-2 border-r pr-4 border-gray-200">
                    <a href="{{ route('lang.switch', 'vi') }}" class="text-xs font-bold {{ app()->getLocale() == 'vi' ? 'text-indigo-600' : 'text-gray-400' }} hover:text-indigo-500 transition">VI</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-gray-400' }} hover:text-indigo-500 transition">EN</a>
                </div>
                <button class="text-gray-500 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Hi, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-600 text-sm font-medium">{{ __('Logout') }}</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium">{{ __('Login') }}</a>
                    <a href="#"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20">Sign
                        Up</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} VTTLib. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-indigo-500"><span class="sr-only">Facebook</span><svg
                        class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                            clip-rule="evenodd" />
                    </svg></a>
                <a href="#" class="text-gray-400 hover:text-indigo-500"><span class="sr-only">Twitter</span><svg
                        class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg></a>
            </div>
        </div>
    </footer>
</body>

</html>