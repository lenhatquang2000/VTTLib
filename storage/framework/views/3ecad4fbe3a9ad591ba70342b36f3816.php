<?php $__env->startSection('title', __('Patron Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Patron Management')); ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1"><?php echo e(__('Manage and audit library member identities.')); ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.patrons.import.index')); ?>" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl shadow-md transition-all hover:bg-emerald-500 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span><?php echo e(__('Import Excel')); ?></span>
            </a>
            <a href="<?php echo e(route('admin.patrons.create')); ?>" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md transition-all hover:bg-indigo-500">
                <?php echo e(__('Add New Patron')); ?>

            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center space-x-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm text-emerald-600 font-bold"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Search and Filters Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Search & Filters')); ?></h2>
        
        <form method="GET" action="<?php echo e(route('admin.patrons.index')); ?>" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="<?php echo e($search ?? ''); ?>" 
                               placeholder="<?php echo e(__('Search patrons...')); ?>" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <select name="search_field" class="px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all" <?php echo e(($searchField ?? 'all') == 'all' ? 'selected' : ''); ?>><?php echo e(__('All Fields')); ?></option>
                    <option value="patron_code" <?php echo e(($searchField ?? '') == 'patron_code' ? 'selected' : ''); ?>><?php echo e(__('Patron Code')); ?></option>
                    <option value="name" <?php echo e(($searchField ?? '') == 'name' ? 'selected' : ''); ?>><?php echo e(__('Name')); ?></option>
                    <option value="email" <?php echo e(($searchField ?? '') == 'email' ? 'selected' : ''); ?>><?php echo e(__('Email')); ?></option>
                    <option value="phone" <?php echo e(($searchField ?? '') == 'phone' ? 'selected' : ''); ?>><?php echo e(__('Phone')); ?></option>
                    <option value="address" <?php echo e(($searchField ?? '') == 'address' ? 'selected' : ''); ?>><?php echo e(__('Address')); ?></option>
                </select>
                
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('Search')); ?>

                </button>
                
                <a href="<?php echo e(route('admin.patrons.index')); ?>" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('Clear')); ?>

                </a>
            </div>

            <!-- Advanced Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Status')); ?></label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" <?php echo e(($status ?? 'all') == 'all' ? 'selected' : ''); ?>><?php echo e(__('All Status')); ?></option>
                        <option value="active" <?php echo e(($status ?? '') == 'active' ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                        <option value="locked" <?php echo e(($status ?? '') == 'locked' ? 'selected' : ''); ?>><?php echo e(__('Locked')); ?></option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Patron Group')); ?></label>
                    <select name="patron_group" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" <?php echo e(($patronGroup ?? 'all') == 'all' ? 'selected' : ''); ?>><?php echo e(__('All Groups')); ?></option>
                        <?php if(isset($patronGroups)): ?>
                            <?php $__currentLoopData = $patronGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group->id); ?>" <?php echo e(($patronGroup ?? '') == $group->id ? 'selected' : ''); ?>><?php echo e($group->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Branch')); ?></label>
                    <select name="branch" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" <?php echo e(($branch ?? 'all') == 'all' ? 'selected' : ''); ?>><?php echo e(__('All Branches')); ?></option>
                        <?php if(isset($branches)): ?>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branchItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branchItem->id); ?>" <?php echo e(($branch ?? '') == $branchItem->id ? 'selected' : ''); ?>><?php echo e($branchItem->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Per Page')); ?></label>
                    <select name="per_page" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="15" <?php echo e(($perPage ?? 15) == 15 ? 'selected' : ''); ?>>15</option>
                        <option value="30" <?php echo e(($perPage ?? '') == 30 ? 'selected' : ''); ?>>30</option>
                        <option value="50" <?php echo e(($perPage ?? '') == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(($perPage ?? '') == 100 ? 'selected' : ''); ?>>100</option>
                    </select>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Registration Date From')); ?></label>
                    <input type="date" name="date_from" value="<?php echo e($dateFrom ?? ''); ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1"><?php echo e(__('Registration Date To')); ?></label>
                    <input type="date" name="date_to" value="<?php echo e($dateTo ?? ''); ?>" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6" id="bulkActionsSection" style="display: none;">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700 dark:text-slate-300">
                    <span id="selectedCount">0</span> <?php echo e(__('patrons selected')); ?>

                </span>
                <button type="button" onclick="clearSelection()" class="text-sm text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <?php echo e(__('Clear selection')); ?>

                </button>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Bulk Edit Button -->
                <button type="button" onclick="openBulkEditModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <?php echo e(__('Bulk Edit')); ?>

                </button>
                
                <!-- Print Cards Button -->
                <form method="POST" action="<?php echo e(route('admin.patrons.cards.generate')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="layout" value="batch">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <?php echo e(__('Print Cards')); ?>

                    </button>
                </form>
                
                <!-- Delete Button -->
                <button type="button" onclick="confirmBulkDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <?php echo e(__('Delete')); ?>

                </button>
            </div>
        </div>
    </div>

    <!-- View Mode Toggle & Results Count -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600 dark:text-slate-400">
                <?php if(isset($patrons)): ?>
                    <?php echo e(__('Showing :count of :total results', ['count' => $patrons->count(), 'total' => $patrons->total()])); ?>

                <?php else: ?>
                    <?php echo e(__('No results to display')); ?>

                <?php endif; ?>
            </span>
        </div>
        
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600 dark:text-slate-400"><?php echo e(__('View Mode:')); ?></span>
            <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-1 flex">
                <button onclick="changeViewMode('card')" class="view-mode-btn px-3 py-1.5 rounded text-sm font-medium transition <?php echo e(($viewMode ?? 'card') == 'card' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200'); ?>">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <?php echo e(__('Cards')); ?>

                </button>
                <button onclick="changeViewMode('grid')" class="view-mode-btn px-3 py-1.5 rounded text-sm font-medium transition <?php echo e(($viewMode ?? '') == 'grid' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200'); ?>">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <?php echo e(__('Grid')); ?>

                </button>
                <button onclick="changeViewMode('list')" class="view-mode-btn px-3 py-1.5 rounded text-sm font-medium transition <?php echo e(($viewMode ?? '') == 'list' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200'); ?>">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <?php echo e(__('List')); ?>

                </button>
            </div>
        </div>
    </div>

    <!-- Results Display -->
    <?php if(isset($patrons) && $patrons->count() > 0): ?>
        <!-- Card View (Default) -->
        <?php if(($viewMode ?? 'card') == 'card'): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $patrons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patron): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 w-full max-w-[420px] mx-auto min-h-[240px] overflow-hidden">
                        <!-- Logo Watermark Background -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                            <img src="<?php echo e(asset('assets/imgs/logo-vttu.png')); ?>" class="w-1/2">
                        </div>
                        
                        <!-- Top Row: Label & Checkbox -->
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[12px] font-black text-indigo-700 dark:text-indigo-400 tracking-tight uppercase"><?php echo e(__('Library Card')); ?></span>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="selected_patrons[]" value="<?php echo e($patron->id); ?>" class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500">
                            </label>
                        </div>

                        <!-- Middle Content -->
                        <div class="flex space-x-5">
                            <!-- Left: Profile Photo -->
                            <div class="w-[110px] h-[140px] flex-shrink-0 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 overflow-hidden">
                                <?php if($patron->profile_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $patron->profile_image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right: Info Details -->
                            <div class="flex-1 flex flex-col pt-1">
                                <h2 class="text-[16px] font-black text-indigo-700 dark:text-indigo-400 uppercase leading-none mb-4 truncate"><?php echo e($patron->display_name); ?></h2>
                                
                                <div class="space-y-3 mb-4">
                                    <div class="text-[12px] font-bold text-indigo-600 dark:text-indigo-400">
                                        <?php echo e(date('d/m/Y', strtotime($patron->registration_date))); ?> - <?php echo e(date('d/m/Y', strtotime($patron->expiry_date))); ?>

                                    </div>
                                    <!-- Barcode Area -->
                                    <div class="relative">
                                        <div class="h-[45px] w-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-start overflow-hidden">
                                            <?php echo $barcodeService->renderSvg($patron->patron_code); ?>

                                        </div>
                                        <div class="text-[10px] font-black font-mono text-indigo-700 dark:text-indigo-400 text-left tracking-[0.2em] mt-1">
                                            <?php echo e($patron->patron_code); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Overlay for Quick Actions -->
                        <div class="absolute bottom-3 right-5 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Lock/Unlock -->
                            <?php if($patron->card_status == 'normal'): ?>
                                <button type="button" onclick="openLockModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name])); ?>)" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-yellow-600 dark:hover:text-yellow-400 shadow-sm" title="<?php echo e(__('Lock Card')); ?>">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </button>
                            <?php else: ?>
                                <button type="button" onclick="openUnlockModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'balance' => $patron->balance])); ?>)" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-green-600 dark:hover:text-green-400 shadow-sm" title="<?php echo e(__('Unlock Card')); ?>">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                </button>
                            <?php endif; ?>
                            
                            <!-- Financial Transaction -->
                            <button type="button" onclick="openTransactionModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'balance' => $patron->balance])); ?>)" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 shadow-sm" title="<?php echo e(__('Financial Transaction')); ?>">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                            
                            <!-- Print Queue -->
                            <?php if($patron->isInPrintQueue()): ?>
                                <form action="<?php echo e(route('admin.patrons.remove-from-print-queue', $patron->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 shadow-sm" title="<?php echo e(__('Remove from Print Queue')); ?>">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo e(route('admin.patrons.add-to-print-queue', $patron->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-gray-400 shadow-sm" title="<?php echo e(__('Add to Print Queue')); ?>">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <!-- Renew -->
                            <button onclick="openRenewModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'expiry' => $patron->expiry_date])); ?>)" 
                                class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </button>
                            
                            <!-- Delete -->
                            <form action="<?php echo e(route('admin.patrons.destroy', $patron->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-400 dark:text-slate-500 hover:text-rose-500 dark:hover:text-rose-400 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <!-- Status Dot -->
                        <div class="absolute top-3 right-3">
                            <div class="w-3 h-3 rounded-full <?php echo e($patron->card_status == 'normal' ? 'bg-green-500' : 'bg-red-500'); ?>"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('No patrons found.')); ?></p>
                    </div>
                <?php endif; ?>
            </div>

        <!-- Grid View -->
        <?php elseif(($viewMode ?? '') == 'grid'): ?>
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
                    <?php $__empty_1 = true; $__currentLoopData = $patrons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patron): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="text-center p-4 border border-gray-200 dark:border-slate-700 rounded-lg hover:shadow-md transition-all duration-200 group cursor-pointer">
                            <!-- Checkbox for bulk selection -->
                            <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="selected_patrons[]" value="<?php echo e($patron->id); ?>" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </label>
                            </div>
                            
                            <!-- Avatar -->
                            <div class="w-16 h-16 mx-auto mb-3 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden relative">
                                <?php if($patron->profile_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $patron->profile_image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    </div>
                                <?php endif; ?>
                                <!-- Status indicator -->
                                <div class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-white <?php echo e($patron->card_status == 'normal' ? 'bg-green-500' : 'bg-red-500'); ?>"></div>
                            </div>
                            
                            <!-- Name -->
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-slate-100 truncate mb-1" title="<?php echo e($patron->display_name); ?>">
                                <?php echo e($patron->display_name); ?>

                            </h3>
                            
                            <!-- Code -->
                            <p class="text-xs text-gray-500 dark:text-slate-400 font-mono mb-2"><?php echo e($patron->patron_code); ?></p>
                            
                            <!-- Group -->
                            <?php if($patron->patronGroup): ?>
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-2 truncate"><?php echo e($patron->patronGroup->name); ?></p>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <div class="flex justify-center mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($patron->card_status == 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                                    <?php echo e($patron->card_status == 'normal' ? __('Active') : __('Locked')); ?>

                                </span>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="flex justify-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="<?php echo e(route('admin.patrons.toggle-status', $patron->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="p-1 bg-gray-100 dark:bg-slate-700 rounded text-gray-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" title="<?php echo e(__('Lock/Unlock')); ?>">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </button>
                                </form>
                                <button onclick="openRenewModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'expiry' => $patron->expiry_date])); ?>)" 
                                    class="p-1 bg-gray-100 dark:bg-slate-700 rounded text-gray-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400" title="<?php echo e(__('Renew')); ?>">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </button>
                                <form action="<?php echo e(route('admin.patrons.destroy', $patron->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1 bg-gray-100 dark:bg-slate-700 rounded text-gray-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400" title="<?php echo e(__('Delete')); ?>">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('No patrons found.')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <!-- List View -->
        <?php elseif(($viewMode ?? '') == 'list'): ?>
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Patron')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Code')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Email')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Phone')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Group')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Branch')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Status')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Registration')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Expiry')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?php echo e(__('Actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $patrons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patron): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_patrons[]" value="<?php echo e($patron->id); ?>" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mr-3">
                                                <?php if($patron->profile_image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $patron->profile_image)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-slate-100"><?php echo e($patron->display_name); ?></div>
                                                <div class="text-xs text-gray-500 dark:text-slate-400"><?php echo e($patron->user->email ?? ''); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-slate-100 font-mono"><?php echo e($patron->patron_code); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-slate-100"><?php echo e($patron->user->email ?? ''); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-slate-100"><?php echo e($patron->phone ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-slate-100"><?php echo e($patron->patronGroup->name ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-slate-100"><?php echo e($patron->branch ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($patron->card_status == 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                                            <span class="w-2 h-2 mr-1.5 rounded-full <?php echo e($patron->card_status == 'normal' ? 'bg-green-400' : 'bg-red-400'); ?>"></span>
                                            <?php echo e($patron->card_status == 'normal' ? __('Active') : __('Locked')); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                        <?php echo e(date('d/m/Y', strtotime($patron->registration_date))); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                        <span class="<?php echo e(\Carbon\Carbon::parse($patron->expiry_date)->isPast() ? 'text-red-600 font-semibold' : ''); ?>">
                                            <?php echo e(date('d/m/Y', strtotime($patron->expiry_date))); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- View Details -->
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="<?php echo e(__('View Details')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            
                                            <!-- Lock/Unlock -->
                                            <?php if($patron->card_status == 'normal'): ?>
                                                <button type="button" onclick="openLockModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name])); ?>)" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="<?php echo e(__('Lock Card')); ?>">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" onclick="openUnlockModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'balance' => $patron->balance])); ?>)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="<?php echo e(__('Unlock Card')); ?>">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Financial Transaction -->
                                            <button type="button" onclick="openTransactionModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'balance' => $patron->balance])); ?>)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="<?php echo e(__('Financial Transaction')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                            
                                            <!-- Print Queue -->
                                            <?php if($patron->isInPrintQueue()): ?>
                                                <form action="<?php echo e(route('admin.patrons.remove-from-print-queue', $patron->id)); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="<?php echo e(__('Remove from Print Queue')); ?>">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('admin.patrons.add-to-print-queue', $patron->id)); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" title="<?php echo e(__('Add to Print Queue')); ?>">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <!-- Renew -->
                                            <button onclick="openRenewModal(<?php echo e(json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'expiry' => $patron->expiry_date])); ?>)" 
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="<?php echo e(__('Renew')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </button>
                                            
                                            <!-- Edit -->
                                            <a href="#" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="<?php echo e(__('Edit')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            
                                            <!-- Delete -->
                                            <form action="<?php echo e(route('admin.patrons.destroy', $patron->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="<?php echo e(__('Delete')); ?>">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('No patrons found.')); ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if($patrons->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($patrons->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-12 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100 mb-2"><?php echo e(__('No patrons found')); ?></h3>
            <p class="text-gray-500 dark:text-slate-400"><?php echo e(__('Try adjusting your search criteria or filters.')); ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Renew Modal -->
<div id="renewModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeRenewModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase"><?php echo e(__('Gia hạn thẻ')); ?></h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="renewPatronName"></p>
            </div>
            <form id="renewForm" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1"><?php echo e(__('Ngày hết hạn mới')); ?></label>
                    <input type="date" name="expiry_date" id="renew_expiry_date" required 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeRenewModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all"><?php echo e(__('Hủy')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-indigo-500 transition-all"><?php echo e(__('Cập nhật')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Bulk Edit Modal -->
<?php echo $__env->make('admin.patrons.bulk-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Include Patron Management Modals -->
<?php echo $__env->make('admin.patrons.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
// Bulk Actions JavaScript
let selectedPatrons = [];

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]:checked');
    selectedPatrons = Array.from(checkboxes).map(cb => cb.value);
    
    console.log('updateBulkActions - selectedPatrons:', selectedPatrons);
    
    const bulkActionsSection = document.getElementById('bulkActionsSection');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedPatrons.length > 0) {
        bulkActionsSection.style.display = 'block';
        selectedCount.textContent = selectedPatrons.length;
        
        // Find the print cards form and add patron IDs
        const printForm = document.querySelector('form[action*="cards/generate"]');
        if (printForm) {
            console.log('updateBulkActions - Found print form:', printForm);
            
            // Remove existing patron ID inputs
            const existingInputs = printForm.querySelectorAll('input[name="patron_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Add new patron ID inputs
            selectedPatrons.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'patron_ids[]';
                input.value = id;
                printForm.appendChild(input);
                console.log('updateBulkActions - Added input for patron ID:', id);
            });
            
            // Log all form data before submission
            printForm.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                console.log('Form submission data:', data);
            });
        } else {
            console.error('updateBulkActions - Print form not found!');
        }
    } else {
        bulkActionsSection.style.display = 'none';
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
    checkboxes.forEach(cb => cb.checked = false);
    updateBulkActions();
}

