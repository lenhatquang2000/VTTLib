<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VTTLib - Root System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f8fafc;
            --sidebar-bg: #1e293b;
            --sidebar-active: #334155;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --accent-color: #4f46e5;
            --border-color: #e2e8f0;
            --header-bg: #ffffff;
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-active: #334155;
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-white: #ffffff;
            --text-secondary: #94a3b8;
            --border-color: #334155;
            --header-bg: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar-root {
            background-color: var(--sidebar-bg);
            color: #cbd5e1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar-link:hover {
            background-color: var(--sidebar-active);
            color: white;
        }

        .sidebar-link.active {
            background-color: var(--accent-color);
            color: white;
        }

        .card-root {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        header {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
        }

        /* Override Red Utility Classes for root consistency */
        .text-red-500 { color: var(--accent-color) !important; }
        .text-red-900 { color: var(--text-secondary) !important; }
        .bg-red-900\/20 { background-color: rgba(79, 70, 229, 0.1) !important; border-color: rgba(79, 70, 229, 0.2) !important; }
        .bg-red-900\/10 { background-color: rgba(79, 70, 229, 0.05) !important; }
        .bg-red-900 { background-color: var(--accent-color) !important; color: white !important; }
        .border-red-900\/20 { border-color: var(--border-color) !important; }
        .border-red-900 { border-color: var(--accent-color) !important; }
        .hover\:bg-red-600:hover { background-color: #4338ca !important; }
        .hover\:bg-red-900\/5:hover { background-color: rgba(79, 70, 229, 0.05) !important; }
        
        .font-mono { font-family: 'Inter', sans-serif !important; }

        /* Toast Animations */
        @keyframes toast-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toast-out {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .animate-toast-in { animation: toast-in 0.3s ease-out forwards; }
        .animate-toast-out { animation: toast-out 0.3s ease-in forwards; }
    </style>
</head>

<body class="min-h-screen flex" id="root-body">
    <!-- Sidebar -->
    <aside class="sidebar-root w-64 flex flex-col transition-all duration-300 sticky top-0 h-screen shadow-xl z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-700">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold text-white shadow-lg">V</div>
                <span class="font-bold tracking-tight text-white">VTTLib <span class="text-indigo-400">Root</span></span>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <div class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Management') }}</div>
            <a href="{{ route('root.users.index') }}"
                class="sidebar-link {{ request()->routeIs('root.users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                {{ __('USER_MANAGEMENT') }}
            </a>
            <a href="{{ route('root.roles.index') }}"
                class="sidebar-link {{ request()->routeIs('root.roles.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                {{ __('Role Management') }}
            </a>
            
            <div class="px-3 py-2 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('System') }}</div>
            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                {{ __('SYSTEM_LOGS') }}
            </a>
            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('DB_TERMINAL') }}
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center p-2 text-sm text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col overflow-hidden transition-all duration-300">
        <header class="h-16 flex items-center justify-between px-8 shadow-sm z-10">
            <div class="flex items-center">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">
                    @if(request()->routeIs('root.users.index'))
                        {{ __('User Management') }}
                    @elseif(request()->routeIs('root.users.privileges'))
                        {{ __('User_Privilege_Management') }}
                    @elseif(request()->routeIs('root.roles.*'))
                        {{ __('Role Management') }}
                    @else
                        {{ __('System Panel') }}
                    @endif
                </h2>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Theme Toggle -->
                <button id="theme-toggle"
                    class="p-2 rounded-lg border border-slate-200 hover:bg-slate-100 transition-all dark:border-slate-700 dark:hover:bg-slate-800">
                    <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    </svg>
                </button>

                <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="px-3 py-1 text-[10px] font-bold rounded {{ app()->getLocale() == 'vi' ? 'bg-white shadow text-indigo-600 dark:bg-slate-700 dark:text-indigo-400' : 'text-slate-500' }}">VI</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-3 py-1 text-[10px] font-bold rounded {{ app()->getLocale() == 'en' ? 'bg-white shadow text-indigo-600 dark:bg-slate-700 dark:text-indigo-400' : 'text-slate-500' }}">EN</a>
                </div>
                
                <div class="flex items-center space-x-3 border-l pl-6 border-slate-200 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="text-xs font-semibold">{{ Auth::user()->name }}</div>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8 bg-slate-50/50 dark:bg-slate-950/20">
            <x-breadcrumb />
            @yield('content')
        </div>

        <div id="toast-container" class="fixed top-6 right-6 z-[200] space-y-3 pointer-events-none"></div>
    </main>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.getElementById('root-body');
        const themeIcon = document.getElementById('theme-icon');

        const savedTheme = localStorage.getItem('root-theme') || 'dark';
        body.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'dark') body.classList.add('dark');
        updateIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            body.setAttribute('data-theme', newTheme);
            body.classList.toggle('dark', newTheme === 'dark');
            localStorage.setItem('root-theme', newTheme);
            updateIcon(newTheme);
        });

        function updateIcon(theme) {
            if (theme === 'light') {
                themeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />`;
            } else {
                themeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />`;
            }
        }

        // Global Toast Logic
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? '#4f46e5' : '#e11d48'; // Indigo-600 vs Rose-600
            const icon = type === 'success' 
                ? `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                : `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;

            toast.className = `flex items-center p-4 min-w-[320px] max-w-md text-white rounded-2xl shadow-2xl pointer-events-auto animate-toast-in`;
            toast.style.backgroundColor = bgColor;
            toast.style.zIndex = '9999';
            
            toast.innerHTML = `
                <div class="flex-shrink-0 mr-3 p-1.5 bg-white/20 rounded-xl">${icon}</div>
                <div class="flex-1 text-[11px] font-black uppercase tracking-widest leading-tight">${message}</div>
                <button class="ml-4 p-1 text-white/40 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;

            container.appendChild(toast);

            // Auto-remove
            const removeTimeout = setTimeout(() => {
                toast.classList.replace('animate-toast-in', 'animate-toast-out');
                setTimeout(() => toast.remove(), 300);
            }, 5000);

            // Close button
            toast.querySelector('button').onclick = () => {
                clearTimeout(removeTimeout);
                toast.classList.replace('animate-toast-in', 'animate-toast-out');
                setTimeout(() => toast.remove(), 300);
            };
        }

        // Global initialization
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger toasts from session
            @if(session('success')) 
                showToast(@json(session('success')), 'success'); 
            @endif

            @if(session('error')) 
                showToast(@json(session('error')), 'error'); 
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast(@json($error), 'error');
                @endforeach
            @endif
        });
    </script>
</body>

</html>