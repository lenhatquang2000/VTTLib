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
        'fas fa-circle-info'    => ['from-blue-500 to-cyan-400',    'shadow-blue-500/25'],
        'fas fa-bullseye'       => ['from-indigo-500 to-purple-400','shadow-indigo-500/25'],
        'fas fa-scale-balanced' => ['from-amber-500 to-orange-400', 'shadow-amber-500/25'],
        'fas fa-clock'          => ['from-emerald-500 to-teal-400', 'shadow-emerald-500/25'],
        'fas fa-sitemap'        => ['from-violet-500 to-fuchsia-400','shadow-violet-500/25'],
    ];

    // ── Accent color mapping (Tailwind-safe) ──
    $accentGradient = match($accent) {
        'indigo'  => 'from-indigo-500 to-purple-400', 'amber'   => 'from-amber-500 to-orange-400',
        'emerald' => 'from-emerald-500 to-teal-400',  'violet'  => 'from-violet-500 to-fuchsia-400',
        default   => 'from-blue-500 to-cyan-400',
    };
    $accentShadow = match($accent) {
        'indigo'  => 'shadow-indigo-500/20', 'amber'   => 'shadow-amber-500/20',
        'emerald' => 'shadow-emerald-500/20','violet'  => 'shadow-violet-500/20',
        default   => 'shadow-blue-500/20',
    };
    $accentDivider = match($accent) {
        'indigo'  => 'from-indigo-500/30', 'amber'   => 'from-amber-500/30',
        'emerald' => 'from-emerald-500/30','violet'  => 'from-violet-500/30',
        default   => 'from-blue-500/30',
    };
    $activeNav = match($accent) {
        'indigo'  => 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25',
        'amber'   => 'bg-amber-600 text-white shadow-lg shadow-amber-600/25',
        'emerald' => 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25',
        'violet'  => 'bg-violet-600 text-white shadow-lg shadow-violet-600/25',
        default   => 'bg-blue-600 text-white shadow-lg shadow-blue-600/25',
    };
    $badgeColor = match($accent) {
        'indigo'  => 'text-indigo-300', 'amber'   => 'text-amber-300',
        'emerald' => 'text-emerald-300','violet'  => 'text-violet-300',
        default   => 'text-blue-300',
    };
    $pingColor = match($accent) {
        'indigo'  => 'bg-indigo-400', 'amber'   => 'bg-amber-400',
        'emerald' => 'bg-emerald-400','violet'  => 'bg-violet-400',
        default   => 'bg-blue-400',
    };
@endphp

