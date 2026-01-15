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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900 flex min-h-screen" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-slate-900 text-white flex flex-col flex-shrink-0 transition-all duration-300 sticky top-0 h-screen z-50">
        <div class="h-16 flex items-center px-6 bg-slate-950 overflow-hidden whitespace-nowrap">
            <span class="text-xl font-bold tracking-wider flex items-center">
                <span class="text-indigo-500 mr-3">V</span>
                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    VTTLib <span class="text-indigo-400 text-sm font-normal">{{ __('VTTLib Admin') }}</span>
                </span>
            </span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar overflow-x-hidden">
            @php
                $roleUserIds = Auth::user()->roles->map(fn($role) => $role->pivot->id);
            @endphp
            @foreach(Auth::user()->getSidebarTabs() as $tab)
                @php
                    $assignedChildren = $tab->children()->whereHas('userRoleSidebars', function ($q) use ($roleUserIds) {
                        $q->whereIn('role_user_id', $roleUserIds);
                    })->get();
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
                    <div class="space-y-1" x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">
                        <button @click="sidebarOpen ? (open = !open) : (sidebarOpen = true, open = true)"
                            :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                            class="w-full flex items-center px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition group">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">{!! $tab->icon !!}</div>
                                <span x-show="sidebarOpen" x-cloak class="ml-3 font-medium whitespace-nowrap">{{ __($tab->name) }}</span>
                            </div>
                            <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-cloak class="pl-10 space-y-1">
                            @foreach($assignedChildren as $child)
                                <a href="{{ $child->route_name != '#' ? route($child->route_name) : '#' }}"
                                    class="block px-4 py-2 text-sm {{ ($child->route_name != '#' && request()->routeIs($child->route_name . '*')) ? 'text-indigo-400 font-bold' : 'text-slate-500 hover:text-white' }} transition whitespace-nowrap">
                                    {{ __($child->name) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $tab->route_name != '#' ? route($tab->route_name) : '#' }}"
                        :class="sidebarOpen ? 'px-4' : 'justify-center px-0'"
                        class="flex items-center py-3 {{ $isParentActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} rounded-lg group transition">
                        <div class="flex-shrink-0" :class="sidebarOpen ? '' : 'flex justify-center w-full'">{!! $tab->icon !!}</div>
                        <span x-show="sidebarOpen" x-cloak class="ml-3 font-medium whitespace-nowrap">{{ __($tab->name) }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="p-4 border-t border-slate-800 overflow-hidden">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    :class="sidebarOpen ? 'px-4' : 'justify-center px-0'"
                    class="w-full flex items-center py-2 text-sm text-slate-400 hover:text-white group transition">
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
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h2>
            </div>
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
    <!-- Toast Notifications -->
    <div x-data="toastManager" 
         @toast.window="add($event.detail)"
         class="fixed top-6 right-6 z-[100] flex flex-col items-end space-y-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full opacity-0 scale-95"
                 x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                 x-transition:leave-end="translate-x-full opacity-0 scale-95"
                 class="pointer-events-auto bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 p-4 min-w-[320px] max-w-md flex items-center space-x-4">
                
                <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                     :class="{
                        'bg-emerald-50 text-emerald-600': toast.type === 'success',
                        'bg-rose-50 text-rose-600': toast.type === 'error' || toast.type === 'danger',
                        'bg-amber-50 text-amber-600': toast.type === 'warning',
                        'bg-indigo-50 text-indigo-600': toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></template>
                    <template x-if="toast.type === 'error' || toast.type === 'danger'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></template>
                    <template x-if="toast.type === 'warning'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></template>
                    <template x-if="toast.type === 'info'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></template>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-900" x-text="toast.message"></p>
                </div>

                <button @click="remove(toast.id)" class="text-slate-300 hover:text-slate-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                    @if(session('success')) sessionToasts.push({ message: @js(session('success')), type: 'success' }); @endif
                    @if(session('error')) sessionToasts.push({ message: @js(session('error')), type: 'error' }); @endif
                    @if(session('warning')) sessionToasts.push({ message: @js(session('warning')), type: 'warning' }); @endif
                    @if(session('info')) sessionToasts.push({ message: @js(session('info')), type: 'info' }); @endif
                    
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            sessionToasts.push({ message: @js($error), type: 'error' });
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
</body>

</html>