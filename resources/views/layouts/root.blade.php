<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROOT_SYSTEM_VTTLIB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,700" rel="stylesheet" />
    <style>
        :root {
            --bg-color: #0a0000;
            --sidebar-bg: #140000;
            --card-bg: #1a0202;
            --text-primary: #ef4444;
            /* red-500 */
            --text-white: #ffffff;
            --text-secondary: #7f1d1d;
            /* red-900 */
            --border-color: #450a0a;
            /* red-950/ish */
            --accent-color: #dc2626;
            /* red-600 */
            --input-bg: #000000;
            --header-bg: transparent;
        }

        [data-theme="light"] {
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-white: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --accent-color: #3b82f6;
            --input-bg: #f1f5f9;
            --header-bg: #ffffff;
        }

        body {
            font-family: 'JetBrains Mono', monospace;
            background-color: var(--bg-color);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .root-bg {
            background-color: var(--bg-color);
        }

        .sidebar-root {
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
        }

        .card-root {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        header {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
        }

        /* Theme overrides for specific colors */
        .text-white {
            color: var(--text-white) !important;
        }

        .text-red-900 {
            color: var(--text-secondary);
        }

        .text-red-500 {
            color: var(--text-primary);
        }

        .border-red-900\/20 {
            border-color: var(--border-color);
        }

        .bg-black {
            background-color: var(--input-bg);
        }

        [data-theme="light"] .bg-black\/40 {
            background-color: #f8fafc;
        }

        [data-theme="light"] .text-red-400 {
            color: #3b82f6;
        }

        [data-theme="light"] .bg-red-900\/20 {
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        [data-theme="light"] .bg-red-900 {
            background-color: var(--accent-color);
            color: white;
        }

        [data-theme="light"] .hover\:bg-red-600:hover {
            background-color: #2563eb;
        }

        [data-theme="light"] .glitch {
            text-shadow: none;
        }

        /* Fix hover states for Light Theme */
        [data-theme="light"] .hover\:text-white:hover {
            color: #0f172a !important;
        }

        [data-theme="light"] .hover\:text-red-500:hover {
            color: #2563eb !important;
        }

        [data-theme="light"] .group:hover .group-hover\:decoration-red-500 {
            text-decoration-color: #3b82f6 !important;
        }

        [data-theme="light"] .group:hover .group-hover\:text-white {
            color: #0f172a !important;
        }
    </style>
</head>

<body class="min-h-screen flex" id="root-body">
    <!-- Sidebar -->
    <aside class="sidebar-root w-64 flex flex-col transition-colors duration-300">
        <div class="h-16 flex items-center px-6 border-b border-red-900/20">
            <span class="font-bold tracking-widest text-lg">ROOT_<span class="text-white">CORE</span></span>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('root.users.index') }}"
                class="block p-3 bg-red-900/10 border-l-2 border-red-600 font-bold transition-all">{{ __('USER_MANAGEMENT') }}</a>
            <a href="#" class="block p-3 text-red-900 hover:text-red-500 transition">{{ __('SYSTEM_LOGS') }}</a>
            <a href="#" class="block p-3 text-red-900 hover:text-red-500 transition">{{ __('DB_TERMINAL') }}</a>
        </nav>
        <div class="p-4 border-t border-red-900/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left p-2 text-xs text-red-900 hover:text-red-500">{{ __('TERMINATE_SESSION') }}</button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col overflow-hidden transition-colors duration-300">
        <header class="h-16 flex items-center justify-between px-8">
            <h1 class="text-sm font-bold opacity-70">ROOT@VTTLIB:~/USER_MANAGER$</h1>
            <div class="flex items-center space-x-6">
                <!-- Theme Toggle -->
                <button id="theme-toggle"
                    class="p-2 rounded-full border border-red-900/30 hover:bg-red-900/10 transition">
                    <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <!-- Moon -->
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                <div class="flex space-x-2 text-[10px]">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="{{ app()->getLocale() == 'vi' ? 'text-red-500 font-bold underline' : 'text-red-900' }}">VI</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="{{ app()->getLocale() == 'en' ? 'text-red-500 font-bold underline' : 'text-red-900' }}">EN</a>
                </div>
                <div class="text-xs">{{ Auth::user()->name }}</div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.getElementById('root-body');
        const themeIcon = document.getElementById('theme-icon');

        // Check for saved theme
        const savedTheme = localStorage.getItem('root-theme') || 'dark';
        body.setAttribute('data-theme', savedTheme);
        updateIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            body.setAttribute('data-theme', newTheme);
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
    </script>
</body>

</html>