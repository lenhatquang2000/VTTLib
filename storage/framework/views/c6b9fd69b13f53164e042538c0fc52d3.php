<div class="space-y-4">
    <?php $__empty_1 = true; $__currentLoopData = $sidebarBooks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $title = $book->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
        $author = $book->fields->where('tag', '100')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? $book->fields->where('tag', '700')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? 'Đang cập nhật tác giả';
    ?>
    <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="flex gap-4 group p-2 rounded-2xl hover:bg-slate-50 transition-all">
        <div class="w-16 h-20 bg-slate-100 rounded-xl flex-shrink-0 overflow-hidden border border-slate-100 shadow-sm">
            <?php if($book->cover_image): ?>
                <img src="<?php echo e(asset('storage/' . $book->cover_image)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <i class="fas fa-book text-xl"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0 py-1">
            <h4 class="text-xs font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-snug mb-1"><?php echo e($title); ?></h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate"><?php echo e($author); ?></p>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-xs text-slate-400 italic text-center py-8">Chưa có sách mới cập nhật</p>
    <?php endif; ?>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/sidebar-books.blade.php ENDPATH**/ ?>