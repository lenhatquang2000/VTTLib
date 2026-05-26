<?php $__env->startSection('content'); ?>
<div class="space-y-4 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-card p-3 rounded-md border border-border gap-3">
        <div>
            <h2 class="text-xl font-bold text-foreground tracking-tight"><?php echo e(__('News_Management')); ?></h2>
            <p class="text-xs text-muted-foreground mt-0.5"><?php echo e(__('Manage_News_Instruction')); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="autoGenerateNews()" 
               class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded bg-muted text-muted-foreground hover:bg-muted/80 border border-border active:scale-95 transition-all text-xs font-semibold cursor-pointer">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <?php echo e(__('Auto_Generate')); ?>

            </button>
            <a href="<?php echo e(route('admin.news.create')); ?>" 
               class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded bg-primary text-primary-foreground hover:bg-primary/90 active:scale-95 transition-all text-xs font-semibold shadow-sm cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <?php echo e(__('Create_News')); ?>

            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        <!-- Total News -->
        <div class="bg-card p-3 rounded-md border border-border group hover:border-primary transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded bg-primary/10 flex items-center justify-center text-primary group-hover:scale-105 transition-transform">
                    <i data-lucide="newspaper" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Total')); ?></p>
                    <p class="text-lg font-bold text-foreground"><?php echo e($stats['total']); ?></p>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-card p-3 rounded-md border border-border group hover:border-emerald-500 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Published')); ?></p>
                    <p class="text-lg font-bold text-foreground"><?php echo e($stats['published']); ?></p>
                </div>
            </div>
        </div>

        <!-- Drafts -->
        <div class="bg-card p-3 rounded-md border border-border group hover:border-muted-foreground transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded bg-muted flex items-center justify-center text-muted-foreground group-hover:scale-105 transition-transform">
                    <i data-lucide="file-edit" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Draft')); ?></p>
                    <p class="text-lg font-bold text-foreground"><?php echo e($stats['draft']); ?></p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-card p-3 rounded-md border border-border group hover:border-amber-500 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Pending')); ?></p>
                    <p class="text-lg font-bold text-foreground"><?php echo e($stats['pending']); ?></p>
                </div>
            </div>
        </div>

        <!-- Featured -->
        <div class="bg-card p-3 rounded-md border border-border group hover:border-purple-500 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded bg-purple-500/10 flex items-center justify-center text-purple-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="star" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Featured')); ?></p>
                    <p class="text-lg font-bold text-foreground"><?php echo e($stats['featured']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List Section -->
    <div class="bg-card rounded-md border border-border overflow-hidden shadow-sm">
        <!-- Toolbar / Filters -->
        <div class="p-3 border-b border-border bg-muted/30">
            <form method="GET" class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-3 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative min-w-[280px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted-foreground">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="w-full pl-9 pr-3 py-1.5 bg-background border border-border rounded text-sm focus:ring-1 focus:ring-primary outline-none transition-all" 
                               placeholder="<?php echo e(__('Search_Placeholder')); ?>">
                    </div>

                    <!-- Status Filter -->
                    <div class="min-w-[140px]">
                        <select name="status" class="w-full px-3 py-1.5 bg-background border border-border rounded text-sm focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                            <option value=""><?php echo e(__('All_Status')); ?></option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>><?php echo e(__('Draft')); ?></option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('Pending')); ?></option>
                            <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>><?php echo e(__('Published')); ?></option>
                            <option value="archived" <?php echo e(request('status') == 'archived' ? 'selected' : ''); ?>><?php echo e(__('Archived')); ?></option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="min-w-[160px]">
                        <select name="category_id" class="w-full px-3 py-1.5 bg-background border border-border rounded text-sm focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                            <option value=""><?php echo e(__('All_Categories')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded bg-primary text-primary-foreground hover:bg-primary/90 active:scale-95 transition-all text-xs font-semibold shadow-sm cursor-pointer">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <?php echo e(__('Filter')); ?>

                    </button>
                    <?php if(request()->anyFilled(['search', 'status', 'category_id'])): ?>
                        <a href="<?php echo e(route('admin.news.index')); ?>" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded bg-muted text-muted-foreground hover:bg-muted/80 border border-border active:scale-95 transition-all text-xs font-semibold cursor-pointer">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <?php echo e(__('Clear')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bulk Actions & Category Quick Filter -->
        <div class="p-3 border-b border-border flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center px-2 py-1.5 bg-muted rounded border border-border">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-border text-primary focus:ring-0 w-3.5 h-3.5 cursor-pointer">
                    <label for="selectAll" class="ml-2 text-[10px] font-bold text-muted-foreground uppercase tracking-widest cursor-pointer"><?php echo e(__('Select_All')); ?></label>
                </div>

                <div class="h-6 w-[1px] bg-border"></div>

                <select id="bulkAction" onchange="performBulkAction()" class="bg-transparent border-none text-[10px] font-bold text-primary uppercase tracking-widest focus:ring-0 cursor-pointer p-0 pr-6 !w-auto !h-auto !py-1" style="width: auto !important; border: none !important; background-color: transparent !important;">
                    <option value=""><?php echo e(__('Hành động')); ?></option>
                    <option value="publish"><?php echo e(__('Publish')); ?></option>
                    <option value="archive"><?php echo e(__('Archive')); ?></option>
                    <option value="delete"><?php echo e(__('Delete')); ?></option>
                    <option value="feature"><?php echo e(__('Feature')); ?></option>
                    <option value="unfeature"><?php echo e(__('Unfeature')); ?></option>
                </select>

                <div class="h-6 w-[1px] bg-border hidden sm:block"></div>

                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="<?php echo e(route('admin.news.index', request()->except('category_id'))); ?>" 
                       class="px-2 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wider transition-all <?php echo e(!request('category_id') ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80 border border-border'); ?>">
                        <?php echo e(__('Tất cả')); ?>

                    </a>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('admin.news.index', array_merge(request()->query(), ['category_id' => $category->id]))); ?>" 
                           class="px-2 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wider transition-all <?php echo e(request('category_id') == $category->id ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80 border border-border'); ?>">
                            <?php echo e($category->name); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <div class="text-[10px] font-bold text-muted-foreground tracking-widest uppercase">
                <?php echo e(__('Found :count records', ['count' => $news->total()])); ?>

            </div>
        </div>

        <!-- Table Content -->
        <?php if($news->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-muted/50 text-[10px] font-bold text-muted-foreground uppercase tracking-widest border-b border-border">
                            <th class="px-3 py-2 w-10 text-center">
                                <i data-lucide="grip-vertical" class="w-3.5 h-3.5 mx-auto"></i>
                            </th>
                            <th class="px-3 py-2 w-10"></th>
                            <th class="px-3 py-2"><?php echo e(__('News_Content')); ?></th>
                            <th class="px-3 py-2"><?php echo e(__('Category')); ?></th>
                            <th class="px-3 py-2"><?php echo e(__('Author')); ?></th>
                            <th class="px-3 py-2"><?php echo e(__('Status')); ?></th>
                            <th class="px-3 py-2 text-center"><?php echo e(__('Stats')); ?></th>
                            <th class="px-3 py-2 text-right"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border" id="news-table-body">
                        <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-row-hover group cursor-pointer" 
                                data-id="<?php echo e($item->id); ?>"
                                onclick="window.location.href='<?php echo e(route('admin.news.edit', $item)); ?>'">
                                <td class="px-3 py-2 text-center cursor-move handle" onclick="event.stopPropagation()">
                                    <i data-lucide="grip-vertical" class="w-4 h-4 text-muted-foreground/30 group-hover:text-primary transition-colors"></i>
                                </td>
                                <td class="px-3 py-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="news-checkbox rounded border-border text-primary focus:ring-0 w-3.5 h-3.5 cursor-pointer" 
                                           value="<?php echo e($item->id); ?>"
                                           onchange="updateSelection(this)">
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-10 h-10 shrink-0 rounded bg-muted border border-border overflow-hidden">
                                            <?php if($item->featured_image): ?>
                                                <img src="<?php echo e($item->featured_image); ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-muted-foreground/40">
                                                    <i data-lucide="image" class="w-5 h-5"></i>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($item->is_featured): ?>
                                                <div class="absolute top-0 right-0 w-4 h-4 bg-purple-500 text-white flex items-center justify-center text-[8px] rounded-bl-sm shadow-sm">
                                                    <i data-lucide="star" class="w-2.5 h-2.5 fill-current"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="max-w-[280px]">
                                            <div class="text-sm font-bold text-foreground truncate group-hover:text-primary transition-colors"><?php echo e($item->title); ?></div>
                                            <div class="text-[10px] text-muted-foreground mt-0.5 font-mono">
                                                <i data-lucide="calendar" class="w-3 h-3 inline mr-0.5"></i> <?php echo e($item->formatted_published_at ?: __('Chưa đăng')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    <?php if($item->category): ?>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm bg-primary/10 text-primary text-[9px] font-bold uppercase tracking-wider border border-primary/20">
                                            <?php echo e($item->category->name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted-foreground/30 text-[10px]">---</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-2 text-xs text-muted-foreground font-medium">
                                    <?php echo e($item->author->name ?? '-'); ?>

                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-widest bg-<?php echo e($item->status_color); ?>-500/10 text-<?php echo e($item->status_color); ?>-500 border border-<?php echo e($item->status_color); ?>-500/20">
                                        <?php echo e($item->status_label); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] font-bold text-foreground"><?php echo e($item->view_count); ?></span>
                                        <span class="text-[8px] text-muted-foreground uppercase tracking-tighter"><?php echo e(__('Views')); ?></span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?php echo e(route('admin.news.edit', $item)); ?>" 
                                           class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-muted text-muted-foreground border border-border transition-all active:scale-90" title="Sửa">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <button onclick="toggleFeatured(<?php echo e($item->id); ?>)" 
                                                class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-muted text-muted-foreground border border-border transition-all active:scale-90 <?php echo e($item->is_featured ? 'text-purple-500 bg-purple-500/10 border-purple-500/20' : ''); ?>" title="Nổi bật">
                                            <i data-lucide="star" class="w-3.5 h-3.5 <?php echo e($item->is_featured ? 'fill-current' : ''); ?>"></i>
                                        </button>
                                        <form action="<?php echo e(route('admin.news.destroy', $item)); ?>" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')"
                                              class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-destructive hover:text-destructive-foreground text-muted-foreground border border-border transition-all active:scale-90 cursor-pointer" title="Xóa">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-3 border-t border-border bg-muted/20">
                <?php echo e($news->links()); ?>

            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-20 bg-card border-t border-border">
                <div class="w-16 h-16 rounded-lg bg-muted flex items-center justify-center text-muted-foreground mx-auto mb-4">
                    <i data-lucide="newspaper" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-foreground mb-1 uppercase tracking-tight"><?php echo e(__('No_News_Found')); ?></h3>
                <p class="text-xs text-muted-foreground mb-6 max-w-xs mx-auto"><?php echo e(__('No_News_Instruction')); ?></p>
                <a href="<?php echo e(route('admin.news.create')); ?>" 
                   class="btn-compact-primary px-6 py-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> <?php echo e(__('Create_First_News')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let selectedNews = new Set();

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.news-checkbox');
    const selectAll = document.getElementById('selectAll').checked;
    
    selectedNews.clear();
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
        if (selectAll) selectedNews.add(checkbox.value);
    });
}

