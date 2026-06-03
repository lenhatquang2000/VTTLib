<!-- Top Navigation Row -->
<?php
    $currentView = request()->query('view', 'list');
    $isLanding = $currentView === 'landing';
    $isIntro = $currentView === 'intro';
    $isContribute = $currentView === 'contribute';
    $isList = $currentView === 'list' && !request()->routeIs('site.oer.*');
?>
<div class="flex flex-wrap items-center gap-x-6 gap-y-2 pb-3 border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-700">
    <a href="<?php echo e(route('site.oer.landing')); ?>" class="flex items-center gap-1.5 <?php echo e($isLanding ? 'text-vttu-red' : 'hover:text-vttu-red'); ?> transition-colors">
        <i class="fas fa-home <?php echo e($isLanding ? 'text-vttu-red' : 'text-slate-400'); ?> text-[12px]"></i>
        <span><?php echo e(__('Quay về OER')); ?></span>
    </a>
    <a href="<?php echo e(route('site.oer.intro')); ?>" class="flex items-center gap-1.5 <?php echo e($isIntro ? 'text-vttu-red' : 'hover:text-vttu-red'); ?> transition-colors">
        <i class="fas fa-info-circle <?php echo e($isIntro ? 'text-vttu-red' : 'text-slate-400'); ?> text-[12px]"></i>
        <span><?php echo e(__('Giới thiệu')); ?></span>
    </a>
    <a href="<?php echo e(route('site.page', 'tai-nguyen-giao-duc-mo')); ?>" class="flex items-center gap-1.5 <?php echo e($isList ? 'text-vttu-red' : 'hover:text-vttu-red'); ?> transition-colors">
        <i class="fas fa-database <?php echo e($isList ? 'text-vttu-red' : 'text-slate-400'); ?> text-[12px]"></i>
        <span><?php echo e(__('Kho tài liệu mở')); ?></span>
    </a>
    <a href="<?php echo e(route('site.oer.contribute')); ?>" class="flex items-center gap-1.5 <?php echo e($isContribute ? 'text-vttu-red' : 'hover:text-vttu-red'); ?> transition-colors">
        <i class="fas fa-share-alt <?php echo e($isContribute ? 'text-vttu-red' : 'text-slate-400'); ?> text-[12px]"></i>
        <span><?php echo e(__('Đóng góp tài liệu')); ?></span>
    </a>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/oer-header.blade.php ENDPATH**/ ?>