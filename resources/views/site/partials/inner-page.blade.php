{{--
    Shared inner page layout - Dùng chung cho các trang con mục Giới thiệu

    Params:
    - $node         (required) SiteNode hiện tại
    - $accent       (optional) Màu chủ đạo: blue, indigo, amber, emerald, violet
    - $badgeText    (optional) Text badge trên header
    - $badgeIcon    (optional) Icon class cho badge
    - $sectionLabel (optional) Tiêu đề sidebar
--}}

@php
    $accent       = $accent ?? 'primary';
    $badgeText    = $badgeText ?? $node->display_name;
    $badgeIcon    = $badgeIcon ?? ($node->icon ?? 'circle-info');
    $sectionLabel = ($node->parent) ? $node->parent->display_name : $node->display_name;

    // Sidebar: lấy các trang anh em
    $sidebarItems = collect();
    if ($node->parent) {
        $sidebarItems = $node->parent->activeChildren()->orderBy('sort_order')->get();
    } else {
        $sidebarItems = $node->activeChildren()->orderBy('sort_order')->get();
    }
    if ($sidebarItems->count() === 0) {
        $sidebarItems = collect([$node]);
    }

    // Lucide icon mapping from FA if possible, or fallback to database icon
    function getLucideIcon($faIcon) {
        $map = [
            'fas fa-circle-info'    => 'info',
            'fas fa-info-circle'    => 'info',
            'fas fa-bullseye'       => 'target',
            'fas fa-scale-balanced' => 'scale',
            'fas fa-clock'          => 'clock',
            'fas fa-sitemap'        => 'sitemap',
            'fas fa-compass'        => 'compass',
            'fas fa-home'           => 'home',
            'fas fa-search'         => 'search',
            'fas fa-phone'          => 'phone',
            'fas fa-university'     => 'landmark',
            'fas fa-concierge-bell' => 'bell',
            'fas fa-headset'        => 'headset',
            'fas fa-cloud'          => 'cloud',
            'fas fa-book-open-reader' => 'book-open',
            'fas fa-mobile-screen'  => 'smartphone',
            'fas fa-sign-in-alt'    => 'log-in',
            'fas fa-key'            => 'key',
            'fas fa-book-journal-whills' => 'book-open',
            'fas fa-file-pdf'       => 'file-text',
            'fas fa-calendar-check' => 'calendar-check',
            'fas fa-plus-circle'    => 'plus-circle',
            'fas fa-layer-group'    => 'layers',
            'fas fa-book'           => 'book',
            'fas fa-database'       => 'database',
            'fas fa-globe'          => 'globe',
            'fas fa-newspaper'      => 'newspaper',
            'fas fa-tablet-alt'     => 'tablet',
            'fas fa-graduation-cap' => 'graduation-cap',
            'fas fa-video'          => 'video',
            'fas fa-poll'           => 'bar-chart-3',
            'fas fa-map-marker-alt' => 'map-pin',
            'fas fa-arrow-left'     => 'arrow-left',
            'fas fa-arrow-right'    => 'arrow-right',
        ];
        return $map[$faIcon] ?? 'file-text';
    }
@endphp

