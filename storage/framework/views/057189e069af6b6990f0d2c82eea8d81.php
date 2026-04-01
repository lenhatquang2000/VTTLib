

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight"><?php echo e(__('Identity Command Center')); ?></h1>
            <p class="text-lg font-medium text-slate-500 dark:text-slate-400 mt-1"><?php echo e(__('Monitor and manage system identity sequences.')); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('createUserModal')" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 dark:shadow-none transition-all transform active:scale-95 group">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <?php echo e(__('New Identity')); ?>

            </button>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center space-x-2 p-1.5 bg-slate-100 dark:bg-slate-900 rounded-[2rem] w-fit border border-slate-200 dark:border-slate-800">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all <?php echo e(Route::is('admin.users.index') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
            <?php echo e(__('Users List')); ?>

        </a>
        <a href="<?php echo e(route('admin.users.privileges')); ?>" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all <?php echo e(Route::is('admin.users.privileges') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
            <?php echo e(__('Privilege Controller')); ?>

        </a>
        <a href="<?php echo e(route('admin.roles.index')); ?>" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all <?php echo e(Route::is('admin.roles.index') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
            <?php echo e(__('Role Management')); ?>

        </a>
    </div>

    <!-- Insight Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-500/20 overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-[0.25em]"><?php echo e(__('Total Identities')); ?></p>
                <h3 class="text-5xl font-black mt-3"><?php echo e($stats['total']); ?></h3>
            </div>
            <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/10 transform rotate-12 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]"><?php echo e(__('Active Units')); ?></p>
            <h3 class="text-5xl font-black mt-3 text-slate-900 dark:text-white"><?php echo e($stats['active']); ?></h3>
            <div class="absolute right-8 top-8 w-4 h-4 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]"><?php echo e(__('New Enrollments')); ?></p>
            <h3 class="text-5xl font-black mt-3 text-slate-900 dark:text-white"><?php echo e($stats['new']); ?></h3>
            <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-slate-50 dark:text-slate-700/50 transform -rotate-12 group-hover:rotate-0 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-8 border-b border-slate-100 dark:border-slate-700/50">
            <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="relative flex-1 group">
                    <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('Search by name, email or username...')); ?>" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 pl-16 pr-8 py-5 rounded-[1.5rem] text-sm font-bold text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-300">
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Role Filter Tablets -->
                    <div class="flex items-center bg-slate-100/50 dark:bg-slate-900/50 p-1.5 rounded-2xl mr-4 border border-slate-100 dark:border-slate-700">
                        <a href="<?php echo e(route('admin.users.index', array_merge(request()->all(), ['role_id' => null]))); ?>" 
                           class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all <?php echo e(!$roleId ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 hover:text-slate-700'); ?>">
                            <?php echo e(__('All')); ?>

                        </a>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('admin.users.index', array_merge(request()->all(), ['role_id' => $role->id]))); ?>" 
                               class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all <?php echo e($roleId == $role->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 hover:text-slate-700'); ?>">
                                <?php echo e($role->display_name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="flex gap-2 min-w-full sm:min-w-0">
                        <button type="submit" class="flex-1 sm:flex-none px-12 py-5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.25em] rounded-[1.5rem] hover:bg-indigo-700 transition transform active:scale-95 shadow-xl shadow-indigo-500/10 dark:shadow-none whitespace-nowrap">
                            <?php echo e(__('Sync Filters')); ?>

                        </button>
                        <?php if($search || $roleId): ?>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="px-6 py-5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-[1.5rem] hover:bg-rose-600 hover:text-white transition flex items-center justify-center border border-rose-100 dark:border-rose-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Identity / Terminal')); ?></th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Security Clearance')); ?></th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Status')); ?></th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Enrolled')); ?></th>
                        <th scope="col" class="px-10 py-6 text-right text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Operations')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all duration-200">
                        <td class="px-10 py-7 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative group-hover:scale-110 transition-transform duration-300">
                                    <div class="h-14 w-14 rounded-[1.25rem] bg-indigo-50 dark:bg-indigo-900/30 border-2 border-white dark:border-slate-800 shadow-sm flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl uppercase">
                                        <?php echo e(substr($user->name, 0, 1)); ?>

                                    </div>
                                    <?php if($user->status == 'active'): ?>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border-4 border-white dark:border-slate-800 rounded-full"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-5">
                                    <div class="text-base font-black text-slate-900 dark:text-white leading-tight"><?php echo e($user->name); ?></div>
                                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 mt-1">@ <?php echo e($user->username); ?> <span class="mx-1.5">•</span> <?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-7">
                            <div class="flex flex-wrap items-center gap-2 text-sky-400">
                                <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <div class="relative group/role">
                                    <span class="inline-flex items-center px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-xl border <?php echo e($role->name == 'root' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/30 shadow-sm' : 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border-indigo-100 dark:border-indigo-900/30 shadow-sm'); ?>">
                                        <?php echo e($role->display_name); ?>

                                        
                                        <?php if($user->id !== Auth::id() || $role->name !== 'root'): ?>
                                        <form action="<?php echo e(route('admin.users.roles.remove', $role->pivot->id)); ?>" method="POST" class="ml-2 inline-flex items-center">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="opacity-0 group-hover/role:opacity-100 transition-opacity hover:text-rose-500 transform hover:scale-125" title="<?php echo e(__('Revoke Role')); ?>">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <span class="px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-xl border bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border-slate-100 dark:border-slate-700 italic opacity-50"><?php echo e(__('Chưa gán')); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-7 whitespace-nowrap">
                            <?php
                                $statusMap = [
                                    'active' => ['emerald', 'Authorized'],
                                    'inactive' => ['slate', 'Standby'],
                                    'suspended' => ['rose', 'Terminated'],
                                ];
                                [$color, $label] = $statusMap[$user->status] ?? ['indigo', $user->status];
                            ?>
                            <div class="inline-flex items-center px-4 py-2 rounded-2xl bg-<?php echo e($color); ?>-50 dark:bg-<?php echo e($color); ?>-900/20 text-<?php echo e($color); ?>-700 dark:text-<?php echo e($color); ?>-400 font-black text-[10px] uppercase tracking-widest border border-<?php echo e($color); ?>-100 dark:border-<?php echo e($color); ?>-900/30 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-<?php echo e($color); ?>-500 mr-2 shadow-[0_0_10px_rgba(var(--color-<?php echo e($color); ?>-500),0.5)]"></span>
                                <?php echo e($label); ?>

                            </div>
                        </td>
                        <td class="px-6 py-7 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-700 dark:text-slate-300"><?php echo e($user->created_at->format('M d, Y')); ?></div>
                            <div class="text-[9px] text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-widest mt-1"><?php echo e($user->created_at->diffForHumans()); ?></div>
                        </td>
                        <td class="px-10 py-7 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2.5">
                                <button onclick="openSidebarSettings('<?php echo e($user->roles->first()?->pivot->id); ?>', '<?php echo e($user->name); ?>', '<?php echo e($user->roles->first()?->name); ?>', <?php echo e($user->getSidebarTabs()->pluck('id')); ?>)"
                                    class="p-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 dark:text-indigo-400 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all border border-transparent hover:border-indigo-100 dark:hover:border-indigo-900/30 shadow-none hover:shadow-xl flex items-center justify-center group" title="<?php echo e(__('Gán quyền hạn (Tabs)')); ?>">
                                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                </button>

                                <!-- Role Assign Dropdown -->
                                <div class="relative group/role-select">
                                    <button class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-500 dark:text-amber-400 rounded-2xl hover:bg-amber-600 hover:text-white transition-all border border-transparent hover:border-amber-100 dark:hover:border-amber-900/30 shadow-none hover:shadow-xl flex items-center justify-center group" title="<?php echo e(__('Gán vai trò (Roles)')); ?>">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    </button>
                                    
                                    <div class="absolute right-0 bottom-full mb-3 w-64 opacity-0 invisible group-hover/role-select:opacity-100 group-hover/role-select:visible transition-all duration-300 z-50 transform translate-y-2 group-hover/role-select:translate-y-0">
                                        <div class="bg-white dark:bg-slate-800 rounded-[1.5rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden p-3">
                                            <div class="px-4 py-2 border-b border-slate-50 dark:border-slate-700/50 mb-2">
                                                <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?php echo e(__('Escalate Clearance')); ?></p>
                                                <p class="text-10px font-bold text-slate-900 dark:text-white truncate mt-0.5"><?php echo e($user->name); ?></p>
                                            </div>
                                            <div class="space-y-1 max-h-48 overflow-y-auto custom-scrollbar">
                                                <?php
                                                    $availableRoles = $roles->reject(fn($r) => $user->roles->contains($r->id));
                                                ?>
                                                <?php $__empty_2 = true; $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <form action="<?php echo e(route('admin.users.roles.store')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                                                    <input type="hidden" name="role_id" value="<?php echo e($role->id); ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-3 text-[11px] font-black uppercase tracking-tight text-slate-600 dark:text-slate-300 hover:bg-indigo-600 hover:text-white rounded-xl transition-all flex items-center justify-between group/item">
                                                        <?php echo e($role->display_name); ?>

                                                        <svg class="w-3 h-3 opacity-0 group-hover/item:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    </button>
                                                </form>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <div class="px-4 py-3 text-[10px] font-bold text-slate-400 italic text-center"><?php echo e(__('Full Access Granted')); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-2xl hover:bg-white dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-600 shadow-none hover:shadow-xl group/btn" title="Edit Master Data">
                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <?php if($user->id !== Auth::id()): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" onsubmit="return confirm('CRITICAL: Delete this core identity permanently?')" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-400 dark:text-rose-600 rounded-2xl hover:bg-rose-600 hover:text-white transition-all shadow-none hover:shadow-xl group/del" title="Destroy Identity">
                                        <svg class="w-5 h-5 group-hover/del:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div class="w-32 h-32 bg-slate-50 dark:bg-slate-900/50 rounded-[3.5rem] flex items-center justify-center mb-8 border border-slate-100 dark:border-slate-700 shadow-inner group">
                                    <svg class="w-16 h-16 text-slate-200 dark:text-slate-700 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <h4 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight"><?php echo e(__('No Identities Detected')); ?></h4>
                                <p class="text-slate-500 dark:text-slate-400 font-medium mt-3 text-base"><?php echo e(__('Try adjusting your filter parameters to locate the subject fingerprint.')); ?></p>
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="mt-10 px-8 py-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-[0.3em] rounded-2xl hover:bg-indigo-600 hover:text-white transition-all"><?php echo e(__('Reset Global Filter')); ?></a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-10 py-10 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700/50 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-[0.25em]">
                <?php echo e(__('Displaying Sequence')); ?> <span class="text-slate-900 dark:text-white"><?php echo e($users->firstItem() ?? 0); ?></span> - <span class="text-slate-900 dark:text-white"><?php echo e($users->lastItem() ?? 0); ?></span> <span class="mx-3 text-slate-300 dark:text-slate-700">/</span> <?php echo e($users->total()); ?> ARCHIVED IDENTITIES
            </div>
            <div class="custom-pagination">
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-xl" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-white dark:bg-slate-800 rounded-[4rem] shadow-2xl relative overflow-hidden transition-all border border-slate-200 dark:border-slate-700 animate-modal">
                <div class="h-3 w-full bg-indigo-600 shadow-[0_4px_20px_rgba(79,70,229,0.4)]"></div>
                <div class="p-14">
                    <div class="flex justify-between items-center mb-12">
                        <div>
                            <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-tight"><?php echo e(__('Initialize Identity')); ?></h3>
                            <p class="text-slate-500 font-bold mt-2 uppercase text-[10px] tracking-widest">Protocol: MASTER_DATA_ENTRY_V2</p>
                        </div>
                        <button onclick="closeModal('createUserModal')" class="p-4 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-all hover:rotate-90 duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="space-y-8">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="max_id" id="max_id_input" value="<?php echo e($maxUserId ?? 0); ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-3 block"><?php echo e(__('Full Subject Name')); ?></label>
                                <input type="text" name="name" id="name_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-6 text-sm font-black text-slate-900 dark:text-white focus:ring-[15px] focus:ring-indigo-500/5 outline-none transition-all duration-300">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-3 block"><?php echo e(__('Identity ID')); ?> (Username)</label>
                                <input type="text" name="username" id="username_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-6 text-sm font-black text-slate-900 dark:text-white focus:ring-[15px] focus:ring-indigo-500/5 outline-none transition-all duration-300 font-mono">
                                <p id="username_status" class="text-[10px] font-black mt-2 pl-3 hidden"></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-3 block"><?php echo e(__('Relay Email Address')); ?></label>
                            <input type="email" name="email" required 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-6 text-sm font-black text-slate-900 dark:text-white focus:ring-[15px] focus:ring-indigo-500/5 outline-none transition-all duration-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-3 block"><?php echo e(__('Master Cipher')); ?> (Password)</label>
                                <input type="password" name="password" id="password_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-6 text-sm font-black text-slate-900 dark:text-white focus:ring-[15px] focus:ring-indigo-500/5 outline-none transition-all duration-300">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-3 block"><?php echo e(__('Initial Clearance')); ?> (Role)</label>
                                <select name="role_id" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-6 text-sm font-black text-slate-700 dark:text-slate-300 focus:ring-[15px] focus:ring-indigo-500/5 outline-none transition-all duration-300 appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1.5rem_center] bg-no-repeat pr-14">
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-10">
                            <button type="button" onclick="closeModal('createUserModal')" class="flex-1 py-6 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-[2rem] text-[10px] font-black uppercase tracking-[0.3em] hover:bg-slate-200 transition duration-300 focus:outline-none"><?php echo e(__('Discard')); ?></button>
                            <button type="submit" class="flex-1 py-6 bg-indigo-600 text-white rounded-[2rem] text-[10px] font-black uppercase tracking-[0.3em] hover:bg-indigo-700 shadow-2xl shadow-indigo-500/40 dark:shadow-none transition transform active:scale-95 focus:outline-none"><?php echo e(__('Execute Enrollment')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl p-4">
            <div class="bg-white dark:bg-slate-800 rounded-[3.5rem] shadow-2xl relative overflow-hidden max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-700 animate-modal">
                <div class="p-10 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-tight tracking-tight"><?php echo e(__('Access Control Terminal')); ?></h3>
                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <span class="bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-xl text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30">Target: <span id="modal-subject-name" class="ml-1 text-slate-900 dark:text-white"></span></span>
                                <span class="bg-amber-50 dark:bg-amber-900/20 px-4 py-1.5 rounded-xl text-[9px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest border border-amber-100 dark:border-amber-900/30">Role: <span id="modal-role-name" class="ml-1 text-slate-900 dark:text-white"></span></span>
                            </div>
                        </div>
                        <button onclick="closeModal('sidebarModal')" class="p-3 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-10 bg-slate-50/50 dark:bg-slate-900/20 custom-scrollbar">
                    <form id="sidebarTabsForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $sidebars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-span-1 md:col-span-2 space-y-4 mb-6 last:mb-0">
                                    <label class="flex items-center p-5 bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-100 dark:border-slate-700 shadow-sm cursor-pointer hover:border-indigo-500 transition-all group">
                                        <input type="checkbox" name="sidebar_ids[]" value="<?php echo e($sidebar->id); ?>" class="sidebar-checkbox w-6 h-6 rounded-lg text-indigo-600 border-slate-300 dark:border-slate-600">
                                        <div class="ml-4">
                                            <span class="block text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider"><?php echo e(__($sidebar->name)); ?></span>
                                        </div>
                                    </label>
                                    
                                    <?php if($sidebar->children->isNotEmpty()): ?>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-8 border-l-2 border-slate-100 dark:border-slate-700 ml-3">
                                            <?php $__currentLoopData = $sidebar->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="flex items-center p-4 bg-white dark:bg-slate-800 rounded-2xl border border-transparent hover:border-indigo-300 dark:hover:border-indigo-800 cursor-pointer shadow-sm transition-all">
                                                    <input type="checkbox" name="sidebar_ids[]" value="<?php echo e($child->id); ?>" class="sidebar-checkbox w-4 h-4 rounded text-indigo-600 border-slate-300 dark:border-slate-600">
                                                    <div class="ml-4">
                                                        <span class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"><?php echo e(__($child->name)); ?></span>
                                                    </div>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </form>
                </div>

                <div class="p-10 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex justify-end gap-4">
                    <button type="button" onclick="closeModal('sidebarModal')" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors"><?php echo e(__('Discard')); ?></button>
                    <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition transform active:scale-95"><?php echo e(__('Commit Changes')); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    @keyframes modal-scale { from { transform: translate(-50%, -45%) scale(0.95); opacity: 0; } to { transform: translate(-50%, -50%) scale(1); opacity: 1; } }
    .animate-modal { animation: modal-scale 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openSidebarSettings(roleUserId, name, role, assignedIds) {
        if (!roleUserId || roleUserId === 'null') {
            alert('Cannot modify tabs for a user without an active role. Please assign a role first.');
            return;
        }
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = (role || 'N/A').toUpperCase();

        const form = document.getElementById('sidebarTabsForm');
        form.action = `/admin/users/roles/${roleUserId}/tabs`;

        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = assignedIds.includes(parseInt(cb.value));
        });

        openModal('sidebarModal');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed').forEach(m => {
                if(!m.classList.contains('hidden')) {
                    closeModal(m.id);
                }
            });
        }
    });

    // Username Generation Logic (Enhanced)
    function removeAccents(str) {
        return str.normalize('NFD')
                  .replace(/[\u0300-\u036f]/g, '')
                  .replace(/đ/g, 'd').replace(/Đ/g, 'D');
    }

    const nameInput = document.getElementById('name_input');
    const usernameInput = document.getElementById('username_input');
    const usernameStatus = document.getElementById('username_status');
    const maxIdInput = document.getElementById('max_id_input');
    let currentGeneratedUsername = '';

    let debounceTimer;
    nameInput?.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const name = this.value.trim();
        
        if (!name) {
            usernameInput.value = '';
            currentGeneratedUsername = '';
            usernameStatus?.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(() => {
            const words = name.split(/\s+/);
            if (words.length > 0) {
                let baseGenerated = '';
                for (let i = 0; i < words.length - 1; i++) {
                    baseGenerated += words[i].charAt(0);
                }
                baseGenerated += words[words.length - 1];
                
                const sanitized = removeAccents(baseGenerated).toLowerCase().replace(/[^a-z0-9]/g, '');
                validateUsername(sanitized);
            }
        }, 800);
    });

    async function validateUsername(username) {
        if (!username) return;

        try {
            const response = await fetch(`/admin/users/check-username?username=${username}`);
            const data = await response.json();
            
            if (data.exists) {
                currentGeneratedUsername = username + (data.max_id + 1);
                maxIdInput.value = data.max_id;
                usernameInput.value = currentGeneratedUsername;
                if (usernameStatus) {
                    usernameStatus.innerText = `[COLLISION] Redirected to: ${currentGeneratedUsername}`;
                    usernameStatus.className = 'text-[10px] font-black mt-2 pl-3 text-amber-500 uppercase tracking-wider';
                    usernameStatus.classList.remove('hidden');
                }
            } else {
                currentGeneratedUsername = username;
                maxIdInput.value = data.max_id;
                usernameInput.value = currentGeneratedUsername;
                if (usernameStatus) {
                    usernameStatus.innerText = '[VALID] Identity Sequence Ready';
                    usernameStatus.className = 'text-[10px] font-black mt-2 pl-3 text-emerald-500 uppercase tracking-wider';
                    usernameStatus.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error validating username:', error);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/users/index.blade.php ENDPATH**/ ?>