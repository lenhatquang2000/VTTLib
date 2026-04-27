<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('MARC Records Import')); ?></h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?php echo e(__('Import từ file Excel hoặc file MARC (.mrc, .txt)')); ?></p>
        </div>
        <a href="<?php echo e(route('admin.marc.book')); ?>"
            class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?php echo e(__('Back to Cataloging')); ?>

        </a>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <button type="button" id="tabExcel" onclick="switchTab('excel')"
                class="flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/10 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <?php echo e(__('Import từ Excel')); ?>

            </button>
            <button type="button" id="tabMarc" onclick="switchTab('marc')"
                class="flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 border-transparent text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
                <?php echo e(__('Import từ file MARC (.mrc / .txt)')); ?>

            </button>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 1: EXCEL IMPORT (existing) -->
    <!-- ============================================================ -->
    <div id="panelExcel">

    <!-- Import Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <form id="importForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <!-- Framework Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            <?php echo e(__('Cataloging Framework')); ?> <span class="text-red-500">*</span>
                        </label>
                        <select name="framework_id" id="framework_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('Select Framework')); ?></option>
                            <?php $__currentLoopData = $frameworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $framework): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($framework->id); ?>"><?php echo e($framework->name); ?> (<?php echo e($framework->code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            <?php echo e(__('Action Type')); ?> <span class="text-red-500">*</span>
                        </label>
                        <select name="action_type" id="action_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="create"><?php echo e(__('Create New Records')); ?></option>
                            <option value="update"><?php echo e(__('Update Existing Records')); ?></option>
                        </select>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                        <?php echo e(__('Excel File')); ?> <span class="text-red-500">*</span>
                    </label>
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-8 text-center hover:border-indigo-500 transition-all duration-200 bg-gray-50/50 dark:bg-slate-800/50">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required
                            class="hidden">
                        <label for="excel_file" class="cursor-pointer block">
                            <div id="uploadPlaceholder" class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <span class="text-base font-medium text-gray-700 dark:text-slate-200"><?php echo e(__('Click to upload or drag and drop')); ?></span>
                                <span class="text-sm text-gray-500 dark:text-slate-500 mt-1"><?php echo e(__('XLSX, XLS, CSV (Max 10MB)')); ?></span>
                            </div>

                            <div id="fileSelectedState" class="hidden flex flex-col items-center">
                                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-4 animate-bounce">
                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span id="selectedFileName" class="text-base font-bold text-emerald-600 dark:text-emerald-400"></span>
                                <span id="selectedFileSize" class="text-sm text-gray-500 dark:text-slate-500 mt-1"></span>
                                <button type="button" onclick="document.getElementById('resetBtn').click()" class="mt-4 text-xs text-red-500 hover:text-red-700 underline"><?php echo e(__('Remove file')); ?></button>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Template Download -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200"><?php echo e(__('Download Template')); ?></h4>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1"><?php echo e(__('Download the Excel template to ensure proper data format')); ?></p>
                            <button type="button" id="downloadTemplate" disabled
                                class="mt-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <?php echo e(__('Download Template')); ?>

                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit" id="uploadBtn" disabled
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed shadow-lg shadow-indigo-200 dark:shadow-none">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <?php echo e(__('Upload & Validate')); ?>

                    </button>
                    <button type="button" id="resetBtn"
                        class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                        <?php echo e(__('Reset')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Validation Results -->
    <div id="validationResults" class="hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Validation Results')); ?></h3>

            <!-- Summary -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-slate-100" id="totalRows">0</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e(__('Total Rows')); ?></div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="validRows">0</div>
                    <div class="text-xs text-green-600 dark:text-green-400"><?php echo e(__('Valid Rows')); ?></div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="invalidRows">0</div>
                    <div class="text-xs text-red-600 dark:text-red-400"><?php echo e(__('Invalid Rows')); ?></div>
                </div>
            </div>

            <!-- Preview -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?php echo e(__('Preview (First 5 valid records)')); ?></h4>
                <div id="previewContainer" class="grid grid-cols-1 gap-4">
                    <!-- Raw MARC records will be injected here -->
                </div>
            </div>

            <!-- Errors -->
            <div id="errorsSection" class="hidden mb-6">
                <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3"><?php echo e(__('Validation Errors')); ?></h4>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-h-60 overflow-y-auto">
                    <div id="errorsList" class="space-y-2 text-sm">
                    </div>
                </div>

                <!-- Suggested Action: Create Framework -->
                <div id="createFrameworkSection" class="hidden mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-bold text-indigo-900 dark:text-indigo-100"><?php echo e(__('Dữ liệu không khớp với khung đã chọn?')); ?></h5>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 mt-1">
                                <?php echo e(__('Có vẻ như file của bạn có cấu trúc cột khác với Khung biên mục hiện tại. Bạn có muốn hệ thống tự động tạo một Khung biên mục mới dựa trên các tiêu đề cột trong file này không?')); ?>

                            </p>
                            <button type="button" id="createFrameworkBtn" class="mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                <?php echo e(__('Tạo Khung biên mục mới từ file này')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button type="button" id="processBtn" disabled
                    class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php echo e(__('Process Import')); ?>

                </button>
                <button type="button" id="cancelBtn"
                    class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('Cancel')); ?>

                </button>
            </div>
        </div>
    </div>

    <!-- Processing Results -->
    <div id="processingResults" class="hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Import Results')); ?></h3>
            <div id="processingResultsContent">
            </div>
        </div>
    </div>

    </div><!-- /panelExcel -->

    <!-- ============================================================ -->
    <!-- TAB 2: MARC FILE IMPORT (.mrc / .txt) -->
    <!-- ============================================================ -->
    <div id="panelMarc" class="hidden space-y-6">

        <!-- MARC Upload Form -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
            <div class="p-6">
                <form id="marcImportForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                                <?php echo e(__('Loại thao tác')); ?> <span class="text-red-500">*</span>
                            </label>
                            <select name="action_type" id="marc_action_type" required
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="create"><?php echo e(__('Tạo bản ghi mới')); ?></option>
                                <option value="update"><?php echo e(__('Cập nhật bản ghi đã có')); ?></option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                                <?php echo e(__('Khung biên mục (tuỳ chọn)')); ?>

                            </label>
                            <select name="framework_id" id="marc_framework_id"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value=""><?php echo e(__('-- Tự động trích xuất từ file --')); ?></option>
                                <?php $__currentLoopData = $frameworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $framework): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($framework->id); ?>"><?php echo e($framework->name); ?> (<?php echo e($framework->code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1"><?php echo e(__('Để trống nếu muốn tạo khung mới từ file MARC')); ?></p>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            <?php echo e(__('File MARC')); ?> <span class="text-red-500">*</span>
                        </label>
                        <div id="marcDropZone" class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-8 text-center hover:border-indigo-500 transition-all duration-200 bg-gray-50/50 dark:bg-slate-800/50">
                            <input type="file" name="marc_file" id="marc_file" accept=".mrc,.txt" required class="hidden">
                            <label for="marc_file" class="cursor-pointer block">
                                <div id="marcUploadPlaceholder" class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-base font-medium text-gray-700 dark:text-slate-200"><?php echo e(__('Click để chọn file hoặc kéo thả')); ?></span>
                                    <span class="text-sm text-gray-500 dark:text-slate-500 mt-1"><?php echo e(__('Hỗ trợ: .mrc (ISO 2709), .txt (MARC text) - Tối đa 10MB')); ?></span>
                                </div>
                                <div id="marcFileSelectedState" class="hidden flex flex-col items-center">
                                    <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-4 animate-bounce">
                                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span id="marcSelectedFileName" class="text-base font-bold text-emerald-600 dark:text-emerald-400"></span>
                                    <span id="marcSelectedFileSize" class="text-sm text-gray-500 dark:text-slate-500 mt-1"></span>
                                    <button type="button" onclick="resetMarcFile()" class="mt-4 text-xs text-red-500 hover:text-red-700 underline"><?php echo e(__('Xoá file')); ?></button>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200"><?php echo e(__('Hướng dẫn')); ?></h4>
                                <ul class="text-xs text-blue-600 dark:text-blue-400 mt-1 space-y-1 list-disc list-inside">
                                    <li><?php echo e(__('Hệ thống sẽ tự động phân tích cấu trúc MARC từ file')); ?></li>
                                    <li><?php echo e(__('Sau khi upload, bạn có thể xem trước dữ liệu và khung biên mục được trích xuất')); ?></li>
                                    <li><?php echo e(__('Bạn có thể lưu khung biên mục mới hoặc chọn khung đã có để import')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3">
                        <button type="submit" id="marcUploadBtn" disabled
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed shadow-lg shadow-indigo-200 dark:shadow-none">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <?php echo e(__('Upload & Phân tích')); ?>

                        </button>
                        <button type="button" id="marcResetBtn"
                            class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                            <?php echo e(__('Reset')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MARC Validation Results -->
        <div id="marcValidationResults" class="hidden space-y-6">
            <!-- Summary -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Kết quả phân tích')); ?></h3>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800 dark:text-slate-100" id="marcTotalRecords">0</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e(__('Tổng bản ghi')); ?></div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="marcValidRecords">0</div>
                            <div class="text-xs text-green-600 dark:text-green-400"><?php echo e(__('Hợp lệ')); ?></div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="marcInvalidRecords">0</div>
                            <div class="text-xs text-red-600 dark:text-red-400"><?php echo e(__('Lỗi')); ?></div>
                        </div>
                    </div>

                    <!-- Preview Records -->
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?php echo e(__('Xem trước bản ghi (tối đa 5)')); ?></h4>
                    <div id="marcPreviewContainer" class="space-y-3 mb-6"></div>

                    <!-- Errors -->
                    <div id="marcErrorsSection" class="hidden mb-6">
                        <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3"><?php echo e(__('Bản ghi lỗi')); ?></h4>
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-h-40 overflow-y-auto">
                            <div id="marcErrorsList" class="space-y-2 text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Extracted Framework -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100"><?php echo e(__('Khung biên mục trích xuất')); ?></h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1"><?php echo e(__('Các trường MARC được phát hiện trong file. Bạn có muốn lưu khung này không?')); ?></p>
                        </div>
                        <button type="button" id="saveFrameworkBtn"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <?php echo e(__('Lưu khung biên mục này')); ?>

                        </button>
                    </div>

                    <div id="marcFrameworkTable" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400 w-20"><?php echo e(__('Tag')); ?></th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Tên trường')); ?></th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400 w-40"><?php echo e(__('Trường con')); ?></th>
                                </tr>
                            </thead>
                            <tbody id="marcFrameworkBody" class="divide-y divide-gray-200 dark:divide-slate-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Process Import -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Xác nhận Import')); ?></h3>

                    <div id="marcFrameworkSelection" class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            <?php echo e(__('Chọn khung biên mục để import')); ?> <span class="text-red-500">*</span>
                        </label>
                        <select id="marcProcessFramework"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('-- Upload file để xem khung phù hợp --')); ?></option>
                        </select>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1"><?php echo e(__('Dropdown sẽ tự cập nhật sau khi upload file, hiển thị khung phù hợp với dấu ✅')); ?></p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" id="marcProcessBtn" disabled
                            class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php echo e(__('Tiến hành Import')); ?>

                        </button>
                        <button type="button" id="marcCancelBtn"
                            class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                            <?php echo e(__('Huỷ')); ?>

                        </button>
                    </div>
                </div>
            </div>

            <!-- MARC Processing Results -->
            <div id="marcProcessingResults" class="hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Kết quả Import')); ?></h3>
                    <div id="marcProcessingResultsContent"></div>
                </div>
            </div>
        </div>

    </div><!-- /panelMarc -->

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let validImportData = [];
        const form = document.getElementById('importForm');
        const fileInput = document.getElementById('excel_file');
        const frameworkSelect = document.getElementById('framework_id');
        const actionTypeSelect = document.getElementById('action_type');
        const uploadBtn = document.getElementById('uploadBtn');
        const downloadTemplateBtn = document.getElementById('downloadTemplate');
        const resetBtn = document.getElementById('resetBtn');

        const dropZone = document.getElementById('dropZone');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const fileSelectedState = document.getElementById('fileSelectedState');
        const selectedFileName = document.getElementById('selectedFileName');
        const selectedFileSize = document.getElementById('selectedFileSize');

        const validationResults = document.getElementById('validationResults');
        const processingResults = document.getElementById('processingResults');

        // Enable/disable buttons based on form state
        function updateButtonStates() {
            const hasFile = fileInput.files.length > 0;
            const hasFramework = frameworkSelect.value !== '';
            uploadBtn.disabled = !(hasFile && hasFramework);
            downloadTemplateBtn.disabled = !hasFramework;

            if (hasFile) {
                const file = fileInput.files[0];
                selectedFileName.textContent = file.name;
                selectedFileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

                uploadPlaceholder.classList.add('hidden');
                fileSelectedState.classList.remove('hidden');
                dropZone.classList.add('border-emerald-500', 'bg-emerald-50/20');
                dropZone.classList.remove('border-gray-300', 'bg-gray-50/50');
            } else {
                uploadPlaceholder.classList.remove('hidden');
                fileSelectedState.classList.add('hidden');
                dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/20');
                dropZone.classList.add('border-gray-300', 'bg-gray-50/50');
            }
        }

        fileInput.addEventListener('change', updateButtonStates);
        frameworkSelect.addEventListener('change', updateButtonStates);

        downloadTemplateBtn.addEventListener('click', function() {
            const frameworkId = frameworkSelect.value;
            if (frameworkId) {
                window.location.href = `<?php echo e(route('admin.marc.import.template')); ?>?framework_id=${frameworkId}`;
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <?php echo e(__('Validating...')); ?>

        `;

            try {
                const response = await fetch('<?php echo e(route('admin.marc.import.upload')); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const result = await response.json();
                    if (result.success) {
                        showValidationResults(result.data);
                        Swal.fire({
                            icon: 'success',
                            title: "<?php echo e(__('File Uploaded')); ?>",
                            text: "<?php echo e(__('File has been uploaded and validated successfully.')); ?>",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "<?php echo e(__('Error')); ?>",
                            text: result.message
                        });
                    }
                } else {
                    // It's not JSON (possibly an error page or dd() output)
                    const html = await response.text();
                    const errorWindow = window.open('', '_blank');
                    errorWindow.document.write(html);
                    errorWindow.document.close();
                    Swal.fire({
                        icon: 'warning',
                        title: "<?php echo e(__('Debug Output')); ?>",
                        text: "<?php echo e(__('The server returned an HTML response. It has been opened in a new tab for debugging.')); ?>"
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "<?php echo e(__('Error')); ?>",
                    text: error.message
                });
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <?php echo e(__('Upload & Validate')); ?>

            `;
            }
        });

        function showValidationResults(data) {
            document.getElementById('totalRows').textContent = data.total_rows;
            document.getElementById('validRows').textContent = data.valid_rows;
            document.getElementById('invalidRows').textContent = data.invalid_rows;

            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = '';

            data.preview.forEach(record => {
                const card = document.createElement('div');
                card.className = "bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-lg p-4 font-mono text-sm overflow-x-auto";
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#<?php echo e(__('Row')); ?> ${record.row_index}</span>
                        <span class="text-xs text-gray-500">${record.title}</span>
                    </div>
                    <pre class="text-gray-800 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">${record.raw_marc}</pre>
                `;
                previewContainer.appendChild(card);
            });
            const errorsSection = document.getElementById('errorsSection');
            const errorsList = document.getElementById('errorsList');
            if (data.errors && data.errors.length > 0) {
                errorsSection.classList.remove('hidden');
                document.getElementById('createFrameworkSection').classList.remove('hidden');
                errorsList.innerHTML = '';
                data.errors.forEach(error => {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'flex items-start space-x-2';
                    errorDiv.innerHTML = `<span class="text-red-600 dark:text-red-400 font-medium"><?php echo e(__('Row')); ?> ${error.row_index}:</span> <span class="text-gray-600 dark:text-slate-400">${error.errors.join(', ')}</span>`;
                    errorsList.appendChild(errorDiv);
                });
            } else {
                errorsSection.classList.add('hidden');
                document.getElementById('createFrameworkSection').classList.add('hidden');
            }
            validImportData = data.valid_data || [];
            document.getElementById('processBtn').disabled = data.valid_rows === 0;
            validationResults.classList.remove('hidden');
            validationResults.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function showProcessingResults(data) {
            const container = document.getElementById('processingResultsContent');
            const total = data.length;
            const successCount = data.filter(r => r.success).length;
            const failCount = total - successCount;

            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-lg text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-slate-100">${total}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e(__('Total Processed')); ?></div>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">${successCount}</div>
                        <div class="text-xs text-green-600 dark:text-green-400"><?php echo e(__('Successful')); ?></div>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">${failCount}</div>
                        <div class="text-xs text-red-600 dark:text-red-400"><?php echo e(__('Failed')); ?></div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Row')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Title')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Status')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Details')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            ${data.map(result => `
                                <tr>
                                    <td class="px-4 py-2">${result.row_index}</td>
                                    <td class="px-4 py-2">${result.title || '-'}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${result.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${result.success ? '<?php echo e(__("Success")); ?>' : '<?php echo e(__("Failed")); ?>'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-xs ${result.success ? 'text-gray-500' : 'text-red-500'}">
                                        ${result.success ? 'ID: ' + result.record_id : result.error}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="location.reload()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                        <?php echo e(__('Done')); ?>

                    </button>
                </div>
            `;

            validationResults.classList.add('hidden');
            processingResults.classList.remove('hidden');
            processingResults.scrollIntoView({
                behavior: 'smooth'
            });
        }

        document.getElementById('createFrameworkBtn').addEventListener('click', async function() {
            const {
                value: formValues
            } = await Swal.fire({
                title: '<?php echo e(__("Tạo Khung biên mục mới")); ?>',
                html: '<input id="swal-input1" class="swal2-input" placeholder="<?php echo e(__("Tên khung (VD: Tài liệu số)")); ?>">' +
                    '<input id="swal-input2" class="swal2-input" placeholder="<?php echo e(__("Mã khung (VD: DIGI)")); ?>">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__("Tạo ngay")); ?>',
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value
                    ]
                }
            });

            if (formValues && formValues[0] && formValues[1]) {
                const formData = new FormData(form);
                formData.append('framework_name', formValues[0]);
                formData.append('framework_code', formValues[1]);

                Swal.fire({
                    title: '<?php echo e(__("Đang khởi tạo...")); ?>',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch('<?php echo e(route('admin.marc.import.create-framework')); ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo e(__("Thành công")); ?>',
                            text: result.message
                        }).then(() => {
                            // Reload or suggest selecting the new framework
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '<?php echo e(__("Lỗi")); ?>',
                            text: result.message
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(__("Lỗi kết nối")); ?>',
                        text: error.message
                    });
                }
            }
        });

        document.getElementById('processBtn').addEventListener('click', async function() {
            this.disabled = true;
            this.innerHTML = `...`;
            try {
                const response = await fetch('<?php echo e(route('admin.marc.import.process')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        framework_id: frameworkSelect.value,
                        action_type: actionTypeSelect.value,
                        validated_data: validImportData
                    })
                });
                const result = await response.json();
                if (result.success) {
                    showProcessingResults(result.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: "<?php echo e(__('Error')); ?>",
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "<?php echo e(__('Error')); ?>",
                    text: error.message
                });
            } finally {
                this.disabled = false;
                this.innerHTML = `<?php echo e(__('Process Import')); ?>`;
            }
        });

        resetBtn.addEventListener('click', function() {
            form.reset();
            updateButtonStates();
            validationResults.classList.add('hidden');
            processingResults.classList.add('hidden');
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            validationResults.classList.add('hidden');
        });
    });

    // ========================================================================
    // TAB SWITCHING
    // ========================================================================
    function switchTab(tab) {
        const tabExcel = document.getElementById('tabExcel');
        const tabMarc = document.getElementById('tabMarc');
        const panelExcel = document.getElementById('panelExcel');
        const panelMarc = document.getElementById('panelMarc');

        const activeClass = 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/10';
        const inactiveClass = 'border-transparent text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300';

        if (tab === 'excel') {
            panelExcel.classList.remove('hidden');
            panelMarc.classList.add('hidden');
            tabExcel.className = `flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 ${activeClass} transition`;
            tabMarc.className = `flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 ${inactiveClass} transition`;
        } else {
            panelExcel.classList.add('hidden');
            panelMarc.classList.remove('hidden');
            tabMarc.className = `flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 ${activeClass} transition`;
            tabExcel.className = `flex-1 px-6 py-4 text-sm font-bold text-center border-b-2 ${inactiveClass} transition`;
        }
    }

    // ========================================================================
    // MARC FILE IMPORT TAB LOGIC
    // ========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const marcForm = document.getElementById('marcImportForm');
        const marcFileInput = document.getElementById('marc_file');
        const marcUploadBtn = document.getElementById('marcUploadBtn');
        const marcDropZone = document.getElementById('marcDropZone');
        const marcUploadPlaceholder = document.getElementById('marcUploadPlaceholder');
        const marcFileSelectedState = document.getElementById('marcFileSelectedState');
        const marcSelectedFileName = document.getElementById('marcSelectedFileName');
        const marcSelectedFileSize = document.getElementById('marcSelectedFileSize');

        let extractedFrameworkData = [];

        // File selection UI
        function updateMarcFileState() {
            const hasFile = marcFileInput.files.length > 0;
            marcUploadBtn.disabled = !hasFile;

            if (hasFile) {
                const file = marcFileInput.files[0];
                marcSelectedFileName.textContent = file.name;
                marcSelectedFileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                marcUploadPlaceholder.classList.add('hidden');
                marcFileSelectedState.classList.remove('hidden');
                marcDropZone.classList.add('border-emerald-500', 'bg-emerald-50/20');
                marcDropZone.classList.remove('border-gray-300', 'bg-gray-50/50');
            } else {
                marcUploadPlaceholder.classList.remove('hidden');
                marcFileSelectedState.classList.add('hidden');
                marcDropZone.classList.remove('border-emerald-500', 'bg-emerald-50/20');
                marcDropZone.classList.add('border-gray-300', 'bg-gray-50/50');
            }
        }

        marcFileInput.addEventListener('change', updateMarcFileState);

        // Drag & drop
        marcDropZone.addEventListener('dragover', e => { e.preventDefault(); marcDropZone.classList.add('border-indigo-500', 'bg-indigo-50/20'); });
        marcDropZone.addEventListener('dragleave', e => { e.preventDefault(); marcDropZone.classList.remove('border-indigo-500', 'bg-indigo-50/20'); });
        marcDropZone.addEventListener('drop', e => {
            e.preventDefault();
            marcDropZone.classList.remove('border-indigo-500', 'bg-indigo-50/20');
            if (e.dataTransfer.files.length > 0) {
                marcFileInput.files = e.dataTransfer.files;
                updateMarcFileState();
            }
        });

        // Upload & parse
        marcForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            marcUploadBtn.disabled = true;
            marcUploadBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <?php echo e(__('Đang phân tích...')); ?>

            `;

            try {
                const formData = new FormData(marcForm);
                const response = await fetch('<?php echo e(route("admin.marc.import.upload-marc")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                if (result.success) {
                    showMarcResults(result.data);
                    Swal.fire({ icon: 'success', title: '<?php echo e(__("Phân tích thành công")); ?>', text: result.message, timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi")); ?>', text: result.message });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi kết nối")); ?>', text: error.message });
            } finally {
                marcUploadBtn.disabled = false;
                marcUploadBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <?php echo e(__('Upload & Phân tích')); ?>

                `;
            }
        });

        function showMarcResults(data) {
            document.getElementById('marcTotalRecords').textContent = data.total_records;
            document.getElementById('marcValidRecords').textContent = data.valid_records;
            document.getElementById('marcInvalidRecords').textContent = data.invalid_records;

            // Preview records
            const previewContainer = document.getElementById('marcPreviewContainer');
            previewContainer.innerHTML = '';
            data.preview.forEach(rec => {
                const card = document.createElement('div');
                card.className = 'bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-lg p-4';
                let fieldsHtml = '';
                if (rec.fields_summary) {
                    fieldsHtml = '<div class="mt-3 space-y-1">' +
                        rec.fields_summary.map(f =>
                            `<div class="flex text-xs"><span class="w-12 font-mono font-bold text-indigo-600 dark:text-indigo-400">${f.tag}</span><span class="text-gray-400 dark:text-slate-500 w-40 truncate">${f.label}</span><span class="text-gray-700 dark:text-slate-300 flex-1 truncate">${f.value}</span></div>`
                        ).join('') +
                    '</div>';
                }
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#${rec.row_index}</span>
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <span><strong>ISBN:</strong> ${rec.isbn || 'N/A'}</span>
                            <span><strong><?php echo e(__('Năm')); ?>:</strong> ${rec.year || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-gray-800 dark:text-slate-100">${rec.title || 'N/A'}</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">${rec.author || 'N/A'} ${rec.publisher ? '— ' + rec.publisher : ''}</div>
                    ${fieldsHtml}
                `;
                previewContainer.appendChild(card);
            });

            // Errors
            const errorsSection = document.getElementById('marcErrorsSection');
            const errorsList = document.getElementById('marcErrorsList');
            if (data.errors && data.errors.length > 0) {
                errorsSection.classList.remove('hidden');
                errorsList.innerHTML = '';
                data.errors.forEach(err => {
                    const div = document.createElement('div');
                    div.className = 'text-red-600 dark:text-red-400';
                    div.textContent = `<?php echo e(__('Bản ghi')); ?> #${err.row_index}: ${err.errors.join(', ')}`;
                    errorsList.appendChild(div);
                });
            } else {
                errorsSection.classList.add('hidden');
            }

            // Extracted framework table
            extractedFrameworkData = data.extracted_framework || [];
            const fwBody = document.getElementById('marcFrameworkBody');
            fwBody.innerHTML = '';
            extractedFrameworkData.forEach(tag => {
                const sfCodes = Object.values(tag.subfields).join(', ');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-2 font-mono font-bold text-indigo-600 dark:text-indigo-400">${tag.tag}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-slate-300">${tag.label}</td>
                    <td class="px-4 py-2 font-mono text-gray-500 dark:text-slate-400">${sfCodes || '-'}</td>
                `;
                fwBody.appendChild(tr);
            });

            // Rebuild framework dropdown with matching info
            const marcProcessFramework = document.getElementById('marcProcessFramework');
            const marcProcessBtn = document.getElementById('marcProcessBtn');
            marcProcessFramework.innerHTML = '';

            // Option: create new from file
            const createOpt = document.createElement('option');
            createOpt.value = '__create_new__';
            createOpt.textContent = '✨ <?php echo e(__("Tạo khung mới từ file MARC")); ?>';
            createOpt.style.fontWeight = 'bold';
            marcProcessFramework.appendChild(createOpt);

            // Separator
            const sepOpt = document.createElement('option');
            sepOpt.disabled = true;
            sepOpt.textContent = '─────────────────────────';
            marcProcessFramework.appendChild(sepOpt);

            // Matching frameworks from server
            const matchingFws = data.matching_frameworks || [];
            let bestMatchId = null;

            matchingFws.forEach(fw => {
                const opt = document.createElement('option');
                opt.value = fw.id;
                let label = `${fw.name} (${fw.code})`;
                if (fw.is_compatible) {
                    label += ` ✅ Phù hợp ${fw.match_ratio}% (${fw.matched_tags}/${fw.total_file_tags} tags)`;
                    if (!bestMatchId) bestMatchId = fw.id;
                } else {
                    label += ` — ${fw.match_ratio}% (${fw.matched_tags}/${fw.total_file_tags} tags)`;
                }
                opt.textContent = label;
                marcProcessFramework.appendChild(opt);
            });

            marcProcessFramework.addEventListener('change', function() {
                marcProcessBtn.disabled = !this.value;
            });

            // Auto-select best match or create-new
            const preSelectedFw = document.getElementById('marc_framework_id').value;
            if (preSelectedFw) {
                marcProcessFramework.value = preSelectedFw;
            } else if (bestMatchId) {
                marcProcessFramework.value = bestMatchId;
            } else {
                marcProcessFramework.value = '__create_new__';
            }
            marcProcessBtn.disabled = false;

            document.getElementById('marcValidationResults').classList.remove('hidden');
            document.getElementById('marcValidationResults').scrollIntoView({ behavior: 'smooth' });
        }

        // Save framework button
        document.getElementById('saveFrameworkBtn').addEventListener('click', async function() {
            if (extractedFrameworkData.length === 0) {
                Swal.fire({ icon: 'warning', title: '<?php echo e(__("Không có dữ liệu")); ?>', text: '<?php echo e(__("Chưa có khung biên mục để lưu. Hãy upload file trước.")); ?>' });
                return;
            }

            const { value: formValues } = await Swal.fire({
                title: '<?php echo e(__("Lưu Khung biên mục")); ?>',
                html:
                    '<div style="text-align:left;margin-bottom:8px"><label style="font-size:13px;font-weight:600"><?php echo e(__("Tên khung biên mục")); ?></label></div>' +
                    '<input id="swal-fw-name" class="swal2-input" placeholder="<?php echo e(__("VD: Sách giáo trình y khoa")); ?>" style="margin-top:0">' +
                    '<div style="text-align:left;margin-bottom:8px;margin-top:16px"><label style="font-size:13px;font-weight:600"><?php echo e(__("Mã khung (viết tắt, không dấu)")); ?></label></div>' +
                    '<input id="swal-fw-code" class="swal2-input" placeholder="<?php echo e(__("VD: SGTYKHOA")); ?>" style="margin-top:0;text-transform:uppercase">' +
                    `<div style="text-align:left;margin-top:16px;font-size:12px;color:#666"><?php echo e(__("Khung sẽ bao gồm")); ?> <strong>${extractedFrameworkData.length}</strong> <?php echo e(__("trường MARC")); ?></div>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__("Lưu khung")); ?>',
                cancelButtonText: '<?php echo e(__("Huỷ")); ?>',
                confirmButtonColor: '#059669',
                preConfirm: () => {
                    const name = document.getElementById('swal-fw-name').value.trim();
                    const code = document.getElementById('swal-fw-code').value.trim();
                    if (!name || !code) {
                        Swal.showValidationMessage('<?php echo e(__("Vui lòng nhập đầy đủ tên và mã khung")); ?>');
                        return false;
                    }
                    return { name, code };
                }
            });

            if (formValues) {
                Swal.fire({ title: '<?php echo e(__("Đang lưu...")); ?>', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                try {
                    const tagsPayload = extractedFrameworkData.map(t => ({
                        tag: t.tag,
                        label: t.label,
                        subfields: Object.values(t.subfields)
                    }));

                    const response = await fetch('<?php echo e(route("admin.marc.import.save-framework-marc")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            framework_name: formValues.name,
                            framework_code: formValues.code,
                            tags: tagsPayload
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        // Add new framework to select dropdown
                        const select = document.getElementById('marcProcessFramework');
                        const opt = document.createElement('option');
                        opt.value = result.data.framework_id;
                        opt.textContent = `${result.data.framework_name} (${result.data.framework_code})`;
                        opt.selected = true;
                        select.appendChild(opt);
                        document.getElementById('marcProcessBtn').disabled = false;

                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo e(__("Khung biên mục đã lưu")); ?>',
                            html: `<p><?php echo e(__("Khung")); ?> <strong>${result.data.framework_name}</strong> <?php echo e(__("đã được tạo thành công và đã được chọn để import.")); ?></p>`,
                            confirmButtonColor: '#059669'
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi")); ?>', text: result.message });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi kết nối")); ?>', text: error.message });
                }
            }
        });

        // Process MARC import
        document.getElementById('marcProcessBtn').addEventListener('click', async function() {
            let frameworkId = document.getElementById('marcProcessFramework').value;
            const actionType = document.getElementById('marc_action_type').value;

            if (!frameworkId) {
                Swal.fire({ icon: 'warning', title: '<?php echo e(__("Chưa chọn khung")); ?>', text: '<?php echo e(__("Vui lòng chọn khung biên mục trước khi import.")); ?>' });
                return;
            }

            // If "create new from file" selected, prompt for name/code first
            if (frameworkId === '__create_new__') {
                if (extractedFrameworkData.length === 0) {
                    Swal.fire({ icon: 'warning', title: '<?php echo e(__("Không có dữ liệu")); ?>', text: '<?php echo e(__("Chưa có khung biên mục để tạo. Hãy upload file trước.")); ?>' });
                    return;
                }

                const { value: formValues } = await Swal.fire({
                    title: '<?php echo e(__("Tạo khung biên mục từ file")); ?>',
                    html:
                        '<div style="text-align:left;margin-bottom:8px"><label style="font-size:13px;font-weight:600"><?php echo e(__("Tên khung biên mục")); ?></label></div>' +
                        '<input id="swalFwName" class="swal2-input" placeholder="<?php echo e(__("Ví dụ: Khung sách giáo trình")); ?>" style="margin:0 0 12px 0;width:100%">' +
                        '<div style="text-align:left;margin-bottom:8px"><label style="font-size:13px;font-weight:600"><?php echo e(__("Mã khung (viết hoa)")); ?></label></div>' +
                        '<input id="swalFwCode" class="swal2-input" placeholder="<?php echo e(__("Ví dụ: GIAOTRINH")); ?>" style="margin:0;width:100%">',
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: '<?php echo e(__("Tạo & Import")); ?>',
                    cancelButtonText: '<?php echo e(__("Huỷ")); ?>',
                    confirmButtonColor: '#16a34a',
                    preConfirm: () => {
                        const name = document.getElementById('swalFwName').value.trim();
                        const code = document.getElementById('swalFwCode').value.trim().toUpperCase();
                        if (!name || !code) {
                            Swal.showValidationMessage('<?php echo e(__("Vui lòng nhập đầy đủ tên và mã khung")); ?>');
                            return false;
                        }
                        if (code.length > 20) {
                            Swal.showValidationMessage('<?php echo e(__("Mã khung tối đa 20 ký tự")); ?>');
                            return false;
                        }
                        return { name, code };
                    }
                });

                if (!formValues) return;

                // Create framework first
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <?php echo e(__('Đang tạo khung biên mục...')); ?>

                `;

                try {
                    const tagsPayload = extractedFrameworkData.map(t => ({
                        tag: t.tag,
                        label: t.label,
                        subfields: Object.values(t.subfields)
                    }));

                    const fwResponse = await fetch('<?php echo e(route("admin.marc.import.save-framework-marc")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            framework_name: formValues.name,
                            framework_code: formValues.code,
                            tags: tagsPayload
                        })
                    });

                    const fwResult = await fwResponse.json();
                    if (!fwResult.success) {
                        Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi tạo khung")); ?>', text: fwResult.message });
                        return;
                    }

                    frameworkId = fwResult.data.framework_id;
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo e(__("Đã tạo khung")); ?>',
                        text: `${fwResult.data.framework_name} (${fwResult.data.framework_code})`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    await new Promise(r => setTimeout(r, 1600));
                } catch (error) {
                    Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi kết nối")); ?>', text: error.message });
                    return;
                } finally {
                    this.disabled = false;
                    this.innerHTML = `
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <?php echo e(__('Tiến hành Import')); ?>

                    `;
                }
            }

            // Confirm import
            const confirmResult = await Swal.fire({
                title: '<?php echo e(__("Xác nhận Import")); ?>',
                text: '<?php echo e(__("Bạn có chắc chắn muốn tiến hành import các bản ghi MARC đã phân tích?")); ?>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__("Tiến hành")); ?>',
                cancelButtonText: '<?php echo e(__("Huỷ")); ?>',
                confirmButtonColor: '#16a34a'
            });

            if (!confirmResult.isConfirmed) return;

            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <?php echo e(__('Đang import...')); ?>

            `;

            try {
                const response = await fetch('<?php echo e(route("admin.marc.import.process-marc")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        framework_id: frameworkId,
                        action_type: actionType
                    })
                });

                const result = await response.json();
                if (result.success) {
                    showMarcProcessingResults(result.data);
                    Swal.fire({ icon: 'success', title: '<?php echo e(__("Import thành công")); ?>', text: result.message, timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi")); ?>', text: result.message });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: '<?php echo e(__("Lỗi kết nối")); ?>', text: error.message });
            } finally {
                this.disabled = false;
                this.innerHTML = `
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php echo e(__('Tiến hành Import')); ?>

                `;
            }
        });

        function showMarcProcessingResults(data) {
            const container = document.getElementById('marcProcessingResultsContent');
            const total = data.length;
            const successCount = data.filter(r => r.success).length;
            const failCount = total - successCount;

            container.innerHTML = `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-lg text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-slate-100">${total}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e(__('Tổng xử lý')); ?></div>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">${successCount}</div>
                        <div class="text-xs text-green-600 dark:text-green-400"><?php echo e(__('Thành công')); ?></div>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">${failCount}</div>
                        <div class="text-xs text-red-600 dark:text-red-400"><?php echo e(__('Thất bại')); ?></div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Nhan đề')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Số cuốn')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Trạng thái')); ?></th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400"><?php echo e(__('Chi tiết')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            ${data.map(row => `
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-2">${row.row_index}</td>
                                    <td class="px-4 py-2 font-medium text-gray-700 dark:text-slate-200">${row.title}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded text-xs font-bold">
                                            ${row.items_count || 0} <?php echo e(__('cuốn')); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        ${row.success ? 
                                            '<span class="text-green-600 dark:text-green-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Thành công</span>' : 
                                            '<span class="text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Thất bại</span>'
                                        }
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-500 dark:text-slate-400">
                                        ${row.success ? 
                                            `<a href="/topsecret/marc-books/form/${row.record_id}" target="_blank" class="text-indigo-600 hover:underline">ID: #${row.record_id}</a>` : 
                                            row.error
                                        }
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="location.reload()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                        <?php echo e(__('Hoàn tất')); ?>

                    </button>
                </div>
            `;

            document.getElementById('marcProcessingResults').classList.remove('hidden');
            document.getElementById('marcProcessingResults').scrollIntoView({ behavior: 'smooth' });
        }

        // Cancel
        document.getElementById('marcCancelBtn').addEventListener('click', function() {
            document.getElementById('marcValidationResults').classList.add('hidden');
        });

        // Reset
        document.getElementById('marcResetBtn').addEventListener('click', function() {
            marcForm.reset();
            updateMarcFileState();
            document.getElementById('marcValidationResults').classList.add('hidden');
            document.getElementById('marcProcessingResults').classList.add('hidden');
        });
    });

    // Global helper
    function resetMarcFile() {
        document.getElementById('marc_file').value = '';
        document.getElementById('marcUploadPlaceholder').classList.remove('hidden');
        document.getElementById('marcFileSelectedState').classList.add('hidden');
        document.getElementById('marcDropZone').classList.remove('border-emerald-500', 'bg-emerald-50/20');
        document.getElementById('marcDropZone').classList.add('border-gray-300', 'bg-gray-50/50');
        document.getElementById('marcUploadBtn').disabled = true;
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_import/index.blade.php ENDPATH**/ ?>