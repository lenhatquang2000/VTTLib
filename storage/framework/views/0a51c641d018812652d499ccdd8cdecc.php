<?php $__env->startSection('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'emerald',
        'badgeText'    => 'Thời gian phục vụ',
        'badgeIcon'    => 'fas fa-clock',
        'sectionLabel' => 'Giới thiệu',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/thoi-gian-phuc-vu.blade.php ENDPATH**/ ?>