function openBulkEditModal() {
    if (selectedPatrons.length === 0) {
        alert('Please select at least one patron to edit.');
        return;
    }
    
    // Set patron IDs
    document.getElementById('bulkEditPatronIds').value = selectedPatrons.join(',');
    document.getElementById('selectedPatronsCount').textContent = selectedPatrons.length;
    
    // Show modal
    document.getElementById('bulkEditModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function confirmBulkDelete() {
    if (selectedPatrons.length === 0) {
        alert('Please select at least one patron to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedPatrons.length} patron(s)? This action cannot be undone.`)) {
        // Create form for bulk delete
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("admin.patrons.bulk.delete")); ?>';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Add patron IDs
        selectedPatrons.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'patron_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    // Select all functionality
    const selectAllCheckbox = document.querySelector('input[type="checkbox"]:not([name="selected_patrons[]"])');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }
});

// Existing functions...
function openRenewModal(patronId) {
    // Implementation for renew modal
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>

<script>
function changeViewMode(mode) {
    const url = new URL(window.location);
    url.searchParams.set('view_mode', mode);
    window.location.href = url.toString();
}

function openRenewModal(patron) {
    document.getElementById('renewForm').action = `/topsecret/patrons/${patron.id}/renew`;
    document.getElementById('renewPatronName').textContent = patron.name;
    document.getElementById('renew_expiry_date').value = patron.expiry;
    document.getElementById('renewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/index.blade.php ENDPATH**/ ?>