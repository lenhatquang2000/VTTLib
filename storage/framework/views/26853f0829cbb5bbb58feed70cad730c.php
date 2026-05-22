<?php $__env->startSection('title', 'VTTLib - Thư viện số hiện đại'); ?>

<?php $__env->startSection('content'); ?>
<!-- 
DEBUG NGÔN NGỮ:
Locale hiện tại: <?php echo e(app()->getLocale()); ?>

Kiểm tra dịch 'Khai phá': <?php echo e(__('Khai phá')); ?>

-->
<?php echo $__env->make('site.partials.book-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                                        <?php echo e(__('Khai phá')); ?> <span class="text-vttu-red"><?php echo e(__('Tri thức')); ?></span> <br><?php echo e(__('trong tầm tay.')); ?>

                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        <?php echo e(__('Hệ thống thư viện số hiện đại cung cấp hàng ngàn tài liệu điện tử, giáo trình và bài giảng phục vụ học tập và nghiên cứu đỉnh cao.')); ?>

                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="#explore" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-bold rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all hover:-translate-y-1 flex items-center group">
                                            <?php echo e(__('Bắt đầu khám phá')); ?>

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
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest"><?php echo e(__('Tài liệu số')); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-2xl font-bold text-white">15k+</p>
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest"><?php echo e(__('Bạn đọc')); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-2xl font-bold text-white">24/7</p>
                                        <p class="text-[10px] font-bold text-vttu-yellow uppercase tracking-widest"><?php echo e(__('Truy cập')); ?></p>
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
                                        <?php echo e(__('Kệ sách')); ?> <span class="text-vttu-red"><?php echo e(__('Y khoa')); ?></span> <br><?php echo e(__('Chuyên sâu.')); ?>

                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        <?php echo e(__('Truy cập kho giáo trình, Atlas giải phẫu và công trình nghiên cứu y học dành riêng cho khối ngành sức khỏe tại VTTU.')); ?>

                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="#explore" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-bold rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all hover:-translate-y-1 flex items-center group">
                                            <?php echo e(__('Xem tài liệu y khoa')); ?>

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
                                    <span class="text-sm font-black text-white uppercase tracking-wider"><?php echo e(__('Nghiên cứu chuyên sâu')); ?></span>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vttu-yellow group-hover:bg-vttu-yellow group-hover:text-vttu-red transition-all">
                                        <i class="fas fa-dna"></i>
                                    </div>
                                    <span class="text-sm font-black text-white uppercase tracking-wider"><?php echo e(__('Atlas Giải phẫu số')); ?></span>
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
                                        <?php echo e(__('Kết nối')); ?> <span class="text-vttu-red"><?php echo e(__('Không không gian')); ?></span> <br><?php echo e(__('Học thuật mới.')); ?>

                                    </h1>
                                    <p class="text-xl text-slate-600 leading-relaxed max-w-xl">
                                        <?php echo e(__('Môi trường học tập hiện đại với trang thiết bị tối tân, phục vụ nhu cầu nghiên cứu và sáng tạo không ngừng của sinh viên VTTU.')); ?>

                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="/login" class="px-10 py-5 bg-vttu-red hover:bg-vttu-dark text-white font-black rounded-[2rem] shadow-2xl shadow-vttu-red/20 transition-all flex items-center group">
                                            <?php echo e(__('Đăng nhập ngay')); ?>

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
                                    <span class="text-xs font-black uppercase tracking-wider text-white"><?php echo e(__('Wifi miễn phí')); ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white"><?php echo e(__('Phòng học nhóm')); ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white"><?php echo e(__('Smart Tech')); ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-vttu-yellow group">
                                    <i class="fas fa-check-circle group-hover:scale-125 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-wider text-white"><?php echo e(__('Không gian yên tĩnh')); ?></span>
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
                        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-up">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                                <div class="flex items-center gap-6 overflow-x-auto" id="book-tabs">
                                    <button @click="loadTab('book', 'book-tabs', 'books-content')"
                                        class="tab-btn text-xl font-black px-6 py-2 rounded-xl whitespace-nowrap transition-all <?php echo e(($activeType ?? 'book') === 'book' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20' : 'text-slate-400 hover:text-vttu-red'); ?>">
                                        <?php echo e(__('SÁCH MỚI')); ?>

                                    </button>
                                    <button @click="loadTab('journal', 'book-tabs', 'books-content')"
                                        class="tab-btn text-xl font-black px-6 py-2 rounded-xl whitespace-nowrap transition-all <?php echo e(($activeType ?? '') === 'journal' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20' : 'text-slate-400 hover:text-vttu-red'); ?>">
                                        <?php echo e(__('TẠP CHÍ ONLINE')); ?>

                                    </button>
                                    <button @click="loadTab('folder', 'book-tabs', 'books-content')"
                                        class="tab-btn text-xl font-black px-6 py-2 rounded-xl whitespace-nowrap transition-all <?php echo e(($activeType ?? '') === 'folder' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20' : 'text-slate-400 hover:text-vttu-red'); ?>">
                                        <?php echo e(__('THƯ MỤC')); ?>

                                    </button>
                                </div>
                                
                                
                                <button @click="sidebarOpen = !sidebarOpen; 
                                        $nextTick(() => {
                                            console.log('Sidebar Toggled. Open:', sidebarOpen);
                                            const leftCol = $el.closest('.flex-col');
                                            const infoGrid = document.getElementById('info-grid');
                                            console.log('Left Column Width:', leftCol ? leftCol.offsetWidth : 'N/A');
                                            console.log('Info Grid Width:', infoGrid ? infoGrid.offsetWidth : 'N/A');
                                            if(infoGrid) {
                                                console.log('Child 1 width:', infoGrid.children[0].offsetWidth);
                                                console.log('Child 2 width:', infoGrid.children[1].offsetWidth);
                                            }
                                        })" 
                                        class="hidden lg:flex items-center justify-center w-12 h-12 bg-slate-50 hover:bg-vttu-red hover:text-white text-slate-400 rounded-2xl transition-all shadow-sm border border-slate-100 group">
                                    <i class="fas" :class="sidebarOpen ? 'fa-indent' : 'fa-outdent'"></i>
                                </button>
                            </div>
                            <div id="books-content">
                                <?php echo $__env->make('site.pages.partials.home-books', ['newBooks' => $newBooks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>

                        <div id="info-grid" class="grid grid-cols-1 gap-6 transition-all duration-500" :class="sidebarOpen ? 'md:grid-cols-2' : 'md:grid-cols-2'">
                            <?php
                                $showAnnouncements = \App\Models\Sidebar::where('name', 'Announcements')->where('is_active', 1)->exists();
                            ?>
                            
                            <?php if($showAnnouncements): ?>
                                <!-- Thông báo -->
                                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl shadow-black/5 border border-slate-100 overflow-hidden" data-aos="fade-up">
                                    <div class="bg-gradient-to-r from-vttu-red to-vttu-dark px-6 py-4 flex items-center justify-between">
                                        <h3 class="text-xl font-black text-white font-montserrat tracking-tight uppercase"><?php echo e(__('THÔNG BÁO')); ?></h3>
                                        <a href="#" class="text-vttu-yellow text-[10px] font-black uppercase tracking-widest hover:underline"><?php echo e(__('Xem tất cả')); ?></a>
                                    </div>
                                    <div class="p-6">
                                        <?php if(isset($homeAnnouncements) && $homeAnnouncements->count() > 0): ?>
                                            <?php 
                                                $firstAnnouncement = $homeAnnouncements->first(); 
                                            ?>
                                            <div class="aspect-video bg-slate-100 rounded-2xl mb-4 flex items-center justify-center overflow-hidden group">
                                                <a href="<?php echo e($firstAnnouncement->url); ?>" class="w-full h-full">
                                                    <img src="<?php echo e($firstAnnouncement->featured_image ?? 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=800&q=80'); ?>" 
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                                </a>
                                            </div>
                                            <div class="space-y-3">
                                                <?php $__currentLoopData = $homeAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e($item->url); ?>" class="block text-vttu-dark font-bold hover:text-vttu-red transition-colors text-sm line-clamp-1">
                                                        • <?php echo e($item->title); ?>

                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="aspect-video bg-slate-100 rounded-2xl mb-4 flex items-center justify-center overflow-hidden group">
                                                <img src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            </div>
                                            <div class="space-y-3">
                                                <p class="text-sm text-slate-400 italic">Chưa có thông báo mới cập nhật</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Tin tức sự kiện -->
                            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl shadow-black/5 border border-slate-100 overflow-hidden" data-aos="fade-up">
                                <div class="bg-gradient-to-r from-vttu-red to-vttu-dark px-6 py-4 flex items-center justify-between">
                                    <h3 class="text-xl font-black text-white font-montserrat tracking-tight uppercase"><?php echo e(__('TIN TỨC SỰ KIỆN')); ?></h3>
                                    <a href="<?php echo e(route('news.index')); ?>" class="text-vttu-yellow text-[10px] font-black uppercase tracking-widest hover:underline"><?php echo e(__('Xem tất cả')); ?></a>
                                </div>
                                <div class="p-6">
                                    <?php if(isset($homeNews) && $homeNews->count() > 0): ?>
                                        <?php 
                                            $newsOrdered = $homeNews->sortBy('sort_order')->take(5);
                                            $firstNews = $newsOrdered->first(); 
                                        ?>
                                        <div class="aspect-video bg-slate-100 rounded-2xl mb-4 flex items-center justify-center overflow-hidden group">
                                            <a href="<?php echo e($firstNews->url); ?>" class="w-full h-full">
                                                <img src="<?php echo e($firstNews->featured_image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80'); ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            </a>
                                        </div>
                                        <div class="space-y-3">
                                            <?php $__currentLoopData = $newsOrdered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e($item->url); ?>" class="block text-vttu-dark font-bold hover:text-vttu-red transition-colors text-sm line-clamp-1">
                                                    • <?php echo e($item->title); ?>

                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="aspect-video bg-slate-100 rounded-2xl mb-4 flex items-center justify-center overflow-hidden group">
                                            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-sm text-slate-400 italic">Chưa có tin tức mới cập nhật</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: 2 Tabs (Tin mới | Video) -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-up">
                            <div class="flex items-center gap-6 border-b border-slate-100 pb-4 mb-6" id="news-tabs">
                                <button @click="loadNewsTab('news', 'news-tabs', 'news-content')"
                                    class="tab-btn text-xl font-black px-6 py-2 rounded-xl transition-all <?php echo e(($activeNewsType ?? 'news') === 'news' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20' : 'text-slate-400 hover:text-vttu-red'); ?>">
                                    <?php echo e(__('TIN MỚI')); ?>

                                </button>
                                <button @click="loadNewsTab('video', 'news-tabs', 'news-content')"
                                    class="tab-btn text-xl font-black px-6 py-2 rounded-xl transition-all <?php echo e(($activeNewsType ?? '') === 'video' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20' : 'text-slate-400 hover:text-vttu-red'); ?>">
                                    <?php echo e(__('VIDEO')); ?>

                                </button>
                            </div>
                            <div id="news-content">
                                <?php echo $__env->make('site.pages.partials.home-news', ['homeNews' => $homeNews], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>

                        <!-- Section 4: Giới thiệu sách -->
                    <div class="bg-white rounded-3xl p-6 text-vttu-dark border border-slate-100 shadow-xl relative overflow-hidden" data-aos="fade-up">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 blur-[80px] rounded-full"></div>
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-4">
                                <div class="aspect-[3/4] bg-white rounded-2xl shadow-2xl p-4 rotate-3 overflow-hidden">
                                    <?php $bookIntroImg = \App\Models\SystemSetting::get('book_intro_image'); ?>
                                    <?php if($bookIntroImg): ?>
                                        <img src="<?php echo e(asset('storage/' . $bookIntroImg)); ?>" 
                                             class="w-full h-full object-cover rounded-lg"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="hidden w-full h-full bg-slate-200 rounded-lg items-center justify-center">
                                            <i class="fas fa-book-open text-slate-400 text-5xl"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-full h-full bg-slate-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-book-open text-slate-400 text-5xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                                <div class="md:col-span-8 space-y-6">
                                    <div class="inline-flex px-4 py-1.5 rounded-full bg-vttu-red/5 backdrop-blur border border-vttu-red/10 text-[10px] font-black tracking-widest uppercase text-vttu-red">Book of the Month</div>
                                    <h3 class="text-4xl font-black leading-tight text-vttu-dark"><?php echo e(__('GIỚI THIỆU SÁCH')); ?><br><?php echo e(__('HÀNG THÁNG')); ?></h3>
                                    <p class="text-vttu-red/80 leading-relaxed"><?php echo e(__('Khám phá những tựa sách hay và giá trị nhất được đội ngũ thủ thư VTTU chọn lọc kỹ lưỡng dành cho bạn.')); ?></p>
                                    <button class="px-8 py-4 bg-vttu-yellow text-vttu-dark font-black rounded-2xl hover:bg-yellow-400 transition-all"><?php echo e(__('Khám phá ngay')); ?></button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: 3 Tabs (Sản khoa | Nhi Khoa | Nội Khoa) -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 shadow-xl shadow-black/5 border border-slate-100" data-aos="fade-up">
                            <div class="flex items-center gap-6 border-b border-slate-100 pb-4 mb-6 overflow-x-auto" id="medical-tabs">
                                <button @click="loadMedicalTab('Sản khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-lg font-black text-white bg-vttu-red px-6 py-2 rounded-xl shadow-lg shadow-vttu-red/20 whitespace-nowrap transition-all"><?php echo e(__('CHUYÊN ĐỀ SẢN KHOA')); ?></button>
                                <button @click="loadMedicalTab('Nhi khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-lg font-black text-slate-400 hover:text-vttu-red px-6 py-2 rounded-xl whitespace-nowrap transition-all"><?php echo e(__('CHUYÊN ĐỀ NHI KHOA')); ?></button>
                                <button @click="loadMedicalTab('Nội khoa', 'medical-tabs', 'medical-content')"
                                    class="tab-btn text-lg font-black text-slate-400 hover:text-vttu-red px-6 py-2 rounded-xl whitespace-nowrap transition-all"><?php echo e(__('CHUYÊN ĐỀ NỘI KHOA')); ?></button>
                            </div>
                            <div id="medical-content">
                                <?php echo $__env->make('site.pages.partials.home-medical', ['medicalResources' => $medicalResources], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN (Sidebar) -->
                <div class="transition-all duration-500 ease-in-out lg:sticky lg:top-24 h-fit" 
                     :class="sidebarOpen ? 'lg:w-[25%] opacity-100 visible translate-x-0' : 'lg:w-0 lg:ml-[-2rem] opacity-0 invisible translate-x-full'">
                    <div class="space-y-0 divide-y divide-vttu-red/10 min-w-[280px]">
                        
                        <!-- Thời gian phục vụ -->
                        <div class="bg-vttu-red p-8 relative overflow-hidden group shadow-2xl shadow-vttu-red/20" data-aos="fade-left">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-2xl rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                            <div class="flex items-center gap-4 mb-8 relative z-10">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-vttu-yellow border border-white/20">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight leading-tight"><?php echo e(__('THỜI GIAN')); ?><br><?php echo e(__('PHỤC VỤ')); ?></h3>
                            </div>
                            <div class="space-y-4 relative z-10">
                                <div class="flex justify-between items-center p-5 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/20 transition-all">
                                    <span class="font-bold text-white/90 text-sm uppercase tracking-widest"><?php echo e(__('Thứ 2 - Thứ 6')); ?></span>
                                    <span class="font-black text-vttu-yellow text-base tracking-tighter">7:30 - 20:00</span>
                                </div>
                                <div class="flex justify-between items-center p-5 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/20 transition-all">
                                    <span class="font-bold text-white/90 text-sm uppercase tracking-widest"><?php echo e(__('Thứ 7 - CN')); ?></span>
                                    <span class="font-black text-vttu-yellow text-base tracking-tighter">8:00 - 17:00</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Tabs: Sách Mới | Nổi bật -->
                        <div class="bg-white p-6 shadow-xl" data-aos="fade-left">
                            <div class="flex bg-slate-100 p-1.5 rounded-2xl mb-6" id="resource-tabs">
                                <button @click="loadResourceTab($event, 'new', 'resource-tabs', 'resources-content')"
                                    class="tab-btn flex-1 py-3 text-xs font-black text-center rounded-xl transition-all <?php echo e(($activeResourceType ?? 'new') === 'new' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20 uppercase tracking-widest' : 'text-slate-400 hover:text-vttu-red uppercase tracking-widest'); ?>">
                                    <?php echo e(__('Mới')); ?>

                                </button>
                                <button @click="loadResourceTab($event, 'featured', 'resource-tabs', 'resources-content')"
                                    class="tab-btn flex-1 py-3 text-xs font-black text-center rounded-xl transition-all <?php echo e(($activeResourceType ?? '') === 'featured' ? 'text-white bg-vttu-red shadow-lg shadow-vttu-red/20 uppercase tracking-widest' : 'text-slate-400 hover:text-vttu-red uppercase tracking-widest'); ?>">
                                    <?php echo e(__('Nổi bật')); ?>

                                </button>
                            </div>
                            <div id="resources-content">
                                <?php echo $__env->make('site.pages.partials.sidebar-books', ['sidebarBooks' => $sidebarBooks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>

                        <!-- Video Section -->
                        <div class="bg-white/95 backdrop-blur-sm p-6" data-aos="fade-left">
                            <h3 class="text-xl font-black text-vttu-dark mb-4">VIDEO</h3>
                            <div class="aspect-video bg-slate-900 rounded-2xl relative overflow-hidden group cursor-pointer shadow-lg">
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white group-hover:scale-125 transition-transform border border-white/30">
                                        <i class="fas fa-play text-xs"></i>
                                    </div>
                                </div>
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover opacity-60">
                            </div>
                            <p class="text-sm font-bold text-vttu-dark mt-4 leading-snug">Hướng dẫn đăng ký và sử dụng tài khoản thư viện số VTTU</p>
                        </div>

                        <!-- Link Buttons -->
                        <div class="grid grid-cols-1 gap-4 p-6" data-aos="fade-left">
                            <a href="#" class="flex items-center justify-between p-6 bg-gradient-to-r from-vttu-red to-vttu-dark rounded-3xl text-white shadow-lg shadow-vttu-red/20 hover:-translate-y-1 transition-all group">
                                <div class="flex items-center gap-4">
                                    <i class="fas fa-globe text-xl"></i>
                                    <span class="font-black text-sm uppercase tracking-widest">Tài nguyên mở</span>
                                </div>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-6 bg-gradient-to-r from-vttu-dark to-black rounded-3xl text-white shadow-lg shadow-black/20 hover:-translate-y-1 transition-all group">
                                <div class="flex items-center gap-4">
                                    <i class="fas fa-desktop text-xl"></i>
                                    <span class="font-black text-sm uppercase tracking-widest">Học liệu số</span>
                                </div>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Slider (4 Items - VTTU Lib) -->
    <section class="py-12 bg-white overflow-hidden">
        <div class="px-4 md:px-12 lg:px-24">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-black text-vttu-dark tracking-tight">VTTU LIB <span class="text-vttu-red">NETWORK</span></h3>
                <div class="flex gap-4">
                    <button class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-vttu-red hover:text-white transition-all"><i class="fas fa-chevron-left text-xs"></i></button>
                    <button class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-vttu-red hover:text-white transition-all"><i class="fas fa-chevron-right text-xs"></i></button>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <?php for($i=1; $i<=4; $i++): ?>
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 flex flex-col items-center justify-center text-center group hover:bg-white hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-20 h-20 bg-white rounded-3xl shadow-lg flex items-center justify-center mb-6 group-hover:bg-vttu-red group-hover:text-white transition-all">
                        <i class="fas fa-university text-3xl"></i>
                    </div>
                    <span class="font-black text-vttu-dark text-lg uppercase tracking-widest">VTTU LIB <?php echo e($i); ?></span>
                    <p class="text-[10px] font-bold text-vttu-red/40 mt-2 uppercase tracking-widest">Library Information System</p>
                </div>
                <?php endfor; ?>
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
<?php $__env->startSection('scripts'); ?>
<script>
    // Define the data function first for Alpine.js
    function catalogWizard() {
        return {
            loadTab(type, tabsId, contentId) {
                const target = event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                contentDiv.classList.add('tab-loading');
                
                fetch(`<?php echo e(route('home')); ?>?type=${type}`, {
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
            loadResourceTab(event, type, tabsId, contentId) {
                const target = event.currentTarget;
                const contentDiv = document.getElementById(contentId);
                const tabsDiv = document.getElementById(tabsId);
                
                if (!contentDiv || !tabsDiv) return;

                console.log('--- SIDEBAR TAB LOAD ---');
                console.log('Type requested:', type);
                console.log('Target URL:', `<?php echo e(route('home')); ?>?resource_type=${type}`);

                contentDiv.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity');
                
                fetch(`<?php echo e(route('home')); ?>?resource_type=${type}`, {
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
                
                fetch(`<?php echo e(route('home')); ?>?medical_type=${type}`, {
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
                
                fetch(`<?php echo e(route('home')); ?>?news_type=${type}`, {
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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/home.blade.php ENDPATH**/ ?>