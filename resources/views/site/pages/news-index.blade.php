@extends('layouts.site')

@section('title', 'Tin tức - VTTLib')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-4 md:px-12 lg:px-24">
        <!-- Header -->
        <div class="mb-12 text-center" data-aos="fade-down">
            <h1 class="text-4xl md:text-6xl font-black text-vttu-dark leading-tight tracking-tighter mb-4 uppercase">
                @if(isset($category))
                    {{ $category->name }}
                @elseif(isset($tag))
                    Từ khóa: #{{ $tag->name }}
                @elseif(isset($isFeatured))
                    Tin tức nổi bật
                @elseif(isset($searchQuery))
                    Kết quả tìm kiếm: "{{ $searchQuery }}"
                @else
                    TIN TỨC & SỰ KIỆN [DEBUG - NEW CONTENT]
                @endif
            </h1>
            <p class="text-slate-500 font-medium max-w-2xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                Thông báo và tin tức mới nhất từ Thư viện Đại học Võ Trường Toản2 .
            </p>
            <div class="w-24 h-2 bg-vttu-red mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                @if(isset($news) && $news->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($news as $item)
                        <article class="bg-white rounded-[2.5rem] shadow-xl shadow-black/5 border border-slate-100 overflow-hidden group hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <a href="{{ $item->url }}" class="block">
                                <div class="aspect-video w-full overflow-hidden relative">
                                    <img src="{{ $item->featured_image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80' }}" 
                                         alt="{{ $item->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @if($item->category)
                                    <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full bg-white/90 backdrop-blur-md text-vttu-red text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ $item->category->name }}
                                    </span>
                                    @endif
                                </div>
                                <div class="p-8">
                                    <div class="flex items-center text-slate-400 text-xs font-bold mb-3 uppercase tracking-widest">
                                        <i class="far fa-calendar-alt mr-2"></i>
                                        {{ $item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y') }}
                                    </div>
                                    <h2 class="text-xl font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-snug mb-4">
                                        {{ $item->title }}
                                    </h2>
                                    <p class="text-slate-500 text-sm line-clamp-3 leading-relaxed mb-6 font-medium">
                                        {{ $item->summary }}
                                    </p>
                                    <span class="inline-flex items-center text-vttu-red text-xs font-black uppercase tracking-[0.2em] group-hover:translate-x-2 transition-transform">
                                        Đọc tiếp
                                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                                    </span>
                                </div>
                            </a>
                        </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        {{ $news->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-[2.5rem] p-12 text-center shadow-xl shadow-black/5 border border-slate-100">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="fas fa-newspaper text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-vttu-dark mb-2">Không tìm thấy bài viết nào</h3>
                        <p class="text-slate-500 font-medium">Vui lòng quay lại sau hoặc thử từ khóa tìm kiếm khác.</p>
                        <a href="{{ route('news.index') }}" class="inline-flex mt-8 px-8 py-3 bg-vttu-red text-white font-black rounded-xl hover:bg-vttu-dark transition-all">Xem tất cả tin tức</a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Search -->
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-left">
                    <h3 class="text-xl font-black text-vttu-dark mb-6 flex items-center">
                        <span class="w-2 h-8 bg-vttu-red rounded-full mr-3"></span>
                        TÌM KIẾM
                    </h3>
                    <form action="{{ route('news.search') }}" method="GET" class="relative">
                        <input type="text" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Nhập từ khóa..." class="w-full pl-6 pr-12 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-vttu-red/20 font-bold text-sm">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-vttu-red transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Featured News (Sidebar) -->
                @if(isset($featuredNews) && $featuredNews->count() > 0)
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-left" data-aos-delay="100">
                    <h3 class="text-xl font-black text-vttu-dark mb-6 flex items-center">
                        <span class="w-2 h-8 bg-vttu-red rounded-full mr-3"></span>
                        TIN NỔI BẬT
                    </h3>
                    <div class="space-y-6">
                        @foreach($featuredNews as $fItem)
                        <a href="{{ $fItem->url }}" class="flex gap-4 group">
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex-shrink-0 overflow-hidden">
                                <img src="{{ $fItem->featured_image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=200&q=80' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-snug">{{ $fItem->title }}</h4>
                                <span class="text-[10px] text-slate-400 font-black mt-1 block uppercase tracking-widest">
                                    {{ $fItem->published_at ? $fItem->published_at->format('d/m/Y') : $fItem->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Categories -->
                @if(isset($categories) && $categories->count() > 0)
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="text-xl font-black text-vttu-dark mb-6 flex items-center">
                        <span class="w-2 h-8 bg-vttu-red rounded-full mr-3"></span>
                        CHUYÊN MỤC
                    </h3>
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                        <a href="{{ route('news.category', $cat->slug) }}" class="flex items-center justify-between p-4 rounded-2xl hover:bg-vttu-red/5 group transition-all">
                            <span class="font-bold text-slate-600 group-hover:text-vttu-red transition-colors">{{ $cat->name }}</span>
                            <span class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:bg-vttu-red group-hover:text-white transition-all">{{ $cat->published_news_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tags -->
                @if(isset($popularTags) && $popularTags->count() > 0)
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-left" data-aos-delay="300">
                    <h3 class="text-xl font-black text-vttu-dark mb-6 flex items-center">
                        <span class="w-2 h-8 bg-vttu-red rounded-full mr-3"></span>
                        TỪ KHÓA HOT
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $t)
                        <a href="{{ route('news.tag', $t->slug) }}" class="px-4 py-2 bg-slate-50 hover:bg-vttu-red hover:text-white text-slate-600 text-xs font-bold rounded-xl transition-all border border-slate-100">
                            #{{ $t->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection
