

<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight"><?php echo e(__('User Management')); ?></h1>
            <p class="text-sm text-muted-foreground"><?php echo e(__('Monitor and manage system identity sequences.')); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openModal('createUserModal')" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <?php echo e(__('New User')); ?>

            </button>
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
            <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2">
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
                        <a href="<?php echo e(route('admin.users.index')); ?>" 
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
                        <th class="py-2 px-3 w-12 text-center">#</th>
                        <th class="py-2 px-3"><?php echo e(__('Identity / Terminal')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Clearance')); ?></th>
                        <th class="py-2 px-3 w-32"><?php echo e(__('Status')); ?></th>
                        <th class="py-2 px-3 w-40"><?php echo e(__('Enrolled')); ?></th>
                        <th class="py-2 px-3 w-48 text-right"><?php echo e(__('Operations')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="table-row-hover group">
                        <td class="py-2 px-3 text-center text-muted-foreground font-medium text-xs">
                            <?php echo e($users->firstItem() + $index); ?>

                        </td>
                        <td class="py-2 px-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                    <?php echo e(substr($user->name, 0, 1)); ?>

                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-foreground leading-tight truncate"><?php echo e($user->name); ?></div>
                                    <div class="text-[11px] text-muted-foreground leading-tight truncate">@ <?php echo e($user->username); ?> <span class="mx-1">•</span> <?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex flex-wrap gap-1">
                                <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-sm border <?php echo e($role->name == 'root' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-primary/10 text-primary border-primary/20'); ?>">
                                    <?php echo e($role->display_name); ?>

                                    <?php if($user->id !== Auth::id() || $role->name !== 'root'): ?>
                                    <form action="<?php echo e(route('admin.users.roles.remove', $role->pivot->id)); ?>" method="POST" class="ml-1 inline-flex items-center">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="hover:text-destructive transition-colors">
                                            <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <span class="text-[10px] text-muted-foreground italic opacity-70"><?php echo e(__('Unassigned')); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <?php
                                $statusMap = [
                                    'active' => ['bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20', 'Authorized'],
                                    'inactive' => ['bg-muted text-muted-foreground border-border', 'Standby'],
                                    'suspended' => ['bg-destructive/10 text-destructive border-destructive/20', 'Terminated'],
                                ];
                                [$statusClass, $statusLabel] = $statusMap[$user->status] ?? ['bg-primary/10 text-primary border-primary/20', $user->status];
                            ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border <?php echo e($statusClass); ?>">
                                <?php echo e($statusLabel); ?>

                            </span>
                        </td>
                        <td class="py-2 px-3">
                            <div class="text-[11px] font-medium text-foreground">
                                <?php echo e($user->created_at ? $user->created_at->format('M d, Y') : 'N/A'); ?>

                            </div>
                            <?php if($user->created_at): ?>
                                <div class="text-[10px] text-muted-foreground opacity-70"><?php echo e($user->created_at->diffForHumans()); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-3 text-right">
                            <div class="flex justify-end items-center gap-1.5">
                                <button onclick="openSidebarSettings('<?php echo e($user->roles->first()?->pivot->id); ?>', '<?php echo e($user->name); ?>', '<?php echo e($user->roles->first()?->name); ?>', <?php echo e($user->getSidebarTabs()->pluck('id')); ?>)"
                                    class="btn-icon-compact" title="<?php echo e(__('Privileges')); ?>">
                                    <i data-lucide="settings-2" class="w-4 h-4"></i>
                                </button>

                                <!-- Role Assign Dropdown (using Alpine for compactness) -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.away="open = false" 
                                        class="btn-icon-compact" title="<?php echo e(__('Assign Role')); ?>">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                    </button>
                                    
                                    <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-48 bg-card rounded-md shadow-lg border border-border z-50 p-1">
                                        <div class="px-2 py-1.5 border-b border-border mb-1">
                                            <p class="text-[10px] font-bold text-muted-foreground uppercase"><?php echo e(__('Assign Clearance')); ?></p>
                                        </div>
                                        <div class="space-y-0.5 max-h-40 overflow-y-auto custom-scrollbar">
                                            <?php
                                                $availableRoles = $roles->reject(fn($r) => $user->roles->contains($r->id));
                                            ?>
                                            <?php $__empty_2 = true; $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <form action="<?php echo e(route('admin.users.roles.store')); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                                                <input type="hidden" name="role_id" value="<?php echo e($role->id); ?>">
                                                <button type="submit" class="w-full text-left px-2 py-1.5 text-xs font-medium hover:bg-muted rounded transition-colors flex items-center justify-between">
                                                    <?php echo e($role->display_name); ?>

                                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                            <div class="px-2 py-2 text-[10px] font-medium text-muted-foreground italic text-center"><?php echo e(__('Full Access Granted')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn-icon-compact" title="<?php echo e(__('Edit')); ?>">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>

                                <?php if($user->id !== Auth::id()): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" onsubmit="return confirm('CRITICAL: Delete this identity permanently?')" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-icon-danger" title="<?php echo e(__('Delete')); ?>">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                    <i data-lucide="search-x" class="w-6 h-6 text-muted-foreground"></i>
                                </div>
                                <h4 class="text-base font-bold text-foreground"><?php echo e(__('No Identities Detected')); ?></h4>
                                <p class="text-muted-foreground text-sm mt-1"><?php echo e(__('Try adjusting your filters.')); ?></p>
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-compact-primary mt-4">
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
                <?php echo e(__('Displaying')); ?> <?php echo e($users->firstItem() ?? 0); ?> - <?php echo e($users->lastItem() ?? 0); ?> <?php echo e(__('of')); ?> <?php echo e($users->total()); ?>

            </div>
            <div>
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="bg-card rounded-md shadow-lg relative overflow-hidden transition-all border border-border flex flex-col">
                <div class="p-4 border-b border-border flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-foreground leading-tight"><?php echo e(__('Initialize Identity')); ?></h3>
                        <p class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest mt-0.5">Protocol: MASTER_DATA_ENTRY_V2</p>
                    </div>
                    <button onclick="closeModal('createUserModal')" class="btn-icon-compact">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="p-4 space-y-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="max_id" id="max_id_input" value="<?php echo e($maxUserId ?? 0); ?>">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block"><?php echo e(__('Full Name')); ?></label>
                            <input type="text" name="name" id="name_input" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block"><?php echo e(__('Username')); ?></label>
                            <input type="text" name="username" id="username_input" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono">
                            <p id="username_status" class="text-[9px] font-bold mt-1 hidden"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block"><?php echo e(__('Email Address')); ?></label>
                        <input type="email" name="email" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block"><?php echo e(__('Password')); ?></label>
                            <input type="password" name="password" id="password_input" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block"><?php echo e(__('Initial Role')); ?></label>
                            <select name="role_id" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="button" onclick="closeModal('createUserModal')" class="btn-compact-secondary flex-1"><?php echo e(__('Discard')); ?></button>
                        <button type="submit" class="btn-compact-primary flex-1"><?php echo e(__('Enroll Subject')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
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
        if (!roleUserId || roleUserId === 'null') {
            Swal.fire({
                icon: 'warning',
                title: 'No Role Assigned',
                text: 'Cannot modify tabs for a user without an active role.',
                customClass: {
                    popup: 'rounded-md',
                    confirmButton: 'btn-compact-primary'
                }
            });
            return;
        }
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = (role || 'N/A').toUpperCase();

        const form = document.getElementById('sidebarTabsForm');
        form.action = `<?php echo e(route('admin.users.tabs', ['id' => ':id'])); ?>`.replace(':id', roleUserId);

        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = assignedIds.includes(parseInt(cb.value));
        });

        openModal('sidebarModal');
    }

    // Username Generation Logic
    const nameInput = document.getElementById('name_input');
    const usernameInput = document.getElementById('username_input');
    const usernameStatus = document.getElementById('username_status');
    
    let debounceTimer;
    nameInput?.addEventListener('input', function() {
        if (usernameInput.value === '' || usernameInput.dataset.auto === 'true') {
            clearTimeout(debounceTimer);
            const name = this.value.trim();
            if (!name) {
                usernameInput.value = '';
                usernameStatus?.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                let username = name.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^\w\s]/gi, '')
                    .replace(/\s+/g, '.')
                    .trim();
                
                if (username) {
                    usernameInput.value = username;
                    usernameInput.dataset.auto = 'true';
                    checkUsername(username);
                }
            }, 500);
        }
    });

    usernameInput?.addEventListener('input', function() {
        this.dataset.auto = 'false';
        checkUsername(this.value);
    });

    async function checkUsername(username) {
        if (username.length < 3) return;
        try {
            const response = await fetch(`<?php echo e(route('admin.users.check')); ?>?username=${username}`);
            const data = await response.json();
            if (usernameStatus) {
                usernameStatus.classList.remove('hidden');
                if (data.available) {
                    usernameStatus.innerText = '✓ Available';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 text-green-600 uppercase';
                } else {
                    usernameStatus.innerText = '✕ Taken';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 text-destructive uppercase';
                }
            }
        } catch (error) {
            console.error('Error checking username:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/users/index.blade.php ENDPATH**/ ?>