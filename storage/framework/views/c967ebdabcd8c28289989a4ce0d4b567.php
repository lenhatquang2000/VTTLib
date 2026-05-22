<?php $__env->startSection('title', $node->meta_title ?: $node->display_name . ' - Thư viện'); ?>

<?php $__env->startSection('meta-description', $node->meta_description); ?>
<?php $__env->startSection('meta-keywords', $node->meta_keywords); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen">
    <!-- Breadcrumb -->
    <?php if($breadcrumb && count($breadcrumb) > 1): ?>
    <nav class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm">
                <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($index > 0): ?>
                        <li class="text-gray-400">/</li>
                    <?php endif; ?>
                    <?php if($index === count($breadcrumb) - 1): ?>
                        <li class="text-gray-600 font-medium"><?php echo e($item['name']); ?></li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo e($item['url']); ?>" class="text-vttu-red hover:text-vttu-dark">
                                <?php echo e($item['name']); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Page Header -->
    <?php if(!$node->activeItems()->count()): ?>
    <section class="bg-white border-b border-slate-100 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-black mb-4 text-vttu-dark"><?php echo e($node->display_name); ?> 2</h1>
                <?php if($node->description): ?>
                    <p class="text-xl text-vttu-red/80"><?php echo e($node->description); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Page Content from Items -->
    <?php if($node->activeItems()->count() > 0): ?>
        <?php $__currentLoopData = $node->activeItems()->ordered()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('site.items.' . $item->item_type, ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php elseif($node->content): ?>
        <!-- Fallback to legacy content -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="prose max-w-none">
                    <?php echo $node->content; ?>

                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- Empty state -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="text-center py-12">
                    <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Trang đang được cập nhật</h3>
                    <p class="text-gray-500">Nội dung của trang này sẽ sớm được bổ sung.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Related Pages -->
    <?php if($node->getSiblings()->count() > 0): ?>
    <section class="py-16 bg-white border-t border-slate-100">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-black text-center mb-8 text-vttu-dark">Trang liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $node->getSiblings()->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sibling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-6 rounded-3xl shadow-xl shadow-black/5 border border-slate-100 hover:border-vttu-red/20 transition group">
                    <div class="flex items-center mb-3">
                        <?php if($sibling->icon): ?>
                            <i class="<?php echo e($sibling->icon); ?> text-vttu-red mr-3"></i>
                        <?php endif; ?>
                        <h3 class="font-black text-vttu-dark group-hover:text-vttu-red transition-colors"><?php echo e($sibling->display_name); ?></h3>
                    </div>
                    <?php if($sibling->description): ?>
                        <p class="text-vttu-dark/70 text-sm mb-4 line-clamp-2"><?php echo e($sibling->description); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo e($sibling->getUrl()); ?>" class="text-vttu-red hover:text-vttu-dark font-black text-sm uppercase tracking-wider">
                        Xem thêm →
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/page.blade.php ENDPATH**/ ?>