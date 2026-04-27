<?php $__env->startSection('title', __('MARC Records Export & Reports')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('MARC Export & Reports')); ?></h1>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?php echo e(__('Export MARC records and generate comprehensive reports')); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.marc.book')); ?>" class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <?php echo e(__('Back to Cataloging')); ?>

            </a>
        </div>
    </div>

    <!-- Export Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <form action="<?php echo e(route('admin.marc.export.download')); ?>" method="GET" target="_blank">
            <?php echo csrf_field(); ?>
            
            <!-- Filters Section -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Export Filters')); ?></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Framework Filter -->
                    <div class="space-y-2">
                        <label for="framework_id" class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo e(__('Cataloging Framework')); ?></label>
                        <select name="framework_id" id="framework_id" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Frameworks')); ?></option>
                            <?php $__currentLoopData = $frameworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $framework): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($framework->id); ?>"><?php echo e($framework->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <!-- Document Type Filter -->
                    <div class="space-y-2">
                        <label for="document_type_id" class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo e(__('Document Type')); ?></label>
                        <select name="document_type_id" id="document_type_id" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Types')); ?></option>
                            <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo e(__('Status')); ?></label>
                        <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Statuses')); ?></option>
                            <option value="pending"><?php echo e(__('Pending')); ?></option>
                            <option value="approved"><?php echo e(__('Approved')); ?></option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo e(__('Date Range')); ?></label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date_from" id="date_from" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="<?php echo e(__('From Date')); ?>">
                            <input type="date" name="date_to" id="date_to" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="<?php echo e(__('To Date')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Export Options')); ?></h2>
                
                <!-- Include Items -->
                <div class="flex items-center space-x-2 mb-4">
                    <input type="checkbox" name="include_items" id="include_items" value="1" class="rounded border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                    <label for="include_items" class="text-sm text-gray-700 dark:text-slate-300">
                        <?php echo e(__('Include item information (barcodes, locations, statuses)')); ?>

                    </label>
                </div>

                <!-- Export Format -->
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo e(__('Export Format')); ?></label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" id="format_excel" name="format" value="excel" checked class="border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                            <label for="format_excel" class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                <?php echo e(__('Excel (.xlsx)')); ?>

                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="format_csv" name="format" value="csv" class="border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                            <label for="format_csv" class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                <?php echo e(__('CSV (.csv)')); ?>

                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="format_marc" name="format" value="marc" class="border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                            <label for="format_marc" class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                <?php echo e(__('MARC (.mrc)')); ?>

                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Actions -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <?php echo e(__('Export Records')); ?>

                </button>
                <a href="<?php echo e(route('admin.marc.import.index')); ?>" class="bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 px-4 py-2.5 rounded-lg text-sm font-semibold transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <?php echo e(__('Import Records')); ?>

                </a>
                <button type="button" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-4 py-2.5 rounded-lg text-sm font-semibold transition flex items-center" onclick="resetForm()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <?php echo e(__('Reset')); ?>

                </button>
            </div>
        </form>
    </div>

    <!-- Báo cáo Phân Hệ Biên Mục -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100"><?php echo e(__('Báo cáo Phân Hệ Biên Mục')); ?></h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span><?php echo e(__('Subsystem Reports')); ?></span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Báo cáo theo Framework -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Báo cáo theo Framework')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Thống kê theo từng framework biên mục')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('framework', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('framework', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo theo Document Type -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Báo cáo theo Loại Tài liệu')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Phân tích theo loại tài liệu')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('document_type', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('document_type', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo Năng suất Biên mục -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Năng suất Biên mục')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Hiệu suất làm việc theo người dùng')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('productivity', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('productivity', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo Chất lượng Biên mục -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Chất lượng Biên mục')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Đánh giá chất lượng bản ghi')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('quality', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('quality', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo Theo Phòng Ban -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Báo cáo Theo Phòng Ban')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Thống kê theo từng phòng ban')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('department', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('department', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo Tổng Hợp -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m0 0V8a2 2 0 012-2h8a2 2 0 012 2v8"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Báo cáo Tổng Hợp')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Tất cả báo cáo trong một file')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateSubsystemReport('comprehensive', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateSubsystemReport('comprehensive', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Báo cáo Tài liệu -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100"><?php echo e(__('Báo cáo Tài liệu')); ?></h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span><?php echo e(__('Document Reports')); ?></span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Thống kê Tài liệu -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Thống kê Tài liệu')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Số liệu thống kê tài liệu tổng quan')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('statistics', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('statistics', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh mục Tài liệu -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Danh mục Tài liệu')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Danh sách đầy đủ tài liệu')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('catalog', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('catalog', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tài liệu Theo Thể loại -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Tài liệu Theo Thể loại')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Phân loại theo chủ đề, thể loại')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('category', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('category', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tài liệu Mới -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Tài liệu Mới')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Tài liệu thêm mới trong khoảng thời gian')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('new', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('new', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tài liệu Đã Xuất bản -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Tài liệu Đã Xuất bản')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Các tài liệu đã xuất bản chính thức')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('published', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('published', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tài liệu Theo Năm Xuất bản -->
            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-slate-100 mb-1"><?php echo e(__('Theo Năm Xuất bản')); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-3"><?php echo e(__('Phân tích theo năm xuất bản')); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="generateDocumentReport('year', 'excel')" class="text-xs bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 px-2 py-1 rounded">
                                Excel
                            </button>
                            <button onclick="generateDocumentReport('year', 'pdf')" class="text-xs bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-gray-900 dark:text-slate-100"><?php echo e(App\Models\BibliographicRecord::count()); ?></div>
                    <div class="text-sm font-medium text-gray-600 dark:text-slate-400"><?php echo e(__('Total Records')); ?></div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-gray-900 dark:text-slate-100"><?php echo e(App\Models\BibliographicRecord::where('status', 'approved')->count()); ?></div>
                    <div class="text-sm font-medium text-gray-600 dark:text-slate-400"><?php echo e(__('Approved Records')); ?></div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-gray-900 dark:text-slate-100"><?php echo e(App\Models\BibliographicRecord::where('status', 'pending')->count()); ?></div>
                    <div class="text-sm font-medium text-gray-600 dark:text-slate-400"><?php echo e(__('Pending Records')); ?></div>
                </div>
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl font-bold text-gray-900 dark:text-slate-100"><?php echo e(App\Models\BookItem::count()); ?></div>
                    <div class="text-sm font-medium text-gray-600 dark:text-slate-400"><?php echo e(__('Total Items')); ?></div>
                </div>
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('framework_id').value = '';
    document.getElementById('document_type_id').value = '';
    document.getElementById('status').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('include_items').checked = false;
    document.getElementById('format_excel').checked = true;
}

function generateSubsystemReport(type, format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route('admin.marc.reports.generate')); ?>';
    form.target = '_blank';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }
    
    // Add report type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'report_type';
    typeInput.value = type;
    form.appendChild(typeInput);
    
    // Add format
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    // Add date filters
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const frameworkId = document.getElementById('framework_id').value;
    
    if (dateFrom) {
        const dateFromInput = document.createElement('input');
        dateFromInput.type = 'hidden';
        dateFromInput.name = 'date_from';
        dateFromInput.value = dateFrom;
        form.appendChild(dateFromInput);
    }
    
    if (dateTo) {
        const dateToInput = document.createElement('input');
        dateToInput.type = 'hidden';
        dateToInput.name = 'date_to';
        dateToInput.value = dateTo;
        form.appendChild(dateToInput);
    }
    
    if (frameworkId) {
        const frameworkInput = document.createElement('input');
        frameworkInput.type = 'hidden';
        frameworkInput.name = 'framework_id';
        frameworkInput.value = frameworkId;
        form.appendChild(frameworkInput);
    }
    
    if (format === 'excel') {
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    } else {
        Swal.fire({
            title: 'Đang tạo báo cáo...',
            html: 'Vui lòng đợi trong giây lát',
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    }
}

function generateDocumentReport(type, format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/topsecret/document-reports/generate';
    form.target = '_blank';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }
    
    // Add report type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'report_type';
    typeInput.value = type;
    form.appendChild(typeInput);
    
    // Add format
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    // Add date filters
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (dateFrom) {
        const dateFromInput = document.createElement('input');
        dateFromInput.type = 'hidden';
        dateFromInput.name = 'date_from';
        dateFromInput.value = dateFrom;
        form.appendChild(dateFromInput);
    }
    
    if (dateTo) {
        const dateToInput = document.createElement('input');
        dateToInput.type = 'hidden';
        dateToInput.name = 'date_to';
        dateToInput.value = dateTo;
        form.appendChild(dateToInput);
    }
    
    if (format === 'excel') {
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    } else {
        Swal.fire({
            title: 'Đang tạo báo cáo...',
            html: 'Vui lòng đợi trong giây lát',
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_books/export.blade.php ENDPATH**/ ?>