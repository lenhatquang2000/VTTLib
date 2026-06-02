<?php $__env->startSection('title', $node->display_name . ' - VTTLib'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen">
    
    <section class="relative pt-32 pb-20 bg-white overflow-hidden">
        
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-vttu-red/5 to-transparent"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-vttu-red/[0.03] blur-[100px] rounded-full"></div>
        
        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="max-w-4xl">
                    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.3em] text-vttu-red mb-6" data-aos="fade-down">
                        <a href="/" class="hover:text-vttu-dark transition-colors">Trang chủ</a>
                        <i class="fas fa-chevron-right text-[7px] opacity-50"></i>
                        <span class="text-vttu-dark/40"><?php echo e($node->display_name); ?></span>
                    </nav>

                    <h1 class="text-4xl md:text-6xl font-black text-vttu-dark leading-tight tracking-tighter mb-6 uppercase" data-aos="fade-right">
                        <?php echo e($node->display_name); ?>

                    </h1>
                    
                    <p class="text-lg text-vttu-dark/60 font-medium max-w-2xl mb-10" data-aos="fade-right" data-aos-delay="100">
                        <?php echo e($node->description ?: 'Cập nhật những thông tin, sự kiện và hoạt động mới nhất từ Thư viện Đại học Võ Trường Toản.'); ?>

                    </p>

                    
                    <div class="relative max-w-2xl" data-aos="fade-up" data-aos-delay="200">
                        <form action="<?php echo e(route('news.search')); ?>" method="GET" class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-vttu-red/20 to-vttu-red/5 rounded-[2rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                            <div class="relative flex items-center bg-slate-50 border border-slate-100 rounded-[1.5rem] overflow-hidden p-1.5 shadow-xl">
                                <div class="flex-1 flex items-center px-6">
                                    <i class="fas fa-search text-slate-400 mr-4"></i>
                                    <input type="text" name="q" placeholder="Tìm kiếm bài viết, tin tức..." 
                                           class="w-full py-4 bg-transparent border-none focus:ring-0 text-slate-700 font-bold placeholder:text-slate-400">
                                </div>
                                <button type="submit" class="bg-vttu-red hover:bg-vttu-dark text-white px-8 py-4 rounded-[1.2rem] font-black text-sm uppercase tracking-widest transition-all duration-300 shadow-lg shadow-vttu-red/30">
                                    Tìm ngay
                                </button>
                            </div>
                        </form>
                        <div class="flex flex-wrap gap-4 mt-6">
                            <span class="text-[10px] font-black text-vttu-dark/20 uppercase tracking-widest">Gợi ý:</span>
                            <?php $__currentLoopData = ['Thông báo', 'Sự kiện', 'Sách mới', 'Học thuật']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('news.search', ['q' => $tag])); ?>" class="text-[10px] font-bold text-vttu-dark/40 hover:text-vttu-red transition-colors underline underline-offset-4 decoration-vttu-red/30">#<?php echo e($tag); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="hidden lg:block relative" data-aos="fade-left" data-aos-delay="300">
                    <div class="relative z-10 w-full aspect-square max-w-md mx-auto">
                        <div class="absolute inset-0 bg-gradient-to-tr from-vttu-red/20 to-transparent rounded-[3rem] rotate-6 scale-105 blur-2xl"></div>
                        <div class="relative overflow-hidden rounded-[3rem] shadow-2xl border border-slate-100 group">
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                 alt="News Illustration">
                            <div class="absolute inset-0 bg-gradient-to-t from-vttu-dark/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-8 left-8 right-8">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 bg-vttu-red rounded-full text-[8px] font-black text-white uppercase tracking-widest">Tin mới</span>
                                    <span class="text-[10px] font-bold text-white/60">Cập nhật 5 phút trước</span>
                                </div>
                                <p class="text-sm font-bold text-white leading-snug">Khám phá kho tri thức số đa dạng tại VTTU Library</p>
                            </div>
                        </div>
                        
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-vttu-red rounded-3xl flex items-center justify-center shadow-2xl shadow-vttu-red/40 animate-bounce">
                            <i class="fas fa-newspaper text-white text-2xl"></i>
                        </div>
                        <div class="absolute -bottom-6 -left-6 px-6 py-4 bg-white rounded-2xl border border-slate-100 shadow-2xl">
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-2">
                                    <?php if(isset($latestPatrons) && $latestPatrons->count() > 0): ?>
                                        <?php $__currentLoopData = $latestPatrons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patron): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="w-6 h-6 rounded-full border-2 border-white overflow-hidden shadow-sm">
                                                <img src="<?php echo e(asset('storage/' . $patron->profile_image)); ?>" class="w-full h-full object-cover" onerror="this.src='https://i.pravatar.cc/100?u=<?php echo e($patron->id); ?>'">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php $__currentLoopData = [1, 2, 3]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="w-6 h-6 rounded-full border-2 border-white overflow-hidden shadow-sm">
                                                <img src="https://i.pravatar.cc/100?img=<?php echo e($i+10); ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[10px] font-black text-vttu-dark uppercase tracking-widest">+<?php echo e(number_format($totalUsers ?? 0)); ?> bạn đọc</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="bg-vttu-red py-4 relative z-20 shadow-lg shadow-vttu-red/20">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                            <i class="fas fa-bolt text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest">Tin nhanh:</span>
                    </div>
                    <div class="hidden md:block overflow-hidden h-6">
                        <div class="animate-marquee-vertical space-y-2">
                            <p class="text-[11px] font-bold text-white/90">Thông báo về việc mượn trả sách trong kỳ nghỉ lễ sắp tới...</p>
                            <p class="text-[11px] font-bold text-white/90">Hệ thống thư viện số đã cập nhật thêm 500+ tài liệu mới...</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-white/50 uppercase tracking-widest">Theo dõi:</span>
                    <div class="flex gap-2">
                        <?php $__currentLoopData = ['facebook-f', 'youtube', 'instagram']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="#" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white text-white hover:text-vttu-red flex items-center justify-center transition-all duration-300">
                                <i class="fab fa-<?php echo e($social); ?> text-xs"></i>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <section class="py-20" x-data="{ viewMode: 'grid' }">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Main Listing (80%) -->
                <div class="lg:w-[80%]">
                    
                    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-[1.5rem] shadow-sm border border-slate-100">
                        <div class="flex items-center gap-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Chế độ xem:</span>
                            <div class="flex bg-slate-50 p-1 rounded-xl">
                                <button @click="viewMode = 'grid'" 
                                        :class="viewMode === 'grid' ? 'bg-white text-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red'"
                                        class="w-10 h-10 rounded-lg flex items-center justify-center transition-all">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button @click="viewMode = 'list'" 
                                        :class="viewMode === 'list' ? 'bg-white text-vttu-red shadow-sm' : 'text-slate-400 hover:text-vttu-red'"
                                        class="w-10 h-10 rounded-lg flex items-center justify-center transition-all">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mr-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tổng cộng: <?php echo e(number_format($news->total())); ?> bài viết</span>
                        </div>
                    </div>

                    <?php if(isset($news) && $news->count() > 0): ?>
                        
                        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-[2.5rem] shadow-xl shadow-black/5 border border-slate-100 overflow-hidden group hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                                <a href="<?php echo e($item->url); ?>" class="block">
                                    <div class="aspect-video w-full overflow-hidden relative">
                                        <img src="<?php echo e($item->featured_image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80'); ?>" 
                                             alt="<?php echo e($item->title); ?>" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <?php if($item->category): ?>
                                        <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full bg-white/90 backdrop-blur-md text-vttu-red text-[10px] font-black uppercase tracking-widest shadow-sm">
                                            <?php echo e($item->category->name); ?>

                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-8">
                                        <div class="flex items-center text-slate-400 text-xs font-bold mb-3 uppercase tracking-widest">
                                            <i class="far fa-calendar-alt mr-2"></i>
                                            <?php echo e($item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y')); ?>

                                        </div>
                                        <h2 class="text-xl font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-snug mb-4">
                                            <?php echo e($item->title); ?>

                                        </h2>
                                        <p class="text-slate-500 text-sm line-clamp-3 leading-relaxed mb-6 font-medium">
                                            <?php echo e($item->summary); ?>

                                        </p>
                                        <span class="inline-flex items-center text-vttu-red text-xs font-black uppercase tracking-[0.2em] group-hover:translate-x-2 transition-transform">
                                            Đọc tiếp
                                            <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                                        </span>
                                    </div>
                                </a>
                            </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <div x-show="viewMode === 'list'" class="space-y-4">
                            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                    <div class="flex-1">
                                        <h2 class="text-lg font-black text-vttu-dark group-hover:text-vttu-red transition-colors mb-2">
                                            <a href="<?php echo e($item->url); ?>"><?php echo e($item->title); ?></a>
                                        </h2>
                                        <div class="flex flex-wrap items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            <div class="flex items-center">
                                                <i class="far fa-calendar-alt mr-2"></i>
                                                <?php echo e($item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y')); ?>

                                            </div>
                                            <?php if($item->category): ?>
                                            <div class="flex items-center text-vttu-red">
                                                <i class="fas fa-folder mr-2"></i>
                                                <?php echo e($item->category->name); ?>

                                            </div>
                                            <?php endif; ?>
                                            <div class="flex items-center">
                                                <i class="far fa-eye mr-2"></i>
                                                <?php echo e(number_format($item->view_count ?? 0)); ?> lượt xem
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="<?php echo e($item->url); ?>" class="inline-flex items-center px-6 py-3 bg-slate-50 group-hover:bg-vttu-red text-slate-400 group-hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                                            Đọc tiếp
                                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-16">
                            <?php echo e($news->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded-[2.5rem] p-20 text-center shadow-xl shadow-black/5 border border-slate-100">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300">
                                <i class="fas fa-newspaper text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-vttu-dark mb-4">Không tìm thấy bài viết nào</h3>
                            <p class="text-slate-500 font-medium text-lg mb-10">Vui lòng quay lại sau hoặc thử từ khóa tìm kiếm khác.</p>
                            <a href="<?php echo e(url()->current()); ?>" class="inline-flex px-10 py-4 bg-vttu-red text-white font-black rounded-2xl hover:bg-vttu-dark transition-all uppercase tracking-widest text-sm">Xem tất cả</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar Notifications (20%) -->
                <aside class="lg:w-[20%] space-y-8">
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-black/5 border border-slate-100 sticky top-24" data-aos="fade-left">
                        <h3 class="text-lg font-black text-vttu-dark mb-6 flex items-center">
                            <span class="w-1.5 h-6 bg-vttu-red rounded-full mr-3"></span>
                            THÔNG BÁO
                        </h3>
                        
                        <div class="space-y-6">
                            
                            <?php if($node->content): ?>
                                <div class="prose prose-sm max-w-none text-slate-600 font-medium leading-relaxed">
                                    <?php echo $node->content; ?>

                                </div>
                            <?php else: ?>
                                <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                                    <i class="fas fa-bell text-slate-200 text-2xl mb-3"></i>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Không có thông báo mới</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="mt-10 pt-8 border-t border-slate-100">
                            <h4 class="text-[10px] font-black text-vttu-red/40 uppercase tracking-[0.2em] mb-4">Liên kết nhanh</h4>
                            <ul class="space-y-3">
                                <li><a href="/noi-quy-thu-vien" class="text-xs font-bold text-slate-500 hover:text-vttu-red transition-colors flex items-center"><i class="fas fa-chevron-right text-[8px] mr-2 opacity-30"></i> Nội quy thư viện</a></li>
                                <li><a href="/huong-dan" class="text-xs font-bold text-slate-500 hover:text-vttu-red transition-colors flex items-center"><i class="fas fa-chevron-right text-[8px] mr-2 opacity-30"></i> Hướng dẫn mượn trả</a></li>
                                <li><a href="/opac/search" class="text-xs font-bold text-slate-500 hover:text-vttu-red transition-colors flex items-center"><i class="fas fa-chevron-right text-[8px] mr-2 opacity-30"></i> Biểu mẫu đăng ký</a></li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/news.blade.php ENDPATH**/ ?>