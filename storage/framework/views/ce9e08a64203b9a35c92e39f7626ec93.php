<?php $__env->startSection('title', __('Nhật Ký Hệ Thống Độc Giả')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Nhật Ký Hệ Thống Độc Giả')); ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1"><?php echo e(__('Xem lịch sử hệ thống liên quan đến độc giả')); ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.patrons.index')); ?>" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-md hover:bg-indigo-500">
                <?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Loại log')); ?></label>
                    <select name="log_type" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value=""><?php echo e(__('Tất cả')); ?></option>
                        <option value="patron_locked" <?php echo e(request('log_type') == 'patron_locked' ? 'selected' : ''); ?>><?php echo e(__('Khóa độc giả')); ?></option>
                        <option value="patron_unlocked" <?php echo e(request('log_type') == 'patron_unlocked' ? 'selected' : ''); ?>><?php echo e(__('Mở khóa độc giả')); ?></option>
                        <option value="patron_transaction" <?php echo e(request('log_type') == 'patron_transaction' ? 'selected' : ''); ?>><?php echo e(__('Giao dịch tài chính')); ?></option>
                        <option value="patron_added_to_print_queue" <?php echo e(request('log_type') == 'patron_added_to_print_queue' ? 'selected' : ''); ?>><?php echo e(__('Thêm vào danh sách chờ in')); ?></option>
                        <option value="patron_removed_from_print_queue" <?php echo e(request('log_type') == 'patron_removed_from_print_queue' ? 'selected' : ''); ?>><?php echo e(__('Xóa khỏi danh sách chờ in')); ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Người thực hiện')); ?></label>
                    <select name="user_id" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value=""><?php echo e(__('Tất cả')); ?></option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Từ ngày')); ?></label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Đến ngày')); ?></label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('Tìm kiếm')); ?>

                </button>
                <a href="<?php echo e(route('admin.patrons.system-logs')); ?>" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('Xóa bộ lọc')); ?>

                </a>
            </div>
        </form>
    </div>

    <!-- System Logs List -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Thời gian')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Loại hoạt động')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Người thực hiện')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Đối tượng')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Chi tiết')); ?></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('IP Address')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php echo e($log->created_at->format('d/m/Y H:i:s')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <?php echo e($log->action); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php echo e($log->user->name ?? 'System'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php if($log->model_type && $log->model_id): ?>
                                    <?php echo e(class_basename($log->model_type)); ?> #<?php echo e($log->model_id); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-slate-100">
                                <?php if($log->details): ?>
                                    <div class="space-y-1">
                                        <?php if(is_array($log->details)): ?>
                                            <?php $__currentLoopData = $log->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($key === 'changes' && is_array($value)): ?>
                                                    <div class="border-l-2 border-blue-400 pl-2 mb-2">
                                                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-1">Các thay đổi:</p>
                                                        <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                                                <span class="font-medium"><?php echo e($field); ?>:</span>
                                                                <span class="line-through text-red-500"><?php echo e($change['old'] ?? 'N/A'); ?></span>
                                                                <span class="mx-1">→</span>
                                                                <span class="text-green-600"><?php echo e($change['new'] ?? 'N/A'); ?></span>
                                                            </p>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php elseif(is_string($value)): ?>
                                                    <p class="text-xs"><span class="font-medium"><?php echo e($key); ?>:</span> <?php echo e($value); ?></p>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <p class="text-xs"><?php echo e($log->details); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                <?php echo e($log->ip_address ?? '-'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('Không tìm thấy nhật ký nào')); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if($logs->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($logs->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/system-logs.blade.php ENDPATH**/ ?>