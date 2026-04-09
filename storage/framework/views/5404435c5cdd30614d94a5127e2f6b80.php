<?php $__env->startSection('content'); ?>
<div class="circulation-reports-page">
    <form action="<?php echo e(route('admin.circulation.reports.export')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="report-header bg-blue-500 text-white p-4 flex items-center justify-between shadow-md">
            <div class="flex items-center">
                <i class="fa-solid fa-chart-pie mr-3 text-xl"></i>
                <h1 class="text-xl font-bold uppercase tracking-wide"><?php echo e(__('Circulation Reports')); ?></h1>
            </div>
            <button type="submit" class="bg-white text-blue-600 px-6 py-2 rounded-lg font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition-all flex items-center shadow-lg transform active:scale-95">
                <i class="fa-solid fa-file-excel mr-2 text-lg"></i>
                <?php echo e(__('Download Excel Collection')); ?>

            </button>
        </div>

        <div class="report-content p-8 bg-white dark:bg-slate-900 min-h-[600px] shadow-inner text-slate-700 dark:text-slate-300">
            <div class="tree-menu" x-data="{ open: true }">
                <div class="tree-item flex items-center mb-4">
                    <button type="button" @click="open = !open" class="mr-2 text-slate-400 hover:text-slate-600 focus:outline-none transition-transform duration-200" :class="open ? '' : '-rotate-90'">
                        <i class="fa-solid fa-minus-square" x-show="open"></i>
                        <i class="fa-solid fa-plus-square" x-show="!open"></i>
                    </button>
                    <i class="fa-solid fa-file-invoice mr-2 text-blue-400 opacity-70"></i>
                    <span class="font-bold text-lg cursor-default"><?php echo e(__('Circulation Reports')); ?></span>
                </div>

                <div x-show="open" x-collapse class="pl-10 space-y-4 border-l border-slate-200 dark:border-slate-800 ml-2 py-2">
                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="currently_borrowed" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.currently_borrowed')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Currently Borrowed Items')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="patron_service" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.patron_service')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Patron Service Report')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="library_entries" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.library_entries')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Number of Library Entries')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="overdue" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.overdue')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Overdue Borrowed Items')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="top_patrons" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.top_patrons')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Most Borrowing Patrons')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="transaction_history" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.transaction_history')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Detailed Book Transaction History')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="website_access" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.website_access')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Website Access Statistics')); ?></span>
                        </a>
                    </div>

                    
                    <div class="tree-subitem group flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer mr-3">
                            <input type="checkbox" name="reports[]" value="never_borrowed" class="sr-only peer" checked>
                            <div class="w-5 h-5 bg-slate-200 peer-focus:outline-none rounded border border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all after:content-['✓'] after:hidden peer-checked:after:block after:text-white after:text-[10px] after:text-center after:leading-5"></div>
                        </label>
                        <a href="<?php echo e(route('admin.circulation.reports.never_borrowed')); ?>" class="flex items-center hover:bg-blue-50 dark:hover:bg-blue-900/10 p-2 rounded transition-colors group flex-1">
                            <i class="fa-solid fa-file-lines mr-2 text-blue-400 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400"><?php echo e(__('Never Borrowed Items')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .circulation-reports-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .report-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .tree-menu {
        user-select: none;
    }
    
    .tree-item i {
        color: #3b82f6;
    }
    
    .tree-subitem {
        position: relative;
    }
    
    .tree-subitem::before {
        content: '';
        position: absolute;
        left: -40px;
        top: 20px;
        width: 30px;
        height: 1px;
        background: #e2e8f0;
    }
    
    .dark .tree-subitem::before {
        background: #1e293b;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/reports/index.blade.php ENDPATH**/ ?>