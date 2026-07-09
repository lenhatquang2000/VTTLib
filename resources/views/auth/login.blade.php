<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - VTTLib</title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
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
        }
    </script>
    
    <style>
        :root {
            --background: 210 40% 96.1%;
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
        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full bg-background text-foreground flex items-center justify-center p-4">
    <!-- Card Container -->
    <div class="w-full max-w-sm bg-card border border-border rounded-md shadow-sm p-4 space-y-4 relative">
        
        <!-- Top Controls (Language & Security Badge) -->
        <div class="flex items-center justify-between text-xs text-muted-foreground">
            <!-- Language Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="flex items-center gap-1 hover:text-foreground font-semibold">
                    <i data-lucide="languages" class="w-4 h-4 text-muted-foreground"></i>
                    {{ app()->getLocale() == 'vi' ? 'VI' : 'EN' }}
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute left-0 mt-1 z-50 bg-card border border-border rounded-sm shadow-md py-1 min-w-[70px]">
                    <a href="{{ route('lang.switch', 'vi') }}" class="block px-2.5 py-1 hover:bg-muted text-foreground {{ app()->getLocale() == 'vi' ? 'font-bold bg-muted' : '' }}">VI</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="block px-2.5 py-1 hover:bg-muted text-foreground {{ app()->getLocale() == 'en' ? 'font-bold bg-muted' : '' }}">EN</a>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="flex items-center gap-1 text-[10px] font-medium tracking-wider uppercase bg-muted/50 px-2 py-0.5 rounded-sm border border-border/40">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-primary"></i>
                {{ __('VTTLib Secure') }}
            </div>
        </div>
        
        <!-- Header -->
        <div class="text-center space-y-1 py-2">
            <h1 class="text-xl font-bold tracking-tight text-foreground">VTTLib</h1>
            <p class="text-xs text-muted-foreground">{{ __('Cổng thông tin tài liệu học tập số') }}</p>
        </div>
        
        <!-- Body Content -->
        @if($isSsoError)
            <!-- SSO Error State View -->
            <div class="space-y-4 py-2 animate-in fade-in duration-300">
                <div class="flex justify-center">
                    <span class="w-10 h-10 rounded-full bg-destructive/10 text-destructive flex items-center justify-center border border-destructive/20">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </span>
                </div>
                
                <div class="p-3 border border-destructive/20 bg-destructive/10 text-destructive text-xs font-semibold leading-relaxed rounded-sm text-center">
                    {{ session('error_message_sso') ?: 'Bạn không có thông tin trên hệ thống vui lòng liên hệ TTCNPM Trường Đại học Võ Trường Toản - SDT: 02933504398. Cảm ơn.' }}
                </div>
                
                <div>
                    <a href="{{ route('login') }}" class="w-full h-9 flex items-center justify-center text-xs font-bold uppercase rounded-sm bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm transition-all">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> {{ __('Quay lại Đăng nhập') }}
                    </a>
                </div>
            </div>
        @else
            <!-- Standard Login View -->
            <form action="{{ route('client.login.store') }}" method="POST" class="space-y-3">
                @csrf
                
                <!-- Normal Validation Errors -->
                @if($errors->any())
                    <div class="p-3 border border-destructive/20 bg-destructive/10 text-destructive text-xs font-semibold rounded-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Username -->
                <div class="space-y-1">
                    <label for="username" class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tên đăng nhập') }}</label>
                    <div class="relative">
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            value="{{ old('username') }}" 
                            required
                            class="w-full h-9 pl-8 pr-3 text-xs border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="{{ __('Nhập mã số / tên đăng nhập') }}"
                            autocomplete="username"
                        >
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-muted-foreground">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Mật khẩu') }}</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required
                            class="w-full h-9 pl-8 pr-3 text-xs border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="{{ __('Nhập mật khẩu') }}"
                            autocomplete="current-password"
                        >
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-muted-foreground">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full h-9 flex items-center justify-center text-xs font-bold uppercase rounded-sm bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm transition-all">
                    {{ __('Đăng nhập') }}
                </button>
            </form>

            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-border"></div>
                <span class="flex-shrink mx-3 text-[10px] text-muted-foreground font-bold uppercase tracking-wider">{{ __('Hoặc') }}</span>
                <div class="flex-grow border-t border-border"></div>
            </div>

            <!-- SSO Button -->
            <a href="https://info.vttu.edu.vn" class="w-full h-9 flex items-center justify-center text-xs font-bold uppercase rounded-sm bg-card border border-border text-foreground hover:bg-muted transition-all shadow-sm">
                <i data-lucide="key" class="w-4 h-4 mr-1.5 text-muted-foreground"></i> {{ __('Cổng VTTU (SSO)') }}
            </a>
        @endif
        
        <!-- Back to Home -->
        <div class="text-center pt-2">
            <a href="{{ url('/') }}" class="inline-flex items-center text-xs text-muted-foreground hover:text-foreground transition-all gap-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                {{ __('Trang chủ') }}
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>