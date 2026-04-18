<?php $__env->startSection('title', $node->meta_title ?: 'Trang Chủ - Thư viện số'); ?>
<?php $__env->startSection('meta-description', $node->meta_description ?: 'Hệ thống quản lý thư viện số hiện đại'); ?>
<?php $__env->startSection('meta-keywords', $node->meta_keywords ?: 'thư viện, tra cứu, sách, tài liệu'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Modern Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-900 via-blue-900 to-blue-800 text-white overflow-hidden">
        <!-- Background SVG Waves -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" fill-opacity="1" d="M0,160L48,176C96,192,192,224,288,224C384,224,480,192,576,165.3C672,139,768,117,864,138.7C960,160,1056,224,1152,245.3C1248,267,1344,245,1392,234.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>

        <div class="container mx-auto px-6 py-24 md:py-32 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/2 space-y-8 text-center md:text-left">
                    <h1 class="text-5xl md:text-7xl font-extrabold leading-tight tracking-tight">
                        Kiến thức trong tầm tay <span class="text-blue-400">của bạn.</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 max-w-2xl leading-relaxed">
                        Khám phá hàng ngàn tài liệu, sách và tài nguyên học thuật tại hệ thống thư viện số hiện đại bậc nhất.
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 pt-4">
                        <a href="<?php echo e(route('site.page', 'tra-cuu-opac')); ?>" class="px-8 py-4 bg-white text-blue-900 font-bold rounded-full shadow-lg hover:bg-blue-50 transition-all transform hover:-translate-y-1">
                            Bắt đầu tra cứu
                        </a>
                        <a href="<?php echo e(route('news.index')); ?>" class="px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-full hover:bg-white/10 transition-all backdrop-blur-sm">
                            Xem tin tức
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 relative">
                    <div class="relative z-10 animate-float">
                        <img src="https://img.freepik.com/free-vector/digital-library-concept-illustration_114360-8433.jpg" 
                             alt="Digital Library" 
                             class="rounded-3xl shadow-2xl border border-white/20 transform md:rotate-3">
                    </div>
                    <!-- Decorative Circle -->
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-500/30 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Wave -->
        <div class="relative h-24 w-full overflow-hidden leading-none rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[90px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5,73.84-4.36,147.54,16.88,218.44,35.26,69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#f8fafc"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#f8fafc"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#f8fafc"></path>
            </svg>
        </div>
    </section>

    <!-- Modern Features Section -->
    <section class="py-24 bg-slate-50 relative">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm">Khám phá dịch vụ</h2>
                <h3 class="text-4xl md:text-5xl font-black text-slate-900">Tính năng nổi bật cho bạn</h3>
                <p class="text-slate-600 text-lg">Hệ thống cung cấp đa dạng các dịch vụ giúp bạn tiếp cận nguồn tri thức một cách nhanh chóng và thuận tiện nhất.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                    $features = [
                        ['icon' => 'fas fa-search', 'title' => 'Tra cứu thông minh', 'desc' => 'Tìm kiếm hàng triệu đầu sách và tài liệu với bộ lọc nâng cao cực kỳ chính xác.', 'color' => 'blue', 'route' => 'tra-cuu-opac'],
                        ['icon' => 'fas fa-book-reader', 'title' => 'Đọc sách trực tuyến', 'desc' => 'Hỗ trợ đọc đa nền tảng, ghi chú và lưu lại trang đang đọc một cách thông minh.', 'color' => 'indigo', 'route' => 'site.page'],
                        ['icon' => 'fas fa-newspaper', 'title' => 'Tin tức & Sự kiện', 'desc' => 'Cập nhật nhanh nhất các thông tin mới nhất về thư viện và đời sống học thuật.', 'color' => 'emerald', 'route' => 'news.index'],
                        ['icon' => 'fas fa-id-card', 'title' => 'Quản lý tài khoản', 'desc' => 'Gia hạn sách, xem lịch sử mượn và quản lý thông tin cá nhân chỉ trong vài click.', 'color' => 'rose', 'route' => 'login'],
                        ['icon' => 'fas fa-mobile-alt', 'title' => 'Hỗ trợ di động', 'desc' => 'Ứng dụng mượt mà trên mọi thiết bị di động, truy cập mọi lúc mọi nơi.', 'color' => 'amber', 'route' => 'home'],
                        ['icon' => 'fas fa-headset', 'title' => 'Hỗ trợ 24/7', 'desc' => 'Đội ngũ hỗ trợ nhiệt tình, giải đáp mọi thắc mắc của bạn qua chatbot và email.', 'color' => 'violet', 'route' => 'site.page'],
                    ];
                ?>

                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white p-10 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-blue-100 relative overflow-hidden transform hover:-translate-y-2">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?php echo e($f['color']); ?>-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50"></div>
                    <div class="w-16 h-16 bg-<?php echo e($f['color']); ?>-100 rounded-2xl flex items-center justify-center mb-8 relative z-10 group-hover:bg-<?php echo e($f['color']); ?>-600 group-hover:rotate-6 transition-all duration-300">
                        <i class="<?php echo e($f['icon']); ?> text-<?php echo e($f['color']); ?>-600 text-2xl group-hover:text-white transition-colors"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 mb-4 relative z-10"><?php echo e($f['title']); ?></h4>
                    <p class="text-slate-600 leading-relaxed mb-6 relative z-10"><?php echo e($f['desc']); ?></p>
                    <a href="<?php echo e($f['route'] === 'home' ? '#' : (Route::has($f['route']) ? route($f['route'], $f['route'] === 'site.page' ? ['code' => 'dich-vu'] : []) : '#')); ?>" class="text-<?php echo e($f['color']); ?>-600 font-bold flex items-center gap-2 group-hover:gap-3 transition-all">
                        Xem thêm <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-blue-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="space-y-2">
                    <div class="text-5xl font-black text-blue-400 tabular-nums">50k+</div>
                    <div class="text-blue-100 font-medium">Đầu sách & Tài liệu</div>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-black text-blue-400 tabular-nums">12k+</div>
                    <div class="text-blue-100 font-medium">Bạn đọc đăng ký</div>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-black text-blue-400 tabular-nums">1.5k+</div>
                    <div class="text-blue-100 font-medium">Truy cập hàng ngày</div>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-black text-blue-400 tabular-nums">24h</div>
                    <div class="text-blue-100 font-medium">Phục vụ trực tuyến</div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    @keyframes float {
        0% { transform: translateY(0px) rotate(3deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(3deg); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/home.blade.php ENDPATH**/ ?>