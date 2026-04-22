@extends('layouts.site')

@section('title', 'VTTLib - Thư viện số hiện đại')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- 1. Hero Section with Interactive Waves -->
    <section class="relative min-h-[90vh] flex items-center pt-20 overflow-hidden bg-[#0a192f]" data-aos="fade-right">
        <!-- Animated Background Background Circles -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute top-[20%] right-[10%] w-64 h-64 bg-cyan-400/10 rounded-full blur-[90px] animate-float" style="animation-delay: 0.6s"></div>
            <div class="absolute bottom-[15%] left-[12%] w-72 h-72 bg-fuchsia-400/10 rounded-full blur-[100px] animate-float" style="animation-delay: 1.2s"></div>
        </div>

        <div class="w-full px-4 md:px-12 lg:px-24 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-10 animate-fade-in-left">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-black uppercase tracking-[0.3em]">
                        <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2 animate-ping"></span>
                        VTTU Digital Repository
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white leading-tight tracking-tighter">
                        Khai phá <span class="animate-gradient-text">Tri thức</span> <br>trong tầm tay.
                    </h1>
                    <p class="text-xl text-slate-400 leading-relaxed max-w-xl">
                        Hệ thống thư viện số hiện đại cung cấp hàng ngàn tài liệu điện tử, giáo trình và bài giảng phục vụ học tập và nghiên cứu đỉnh cao.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#explore" class="px-10 py-5 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-[2rem] shadow-2xl shadow-blue-600/30 transition-all hover:-translate-y-1 flex items-center group">
                            Bắt đầu khám phá
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                        </a>
                        <a href="/login" class="px-10 py-5 bg-white/5 hover:bg-white/10 text-white font-black rounded-[2rem] backdrop-blur-xl border border-white/10 transition-all flex items-center">
                            Đăng nhập OPAC
                        </a>
                    </div>
                    <!-- Quick Search Input -->
                    <div class="max-w-xl bg-white/5 p-2 rounded-[2.5rem] border border-white/10 backdrop-blur-md">
                        <form action="#" class="flex items-center">
                            <input type="text" class="flex-1 bg-transparent border-none text-white px-6 focus:ring-0 font-bold placeholder:text-gray-500" placeholder="Tìm kiếm sách, bài giảng, tài liệu...">
                            <button class="w-14 h-14 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-xl">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Floating Illustration Card -->
                <div class="relative animate-float hidden lg:block">
                    <div class="absolute inset-0 bg-blue-500 blur-[100px] opacity-20"></div>
                    <img src="https://img.freepik.com/free-vector/digital-library-concept-illustration_114360-8451.jpg" alt="Library" class="relative z-10 rounded-[3rem] shadow-2xl border border-white/10">
                    <!-- Glass Stats Card -->
                    <div class="absolute -bottom-10 -left-10 bg-white/10 backdrop-blur-2xl p-8 rounded-[2.5rem] border border-white/10 shadow-2xl animate-bounce-slow">
                        <div class="text-4xl font-black text-white">25k+</div>
                        <div class="text-xs font-bold text-blue-400 uppercase tracking-widest mt-1">Tài liệu số</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SVG Waves -->
        <div class="absolute bottom-0 left-0 w-full leading-[0]">
            <svg class="relative block w-full h-[150px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs><path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" /></defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(248,250,252,0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(248,250,252,0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(248,250,252,0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#f8fafc" />
                </g>
            </svg>
        </div>
    </section>

    <!-- 2. Interactive Quick Folders (Digital Repository) -->
    <section id="explore" class="py-32 relative" data-aos="fade-up" data-aos-delay="100">
        <div class="absolute -top-20 left-0 w-80 h-80 bg-blue-500/10 blur-[120px] rounded-full pointer-events-none animate-float"></div>
        <div class="absolute -bottom-24 right-0 w-96 h-96 bg-indigo-500/10 blur-[140px] rounded-full pointer-events-none animate-float" style="animation-delay: 0.9s"></div>
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Danh mục <span class="text-blue-600">Tài liệu số</span></h2>
                    <p class="text-lg text-slate-500 font-medium">Truy cập nhanh vào các phân hệ tài liệu được phân loại khoa học theo từng lĩnh vực đào tạo tại VTTU.</p>
                </div>
                <a href="/page/digital-library" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-blue-600 transition-all shadow-xl">
                    Xem tất cả thư mục
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $folders = [
                        ['name' => 'Bài giảng VTTU', 'icon' => 'fa-chalkboard-teacher', 'color' => 'blue', 'count' => '1,240'],
                        ['name' => 'Y học - Sức khỏe', 'icon' => 'fa-hand-holding-medical', 'color' => 'emerald', 'count' => '3,500'],
                        ['name' => 'Kinh tế - Luật', 'icon' => 'fa-balance-scale', 'color' => 'orange', 'count' => '2,100'],
                        ['name' => 'Công nghệ thông tin', 'icon' => 'fa-laptop-code', 'color' => 'indigo', 'count' => '1,850'],
                    ];
                @endphp
                @foreach($folders as $f)
                <div class="group p-10 bg-white rounded-[3rem] border border-slate-100 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden text-center card-3d" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-{{ $f['color'] }}-500/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-{{ $f['color'] }}-600/10 rounded-3xl flex items-center justify-center text-{{ $f['color'] }}-600 mx-auto mb-8 group-hover:bg-{{ $f['color'] }}-600 group-hover:text-white transition-all shadow-lg">
                            <i class="fas {{ $f['icon'] }} text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $f['name'] }}</h3>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $f['count'] }} Tài liệu</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-blue-900 text-white relative overflow-hidden" data-aos="fade-up" data-aos-delay="150">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10 text-center">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
                <div>
                    <div class="text-5xl font-black mb-2">25k+</div>
                    <div class="text-blue-400 text-xs font-black uppercase tracking-widest">Tài liệu số</div>
                </div>
                <div>
                    <div class="text-5xl font-black mb-2">15k+</div>
                    <div class="text-blue-400 text-xs font-black uppercase tracking-widest">Bạn đọc</div>
                </div>
                <div>
                    <div class="text-5xl font-black mb-2">120+</div>
                    <div class="text-blue-400 text-xs font-black uppercase tracking-widest">Đối tác quốc tế</div>
                </div>
                <div>
                    <div class="text-5xl font-black mb-2">24/7</div>
                    <div class="text-blue-400 text-xs font-black uppercase tracking-widest">Truy cập</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Real-world Resources Shelf (Dữ liệu từ library.vttu.edu.vn) -->
    <section class="py-32 bg-white relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-600/10 text-blue-600 text-[10px] font-black uppercase tracking-widest mb-4">
                        New Arrivals
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">Sách & Tài liệu <br><span class="text-blue-600">Mới cập nhật</span></h2>
                </div>
                <div class="flex gap-4">
                    <button class="w-14 h-14 rounded-2xl border border-slate-200 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm"><i class="fas fa-chevron-left"></i></button>
                    <button class="w-14 h-14 rounded-2xl border border-slate-200 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-8">
                @php
                    $vttuResources = [
                        ['title' => 'Fintech 4.0', 'author' => 'Kitao Yoshitaka', 'type' => 'Sách chuyên khảo'],
                        ['title' => 'The Fintech Book', 'author' => 'Susanne Chishti', 'type' => 'Sách chuyên khảo'],
                        ['title' => 'Quản trị Ngân hàng Thương mại', 'author' => 'Nguyễn Văn Tiến', 'type' => 'Giáo trình'],
                        ['title' => 'Đầu tư Tài chính', 'author' => 'Bodie; Kane; Marcus', 'type' => 'Tài liệu dịch'],
                        ['title' => 'Quản trị đổi mới sáng tạo', 'author' => 'Nguyễn Ngọc Quý', 'type' => 'Chuyên khảo'],
                        ['title' => 'Chính phủ điện tử', 'author' => 'Đỗ Văn Thắng', 'type' => 'Giáo trình'],
                    ];
                @endphp
                @foreach($vttuResources as $item)
                <div class="group cursor-pointer" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative aspect-[3/4.5] bg-slate-100 rounded-[2rem] overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-500 mb-6 border border-slate-200/50">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-indigo-900/40 mix-blend-multiply opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <i class="fas fa-book-open text-4xl text-slate-300 group-hover:text-white group-hover:scale-110 transition-all duration-500"></i>
                            <div class="mt-4 opacity-0 group-hover:opacity-100 transition-all transform translate-y-4 group-hover:translate-y-0">
                                <span class="px-4 py-2 bg-white text-slate-900 text-[10px] font-black uppercase rounded-full shadow-xl">CHI TIẾT</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest">{{ $item['type'] }}</span>
                        <h4 class="text-sm font-black text-slate-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight">{{ $item['title'] }}</h4>
                        <p class="text-[10px] text-slate-500 font-bold italic">{{ $item['author'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. New Resources Section (Dữ liệu động) -->
    <section class="py-32" data-aos="fade-up" data-aos-delay="300">
        <div class="absolute left-0 top-10 w-72 h-72 bg-blue-500/10 blur-[120px] rounded-full pointer-events-none animate-float"></div>
        <div class="absolute right-0 bottom-10 w-80 h-80 bg-cyan-500/10 blur-[140px] rounded-full pointer-events-none animate-float" style="animation-delay: 0.8s"></div>
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div class="space-y-4">
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight">Tài liệu <span class="animate-gradient-text">Mới nhất</span></h2>
                    <p class="text-xl text-slate-500 leading-relaxed">Khám phá các giáo trình, luận văn và tài liệu nghiên cứu vừa được cập nhật trong hệ thống.</p>
                </div>
                <a href="#" class="px-8 py-4 bg-slate-900 text-white rounded-full font-bold hover:bg-blue-600 transition-all duration-300">Xem tất cả kho số</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-6">
                @foreach($newResources as $resource)
                <div class="group cursor-pointer card-3d" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 mb-4 shadow-sm group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500 relative">
                        <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/20 transition-all duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur text-blue-600 rounded-full text-xs font-black shadow-xl">CHI TIẾT</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-black text-slate-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight">{{ $resource->title }}</h4>
                        <p class="text-[10px] text-slate-500 font-bold italic">
                            {{ is_array($resource->authors) ? implode(', ', $resource->authors) : $resource->authors }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Special Medical Collection (Y Dược VTTU - Dữ liệu động) -->
    <section class="py-32 bg-slate-50 relative overflow-hidden border-y border-slate-100" data-aos="fade-up" data-aos-delay="400">
        <div class="absolute top-0 right-0 w-1/4 h-full bg-red-600/5 blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-24 left-0 w-96 h-96 bg-red-500/5 blur-[140px] rounded-full pointer-events-none animate-float"></div>
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-72 h-72 bg-amber-500/5 blur-[120px] rounded-full pointer-events-none animate-float" style="animation-delay: 1.1s"></div>
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <div class="w-20 h-20 bg-red-600 text-white rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-red-600/20 rotate-3 transition-transform hover:rotate-0 duration-500">
                    <i class="fas fa-heartbeat text-3xl"></i>
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight">Kệ sách <span class="animate-gradient-text">Y khoa Chuyên sâu</span></h2>
                <p class="text-gray-500 text-lg">Hệ thống giáo trình, bài giảng và Atlas giải phẫu dành riêng cho khối ngành sức khỏe tại VTTU.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach($medicalResources as $resource)
                <div class="bg-white p-8 rounded-[3rem] border border-slate-200/60 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group card-3d" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 mb-6 group-hover:bg-red-600 group-hover:text-white transition-all">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xl font-black text-slate-900 leading-tight group-hover:text-red-600 transition-colors">{{ $resource->title }}</h4>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest italic">
                            {{ is_array($resource->authors) ? implode(', ', $resource->authors) : $resource->authors }}
                        </p>
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">{{ $resource->description }}</p>
                        
                        <div class="pt-4 flex items-center gap-4 text-xs font-black text-slate-400">
                            <span class="flex items-center gap-1"><i class="far fa-eye"></i> {{ $resource->view_count }}</span>
                            <span class="flex items-center gap-1"><i class="far fa-arrow-alt-circle-down"></i> {{ $resource->download_count }}</span>
                        </div>

                        <a href="{{ route('admin.digital-resources.show', $resource->id) }}" class="inline-flex items-center gap-2 text-red-600 font-black text-xs uppercase tracking-widest pt-4 group/btn">
                            Khám phá ngay
                            <i class="fas fa-chevron-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. News & Internal Notifications (Tin tức từ library.vttu.edu.vn) -->
    <section class="py-32 bg-white" data-aos="fade-up" data-aos-delay="500">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <!-- Left: Important Notifications -->
                <div class="lg:col-span-8">
                    <div class="flex items-center justify-between mb-12">
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">Thông báo <span class="text-blue-600">Từ Thư viện</span></h3>
                        <a href="#" class="text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Xem tất cả</a>
                    </div>
                    
                    <div class="space-y-6">
                        @php
                            $vttuNews = [
                                ['tag' => 'Sự kiện', 'date' => '20/04/2026', 'title' => 'Chào mừng Ngày Sách và Văn hóa đọc Việt Nam lần thứ tư năm 2025', 'desc' => 'Chuỗi hoạt động hưởng ứng ngày hội đọc sách tại Thư viện VTTU.'],
                                ['tag' => 'Nghỉ lễ', 'date' => '15/04/2026', 'title' => 'Thông báo Nghỉ lễ Giỗ tổ Hùng Vương, 30/04 & 01/05', 'desc' => 'Thư viện xin thông báo lịch nghỉ lễ phục vụ bạn đọc sắp tới.'],
                                ['tag' => 'Học thuật', 'date' => '10/04/2026', 'title' => 'Giới thiệu Tài liệu số của Trung tâm Học liệu - Đại học Cần Thơ', 'desc' => 'Liên kết khai thác kho tài liệu số đa dạng với các đơn vị đối tác.'],
                                ['tag' => 'K18', 'date' => '05/04/2026', 'title' => 'Kích hoạt tài khoản Thư viện cho Tân sinh viên K18', 'desc' => 'Hướng dẫn chi tiết quy trình đăng nhập và sử dụng OPAC cho sinh viên khóa mới.'],
                            ];
                        @endphp
                        @foreach($vttuNews as $news)
                        <div class="group flex flex-col md:flex-row gap-8 p-8 rounded-[2.5rem] bg-slate-50 hover:bg-white border border-transparent hover:border-blue-200 hover:shadow-2xl transition-all duration-500">
                            <div class="md:w-48 h-48 rounded-3xl overflow-hidden shadow-lg relative">
                                <img src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-blue-600/10 group-hover:opacity-0 transition-opacity"></div>
                            </div>
                            <div class="space-y-3 py-2">
                                <div class="flex items-center gap-4">
                                    <span class="px-3 py-1 bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-sm">{{ $news['tag'] }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 tracking-widest"><i class="far fa-calendar-alt mr-1 text-blue-500"></i> {{ $news['date'] }}</span>
                                </div>
                                <h4 class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">{{ $news['title'] }}</h4>
                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">{{ $news['desc'] }}</p>
                                <a href="#" class="inline-flex items-center text-slate-900 font-black text-[10px] uppercase tracking-[0.2em] group/more pt-4">
                                    Chi tiết <i class="fas fa-chevron-right ml-2 group-hover/more:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Quick Links & Services -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="p-10 bg-slate-900 rounded-[3rem] text-white shadow-2xl relative overflow-hidden group/card">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-[80px] -mt-20 -mr-20"></div>
                        <h3 class="text-2xl font-black mb-8 relative z-10 leading-tight">Dịch vụ <br>Tiện ích Số</h3>
                        <div class="space-y-6 relative z-10">
                            <a href="#" class="flex items-center gap-5 group/item">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center group-hover/item:bg-blue-600 transition-all shadow-lg border border-white/5">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div>
                                    <h5 class="font-black text-sm">App Thư viện</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Mobile Access</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center gap-5 group/item">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center group-hover/item:bg-emerald-600 transition-all shadow-lg border border-white/5">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <h5 class="font-black text-sm">Thẻ sinh viên</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Integrated ID</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center gap-5 group/item">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center group-hover/item:bg-orange-600 transition-all shadow-lg border border-white/5">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <h5 class="font-black text-sm">Vị trí Thư viện</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">4th Floor - Theory Area</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-10 bg-white rounded-[3rem] border border-slate-200 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-slate-100 rounded-full -mr-12 -mt-12"></div>
                        <h3 class="text-xl font-black text-slate-900 mb-8 relative z-10 leading-tight">Thời gian <br>Phục vụ</h3>
                        <div class="space-y-5 relative z-10">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">Thứ 2 - Thứ 6</span>
                                <span class="px-4 py-1.5 bg-blue-50 rounded-xl text-[11px] font-black text-blue-600 shadow-sm border border-blue-100">07:00 - 17:00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">Thứ 7</span>
                                <span class="px-4 py-1.5 bg-blue-50 rounded-xl text-[11px] font-black text-blue-600 shadow-sm border border-blue-100">07:30 - 11:30</span>
                            </div>
                            <div class="flex justify-between items-center opacity-40 grayscale">
                                <span class="text-sm font-bold text-slate-500">Chủ nhật</span>
                                <span class="px-4 py-1.5 bg-slate-100 rounded-xl text-[11px] font-black text-slate-400 border border-slate-200">Nghỉ lễ</span>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-3 text-xs font-black text-slate-400">
                            <i class="fas fa-circle text-[8px] animate-pulse"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Hiện đang mở cửa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- 6. Library Spaces (Gallery Section) -->
    <section class="py-32 bg-slate-50 border-y border-slate-100" data-aos="fade-up" data-aos-delay="1400">
        <div class="px-4 md:px-12 lg:px-24">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight">Không gian <span class="text-blue-600">Học thuật</span></h2>
                <p class="text-gray-500 text-lg">Khám phá môi trường học tập hiện đại, yên tĩnh với trang thiết bị tối tân tại VTTU.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-auto md:h-[600px]">
                <div class="md:col-span-2 md:row-span-2 relative group overflow-hidden rounded-[3rem] shadow-2xl h-[400px] md:h-full" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1521587760476-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <h4 class="text-2xl font-black">Sảnh đọc trung tâm</h4>
                        <p class="text-blue-200">Sức chứa hơn 500 sinh viên cùng lúc</p>
                    </div>
                </div>
                <div class="md:col-span-2 relative group overflow-hidden rounded-[3rem] shadow-2xl h-[250px] md:h-auto" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-8 left-8 text-white">
                        <h4 class="text-xl font-black">Phòng học nhóm Smart</h4>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-[3rem] shadow-2xl h-[250px] md:h-auto" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="relative group overflow-hidden rounded-[3rem] shadow-2xl h-[250px] md:h-auto" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1558023156-4a69442f9970?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
            </div>
        </div>
    </section>

    <!-- 7. How it works (Process) -->
    <section class="py-32 bg-white relative overflow-hidden" data-aos="fade-up" data-aos-delay="1600">
        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-slate-50 to-transparent"></div>
        <div class="px-4 md:px-12 lg:px-24">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/2 space-y-8">
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">Quy trình <br><span class="text-indigo-600">Sử dụng Thư viện</span></h2>
                    <p class="text-xl text-slate-500 leading-relaxed">Chúng tôi tối ưu hóa mọi quy trình để bạn có thể tiếp cận tài liệu nhanh nhất có thể.</p>
                    
                    <div class="space-y-8">
                        <div class="flex gap-6 items-start group">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black shrink-0 shadow-lg group-hover:scale-110 transition-transform">1</div>
                            <div class="space-y-2">
                                <h4 class="text-xl font-bold text-slate-900">Đăng ký tài khoản</h4>
                                <p class="text-slate-500">Sử dụng mã sinh viên để kích hoạt tài khoản OPAC trực tuyến.</p>
                            </div>
                        </div>
                        <div class="flex gap-6 items-start group">
                            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-black shrink-0 shadow-lg group-hover:scale-110 transition-transform">2</div>
                            <div class="space-y-2">
                                <h4 class="text-xl font-bold text-slate-900">Tìm kiếm tài liệu</h4>
                                <p class="text-slate-500">Dùng thanh tìm kiếm thông minh để lọc theo Tiêu đề, Tác giả hoặc ISBN.</p>
                            </div>
                        </div>
                        <div class="flex gap-6 items-start group">
                            <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center font-black shrink-0 shadow-lg group-hover:scale-110 transition-transform">3</div>
                            <div class="space-y-2">
                                <h4 class="text-xl font-bold text-slate-900">Mượn hoặc Xem số</h4>
                                <p class="text-slate-500">Nhận sách tại quầy hoặc xem trực tiếp các định dạng PDF, Video ngay tại đây.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="absolute inset-0 bg-indigo-600/5 blur-[120px] rounded-full"></div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-6 pt-12">
                            <div class="p-8 bg-slate-50 rounded-[2.5rem] shadow-xl border border-slate-100">
                                <i class="fas fa-bolt text-3xl text-yellow-500 mb-4"></i>
                                <h5 class="font-black text-slate-900 mb-2">Tốc độ cao</h5>
                                <p class="text-xs text-slate-500">Truy cập dữ liệu không độ trễ.</p>
                            </div>
                            <div class="p-8 bg-indigo-600 rounded-[2.5rem] shadow-xl text-white">
                                <i class="fas fa-shield-check text-3xl mb-4"></i>
                                <h5 class="font-black mb-2">Bảo mật</h5>
                                <p class="text-xs text-indigo-100">Dữ liệu được bảo vệ tuyệt đối.</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="p-8 bg-slate-900 rounded-[2.5rem] shadow-xl text-white">
                                <i class="fas fa-mobile-android text-3xl text-blue-400 mb-4"></i>
                                <h5 class="font-black mb-2">Đa thiết bị</h5>
                                <p class="text-xs text-slate-400">Tương thích Mobile & Tablet.</p>
                            </div>
                            <div class="p-8 bg-slate-50 rounded-[2.5rem] shadow-xl border border-slate-100">
                                <i class="fas fa-clock text-3xl text-emerald-500 mb-4"></i>
                                <h5 class="font-black text-slate-900 mb-2">24/7</h5>
                                <p class="text-xs text-slate-500">Mở cửa kho số không giới hạn.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FAQ Section -->
    <section class="py-32 bg-slate-50 border-y border-slate-100" data-aos="fade-up" data-aos-delay="1800">
        <div class="px-4 md:px-12 lg:px-24">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Hỏi đáp <span class="text-blue-600">Thường gặp</span></h2>
                    <p class="text-lg text-slate-500 font-medium">Tìm nhanh câu trả lời cho những thắc mắc phổ biến của bạn đọc.</p>
                </div>

                <div class="space-y-4">
                    @php
                        $faqs = [
                            ['q' => 'Làm thế nào để đăng ký thẻ thư viện?', 'a' => 'Sinh viên VTTU được cấp tài khoản thư viện tự động dựa trên mã số sinh viên ngay khi nhập học.'],
                            ['q' => 'Tôi có thể mượn tối đa bao nhiêu cuốn sách?', 'a' => 'Mỗi bạn đọc có thể mượn tối đa 5 cuốn sách trong thời gian 14 ngày, có thể gia hạn trực tuyến.'],
                            ['q' => 'Làm sao để truy cập kho tài liệu số từ xa?', 'a' => 'Bạn chỉ cần đăng nhập vào hệ thống bằng tài khoản được cấp và truy cập mục "Kho tài liệu số" để xem hoặc tải tệp.'],
                            ['q' => 'Thư viện có hỗ trợ mượn liên thư viện không?', 'a' => 'Có, chúng tôi liên kết với hệ thống thư viện quốc gia để hỗ trợ bạn mượn tài liệu từ các đơn vị khác.'],
                        ];
                    @endphp
                    @foreach($faqs as $faq)
                    <div class="group bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $faq['q'] }}</h4>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-50 text-slate-500 hidden group-hover:block animate-fade-in">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Final CTA -->
    <section class="py-20 bg-white" data-aos="fade-up" data-aos-delay="2000">
        <div class="px-4 md:px-12 lg:px-24">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-900 rounded-[4rem] p-10 md:p-20 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -mt-20 -mr-20"></div>
                <div class="relative z-10 text-center max-w-3xl mx-auto space-y-8">
                    <h2 class="text-4xl md:text-6xl font-black text-white leading-tight">Sẵn sàng bước vào <br>thế giới tri thức?</h2>
                    <p class="text-xl text-blue-100">Đăng ký tài khoản ngay hôm nay để nhận được đầy đủ quyền lợi khai thác tài nguyên số.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-6">
                        <a href="/register" class="px-12 py-5 bg-white text-indigo-900 font-black rounded-2xl shadow-2xl hover:bg-blue-50 transition-all transform hover:-translate-y-1">Đăng ký ngay</a>
                        <a href="/contact" class="px-12 py-5 bg-blue-700/50 text-white font-black rounded-2xl backdrop-blur-md border border-white/20 hover:bg-blue-700 transition-all">Liên hệ hỗ trợ</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .animate-float { animation: float 6s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    .animate-bounce-slow { animation: bounce-slow 4s ease-in-out infinite; }
    @keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .parallax > use { animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite; }
    .parallax > use:nth-child(1) { animation-delay: -2s; animation-duration: 7s; }
    .parallax > use:nth-child(2) { animation-delay: -3s; animation-duration: 10s; }
    .parallax > use:nth-child(3) { animation-delay: -4s; animation-duration: 13s; }
    .parallax > use:nth-child(4) { animation-delay: -5s; animation-duration: 20s; }
    @keyframes move-forever { 0% { transform: translate3d(-90px,0,0); } 100% { transform: translate3d(85px,0,0); } }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .animate-fade-in-left { animation: fadeInLeft 1s ease-out; }
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection
