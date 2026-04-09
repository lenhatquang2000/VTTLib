<!-- Book Information Component -->
<div class="bg-gray-800 dark:bg-slate-800 rounded-lg p-4">
    <h4 class="text-sm font-bold text-gray-300 mb-3 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <?php echo e(__('Thông tin Sách')); ?>

    </h4>
    <div id="<?php echo e($id ?? 'bookSearchResult'); ?>" class="space-y-2">
        <div class="text-center text-gray-500 text-sm py-8">
            <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <p><?php echo e(__('Nhập mã vạch sách để hiển thị thông tin')); ?></p>
        </div>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/components/book-info.blade.php ENDPATH**/ ?>