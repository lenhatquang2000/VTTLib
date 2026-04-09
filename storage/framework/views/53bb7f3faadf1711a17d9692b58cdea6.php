<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e($policy->name); ?></h1>
            <p class="text-sm text-gray-400 mt-1">
                <?php echo e(__('Nhóm bạn đọc')); ?>: <?php echo e($policy->patronGroup->name ?? 'N/A'); ?>

                <?php if($policy->notes): ?>
                <br><?php echo e(__('Ghi chú')); ?>: <?php echo e($policy->notes); ?>

                <?php endif; ?>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.circulation.policies.edit', $policy)); ?>" class="btn-secondary">
                <i class="fas fa-edit mr-2"></i><?php echo e(__('Sửa')); ?>

            </a>
            <a href="<?php echo e(route('admin.circulation.policies.index')); ?>" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i><?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-4">
        <?php if($policy->is_active): ?>
            <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-sm font-medium">
                <i class="fas fa-check-circle mr-2"></i><?php echo e(__('Kích hoạt')); ?>

            </span>
        <?php else: ?>
            <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 rounded text-sm font-medium">
                <i class="fas fa-pause-circle mr-2"></i><?php echo e(__('Vô hiệu')); ?>

            </span>
        <?php endif; ?>
    </div>

    <!-- Policy Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Loan Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-book-reader mr-2 text-blue-500"></i>
                <?php echo e(__('Cài đặt mượn sách')); ?>

            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Số ngày mượn tối đa')); ?></span>
                    <span class="text-sm font-bold text-blue-600"><?php echo e($policySummary['loan']['max_days']); ?> <?php echo e(__('ngày')); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Số sách mượn tối đa')); ?></span>
                    <span class="text-sm font-bold text-blue-600"><?php echo e($policySummary['loan']['max_items']); ?> <?php echo e(__('sách')); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Số lần gia hạn tối đa')); ?></span>
                    <span class="text-sm font-bold text-blue-600"><?php echo e($policySummary['loan']['max_renewals']); ?> <?php echo e(__('lần')); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium"><?php echo e(__('Ngày gia hạn mỗi lần')); ?></span>
                    <span class="text-sm font-bold text-blue-600"><?php echo e($policySummary['loan']['renewal_days']); ?> <?php echo e(__('ngày')); ?></span>
                </div>
            </div>
        </div>

        <!-- Fine Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-coins mr-2 text-yellow-500"></i>
                <?php echo e(__('Cài đặt phạt')); ?>

            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Phí phạt/ngày')); ?></span>
                    <span class="text-sm font-bold text-yellow-600"><?php echo e(number_format($policySummary['fines']['per_day'])); ?> VND</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Phạt tối đa')); ?></span>
                    <span class="text-sm font-bold text-yellow-600"><?php echo e(number_format($policySummary['fines']['max_fine'])); ?> VND</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium"><?php echo e(__('Ngày ân hạn')); ?></span>
                    <span class="text-sm font-bold text-yellow-600"><?php echo e($policySummary['fines']['grace_period']); ?> <?php echo e(__('ngày')); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium"><?php echo e(__('Nợ tối đa được phép')); ?></span>
                    <span class="text-sm font-bold text-yellow-600"><?php echo e(number_format($policySummary['fines']['max_outstanding'])); ?> VND</span>
                </div>
            </div>
        </div>

        <!-- Reading Room Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-chair mr-2 text-purple-500"></i>
                <?php echo e(__('Cài đặt mượn đọc tại chỗ')); ?>

            </h3>
            
            <?php if($policySummary['reading_room']['allowed']): ?>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Trạng thái')); ?></span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-medium">
                            <?php echo e(__('Cho phép')); ?>

                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Số tài liệu tối đa')); ?></span>
                        <span class="text-sm font-bold text-purple-600"><?php echo e($policySummary['reading_room']['max_items']); ?> <?php echo e(__('tài liệu')); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Thời gian mượn tối đa')); ?></span>
                        <span class="text-sm font-bold text-purple-600"><?php echo e($policySummary['reading_room']['max_hours']); ?> <?php echo e(__('giờ')); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Giờ trả mặc định')); ?></span>
                        <span class="text-sm font-bold text-purple-600"><?php echo e($policySummary['reading_room']['due_time']); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium"><?php echo e(__('Phí phạt/giờ')); ?></span>
                        <span class="text-sm font-bold text-purple-600"><?php echo e(number_format($policySummary['reading_room']['fine_per_hour'])); ?> VND</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-times-circle text-4xl text-red-400 mb-3"></i>
                    <p class="text-sm text-gray-500"><?php echo e(__('Không cho phép sử dụng mượn đọc tại chỗ')); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-hand-holding mr-2 text-green-500"></i>
                <?php echo e(__('Cài đặt giữ lại sách')); ?>

            </h3>
            
            <?php if($policySummary['holds']['allowed']): ?>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Trạng thái')); ?></span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-medium">
                            <?php echo e(__('Cho phép')); ?>

                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Số giữ lại tối đa')); ?></span>
                        <span class="text-sm font-bold text-green-600"><?php echo e($policySummary['holds']['max_holds']); ?> <?php echo e(__('giữ lại')); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Ngày hết hạn')); ?></span>
                        <span class="text-sm font-bold text-green-600"><?php echo e($policySummary['holds']['expiry_days']); ?> <?php echo e(__('ngày')); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Ngày thông báo')); ?></span>
                        <span class="text-sm font-bold text-green-600"><?php echo e($policySummary['holds']['notification_days']); ?> <?php echo e(__('ngày')); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium"><?php echo e(__('Phí hủy giữ lại')); ?></span>
                        <span class="text-sm font-bold text-green-600"><?php echo e(number_format($policySummary['holds']['cancellation_fee'])); ?> VND</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium"><?php echo e(__('Gia hạn')); ?></span>
                        <span class="text-sm font-bold text-green-600">
                            <?php if($policySummary['holds']['can_renew']): ?>
                                <?php echo e(__('Cho phép')); ?> (<?php echo e($policySummary['holds']['max_renewals']); ?> <?php echo e(__('lần')); ?>)
                            <?php else: ?>
                                <?php echo e(__('Không cho phép')); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-times-circle text-4xl text-red-400 mb-3"></i>
                    <p class="text-sm text-gray-500"><?php echo e(__('Không cho phép đặt giữ lại sách')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-2">
        <form action="<?php echo e(route('admin.circulation.policies.toggle', $policy)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-secondary">
                <i class="fas fa-<?php echo e($policy->is_active ? 'pause' : 'play'); ?> mr-2"></i>
                <?php echo e($policy->is_active ? __('Vô hiệu hóa') : __('Kích hoạt')); ?>

            </button>
        </form>
        
        <form action="<?php echo e(route('admin.circulation.policies.duplicate', $policy)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-secondary">
                <i class="fas fa-copy mr-2"></i><?php echo e(__('Sao chép')); ?>

            </button>
        </form>
        
        <a href="<?php echo e(route('admin.circulation.policies.edit', $policy)); ?>" class="btn-primary">
            <i class="fas fa-edit mr-2"></i><?php echo e(__('Sửa chính sách')); ?>

        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/policies/show.blade.php ENDPATH**/ ?>