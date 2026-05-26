@extends('layouts.site')

@section('title', 'VTTLib - Thư viện số hiện đại')

@section('content')
<!-- 
DEBUG NGÔN NGỮ:
Locale hiện tại: {{ app()->getLocale() }}
Kiểm tra dịch 'Khai phá': {{ __('Khai phá') }}
-->
@include('site.partials.book-loader')
<div class="bg-slate-50 min-h-screen">
    <!-- 1. Hero Slider Section -->
    <section class="relative min-h-[70vh] bg-white overflow-hidden" data-aos="fade">
        <div class="swiper heroSwiper h-full min-h-[70vh]">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide flex flex-col">
                    <!-- Body (75%) -->
                    <div class="flex-grow flex items-center pt-32 md:pt-40 pb-12">
                        <div class="w-full px-4 md:px-12 lg:px-24 relative z-20">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                                <div class="space-y-8">
                                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-vttu-red/5 border border-vttu-red/20 text-vttu-red text-xs font-bold uppercase tracking-[0.3em]">
                                        <span class="flex h-2 w-2 rounded-full bg-vttu-red mr-2 animate-ping"></span>
                                        VTTU Digital Repository
                                    </div>
                                    <h1 class="text-5xl md:text-7xl font-bold text-vttu-dark leading-tight tracking-tighter">
                                        {{ __('Khai phá') }} <span class="text-vttu-red">{{ __('Tri thức') }}</span> <br>{{ __('trong tầm tay.') }}
                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        {{ __('Hệ thống thư viện số hiện đại cung cấp hàng ngàn tài liệu điện tử, giáo trình và bài giảng phục vụ học tập và nghiên cứu đỉnh cao.') }}
                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="#explore" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-bold rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all hover:-translate-y-1 flex items-center group">
                                            {{ __('Bắt đầu khám phá') }}
                                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="relative hidden lg:block">
                                    <div class="absolute inset-0 bg-vttu-dark blur-[100px] opacity-40"></div>
                                    <img src="https://img.freepik.com/free-vector/digital-library-concept-illustration_114360-8451.jpg" alt="Library" class="relative z-10 rounded-[3rem] shadow-2xl border border-white/10 max-h-[400px] w-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Hero (25%) -->
                    <div class="h-[25%] bg-vttu-red py-8">
                        <div class=" mx-auto px-4 md:px-12 lg:px-24">
                            <div class="flex flex-wrap items-center justify-start gap-16">
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-2xl font-bold text-white">25k+</p>
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest">{{ __('Tài liệu số') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-2xl font-bold text-white">15k+</p>
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest">{{ __('Bạn đọc') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-2xl font-bold text-white">24/7</p>
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest">{{ __('Truy cập') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide flex flex-col">
                    <!-- Body (75%) -->
                    <div class="flex-grow flex items-center pt-32 md:pt-40 pb-12">
                        <div class="w-full px-4 md:px-12 lg:px-24 relative z-20">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                                <div class="space-y-8">
                                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-vttu-red/5 border border-vttu-red/20 text-vttu-red text-xs font-bold uppercase tracking-[0.3em]">
                                        <span class="flex h-2 w-2 rounded-full bg-vttu-red mr-2 animate-ping"></span>
                                        VTTU Medical Collection
                                    </div>
                                    <h1 class="text-5xl md:text-7xl font-bold text-vttu-dark leading-tight tracking-tighter">
                                        {{ __('Kệ sách') }} <span class="text-vttu-red">{{ __('Y khoa') }}</span> <br>{{ __('Chuyên sâu.') }}
                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        {{ __('Truy cập kho giáo trình, Atlas giải phẫu và công trình nghiên cứu y học dành riêng cho khối ngành sức khỏe tại VTTU.') }}
                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="#explore" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-bold rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all hover:-translate-y-1 flex items-center group">
                                            {{ __('Xem tài liệu y khoa') }}
                                            <i class="fas fa-stethoscope ml-3 group-hover:rotate-12 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="relative hidden lg:block">
                                    <div class="absolute inset-0 bg-vttu-red/10 blur-[100px] opacity-40"></div>
                                    <img src="https://img.freepik.com/free-vector/medical-video-call-consultation-illustration_52683-61434.jpg" alt="Medical" class="relative z-10 rounded-[3rem] shadow-2xl border border-slate-100 max-h-[400px] w-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Hero (25%) -->
                    <div class="h-[25%] bg-vttu-red py-8">
                        <div class=" mx-auto px-4 md:px-12 lg:px-24">
                            <div class="flex flex-wrap items-center justify-start gap-16">
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-microscope"></i>
                                    </div>
                                    <span class="text-sm font-black text-white uppercase tracking-wider">{{ __('Nghiên cứu chuyên sâu') }}</span>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-dna"></i>
                                    </div>
                                    <span class="text-sm font-black text-white uppercase tracking-wider">{{ __('Atlas Giải phẫu số') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide flex flex-col">
                    <!-- Body (75%) -->
                    <div class="flex-grow flex items-center pt-32 md:pt-40 pb-12">
                        <div class="w-full px-4 md:px-12 lg:px-24 relative z-20">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                                <div class="space-y-8">
                                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-vttu-red/5 border border-vttu-red/20 text-vttu-red text-xs font-black uppercase tracking-[0.3em]">
                                        <span class="flex h-2 w-2 rounded-full bg-vttu-red mr-2 animate-ping"></span>
                                        Learning Resources
                                    </div>
                                    <h1 class="text-5xl md:text-7xl font-black text-vttu-dark leading-tight tracking-tighter">
                                        {{ __('Kết nối') }} <span class="text-vttu-red">{{ __('Không không gian') }}</span> <br>{{ __('Học thuật mới.') }}
                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        {{ __('Môi trường học tập hiện đại với trang thiết bị tối tân, phục vụ nhu cầu nghiên cứu và sáng tạo không ngừng của sinh viên VTTU.') }}
                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="/login" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-black rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all flex items-center group">
                                            {{ __('Đăng nhập ngay') }}
                                            <i class="fas fa-sign-in-alt ml-3 group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="relative hidden lg:block">
                                    <div class="absolute inset-0 bg-vttu-red/10 blur-[100px] opacity-40"></div>
                                    <img src="https://img.freepik.com/free-vector/study-concept-illustration_114360-1111.jpg" alt="Study" class="relative z-10 rounded-[3rem] shadow-2xl border border-slate-100 max-h-[400px] w-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Hero (25%) -->
                    <div class="h-[25%] bg-vttu-red py-8">
                        <div class=" mx-auto px-4 md:px-12 lg:px-24">
                            <div class="flex flex-wrap items-center justify-start gap-16">
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white">{{ __('Wifi miễn phí') }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white">{{ __('Phòng học nhóm') }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white">{{ __('Smart Tech') }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white">{{ __('Không gian yên tĩnh') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slider Controls -->
            <div class="swiper-pagination !bottom-12"></div>
            <div class="swiper-button-next !right-8 !text-vttu-red/30 hover:!text-vttu-red transition-colors after:!text-2xl hidden md:flex"></div>
            <div class="swiper-button-prev !left-8 !text-vttu-red/30 hover:!text-vttu-red transition-colors after:!text-2xl hidden md:flex"></div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <section class="py-8 relative overflow-hidden bg-slate-50 w-full" x-data="{ ...catalogWizard(), sidebarOpen: true }">
        <!-- Content overlay for readability -->
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse at center top, rgba(255,255,255,0.15) 0%, transparent 60%);"></div>

        <div class="px-4 md:px-12 lg:px-24 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row gap-8 transition-all duration-500 ease-in-out w-full">
                
                <!-- LEFT COLUMN -->
                <div class="transition-all duration-500 ease-in-out flex-1 w-full" 
                     style="min-width: 0;"
                     :class="sidebarOpen ? 'lg:w-[72%]' : 'lg:w-full'">
                    <div class="space-y-6 w-full">
                        <!-- Section 1: 3 Tabs (Sách Mới | Tạp Chí Online | Thư mục) -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-md p-4 shadow-sm border border-slate-100" data-aos="fade-up">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                                <div class="flex items-center gap-3 overflow-x-auto" id="book-tabs">
                                    <button @click="loadTab('book', 'book-tabs', 'books-content')"
                                        class="tab-btn text-sm font-bold px-4 py-1.5 rounded-sm whitespace-nowrap transition-all {{ ($activeType ?? 'book') === 'book' ? 'text-white bg-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red' }}">
                                        {{ __('SÁCH MỚI') }}
                                    </button>
                                    <button @click="loadTab('journal', 'book-tabs', 'books-content')"
                                        class="tab-btn text-sm font-bold px-4 py-1.5 rounded-sm whitespace-nowrap transition-all {{ ($activeType ?? '') === 'journal' ? 'text-white bg-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red' }}">
                                        {{ __('TẠP CHÍ ONLINE') }}
                                    </button>
                                    <button @click="loadTab('folder', 'book-tabs', 'books-content')"
                                        class="tab-btn text-sm font-bold px-4 py-1.5 rounded-sm whitespace-nowrap transition-all {{ ($activeType ?? '') === 'folder' ? 'text-white bg-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red' }}">
                                        {{ __('THƯ MỤC') }}
                                    </button>
                                </div>
                                
                                {{-- Sidebar Toggle Button (Only visible on LG) --}}
                                <button @click="sidebarOpen = !sidebarOpen" 
                                        class="hidden lg:flex items-center justify-center w-8 h-8 bg-slate-50 hover:bg-vttu-red hover:text-white text-slate-400 rounded-sm transition-all shadow-sm border border-slate-100 group">
                                    <i class="fas" :class="sidebarOpen ? 'fa-indent' : 'fa-outdent'"></i>
                                </button>
                            </div>
                            <div id="books-content" class="min-h-[300px] relative flex items-start justify-start pt-4">
                                <div class="flex items-center justify-center w-full py-20">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-vttu-red"></div>
                                </div>
                            </div>
                        </div>

                        <div id="info-grid" class="grid grid-cols-1 gap-6 transition-all duration-500" :class="sidebarOpen ? 'md:grid-cols-2' : 'md:grid-cols-2'">
                            <div class="bg-white rounded-md shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                                    <div class="bg-vttu-red px-4 py-3 flex items-center justify-between">
                                        <h3 class="text-sm font-bold text-white uppercase tracking-tight">{{ __('THÔNG BÁO') }}</h3>
                                        <a href="#" class="text-vttu-yellow text-[10px] font-bold uppercase tracking-widest hover:underline">{{ __('Xem tất cả') }}</a>
                                    </div>
                                    <div class="p-3">
                                        @if(isset($homeAnnouncements) && $homeAnnouncements->count() > 0)
                                            @php $firstAnn = $homeAnnouncements->first(); @endphp
                                            <div class="mb-3 aspect-video rounded-md overflow-hidden bg-slate-100 relative group">
                                                <a href="{{ $firstAnn->url }}" class="w-full h-full block">
                                                    <img src="{{ $firstAnn->featured_image ?? 'https://img.freepik.com/free-vector/breaking-news-concept_23-2148514216.jpg' }}" 
                                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                                         onerror="this.onerror=null;this.src='https://img.freepik.com/free-vector/breaking-news-concept_23-2148514216.jpg'">
                                                </a>
                                            </div>
                                            <div class="space-y-2">
                                                @foreach($homeAnnouncements as $item)
                                                    <a href="{{ $item->url }}" class="flex items-start gap-2 text-vttu-dark hover:text-vttu-red transition-colors group">
                                                        <span class="text-vttu-red mt-1">•</span>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-medium line-clamp-2 leading-snug group-hover:underline">{{ $item->title }}</p>
                                                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->created_at->format('H:i:s') }}</p>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="py-6 text-center">
                                                <p class="text-xs text-slate-400 italic">Chưa có thông báo mới cập nhật</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            <!-- Tin tức sự kiện -->
                            <div class="bg-white rounded-md shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
                                <div class="bg-vttu-red px-4 py-3 flex items-center justify-between">
                                    <h3 class="text-sm font-bold text-white uppercase tracking-tight">{{ __('TIN TỨC SỰ KIỆN') }}</h3>
                                    <a href="{{ route('news.index') }}" class="text-vttu-yellow text-[10px] font-bold uppercase tracking-widest hover:underline">{{ __('Xem tất cả') }}</a>
                                </div>
                                <div class="p-3">
                                    @if(isset($homeNews) && $homeNews->count() > 0)
                                        @php 
                                            $newsOrdered = $homeNews->sortBy('sort_order');
                                            $firstNews = $newsOrdered->first(); 
                                        @endphp
                                        <div class="mb-3 aspect-video rounded-md overflow-hidden bg-slate-100 relative group">
                                            <a href="{{ $firstNews->url }}" class="w-full h-full block">
                                                <img src="{{ $firstNews->featured_image ?? 'https://img.freepik.com/free-vector/breaking-news-concept_23-2148514216.jpg' }}" 
                                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                                     onerror="this.onerror=null;this.src='https://img.freepik.com/free-vector/breaking-news-concept_23-2148514216.jpg'">
                                            </a>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($homeNews->sortBy('sort_order')->take(5) as $item)
                                                <a href="{{ $item->url }}" class="flex items-start gap-2 text-vttu-dark hover:text-vttu-red transition-colors group">
                                                    <span class="text-vttu-red mt-1">•</span>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-medium line-clamp-2 leading-snug group-hover:underline">{{ $item->title }}</p>
                                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->created_at->format('H:i:s') }}</p>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-6 text-center">
                                            <p class="text-xs text-slate-400 italic">Chưa có tin tức mới cập nhật</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: 2 Tabs (Tin mới | Video) -->
                        <div class="bg-white rounded-md shadow-sm border border-slate-100 p-4" data-aos="fade-up">
                            <div class="flex items-center gap-4 border-b border-slate-100 pb-3 mb-4" id="news-tabs">
                                <button @click="loadNewsTab('news', 'news-tabs', 'news-content')"
                                    class="tab-btn text-sm font-bold px-4 py-1.5 rounded-sm transition-all {{ ($activeNewsType ?? 'news') === 'news' ? 'text-white bg-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red' }}">
                                    {{ __('TIN MỚI') }}
                                </button>
                                <button @click="loadNewsTab('video', 'news-tabs', 'news-content')"
                                    class="tab-btn text-sm font-bold px-4 py-1.5 rounded-sm transition-all {{ ($activeNewsType ?? '') === 'video' ? 'text-white bg-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red' }}">
                                    {{ __('VIDEO') }}
                                </button>
                            </div>
                            <div id="news-content" class="min-h-[200px] relative">
                                @include('site.pages.partials.home-news', ['homeNews' => $tabNews ?? $homeNews])
                            </div>
                        </div>

                        <!-- Section 4: Giới thiệu sách -->
                        <div class="bg-white rounded-md p-4 text-vttu-dark border border-slate-100 shadow-sm relative overflow-hidden" data-aos="fade-up">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-vttu-red/5 blur-3xl rounded-full"></div>
                            <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-10">
                                <div class="md:col-span-3">
                                    <div class="aspect-[3/4] bg-slate-100 rounded-sm shadow-md p-2 rotate-2 overflow-hidden border border-white">
                                        @php $bookIntroImg = \App\Models\SystemSetting::get('book_intro_image'); @endphp
                                        @if($bookIntroImg)
                                            <img src="{{ asset('storage/' . $bookIntroImg) }}" 
                                                 class="w-full h-full object-contain mix-blend-multiply"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                            <div class="hidden w-full h-full bg-slate-50 items-center justify-center">
                                                <i class="fas fa-book-open text-slate-300 text-3xl"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                                <i class="fas fa-book-open text-slate-300 text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="md:col-span-9 space-y-3">
                                    <div class="inline-flex px-2 py-0.5 rounded-sm bg-vttu-red/5 border border-vttu-red/10 text-[9px] font-bold tracking-widest uppercase text-vttu-red">Book of the Month</div>
                                    <h3 class="text-xl font-bold leading-tight text-vttu-dark uppercase">{{ __('GIỚI THIỆU SÁCH HÀNG THÁNG') }}</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ __('Khám phá những tựa sách hay và giá trị nhất được đội ngũ thủ thư VTTU chọn lọc kỹ lưỡng dành cho bạn.') }}</p>
                                    <a href="{{ route('opac.search') }}" class="inline-block px-6 py-2 bg-vttu-yellow text-vttu-dark text-xs font-bold rounded-sm hover:bg-yellow-400 transition-all shadow-sm text-center">{{ __('Khám phá ngay') }}</a>
                                </div>
                            </div>
                            <div class="border-t border-vttu-red"></div>
                            <!-- Carousel bên dưới (Giả lập 2 cuốn sách theo mẫu Grid - Kích thước nhỏ) -->
                            <div class="mt-6 pt-6 border-t border-slate-50">
                                <div class="flex gap-3">
                                    @for($i=1; $i<=2; $i++)
                                    <div class="bg-white p-2.5 rounded-md border border-slate-100 hover:border-vttu-red/20 transition-all group flex flex-col shadow-sm w-[160px] flex-shrink-0">
                                        <!-- Book Cover -->
                                        <div class="aspect-[3/4] bg-slate-50 rounded-sm mb-2 border border-slate-50 flex items-center justify-center overflow-hidden relative">
                                            @if($i == 1)
                                                <div class="w-full h-full bg-vttu-red flex items-center justify-center text-white font-bold text-center p-2 text-[10px]">VTTU Library</div>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-slate-50">
                                                    <i class="fas fa-book-open text-slate-200 text-2xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-1.5 right-1.5">
                                                <span class="px-1.5 py-0.5 bg-white/90 backdrop-blur text-vttu-red rounded-sm text-[7px] font-bold uppercase tracking-widest shadow-sm">SÁCH</span>
                                            </div>
                                        </div>

                                        <!-- Book Info -->
                                        <div class="flex-grow flex flex-col gap-1.5">
                                            <h3 class="text-[10px] font-bold text-vttu-dark group-hover:text-vttu-red transition-colors leading-tight line-clamp-2 min-h-[1.5rem]">
                                                {{ $i == 1 ? 'Giáo trình Kinh tế phát triển' : 'Giáo trình Lý thuyết giải phẫu chức năng hệ vận động' }}
                                            </h3>
                                            
                                            <p class="text-[9px] font-medium text-slate-500 flex items-center gap-1 truncate">
                                                <i class="fas fa-user-edit text-[7px] text-vttu-red"></i>
                                                {{ $i == 1 ? 'Phí Thị Hằng' : 'Hoàng Anh Lân' }}
                                            </p>
                                            
                                            <div class="mt-auto pt-1.5 flex items-center justify-between border-t border-slate-50">
                                                <span class="text-[7px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-sm uppercase tracking-tighter border border-emerald-100">SẴN SÀNG</span>
                                                <button class="w-5 h-5 rounded-sm bg-slate-50 flex items-center justify-center text-vttu-red hover:bg-vttu-red hover:text-white transition-all shadow-sm">
                                                    <i class="fas fa-arrow-right text-[7px]"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: 3 Tabs (Sản khoa | Nhi Khoa | Nội Khoa) -->
                        <div class="bg-white rounded-md p-4 shadow-sm border border-slate-100" data-aos="fade-up">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-4 overflow-x-auto" id="medical-tabs">
                                <button @click="loadMedicalTab('Sản khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-xs font-bold text-white bg-vttu-red px-4 py-1.5 rounded-sm shadow-sm whitespace-nowrap transition-all uppercase">{{ __('SẢN KHOA') }}</button>
                                <button @click="loadMedicalTab('Nhi khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-xs font-bold text-slate-400 hover:text-vttu-red px-4 py-1.5 rounded-sm whitespace-nowrap transition-all uppercase">{{ __('NHI KHOA') }}</button>
                                <button @click="loadMedicalTab('Nội khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-xs font-bold text-slate-400 hover:text-vttu-red px-4 py-1.5 rounded-sm whitespace-nowrap transition-all uppercase">{{ __('NỘI KHOA') }}</button>
                            </div>
                            <div id="medical-content" class="min-h-[200px]">
                                @include('site.pages.partials.home-medical', ['medicalResources' => $medicalResources])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN (Sidebar) -->
                <div class="transition-all duration-500 ease-in-out lg:sticky lg:top-24 h-fit" 
                     :class="sidebarOpen ? 'lg:w-[25%] opacity-100 visible translate-x-0' : 'lg:w-0 lg:ml-[-1rem] opacity-0 invisible translate-x-full'">
                    <div class="space-y-4 min-w-[280px]">
                        
                        <!-- Thời gian phục vụ -->
                        <div class="bg-vttu-red p-4 rounded-md relative overflow-hidden group shadow-sm" data-aos="fade-left">
                            <div class="flex items-center gap-3 mb-4 relative z-10">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-sm flex items-center justify-center text-vttu-yellow border border-white/20">
                                    <i class="fas fa-clock text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-white tracking-tight leading-tight uppercase">{{ __('THỜI GIAN') }}<br>{{ __('PHỤC VỤ') }}</h3>
                            </div>
                            <div class="space-y-2 relative z-10">
                                <div class="flex justify-between items-center p-3 bg-white/10 backdrop-blur-sm rounded-sm border border-white/10 hover:bg-white/20 transition-all">
                                    <span class="font-medium text-white/90 text-[11px] uppercase tracking-widest">{{ __('Thứ 2 - Thứ 6') }}</span>
                                    <span class="font-bold text-vttu-yellow text-sm">7:30 - 20:00</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/10 backdrop-blur-sm rounded-sm border border-white/10 hover:bg-white/20 transition-all">
                                    <span class="font-medium text-white/90 text-[11px] uppercase tracking-widest">{{ __('Thứ 7 - CN') }}</span>
                                    <span class="font-bold text-vttu-yellow text-sm">8:00 - 17:00</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Tabs: Sách Mới | Nổi bật -->
                        <div class="bg-white p-4 rounded-md shadow-sm border border-slate-100" data-aos="fade-left">
                            <div class="flex bg-slate-50 p-1 rounded-sm mb-4" id="resource-tabs">
                                <button @click="loadResourceTab($event, 'new', 'resource-tabs', 'resources-content')"
                                    class="tab-btn flex-1 py-2 text-[10px] font-bold text-center rounded-sm transition-all {{ ($activeResourceType ?? 'new') === 'new' ? 'text-white bg-vttu-red shadow-sm uppercase tracking-widest' : 'text-slate-400 hover:text-vttu-red uppercase tracking-widest' }}">
                                    {{ __('Mới') }}
                                </button>
                                <button @click="loadResourceTab($event, 'featured', 'resource-tabs', 'resources-content')"
                                    class="tab-btn flex-1 py-2 text-[10px] font-bold text-center rounded-sm transition-all {{ ($activeResourceType ?? '') === 'featured' ? 'text-white bg-vttu-red shadow-sm uppercase tracking-widest' : 'text-slate-400 hover:text-vttu-red uppercase tracking-widest' }}">
                                    {{ __('Nổi bật') }}
                                </button>
                            </div>
                            <div id="resources-content" class="min-h-[200px]">
                                @include('site.pages.partials.sidebar-books', ['sidebarBooks' => $sidebarBooks])
                            </div>
                        </div>

                        <!-- Video Section -->
                        <div class="bg-white rounded-md shadow-sm border border-slate-100 p-4" data-aos="fade-left">
                            <h3 class="text-sm font-bold text-vttu-dark mb-3 uppercase tracking-tight">{{ __('VIDEO') }}</h3>
                            <div class="aspect-video bg-slate-900 rounded-sm relative overflow-hidden group cursor-pointer shadow-sm">
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white group-hover:scale-110 transition-transform border border-white/30">
                                        <i class="fas fa-play text-[10px]"></i>
                                    </div>
                                </div>
                                <img src="https://img.freepik.com/free-vector/video-streaming-concept-illustration_114360-10731.jpg" class="w-full h-full object-cover opacity-60">
                            </div>
                            <p class="text-[11px] font-medium text-vttu-dark mt-3 leading-snug">{{ __('Hướng dẫn đăng ký và sử dụng tài khoản thư viện số VTTU') }}</p>
                        </div>

                        <!-- Link Buttons -->
                        <div class="grid grid-cols-1 gap-2" data-aos="fade-left">
                            <a href="#" class="flex items-center justify-between p-4 bg-vttu-red rounded-sm text-white shadow-sm hover:-translate-y-0.5 transition-all group">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-globe text-lg text-vttu-yellow"></i>
                                    <span class="font-bold text-[11px] uppercase tracking-widest">{{ __('Tài nguyên mở') }}</span>
                                </div>
                                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-4 bg-vttu-dark rounded-sm text-white shadow-sm hover:-translate-y-0.5 transition-all group">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-desktop text-lg text-vttu-yellow"></i>
                                    <span class="font-bold text-[11px] uppercase tracking-widest">{{ __('Học liệu số') }}</span>
                                </div>
                                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Slider (4 Items - VTTU Lib) -->
    <section class="py-8 bg-white border-t border-slate-100">
        <div class="px-4 md:px-12 lg:px-24">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-vttu-dark tracking-tight uppercase">VTTU LIB <span class="text-vttu-red">NETWORK</span></h3>
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-sm border border-slate-200 flex items-center justify-center hover:bg-vttu-red hover:text-white transition-all shadow-sm"><i class="fas fa-chevron-left text-[10px]"></i></button>
                    <button class="w-8 h-8 rounded-sm border border-slate-200 flex items-center justify-center hover:bg-vttu-red hover:text-white transition-all shadow-sm"><i class="fas fa-chevron-right text-[10px]"></i></button>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @for($i=1; $i<=4; $i++)
                <div class="p-6 bg-slate-50 rounded-md border border-slate-100 flex flex-col items-center justify-center text-center group hover:bg-white hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 bg-white rounded-sm shadow-sm flex items-center justify-center mb-4 group-hover:bg-vttu-red group-hover:text-white transition-all">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <span class="font-bold text-vttu-dark text-sm uppercase tracking-wider">VTTU LIB {{ $i }}</span>
                    <p class="text-[9px] font-medium text-slate-400 mt-1 uppercase tracking-widest">Library Information System</p>
                </div>
                @endfor
            </div>
        </div>
    </section>
