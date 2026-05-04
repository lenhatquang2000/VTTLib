<?php $__env->startSection('title', __('MARC Records Export & Reports')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 dark:text-slate-100 tracking-tight"><?php echo e(__('Xuất bản ghi & Báo cáo MARC')); ?></h1>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 flex items-center">
                <i class="fas fa-info-circle mr-2 text-indigo-500"></i>
                <?php echo e(__('Tùy chỉnh bộ lọc và chọn loại báo cáo phù hợp để trích xuất dữ liệu')); ?>

            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.marc.book')); ?>" class="inline-flex items-center px-4 py-2 text-sm font-bold bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2 text-xs"></i>
                <?php echo e(__('Quay lại')); ?>

            </a>
            <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-2 text-sm font-bold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                <i class="fas fa-sync-alt mr-2 text-xs"></i>
                <?php echo e(__('Làm mới')); ?>

            </button>
        </div>
    </div>

    <!-- Smart Filter Form -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 p-8">
        <form action="<?php echo e(route('admin.marc.export.download')); ?>" id="mainExportForm" method="GET" target="_blank">
            <?php echo csrf_field(); ?>
            
            <!-- Filters Section -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-slate-800 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-filter text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 tracking-tight"><?php echo e(__('BỘ LỌC THÔNG MINH')); ?></h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Document Type Filter -->
                    <div class="space-y-3">
                        <label for="document_type_id" class="flex items-center text-sm font-bold text-gray-700 dark:text-slate-300">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-500 mr-2">
                                <i class="fas fa-file-alt text-xs"></i>
                            </span>
                            <?php echo e(__('Loại tài liệu')); ?>

                        </label>
                        <select name="document_type_id" id="document_type_id" class="select2-smart w-full">
                            <option value=""><?php echo e(__('Tất cả loại tài liệu')); ?></option>
                            <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="space-y-3">
                        <label for="status" class="flex items-center text-sm font-bold text-gray-700 dark:text-slate-300">
                            <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-500 mr-2">
                                <i class="fas fa-check-circle text-xs"></i>
                            </span>
                            <?php echo e(__('Trạng thái bản ghi')); ?>

                        </label>
                        <select name="status" id="status" class="select2-smart w-full">
                            <option value=""><?php echo e(__('Tất cả trạng thái')); ?></option>
                            <option value="pending" data-icon="fa-clock"><?php echo e(__('Đang chờ duyệt')); ?></option>
                            <option value="approved" data-icon="fa-check-double"><?php echo e(__('Đã được phê duyệt')); ?></option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="space-y-3">
                        <label class="flex items-center text-sm font-bold text-gray-700 dark:text-slate-300">
                            <span class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center text-rose-500 mr-2">
                                <i class="fas fa-calendar-alt text-xs"></i>
                            </span>
                            <?php echo e(__('Khoảng thời gian tạo')); ?>

                        </label>
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-1">
                                <input type="date" name="date_from" id="date_from" class="w-full pl-4 pr-10 py-3 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 dark:text-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all outline-none">
                            </div>
                            <div class="text-gray-300 dark:text-slate-600">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="relative flex-1">
                                <input type="date" name="date_to" id="date_to" class="w-full pl-4 pr-10 py-3 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 dark:text-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Section -->
            <div class="bg-gray-50 dark:bg-slate-800/50 rounded-3xl p-6 border border-gray-100 dark:border-slate-800">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <!-- Format Toggle -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                        <div class="space-y-2">
                            <span class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block"><?php echo e(__('ĐỊNH DẠNG FILE')); ?></span>
                            <div class="inline-flex p-1.5 bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm">
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="format" value="excel" checked class="sr-only peer">
                                    <div class="px-5 py-2 text-sm font-bold rounded-xl transition-all peer-checked:bg-indigo-600 peer-checked:text-white text-gray-500 group-hover:text-indigo-600 peer-checked:group-hover:text-white">
                                        <i class="fas fa-file-excel mr-2 text-xs"></i>Excel
                                    </div>
                                </label>
                                <label class="relative group cursor-pointer ml-1">
                                    <input type="radio" name="format" value="csv" class="sr-only peer">
                                    <div class="px-5 py-2 text-sm font-bold rounded-xl transition-all peer-checked:bg-indigo-600 peer-checked:text-white text-gray-500 group-hover:text-indigo-600 peer-checked:group-hover:text-white">
                                        <i class="fas fa-file-csv mr-2 text-xs"></i>CSV
                                    </div>
                                </label>
                                <label class="relative group cursor-pointer ml-1">
                                    <input type="radio" name="format" value="marc" class="sr-only peer">
                                    <div class="px-5 py-2 text-sm font-bold rounded-xl transition-all peer-checked:bg-indigo-600 peer-checked:text-white text-gray-500 group-hover:text-indigo-600 peer-checked:group-hover:text-white">
                                        <i class="fas fa-database mr-2 text-xs"></i>MARC
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Item Switch -->
                        <div class="space-y-2">
                            <span class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block"><?php echo e(__('THÔNG TIN CHI TIẾT')); ?></span>
                            <label class="relative inline-flex items-center cursor-pointer h-[52px]">
                                <input type="checkbox" name="include_items" id="include_items" value="1" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[15px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-bold text-gray-700 dark:text-slate-300">
                                    <?php echo e(__('Kèm thông tin ấn phẩm')); ?>

                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <a href="<?php echo e(route('admin.marc.import.index')); ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-slate-900 text-emerald-600 border border-emerald-200 dark:border-emerald-900/30 font-bold rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                            <i class="fas fa-file-import mr-2"></i>
                            <?php echo e(__('Nhập liệu')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Additional Scripts for Smart Filter -->
    <?php $__env->startPush('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 52px !important;
            padding: 10px 16px !important;
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            outline: none !important;
            transition: all 0.2s !important;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(30, 41, 59, 0.5) !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .select2-container--default .select2-selection--single:focus, 
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 12px !important;
        }
        .select2-dropdown {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            margin-top: 4px !important;
        }
        .dark .select2-dropdown {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }
        .select2-results__option {
            padding: 12px 16px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5 !important;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-smart').select2({
                minimumResultsForSearch: 10,
                width: '100%'
            });
        });
    </script>
    <?php $__env->stopPush(); ?>

    <!-- Subsystem Reports Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Báo cáo phân hệ biên mục -->
        <div class="group bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-all hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1">
            <div class="p-6 border-b border-gray-50 dark:border-slate-800 bg-gradient-to-br from-indigo-50/50 to-transparent dark:from-indigo-900/10">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200 dark:shadow-none">
                        <i class="fas fa-book-reader text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('Phân hệ biên mục')); ?></h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium"><?php echo e(__('Quản lý danh sách & bài trích')); ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-2">
                <button type="button" onclick="generateReport('cataloging_subsystem')" class="w-full flex items-center p-3 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-indigo-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-list-ul text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-indigo-600 transition-colors"><?php echo e(__('Danh sách bản ghi biên mục')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('article_index')" class="w-full flex items-center p-3 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-indigo-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-quote-left text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-indigo-600 transition-colors"><?php echo e(__('Thư mục bài trích')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('book_stats')" class="w-full flex items-center p-3 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-indigo-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-chart-pie text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-indigo-600 transition-colors"><?php echo e(__('Thống kê số lượng đầu sách')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('book_id_list')" class="w-full flex items-center p-3 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-indigo-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-id-card text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-indigo-600 transition-colors"><?php echo e(__('Danh sách tài liệu theo mã sách')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- Báo cáo tài liệu -->
        <div class="group bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-all hover:shadow-xl hover:shadow-emerald-500/5 hover:-translate-y-1">
            <div class="p-6 border-b border-gray-50 dark:border-slate-800 bg-gradient-to-br from-emerald-50/50 to-transparent dark:from-emerald-900/10">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 dark:shadow-none">
                        <i class="fas fa-archive text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('Báo cáo tài liệu')); ?></h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium"><?php echo e(__('Tình hình kho & đăng ký')); ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-2">
                <button type="button" onclick="generateReport('inventory_status')" class="w-full flex items-center p-3 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-emerald-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-warehouse text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-emerald-600 transition-colors"><?php echo e(__('Tình hình kho tài liệu')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('accession_book')" class="w-full flex items-center p-3 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-emerald-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-address-book text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-emerald-600 transition-colors"><?php echo e(__('Số đăng ký cá biệt')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="notImplemented()" class="w-full flex items-center p-3 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group/item text-left">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-emerald-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-tags text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-emerald-600 transition-colors"><?php echo e(__('Danh sách nhan đề và số lượng')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- In Nhãn & Mã vạch -->
        <div class="group bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-all hover:shadow-xl hover:shadow-rose-500/5 hover:-translate-y-1">
            <div class="p-6 border-b border-gray-50 dark:border-slate-800 bg-gradient-to-br from-rose-50/50 to-transparent dark:from-rose-900/10">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-rose-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 dark:shadow-none">
                        <i class="fas fa-print text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('In Nhãn & Mã vạch')); ?></h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium"><?php echo e(__('Công cụ in ấn & dán nhãn')); ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-2">
                <button type="button" onclick="generateReport('spine_label')" class="w-full flex items-center p-3 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-rose-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-barcode text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-rose-600 transition-colors"><?php echo e(__('Dữ liệu in Nhãn gáy')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('barcode_list')" class="w-full flex items-center p-3 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-rose-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-stream text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-rose-600 transition-colors"><?php echo e(__('Dữ liệu in mã vạch')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
                <button type="button" onclick="generateReport('generated_barcodes')" class="w-full flex items-center p-3 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition group/item">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-rose-500 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors shadow-sm">
                        <i class="fas fa-plus-circle text-xs"></i>
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-600 dark:text-slate-300 group-hover/item:text-rose-600 transition-colors"><?php echo e(__('In mã vạch phát sinh')); ?></span>
                    <i class="fas fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover/item:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
    function generateReport(type) {
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

        // Add filter values from the main form
        const filters = [
            'framework_id', 'document_type_id', 'status', 
            'date_from', 'date_to'
        ];

        filters.forEach(id => {
            const element = document.getElementById(id);
            if (element && element.value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = id;
                input.value = element.value;
                form.appendChild(input);
            }
        });

        // Add include_items if checked
        const includeItems = document.getElementById('include_items');
        if (includeItems && includeItems.checked) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'include_items';
            input.value = '1';
            form.appendChild(input);
        }

        // Add format from radio buttons
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        const selectedFormat = document.querySelector('input[name="format"]:checked');
        formatInput.value = selectedFormat ? selectedFormat.value : 'excel';
        form.appendChild(formatInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    function notImplemented() {
        Swal.fire({
            title: '<?php echo e(__("Thông báo")); ?>',
            text: '<?php echo e(__("Chức năng này hiện đang trong quá trình phát triển.")); ?>',
            icon: 'info',
            confirmButtonText: '<?php echo e(__("Đóng")); ?>',
            confirmButtonColor: '#4f46e5'
        });
    }

    function resetForm() {
        document.getElementById('document_type_id').value = '';
        document.getElementById('status').value = '';
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('include_items').checked = false;
        // Reset radio buttons to excel
        document.querySelector('input[name="format"][value="excel"]').checked = true;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_books/export.blade.php ENDPATH**/ ?>