

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center justify-between pb-4">
            <div>
                <a href="<?php echo e(route('admin.roles.index')); ?>" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-wider mb-2">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <?php echo e(__('Back_to_Protocol')); ?>

                </a>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight"><?php echo e(__('Modify_Security_Clearance')); ?>: <span class="text-indigo-600 dark:text-indigo-400"><?php echo e($role->display_name); ?></span></h1>
            </div>
        </div>

        <form action="<?php echo e(route('admin.roles.update', $role->id)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 space-y-8 border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest pl-1"><?php echo e(__('Role_System_Name')); ?> (Slug)</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $role->name)); ?>" required
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl p-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all <?php echo e(in_array($role->name, ['root', 'admin', 'visitor']) ? 'opacity-60 cursor-not-allowed' : ''); ?>"
                            <?php echo e(in_array($role->name, ['root', 'admin', 'visitor']) ? 'readonly' : ''); ?>>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1 pl-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest pl-1"><?php echo e(__('Role_Display_Name')); ?></label>
                        <input type="text" name="display_name" value="<?php echo e(old('display_name', $role->display_name)); ?>" required
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl p-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1 pl-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider"><?php echo e(__('Default_Sidebar_Access_Template')); ?></h3>
                        <span class="text-[10px] bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-500 font-bold uppercase tracking-widest">Configuration</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-h-[600px] overflow-y-auto pr-3 custom-scrollbar">
                        <?php $__currentLoopData = $sidebars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="space-y-3 col-span-1 md:col-span-2 group">
                                <label class="flex items-center p-4 bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-900/50 transition-all shadow-sm">
                                    <input type="checkbox" name="sidebars[]" value="<?php echo e($sidebar->id); ?>"
                                        <?php echo e(in_array($sidebar->id, $roleSidebars) ? 'checked' : ''); ?>

                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500 transition shadow-sm">
                                    <div class="ml-4">
                                        <span class="block text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide"><?php echo e(__($sidebar->name)); ?></span>
                                        <span class="block text-[10px] text-slate-500 uppercase font-bold tracking-tighter mt-0.5">Parent Category</span>
                                    </div>
                                </label>
                                
                                <?php if($sidebar->children->isNotEmpty()): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-8">
                                        <?php $__currentLoopData = $sidebar->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center p-3.5 bg-white dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-xl cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-400 hover:shadow-md transition-all">
                                                <input type="checkbox" name="sidebars[]" value="<?php echo e($child->id); ?>"
                                                    <?php echo e(in_array($child->id, $roleSidebars) ? 'checked' : ''); ?>

                                                    class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500 transition">
                                                <div class="ml-3">
                                                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-tight"><?php echo e(__($child->name)); ?></span>
                                                    <span class="block text-[9px] font-mono text-slate-400 mt-1"><?php echo e($child->route_name); ?></span>
                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <button type="button" onclick="if(confirm('<?php echo e(__('Delete_Confirmation')); ?>')) document.getElementById('delete-role-form').submit();" 
                            class="inline-flex items-center px-4 py-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-lg hover:bg-rose-600 hover:text-white transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <?php echo e(__('Delete_Role')); ?>

                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="<?php echo e(route('admin.roles.index')); ?>" class="flex-1 md:flex-none text-center px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-bold uppercase transition hover:bg-slate-200">
                            <?php echo e(__('Abort')); ?>

                        </a>
                        <button type="submit"
                            class="flex-1 md:flex-none px-10 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-indigo-700 shadow-lg shadow-indigo-100 dark:shadow-none transition transform active:scale-95">
                            <?php echo e(__('Update_Sequence')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="delete-role-form" action="<?php echo e(route('admin.roles.destroy', $role->id)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/roles/edit.blade.php ENDPATH**/ ?>