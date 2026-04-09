<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <?php if(session('success')): ?>
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e(__('Chính sách lưu thông')); ?></h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo e(__('Quản lý chính sách mượn, trả, mượn đọc và giữ lại sách')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.circulation.policies.create')); ?>" class="btn-primary">
                <i class="fas fa-plus mr-2"></i><?php echo e(__('Thêm chính sách')); ?>

            </a>
        </div>
    </div>

    <!-- Policies Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800 text-xs uppercase">
                    <tr>
                        <th class="p-4 text-left"><?php echo e(__('Tên chính sách')); ?></th>
                        <th class="p-4 text-left"><?php echo e(__('Nhóm bạn đọc')); ?></th>
                        <th class="p-4 text-center"><?php echo e(__('Mượn')); ?></th>
                        <th class="p-4 text-center"><?php echo e(__('Mượn đọc')); ?></th>
                        <th class="p-4 text-center"><?php echo e(__('Giữ lại')); ?></th>
                        <th class="p-4 text-center"><?php echo e(__('Trạng thái')); ?></th>
                        <th class="p-4 text-center"><?php echo e(__('Thao tác')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                        <td class="p-4">
                            <div class="font-medium"><?php echo e($policy->name); ?></div>
                            <?php if($policy->notes): ?>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e(Str::limit($policy->notes, 50)); ?></div>
                            <?php endif; ?>
                            <?php if(str_contains($policy->notes ?? '', '[ĐÁNH DẤU XÓA:')): ?>
                                <div class="text-xs text-red-500 mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i><?php echo e(__('Đã đánh dấu xóa')); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded text-xs">
                                <?php echo e($policy->patronGroup->name ?? 'N/A'); ?>

                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="text-xs space-y-1">
                                <div><?php echo e($policy->max_items); ?> <?php echo e(__('sách')); ?></div>
                                <div class="text-gray-500"><?php echo e($policy->max_loan_days); ?> <?php echo e(__('ngày')); ?></div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <?php if($policy->can_use_reading_room): ?>
                                <div class="text-xs space-y-1">
                                    <div class="text-green-600"><?php echo e(__('Cho phép')); ?></div>
                                    <div class="text-gray-500"><?php echo e($policy->max_reading_room_items); ?> <?php echo e(__('tài liệu')); ?></div>
                                </div>
                            <?php else: ?>
                                <span class="text-red-600 text-xs"><?php echo e(__('Không')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if($policy->can_place_hold): ?>
                                <div class="text-xs space-y-1">
                                    <div class="text-green-600"><?php echo e(__('Cho phép')); ?></div>
                                    <div class="text-gray-500"><?php echo e($policy->max_holds); ?> <?php echo e(__('giữ lại')); ?></div>
                                </div>
                            <?php else: ?>
                                <span class="text-red-600 text-xs"><?php echo e(__('Không')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if($policy->is_active): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-xs">
                                    <?php echo e(__('Kích hoạt')); ?>

                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 rounded text-xs">
                                    <?php echo e(__('Vô hiệu')); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-1">
                                <a href="<?php echo e(route('admin.circulation.policies.show', $policy)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" 
                                   title="<?php echo e(__('Xem')); ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.circulation.policies.edit', $policy)); ?>" 
                                   class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300" 
                                   title="<?php echo e(__('Sửa')); ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.circulation.policies.toggle', $policy)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300" 
                                            title="<?php echo e($policy->is_active ? __('Vô hiệu hóa') : __('Kích hoạt')); ?>">
                                        <i class="fas fa-<?php echo e($policy->is_active ? 'pause' : 'play'); ?>"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.circulation.policies.duplicate', $policy)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" 
                                            title="<?php echo e(__('Sao chép')); ?>">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <?php if(str_contains($policy->notes ?? '', '[ĐÁNH DẤU XÓA:')): ?>
                                    <!-- Force delete for marked policies -->
                                    <form action="<?php echo e(route('admin.circulation.policies.force-delete', $policy)); ?>" method="POST" 
                                          onsubmit="return confirm('<?php echo e(__('CẢNH BÁO: Xóa hoàn toàn chính sách này sẽ xóa tất cả dữ liệu liên quan! Bạn có chắc chắn?')); ?>')" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" 
                                                title="<?php echo e(__('Xóa hoàn toàn')); ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php elseif(!$policy->loanTransactions()->exists() && (!$policy->patronGroup || !$policy->patronGroup->patrons()->exists())): ?>
                                    <!-- Regular delete for unused policies -->
                                    <form action="<?php echo e(route('admin.circulation.policies.destroy', $policy)); ?>" method="POST" 
                                          onsubmit="return confirm('<?php echo e(__('Bạn có chắc chắn muốn xóa chính sách này?')); ?>')" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" 
                                                title="<?php echo e(__('Xóa')); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm"><?php echo e(__('Chưa có chính sách nào')); ?></p>
                            <a href="<?php echo e(route('admin.circulation.policies.create')); ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-2 inline-block">
                                <?php echo e(__('Thêm chính sách mới')); ?>

                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($policies->hasPages()): ?>
        <div class="p-4 border-t border-gray-200 dark:border-slate-700">
            <?php echo e($policies->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/policies/index.blade.php ENDPATH**/ ?>