</div>

<style>
    .animate-float { animation: float 6s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    /* Loading animation for AJAX */
    .tab-loading { opacity: 0.5; pointer-events: none; }
</style>
@section('scripts')
<script>
    // Define the data function first for Alpine.js
    function catalogWizard() {
        return {
            loadTab(type, tabsId, contentId, eventOverride = null) {
                const target = eventOverride ? eventOverride.currentTarget : event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                contentDiv.classList.add('tab-loading');
                
                fetch(`{{ route('home') }}?type=${type}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    contentDiv.innerHTML = html;
                    contentDiv.classList.remove('tab-loading');
                    
                    tabsDiv.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('text-white', 'bg-vttu-red', 'shadow-sm');
                        btn.classList.add('text-slate-400', 'hover:text-vttu-red');
                    });
                    target.classList.remove('text-slate-400', 'hover:text-vttu-red');
                    target.classList.add('text-white', 'bg-vttu-red', 'shadow-sm');

                    // Re-init Swiper for new content
                    this.initBooksSwiper();

                    // Console log dữ liệu vừa get được
                    const books = contentDiv.querySelectorAll('.swiper-slide:not(.py-12)'); // py-12 là cho slide trống (empty)
                    console.log(`[AJAX] Loaded tab: ${type}. Total books found: ${books.length}`);
                    books.forEach((book, index) => {
                        const title = book.querySelector('h3')?.textContent?.trim();
                        console.log(`  ${index + 1}. ${title}`);
                    });
                })
                .catch(error => {
                    console.error('Error loading tab:', error);
                    contentDiv.classList.remove('tab-loading');
                });
            },
            initBooksSwiper() {
                // Đảm bảo Swiper cũ được destroy nếu cần hoặc chỉ khởi tạo nếu có container
                const container = document.querySelector('.books-swiper-container');
                if (container) {
                    if (container.swiper) container.swiper.destroy();
                    new Swiper('.books-swiper-container', {
                        slidesPerView: 1,
                        spaceBetween: 12,
                        centeredSlides: false,
                        navigation: {
                            nextEl: '.books-next',
                            prevEl: '.books-prev',
                        },
                        pagination: {
                            el: '.books-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            640: { slidesPerView: 2, spaceBetween: 16 },
                            1024: { slidesPerView: 3, spaceBetween: 20 },
                            1280: { slidesPerView: 4, spaceBetween: 24 }
                        }
                    });
                }
            },
            loadResourceTab(event, type, tabsId, contentId) {
                const target = event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                console.log('--- SIDEBAR TAB LOAD ---');
                console.log('Type requested:', type);
                console.log('Target URL:', `{{ route('home') }}?resource_type=${type}`);

                contentDiv.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity');
                
                fetch(`{{ route('home') }}?resource_type=${type}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text();
                })
                .then(html => {
                    console.log('--- AJAX RESPONSE DEBUG ---');
                    console.log('HTML Length:', html.length);
                    
                    // Tạo một temp element để parse HTML và đếm số lượng bản ghi
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    // Mỗi cuốn sách trong sidebar-books.blade.php nằm trong một thẻ <a>
                    const items = doc.querySelectorAll('a[href*="/opac/book/"]');
                    console.log('Found book items in response:', items.length);
                    
                    if (items.length === 0) {
                        console.warn('No items found in the HTML response. Possible causes: empty query result, wrong template being rendered, or status filtering.');
                    } else {
                        console.log('Sample book title:', items[0].querySelector('h4')?.textContent?.trim());
                    }

                    contentDiv.innerHTML = html;
                    contentDiv.classList.remove('opacity-50', 'pointer-events-none');
                    
                    tabsDiv.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('text-white', 'bg-vttu-red', 'shadow-lg', 'shadow-vttu-red/20');
                        btn.classList.add('text-slate-400', 'hover:text-vttu-red');
                    });
                    target.classList.remove('text-slate-400', 'hover:text-vttu-red');
                    target.classList.add('text-white', 'bg-vttu-red', 'shadow-lg', 'shadow-vttu-red/20');
                })
                .catch(err => console.error('Tab Load Error:', err));
            },
            loadMedicalTab(type, tabsId, contentId) {
                const target = event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                contentDiv.classList.add('tab-loading');
                
                fetch(`{{ route('home') }}?medical_type=${type}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    contentDiv.innerHTML = html;
                    contentDiv.classList.remove('tab-loading');
                    
                    tabsDiv.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('text-white', 'bg-vttu-red', 'shadow-lg', 'shadow-vttu-red/20');
                        btn.classList.add('text-slate-400', 'hover:text-vttu-red');
                    });
                    target.classList.remove('text-slate-400', 'hover:text-vttu-red');
                    target.classList.add('text-white', 'bg-vttu-red', 'shadow-lg', 'shadow-vttu-red/20');
                });
            },
            loadNewsTab(type, tabsId, contentId) {
                const target = event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                contentDiv.classList.add('tab-loading');
                
                fetch(`{{ route('home') }}?news_type=${type}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    contentDiv.innerHTML = html;
                    contentDiv.classList.remove('tab-loading');
                    
                    tabsDiv.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('text-white', 'bg-vttu-red', 'shadow-sm');
                        btn.classList.add('text-slate-400', 'hover:text-vttu-red');
                    });
                    target.classList.remove('text-slate-400', 'hover:text-vttu-red');
                    target.classList.add('text-white', 'bg-vttu-red', 'shadow-sm');
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const heroSwiper = new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + ' !bg-white !w-3 !h-3"></span>';
                },
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 1000,
        });
        // Initialize Books Swiper on load
        const wizard = catalogWizard();
        // wizard.initBooksSwiper(); // Không init trực tiếp vì sẽ load bằng AJAX bên dưới

        // Force load tab đầu tiên bằng AJAX khi vào trang
        const firstTab = document.querySelector('#book-tabs .tab-btn');
        if (firstTab) {
            // Tạo một mock event để khớp với logic loadTab
            const mockEvent = { currentTarget: firstTab };
            wizard.loadTab('book', 'book-tabs', 'books-content', mockEvent);
        }
    });
</script>
@endsection
