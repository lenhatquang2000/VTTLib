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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
                    colors: {
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                        ring: 'hsl(var(--ring))',
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: {
                            DEFAULT: 'hsl(var(--primary))',
                            foreground: 'hsl(var(--primary-foreground))',
                        },
                        secondary: {
                            DEFAULT: 'hsl(var(--secondary))',
                            foreground: 'hsl(var(--secondary-foreground))',
                        },
                        destructive: {
                            DEFAULT: 'hsl(var(--destructive))',
                            foreground: 'hsl(var(--destructive-foreground))',
                        },
                        muted: {
                            DEFAULT: 'hsl(var(--muted))',
                            foreground: 'hsl(var(--muted-foreground))',
                        },
                        accent: {
                            DEFAULT: 'hsl(var(--accent))',
                            foreground: 'hsl(var(--accent-foreground))',
                        },
                        card: {
                            DEFAULT: 'hsl(var(--card))',
                            foreground: 'hsl(var(--card-foreground))',
                        },
                    },
                },
            },
            plugins: [],
        }
    </script>
    <style>
        :root {
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --primary: 221.2 83.2% 53.3%;
            --primary-foreground: 210 40% 98%;
            --secondary: 210 40% 96.1%;
            --secondary-foreground: 222.2 47.4% 11.2%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --accent: 210 40% 96.1%;
            --accent-foreground: 222.2 47.4% 11.2%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 40% 98%;
            --border: 214.3 31.8% 91.4%;
            --input: 214.3 31.8% 91.4%;
            --ring: 221.2 83.2% 53.3%;
        }

        .dark {
            --background: 222.2 84% 4.9%;
            --foreground: 210 40% 98%;
            --card: 222.2 84% 6.9%;
            --card-foreground: 210 40% 98%;
            --primary: 217.2 91.2% 59.8%;
            --primary-foreground: 222.2 47.4% 11.2%;
            --secondary: 217.2 32.6% 17.5%;
            --secondary-foreground: 210 40% 98%;
            --muted: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --accent: 217.2 32.6% 17.5%;
            --accent-foreground: 210 40% 98%;
            --destructive: 0 62.8% 30.6%;
            --destructive-foreground: 210 40% 98%;
            --border: 217.2 32.6% 25%;
            --input: 217.2 32.6% 17.5%;
            --ring: 224.3 76.3% 48%;
        }
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

        /* Table/Data List: Hover effect */
        .table-row-hover {
            @apply transition-colors duration-150 hover:bg-muted/50 active:bg-muted;
        }

        /* Button: Professional Hover/Active Effects */
        .btn-compact {
            @apply inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-sm text-[11px] font-bold uppercase tracking-wider transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none;
        }

        .btn-compact-primary {
            @apply btn-compact bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm;
        }

        .btn-compact-secondary {
            @apply btn-compact bg-secondary text-secondary-foreground hover:bg-secondary/80 border border-border;
        }

        .btn-compact-muted {
            @apply btn-compact bg-muted text-muted-foreground hover:bg-muted/80 border border-border;
        }

        .btn-icon-compact {
            @apply w-8 h-8 flex items-center justify-center rounded-sm bg-background hover:bg-muted text-muted-foreground border border-border transition-all active:scale-90;
        }

        .btn-icon-danger {
            @apply btn-icon-compact hover:bg-destructive hover:text-destructive-foreground;
        }

        .input-field {
            @apply w-full px-3 py-1.5 bg-background border border-border rounded-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring focus:border-primary transition-all duration-200;
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
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.25em 1.25em !important;
            padding-right: 2.5rem !important;
            color: inherit !important;
            text-indent: 0 !important;
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
    class="font-sans antialiased bg-gray-100 dark:bg-slate-900 text-gray-900 dark:text-slate-100 flex min-h-screen transition-colors duration-300"
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
    <aside :class="sidebarOpen ? 'w-56' : 'w-16'"
        class="bg-card border-r border-border text-foreground flex flex-col flex-shrink-0 transition-all duration-300 sticky top-0 h-screen z-50">
        <div class="h-14 flex items-center px-4 bg-muted/50 border-b border-border overflow-hidden whitespace-nowrap">
            <span class="text-lg font-bold tracking-tight flex items-center">
                <div class="w-8 h-8 rounded-sm bg-primary flex items-center justify-center text-primary-foreground text-xs mr-3 shadow-sm shrink-0">V</div>
                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">
                    VTTLib <span class="text-[9px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-sm ml-1 tracking-widest uppercase font-bold">Admin</span>
                </span>
            </span>
        </div>

        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto custom-scrollbar overflow-x-hidden">
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
                    :class="sidebarOpen ? 'justify-between' : 'justify-center px-0'"
                    class="w-full flex items-center px-3 py-2 text-muted-foreground hover:bg-muted hover:text-foreground rounded-sm transition-all group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-4 h-4 flex items-center justify-center group-hover:text-primary transition-colors">{!! $tab->icon !!}</div>
                        <span x-show="sidebarOpen" x-cloak
                            class="ml-3 font-bold text-[10px] uppercase tracking-wider whitespace-nowrap">{{ $tab->display_name }}</span>
                    </div>
                    <i data-lucide="chevron-down" x-show="sidebarOpen" x-cloak class="w-3.5 h-3.5 transition-transform duration-300 opacity-60"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && sidebarOpen" x-cloak x-collapse class="px-1.5 pb-1 space-y-0.5 mt-1 border-l border-border ml-4.5">
                    @foreach($assignedChildren as $child)
                        <a href="{{ $child->route_name && $child->route_name !== '#' && Route::has($child->route_name) ? route($child->route_name) : '#' }}"
                            class="group flex items-center px-3 py-1.5 text-xs font-bold rounded-sm transition-all duration-200 {{ request()->routeIs($child->route_name . '*') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="{{ $child->icon ?? 'fas fa-circle' }} w-3.5 h-3.5 flex items-center justify-center text-[7px] opacity-40 group-hover:opacity-100 transition-all"></i>
                                </div>
                                <span class="ml-2.5 truncate uppercase tracking-widest text-[9px]">{{ $child->display_name }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @else
            <a href="{{ (!blank($tab->route_name) && $tab->route_name !== '#' && Route::has($tab->route_name)) ? route($tab->route_name) : '#' }}"
                :class="sidebarOpen ? 'px-3' : 'justify-center px-0'"
                class="flex items-center py-2.5 {{ $isParentActive ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }} rounded-sm group transition-all">
                <div class="flex-shrink-0 w-4 h-4 flex items-center justify-center group-hover:text-primary transition-colors" :class="sidebarOpen ? '' : 'w-full'">
                    {!! $tab->icon !!}
                </div>
                <span x-show="sidebarOpen" x-cloak
                    class="ml-3 font-bold text-[10px] uppercase tracking-widest whitespace-nowrap">{{ $tab->display_name }}</span>
            </a>
            @endif
            @endforeach
        </nav>

        <div class="p-3 border-t border-border overflow-hidden">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" :class="sidebarOpen ? 'px-3' : 'justify-center px-0'"
                    class="w-full flex items-center py-2 text-xs font-bold uppercase tracking-widest text-muted-foreground hover:text-destructive group transition-all">
                    <i data-lucide="log-out" class="w-4 h-4 flex-shrink-0 group-hover:text-destructive transition-colors"></i>
                    <span x-show="sidebarOpen" x-cloak class="ml-3 whitespace-nowrap">{{ __('Logout') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header
            class="h-14 bg-background border-b border-border shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 transition-colors duration-300 sticky top-0">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-sm text-muted-foreground hover:bg-muted hover:text-foreground focus:outline-none mr-4 transition-colors">
                    <i data-lucide="menu" class="w-5 h-5" x-show="!sidebarOpen"></i>
                    <i data-lucide="menu-x" class="w-5 h-5" x-show="sidebarOpen"></i>
                </button>
                <h2 class="text-base font-bold text-foreground tracking-tight">{{ __('Dashboard') }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <!-- Theme Toggle -->
                <button @click="toggleDarkMode()"
                    class="p-2 rounded-sm text-muted-foreground hover:bg-muted hover:text-foreground transition-all duration-300 group">
                    <i data-lucide="moon" x-show="!darkMode" class="w-4 h-4 group-hover:rotate-12 transition-transform"></i>
                    <i data-lucide="sun" x-show="darkMode" x-cloak class="w-4 h-4 group-hover:rotate-90 transition-transform text-amber-500"></i>
                </button>

                <div class="h-4 w-[1px] bg-border mx-1"></div>

                <div class="flex items-center gap-2 mr-1">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="text-[10px] font-bold uppercase tracking-wider {{ app()->getLocale() == 'vi' ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }} transition">VI</a>
                    <span class="text-border text-[10px]">|</span>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="text-[10px] font-bold uppercase tracking-wider {{ app()->getLocale() == 'en' ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }} transition">EN</a>
                </div>

                <div class="h-4 w-[1px] bg-border mx-1"></div>

                <div class="relative group cursor-pointer p-2 rounded-sm hover:bg-muted transition-colors">
                    <span
                        class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full ring-2 ring-background bg-destructive animate-pulse"></span>
                    <i data-lucide="bell" class="w-4 h-4 text-muted-foreground group-hover:text-primary transition-colors"></i>
                </div>

                <div class="flex items-center ml-2 pl-3 border-l border-border gap-3">
                    <div class="text-right hidden md:block">
                        <p class="text-[11px] font-bold text-foreground leading-none mb-1">
                            {{ Auth::user()->full_name ?? Auth::user()->name }}
                        </p>
                        <p
                            class="text-[9px] font-bold text-primary uppercase tracking-wider opacity-80">
                            {{ Auth::user()->roles->pluck('display_name')->first() }}
                        </p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-bold text-xs shadow-sm ring-2 ring-background ring-offset-1 ring-offset-border"
                        title="{{ Auth::user()->name ?? '' }}">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main
            class="flex-1 overflow-x-hidden overflow-y-auto bg-background p-4 sm:p-6 transition-colors duration-300 custom-scrollbar">
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
                class="pointer-events-auto bg-card rounded-md shadow-lg border border-border p-3 min-w-[280px] max-w-md flex items-center space-x-3">

                <div class="shrink-0 w-8 h-8 rounded-sm flex items-center justify-center" :class="{
                        'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400': toast.type === 'success',
                        'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400': toast.type === 'error' || toast.type === 'danger',
                        'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400': toast.type === 'warning',
                        'bg-primary/10 text-primary': toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'"><i data-lucide="check-circle" class="w-5 h-5"></i></template>
                    <template x-if="toast.type === 'error' || toast.type === 'danger'"><i data-lucide="alert-circle" class="w-5 h-5"></i></template>
                    <template x-if="toast.type === 'warning'"><i data-lucide="alert-triangle" class="w-5 h-5"></i></template>
                    <template x-if="toast.type === 'info'"><i data-lucide="info" class="w-5 h-5"></i></template>
                </div>

                <div class="flex-1">
                    <p class="text-xs font-bold text-foreground" x-text="toast.message"></p>
                </div>

                <button @click="remove(toast.id)"
                    class="text-muted-foreground hover:text-foreground transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
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

    @vite(['resources/js/app.js'])
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>

</html>
