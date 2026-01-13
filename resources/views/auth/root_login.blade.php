<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROOT_ACCESS_TERMINAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'JetBrains Mono', monospace;
        }

        .glitch {
            text-shadow: 2px 0 #ff0000, -2px 0 #00ff00;
        }

        .terminal-bg {
            background-color: #050505;
            background-image: radial-gradient(#1a0000 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="h-full terminal-bg text-red-600 flex items-center justify-center p-6">
    <div class="max-w-md w-full border-2 border-red-900 bg-black p-8 shadow-[0_0_30px_rgba(255,0,0,0.15)]">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold glitch uppercase tracking-tighter mb-2">Root Terminal</h1>
            <p class="text-xs text-red-900 uppercase">Warning: System Level Access</p>
        </div>

        <form action="{{ route('root.login.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs uppercase mb-2">Identifier</label>
                <input type="email" name="email" required
                    class="w-full bg-transparent border border-red-900 p-2 focus:border-red-500 outline-none transition text-red-500">
                @error('email') <p class="text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs uppercase mb-2">Master Key</label>
                <input type="password" name="password" required
                    class="w-full bg-transparent border border-red-900 p-2 focus:border-red-500 outline-none transition text-red-500">
            </div>
            <button type="submit"
                class="w-full bg-red-900 text-black font-bold py-3 uppercase hover:bg-red-600 transition duration-300">Execute
                Authentication</button>
        </form>

        <div class="mt-8 pt-6 border-t border-red-900/30 flex justify-between items-center text-[10px] text-red-900">
            <span>KERNEL_V6.4.2_STABLE</span>
            <div class="flex space-x-2">
                <a href="{{ route('lang.switch', 'vi') }}" class="hover:text-red-500">VI</a>
                <span>|</span>
                <a href="{{ route('lang.switch', 'en') }}" class="hover:text-red-500">EN</a>
            </div>
        </div>
    </div>
</body>

</html>