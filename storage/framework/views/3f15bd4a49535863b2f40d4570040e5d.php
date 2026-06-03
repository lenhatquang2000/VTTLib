

<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight"><?php echo e(__('Privilege Controller')); ?></h1>
            <p class="text-sm text-muted-foreground"><?php echo e(__('Assign and manage security clearances for system subjects.')); ?></p>
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
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="<?php echo e(route('admin.users.privileges')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo e($search); ?>" 
                        placeholder="Search users..." 
                        class="block w-full pl-9 pr-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                
                <!-- Role Filter -->
                <select name="role_id" class="h-9 w-full sm:w-40 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value="">All Roles</option>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>" <?php echo e($roleId == $role->id ? 'selected' : ''); ?>><?php echo e($role->display_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-compact-primary h-9 px-4">
                        <?php echo e(__('Search')); ?>

                    </button>

                    <?php if($search || $roleId): ?>
                        <a href="<?php echo e(route('admin.users.privileges')); ?>" 
                            class="btn-compact-secondary h-9 px-4">
                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                            <?php echo e(__('Clear')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3"><?php echo e(__('Identity')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Username')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Clearance Level')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Status / Permissions')); ?></th>
                        <th class="py-2 px-3 w-32 text-right"><?php echo e(__('Operations')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $roleUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                        <?php echo e(substr($ru->user->name, 0, 1)); ?>

                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-foreground leading-tight truncate"><?php echo e($ru->user->name); ?></div>
                                        <div class="text-[11px] text-muted-foreground leading-tight truncate"><?php echo e($ru->user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3 font-mono text-xs text-muted-foreground">
                                @ <?php echo e($ru->user->username ?? '---'); ?>

                            </td>
                            <td class="py-2 px-3">
                                <?php
                                    $roleClass = match($ru->role->name) {
                                        'root' => 'bg-destructive/10 text-destructive border-destructive/20',
                                        'admin' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                        'visitor' => 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20',
                                        default => 'bg-primary/10 text-primary border-primary/20'
                                    };
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border <?php echo e($roleClass); ?>">
                                    <?php echo e($ru->role->display_name); ?>

                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <button
                                    onclick="openSidebarSettings('<?php echo e($ru->id); ?>', '<?php echo e($ru->user->name); ?>', '<?php echo e($ru->role->name); ?>', <?php echo e($ru->sidebars->pluck('sidebar_id')); ?>)"
                                    class="btn-compact-primary text-[10px] px-3 py-1.5">
                                    <i data-lucide="settings-2" class="w-3.5 h-3.5 mr-1"></i>
                                    <?php echo e(__('Modify Tabs')); ?> <span class="ml-1 px-1.5 py-0.5 bg-background rounded-sm text-[9px]"><?php echo e($ru->sidebars->count()); ?></span>
                                </button>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="<?php echo e(route('admin.users.edit', $ru->user_id)); ?>" class="btn-icon-compact" title="<?php echo e(__('Edit')); ?>">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                        <i data-lucide="search-x" class="w-6 h-6 text-muted-foreground"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-foreground"><?php echo e(__('No Privileged Identities Found')); ?></h4>
                                    <p class="text-muted-foreground text-sm mt-1"><?php echo e(__('Try adjusting your filters.')); ?></p>
                                    <a href="<?php echo e(route('admin.users.privileges')); ?>" class="btn-compact-primary mt-4">
                                        <?php echo e(__('Reset Filters')); ?>

                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-muted/30 border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-[10px] text-muted-foreground font-bold uppercase tracking-wider">
                <?php echo e(__('Displaying')); ?> <?php echo e($roleUsers->firstItem() ?? 0); ?> - <?php echo e($roleUsers->lastItem() ?? 0); ?> <?php echo e(__('of')); ?> <?php echo e($roleUsers->total()); ?>

            </div>
            <div>
                <?php echo e($roleUsers->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR MODAL -->
<div id="sidebarModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('sidebarModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
        <div class="bg-card rounded-md shadow-lg relative overflow-hidden max-h-[90vh] flex flex-col border border-border">
            <div class="p-4 border-b border-border bg-card">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-base font-bold text-foreground leading-tight"><?php echo e(__('Access Control Terminal')); ?></h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="bg-primary/10 px-2 py-0.5 rounded-sm text-[10px] font-bold text-primary uppercase border border-primary/20">Target: <span id="modal-subject-name" class="ml-1 text-foreground"></span></span>
                            <span class="bg-amber-500/10 px-2 py-0.5 rounded-sm text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase border border-amber-500/20">Role: <span id="modal-role-name" class="ml-1 text-foreground"></span></span>
                        </div>
                    </div>
                    <button onclick="closeModal('sidebarModal')" class="btn-icon-compact">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-muted/10 custom-scrollbar">
                <form id="sidebarTabsForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $sidebars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="space-y-2 mb-4">
                                <label class="flex items-center gap-3 p-2 bg-card rounded border border-border hover:border-primary/50 transition-all cursor-pointer group">
                                    <input type="checkbox" name="sidebar_ids[]" value="<?php echo e($sidebar->id); ?>" class="sidebar-checkbox w-4 h-4 rounded-sm text-primary border-input bg-background focus:ring-primary focus:ring-offset-background">
                                    <span class="text-xs font-bold text-foreground uppercase tracking-wide"><?php echo e(__($sidebar->name)); ?></span>
                                </label>
                                <?php if($sidebar->children->isNotEmpty()): ?>
                                    <div class="grid grid-cols-1 gap-1.5 pl-6 border-l border-border ml-4">
                                        <?php $__currentLoopData = $sidebar->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center gap-2.5 p-1.5 hover:bg-muted/50 rounded-sm cursor-pointer transition-all">
                                                <input type="checkbox" name="sidebar_ids[]" value="<?php echo e($child->id); ?>" class="sidebar-checkbox w-3.5 h-3.5 rounded-sm text-primary border-input bg-background focus:ring-primary">
                                                <span class="text-[11px] font-medium text-muted-foreground hover:text-foreground transition-colors"><?php echo e(__($child->name)); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </form>
            </div>

            <div class="p-3 border-t border-border bg-card flex justify-end gap-2">
                <button type="button" onclick="closeModal('sidebarModal')" class="btn-compact-secondary"><?php echo e(__('Discard')); ?></button>
                <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()" class="btn-compact-primary"><?php echo e(__('Commit Changes')); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        lucide.createIcons();
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed:not(.hidden)').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });

    function openSidebarSettings(roleUserId, name, role, assignedIds) {
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = (role || 'N/A').toUpperCase();
        const form = document.getElementById('sidebarTabsForm');
        form.action = `<?php echo e(route('admin.users.tabs', ['id' => ':id'])); ?>`.replace(':id', roleUserId);
        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => { cb.checked = assignedIds.includes(parseInt(cb.value)); });
        openModal('sidebarModal');
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/users/privileges.blade.php ENDPATH**/ ?>