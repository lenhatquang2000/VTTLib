<?php $__env->startSection('title', $node->meta_title ?: 'Giới Thiệu - Thư viện số'); ?>

<?php $__env->startSection('content'); ?>
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
                    <?php echo e($node->display_name); ?>

                </h1>
                <p class="mt-6 text-lg md:text-xl text-slate-300 leading-relaxed max-w-3xl">
                    <?php echo e($node->description ?: 'Giới thiệu tổng quan về Thư viện và định hướng phát triển.'); ?>

                </p>
            </div>
        </div>
    </section>

    <?php
        $sidebarItems = collect();
        if ($node->parent) {
            $sidebarItems = $node->parent->activeChildren()->get();
        } else {
            $sidebarItems = $node->activeChildren()->get();
        }
        if ($sidebarItems->count() === 0) {
            $sidebarItems = collect([$node]);
        }
    ?>

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
                                <?php $__currentLoopData = $sidebarItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $active = $item->id === $node->id;
                                    ?>
                                    <a href="<?php echo e($item->getUrl()); ?>"
                                       class="block px-6 py-4 rounded-2xl font-black transition-all <?php echo e($active ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-700 hover:bg-slate-50'); ?>">
                                        <div class="flex items-center justify-between">
                                            <span><?php echo e($item->display_name); ?></span>
                                            <i class="fas fa-chevron-right text-xs <?php echo e($active ? 'text-white/90' : 'text-slate-300'); ?>"></i>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <span class="animate-gradient-text"><?php echo e(mb_strtoupper($node->display_name)); ?></span>
                            </h2>

                            <div class="mt-8 prose prose-slate max-w-none">
                                <?php echo $node->content; ?>

                            </div>

                            
                            <div id="page-builder-content" class="mt-12 space-y-12">
                                <?php if(isset($node) && $node->activeItems->count() > 0): ?>
                                    <?php $__currentLoopData = $node->activeItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="builder-item animate-up" data-aos="fade-up">
                                            <?php
                                                $itemData = is_string($item->item_data) ? json_decode($item->item_data, true) : $item->item_data;
                                            ?>
                                            <?php if ($__env->exists('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData])) echo $__env->make('site.items.' . $item->item_type, ['item' => $item, 'data' => $itemData], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/about.blade.php ENDPATH**/ ?>