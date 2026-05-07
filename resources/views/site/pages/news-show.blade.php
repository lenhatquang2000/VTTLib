@extends('layouts.site')

@section('title', $news->title . ' - VTTLib')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-20">
    <div class="w-full px-4 md:px-12 lg:px-32 xl:px-48">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Main Content -->
            <article class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-black/5 border border-slate-100 overflow-hidden">
                    <!-- Featured Image -->
                    @if($news->featured_image)
                    <div class="aspect-video w-full overflow-hidden">
                        <img src="{{ $news->featured_image }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <div class="p-8 md:p-12">
                        <!-- Meta -->
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            @if($news->category)
                            <span class="px-4 py-1.5 rounded-full bg-vttu-red/5 text-vttu-red text-xs font-black uppercase tracking-widest border border-vttu-red/10">
                                {{ $news->category->name }}
                            </span>
                            @endif
                            <div class="flex items-center text-slate-400 text-sm font-bold">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ $news->published_at ? $news->published_at->format('d/m/Y') : $news->created_at->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center text-slate-400 text-sm font-bold">
                                <i class="far fa-eye mr-2"></i>
                                {{ number_format($news->view_count) }} lượt xem
                            </div>
                        </div>

                        <!-- Title -->
                        <h1 class="text-3xl md:text-5xl font-black text-vttu-dark leading-tight tracking-tighter mb-8">
                            {{ $news->title }}
                        </h1>

                        <!-- Summary -->
                        @if($news->summary)
                        <div class="p-6 bg-slate-50 rounded-2xl border-l-4 border-vttu-red mb-8">
                            <p class="text-lg text-slate-600 leading-relaxed font-medium italic">
                                {{ $news->summary }}
                            </p>
                        </div>
                        @endif

                        <!-- Content -->
                        <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed font-medium">
                            {!! nl2br($news->content) !!}
                        </div>

                        <!-- Tags -->
                        @if($news->tags->count() > 0)
                        <div class="mt-12 pt-8 border-t border-slate-100 flex flex-wrap gap-2">
                            @foreach($news->tags as $tag)
                            <a href="{{ route('news.tag', $tag->slug) }}" class="px-4 py-2 bg-slate-100 hover:bg-vttu-red hover:text-white text-slate-600 text-xs font-bold rounded-xl transition-all">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    @if(isset($previousNews) && $previousNews)
                    <a href="{{ $previousNews->url }}" class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Bài trước</span>
                        <h4 class="text-vttu-dark font-bold group-hover:text-vttu-red transition-colors line-clamp-1">{{ $previousNews->title }}</h4>
                    </a>
                    @endif
                    @if(isset($nextNews) && $nextNews)
                    <a href="{{ $nextNews->url }}" class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all group text-right">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Bài tiếp theo</span>
                        <h4 class="text-vttu-dark font-bold group-hover:text-vttu-red transition-colors line-clamp-1">{{ $nextNews->title }}</h4>
                    </a>
                    @endif
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Related News -->
                @if(isset($relatedNews) && $relatedNews->count() > 0)
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100">
                    <h3 class="text-xl font-black text-vttu-dark mb-6 flex items-center">
                        <span class="w-2 h-8 bg-vttu-red rounded-full mr-3"></span>
                        TIN LIÊN QUAN
                    </h3>
                    <div class="space-y-6">
                        @foreach($relatedNews as $item)
                        <a href="{{ $item->url }}" class="flex gap-4 group">
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex-shrink-0 overflow-hidden">
                                <img src="{{ $item->featured_image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=200&q=80' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h4>
                                <span class="text-[10px] text-slate-400 font-bold mt-1 block uppercase tracking-widest">
                                    {{ $item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Contact Box -->
                <div class="bg-vttu-dark rounded-[2rem] p-8 text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-2xl rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                    <h3 class="text-2xl font-black mb-4 relative z-10">Bạn cần hỗ trợ?</h3>
                    <p class="text-white/70 text-sm leading-relaxed mb-6 relative z-10">Liên hệ với chúng tôi để được giải đáp mọi thắc mắc về tài liệu và dịch vụ thư viện.</p>
                    <a href="/noi-quy-thu-vien" class="inline-flex items-center px-6 py-3 bg-vttu-yellow text-vttu-dark font-black rounded-xl hover:bg-yellow-400 transition-all relative z-10">
                        Liên hệ ngay
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