<div class="min-h-screen bg-background text-foreground animate-fade-in pt-16" x-data="{ sidebarOpen: true }">

    <!-- Floating Expand Button when Sidebar is Collapsed -->
    <div x-show="!sidebarOpen"
         class="fixed left-0 top-1/2 -translate-y-1/2 z-[100]"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         x-cloak>
        <button @click="sidebarOpen = true; if(window.lucide) lucide.createIcons();"
                class="flex items-center justify-center w-10 h-12 bg-vttu-red text-white rounded-r-lg shadow-lg hover:bg-vttu-dark active:scale-95 transition-all border-y border-r border-white/20"
                title="{{ __('Mở rộng Sidebar') }}">
            <i data-lucide="panel-left-open" class="w-5 h-5"></i>
        </button>
    </div>

    <div class="w-full px-4 py-4 mt-[6px] md:px-6 md:py-6">
        <div class="flex flex-col lg:flex-row gap-4">
            
            <!-- Sidebar -->
            <aside class="lg:w-72 space-y-4 order-2 lg:order-1 transition-all duration-300 overflow-hidden lg:sticky lg:top-20 lg:self-start h-fit"
                   x-show="sidebarOpen"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 -translate-x-full"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 translate-x-0"
                   x-transition:leave-end="opacity-0 -translate-x-full">
                <!-- Navigation Card -->
                <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
                    <div class="p-3 bg-vttu-red border-b border-vttu-red/20 shadow-sm relative overflow-hidden group flex items-center justify-between">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 blur-xl rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-700"></div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="w-1 h-4 bg-vttu-yellow rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-white">{{ $sectionLabel }}</h3>
                        </div>
                        <button @click="sidebarOpen = false; if(window.lucide) lucide.createIcons();"
                                class="relative z-10 p-1.5 text-white/80 hover:text-white rounded hover:bg-white/10 active:scale-95 transition-all flex items-center justify-center"
                                title="{{ __('Thu gọn Sidebar') }}">
                            <i data-lucide="panel-left-close" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <nav class="p-2 space-y-1">
                        @foreach($sidebarItems as $item)
                            @php 
                                $active = $item->id === $node->id;
                            @endphp
                            <a href="{{ $item->getUrl() }}"
                               class="flex items-center gap-3 px-3 py-2 rounded text-sm transition-all relative group
                                      {{ $active 
                                         ? 'bg-vttu-red text-white font-bold shadow-md shadow-vttu-red/20' 
                                         : 'text-muted-foreground hover:bg-vttu-red/10 hover:text-vttu-red active:bg-vttu-red active:text-white active:scale-[0.98]' }}">
                                
                                @if(!$active)
                                    <div class="w-8 h-8 rounded-sm bg-gradient-to-br from-vttu-red to-vttu-dark shadow-vttu-red/25 flex items-center justify-center text-white flex-shrink-0 transition-all group-hover:scale-110 group-active:scale-95 group-active:bg-none group-active:text-vttu-yellow">
                                        <i data-lucide="{{ getLucideIcon($item->icon) }}" class="w-4 h-4"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 group-active:scale-95">
                                        <i data-lucide="{{ getLucideIcon($item->icon) }}" class="w-4 h-4 text-vttu-yellow"></i>
                                    </div>
                                @endif
                                
                                <span class="truncate transition-colors">{{ $item->display_name }}</span>
                                @if($active)
                                    <i data-lucide="chevron-right" class="w-3 h-3 ml-auto text-vttu-yellow/70 group-hover:translate-x-0.5 transition-transform"></i>
                                @endif
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- CTA Card -->
                <div class="bg-gradient-to-br from-vttu-red/10 to-vttu-dark/10 border border-vttu-red/20 rounded-md p-4 text-center space-y-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-vttu-red to-vttu-dark rounded-full flex items-center justify-center mx-auto text-vttu-yellow shadow-lg shadow-vttu-red/20">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-foreground italic">{{ __('Tra cứu OPAC') }}</h4>
                        <p class="text-xs text-muted-foreground leading-relaxed mt-1">{{ __('Tìm kiếm tài liệu trực tuyến') }}</p>
                    </div>
                    <a href="{{ route('opac.search') }}"
                       class="inline-flex items-center justify-center w-full px-4 py-2 bg-vttu-yellow text-vttu-dark text-xs font-black rounded shadow-sm hover:bg-yellow-400 active:scale-[0.98] transition-all">
                        {{ __('Tra cứu ngay') }} <i data-lucide="arrow-right" class="w-3 h-3 ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-4 order-1 lg:order-2 transition-all duration-300">
                @if($node->node_code === 'tai-lieu-so' || $node->masterpage === 'digital-resources')
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="p-1.5 rounded bg-muted hover:bg-primary/10 hover:text-primary active:scale-95 transition-all border border-border shadow-sm group"
                                    title="{{ __('Thu gọn/Mở rộng Sidebar') }}">
                                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 transition-transform duration-300" :class="!sidebarOpen && 'rotate-180'"></i>
                            </button>

                            <div class="w-1 h-3.5 bg-vttu-red rounded-full ml-1"></div>
                            <h2 class="text-xs font-black uppercase tracking-widest text-vttu-dark">{{ __('Tài nguyên') }}</h2>
                        </div>
                    </div>
                    
                    @if(request()->routeIs('site.digital-resources.view'))
                        @include('site.pages.partials.digital-resource-view-content')
                    @elseif(isset($resource))
                        @include('site.pages.partials.digital-resource-detail-content')
                    @else
                        @include('site.pages.partials.digital-list-content')
                    @endif
                @elseif($node->node_code === 'tai-nguyen-giao-duc-mo' || $node->masterpage === 'oer')
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="p-1.5 rounded bg-muted hover:bg-primary/10 hover:text-primary active:scale-95 transition-all border border-border shadow-sm group"
                                    title="{{ __('Thu gọn/Mở rộng Sidebar') }}">
                                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 transition-transform duration-300" :class="!sidebarOpen && 'rotate-180'"></i>
                            </button>

                            <div class="w-1 h-3.5 bg-vttu-red rounded-full ml-1"></div>
                            <h2 class="text-xs font-black uppercase tracking-widest text-vttu-dark">{{ __('Tài nguyên giáo dục mở') }}</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('site.oer.landing') }}" class="text-xs font-bold text-slate-600 hover:text-vttu-red transition-colors uppercase tracking-wider">{{ __('Trang chủ OER') }}</a>
                        </div>
                    </div>
                    
                    @if(request()->query('view') === 'landing')
                        @include('site.pages.partials.oer-landing-content')
                    @elseif(request()->query('view') === 'intro')
                        @include('site.pages.partials.oer-intro-content')
                    @elseif(request()->query('view') === 'contribute')
                        @include('site.pages.partials.oer-contribute-content')
                    @else
                        @include('site.pages.partials.oer-list-content')
                    @endif
                @elseif(isset($customContent) && $customContent === true)
                    @php
                        $openingTime = \App\Models\SystemSetting::get('opening_time', '08:00');
                        $closingTime = \App\Models\SystemSetting::get('closing_time', '17:00');
                        // Format time để hiển thị
                        $openingTimeFormatted = \Carbon\Carbon::createFromFormat('H:i', $openingTime)->format('H:i');
                        $closingTimeFormatted = \Carbon\Carbon::createFromFormat('H:i', $closingTime)->format('H:i');
                    @endphp
                    <!-- Custom Content từ page -->
                    <!-- Hero Section with Background Image -->
                    <div class="relative rounded-lg overflow-hidden shadow-lg mb-8 h-96"
                         style="background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 100%), url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1200&h=600&fit=crop'); background-size: cover; background-position: center;">
                        
                        <!-- Content Box -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                            <!-- Header Text -->
                            <div class="mb-6">
                                <h1 class="text-3xl md:text-4xl font-black text-vttu-red tracking-wider mb-1">
                                    THƯ VIỆN
                                </h1>
                                <h2 class="text-2xl md:text-3xl font-black text-vttu-red tracking-wider">
                                    ĐẠI HỌC VÔ TRƯỜNG TOẢN
                                </h2>
                                <div class="w-24 h-1 bg-vttu-red mx-auto mt-3"></div>
                            </div>

                            <!-- Main Title -->
                            <div class="mb-8">
                                <h3 class="text-2xl md:text-4xl font-black text-gray-900 tracking-widest" 
                                    style="text-shadow: 2px 2px 0 rgba(255,255,255,0.3); letter-spacing: 0.15em;">
                                    THỜI GIAN PHỤC VỤ
                                </h3>
                            </div>

                            <!-- Clock Icon Box -->
                            <div class="relative mb-8">
                                <div class="w-24 h-24 bg-gradient-to-br from-vttu-red to-vttu-dark rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                    <div class="text-white text-3xl">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box 1 -->
                            <div class="bg-white/95 backdrop-blur border-4 border-vttu-red rounded-lg px-8 py-6 mb-6 max-w-md shadow-lg">
                                <div class="text-gray-900">
                                    <p class="text-sm font-bold uppercase tracking-wider mb-3 text-vttu-dark">
                                        {{ __('Thứ Hai - Thứ Bảy') }}
                                    </p>
                                    <p class="text-4xl font-black text-gray-900 tracking-tight">
                                        {{ $openingTimeFormatted }} - {{ $closingTimeFormatted }}
                                    </p>
                                </div>
                            </div>

                            <!-- Info Box 2 -->
                            <div class="bg-white/95 backdrop-blur border-4 border-vttu-red rounded-lg px-8 py-6 max-w-md shadow-lg">
                                <div class="text-gray-900">
                                    <p class="text-sm font-bold uppercase tracking-wider mb-2 text-vttu-dark">
                                        {{ __('Chủ nhật và các ngày lễ') }}
                                    </p>
                                    <p class="text-2xl font-black text-gray-900 tracking-widest" style="letter-spacing: 0.1em;">
                                        KHÔNG HOẠT ĐỘNG
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Box 1 -->
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                    <i class="fas fa-calendar-days text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ __('Ngày làm việc') }}</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {{ __('Từ Thứ Hai đến Thứ Bảy, Thư viện mở cửa phục vụ bạn đọc trong suốt thời gian từ') }} <strong>{{ $openingTimeFormatted }}</strong> {{ __('đến') }} <strong>{{ $closingTimeFormatted }}</strong>{{ __('.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Box 2 -->
                        <div class="bg-gradient-to-br from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                    <i class="fas fa-ban text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ __('Ngày nghỉ') }}</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {{ __('Thư viện không hoạt động vào Chủ nhật, các ngày lễ, Tết theo quy định của Trường Đại học Võ Trường Toản.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notice Section -->
                    <div class="bg-vttu-red/10 border border-vttu-red/30 rounded-lg p-6 mb-8">
                        <div class="flex gap-4">
                            <div class="text-vttu-red text-2xl flex-shrink-0">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">{{ __('Thông báo quan trọng') }}</h4>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    {{ __('Vào các ngày lễ, Tết Dương lịch, Tết Âm lịch và các dịp lễ kỷ niệm theo quy định của nhà trường, thư viện sẽ đóng cửa. Vui lòng liên hệ với thư viện để biết thêm thông tin chi tiết.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg p-6 border border-slate-200">
                        <h4 class="font-bold text-gray-900 mb-4">{{ __('Liên hệ Thư viện') }}</h4>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="flex gap-3">
                                <div class="text-vttu-red text-xl flex-shrink-0">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">{{ __('Điện thoại') }}</p>
                                    <p class="text-gray-900 font-semibold">+84 (0) xxx xxxx</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="text-vttu-red text-xl flex-shrink-0">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">{{ __('Email') }}</p>
                                    <p class="text-gray-900 font-semibold">library@vttu.edu.vn</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="text-vttu-red text-xl flex-shrink-0">
                                    <i class="fas fa-map-pin"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">{{ __('Địa chỉ') }}</p>
                                    <p class="text-gray-900 font-semibold">Tầng 3, Tòa A</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(isset($customSitemap) && $customSitemap === true)
                    <!-- 3D Interactive Website Sitemap -->
                    <div class="w-full space-y-4">
                        <!-- Header Card -->
                        <div class="bg-gradient-to-r from-vttu-red to-vttu-dark text-white rounded-lg p-6 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-black tracking-wider mb-2">BẢN ĐỒ WEBSITE THƯVIỆN</h2>
                                    <p class="text-sm opacity-90">Khám phá cấu trúc tổ chức toàn bộ nội dung website Thư viện VTTU</p>
                                </div>
                                <div class="text-5xl opacity-20"><i class="fas fa-sitemap"></i></div>
                            </div>
                        </div>


                        <!-- 3D Canvas Container -->
                        <div class="relative bg-gray-900 rounded-lg overflow-hidden shadow-xl border border-gray-700" id="canvas-container" style="height: 600px;">
                            <canvas id="sitemap-canvas"></canvas>
                            
                            <!-- Info Panel (Overlay) -->
                            <div id="info-panel" class="absolute bottom-6 right-6 bg-white rounded-lg shadow-2xl p-6 max-w-sm hidden z-50 border-2 border-vttu-red transform transition-all duration-300 max-h-96 overflow-y-auto">
                                <button onclick="closeInfoPanel()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl sticky">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                <div id="info-content" class="text-sm"></div>
                            </div>

                            <!-- Breadcrumb Display -->
                            <div id="breadcrumb-panel" class="absolute top-4 left-4 bg-black/80 text-white rounded-lg px-4 py-2 text-xs hidden z-40 max-w-xs">
                                <div id="breadcrumb-content"></div>
                            </div>
                        </div>
                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
                    <script>
                        // Website structure data
                        const siteStructure = {
                            name: "Thư viện VTTU",
                            url: "/",
                            description: "Trang chủ Thư viện Đại học Võ Trường Toản - Nơi lưu giữ và chia sẻ tri thức",
                            role: "Trang chủ",
                            icon: "home",
                            children: [
                                {
                                    name: "Giới thiệu",
                                    url: "/page/gioi-thieu",
                                    description: "Tìm hiểu về lịch sử, sứ mệnh và tầm nhìn của Thư viện VTTU",
                                    role: "Mục giới thiệu",
                                    icon: "info",
                                    children: [
                                        { name: "Giới thiệu chung", url: "/page/gioi-thieu-chung", description: "Thông tin tổng quát về thư viện, các dịch vụ chính", role: "Trang con" },
                                        { name: "Thời gian phục vụ", url: "/thoi-gian-phuc-vu", description: "Giờ mở cửa, lịch nghỉ, thông tin liên hệ", role: "Trang con" },
                                        { name: "Nội quy thư viện", url: "/noi-quy-thu-vien", description: "Các quy định, điều kiện sử dụng dịch vụ", role: "Trang con" },
                                        { name: "Liên hệ", url: "/page/lien-he", description: "Thông tin liên lạc, địa chỉ, số điện thoại", role: "Trang con" }
                                    ]
                                },
                                {
                                    name: "Dịch vụ",
                                    url: "/page/dich-vu",
                                    description: "Các dịch vụ toàn diện mà thư viện cung cấp cho độc giả",
                                    role: "Mục dịch vụ",
                                    icon: "service",
                                    children: [
                                        { name: "Mượn trả tài liệu", url: "/page/muon-tra-tai-lieu", description: "Hướng dẫn quy trình mượn, trả, gia hạn tài liệu", role: "Trang con" },
                                        { name: "Tra cứu OPAC", url: "/opac", description: "Tìm kiếm sách, tài liệu trực tuyến trong danh mục", role: "Trang con" },
                                        { name: "Tài nguyên số", url: "/tai-lieu-so", description: "Tài liệu điện tử, database, tạp chí khoa học", role: "Trang con" }
                                    ]
                                },
                                {
                                    name: "Tài nguyên",
                                    url: "/tai-nguyen-giao-duc-mo",
                                    description: "Kho tài nguyên giáo dục mở - Hỗ trợ giảng dạy và học tập",
                                    role: "Mục tài nguyên",
                                    icon: "book",
                                    children: [
                                        { name: "Tài nguyên giáo dục mở", url: "/tai-nguyen-giao-duc-mo", description: "Tài nguyên OER - Các khóa học, giáo trình mở", role: "Trang con" },
                                        { name: "Tin tức", url: "/tin-tuc", description: "Tin tức hoạt động, thông báo sự kiện thư viện", role: "Trang con" }
                                    ]
                                },
                                {
                                    name: "Tra cứu",
                                    url: "/tra-cuu",
                                    description: "Công cụ tìm kiếm và khám phá tài liệu tổng hợp",
                                    role: "Mục tra cứu",
                                    icon: "search",
                                    children: [
                                        { name: "OPAC", url: "/opac", description: "Tra cứu catalog sách và tài liệu in", role: "Trang con" },
                                        { name: "Bản đồ Website", url: "/ban-do-website-thu-vien", description: "Bản đồ cấu trúc website - Khám phá cấu trúc tổ chức", role: "Trang con" }
                                    ]
                                }
                            ]
                        };

                        // Three.js Setup
                        const canvas = document.getElementById('sitemap-canvas');
                        const container = document.getElementById('canvas-container');
                        
                        const scene = new THREE.Scene();
                        scene.background = new THREE.Color(0x0f1419);
                        
                        const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
                        camera.position.set(0, 0, 20);
                        
                        const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
                        renderer.setSize(container.clientWidth, container.clientHeight);
                        renderer.setPixelRatio(window.devicePixelRatio);
                        
                        // Enhanced Lighting
                        const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
                        scene.add(ambientLight);
                        
                        const pointLight1 = new THREE.PointLight(0xffffff, 0.9);
                        pointLight1.position.set(15, 15, 15);
                        scene.add(pointLight1);

                        const pointLight2 = new THREE.PointLight(0x4488ff, 0.5);
                        pointLight2.position.set(-15, -15, -10);
                        scene.add(pointLight2);

                        // Create 3D nodes and structure
                        const nodeGroup = new THREE.Group();
                        scene.add(nodeGroup);
                        
                        const nodeMap = new Map();
                        const allMeshes = [];
                        let selectedNode = null;

                        function createTextTexture(text, size = 512) {
                            const canvas = document.createElement('canvas');
                            canvas.width = size;
                            canvas.height = size;
                            const ctx = canvas.getContext('2d');
                            
                            // Background
                            ctx.fillStyle = 'rgba(0, 0, 0, 0.85)';
                            ctx.fillRect(0, 0, size, size);
                            
                            // Border
                            ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
                            ctx.lineWidth = 2;
                            ctx.strokeRect(2, 2, size - 4, size - 4);
                            
                            // Text
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 40px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            
                            // Word wrap
                            const words = text.split(' ');
                            let lines = [];
                            let currentLine = '';
                            
                            for (let word of words) {
                                const testLine = currentLine + (currentLine ? ' ' : '') + word;
                                const metrics = ctx.measureText(testLine);
                                
                                if (metrics.width > size - 60) {
                                    if (currentLine) lines.push(currentLine);
                                    currentLine = word;
                                } else {
                                    currentLine = testLine;
                                }
                            }
                            if (currentLine) lines.push(currentLine);
                            
                            // Draw text
                            const lineHeight = 50;
                            const startY = (size - (lines.length - 1) * lineHeight) / 2;
                            
                            lines.forEach((line, i) => {
                                ctx.strokeStyle = '#000000';
                                ctx.lineWidth = 4;
                                ctx.strokeText(line, size / 2, startY + i * lineHeight);
                                ctx.fillStyle = '#ffffff';
                                ctx.fillText(line, size / 2, startY + i * lineHeight);
                            });
                            
                            const texture = new THREE.CanvasTexture(canvas);
                            texture.anisotropy = 16;
                            return texture;
                        }

                        // Create 3D icon texture from emoji/symbol
                        function createIconTexture(iconName, color = '#ffffff', size = 256) {
                            const canvas = document.createElement('canvas');
                            canvas.width = size;
                            canvas.height = size;
                            const ctx = canvas.getContext('2d');
                            
                            // Transparent background
                            ctx.clearRect(0, 0, size, size);
                            
                            // Icon mapping - using unicode symbols
                            const icons = {
                                'home': '🏠',
                                'info': 'ℹ️',
                                'book': '📚',
                                'search': '🔍',
                                'service': '⚙️',
                                'page': '📄'
                            };
                            
                            // Draw icon as large emoji/symbol
                            ctx.font = `bold ${size * 0.7}px Arial`;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(icons[iconName] || '●', size / 2, size / 2);
                            
                            const texture = new THREE.CanvasTexture(canvas);
                            return texture;
                        }

                        function createNode(data, x = 0, y = 0, z = 0, level = 0, parent = null) {
                            // Create cube instead of sphere for icon display
                            const cubeSize = level === 0 ? 1.2 : (level === 1 ? 0.9 : 0.7);
                            const geometry = new THREE.BoxGeometry(cubeSize, cubeSize, cubeSize);
                            
                            // Color based on level
                            let color, emissiveColor;
                            switch(level) {
                                case 0:
                                    color = new THREE.Color(0xff4444);
                                    emissiveColor = new THREE.Color(0xff0000);
                                    break;
                                case 1:
                                    color = new THREE.Color(0x4488ff);
                                    emissiveColor = new THREE.Color(0x2255cc);
                                    break;
                                default:
                                    color = new THREE.Color(0x44dd44);
                                    emissiveColor = new THREE.Color(0x22aa22);
                            }
                            
                            // Icon mapping
                            const iconMap = {
                                'Thư viện VTTU': 'home',
                                'Giới thiệu': 'info',
                                'Dịch vụ': 'service',
                                'Tài nguyên': 'book',
                                'Tra cứu': 'search'
                            };
                            const iconName = iconMap[data.name] || 'page';
                            
                            // Create materials for each face with icon
                            const iconTexture = createIconTexture(iconName, color.getHexString());
                            const materials = [
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture }),
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture }),
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture }),
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture }),
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture }),
                                new THREE.MeshPhongMaterial({ color: color, emissive: emissiveColor, emissiveIntensity: 0.4, shininess: 100, map: iconTexture })
                            ];
                            
                            const mesh = new THREE.Mesh(geometry, materials);
                            mesh.position.set(x, y, z);
                            mesh.userData = {
                                data: data,
                                level: level,
                                parent: parent,
                                originalColor: color,
                                originalEmissive: emissiveColor,
                                isLabel: false
                            };
                            
                            nodeGroup.add(mesh);
                            allMeshes.push(mesh);
                            
                            // Add text label above the node
                            const labelTexture = createTextTexture(data.name);
                            const labelMaterial = new THREE.MeshBasicMaterial({ map: labelTexture, transparent: true });
                            const labelWidth = level === 0 ? 3.5 : (level === 1 ? 3 : 2.2);
                            const labelHeight = labelWidth * 0.6;
                            const labelGeometry = new THREE.PlaneGeometry(labelWidth, labelHeight);
                            const labelMesh = new THREE.Mesh(labelGeometry, labelMaterial);
                            labelMesh.position.set(x, y + cubeSize / 2 + 1.2, z);
                            labelMesh.userData = {
                                isLabel: true,
                                node: mesh
                            };
                            nodeGroup.add(labelMesh);
                            
                            return mesh;
                        }

                        function createLine(from, to, isHighlight = false) {
                            const geometry = new THREE.BufferGeometry();
                            geometry.setAttribute('position', new THREE.BufferAttribute(
                                new Float32Array([from.x, from.y, from.z, to.x, to.y, to.z]),
                                3
                            ));
                            
                            const color = isHighlight ? 0xffaa00 : 0x555555;
                            const material = new THREE.LineBasicMaterial({ color: color, linewidth: isHighlight ? 3 : 1 });
                            const line = new THREE.Line(geometry, material);
                            nodeGroup.add(line);
                        }

                        // Build structure
                        const rootNode = createNode(siteStructure, 0, 0, 0, 0);
                        
                        // Position child sections
                        const sections = siteStructure.children;
                        const angleStep = (Math.PI * 2) / sections.length;
                        const sectionNodes = {};
                        
                        sections.forEach((section, sectionIndex) => {
                            const sectionAngle = angleStep * sectionIndex;
                            const sectionX = Math.cos(sectionAngle) * 9;
                            const sectionZ = Math.sin(sectionAngle) * 9;
                            
                            const sectionNode = createNode(section, sectionX, 0, sectionZ, 1, rootNode);
                            sectionNodes[section.name] = sectionNode;
                            createLine(rootNode.position, sectionNode.position);
                            
                            // Position child pages
                            if (section.children && section.children.length > 0) {
                                section.children.forEach((page, pageIndex) => {
                                    const pageAngle = (Math.PI * 2) / section.children.length * pageIndex;
                                    const pageX = sectionX + Math.cos(pageAngle) * 5;
                                    const pageY = 2.5 + Math.random() * 2;
                                    const pageZ = sectionZ + Math.sin(pageAngle) * 5;
                                    
                                    const pageNode = createNode(page, pageX, pageY, pageZ, 2, sectionNode);
                                    createLine(sectionNode.position, pageNode.position);
                                });
                            }
                        });

                        // Mouse controls
                        let isDragging = false;
                        let previousMousePosition = { x: 0, y: 0 };
                        let hoveredNode = null;

                        canvas.addEventListener('mousedown', (e) => {
                            isDragging = true;
                            previousMousePosition = { x: e.clientX, y: e.clientY };
                        });

                        canvas.addEventListener('mousemove', (e) => {
                            if (isDragging) {
                                const deltaX = e.clientX - previousMousePosition.x;
                                const deltaY = e.clientY - previousMousePosition.y;

                                nodeGroup.rotation.y += deltaX * 0.01;
                                nodeGroup.rotation.x += deltaY * 0.01;

                                previousMousePosition = { x: e.clientX, y: e.clientY };
                            }
                        });

                        canvas.addEventListener('mouseup', () => {
                            isDragging = false;
                        });

                        canvas.addEventListener('wheel', (e) => {
                            e.preventDefault();
                            camera.position.z += e.deltaY * 0.01;
                            camera.position.z = Math.max(5, Math.min(50, camera.position.z));
                        });

                        // Raycasting for node selection
                        const raycaster = new THREE.Raycaster();
                        const mouse = new THREE.Vector2();

                        canvas.addEventListener('click', (event) => {
                            mouse.x = (event.clientX / container.clientWidth) * 2 - 1;
                            mouse.y = -(event.clientY / container.clientHeight) * 2 + 1;

                            raycaster.setFromCamera(mouse, camera);
                            const intersects = raycaster.intersectObjects(allMeshes);

                            if (intersects.length > 0) {
                                const clickedMesh = intersects[0].object;
                                selectedNode = clickedMesh;
                                showInfoPanel(clickedMesh.userData);
                                showBreadcrumb(clickedMesh.userData);
                            }
                        });

                        function showInfoPanel(userData) {
                            const infoPanelContent = document.getElementById('info-content');
                            const infoPanel = document.getElementById('info-panel');
                            
                            const data = userData.data;
                            const pathToRoot = getPathToRoot(userData);
                            const pathDisplay = pathToRoot.map(d => d.name).reverse().join(' > ');
                            
                            let html = `
                                <div class="space-y-4">
                                    <div class="pb-3 border-b-2 border-vttu-red">
                                        <p class="text-xs text-gray-500 mb-1 font-bold uppercase tracking-wider">Vị trí trong website</p>
                                        <p class="text-xs text-gray-700 font-semibold">${pathDisplay}</p>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-lg font-black text-vttu-red mb-1">${data.name}</h3>
                                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">${data.role}</p>
                                        <p class="text-sm text-gray-700 leading-relaxed">${data.description}</p>
                                    </div>
                                    
                                    <div class="bg-gray-100 rounded px-3 py-2 text-xs border-l-4 border-vttu-red">
                                        <p class="text-gray-600 font-bold mb-1">📍 Endpoint:</p>
                                        <p class="text-vttu-red font-mono font-bold text-[11px] break-all">${data.url}</p>
                                    </div>
                                    
                                    <!-- Navigation Buttons -->
                                    <div class="space-y-2 pt-2">
            `;
                            
                            // Parent navigation
                            if (userData.parent && userData.parent.userData && userData.parent.userData.data) {
                                const parentData = userData.parent.userData.data;
                                html += `
                                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded border border-blue-300 p-3">
                                        <p class="text-xs font-bold text-blue-900 mb-2 flex items-center gap-1">⬆️ Trang mục cha</p>
                                        <button onclick="navigateToNode('${parentData.name}')" class="w-full px-2 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-black rounded transition-all transform hover:scale-105 active:scale-95">
                                            📍 ${parentData.name}
                                        </button>
                                    </div>
                                `;
                            }
                            
                            // Sibling navigation
                            if (userData.parent && userData.parent.userData && userData.parent.userData.data) {
                                const siblings = userData.parent.userData.data.children || [];
                                const nonSelfSiblings = siblings.filter(s => s.name !== data.name);
                                
                                if (nonSelfSiblings.length > 0) {
                                    html += `
                                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded border border-purple-300 p-3">
                                            <p class="text-xs font-bold text-purple-900 mb-2 flex items-center gap-1">↔️ Các trang anh em (${nonSelfSiblings.length})</p>
                                            <div class="space-y-1 grid grid-cols-1 gap-1">
                                    `;
                                    nonSelfSiblings.forEach(sibling => {
                                        html += `
                                            <button onclick="navigateToNode('${sibling.name}')" class="px-2 py-1.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-xs font-bold rounded transition-all transform hover:scale-105 active:scale-95 text-left">
                                                → ${sibling.name}
                                            </button>
                                        `;
                                    });
                                    html += `
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                            
                            // Children navigation
                            if (data.children && data.children.length > 0) {
                                html += `
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded border border-green-300 p-3">
                                        <p class="text-xs font-bold text-green-900 mb-2 flex items-center gap-1">⬇️ Các trang con (${data.children.length})</p>
                                        <div class="space-y-1">
                                `;
                                data.children.forEach(child => {
                                    html += `
                                        <button onclick="navigateToNode('${child.name}')" class="w-full px-2 py-1.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-xs font-bold rounded transition-all transform hover:scale-105 active:scale-95 text-left">
                                            ├─ ${child.name}
                                        </button>
                                    `;
                                });
                                html += `
                                        </div>
                                    </div>
                                `;
                            }
                            
                            html += `
                                    </div>
                                    
                                    <div class="pt-3 border-t-2 border-gray-200 flex gap-2">
                                        <a href="${data.url}" target="_blank" class="flex-1 px-3 py-2 bg-vttu-red text-white text-xs font-black rounded hover:bg-vttu-dark transition-all transform hover:scale-105 active:scale-95 text-center">
                                            🔗 Truy cập
                                        </a>
                                        <button onclick="closeInfoPanel()" class="px-3 py-2 bg-gray-300 text-gray-700 text-xs font-black rounded hover:bg-gray-400 transition-all transform hover:scale-105 active:scale-95">
                                            ✕ Đóng
                                        </button>
                                    </div>
                                </div>
                            `;
                            
                            infoPanelContent.innerHTML = html;
                            infoPanel.classList.remove('hidden');
                        }

                        function getPathToRoot(userData, path = []) {
                            path.push(userData.data);
                            if (userData.parent && userData.parent.userData) {
                                return getPathToRoot(userData.parent.userData, path);
                            }
                            return path;
                        }

                        function showBreadcrumb(userData) {
                            const breadcrumbPanel = document.getElementById('breadcrumb-panel');
                            const breadcrumbContent = document.getElementById('breadcrumb-content');
                            
                            const pathToRoot = getPathToRoot(userData);
                            const pathDisplay = pathToRoot.map(d => d.name).reverse().join(' / ');
                            
                            breadcrumbContent.innerHTML = `<i class="fas fa-map-pin mr-2"></i> ${pathDisplay}`;
                            breadcrumbPanel.classList.remove('hidden');
                        }

                        function closeInfoPanel() {
                            document.getElementById('info-panel').classList.add('hidden');
                            document.getElementById('breadcrumb-panel').classList.add('hidden');
                            selectedNode = null;
                        }

                        function navigateToPage() {
                            const select = document.getElementById('navigate-select');
                            if (select.value) {
                                window.location.href = select.value;
                            }
                        }

                        // Navigate to a specific node by name and highlight it
                        function navigateToNode(nodeName) {
                            // Find the mesh with matching node name
                            let targetMesh = null;
                            
                            allMeshes.forEach(mesh => {
                                if (mesh.userData.data && mesh.userData.data.name === nodeName) {
                                    targetMesh = mesh;
                                }
                            });
                            
                            if (targetMesh) {
                                // Update selected node
                                selectedNode = targetMesh;
                                
                                // Show info panel
                                showInfoPanel(targetMesh.userData);
                                showBreadcrumb(targetMesh.userData);
                                
                                // Animate camera to focus on the node
                                const targetPos = targetMesh.position;
                                const direction = new THREE.Vector3();
                                direction.subVectors(targetPos, camera.position).normalize();
                                
                                // Smooth camera animation
                                let startPos = camera.position.clone();
                                let animationProgress = 0;
                                
                                function animateCamera() {
                                    animationProgress += 0.05;
                                    
                                    if (animationProgress < 1) {
                                        const newPos = startPos.clone().lerp(targetPos.clone().add(direction.clone().multiplyScalar(15)), animationProgress);
                                        camera.position.copy(newPos);
                                        camera.lookAt(targetPos);
                                        requestAnimationFrame(animateCamera);
                                    } else {
                                        camera.position.copy(targetPos).add(direction.clone().multiplyScalar(15));
                                        camera.lookAt(targetPos);
                                    }
                                }
                                
                                animateCamera();
                            }
                        }

                        // Animation loop
                        function animate() {
                            requestAnimationFrame(animate);
                            
                            // Auto-rotate slowly when not dragging
                            if (!isDragging) {
                                nodeGroup.rotation.y += 0.0001;
                            }
                            
                            // Pulsing effect on selected node
                            if (selectedNode) {
                                const time = Date.now() * 0.002;
                                const scale = 1 + Math.sin(time) * 0.2;
                                selectedNode.scale.set(scale, scale, scale);
                            }
                            
                            // Make labels always face camera
                            nodeGroup.children.forEach(child => {
                                if (child.userData && child.userData.isLabel) {
                                    child.lookAt(camera.position);
                                }
                            });
                            
                            renderer.render(scene, camera);
                        }
                        animate();

                        // Handle window resize
                        window.addEventListener('resize', () => {
                            const width = container.clientWidth;
                            const height = container.clientHeight;
                            camera.aspect = width / height;
                            camera.updateProjectionMatrix();
                            renderer.setSize(width, height);
                        });

                        // Close panel when clicking outside
                        canvas.addEventListener('contextmenu', (e) => {
                            e.preventDefault();
                            closeInfoPanel();
                        });
                    </script>
                @else
                    <article class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden text-gray-500">
                        @php 
                        $hasDarkBg = isset($sidebarIcons[$node->icon]);
                        $headerColors = $sidebarIcons[$node->icon] ?? ['from-muted/20 to-muted/10', '']; 
                    @endphp
                    @if($node->node_code !== 'huong-dan' && $node->node_code !== 'gioi-thieu')
                    <div class="p-4 border-b border-border bg-gradient-to-r {{ $headerColors[0] }} opacity-90">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded flex items-center justify-center border shadow-sm
                                        {{ $hasDarkBg ? 'bg-white/10 text-white border-white/20' : 'bg-vttu-red/10 text-vttu-red border-vttu-red/20' }}">
                                <i data-lucide="{{ getLucideIcon($node->icon) }}" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest
                                          {{ $hasDarkBg ? 'text-white/70' : 'text-vttu-red/80' }}">{{ $sectionLabel }}</p>
                                <h1 class="text-xl md:text-2xl font-black tracking-tight
                                           {{ $hasDarkBg ? 'text-white' : 'text-vttu-red' }}">{{ $node->display_name }}</h1>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Article Body -->
                    <div class="p-4 md:p-6">
                        <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none 
                                    prose-headings:text-foreground prose-headings:font-bold
                                    prose-p:text-muted-foreground prose-p:leading-relaxed
                                    prose-strong:text-foreground
                                    prose-a:text-vttu-red prose-a:font-bold prose-a:no-underline hover:prose-a:underline
                                    prose-img:rounded-md prose-img:border prose-img:border-border shadow-vttu-red/5">
                            {{-- Hiển thị header image cho trang nội quy thư viện --}}
                            @if($node->node_code === 'noi-quy-thu-vien')
                                @include('site.pages.noi-quy-thu-vien-header')
                            @endif
                            
                            @if($node->node_code === 'huong-dan')
                                @include('site.pages.huong-dan-content')
                            @elseif($node->node_code === 'gioi-thieu-chung')
                                @include('site.pages.gioi-thieu-chung-content')
                            @elseif($node->node_code === 'co-so-du-lieu')
                                @include('site.pages.co-so-du-lieu-content')
                            @elseif($node->node_code === 'noi-quy-thu-vien')
                                @include('site.pages.noi-quy-thu-vien-content')
                            @elseif($node->node_code === 'cam-nang-hdsd')
                                @include('site.pages.cam-nang-hdsd-content')
                            @elseif($node->node_code === 'tai-app-mobile')
                                @include('site.pages.tai-app-mobile-content')
                            @elseif($node->node_code === 'dang-nhap-tai-khoan')
                                @include('site.pages.dang-nhap-tai-khoan-content')
                            @elseif($node->node_code === 'doi-mat-khau')
                                @include('site.pages.doi-mat-khau-content')
                            @elseif($node->node_code === 'tra-cuu-tai-lieu-giay')
                                @include('site.pages.tra-cuu-tai-lieu-giay-content')
                            @elseif($node->node_code === 'tra-cuu-tai-lieu-so')
                                @include('site.pages.tra-cuu-tai-lieu-so-content')
                            @elseif($node->node_code === 'muon-truoc-gia-han')
                                @include('site.pages.muon-truoc-gia-han-content')
                            @elseif($node->node_code === 'de-nghi-bo-sung')
                                @include('site.pages.de-nghi-bo-sung-content')
                            @else
                                {!! $node->content !!}
                            @endif
                        </div>
                        
                        {{-- Hiển thị component tiện ích thư viện cho trang giới thiệu chung --}}
                        @if($node->node_code === 'gioi-thieu-chung')
                            <div class="mt-8 pt-8 border-t border-border">
                                @include('site.partials.library-benefits')
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Footer -->
                        <div class="p-3 border-t border-border bg-muted/20 grid grid-cols-2 gap-3">
                            @php
                                $prev = $sidebarItems->where('sort_order', '<', $node->sort_order)->last();
                                $next = $sidebarItems->where('sort_order', '>', $node->sort_order)->first();
                            @endphp
                            
                            <div>
                                @if($prev)
                                    <a href="{{ $prev->getUrl() }}" class="flex items-center gap-2 p-2 rounded border border-border bg-card hover:bg-muted hover:border-vttu-red/30 active:bg-accent transition-all group">
                                        <div class="w-7 h-7 rounded bg-muted flex items-center justify-center text-muted-foreground group-hover:bg-vttu-red group-hover:text-white group-active:scale-90 transition-all">
                                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-[8px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Trước') }}</p>
                                            <p class="text-[11px] font-bold text-foreground truncate group-hover:text-vttu-red transition-colors">{{ $prev->display_name }}</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                @if($next)
                                    <a href="{{ $next->getUrl() }}" class="flex items-center justify-end gap-2 p-2 rounded border border-border bg-card hover:bg-muted hover:border-vttu-red/30 active:bg-accent transition-all group">
                                        <div class="overflow-hidden text-right">
                                            <p class="text-[8px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tiếp') }}</p>
                                            <p class="text-[11px] font-bold text-foreground truncate group-hover:text-vttu-red transition-colors">{{ $next->display_name }}</p>
                                        </div>
                                        <div class="w-7 h-7 rounded bg-muted flex items-center justify-center text-muted-foreground group-hover:bg-vttu-red group-hover:text-white group-active:scale-90 transition-all">
                                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endif

                <!-- Page Builder Blocks -->
                @if(isset($node) && $node->activeItems->count() > 0)
                    <div class="space-y-3">
                        @foreach($node->activeItems as $item)
                            <div class="bg-card border border-border rounded-md shadow-sm p-3">
                                @php $itemData = is_string($item->item_data) ? json_decode($item->item_data, true) : $item->item_data; @endphp
                                @includeIf('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData])
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
