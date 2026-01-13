<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Admin Dashboard') }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 transition-all duration-300">
        <div class="h-16 flex items-center px-6 bg-slate-950">
            <span class="text-xl font-bold tracking-wider">VTTLib <span
                    class="text-indigo-400 text-sm font-normal">{{ __('Admin') }}</span></span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @foreach(Auth::user()->getSidebarTabs() as $tab)
                <a href="{{ $tab->route_name != '#' ? route($tab->route_name) : '#' }}"
                    class="flex items-center px-4 py-3 {{ ($tab->route_name != '#' && request()->routeIs($tab->route_name . '*')) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} rounded-lg group transition">
                    {!! $tab->icon !!}
                    {{ __($tab->name) }}
                </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-4 py-2 text-sm text-slate-400 hover:text-white group transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h2>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 mr-2 border-r pr-4 border-gray-200">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="text-xs font-bold {{ app()->getLocale() == 'vi' ? 'text-indigo-600' : 'text-gray-400' }} hover:text-indigo-500 transition">VI</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-gray-400' }} hover:text-indigo-500 transition">EN</a>
                </div>
                <div class="relative">
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm"
                    title="{{ Auth::user()->name ?? '' }}">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @yield('content')
        </main>
    </div>
</body>

</html>