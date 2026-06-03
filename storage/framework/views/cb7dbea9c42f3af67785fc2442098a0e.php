<?php
    $resources = $resources ?? collect();
    $totalCount = $totalCount ?? 0;
    $currentSubject = $currentSubject ?? '';
?>

<div class="space-y-6 animate-fade-in">
    <?php echo $__env->make('site.pages.partials.oer-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Category Tabs with Icons -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Y HỌC SỨC KHỎE -->
        <a href="<?php echo e(route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'subject' => 'Y học sức khỏe'])); ?>" 
           class="flex flex-col items-center group">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center mb-3 group-hover:shadow-md transition-all">
                <img src="https://img.icons8.com/color/96/medical-heart.png" class="w-10 h-10 md:w-12 md:h-12 object-contain" alt="Y học">
            </div>
            <div class="w-full py-3 px-2 rounded-sm text-center font-black uppercase tracking-wider text-[11px] md:text-xs transition-colors shadow-sm <?php echo e($currentSubject === 'Y học sức khỏe' ? 'bg-[#7B0000] text-white' : 'bg-[#7B0000] text-white hover:bg-[#5A0000]'); ?>">
                <?php echo e(__('Y học sức khỏe')); ?>

            </div>
        </a>

        <!-- KINH TẾ -->
        <a href="<?php echo e(route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'subject' => 'Kinh tế'])); ?>" 
           class="flex flex-col items-center group">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center mb-3 group-hover:shadow-md transition-all">
                <img src="https://img.icons8.com/color/96/globe--v1.png" class="w-10 h-10 md:w-12 md:h-12 object-contain" alt="Kinh tế">
            </div>
            <div class="w-full py-3 px-2 rounded-sm text-center font-black uppercase tracking-wider text-[11px] md:text-xs transition-colors shadow-sm <?php echo e($currentSubject === 'Kinh tế' ? 'bg-[#7B0000] text-white' : 'bg-[#7B0000] text-white hover:bg-[#5A0000]'); ?>">
                <?php echo e(__('Kinh tế')); ?>

            </div>
        </a>

        <!-- LUẬT -->
        <a href="<?php echo e(route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'subject' => 'Luật'])); ?>" 
           class="flex flex-col items-center group">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center mb-3 group-hover:shadow-md transition-all">
                <img src="https://img.icons8.com/color/96/scales.png" class="w-10 h-10 md:w-12 md:h-12 object-contain" alt="Luật">
            </div>
            <div class="w-full py-3 px-2 rounded-sm text-center font-black uppercase tracking-wider text-[11px] md:text-xs transition-colors shadow-sm <?php echo e($currentSubject === 'Luật' ? 'bg-[#7B0000] text-white' : 'bg-[#7B0000] text-white hover:bg-[#5A0000]'); ?>">
                <?php echo e(__('Luật')); ?>

            </div>
        </a>

        <!-- KHÁC -->
        <a href="<?php echo e(route('site.page', ['code' => 'tai-nguyen-giao-duc-mo', 'subject' => 'Khác'])); ?>" 
           class="flex flex-col items-center group">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center mb-3 group-hover:shadow-md transition-all">
                <img src="https://img.icons8.com/color/96/monitor--v1.png" class="w-10 h-10 md:w-12 md:h-12 object-contain" alt="Khác">
            </div>
            <div class="w-full py-3 px-2 rounded-sm text-center font-black uppercase tracking-wider text-[11px] md:text-xs transition-colors shadow-sm <?php echo e($currentSubject === 'Khác' ? 'bg-[#7B0000] text-white' : 'bg-[#7B0000] text-white hover:bg-[#5A0000]'); ?>">
                <?php echo e(__('Khác')); ?>

            </div>
        </a>
    </div>

    <!-- Search Box -->
    <div class="bg-white border border-slate-200 rounded-sm overflow-hidden shadow-sm">
        <form id="oer-search-form" action="<?php echo e(route('site.page', 'tai-nguyen-giao-duc-mo')); ?>" method="GET" class="flex items-center">
            <?php if($currentSubject): ?>
                <input type="hidden" name="subject" value="<?php echo e($currentSubject); ?>">
            <?php endif; ?>
            <input type="text" name="q" id="oer-search-input" value="<?php echo e($keyword ?? ''); ?>"
                   placeholder="<?php echo e(__('Nhập từ khóa cần tìm')); ?>" 
                   class="flex-1 py-3 px-4 text-sm text-slate-600 outline-none placeholder:text-slate-400">
            <button type="submit" class="px-6 py-3 bg-[#7B0000] text-white hover:bg-[#5A0000] transition-colors">
                <i class="fas fa-search text-sm"></i>
            </button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        const searchInput = document.getElementById('oer-search-input');
        const searchForm = document.getElementById('oer-search-form');

        if (searchInput && searchForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 1000); // 1 second debounce
            });

            // Focus và đưa con trỏ về cuối văn bản nếu đang có từ khóa tìm kiếm
            if (searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
    });
    </script>

    <!-- Results List -->
    <div class="bg-white border border-slate-200 rounded-sm shadow-sm overflow-hidden divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="p-6 flex flex-col md:flex-row gap-6 group hover:bg-slate-50/50 transition-colors">
            <!-- Left: Metadata Content -->
            <div class="flex-1 space-y-3">
                <a href="<?php echo e(route('site.oer.show', $item->id)); ?>" class="block">
                    <h3 class="text-sm md:text-base font-bold text-[#0056b3] hover:underline leading-snug">
                        <?php echo e($item->title); ?>

                    </h3>
                </a>
                
                <div class="space-y-1.5 text-[12px] text-slate-700">
                    <div class="flex gap-1 flex-wrap">
                        <span class="font-bold"><?php echo e(__('Tác giả')); ?>:</span>
                        <span><?php echo e($item->author ?: __('Chưa rõ')); ?></span>
                    </div>
                    <div class="flex gap-1 flex-wrap">
                        <span class="font-bold"><?php echo e(__('Thông tin xuất bản')); ?>:</span>
                        <span><?php echo e($item->publisher ?: '...'); ?> <?php echo e($item->publish_year ? '('.$item->publish_year.')' : ''); ?></span>
                    </div>
                    <div class="flex gap-1 flex-wrap">
                        <span class="font-bold"><?php echo e(__('Nguồn đóng góp')); ?>:</span>
                        <?php if($item->external_link): ?>
                            <a href="<?php echo e($item->external_link); ?>" target="_blank" class="text-[#0056b3] hover:underline break-all"><?php echo e($item->external_link); ?></a>
                        <?php else: ?>
                            <span><?php echo e($item->source ?: '...'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-1 items-center">
                        <span class="font-bold"><?php echo e(__('Giấy phép')); ?>:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#0056b3]"><?php echo e($item->license ?: 'CC BY-NC'); ?></span>
                            <?php if($item->license_url): ?>
                                <a href="<?php echo e($item->license_url); ?>" target="_blank" class="text-[#0056b3]"><i class="fas fa-external-link-alt text-[10px]"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <span class="font-bold"><?php echo e(__('Ngày đăng')); ?>:</span>
                        <span><?php echo e($item->created_at->format('d/m/Y')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right: Thumbnail -->
            <div class="w-full md:w-32 lg:w-40 flex-shrink-0">
                <a href="<?php echo e(route('site.oer.show', $item->id)); ?>" class="block aspect-[3/4] rounded-sm border border-slate-200 overflow-hidden shadow-sm group-hover:shadow-md transition-all">
                    <img src="<?php echo e($item->thumbnail_url); ?>" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         alt="<?php echo e($item->title); ?>">
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="py-20 text-center">
            <div class="flex flex-col items-center justify-center opacity-20">
                <i class="fas fa-database text-5xl mb-4"></i>
                <p class="text-sm font-bold uppercase tracking-widest"><?php echo e(__('Không tìm thấy tài nguyên nào')); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($resources->hasPages()): ?>
    <div class="pt-4">
        <?php echo e($resources->links('site.partials.pagination-compact')); ?>

    </div>
    <?php endif; ?>
</div>

<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/oer-list-content.blade.php ENDPATH**/ ?>