function updateSelection(checkbox) {
    if (checkbox.checked) {
        selectedNews.add(checkbox.value);
    } else {
        selectedNews.delete(checkbox.value);
        document.getElementById('selectAll').checked = false;
    }
}

function performBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action || selectedNews.size === 0) {
        if (action) Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một bài viết', 'info');
        return;
    }
    
    if (action === 'delete') {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Bạn có chắc muốn xóa ${selectedNews.size} bài viết đã chọn?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'hsl(var(--destructive))',
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) executeBulkAction(action);
        });
    } else {
        executeBulkAction(action);
    }
}

function executeBulkAction(action) {
    const newsIds = Array.from(selectedNews);
    fetch('<?php echo e(route("admin.news.bulk-action")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ action: action, news_ids: newsIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Lỗi', data.message, 'error');
        }
    });
}

function toggleFeatured(newsId) {
    fetch(`/topsecret/news/${newsId}/toggle-featured`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function autoGenerateNews() {
    Swal.fire({
        title: 'Tự động tạo tin tức?',
        text: "Hệ thống sẽ tự động tạo một bài viết mẫu cho bạn.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'hsl(var(--primary))',
        confirmButtonText: 'Tạo ngay',
        cancelButtonText: 'Hủy',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('<?php echo e(route("admin.news.auto-generate")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(response.statusText);
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value.success) {
            Swal.fire('Thành công', result.value.message, 'success').then(() => {
                window.location.href = result.value.redirect;
            });
        }
    });
}

// Initialize Sortable and Lucide
document.addEventListener('DOMContentLoaded', function() {
    // Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const el = document.getElementById('news-table-body');
    if (el) {
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            ghostClass: 'bg-primary/5',
            onEnd: function() {
                const ids = Array.from(el.querySelectorAll('tr')).map(tr => tr.dataset.id);
                updateOrder(ids);
            }
        });
    }
});

function updateOrder(ids) {
    fetch('<?php echo e(route("admin.news.reorder")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Order updated');
        } else {
            Swal.fire('Lỗi', data.message, 'error');
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/news/index.blade.php ENDPATH**/ ?>