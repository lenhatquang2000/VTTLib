<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-fade-in" 
     x-data="{ 
        sidebarOpen: <?php echo e(request('collapsed') ? 'false' : 'true'); ?>,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            const url = new URL(window.location.href);
            if (!this.sidebarOpen) {
                url.searchParams.set('collapsed', '1');
            } else {
                url.searchParams.delete('collapsed');
            }
            window.history.replaceState({}, '', url);
        }
     }">
    <!-- Compact Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 bg-card p-3 rounded-md border border-border shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center text-primary-foreground shadow-sm">
                <i data-lucide="file-signature" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-0.5">
                    <span><?php echo e(__('Biên mục')); ?></span>
                    <i data-lucide="chevron-right" class="w-3 h-3 opacity-50"></i>
                    <span><?php echo e(__('Tài liệu số')); ?></span>
                </div>
                <h1 class="text-lg font-bold text-foreground tracking-tight"><?php echo e(__('Quản lý Biên mục Tài liệu số')); ?></h1>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <button @click="toggleSidebar()" 
                    class="px-3 py-2 bg-muted hover:bg-muted/80 text-foreground font-medium rounded-md transition-all border border-border flex items-center justify-center text-xs">
                <i x-show="sidebarOpen" data-lucide="indent" class="w-4 h-4"></i>
                <i x-show="!sidebarOpen" data-lucide="outdent" class="w-4 h-4"></i>
                <span class="ml-2" x-text="sidebarOpen ? '<?php echo e(__('Thu gọn')); ?>' : '<?php echo e(__('Mở rộng')); ?>'"></span>
            </button>
            <?php if(request('category_id')): ?>
            <a href="<?php echo e(route('admin.digital-cataloging.create', ['category_id' => request('category_id'), 'collapsed' => request('collapsed')])); ?>" 
               class="flex-1 lg:flex-none px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-md shadow-sm transition-all flex items-center justify-center text-xs group">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                <?php echo e(__('Biên Mục Tài Liệu Số')); ?>

            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <!-- Sidebar: Folders -->
        <div class="transition-all duration-300 ease-in-out" 
             :class="sidebarOpen ? 'w-56 lg:w-64 opacity-100' : 'w-0 opacity-0 overflow-hidden hidden lg:block'"
             x-data="{ showAddCategory: false }">
            <div class="bg-card border border-border rounded-md p-3 shadow-sm sticky top-4 transition-colors duration-200">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider flex items-center">
                        <i data-lucide="folder-tree" class="w-4 h-4 mr-2 text-primary"></i>
                        <?php echo e(__('Phân mục tài liệu')); ?>

                    </h3>
                    <button @click="showAddCategory = true" class="w-6 h-6 rounded bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground transition-all flex items-center justify-center border border-primary/20" title="<?php echo e(__('Thêm phân mục mới')); ?>">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </button>
                </div>
                
                <nav class="space-y-1">
                    <a href="<?php echo e(route('admin.digital-cataloging.index', ['collapsed' => request('collapsed')])); ?>" 
                       class="flex items-center justify-between px-3 py-2 rounded-md transition-all text-xs <?php echo e(!request('category_id') ? 'bg-primary text-primary-foreground font-bold' : 'text-muted-foreground hover:bg-muted hover:text-foreground font-medium'); ?>">
                        <div class="flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span><?php echo e(__('Tất cả tài liệu')); ?></span>
                        </div>
                    </a>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.digital-cataloging.index', ['category_id' => $category->id, 'collapsed' => request('collapsed')])); ?>" 
                       class="flex items-center justify-between px-3 py-2 rounded-md transition-all text-xs <?php echo e(request('category_id') == $category->id ? 'bg-primary text-primary-foreground font-bold' : 'text-muted-foreground hover:bg-muted hover:text-foreground font-medium'); ?>">
                        <div class="flex items-center gap-2">
                            <?php if(request('category_id') == $category->id): ?>
                                <i data-lucide="folder-open" class="w-4 h-4"></i>
                            <?php else: ?>
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            <?php endif; ?>
                            <span class="truncate"><?php echo e($category->folder_name); ?></span>
                        </div>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-sm <?php echo e(request('category_id') == $category->id ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground border border-border'); ?>">
                            <?php echo e($category->resources_count); ?>

                        </span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </nav>
            </div>
            
            <!-- Modal Thêm Phân Mục -->
            <div x-show="showAddCategory" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @keydown.escape.window="showAddCategory = false"
                 style="display: none;">
                <div class="bg-card border border-border rounded-md w-full max-w-[320px] p-4 shadow-lg relative" @click.away="showAddCategory = false">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-bold text-foreground tracking-tight"><?php echo e(__('Thêm Phân Mục')); ?></h4>
                        <button @click="showAddCategory = false" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-muted text-muted-foreground transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <form action="<?php echo e(route('admin.digital-cataloging.category.store')); ?>" method="POST" class="space-y-3">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider px-1"><?php echo e(__('Mã phân mục')); ?></label>
                            <input type="text" name="folder_code" required placeholder="BG-VTTU"
                                   class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider px-1"><?php echo e(__('Tên phân mục')); ?></label>
                            <input type="text" name="folder_name" required placeholder="Bài Giảng VTTU"
                                   class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                        </div>
                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="showAddCategory = false" 
                                    class="flex-1 py-2 bg-muted hover:bg-muted/80 text-foreground font-medium rounded-md transition-all border border-border text-xs">
                                <?php echo e(__('Hủy')); ?>

                            </button>
                            <button type="submit" 
                                    class="flex-1 py-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-md shadow-sm transition-all text-xs">
                                <?php echo e(__('Lưu')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'w-full lg:w-3/4' : 'w-full'">
            <!-- Search & Filters -->
            <div class="bg-card border border-border rounded-md p-3 shadow-sm mb-4 transition-colors duration-200">
                <form action="<?php echo e(route('admin.digital-cataloging.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="hidden" name="category_id" value="<?php echo e(request('category_id')); ?>">
                    <input type="hidden" name="collapsed" value="<?php echo e(request('collapsed')); ?>">
                    <div class="flex-1 relative group">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('Tìm kiếm tiêu đề, mã số...')); ?>"
                               class="w-full pl-9 pr-4 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-md transition-all shadow-sm flex items-center justify-center text-xs">
                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                        <?php echo e(__('Lọc')); ?>

                    </button>
                </form>
            </div>

            <!-- Table Container -->
            <div class="bg-card overflow-hidden border border-border shadow-sm rounded-md transition-colors duration-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-muted/50 border-b border-border text-foreground transition-colors duration-200">
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo e(__('Thông tin tài liệu')); ?></th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo e(__('Định danh')); ?></th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo e(__('Tác giả')); ?></th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo e(__('Trạng thái')); ?></th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground text-right"><?php echo e(__('Thao tác')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border transition-colors duration-200">
                            <?php $__empty_1 = true; $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-muted/50 transition-all group">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-8 h-8 shrink-0 bg-muted rounded flex items-center justify-center text-muted-foreground border border-border shadow-sm">
                                            <?php
                                                $icon = 'file-text';
                                                $format = strtolower($res->format);
                                                if($format == 'pdf') $icon = 'file-digit';
                                                elseif(in_array($format, ['doc','docx'])) $icon = 'file-text';
                                                elseif(in_array($format, ['mp4','avi','mov'])) $icon = 'video';
                                                elseif(in_array($format, ['jpg','png','jpeg'])) $icon = 'image';
                                            ?>
                                            <i data-lucide="<?php echo e($icon); ?>" class="w-4 h-4"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-foreground truncate max-w-[200px] mb-0.5"><?php echo e($res->title); ?></div>
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="paperclip" class="w-3 h-3 text-muted-foreground"></i>
                                                <span class="text-[10px] font-medium text-primary truncate max-w-[150px]"><?php echo e($res->file_name); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-foreground font-mono"><?php echo e($res->identifier); ?></span>
                                        <span class="text-[9px] text-muted-foreground font-medium uppercase tracking-tight mt-0.5"><?php echo e($res->resource_type); ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap gap-1 items-center">
                                        <?php $authors = is_array($res->authors) ? $res->authors : [$res->authors]; ?>
                                        <?php $__currentLoopData = array_slice($authors, 0, 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-[11px] font-medium text-foreground"><?php echo e($author); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($authors) > 1): ?>
                                            <span class="px-1 py-0.5 rounded-sm bg-muted text-[9px] text-primary font-bold border border-border">+<?php echo e(count($authors)-1); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if($res->status === 'published'): ?>
                                        <div class="inline-flex items-center px-2 py-0.5 bg-emerald-500/10 text-emerald-600 text-[10px] font-bold rounded-sm border border-emerald-500/20">
                                            <span class="w-1 h-1 bg-emerald-500 rounded-full mr-1.5"></span>
                                            <?php echo e(__('Đã ban hành')); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center px-2 py-0.5 bg-amber-500/10 text-amber-600 text-[10px] font-bold rounded-sm border border-amber-500/20">
                                            <span class="w-1 h-1 bg-amber-500 rounded-full mr-1.5"></span>
                                            <?php echo e(__('Chờ duyệt')); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <button class="w-7 h-7 rounded bg-background hover:bg-primary hover:text-primary-foreground text-muted-foreground transition-all flex items-center justify-center border border-border shadow-sm group/btn" title="Xem">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button class="w-7 h-7 rounded bg-background hover:bg-primary hover:text-primary-foreground text-muted-foreground transition-all flex items-center justify-center border border-border shadow-sm group/btn" title="Biên tập">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button class="w-7 h-7 rounded bg-background hover:bg-destructive hover:text-destructive-foreground text-muted-foreground transition-all flex items-center justify-center border border-border shadow-sm group/btn" title="Xóa">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-muted rounded-md flex items-center justify-center text-muted-foreground mb-3 border border-border border-dashed">
                                            <i data-lucide="folder-open" class="w-6 h-6 opacity-20"></i>
                                        </div>
                                        <h3 class="text-sm font-bold text-foreground mb-1"><?php echo e(__('Không tìm thấy tài liệu')); ?></h3>
                                        <p class="text-[11px] text-muted-foreground max-w-[200px] mx-auto">Thử thay đổi điều kiện lọc hoặc chọn một phân mục khác.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($resources->hasPages()): ?>
                <div class="px-4 py-3 bg-muted/30 border-t border-border pagination-admin">
                    <?php echo e($resources->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/digital_cataloging/index.blade.php ENDPATH**/ ?>