<?php $__env->startSection('title', __('Thông tin phân phối')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e(__('Thông tin phân phối')); ?></h1>
            <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('Xem và quản lý tất cả tài liệu trong hệ thống')); ?></p>
        </div>
        <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?php echo e(__('Quay lại')); ?>

        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Tổng số')); ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($stats['total'])); ?></p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Có sẵn')); ?></p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400"><?php echo e(number_format($stats['available'])); ?></p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Đang mượn')); ?></p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?php echo e(number_format($stats['on_loan'])); ?></p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Đang xử lý')); ?></p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400"><?php echo e(number_format($stats['in_processing'])); ?></p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Đã mất')); ?></p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400"><?php echo e(number_format($stats['lost'])); ?></p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium"><?php echo e(__('Hỏng')); ?></p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?php echo e(number_format($stats['damaged'])); ?></p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Tìm kiếm')); ?></label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="<?php echo e(__('Nhập mã vạch hoặc tên sách...')); ?>"
                       class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Trạng thái')); ?></label>
                <select name="status" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
                    <option value=""><?php echo e(__('Tất cả')); ?></option>
                    <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>><?php echo e(__('Có sẵn')); ?></option>
                    <option value="on_loan" <?php echo e(request('status') == 'on_loan' ? 'selected' : ''); ?>><?php echo e(__('Đang mượn')); ?></option>
                    <option value="in_processing" <?php echo e(request('status') == 'in_processing' ? 'selected' : ''); ?>><?php echo e(__('Đang xử lý')); ?></option>
                    <option value="lost" <?php echo e(request('status') == 'lost' ? 'selected' : ''); ?>><?php echo e(__('Đã mất')); ?></option>
                    <option value="damaged" <?php echo e(request('status') == 'damaged' ? 'selected' : ''); ?>><?php echo e(__('Hỏng')); ?></option>
                </select>
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('Chi nhánh')); ?></label>
                <select name="branch_id" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
                    <option value=""><?php echo e(__('Tất cả')); ?></option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(request('branch_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <?php echo e(__('Tìm kiếm')); ?>

            </button>
            
            <a href="<?php echo e(route('admin.circulation.distribution')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <?php echo e(__('Xóa bộ lọc')); ?>

            </a>
        </form>
    </div>

    <!-- Book Items Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Mã vạch')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Tên tài liệu')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Tác giả')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Chi nhánh')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Vị trí')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Trạng thái')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Người mượn')); ?></th>
                        <th class="px-6 py-3 text-left"><?php echo e(__('Hạn trả')); ?></th>
                        <th class="px-6 py-3 text-center"><?php echo e(__('Hành động')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $bookItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-gray-600 dark:text-gray-400"><?php echo e($item->barcode); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="font-medium text-gray-900 dark:text-white truncate" title="<?php echo e($item->bibliographicRecord->title ?? 'N/A'); ?>">
                                    <?php echo e($item->bibliographicRecord->title ?? 'N/A'); ?>

                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 dark:text-gray-400"><?php echo e($item->bibliographicRecord->author ?? 'N/A'); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 dark:text-gray-400"><?php echo e($item->branch->name ?? 'N/A'); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 dark:text-gray-400"><?php echo e($item->location ?? 'N/A'); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'available' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
                                    'on_loan' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400', 
                                    'in_processing' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400',
                                    'lost' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
                                    'damaged' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400',
                                ];
                                $statusTexts = [
                                    'available' => __('Có sẵn'),
                                    'on_loan' => __('Đang mượn'),
                                    'in_processing' => __('Đang xử lý'), 
                                    'lost' => __('Đã mất'),
                                    'damaged' => __('Hỏng'),
                                ];
                                $statusClass = $statusColors[$item->status] ?? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400';
                                $statusText = $statusTexts[$item->status] ?? ucfirst($item->status);
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($statusClass); ?>">
                                <?php echo e($statusText); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($item->currentLoan): ?>
                                <div>
                                    <p class="text-gray-900 dark:text-white text-sm font-medium"><?php echo e($item->currentLoan->patron->display_name ?? $item->currentLoan->patron->user->name); ?></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400"><?php echo e($item->currentLoan->patron->patron_code); ?></p>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-500 dark:text-gray-500">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($item->currentLoan): ?>
                                <span class="text-gray-600 dark:text-gray-400"><?php echo e($item->currentLoan->due_date->format('d/m/Y')); ?></span>
                            <?php else: ?>
                                <span class="text-gray-500 dark:text-gray-500">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="showBookHistory('<?php echo e($item->barcode); ?>', '<?php echo e($item->bibliographicRecord->title ?? 'N/A'); ?>')" 
                                        class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 text-xs" 
                                        title="<?php echo e(__("Lịch sử")); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <?php if($item->status === 'on_loan' && $item->currentLoan): ?>
                                    <button onclick="recallLoanTransaction('<?php echo e($item->barcode); ?>', '<?php echo e($item->bibliographicRecord->title ?? 'N/A'); ?>')" 
                                            class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 text-xs" 
                                            title="<?php echo e(__("Triệu hồi")); ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </button>
                                    <button onclick="declareLostLoanTransaction('<?php echo e($item->barcode); ?>', '<?php echo e($item->bibliographicRecord->title ?? 'N/A'); ?>')" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-xs" 
                                            title="<?php echo e(__("Khai báo mất")); ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium"><?php echo e(__('Không tìm thấy tài liệu nào')); ?></p>
                            <p class="text-sm mt-1"><?php echo e(__('Thử thay đổi bộ lọc hoặc tìm kiếm')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($bookItems->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <?php echo e($bookItems->links('pagination::tailwind')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Book History Modal -->
<div id="bookHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(__('Lịch sử tài liệu')); ?></h3>
            <button onclick="closeBookHistoryModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <!-- Book Info -->
            <div id="bookInfo" class="mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                <!-- Book info will be loaded here -->
            </div>
            
            <!-- Loading -->
            <div id="historyLoading" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600 dark:text-gray-400"><?php echo e(__('Đang tải lịch sử...')); ?></p>
            </div>
            
            <!-- History List -->
            <div id="historyList">
                <!-- History will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Show book history modal
function showBookHistory(barcode, title) {
    const modal = document.getElementById('bookHistoryModal');
    const loading = document.getElementById('historyLoading');
    const bookInfo = document.getElementById('bookInfo');
    const historyList = document.getElementById('historyList');
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Show loading
    loading.classList.remove('hidden');
    bookInfo.innerHTML = '';
    historyList.innerHTML = '';
    
    // Fetch book history
    fetch('<?php echo e(route("admin.circulation.book-history")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            barcode: barcode
        })
    })
    .then(response => response.json())
    .then(data => {
        loading.classList.add('hidden');
        
        if (data.success) {
            displayBookInfo(data.data.book_info);
            displayHistory(data.data.history);
        } else {
            historyList.innerHTML = `
                <div class="text-center py-8 text-red-600 dark:text-red-400">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        loading.classList.add('hidden');
        console.error('History error:', error);
        historyList.innerHTML = `
            <div class="text-center py-8 text-red-600 dark:text-red-400">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg font-medium"><?php echo e(__("Có lỗi xảy ra khi tải lịch sử")); ?></p>
            </div>
        `;
    });
}

// Close modal
function closeBookHistoryModal() {
    document.getElementById('bookHistoryModal').classList.add('hidden');
}

// Display book info
function displayBookInfo(bookInfo) {
    const bookInfoDiv = document.getElementById('bookInfo');
    
    const statusColors = {
        'available': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
        'on_loan': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
        'in_processing': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400',
        'lost': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
        'damaged': 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400',
    };
    
    const statusTexts = {
        'available': '<?php echo e(__("Có sẵn")); ?>',
        'on_loan': '<?php echo e(__("Đang mượn")); ?>',
        'in_processing': '<?php echo e(__("Đang xử lý")); ?>',
        'lost': '<?php echo e(__("Đã mất")); ?>',
        'damaged': '<?php echo e(__("Hỏng")); ?>',
    };
    
    const statusClass = statusColors[bookInfo.current_status] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400';
    const statusText = statusTexts[bookInfo.current_status] || bookInfo.current_status;
    
    bookInfoDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Mã vạch')); ?></p>
                <p class="font-mono text-sm font-medium text-gray-900 dark:text-white">${bookInfo.barcode}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Tên tài liệu')); ?></p>
                <p class="font-medium text-gray-900 dark:text-white">${bookInfo.title}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Tác giả')); ?></p>
                <p class="text-gray-900 dark:text-white">${bookInfo.author}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Chi nhánh')); ?></p>
                <p class="text-gray-900 dark:text-white">${bookInfo.branch}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Vị trí')); ?></p>
                <p class="text-gray-900 dark:text-white">${bookInfo.location}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('Trạng thái hiện tại')); ?></p>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                    ${statusText}
                </span>
            </div>
        </div>
    `;
}

// Display history
function displayHistory(history) {
    const historyList = document.getElementById('historyList');
    
    if (history.length === 0) {
        historyList.innerHTML = `
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium"><?php echo e(__("Chưa có lịch sử nào")); ?></p>
            </div>
        `;
        return;
    }
    
    const statusColors = {
        'blue': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
        'green': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
        'yellow': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400',
        'red': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
        'gray': 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400',
    };
    
    let html = `
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white"><?php echo e(__('Lịch sử giao dịch')); ?></h4>
                <span class="text-sm text-gray-600 dark:text-gray-400">${history.length} <?php echo e(__('giao dịch')); ?></span>
            </div>
            <div class="space-y-3">
    `;
    
    history.forEach(transaction => {
        const statusClass = statusColors[transaction.status_color] || statusColors['gray'];
        
        html += `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                ${transaction.status_display}
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                ${transaction.date}
                            </span>
                            ${transaction.return_date ? `<span class="text-sm text-gray-600 dark:text-gray-400">→ ${transaction.return_date}</span>` : ''}
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('Bạn đọc')); ?></p>
                                <p class="font-medium text-gray-900 dark:text-white">${transaction.patron.name}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">${transaction.patron.code} • ${transaction.patron.group}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('Thời gian')); ?></p>
                                <p class="text-gray-900 dark:text-white">
                                    <?php echo e(__('Mượn')); ?>: ${transaction.date}<br>
                                    <?php echo e(__('Hạn trả')); ?>: ${transaction.due_date}
                                    ${transaction.return_date ? '<br><?php echo e(__("Trả")); ?>: ' + transaction.return_date : ''}
                                </p>
                            </div>
                        </div>
                        
                        ${transaction.notes ? `
                            <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-900/50 rounded text-sm">
                                <p class="text-gray-600 dark:text-gray-400"><?php echo e(__("Ghi chú")); ?>:</p>
                                <p class="text-gray-900 dark:text-white">${transaction.notes}</p>
                            </div>
                        ` : ''}
                        
                        <div class="mt-3 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                            <span><?php echo e(__("Người thực hiện")); ?>: ${transaction.loaned_by}</span>
                            ${transaction.renewal_count > 0 ? `<span><?php echo e(__("Đã gia hạn")); ?>: ${transaction.renewal_count} <?php echo e(__("lần")); ?></span>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
        </div>
    `;
    
    historyList.innerHTML = html;
}

// Close modal when clicking outside
document.getElementById('bookHistoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookHistoryModal();
    }
});

// Include the same functions from loan-desk.blade.php for recall and declare lost
function recallLoanTransaction(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '<?php echo e(__("Lỗi")); ?>',
            text: '<?php echo e(__("Không thể triệu hồi tài liệu này")); ?>',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show confirmation
    Swal.fire({
        title: '<?php echo e(__("Xác nhận triệu hồi tài liệu")); ?>',
        html: `<strong>${title}</strong><br><small>${barcode}</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<?php echo e(__("Triệu hồi")); ?>',
        cancelButtonText: '<?php echo e(__("Hủy")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '<?php echo e(__("Đang xử lý")); ?>',
                text: '<?php echo e(__("Đang ghi nhận triệu hồi...")); ?>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call
            fetch('<?php echo e(route("admin.circulation.recall")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    barcode: barcode,
                    reason: 'Triệu hồi từ trang phân phối'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let message = data.message;
                    
                    // Add due date information to success message
                    if (data.data.is_overdue) {
                        message += `\n\nTài liệu đã quá hạn. Hạn trả không thay đổi: ${data.data.new_due_date}`;
                    } else {
                        message += `\n\nHạn trả đã cập nhật thành ngày triệu hồi: ${data.data.new_due_date}`;
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo e(__("Thành công")); ?>',
                        text: message,
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Refresh page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(__("Lỗi")); ?>',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Recall error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo e(__("Lỗi")); ?>',
                    text: '<?php echo e(__("Có lỗi xảy ra khi ghi nhận triệu hồi")); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

function declareLostLoanTransaction(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '<?php echo e(__("Lỗi")); ?>',
            text: '<?php echo e(__("Không thể khai báo mất tài liệu này")); ?>',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show confirmation
    Swal.fire({
        title: '<?php echo e(__("Xác nhận khai báo mất tài liệu")); ?>',
        html: `<strong>${title}</strong><br><small>${barcode}</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<?php echo e(__("Khai báo mất")); ?>',
        cancelButtonText: '<?php echo e(__("Hủy")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '<?php echo e(__("Đang xử lý")); ?>',
                text: '<?php echo e(__("Đang ghi nhận khai báo mất...")); ?>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call
            fetch('<?php echo e(route("admin.circulation.declare-lost")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    barcode: barcode,
                    notes: `Khai báo mất từ trang phân phối: ${title}`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo e(__("Thành công")); ?>',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Refresh page after successful action
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(__("Lỗi")); ?>',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Declare lost error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo e(__("Lỗi")); ?>',
                    text: '<?php echo e(__("Có lỗi xảy ra khi ghi nhận khai báo mất")); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/distribution.blade.php ENDPATH**/ ?>