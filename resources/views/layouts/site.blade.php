<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Thư viện số')</title>
    
    <!-- SEO Meta Tags -->
    @if(isset($node))
        @section('meta-description')
            <meta name="description" content="{{ $node->meta_description ?: Str::limit(strip_tags($node->content ?? ''), 160) }}">
        @show
        @section('meta-keywords')
            <meta name="keywords" content="{{ $node->meta_keywords ?: 'thư viện, số, quản lý, sách' }}">
        @show
    @else
        <meta name="description" content="Thư viện số - Nền tảng quản lý thư viện hiện đại">
        <meta name="keywords" content="thư viện, số, quản lý, sách, OPAC">
    @endif
    
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Be Vietnam Pro', 'sans-serif'],
                        montserrat: ['Be Vietnam Pro', 'sans-serif'],
                    },
                    colors: {
                        vttu: {
                            dark: '#680102',
                            red: '#7B0000',
                            yellow: '#FFD700',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @stack('styles')
    <!-- Custom Styles -->
    <style>
        .prose {
            max-width: none;
        }
        
        /* Floating Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Gradient Text Animation */
        @keyframes gradient-text {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient-text {
            background: linear-gradient(-45deg, #3b82f6, #06b6d4, #8b5cf6, #ec4899);
            background-size: 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-text 5s ease infinite;
        }

        /* 3D Card Hover */
        .card-3d {
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            perspective: 1000px;
        }
        .card-3d:hover {
            transform: translateY(-10px) rotateX(5deg) rotateY(2deg);
        }

        .prose h1, .prose h2, .prose h3 {
            color: #1f2937;
            font-weight: bold;
        }
        .prose h1 { font-size: 2.5rem; margin-top: 2rem; margin-bottom: 1rem; }
        .prose h2 { font-size: 2rem; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .prose h3 { font-size: 1.5rem; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
        .prose a {
            color: #2563eb;
            text-decoration: none;
        }
        .prose a:hover {
            text-decoration: underline;
        }
        .prose blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: #6b7280;
        }
        .prose code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header id="siteHeader" class="fixed top-0 left-0 w-full z-50 transition-all duration-500 bg-vttu-dark shadow-xl">
        <nav class="container mx-auto px-4 md:px-12 lg:px-24 py-3 transition-all duration-500" id="headerNav">
            <div class="flex justify-between items-center px-8 py-2 transition-all duration-500" id="headerContainer">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-10 w-10 object-contain transition-all duration-500" id="headerLogo">
                        @else
                            <i class="fas fa-book-open text-vttu-yellow text-2xl transition-all duration-500" id="headerLogoIcon"></i>
                        @endif
                        <span class="font-black text-xl text-white tracking-tighter transition-all duration-500" id="headerTitle">{{ \App\Models\SystemSetting::get('site_name', 'VTTLib') }}</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    @if(isset($menuItems))
                        @foreach($menuItems as $item)
                            <div class="relative group">
                                <a href="{{ $item->getUrl() }}" 
                                   class="text-white/80 hover:text-white font-black text-xs uppercase tracking-[0.2em] transition-all py-2 block">
                                    {{ $item->display_name }}
                                </a>
                                <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-vttu-yellow transition-all group-hover:w-full"></div>
                            </div>
                        @endforeach
                    @endif
                    
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-3 px-6 py-2.5 bg-white/10 hover:bg-white text-white hover:text-vttu-red rounded-full border border-white/20 transition-all shadow-lg backdrop-blur-md group-hover:shadow-vttu-red/20">
                                <div class="w-7 h-7 bg-vttu-yellow text-vttu-dark rounded-full flex items-center justify-center text-[10px] font-black">
                                    {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username, 0, 1)) }}
                                </div>
                                <span class="font-black text-xs uppercase tracking-widest whitespace-nowrap">{{ Auth::user()->full_name ?? Auth::user()->username }}</span>
                                <i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:rotate-180 transition-transform"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-3xl shadow-2xl border border-slate-100 py-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform translate-y-2 group-hover:translate-y-0 z-[60]">
                                <div class="px-6 py-2 border-b border-slate-50 mb-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tài khoản</p>
                                    <p class="text-sm font-black text-vttu-dark truncate">{{ Auth::user()->username }}</p>
                                </div>
                                @if(!Auth::user()->hasRole('visitor'))
                                    <a href="/topsecret/dashboard" class="flex items-center space-x-3 px-6 py-3 text-vttu-dark/80 hover:text-vttu-red hover:bg-vttu-red/5 transition-all">
                                        <i class="fas fa-tachometer-alt w-5"></i>
                                        <span class="text-xs font-black uppercase tracking-widest">Quản trị</span>
                                    </a>
                                @endif
                                <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-6 py-3 text-vttu-dark/80 hover:text-vttu-red hover:bg-vttu-red/5 transition-all">
                                    <i class="fas fa-user-circle w-5"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">Hồ sơ</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="mt-2 border-t border-slate-50 pt-2">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-3 px-6 py-3 text-red-500 hover:bg-red-50 transition-all text-left">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span class="text-xs font-black uppercase tracking-widest">Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-white/10 hover:bg-white text-white hover:text-vttu-red font-black text-xs uppercase tracking-widest rounded-full border border-white/20 transition-all shadow-lg backdrop-blur-md">
                            Đăng nhập
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2 bg-white/10 rounded-xl text-white">
                        <i class="fas fa-bars-staggered text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden mt-4 bg-slate-900/95 backdrop-blur-2xl rounded-[2rem] border border-white/10 p-6 shadow-2xl">
                @if(isset($menuItems))
                    @foreach($menuItems as $item)
                        <a href="{{ $item->getUrl() }}" 
                           class="block py-4 text-white/80 hover:text-white font-black text-sm uppercase tracking-widest border-b border-white/5 last:border-0">
                            {{ $item->display_name }}
                        </a>
                    @endforeach
                @endif
                
                @auth
                    <div class="py-4 border-b border-white/5">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Đã đăng nhập</p>
                        <p class="text-white font-black">{{ Auth::user()->full_name ?? Auth::user()->username }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full mt-6 py-4 bg-vttu-red text-white text-center font-black rounded-2xl uppercase tracking-widest text-xs">
                            ĐĂNG XUẤT
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block mt-6 py-4 bg-vttu-red text-white text-center font-black rounded-2xl">
                        ĐĂNG NHẬP
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-vttu-dark text-white py-12 border-t border-vttu-red/20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-book-open text-vttu-yellow text-xl"></i>
                        <span class="font-bold text-lg">Thư viện số</span>
                    </div>
                    <p class="text-white/60">
                        Nền tảng quản lý thư viện hiện đại, hiệu quả và toàn diện.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4 text-vttu-yellow">Liên kết nhanh</h3>
                    @if(isset($menuItems))
                        <ul class="space-y-2">
                            @foreach($menuItems->take(5) as $item)
                                @if($item->can_access ?? true)
                                    <li>
                                        <a href="{{ $item->getUrl() }}" 
                                           class="text-white/60 hover:text-vttu-yellow transition"
                                           @if($item->target === '_blank') target="_blank" @endif>
                                            {{ $item->display_name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Services -->
                <div>
                    <h3 class="font-semibold mb-4 text-vttu-yellow">Dịch vụ</h3>
                    @if(isset($footerItems))
                        <ul class="space-y-2">
                            @foreach($footerItems as $item)
                                @if($item->can_access ?? true)
                                    <li>
                                        <a href="{{ $item->getUrl() }}" 
                                           class="text-white/60 hover:text-vttu-yellow transition"
                                           @if($item->target === '_blank') target="_blank" @endif>
                                            {{ $item->display_name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-semibold mb-4 text-vttu-yellow">Liên hệ</h3>
                    @if(isset($footerItems))
                        @php
                            $contactNode = $footerItems->firstWhere('node_code', 'lien-he');
                        @endphp
                        @if($contactNode && $contactNode->content)
                            <div class="prose prose-sm text-white/60">
                                {!! $contactNode->content !!}
                            </div>
                        @else
                            <ul class="space-y-2 text-white/60">
                                <li class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-vttu-yellow"></i>
                                    123 Đường ABC, Quận 1, TP.HCM
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-phone mr-2 text-vttu-yellow"></i>
                                    (028) 1234 5678
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-envelope mr-2 text-vttu-yellow"></i>
                                    info@thuvienso.vn
                                </li>
                            </ul>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-white/10 mt-8 pt-8 text-center text-white/40">
                <p>&copy; {{ date('Y') }} Thư viện số. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Global Image Fallback Handler
        document.addEventListener('error', function (e) {
            if (e.target.tagName.toLowerCase() === 'img') {
                // Sử dụng ảnh placeholder của VTTU hoặc dịch vụ placeholder uy tín
                const fallbackUrl = "https://placehold.co/800x600/7B0000/FFFFFF?text=VTTU+Library";
                
                // Tránh lặp vô tận nếu chính ảnh fallback cũng lỗi
                if (e.target.src !== fallbackUrl) {
                    e.target.src = fallbackUrl;
                }
            }
        }, true);

        // Header transparency handling (Disabled as per user request for solid bg)
        window.addEventListener('scroll', function() {
            const header = document.getElementById('siteHeader');
            const navLinks = document.querySelectorAll('#siteHeader a:not(.bg-white):not(.rounded-full), #siteHeader button:not(.lg\\:hidden)');
            
            if (window.scrollY > 50) {
                header.classList.add('shadow-2xl');
            } else {
                header.classList.remove('shadow-2xl');
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new Event('scroll'));
        });

        AOS.init({
            duration: 500,
            once: true,
            easing: 'ease-out',
        });
    </script>
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
