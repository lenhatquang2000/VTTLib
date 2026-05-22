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

    // Client view colors based on Rule.txt
    $sidebarIcons = [
        'fas fa-circle-info'    => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-bullseye'       => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-scale-balanced' => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-clock'          => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
        'fas fa-sitemap'        => ['from-vttu-red to-vttu-dark',    'shadow-vttu-red/25'],
    ];

    // Lucide icon mapping from FA if possible, or fallback to database icon
    function getLucideIcon($faIcon) {
        $map = [
            'fas fa-circle-info'    => 'info',
            'fas fa-bullseye'       => 'target',
            'fas fa-scale-balanced' => 'scale',
            'fas fa-clock'          => 'clock',
            'fas fa-sitemap'        => 'sitemap',
            'fas fa-home'           => 'home',
            'fas fa-search'         => 'search',
            'fas fa-arrow-left'     => 'arrow-left',
            'fas fa-arrow-right'    => 'arrow-right',
        ];
        return $map[$faIcon] ?? 'file-text';
    }
@endphp

<div class="min-h-screen bg-background text-foreground animate-fade-in" x-data="{ sidebarOpen: true }">
    <!-- Header / Breadcrumb -->
    <header class="sticky top-0 z-40 w-full border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container flex h-14 items-center px-4 md:px-6">
            <nav class="flex items-center space-x-2 text-sm font-medium">
                <a href="/" class="flex items-center gap-1 text-muted-foreground hover:text-foreground transition-colors">
                    <i data-lucide="home" class="w-4 h-4"></i>
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-muted-foreground opacity-50"></i>
                <a href="/page/gioi-thieu" class="text-muted-foreground hover:text-foreground transition-colors">
                    {{ __('Giới thiệu') }}
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-muted-foreground opacity-50"></i>
                <span class="font-bold text-foreground">{{ $node->display_name }}</span>
            </nav>
        </div>
    </header>

    <div class="w-full px-4 py-4 mt-[6px] md:px-6 md:py-6">
        <div class="flex flex-col lg:flex-row gap-4">
            
            <!-- Sidebar -->
            <aside class="lg:w-72 space-y-4 order-2 lg:order-1 transition-all duration-300 overflow-hidden"
                   x-show="sidebarOpen"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 -translate-x-full"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 translate-x-0"
                   x-transition:leave-end="opacity-0 -translate-x-full">
                <!-- Navigation Card -->
                <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
                    <div class="p-3 bg-vttu-red border-b border-vttu-red/20 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 blur-xl rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-700"></div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="w-1 h-4 bg-vttu-yellow rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-white">{{ $sectionLabel }}</h3>
                        </div>
                    </div>
                    <nav class="p-2 space-y-1">
                        @foreach($sidebarItems as $item)
                            @php 
                                $active = $item->id === $node->id;
                                $colorClasses = $sidebarIcons[$item->icon] ?? null;
                            @endphp
                            <a href="{{ $item->getUrl() }}"
                               class="flex items-center gap-3 px-3 py-2 rounded text-sm transition-all relative group
                                      {{ $active 
                                         ? 'bg-vttu-red text-white font-bold shadow-md shadow-vttu-red/20' 
                                         : 'text-muted-foreground hover:bg-vttu-red/10 hover:text-vttu-red active:bg-vttu-red active:text-white active:scale-[0.98]' }}">
                                
                                @if(!$active && $colorClasses)
                                    <div class="w-8 h-8 rounded-sm bg-gradient-to-br {{ $colorClasses[0] }} {{ $colorClasses[1] }} flex items-center justify-center text-white flex-shrink-0 transition-all group-hover:scale-110 group-active:scale-95 group-active:bg-none group-active:text-vttu-yellow">
                                        <i data-lucide="{{ getLucideIcon($item->icon) }}" class="w-4 h-4"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 group-active:scale-95">
                                        <i data-lucide="{{ getLucideIcon($item->icon) }}" class="w-4 h-4 {{ $active ? 'text-vttu-yellow' : 'opacity-70' }}"></i>
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
                @else
                    <article class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden text-gray-500">
                        <!-- Article Header -->
                    @php $headerColors = $sidebarIcons[$node->icon] ?? ['from-primary/10 to-primary/5', '']; @endphp
                    <div class="p-4 border-b border-border bg-gradient-to-r {{ $headerColors[0] }} opacity-90">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-white/10 flex items-center justify-center text-white border border-white/20 shadow-sm">
                                <i data-lucide="{{ getLucideIcon($node->icon) }}" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest">{{ $sectionLabel }}</p>
                                <h1 class="text-xl md:text-2xl font-black text-white tracking-tight">{{ $node->display_name }}</h1>
                            </div>
                        </div>
                    </div>

                    <!-- Article Body -->
                    <div class="p-4 md:p-6">
                        <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none 
                                    prose-headings:text-foreground prose-headings:font-bold
                                    prose-p:text-muted-foreground prose-p:leading-relaxed
                                    prose-strong:text-foreground
                                    prose-a:text-vttu-red prose-a:font-bold prose-a:no-underline hover:prose-a:underline
                                    prose-img:rounded-md prose-img:border prose-img:border-border shadow-vttu-red/5">
                            {!! $node->content !!}
                        </div>
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