<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-50">

    {{-- ══════════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-600/20 via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-indigo-600/15 via-transparent to-transparent"></div>
            <div class="absolute top-0 right-0 w-[700px] h-[700px] bg-blue-500/10 blur-[180px] rounded-full animate-float"></div>
            <div class="absolute -bottom-32 -left-32 w-[600px] h-[600px] bg-indigo-500/10 blur-[160px] rounded-full animate-float" style="animation-delay:1.2s"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-cyan-500/5 blur-[120px] rounded-full animate-float" style="animation-delay:2.4s"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>

        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10 pt-16 pb-24">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-500 mb-10" data-aos="fade-down" data-aos-delay="100">
                <a href="/" class="hover:text-white transition-colors"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right text-[7px]"></i>
                <a href="/page/gioi-thieu" class="hover:text-white transition-colors">Giới thiệu</a>
                <i class="fas fa-chevron-right text-[7px]"></i>
                <span class="{{ $badgeColor }}">{{ $node->display_name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-delay="200">
                    <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-white/[0.06] border border-white/10 backdrop-blur-xl text-[10px] font-black uppercase tracking-[0.35em] {{ $badgeColor }} mb-8">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $pingColor }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $pingColor }}"></span>
                        </span>
                        {{ $badgeText }}
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-8">
                        <span class="animate-gradient-text">Thư viện</span><br>
                        <span class="text-white">Đại học Võ Trường Toản</span>
                    </h1>
                    <p class="text-lg text-slate-400 leading-relaxed max-w-xl">
                        {{ $node->description }}
                    </p>
                </div>

                {{-- Stats cards --}}
                <div class="grid grid-cols-2 gap-5" data-aos="fade-left" data-aos-delay="400">
                    <div class="group p-7 rounded-3xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-xl hover:bg-white/[0.08] transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mb-5 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">15+</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Năm hoạt động</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-xl hover:bg-white/[0.08] transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">50K+</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tài liệu</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-xl hover:bg-white/[0.08] transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-fuchsia-400 flex items-center justify-center mb-5 shadow-lg shadow-violet-500/20 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">10K+</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Bạn đọc / Năm</div>
                    </div>
                    <div class="group p-7 rounded-3xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-xl hover:bg-white/[0.08] transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center mb-5 shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform">
                            <i class="fas fa-laptop text-white"></i>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">24/7</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tra cứu Online</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0,40 C360,80 720,0 1440,40 L1440,80 L0,80 Z" fill="#f8fafc"/>
            </svg>
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
                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-7 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/20 blur-2xl rounded-full"></div>
                                <div class="relative z-10">
                                    <div class="text-[9px] font-black uppercase tracking-[0.4em] text-blue-300 mb-2">Chuyên mục</div>
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
                                              {{ $active ? $activeNav : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs flex-shrink-0 transition-all
                                                    {{ $active
                                                        ? 'bg-white/20 text-white'
                                                        : 'bg-gradient-to-br ' . $iconColors[0] . ' text-white shadow-md ' . $iconColors[1] . ' group-hover:scale-110' }}">
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
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] blur-lg opacity-25 group-hover:opacity-40 transition-opacity"></div>
                            <div class="relative bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] p-8 text-white overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/15 blur-3xl rounded-full"></div>
                                <div class="relative z-10 text-center">
                                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-5 backdrop-blur-sm">
                                        <i class="fas fa-search text-xl text-blue-400"></i>
                                    </div>
                                    <h4 class="font-black text-base mb-2">Tra cứu OPAC</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed mb-5">Tìm kiếm tài liệu trực tuyến trong hệ thống Thư viện.</p>
                                    <a href="http://opac.vttu.edu.vn" target="_blank"
                                       class="block w-full py-3.5 bg-white text-slate-900 rounded-xl font-black text-sm hover:bg-blue-500 hover:text-white transition-all shadow-lg">
                                        Tra cứu ngay <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- ── Main Content ── --}}
                <div class="lg:col-span-9 order-1 lg:order-2 space-y-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                        {{-- Article header --}}
                        <div class="px-10 pt-10 pb-0">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $accentGradient }} flex items-center justify-center shadow-lg {{ $accentShadow }}">
                                    <i class="{{ $badgeIcon }} text-white text-lg"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">{{ $sectionLabel }}</div>
                                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">{{ $node->display_name }}</h2>
                                </div>
                            </div>
                            <div class="h-px bg-gradient-to-r {{ $accentDivider }} via-slate-200 to-transparent"></div>
                        </div>

                        {{-- Article body --}}
                        <div class="p-10 relative">
                            <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-500/[0.03] blur-[100px] rounded-full pointer-events-none"></div>
                            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-indigo-500/[0.03] blur-[120px] rounded-full pointer-events-none"></div>

                            <div class="relative z-10 prose prose-slate prose-lg max-w-none
                                prose-headings:font-black prose-headings:tracking-tight prose-headings:text-slate-900
                                prose-p:text-slate-600 prose-p:leading-[1.85]
                                prose-strong:text-slate-800
                                prose-li:text-slate-600
                                prose-a:text-blue-600 prose-a:font-bold prose-a:no-underline hover:prose-a:underline
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
                                <a href="{{ $prev->getUrl() }}" class="group flex items-center gap-5 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                                    <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all flex-shrink-0">
                                        <i class="fas fa-arrow-left text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Trang trước</div>
                                        <div class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $prev->display_name }}</div>
                                    </div>
                                </a>
                            @endif
                        </div>
                        <div class="w-full md:w-1/2">
                            @if($next)
                                <a href="{{ $next->getUrl() }}" class="group flex items-center justify-end gap-5 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 text-right">
                                    <div>
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tiếp theo</div>
                                        <div class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $next->display_name }}</div>
                                    </div>
                                    <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all flex-shrink-0">
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
