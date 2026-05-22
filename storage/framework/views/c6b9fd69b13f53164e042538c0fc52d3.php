<div class="space-y-2">
    <?php $__empty_1 = true; $__currentLoopData = $sidebarBooks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $title = $book->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
        $author = $book->fields->where('tag', '100')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? $book->fields->where('tag', '700')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? 'Đang cập nhật tác giả';
    ?>
    <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="flex gap-3 group p-1 rounded-sm hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
        <div class="w-12 h-16 bg-slate-100 rounded-sm flex-shrink-0 overflow-hidden border border-slate-100 shadow-sm relative">
            <?php if($book->cover_image): ?>
                <img src="<?php echo e(asset('storage/' . $book->cover_image)); ?>" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-slate-50">
                    <i class="fas fa-book text-lg text-slate-300"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0 py-0.5">
            <h4 class="text-[11px] font-bold text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-tight mb-1"><?php echo e($title); ?></h4>
            <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest truncate"><?php echo e($author); ?></p>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-[10px] text-slate-400 italic text-center py-4">Chưa có sách mới cập nhật</p>
    <?php endif; ?>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/sidebar-books.blade.php ENDPATH**/ ?>