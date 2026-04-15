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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configure Tailwind with custom config
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
            plugins: [],
        }
    </script>
    <style>
        @import url('https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700');
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Force remove overflow-hidden from parent container */
        .flex-1.flex.flex-col.min-w-0 {
            overflow: visible !important;
        }
        
        /* Force main element to have limited height for scrolling */
        main {
            height: calc(100vh - 4rem) !important; /* Subtract header height */
            max-height: calc(100vh - 4rem) !important;
        }

        /* Admin Component Classes */
        .card-admin {
            @apply bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800;
        }

        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-900;
        }

        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 dark:focus:ring-offset-slate-900;
        }

        .btn-danger {
            @apply inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-slate-900;
        }

        .input-field {
            @apply w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-colors duration-200;
        }

        .text-blue-400 {
            @apply text-blue-500 dark:text-blue-400;
        }

        .text-green-400 {
            @apply text-green-500 dark:text-green-400;
        }

        .text-gray-400 {
            @apply text-slate-500 dark:text-slate-400;
        }

        .text-red-400 {
            @apply text-red-500 dark:text-red-400;
        }

        .text-yellow-400 {
            @apply text-yellow-500 dark:text-yellow-400;
        }

        .text-purple-400 {
            @apply text-purple-500 dark:text-purple-400;
        }

        .bg-blue-400 {
            @apply bg-blue-500 dark:bg-blue-400;
        }

        .bg-green-400 {
            @apply bg-green-500 dark:bg-green-400;
        }

        .bg-gray-400 {
            @apply bg-slate-500 dark:bg-slate-400;
        }

        .bg-red-400 {
            @apply bg-red-500 dark:bg-red-400;
        }

        .bg-yellow-400 {
            @apply bg-yellow-500 dark:bg-yellow-400;
        }

        .bg-purple-400 {
            @apply bg-purple-500 dark:bg-purple-400;
        }

        .bg-blue-900\/30 {
            @apply bg-blue-900 dark:bg-blue-900/30;
        }

        .bg-green-900\/30 {
            @apply bg-green-900 dark:bg-green-900/30;
        }

        .bg-gray-900\/30 {
            @apply bg-slate-900 dark:bg-slate-900/30;
        }

        .bg-red-900\/30 {
            @apply bg-red-900 dark:bg-red-900/30;
        }

        .bg-yellow-900\/30 {
            @apply bg-yellow-900 dark:bg-yellow-900/30;
        }

        .bg-purple-900\/30 {
            @apply bg-purple-900 dark:bg-purple-900/30;
        }

        .border-gray-700 {
            @apply border-slate-700 dark:border-slate-700;
        }

        .border-gray-800 {
            @apply border-slate-800 dark:border-slate-800;
        }

        .hover\:bg-gray-800\/50:hover {
            @apply hover:bg-slate-800/50 dark:hover:bg-slate-800/50;
        }

        .bg-gray-900\/50 {
            @apply bg-slate-900/50 dark:bg-slate-900/50;
        }

        /* Dark mode select dropdown styling - Stronger rules */
        select, .input-field {
            background-color: white !important;
            color: #1e293b !important;
            border: 1px solid #cbd5e1 !important;
        }
        
        .dark select, .dark .input-field {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
            border: 1px solid #475569 !important;
        }
        
        /* Custom dropdown arrow */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
            padding-right: 2.5rem !important;
        }
        
        .dark select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%9ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        }
        
        /* Options styling */
        select option {
            background-color: white !important;
            color: #1e293b !important;
            padding: 0.5rem !important;
        }
        
        .dark select option {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        
        /* Add proper padding and border-radius */
        select, .input-field {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            width: 100% !important;
            transition: all 0.2s ease !important;
        }
        
        select {
            padding-right: 2.5rem !important;
        }
        
        /* Focus states */
        select:focus, .input-field:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
        
        .dark select:focus, .dark .input-field:focus {
            box-shadow: 0 0 0 2px #60a5fa !important;
            border-color: #60a5fa !important;
        }
        
        /* Hover states */
        select:hover, .input-field:hover {
            border-color: #94a3b8 !important;
        }
        
        .dark select:hover, .dark .input-field:hover {
            border-color: #64748b !important;
        }
    </style>

    <script>
        // Set dark mode immediately to avoid flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>

<body
    class="font-sans antialiased bg-gray-100 dark:bg-slate-950 text-gray-900 dark:text-slate-100 flex min-h-screen transition-colors duration-300"
    x-data="{ 
        sidebarOpen: true, 
        darkMode: localStorage.getItem('theme') === 'dark',
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }">

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-24 px-2'"
        class="bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 text-slate-800 dark:text-white flex flex-col flex-shrink-0 transition-all duration-300 sticky top-0 h-screen z-50">
        <div class="h-16 flex items-center px-6 bg-slate-50/50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800 overflow-hidden whitespace-nowrap">
            <span class="text-xl font-black tracking-tighter flex items-center">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs mr-3 shadow-sm">V</span>
                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    VTTLib <span class="text-[10px] bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded ml-1 tracking-widest uppercase">Admin</span>
                </span>
            </span>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-2.5 overflow-y-auto custom-scrollbar overflow-x-hidden">
            @php
            $roleUserIds = Auth::user()->roles->map(fn($role) => $role->pivot->id);
            @endphp
            @foreach(Auth::user()->getSidebarTabs() as $tab)
            @php
            // Using direct query to avoid unknown method lint if model is not inferred
            $assignedChildren = \App\Models\Sidebar::where('parent_id', $tab->id)
            ->where('is_active', true)
            ->whereHas('userRoleSidebars', function ($q) use ($roleUserIds) {
            $q->whereIn('role_user_id', $roleUserIds);
            })->orderBy('order')->get();

            $hasChildren = $assignedChildren->isNotEmpty();
            $isParentActive = false;
            if ($hasChildren) {
            foreach ($assignedChildren as $child) {
            if ($child->route_name != '#' && request()->routeIs($child->route_name . '*')) {
            $isParentActive = true;
            break;
            }
            }
            } else {
            $isParentActive = ($tab->route_name != '#' && request()->routeIs($tab->route_name . '*'));
            }
            @endphp

            @if($hasChildren)
            <div class="space-y-1.5" x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">
                <button @click="sidebarOpen ? (open = !open) : (sidebarOpen = true, open = true)"
                    :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                    class="w-full flex items-center px-4 py-3.5 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-indigo-600 dark:hover:text-white rounded-2xl transition group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-colors">{!! $tab->icon !!}</div>
                        <span x-show="sidebarOpen" x-cloak
                            class="ml-3 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">{{ $tab->display_name }}</span>
                    </div>
                    <svg x-show="sidebarOpen" x-cloak class="w-3.5 h-3.5 transition-transform duration-300 opacity-60"
                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open && sidebarOpen" x-cloak x-collapse class="pl-12 space-y-1">
                    @foreach($assignedChildren as $child)
                    <a href="{{ (!blank($child->route_name) && $child->route_name !== '#' && Route::has($child->route_name)) ? route($child->route_name) : '#' }}"
                        class="block py-2.5 px-4 text-[10px] font-black uppercase tracking-widest border-l-2 {{ ($child->route_name != '#' && request()->routeIs($child->route_name . '*')) ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/10' : 'border-slate-100 dark:border-slate-800 text-slate-500 hover:text-indigo-600 hover:border-indigo-400 transition' }} whitespace-nowrap">
                        {{ $child->display_name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <a href="{{ (!blank($tab->route_name) && $tab->route_name !== '#' && Route::has($tab->route_name)) ? route($tab->route_name) : '#' }}"
                :class="sidebarOpen ? 'px-4' : 'justify-center px-0'"
                class="flex items-center py-3.5 {{ $isParentActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 dark:shadow-none' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-indigo-600 dark:hover:text-white' }} rounded-2xl group transition">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-colors" :class="sidebarOpen ? '' : 'w-full'">
                    {!! $tab->icon !!}
                </div>
                <span x-show="sidebarOpen" x-cloak
                    class="ml-3 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">{{ $tab->display_name }}</span>
            </a>
            @endif
            @endforeach
        </nav>

        <div class="p-4 border-t border-slate-800 dark:border-slate-800/50 overflow-hidden">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" :class="sidebarOpen ? 'px-4' : 'justify-center px-0'"
                    class="w-full flex items-center py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 group transition">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="ml-3 whitespace-nowrap">{{ __('Logout') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header
            class="h-16 bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 shadow-sm flex items-center justify-between px-6 z-10 transition-colors duration-300">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7"></path>
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-slate-100 leading-tight">{{ __('Dashboard') }}
                </h2>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Theme Toggle -->
                <button @click="toggleDarkMode()"
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-300 group">
                    <svg x-show="!darkMode" class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak
                        class="w-5 h-5 group-hover:rotate-90 transition-transform text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-800 mx-2"></div>

                <div class="flex items-center space-x-2 mr-2">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="text-xs font-bold {{ app()->getLocale() == 'vi' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-slate-500' }} hover:text-indigo-500 transition">VI</a>
                    <span class="text-gray-300 dark:text-slate-700">|</span>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-slate-500' }} hover:text-indigo-500 transition">EN</a>
                </div>

                <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-800 mx-2"></div>

                <div class="relative group cursor-pointer">
                    <span
                        class="absolute -top-1 -right-1 block h-2.5 w-2.5 rounded-full ring-2 ring-white dark:ring-slate-900 bg-red-500 animate-pulse"></span>
                    <svg class="h-6 w-6 text-slate-500 dark:text-slate-400 group-hover:text-indigo-500 transition-colors"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                <div class="flex items-center ml-4 pl-4 border-l border-slate-200 dark:border-slate-800">
                    <div class="text-right mr-3 hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-100 leading-none mb-1">
                            {{ Auth::user()->full_name ?? Auth::user()->name }}
                        </p>
                        <p
                            class="text-[10px] font-semibold text-indigo-500 dark:text-indigo-400 uppercase tracking-wider">
                            {{ Auth::user()->roles->pluck('display_name')->implode(', ') }}
                        </p>
                    </div>
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/20 ring-2 ring-white dark:ring-slate-900"
                        title="{{ Auth::user()->name ?? '' }}">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main
            class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-slate-950 p-6 transition-colors duration-300">
            <x-breadcrumb />
            @yield('content')
        </main>
    </div>
    <!-- Toast Notifications -->
    <div x-data="toastManager" @toast.window="add($event.detail)"
        class="fixed top-6 right-6 z-[100] flex flex-col items-end space-y-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full opacity-0 scale-95"
                x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                x-transition:leave-end="translate-x-full opacity-0 scale-95"
                class="pointer-events-auto bg-white dark:bg-slate-900 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-800 p-4 min-w-[320px] max-w-md flex items-center space-x-4">

                <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="{
                        'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400': toast.type === 'success',
                        'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400': toast.type === 'error' || toast.type === 'danger',
                        'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400': toast.type === 'warning',
                        'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400': toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'"><svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg></template>
                    <template x-if="toast.type === 'error' || toast.type === 'danger'"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></template>
                    <template x-if="toast.type === 'warning'"><svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg></template>
                    <template x-if="toast.type === 'info'"><svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></template>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="toast.message"></p>
                </div>

                <button @click="remove(toast.id)"
                    class="text-slate-300 dark:text-slate-600 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('toastManager', () => ({
                toasts: [],
                init() {
                    const sessionToasts = [];
                    @if(session('success')) sessionToasts.push({
                        message: @js(session('success')),
                        type: 'success'
                    });
                    @endif
                    @if(session('error')) sessionToasts.push({
                        message: @js(session('error')),
                        type: 'error'
                    });
                    @endif
                    @if(session('warning')) sessionToasts.push({
                        message: @js(session('warning')),
                        type: 'warning'
                    });
                    @endif
                    @if(session('info')) sessionToasts.push({
                        message: @js(session('info')),
                        type: 'info'
                    });
                    @endif

                    @if($errors->any())
                    @foreach($errors->all() as $error)
                    sessionToasts.push({
                        message: @js($error),
                        type: 'error'
                    });
                    @endforeach
                    @endif

                    // Process initial toasts with delay to ensure animations work
                    sessionToasts.forEach((t, i) => {
                        setTimeout(() => this.add(t), i * 200);
                    });
                },
                add(data) {
                    const id = Math.random().toString(36).substr(2, 9);
                    this.toasts.push({
                        id: id,
                        message: data.message,
                        type: data.type || 'info',
                        visible: false
                    });

                    setTimeout(() => {
                        const toast = this.toasts.find(t => t.id === id);
                        if (toast) toast.visible = true;
                    }, 100);

                    setTimeout(() => this.remove(id), 5000);
                },
                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                }
            }));
        });
    </script>

    <!-- Scroll To Top/Bottom Button -->
    <div x-data="{
        isAtTop: true,
        mainElement: null,
        init() {
            // Find the main content element specifically
            this.mainElement = document.querySelector('main');
            
            if (!this.mainElement) {
                return;
            }
            
            // Listen to scroll events on main element
            this.mainElement.addEventListener('scroll', () => {
                this.isAtTop = this.mainElement.scrollTop < 100;
            });
            
            // Check initial scroll position
            this.isAtTop = this.mainElement.scrollTop < 100;
        },
        scrollToPosition() {
            if (!this.mainElement) {
                return;
            }
            
            if (this.isAtTop) {
                // Scroll to bottom of main content
                this.mainElement.scrollTo({ 
                    top: this.mainElement.scrollHeight, 
                    behavior: 'smooth' 
                });
            } else {
                // Scroll to top of main content
                this.mainElement.scrollTo({ 
                    top: 0, 
                    behavior: 'smooth' 
                });
            }
        }
    }" 
    class="fixed bottom-6 right-8 z-[90]">
        <button @click="scrollToPosition()"
            class="p-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg shadow-indigo-300/50 dark:shadow-none transition-all duration-300 group flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-900 hover:scale-110"
            :title="isAtTop ? 'Cuộn xuống cuối' : 'Cuộn lên đầu'">

            <!-- Icon Scroll To Top (Up arrow) - Shows when NOT at top -->
            <svg x-show="!isAtTop" x-cloak class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>

            <!-- Icon Scroll To Bottom (Down arrow) - Shows when AT top -->
            <svg x-show="isAtTop" class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </button>
    </div>

    @stack('scripts')
</body>

</html>
