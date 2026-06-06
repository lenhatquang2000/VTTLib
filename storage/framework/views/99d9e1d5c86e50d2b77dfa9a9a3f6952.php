<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight"><?php echo e(__('OER Management')); ?></h1>
            <p class="text-sm text-muted-foreground"><?php echo e(__('Manage open educational resources.')); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.oer.contributions')); ?>" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:border-border/80 hover:text-foreground active:bg-muted/60" title="<?php echo e(__('Review Contributions')); ?>">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span class="hidden sm:inline"><?php echo e(__('Review Contributions')); ?></span>
                <span class="sm:hidden"><?php echo e(__('Review')); ?></span>
            </a>
            <a href="<?php echo e(route('admin.oer.create')); ?>" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80" title="<?php echo e(__('New Resource')); ?>">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline"><?php echo e(__('New Resource')); ?></span>
                <span class="sm:hidden"><?php echo e(__('New')); ?></span>
            </a>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <a href="<?php echo e(route('admin.oer.index')); ?>" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all bg-card text-primary shadow-sm">
            <?php echo e(__('Resources')); ?>

        </a>
        <a href="<?php echo e(route('admin.oer.contributions')); ?>" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all text-muted-foreground hover:text-foreground">
            <?php echo e(__('Contributions')); ?>

        </a>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="<?php echo e(route('admin.oer.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Search resources..." 
                        class="block w-full pl-9 pr-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                
                <!-- Type Filter -->
                <select name="type" class="h-9 w-full sm:w-40 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value=""><?php echo e(__('All Types')); ?></option>
                    <option value="document" <?php echo e(request('type') == 'document' ? 'selected' : ''); ?>>Document</option>
                    <option value="video" <?php echo e(request('type') == 'video' ? 'selected' : ''); ?>>Video</option>
                    <option value="audio" <?php echo e(request('type') == 'audio' ? 'selected' : ''); ?>>Audio</option>
                    <option value="interactive" <?php echo e(request('type') == 'interactive' ? 'selected' : ''); ?>>Interactive</option>
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80">
                        <?php echo e(__('Search')); ?>

                    </button>

                    <?php if(request('search') || request('type')): ?>
                        <a href="<?php echo e(route('admin.oer.index')); ?>" 
                            class="inline-flex items-center justify-center gap-2 px-3 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                            <i data-lucide="x" class="w-4 h-4"></i>
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
                        <th class="py-2 px-3"><?php echo e(__('Title')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Type')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Author')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('License')); ?></th>
                        <th class="py-2 px-3"><?php echo e(__('Status')); ?></th>
                        <th class="py-2 px-3 w-32 text-right"><?php echo e(__('Operations')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-8 rounded bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0 overflow-hidden">
                                        <?php if($resource->cover_path): ?>
                                            <img src="<?php echo e($resource->thumbnail_url); ?>" alt="" class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-foreground leading-tight truncate"><?php echo e($resource->title); ?></div>
                                        <div class="text-[11px] text-muted-foreground leading-tight truncate"><?php echo e($resource->publisher ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border bg-primary/10 text-primary border-primary/20">
                                    <?php echo e($resource->resource_type); ?>

                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <div class="text-sm text-muted-foreground truncate max-w-[150px]">
                                    <?php echo e($resource->author); ?>

                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="text-[11px] text-muted-foreground truncate max-w-[100px]">
                                    <?php echo e($resource->license ?? 'N/A'); ?>

                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <?php
                                    $statusClass = match($resource->status) {
                                        'published' => 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20',
                                        'draft' => 'bg-muted text-muted-foreground border-border',
                                        default => 'bg-primary/10 text-primary border-primary/20'
                                    };
                                    $statusLabel = match($resource->status) {
                                        'published' => 'Published',
                                        'draft' => 'Draft',
                                        default => $resource->status
                                    };
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border <?php echo e($statusClass); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="<?php echo e(route('admin.oer.edit', $resource)); ?>" class="btn-icon-compact" title="<?php echo e(__('Edit')); ?>">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.oer.destroy', $resource)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Delete this resource?')); ?>')" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-icon-danger" title="<?php echo e(__('Delete')); ?>">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                        <i data-lucide="file-x" class="w-6 h-6 text-muted-foreground"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-foreground"><?php echo e(__('No Resources Found')); ?></h4>
                                    <p class="text-muted-foreground text-sm mt-1"><?php echo e(__('Get started by creating your first OER resource.')); ?></p>
                                    <a href="<?php echo e(route('admin.oer.create')); ?>" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80 mt-4">
                                        <?php echo e(__('Create Resource')); ?>

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
                <?php echo e(__('Displaying')); ?> <?php echo e($resources->firstItem() ?? 0); ?> - <?php echo e($resources->lastItem() ?? 0); ?> <?php echo e(__('of')); ?> <?php echo e($resources->total()); ?>

            </div>
            <div>
                <?php echo e($resources->links()); ?>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/oer/index.blade.php ENDPATH**/ ?>