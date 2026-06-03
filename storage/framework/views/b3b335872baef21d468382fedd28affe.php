

<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight"><?php echo e(__('Role Management')); ?></h1>
            <p class="text-sm text-muted-foreground"><?php echo e(__('Define and manage system access levels.')); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-compact-secondary">
                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                <?php echo e(__('Manage Subjects')); ?>

            </a>
            <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <?php echo e(__('New Role')); ?>

            </a>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all <?php echo e(Route::is('admin.users.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'); ?>">
            <?php echo e(__('Users List')); ?>

        </a>
        <a href="<?php echo e(route('admin.users.privileges')); ?>" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all <?php echo e(Route::is('admin.users.privileges') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'); ?>">
            <?php echo e(__('Privilege Controller')); ?>

        </a>
        <a href="<?php echo e(route('admin.roles.index')); ?>" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all <?php echo e(Route::is('admin.roles.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'); ?>">
            <?php echo e(__('Role Management')); ?>

        </a>
    </div>

        


    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3 w-12"><?php echo e(__('ID')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Role Identity')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Assigned Subjects')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Default Tabs')); ?></th>
                        <th class="py-2 px-3 w-32 text-right"><?php echo e(__('Operations')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3 text-center text-muted-foreground font-medium text-xs">
                                #<?php echo e(str_pad($role->id, 3, '0', STR_PAD_LEFT)); ?>

                            </td>
                            <td class="py-2 px-3">
                                <div class="text-sm font-semibold text-foreground leading-tight"><?php echo e($role->display_name); ?></div>
                                <div class="text-[11px] text-muted-foreground font-mono mt-0.5"><?php echo e($role->name); ?></div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-sm border border-primary/20">
                                        <?php echo e($role->users_count); ?>

                                    </span>
                                    <span class="text-[10px] text-muted-foreground font-medium">subjects</span>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $role->sidebars->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-0.5 bg-muted text-muted-foreground text-[10px] font-medium rounded-sm border border-border">
                                            <?php echo e(__($sidebar->name)); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($role->sidebars->count() > 5): ?>
                                        <span class="text-[10px] text-muted-foreground font-medium self-center">+<?php echo e($role->sidebars->count() - 5); ?></span>
                                    <?php endif; ?>
                                    <?php if($role->sidebars->count() == 0): ?>
                                        <span class="text-[10px] text-muted-foreground italic font-medium">No default tabs</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="<?php echo e(route('admin.roles.edit', $role->id)); ?>" class="btn-icon-compact" title="<?php echo e(__('Edit')); ?>">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.roles.destroy', $role->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Delete_Confirmation')); ?>')" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-icon-danger" title="<?php echo e(__('Delete')); ?>">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>