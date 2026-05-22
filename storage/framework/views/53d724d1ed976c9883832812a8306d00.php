<div class="relative group/books-swiper overflow-hidden w-full">
    <div class="swiper books-swiper-container !pb-10">
        <div class="swiper-wrapper flex flex-nowrap">
            <?php $__empty_1 = true; $__currentLoopData = $newBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $title = $book->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                $author = $book->fields->where('tag', '100')->first()?->subfields->where('code', 'a')->first()?->value 
                        ?? $book->fields->where('tag', '700')->first()?->subfields->where('code', 'a')->first()?->value 
                        ?? 'Đang cập nhật tác giả';
            ?>
            <div class="swiper-slide h-auto shrink-0 !w-[180px] md:!w-[200px] lg:!w-[210px]">
                <div class="bg-white p-3 rounded-md border border-slate-100 hover:border-vttu-red/20 transition-all group flex flex-col h-full shadow-sm hover:shadow-md">
                    <!-- Book Cover -->
                    <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="block aspect-[3/4] bg-slate-100 rounded-md mb-3 border border-slate-100 group-hover:bg-vttu-red/5 transition-colors overflow-hidden relative">
                        <?php if($book->cover_image): ?>
                            <img src="<?php echo e(asset('storage/' . $book->cover_image)); ?>" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-slate-50">
                                <i class="fas fa-book-open text-slate-300 group-hover:text-vttu-red text-3xl transition-colors"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-2 right-2 z-10">
                            <?php
                                $lang = app()->getLocale();
                                $typeDisplay = ($lang === 'vi') ? ($book->record_type_vi ?? 'Sách') : ($book->record_type_en ?? 'Book');
                            ?>
                            <span class="px-2 py-0.5 bg-white/90 backdrop-blur text-vttu-dark rounded-sm text-[8px] font-black uppercase tracking-widest shadow-sm"><?php echo e($typeDisplay); ?></span>
                        </div>
                    </a>

                    <!-- Book Info -->
                    <div class="flex-grow flex flex-col gap-2">
                        <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="block">
                            <h3 class="text-xs font-bold text-vttu-dark group-hover:text-vttu-red transition-colors leading-tight line-clamp-2 min-h-[2.5rem]">
                                <?php echo e($title); ?>

                            </h3>
                        </a>
                        <p class="text-[10px] font-medium text-slate-500 flex items-center gap-1.5 truncate min-h-[1.25rem]">
                            <i class="fas fa-user-edit text-[8px] text-vttu-red"></i>
                            <?php echo e($author); ?>

                        </p>
                        
                        <div class="mt-auto pt-2 flex items-center justify-between border-t border-slate-50">
                            <?php if($book->items->where('status', 'available')->count() > 0): ?>
                                <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-sm uppercase tracking-tighter border border-emerald-100">Sẵn sàng</span>
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-sm uppercase tracking-tighter border border-rose-100">Hết sách</span>
                            <?php endif; ?>
                            <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="w-6 h-6 rounded-sm bg-slate-50 flex items-center justify-center text-vttu-red hover:bg-vttu-red hover:text-white transition-all shadow-sm">
                                <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="swiper-slide w-full py-12 text-center shrink-0">
                <p class="text-slate-400 font-bold italic text-sm">Không có tài liệu nào trong chuyên mục này.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="swiper-button-next books-next !w-8 !h-8 !bg-white !rounded-full !shadow-lg !border !border-slate-100 !text-vttu-red after:!text-[10px] !right-2 opacity-0 group-hover/books-swiper:opacity-100 transition-opacity z-30"></div>
        <div class="swiper-button-prev books-prev !w-8 !h-8 !bg-white !rounded-full !shadow-lg !border !border-slate-100 !text-vttu-red after:!text-[10px] !left-2 opacity-0 group-hover/books-swiper:opacity-100 transition-opacity z-30"></div>
        
        <!-- Pagination -->
        <div class="swiper-pagination books-pagination !-bottom-1"></div>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/home-books.blade.php ENDPATH**/ ?>