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
    $accent       = $accent ?? 'blue';
    $badgeText    = $badgeText ?? $node->display_name;
    $badgeIcon    = $badgeIcon ?? ($node->icon ?? 'fas fa-info-circle');
    $sectionLabel = $sectionLabel ?? 'Giới thiệu';

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

    // Icon → gradient mapping cho sidebar
    $sidebarIcons = [
        'fas fa-circle-info'    => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-bullseye'       => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-scale-balanced' => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-clock'          => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-sitemap'        => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
    ];

    // ── Accent color mapping (Tailwind-safe) ──
    $accentGradient = 'from-vttu-red to-vttu-dark';
    $accentShadow = 'shadow-vttu-red/20';
    $accentDivider = 'from-vttu-red/30';
    $activeNav = 'bg-vttu-red text-white shadow-lg shadow-vttu-red/25';
    $badgeColor = 'text-vttu-red';
    $pingColor = 'bg-vttu-red';
@endphp

<div class="min-h-screen bg-slate-50">

    {{-- ══════════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-white border-b border-slate-100">
        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10 pt-12 pb-16">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-vttu-red/60 mb-8" data-aos="fade-down" data-aos-delay="100">
                <a href="/" class="hover:text-vttu-red transition-colors"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right text-[7px]"></i>
                <a href="/page/gioi-thieu" class="hover:text-vttu-red transition-colors">Giới thiệu</a>
                <i class="fas fa-chevron-right text-[7px]"></i>
                <span class="text-vttu-red font-black">{{ $node->display_name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-delay="200">
                    <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-vttu-red/5 border border-vttu-red/10 backdrop-blur-xl text-[10px] font-black uppercase tracking-[0.35em] text-vttu-red mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-vttu-red opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-vttu-red"></span>
                        </span>
                        {{ $badgeText }}
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-6 text-vttu-dark">
                        <span class="text-vttu-red">Thư viện</span><br>
                        <span>Đại học Võ Trường Toản</span>
                    </h1>
                    <p class="text-lg text-vttu-dark/70 leading-relaxed max-w-xl">
                        {{ $node->description }}
                    </p>
                </div>

                {{-- Stats cards --}}
                <div class="grid grid-cols-2 gap-5" data-aos="fade-left" data-aos-delay="400">
                    <div class="group p-7 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-vttu-red/10 flex items-center justify-center mb-5 group-hover:bg-vttu-red group-hover:text-white transition-all">
                            <i class="fas fa-calendar-alt text-vttu-red group-hover:text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-vttu-dark mb-1">15+</div>
                        <div class="text-xs font-bold text-vttu-red/40 uppercase tracking-widest">Năm hoạt động</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-vttu-red/10 flex items-center justify-center mb-5 group-hover:bg-vttu-red group-hover:text-white transition-all">
                            <i class="fas fa-book-open text-vttu-red group-hover:text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-vttu-dark mb-1">50K+</div>
                        <div class="text-xs font-bold text-vttu-red/40 uppercase tracking-widest">Tài liệu</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-vttu-red/10 flex items-center justify-center mb-5 group-hover:bg-vttu-red group-hover:text-white transition-all">
                            <i class="fas fa-users text-vttu-red group-hover:text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-vttu-dark mb-1">10K+</div>
                        <div class="text-xs font-bold text-vttu-red/40 uppercase tracking-widest">Bạn đọc / Năm</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-vttu-red/10 flex items-center justify-center mb-5 group-hover:bg-vttu-red group-hover:text-white transition-all">
                            <i class="fas fa-laptop text-vttu-red group-hover:text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-vttu-dark mb-1">24/7</div>
                        <div class="text-xs font-bold text-vttu-red/40 uppercase tracking-widest">Tra cứu Online</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════
         SIDEBAR + ARTICLE
    ══════════════════════════════════════════════ --}}
    <section class="py-16">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- ── Sidebar ── --}}
                <aside class="lg:col-span-3 order-2 lg:order-1" data-aos="fade-right" data-aos-delay="100">
                    <div class="sticky top-24 space-y-6">
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/20 overflow-hidden">
                            <div class="p-7 bg-vttu-dark text-white relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-vttu-red/20 blur-2xl rounded-full"></div>
                                <div class="relative z-10">
                                    <div class="text-[9px] font-black uppercase tracking-[0.4em] text-vttu-yellow/70 mb-2">Chuyên mục</div>
                                    <div class="text-xl font-black">{{ $sectionLabel }}</div>
                                </div>
                            </div>
                            <nav class="p-3">
                                @foreach($sidebarItems as $item)
                                    @php
                                        $active = $item->id === $node->id;
                                        $iconColors = $sidebarIcons[$item->icon] ?? ['from-slate-500 to-slate-400', 'shadow-slate-500/25'];
                                    @endphp
                                    <a href="{{ $item->getUrl() }}"
                                       class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 mb-1
                                              {{ $active ? $activeNav : 'text-vttu-dark/70 hover:bg-slate-50 hover:text-vttu-red' }}">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs flex-shrink-0 transition-all
                                                    {{ $active
                                                        ? 'bg-white/20 text-white'
                                                        : 'bg-vttu-red/10 text-vttu-red group-hover:scale-110' }}">
                                            <i class="{{ $item->icon }}"></i>
                                        </div>
                                        <span class="font-bold text-sm leading-tight">{{ $item->display_name }}</span>
                                        @if($active)
                                            <i class="fas fa-chevron-right text-[9px] text-white/50 ml-auto"></i>
                                        @endif
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        {{-- CTA card --}}
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-vttu-red/20 rounded-3xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity"></div>
                            <div class="relative bg-vttu-dark rounded-3xl p-8 text-white overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-vttu-red/15 blur-3xl rounded-full"></div>
                                <div class="relative z-10 text-center">
                                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-5 backdrop-blur-sm">
                                        <i class="fas fa-search text-xl text-vttu-yellow"></i>
                                    </div>
                                    <h4 class="font-black text-base mb-2">Tra cứu OPAC</h4>
                                    <p class="text-white/60 text-xs leading-relaxed mb-5">Tìm kiếm tài liệu trực tuyến trong hệ thống Thư viện.</p>
                                    <a href="http://opac.vttu.edu.vn" target="_blank"
                                       class="block w-full py-3.5 bg-vttu-yellow text-vttu-dark rounded-xl font-black text-sm hover:bg-yellow-400 transition-all shadow-lg">
                                        Tra cứu ngay <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- ── Main Content ── --}}
                <div class="lg:col-span-9 order-1 lg:order-2 space-y-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/20 overflow-hidden">
                        {{-- Article header --}}
                        <div class="px-10 pt-10 pb-0">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-vttu-red/10 flex items-center justify-center shadow-lg">
                                    <i class="{{ $badgeIcon }} text-vttu-red text-lg"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-vttu-red/60 uppercase tracking-[0.3em]">{{ $sectionLabel }}</div>
                                    <h2 class="text-2xl md:text-3xl font-black text-vttu-dark tracking-tight">{{ $node->display_name }}</h2>
                                </div>
                            </div>
                            <div class="h-px bg-gradient-to-r from-vttu-red/20 via-slate-200 to-transparent"></div>
                        </div>

                        {{-- Article body --}}
                        <div class="p-10 relative">
                            <div class="absolute -top-20 -right-20 w-80 h-80 bg-vttu-red/[0.03] blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="relative z-10 prose prose-slate prose-lg max-w-none
                                prose-headings:font-black prose-headings:tracking-tight prose-headings:text-vttu-dark
                                prose-p:text-vttu-dark/80 prose-p:leading-[1.85]
                                prose-strong:text-vttu-dark
                                prose-li:text-vttu-dark/80
                                prose-a:text-vttu-red prose-a:font-bold prose-a:no-underline hover:prose-a:underline
                                prose-img:rounded-2xl prose-img:shadow-xl">
                                {!! $node->content !!}
                            </div>
                        </div>
                    </div>

                    {{-- Page Builder blocks --}}
                    @if(isset($node) && $node->activeItems->count() > 0)
                        <div id="page-builder-content" class="space-y-8">
                            @foreach($node->activeItems as $item)
                                <div class="builder-item" data-aos="fade-up">
                                    @php $itemData = is_string($item->item_data) ? json_decode($item->item_data, true) : $item->item_data; @endphp
                                    @includeIf('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData])
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Navigation footer --}}
                    <div class="flex flex-col md:flex-row justify-between gap-5">
                        @php
                            $prev = $sidebarItems->where('sort_order', '<', $node->sort_order)->last();
                            $next = $sidebarItems->where('sort_order', '>', $node->sort_order)->first();
                        @endphp
                        <div class="w-full md:w-1/2">
                            @if($prev)
                                <a href="{{ $prev->getUrl() }}" class="group flex items-center gap-5 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-vttu-red/20 transition-all duration-300">
                                    <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-vttu-red group-hover:text-white transition-all flex-shrink-0">
                                        <i class="fas fa-arrow-left text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-black text-vttu-red/40 uppercase tracking-widest">Trang trước</div>
                                        <div class="text-sm font-bold text-vttu-dark group-hover:text-vttu-red transition-colors">{{ $prev->display_name }}</div>
                                    </div>
                                </a>
                            @endif
                        </div>
                        <div class="w-full md:w-1/2">
                            @if($next)
                                <a href="{{ $next->getUrl() }}" class="group flex items-center justify-end gap-5 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-vttu-red/20 transition-all duration-300 text-right">
                                    <div>
                                        <div class="text-[9px] font-black text-vttu-red/40 uppercase tracking-widest">Tiếp theo</div>
                                        <div class="text-sm font-bold text-vttu-dark group-hover:text-vttu-red transition-colors">{{ $next->display_name }}</div>
                                    </div>
                                    <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-vttu-red group-hover:text-white transition-all flex-shrink-0">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
