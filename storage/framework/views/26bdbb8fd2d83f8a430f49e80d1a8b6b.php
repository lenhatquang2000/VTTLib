<?php $__env->startSection('title', 'Trang Chủ - Thư viện'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen">
    <!-- Dynamic Hero Section from SiteNode -->
    <?php if($homeNode && $homeNode->content): ?>
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="prose max-w-none text-center">
                <?php echo $homeNode->content; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Dynamic Features Section from SiteNodes -->
    <?php if($menuItems && $menuItems->count() > 0): ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Tính năng nổi bật</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php $__currentLoopData = $menuItems->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="<?php echo e($item->icon ?? 'fas fa-star'); ?> text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2"><?php echo e($item->display_name); ?></h3>
                    <p class="text-gray-600"><?php echo e($item->description ?? 'Tính năng nổi bật của hệ thống'); ?></p>
                    <?php if($item->url || $item->route_name): ?>
                    <a href="<?php echo e($item->url ?? route($item->route_name)); ?>" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Xem chi tiết →
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/home.blade.php ENDPATH**/ ?>