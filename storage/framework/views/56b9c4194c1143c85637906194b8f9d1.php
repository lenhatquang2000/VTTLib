<?php $__env->startSection('content'); ?>
<div class="space-y-6 pb-12">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('admin.patrons.index')); ?>" class="text-xs font-bold text-slate-400 hover:text-indigo-600 flex items-center transition-colors mb-2 uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <?php echo e(__('Back to List')); ?>

            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Edit Patron')); ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1"><?php echo e(__('Edit information for')); ?>: <?php echo e($patron->display_name); ?></p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="px-4 py-2 <?php echo e($patron->card_status == 'normal' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20' : 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-500/20'); ?> text-[10px] font-black uppercase tracking-widest rounded-xl border shadow-sm">
                <?php echo e(__('Tình trạng thẻ')); ?>: <?php echo e($patron->card_status == 'normal' ? __('Bình thường') : __('Bị khóa')); ?>

            </span>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-start space-x-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-rose-600 font-medium">
                <ul class="list-disc list-inside"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.patrons.update', $patron->id)); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
        
        <!-- Sidebar: Image & Status Toggles -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-8 flex flex-col items-center">
                <div class="w-full aspect-square rounded-3xl bg-slate-50 dark:bg-slate-950/50 border-2 border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden relative group cursor-pointer mb-6" onclick="document.getElementById('avatar-input').click()">
                    <?php if($patron->profile_image): ?>
                        <img id="avatar-preview" src="<?php echo e(asset('storage/' . $patron->profile_image)); ?>" class="w-full h-full object-cover">
                        <div id="avatar-placeholder" class="hidden text-slate-400 dark:text-slate-600 flex flex-col items-center">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest"><?php echo e(__('Ảnh đại diện')); ?></span>
                        </div>
                    <?php else: ?>
                        <img id="avatar-preview" src="#" class="hidden w-full h-full object-cover">
                        <div id="avatar-placeholder" class="text-slate-400 dark:text-slate-600 flex flex-col items-center">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest"><?php echo e(__('Ảnh đại diện')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" name="profile_image" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                <div class="flex space-x-2 w-full">
                    <button type="button" onclick="document.getElementById('avatar-input').click()" class="flex-1 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo e(__('Chọn ảnh')); ?>

                    </button>
                    <button type="button" onclick="removeAvatar()" class="flex-1 bg-rose-50 dark:bg-rose-500/10 text-rose-500 dark:text-rose-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <?php echo e(__('Xoá ảnh')); ?>

                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 space-y-4">
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400"><?php echo e(__('Chỉ đăng ký đọc')); ?></span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_read_only" value="1" class="sr-only peer" <?php echo e($patron->is_read_only ? 'checked' : ''); ?>>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </label>
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400"><?php echo e(__('Thẻ chờ in')); ?></span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_waiting_for_print" value="1" class="sr-only peer" <?php echo e($patron->is_waiting_for_print ? 'checked' : ''); ?>>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </div>
                </label>
                
                <!-- NEW: Reading Room Only -->
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400"><?php echo e(__('Đọc tại chỗ')); ?></span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_reading_room_only" value="1" class="sr-only peer" <?php echo e($patron->is_reading_room_only ? 'checked' : ''); ?>>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </div>
                </label>
                
                <!-- NEW: Add to Print Queue -->
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400"><?php echo e(__('Thêm vào danh sách chờ in')); ?></span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="add_to_print_queue" value="1" class="sr-only peer" <?php echo e($patron->add_to_print_queue ? 'checked' : ''); ?>>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </div>
                </label>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 space-y-4">
                <div class="text-center">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-3"><?php echo e(__('Trạng thái hiện tại')); ?></h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400"><?php echo e(__('Thẻ')); ?></span>
                            <span class="px-2 py-1 text-[8px] font-black rounded-full <?php echo e($patron->card_status == 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                                <?php echo e($patron->card_status == 'normal' ? __('Bình thường') : __('Bị khóa')); ?>

                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400"><?php echo e(__('Số dư')); ?></span>
                            <span class="text-[10px] font-bold <?php echo e($patron->balance >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e(number_format($patron->balance, 0, ',', '.')); ?> VNĐ
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400"><?php echo e(__('Ngày hết hạn')); ?></span>
                            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                <?php echo e(date('d/m/Y', strtotime($patron->expiry_date))); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Part 1: Identity -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200"><?php echo e(__('1. Thông tin định danh')); ?></h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Mã độc giả')); ?> <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="patron_code" required value="<?php echo e($patron->patron_code); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('MSSV')); ?></label>
                        <input type="text" name="mssv" value="<?php echo e($patron->mssv ?? ''); ?>" placeholder="Ex: 20210001"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Số danh bạ')); ?></label>
                        <input type="text" name="phone_contact" value="<?php echo e($patron->phone_contact ?? ''); ?>" placeholder="5339"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Loại độc giả')); ?></label>
                        <select name="patron_group_id" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                            <?php $__currentLoopData = $patronGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group->id); ?>" <?php echo e($patron->patron_group_id == $group->id ? 'selected' : ''); ?>><?php echo e($group->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Tên đầy đủ')); ?> <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="<?php echo e($patron->user->name); ?>" placeholder="NGUYEN VAN A"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Tên hiển thị')); ?> <span class="text-rose-500">*</span></label>
                        <input type="text" name="display_name" required value="<?php echo e($patron->display_name); ?>" placeholder="Van A"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Part 2: Personal & Organization -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200"><?php echo e(__('2. Cá nhân & Đơn vị')); ?></h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Ngày sinh')); ?></label>
                            <input type="date" name="date_of_birth" value="<?php echo e($patron->date_of_birth); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 block"><?php echo e(__('Giới tính')); ?></label>
                            <div class="flex items-center space-x-6 h-[46px]">
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="radio" name="gender" value="male" class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-slate-700 focus:ring-indigo-500" <?php echo e($patron->gender == 'male' ? 'checked' : ''); ?>>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors"><?php echo e(__('Nam')); ?></span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="radio" name="gender" value="female" class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-slate-700 focus:ring-indigo-500" <?php echo e($patron->gender == 'female' ? 'checked' : ''); ?>>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors"><?php echo e(__('Nữ')); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Tên trường')); ?></label>
                                <input type="text" name="school_name" value="<?php echo e($patron->school_name ?? ''); ?>"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Khóa')); ?></label>
                                <input type="text" name="batch" value="<?php echo e($patron->batch ?? ''); ?>"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Bộ phận')); ?></label>
                                <input type="text" name="department" value="<?php echo e($patron->department); ?>"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Chức vụ/Lớp')); ?></label>
                                <input type="text" name="position_class" value="<?php echo e($patron->position_class ?? ''); ?>"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 3: Contact & Auth -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200"><?php echo e(__('3. Liên lạc & Tài khoản')); ?></h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Số điện thoại')); ?></label>
                            <input type="text" name="phone" value="<?php echo e($patron->phone); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Fax')); ?></label>
                            <input type="text" name="fax" value="<?php echo e($patron->fax ?? ''); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Email')); ?> <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="<?php echo e($patron->user->email); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Mật khẩu mới (để trống nếu không đổi)')); ?></label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Chi nhánh')); ?></label>
                            <select name="branch_id" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id); ?>" <?php echo e($patron->branch_id == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Phân loại')); ?></label>
                            <select name="classification_type" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option value="individual" <?php echo e(($patron->classification_type ?? 'individual') == 'individual' ? 'selected' : ''); ?>><?php echo e(__('Cá nhân')); ?></option>
                                <option value="group" <?php echo e(($patron->classification_type ?? 'individual') == 'group' ? 'selected' : ''); ?>><?php echo e(__('Tổ chức')); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Danh sách địa chỉ')); ?></label>
                            <button type="button" onclick="addAddressField()" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-700">
                                + <?php echo e(__('Thêm địa chỉ')); ?>

                            </button>
                        </div>
                        <div id="address-list" class="space-y-3">
                            <?php if($patron->address): ?>
                                <div class="relative group">
                                    <input type="text" name="addresses[]" value="<?php echo e($patron->address); ?>" placeholder="<?php echo e(__('Địa chỉ chính...')); ?>"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[8px] font-black text-emerald-500 uppercase tracking-tighter bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-md"><?php echo e(__('Mặc định')); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="relative group">
                                    <input type="text" name="addresses[]" placeholder="<?php echo e(__('Địa chỉ chính...')); ?>"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[8px] font-black text-emerald-500 uppercase tracking-tighter bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-md"><?php echo e(__('Mặc định')); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 4: Financial & System Dates -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200"><?php echo e(__('4. Tài chính & Hệ thống')); ?></h2>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Lệ phí làm thẻ')); ?></label>
                            <input type="number" name="card_fee" value="<?php echo e($patron->card_fee ?? 0); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Tiền thế chân')); ?></label>
                            <input type="number" name="deposit" value="<?php echo e($patron->deposit ?? 0); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Số dư tài khoản')); ?></label>
                            <input type="number" name="balance" value="<?php echo e($patron->balance); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50 dark:border-slate-800">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Ngày cập nhật')); ?></label>
                            <input type="date" value="<?php echo e(date('Y-m-d')); ?>" disabled
                                class="w-full bg-slate-100 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 dark:text-slate-500 cursor-not-allowed">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Ngày đăng ký')); ?> <span class="text-rose-500">*</span></label>
                            <input type="date" name="registration_date" required value="<?php echo e($patron->registration_date); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Ngày hết hạn')); ?> <span class="text-rose-500">*</span></label>
                            <input type="date" name="expiry_date" required value="<?php echo e($patron->expiry_date); ?>"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2 pt-6 border-t border-slate-50 dark:border-slate-800">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Ghi chú')); ?></label>
                        <textarea name="notes" rows="3" placeholder="<?php echo e(__('Nhập ghi chú thêm về độc giả...')); ?>"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"><?php echo e($patron->notes ?? ''); ?></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1"><?php echo e(__('Tập tin đính kèm')); ?></label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-800 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-2 text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest"><?php echo e(__('Chọn file đính kèm')); ?></p>
                                </div>
                                <input name="attachments" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="card_status" value="<?php echo e($patron->card_status); ?>">

            <!-- Submit Button -->
            <button type="submit" class="group w-full relative overflow-hidden bg-slate-900 dark:bg-indigo-600 text-white rounded-3xl py-6 shadow-2xl transition-all hover:shadow-indigo-500/25 active:scale-[0.98]">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative flex items-center justify-center space-x-3">
                    <span class="text-sm font-black uppercase tracking-[0.3em] ml-2"><?php echo e(__('Update Patron Information')); ?></span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </button>
        </div>
    </form>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('avatar-preview').classList.remove('hidden');
                document.getElementById('avatar-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeAvatar() {
        document.getElementById('avatar-preview').src = '#';
        document.getElementById('avatar-preview').classList.add('hidden');
        document.getElementById('avatar-placeholder').classList.remove('hidden');
        document.getElementById('avatar-input').value = '';
    }

    function addAddressField() {
        const container = document.getElementById('address-list');
        const div = document.createElement('div');
        div.className = 'relative group flex items-center space-x-2 animate-in fade-in slide-in-from-top-2 duration-300';
        div.innerHTML = `
            <input type="text" name="addresses[]" placeholder="<?php echo e(__('Địa chỉ bổ sung...')); ?>"
                class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(div);
    }
</script>

<script>
function openRenewModal() {
    document.getElementById('renewModal').classList.remove('hidden');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/edit.blade.php ENDPATH**/ ?>