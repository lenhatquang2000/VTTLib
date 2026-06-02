<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('site.partials.inner-page', [
        'node' => $node,
        'sectionLabel' => __('Tài nguyên')
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/digital-resources.blade.php ENDPATH**/ ?>