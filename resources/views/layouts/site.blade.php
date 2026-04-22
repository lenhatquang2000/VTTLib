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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                        <span class="font-bold text-xl text-gray-800">Thư viện số</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    @if(isset($menuItems))
                        @foreach($menuItems as $item)
                            @if($item->can_access ?? true)
                                @if($item->activeChildren->count() > 0)
                                    {{-- 🌟 Dropdown cho các mục có node con --}}
                                    <div class="relative group h-16 flex items-center">
                                        <a href="{{ $item->getUrl() }}" 
                                           class="flex items-center gap-1 text-gray-600 group-hover:text-blue-600 font-bold transition-all"
                                           @if($item->target === '_blank') target="_blank" @endif>
                                            {{ $item->display_name }}
                                            <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                                        </a>
                                        
                                        <!-- Dropdown Menu -->
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-64 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 z-[100]">
                                            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-3 overflow-hidden">
                                                @foreach($item->activeChildren as $child)
                                                    <a href="{{ $child->getUrl() }}" 
                                                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-50 text-slate-600 hover:text-blue-600 font-bold transition-all text-sm mb-1 last:mb-0">
                                                        <i class="{{ $child->icon ?: 'fas fa-chevron-right' }} text-xs opacity-50"></i>
                                                        {{ $child->display_name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Nút menu bình thường --}}
                                    <a href="{{ $item->getUrl() }}" 
                                       class="text-gray-600 hover:text-blue-600 font-bold transition-all"
                                       @if($item->target === '_blank') target="_blank" @endif>
                                        {{ $item->display_name }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                @if(isset($menuItems))
                    @foreach($menuItems as $item)
                        @if($item->can_access ?? true)
                            <a href="{{ $item->getUrl() }}" 
                               class="block py-2 text-gray-600 hover:text-blue-600 transition"
                               @if($item->target === '_blank') target="_blank" @endif>
                                {{ $item->display_name }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-book-open text-blue-400 text-xl"></i>
                        <span class="font-bold text-lg">Thư viện số</span>
                    </div>
                    <p class="text-gray-400">
                        Nền tảng quản lý thư viện hiện đại, hiệu quả và toàn diện.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4">Liên kết nhanh</h3>
                    @if(isset($menuItems))
                        <ul class="space-y-2">
                            @foreach($menuItems->take(5) as $item)
                                @if($item->can_access ?? true)
                                    <li>
                                        <a href="{{ $item->getUrl() }}" 
                                           class="text-gray-400 hover:text-white transition"
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
                    <h3 class="font-semibold mb-4">Dịch vụ</h3>
                    @if(isset($footerItems))
                        <ul class="space-y-2">
                            @foreach($footerItems as $item)
                                @if($item->can_access ?? true)
                                    <li>
                                        <a href="{{ $item->getUrl() }}" 
                                           class="text-gray-400 hover:text-white transition"
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
                    <h3 class="font-semibold mb-4">Liên hệ</h3>
                    @if(isset($footerItems))
                        @php
                            $contactNode = $footerItems->firstWhere('node_code', 'lien-he');
                        @endphp
                        @if($contactNode && $contactNode->content)
                            <div class="prose prose-sm text-gray-400">
                                {!! $contactNode->content !!}
                            </div>
                        @else
                            <ul class="space-y-2 text-gray-400">
                                <li class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    123 Đường ABC, Quận 1, TP.HCM
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    (028) 1234 5678
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    info@thuvienso.vn
                                </li>
                            </ul>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Thư viện số. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
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

    @yield('scripts')
</body>
</html>
