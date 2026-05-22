<?php $__env->startSection('content'); ?>
<div class="space-y-8 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-slate-100 tracking-tight"><?php echo e(__('Announcement_Management')); ?></h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?php echo e(__('Manage_Announcements_Instruction')); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="autoGenerateAnnouncement()" 
               class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-lg shadow-amber-200 dark:shadow-none">
                <i class="fas fa-magic mr-2"></i>
                <?php echo e(__('Auto_Generate')); ?>

            </button>
            <a href="<?php echo e(route('admin.news.create', ['category_id' => $announcementCategory->id ?? ''])); ?>" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <?php echo e(__('Create_Announcement')); ?>

            </a>
        </div>
    </div>

    <!-- Main List Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <!-- Toolbar / Filters -->
        <div class="p-6 border-b border-gray-50 dark:border-slate-800">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all" 
                               placeholder="<?php echo e(__('Search_Announcements')); ?>">
                    </div>

                    <!-- Status Filter -->
                    <div class="min-w-[160px]">
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                            <option value=""><?php echo e(__('All_Status')); ?></option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>><?php echo e(__('Draft')); ?></option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('Pending')); ?></option>
                            <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>><?php echo e(__('Published')); ?></option>
                            <option value="archived" <?php echo e(request('status') == 'archived' ? 'selected' : ''); ?>><?php echo e(__('Archived')); ?></option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-6 py-2.5 bg-gray-800 dark:bg-slate-700 text-white rounded-xl text-sm font-bold hover:bg-gray-900 transition-all">
                        <?php echo e(__('Filter')); ?>

                    </button>
                    <?php if(request()->anyFilled(['search', 'status'])): ?>
                        <a href="<?php echo e(route('admin.news.announcements')); ?>" class="flex-1 lg:flex-none px-6 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all text-center">
                            <?php echo e(__('Clear')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bulk Actions & Table Container -->
        <div class="p-6 bg-gray-50/30 dark:bg-slate-900/50 border-b border-gray-50 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center px-3 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded text-indigo-600 focus:ring-0 w-4 h-4 cursor-pointer">
                    <label for="selectAll" class="ml-2.5 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest cursor-pointer"><?php echo e(__('Select_All')); ?></label>
                </div>

                <div class="h-8 w-[1px] bg-gray-200 dark:bg-slate-700"></div>

                <select id="bulkAction" onchange="performBulkAction()" class="bg-transparent border-none text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest focus:ring-0 cursor-pointer">
                    <option value=""><?php echo e(__('Hành động')); ?></option>
                    <option value="publish"><?php echo e(__('Publish')); ?></option>
                    <option value="archive"><?php echo e(__('Archive')); ?></option>
                    <option value="delete"><?php echo e(__('Delete')); ?></option>
                </select>
            </div>
            
            <div class="text-xs font-bold text-gray-400 dark:text-slate-500 tracking-widest uppercase">
                <?php echo e(__('Found :count announcements', ['count' => $news->total()])); ?>

            </div>
        </div>

        <!-- Content Area -->
        <?php if($news->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-800/50 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 w-10 text-center">
                                <i class="fas fa-arrows-alt-v"></i>
                            </th>
                            <th class="px-6 py-4 w-10"></th>
                            <th class="px-6 py-4"><?php echo e(__('Announcement_Title')); ?></th>
                            <th class="px-6 py-4"><?php echo e(__('Author')); ?></th>
                            <th class="px-6 py-4"><?php echo e(__('Status')); ?></th>
                            <th class="px-6 py-4 text-center"><?php echo e(__('Views')); ?></th>
                            <th class="px-6 py-4 text-right"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800" id="news-table-body">
                        <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer" 
                                data-id="<?php echo e($item->id); ?>"
                                onclick="window.location.href='<?php echo e(route('admin.news.edit', $item)); ?>'">
                                <td class="px-6 py-4 text-center cursor-move handle" onclick="event.stopPropagation()">
                                    <i class="fas fa-grip-vertical text-gray-300 group-hover:text-indigo-400 transition-colors"></i>
                                </td>
                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="news-checkbox rounded text-indigo-600 focus:ring-0 w-4 h-4 cursor-pointer" value="<?php echo e($item->id); ?>">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative w-12 h-12 shrink-0 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm">
                                            <?php if($item->featured_image): ?>
                                                <img src="<?php echo e($item->featured_image); ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i class="fas fa-bullhorn text-lg"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="max-w-[400px]">
                                            <div class="font-bold text-gray-800 dark:text-slate-100 truncate group-hover:text-indigo-600 transition-colors"><?php echo e($item->title); ?></div>
                                            <div class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 font-mono">
                                                <i class="far fa-calendar-alt mr-1"></i> <?php echo e($item->formatted_published_at ?: __('Chưa đăng')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold">
                                            <?php echo e(substr($item->author->name ?? 'A', 0, 1)); ?>

                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 dark:text-slate-400"><?php echo e($item->author->name ?? '-'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-<?php echo e($item->status_color); ?>-50 dark:bg-<?php echo e($item->status_color); ?>-900/30 text-<?php echo e($item->status_color); ?>-600 dark:text-<?php echo e($item->status_color); ?>-400 border border-<?php echo e($item->status_color); ?>-100 dark:border-<?php echo e($item->status_color); ?>-800/50">
                                        <?php echo e($item->status_label); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[10px] font-black text-gray-700 dark:text-slate-200"><?php echo e($item->view_count); ?></span>
                                </td>
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="<?php echo e(route('admin.news.edit', $item)); ?>" 
                                           class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.news.destroy', $item)); ?>" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này?')"
                                              class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
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
            <div class="p-6 border-t border-gray-50 dark:border-slate-800">
                <?php echo e($news->links()); ?>

            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-24 bg-white dark:bg-slate-900 border-t border-gray-50 dark:border-slate-800">
                <div class="relative inline-block mb-6">
                    <div class="w-24 h-24 rounded-3xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-400 dark:text-indigo-600 animate-pulse">
                        <i class="fas fa-bullhorn text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-gray-800 dark:text-slate-100 mb-2 uppercase tracking-tight"><?php echo e(__('No_Announcements_Found')); ?></h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 max-w-sm mx-auto text-sm leading-relaxed"><?php echo e(__('No_Announcements_Instruction')); ?></p>
                <a href="<?php echo e(route('admin.news.create', ['category_id' => $announcementCategory->id ?? ''])); ?>" 
                   class="inline-flex items-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-black transition-all shadow-xl shadow-indigo-200 dark:shadow-none hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i> <?php echo e(__('Create_First_Announcement')); ?>

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
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
        const newsId = checkbox.value;
        if (selectAll) selectedNews.add(newsId);
        else selectedNews.delete(newsId);
    });
}

function performBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action || selectedNews.size === 0) {
        if (action) Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một thông báo', 'info');
        return;
    }
    
    if (action === 'delete') {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Bạn có chắc muốn xóa ${selectedNews.size} thông báo đã chọn?`,
            icon: 'warning',
            confirmButtonColor: '#e11d48',
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

// Initialize Sortable
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('news-table-body');
    if (el) {
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            ghostClass: 'bg-indigo-50',
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

function autoGenerateAnnouncement() {
    Swal.fire({
        title: 'Tự động tạo thông báo?',
        text: "Hệ thống sẽ tự động tạo một thông báo mẫu cho bạn.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Tạo ngay',
        cancelButtonText: 'Hủy',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('<?php echo e(route("admin.news.auto-generate")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    category_id: '<?php echo e($announcementCategory->id ?? ""); ?>'
                })
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/news/announcements.blade.php ENDPATH**/ ?>