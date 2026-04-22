<?php $__env->startSection('title', $node->meta_title ?: $node->display_name . ' - Thư viện số'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-50">
    <!-- Header Section -->
    <section class="relative overflow-hidden bg-slate-950 text-white pt-32 pb-20" data-aos="fade-down">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
        <div class="absolute -top-24 -right-24 w-[520px] h-[520px] bg-blue-500/10 blur-[140px] rounded-full animate-float"></div>
        
        <div class="w-full px-4 md:px-12 lg:px-24 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur text-[10px] font-black uppercase tracking-[0.3em] mb-6 text-blue-300">
                        <i class="fas fa-book-open-reader"></i> Hướng dẫn sử dụng
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight">
                        <?php echo e($node->display_name); ?>

                    </h1>
                </div>
                <div class="hidden lg:block pb-2">
                    <nav class="flex items-center gap-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
                        <a href="/" class="hover:text-white transition-colors">Trang chủ</a>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <a href="/page/huong-dan" class="hover:text-white transition-colors">Hướng dẫn</a>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <span class="text-white"><?php echo e($node->display_name); ?></span>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <?php
        $sidebarItems = collect();
        if ($node->parent) {
            $sidebarItems = $node->parent->activeChildren()->orderBy('sort_order')->get();
        } elseif ($node->activeChildren->count() > 0) {
            $sidebarItems = $node->activeChildren()->orderBy('sort_order')->get();
        }
    ?>

    <!-- Main Content -->
    <section class="py-16">
        <div class="w-full px-4 md:px-12 lg:px-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Sidebar -->
                <aside class="lg:col-span-3">
                    <div class="sticky top-24 space-y-6">
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden">
                            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Danh mục</div>
                                <div class="mt-2 text-xl font-black text-slate-900 leading-tight">Hướng dẫn</div>
                            </div>
                            <nav class="p-4">
                                <?php if($sidebarItems->count() > 0): ?>
                                    <?php $__currentLoopData = $sidebarItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e($item->getUrl()); ?>" 
                                           class="group flex items-center justify-between px-6 py-4 rounded-2xl transition-all mb-1 <?php echo e($node->id === $item->id ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600'); ?>">
                                            <span class="font-bold text-sm"><?php echo e($item->display_name); ?></span>
                                            <i class="fas fa-chevron-right text-[10px] transition-transform group-hover:translate-x-1 <?php echo e($node->id === $item->id ? 'text-white/50' : 'text-slate-300'); ?>"></i>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <a href="/page/huong-dan" class="flex items-center gap-3 px-6 py-4 text-blue-600 font-bold hover:bg-blue-50 rounded-2xl transition-all">
                                        <i class="fas fa-arrow-left text-xs"></i> Quay lại mục chính
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>

                        <!-- Card hỗ trợ -->
                        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/20 blur-3xl rounded-full transition-all group-hover:scale-150"></div>
                            <div class="relative z-10 text-center">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur">
                                    <i class="fas fa-headset text-2xl text-blue-400"></i>
                                </div>
                                <h4 class="font-black text-lg mb-2">Bạn cần hỗ trợ?</h4>
                                <p class="text-slate-400 text-xs leading-relaxed mb-6 px-4">Đội ngũ thư viện luôn sẵn sàng giải đáp thắc mắc của bạn.</p>
                                <a href="tel:0292xxxxxx" class="block w-full py-4 bg-white text-slate-900 rounded-2xl font-black text-sm hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                                    Gửi yêu cầu ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Vùng nội dung chính -->
                <main class="lg:col-span-9 space-y-12">
                    <?php if(!$node->parent_id): ?>
                        
                        <div class="space-y-12" data-aos="fade-up">
                            <!-- Group 1: Tài khoản -->
                            <div class="p-10 rounded-[3rem] border border-slate-200 bg-slate-50/50 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 blur-[80px] rounded-full"></div>
                                <div class="flex items-center gap-4 mb-10 relative z-10">
                                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30 animate-pulse"><i class="fas fa-shield-alt text-xl"></i></div>
                                    <div><h3 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Tài khoản & Ứng dụng</h3><p class="text-slate-400 text-sm font-medium">Quản lý định danh và trải nghiệm di động</p></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                                    <a href="/page/cam-nang-hdsd" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-blue-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 animate-float"><i class="fas fa-book text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-blue-600 transition-colors text-xs uppercase">Cẩm nang HDSD</h4>
                                    </a>
                                    <a href="/page/tai-app-mobile" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 animate-float" style="animation-delay: 0.2s"><i class="fas fa-mobile-alt text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors text-xs uppercase text-nowrap">Tải App Mobile</h4>
                                    </a>
                                    <a href="/page/dang-nhap-tai-khoan" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-emerald-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 animate-float" style="animation-delay: 0.4s"><i class="fas fa-user-check text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-emerald-600 transition-colors text-xs uppercase">Đăng nhập</h4>
                                    </a>
                                    <a href="/page/doi-mat-khau" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-amber-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 animate-float" style="animation-delay: 0.6s"><i class="fas fa-key text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-amber-600 transition-colors text-xs uppercase">Đổi mật khẩu</h4>
                                    </a>
                                </div>
                            </div>

                            <!-- Group 2: Tra cứu -->
                            <div class="p-10 rounded-[3rem] border border-slate-200 bg-white relative overflow-hidden shadow-sm">
                                <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-500/5 blur-[80px] rounded-full"></div>
                                <div class="flex items-center gap-4 mb-10 relative z-10">
                                    <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-600/30 animate-pulse"><i class="fas fa-search text-xl"></i></div>
                                    <div><h3 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Tra cứu & Mượn trả</h3><p class="text-slate-400 text-sm font-medium">Khai thác tài nguyên Thư viện hiệu quả</p></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                                    <a href="/page/tra-cuu-tai-lieu-giay" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-rose-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 animate-float mx-auto md:mx-0"><i class="fas fa-book-reader text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-rose-600 transition-colors text-[10px] uppercase">Tra cứu sách giấy</h4>
                                    </a>
                                    <a href="/page/tra-cuu-tai-lieu-so" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-cyan-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-16 h-16 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600 mb-6 group-hover:bg-cyan-600 group-hover:text-white transition-all duration-500 animate-float mx-auto md:mx-0" style="animation-delay: 0.3s"><i class="fas fa-laptop text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-cyan-600 transition-colors text-[10px] uppercase">Tra cứu sách số</h4>
                                    </a>
                                    <a href="/page/muon-truoc-gia-han" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-violet-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-16 h-16 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600 mb-6 group-hover:bg-violet-600 group-hover:text-white transition-all duration-500 animate-float mx-auto md:mx-0" style="animation-delay: 0.6s"><i class="fas fa-history text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-violet-600 transition-colors text-[10px] uppercase">Mượn trước / Gia hạn</h4>
                                    </a>
                                    <a href="/page/de-nghi-bo-sung" class="group relative p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl hover:border-orange-300 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                                        <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500 animate-float mx-auto md:mx-0" style="animation-delay: 0.9s"><i class="fas fa-plus-circle text-2xl"></i></div>
                                        <h4 class="font-black text-slate-900 group-hover:text-orange-600 transition-colors text-[10px] uppercase">Đề nghị bổ sung</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl p-8 md:p-12 relative overflow-hidden min-h-[600px]">
                        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/5 blur-[120px] rounded-full pointer-events-none animate-float"></div>
                        
                        <article class="relative z-10">
                            <!-- Nội dung Rich Text -->
                            <div class="prose prose-slate prose-lg max-w-none 
                                prose-headings:font-black prose-headings:tracking-tight prose-headings:text-slate-900
                                prose-p:text-slate-600 prose-p:leading-relaxed
                                prose-strong:text-slate-900
                                prose-li:text-slate-600
                                prose-table:rounded-2xl prose-table:overflow-hidden prose-th:bg-slate-900 prose-th:text-white prose-th:p-4 prose-td:p-4">
                                <?php echo $node->content; ?>

                            </div>

                            <!-- Vùng dành cho Page Builder -->
                            <div id="page-builder-content" class="mt-16 space-y-16">
                                <?php if($node->activeItems->count() > 0): ?>
                                    <?php $__currentLoopData = $node->activeItems()->ordered()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="builder-item" data-aos="fade-up">
                                            <?php
                                                $itemData = is_string($item->item_data) ? json_decode($item->item_data, true) : $item->item_data;
                                            ?>
                                            <?php if ($__env->exists('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData])) echo $__env->make('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <?php
                            $prev = $sidebarItems->where('sort_order', '<', $node->sort_order)->last();
                            $next = $sidebarItems->where('sort_order', '>', $node->sort_order)->first();
                        ?>
                        
                        <div class="w-full md:w-1/2">
                            <?php if($prev): ?>
                                <a href="<?php echo e($prev->getUrl()); ?>" class="flex items-center gap-6 p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <i class="fas fa-arrow-left"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Trang trước</div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?php echo e($prev->display_name); ?></div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="w-full md:w-1/2">
                            <?php if($next): ?>
                                <a href="<?php echo e($next->getUrl()); ?>" class="flex items-center justify-end gap-6 p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group text-right">
                                    <div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiếp theo</div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?php echo e($next->display_name); ?></div>
                                    </div>
                                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
</div>

<style>
    /* Typography adjustments for help pages */
    .prose ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 2rem; }
    .prose ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 2rem; }
    .prose li { margin-bottom: 0.75rem; }
    .prose h3 { margin-top: 3rem; border-left: 4px solid #2563eb; padding-left: 1rem; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/help.blade.php ENDPATH**/ ?>