@extends('layouts.site')

@section('title', $node->meta_title ?: 'Giới Thiệu - Thư viện số')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header Section -->
    <section class="relative overflow-hidden bg-slate-950 text-white" data-aos="fade-up">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/15 to-transparent"></div>
        <div class="absolute -top-24 -right-24 w-[520px] h-[520px] bg-blue-500/15 blur-[140px] rounded-full animate-float"></div>
        <div class="absolute -bottom-24 -left-24 w-[520px] h-[520px] bg-indigo-500/15 blur-[140px] rounded-full animate-float" style="animation-delay: 0.9s"></div>

        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10 py-20">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur text-[10px] font-black uppercase tracking-[0.3em] text-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                    Giới thiệu
                </div>
                <h1 class="mt-6 text-4xl md:text-6xl font-black tracking-tight leading-tight">
                    {{ $node->display_name }}
                </h1>
                <p class="mt-6 text-lg md:text-xl text-slate-300 leading-relaxed max-w-3xl">
                    {{ $node->description ?: 'Giới thiệu tổng quan về Thư viện và định hướng phát triển.' }}
                </p>
            </div>
        </div>
    </section>

    @php
        $sidebarItems = collect();
        if ($node->parent) {
            $sidebarItems = $node->parent->activeChildren()->get();
        } else {
            $sidebarItems = $node->activeChildren()->get();
        }
        if ($sidebarItems->count() === 0) {
            $sidebarItems = collect([$node]);
        }
    @endphp

    <!-- Main Content -->
    <section class="py-16" data-aos="fade-up" data-aos-delay="150">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <aside class="lg:col-span-4" data-aos="fade-right" data-aos-delay="200">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden">
                            <div class="p-8 border-b border-slate-100">
                                <div class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Chuyên mục</div>
                                <div class="mt-2 text-2xl font-black text-slate-900">Giới thiệu</div>
                            </div>
                            <nav class="p-4">
                                @foreach($sidebarItems as $item)
                                    @php
                                        $active = $item->id === $node->id;
                                    @endphp
                                    <a href="{{ $item->getUrl() }}"
                                       class="block px-6 py-4 rounded-2xl font-black transition-all {{ $active ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                                        <div class="flex items-center justify-between">
                                            <span>{{ $item->display_name }}</span>
                                            <i class="fas fa-chevron-right text-xs {{ $active ? 'text-white/90' : 'text-slate-300' }}"></i>
                                        </div>
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                </aside>

                <div class="lg:col-span-8" data-aos="fade-up" data-aos-delay="250">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl p-10 relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/5 blur-[120px] rounded-full pointer-events-none animate-float"></div>
                        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/5 blur-[140px] rounded-full pointer-events-none animate-float" style="animation-delay: 0.8s"></div>

                        <div class="relative z-10">
                            <div class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em]">Chuyên mục</div>
                            <h2 class="mt-2 text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                                <span class="animate-gradient-text">{{ mb_strtoupper($node->display_name) }}</span>
                            </h2>

                            <div class="mt-8 prose prose-slate max-w-none">
                                {!! $node->content !!}
                            </div>

                            {{-- 🚀 Dành cho Page Builder: Tự động render các block kéo thả --}}
                            <div id="page-builder-content" class="mt-12 space-y-12">
                                @if(isset($node) && $node->activeItems->count() > 0)
                                    @foreach($node->activeItems as $item)
                                        <div class="builder-item animate-up" data-aos="fade-up">
                                            @php
                                                $itemData = is_string($item->item_data) ? json_decode($item->item_data, true) : $item->item_data;
                                            @endphp
                                            @includeIf('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData])
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
