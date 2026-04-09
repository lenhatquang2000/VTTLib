<?php $__env->startSection('title', __('Danh sách chờ in thẻ')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Danh sách chờ in thẻ')); ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1"><?php echo e(__('Quản lý danh sách các thẻ đang chờ in')); ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.patrons.index')); ?>" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-xl font-medium transition">
                <?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Trạng thái')); ?></label>
                <select name="status" class="px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value=""><?php echo e(__('Tất cả')); ?></option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('Chờ in')); ?></option>
                    <option value="printed" <?php echo e(request('status') == 'printed' ? 'selected' : ''); ?>><?php echo e(__('Đã in')); ?></option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('Đã hủy')); ?></option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition self-end">
                <?php echo e(__('Lọc')); ?>

            </button>
            <a href="<?php echo e(route('admin.patrons.print-queue.index')); ?>" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-lg text-sm font-semibold transition self-end">
                <?php echo e(__('Xóa bộ lọc')); ?>

            </a>
        </form>
    </div>

    <!-- Print Queue List -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Độc giả')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Mã độc giả')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Ưu tiên')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Trạng thái')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Ngày thêm')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Người thêm')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Hành động')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $printQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mr-3">
                                        <?php if($item->patron && $item->patron->profile_image): ?>
                                            <img src="<?php echo e(asset('storage/' . $item->patron->profile_image)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-slate-100"><?php echo e($item->patron->display_name ?? 'N/A'); ?></div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e($item->patron->user->email ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-slate-100 font-mono"><?php echo e($item->patron->patron_code ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($item->priority >= 5 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ($item->priority >= 3 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200')); ?>">
                                    <?php echo e($item->priority); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    <?php echo e($item->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : ''); ?>

                                    <?php echo e($item->status == 'printed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ''); ?>

                                    <?php echo e($item->status == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ''); ?>">
                                    <?php echo e($item->status_label); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php echo e($item->created_at->format('d/m/Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php echo e($item->addedBy->name ?? 'System'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <?php if($item->status == 'pending'): ?>
                                        <form action="<?php echo e(route('admin.patrons.print-queue.mark-printed', $item->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="<?php echo e(__('Đánh dấu đã in')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($item->status == 'pending'): ?>
                                        <form action="<?php echo e(route('admin.patrons.print-queue.destroy', $item->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="<?php echo e(__('Hủy')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('Không có mục nào trong danh sách chờ in')); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if($printQueue->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($printQueue->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/print-queue/index.blade.php ENDPATH**/ ?>