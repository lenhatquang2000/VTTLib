<?php $__env->startSection('title', 'OPAC - Tra cứu mục lục trực tuyến - VTTLib'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen pt-24 pb-12">
    <div class=" mx-auto px-1">
        
        <!-- Header OPAC -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-8" data-aos="fade-down">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-3xl font-black text-vttu-dark tracking-tight">TRA CỨU OPAC</h1>
                    <p class="text-slate-500 font-medium mt-1">Hệ thống tra cứu mục lục công cộng trực tuyến</p>
                </div>
                <div class="flex items-center gap-4 bg-emerald-50 px-6 py-3 rounded-2xl border border-emerald-100">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none">Trạng thái hệ thống</p>
                        <p class="text-sm font-bold text-vttu-dark mt-1">Kết nối thành công (<?php echo e(number_format($totalRecords ?? 0)); ?> biểu ghi)</p>
                    </div>
                </div>
            </div>

            <!-- Search Bar OPAC -->
            <div class="mt-8">
                <form action="<?php echo e(route('opac.search')); ?>" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-vttu-red transition-colors"></i>
                    </div>
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" 
                        placeholder="Nhập tên sách, tác giả, từ khóa hoặc ISBN..." 
                        class="w-full bg-slate-50 border-2 border-transparent focus:border-vttu-red/20 focus:bg-white pl-14 pr-40 py-5 rounded-[2rem] text-lg font-medium text-vttu-dark transition-all outline-none shadow-inner">
                    <div class="absolute inset-y-2 right-2 flex gap-2">
                        <select name="type" class="bg-white border border-slate-200 rounded-full px-4 text-xs font-black uppercase tracking-widest text-slate-500 outline-none focus:border-vttu-red transition-all">
                            <option value="all">Bất kỳ</option>
                            <option value="title" <?php echo e(request('type') == 'title' ? 'selected' : ''); ?>>Nhan đề</option>
                            <option value="author" <?php echo e(request('type') == 'author' ? 'selected' : ''); ?>>Tác giả</option>
                            <option value="subject" <?php echo e(request('type') == 'subject' ? 'selected' : ''); ?>>Chủ đề</option>
                        </select>
                        <button type="submit" class="bg-vttu-red text-white px-8 rounded-full font-black uppercase text-xs tracking-[0.2em] hover:bg-vttu-dark transition-all shadow-lg shadow-vttu-red/20">
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- CỘT TRÁI (75%) - Kết quả tra cứu -->
            <div class="lg:col-span-9 space-y-6" data-aos="fade-right">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-xl font-black text-vttu-dark uppercase tracking-tight">KẾT QUẢ TRA CỨU</h2>
                    <span class="text-xs font-bold text-slate-400">Trang <?php echo e($books->currentPage()); ?> / <?php echo e($books->lastPage()); ?></span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Helper để lấy nội dung từ MARC fields
                        $getTitle = function($book) {
                            $f245 = $book->fields->where('tag', '245')->first();
                            return $f245 ? $f245->subfields->where('code', 'a')->first()?->value : 'Không có nhan đề';
                        };
                        $getAuthor = function($book) {
                            $f100 = $book->fields->where('tag', '100')->first();
                            if ($f100) return $f100->subfields->where('code', 'a')->first()?->value;
                            $f700 = $book->fields->where('tag', '700')->first();
                            return $f700 ? $f700->subfields->where('code', 'a')->first()?->value : 'Đang cập nhật tác giả';
                        };
                    ?>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 hover:border-vttu-red/20 transition-all group flex flex-col shadow-sm hover:shadow-xl hover:-translate-y-1">
                        <!-- Book Cover -->
                        <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="aspect-[3/4] bg-slate-100 rounded-xl mb-4 flex items-center justify-center border border-slate-50 group-hover:bg-vttu-red/5 transition-colors overflow-hidden relative">
                            <?php if($book->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $book->cover_image)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <i class="fas fa-book-open text-slate-300 group-hover:text-vttu-red text-4xl transition-colors"></i>
                            <?php endif; ?>
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-0.5 bg-white/90 backdrop-blur text-vttu-dark rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm"><?php echo e($book->record_type ?? 'Sách'); ?></span>
                            </div>
                        </a>

                        <!-- Book Info -->
                        <div class="flex-grow flex flex-col">
                            <a href="<?php echo e(route('opac.book.show', $book->id)); ?>">
                                <h3 class="text-sm font-black text-vttu-dark group-hover:text-vttu-red transition-colors leading-tight line-clamp-2 min-h-[2.5rem]">
                                    <?php echo e($getTitle($book)); ?>

                                </h3>
                            </a>
                            <p class="text-[11px] font-bold text-slate-500 mt-2 flex items-center gap-1.5 truncate">
                                <i class="fas fa-user-edit text-[9px] text-vttu-red"></i>
                                <?php echo e($getAuthor($book)); ?>

                            </p>
                            
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                                <?php if($book->items->where('status', 'available')->count() > 0): ?>
                                    <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Sẵn sàng</span>
                                <?php else: ?>
                                    <span class="text-[9px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Hết sách</span>
                                <?php endif; ?>
                                <a href="<?php echo e(route('opac.book.show', $book->id)); ?>" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-vttu-red hover:bg-vttu-red hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full bg-white p-12 rounded-3xl text-center border border-slate-100">
                            <i class="fas fa-search text-slate-200 text-5xl mb-4"></i>
                            <p class="text-slate-500 font-bold">Không tìm thấy tài liệu nào trong hệ thống.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination OPAC -->
                <div class="flex justify-center pt-8">
                    <?php echo e($books->links()); ?>

                </div>
            </div>

            <!-- CỘT PHẢI (25%) - Filters & Sidebar -->
            <div class="lg:col-span-3 space-y-8" data-aos="fade-left">
                
                <!-- Sách theo kho -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-vttu-dark uppercase tracking-widest border-b border-slate-50 pb-4 mb-4">SÁCH THEO KHO</h3>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $sidebar['locations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('opac.search', ['location' => $location->id])); ?>" class="flex justify-between items-center group">
                            <span class="text-sm font-bold text-slate-600 group-hover:text-vttu-red transition-colors truncate pr-2"><?php echo e($location->name); ?></span>
                            <span class="bg-slate-50 text-slate-400 px-2 py-0.5 rounded-lg text-[10px] font-black group-hover:bg-vttu-red/10 group-hover:text-vttu-red transition-all"><?php echo e($location->book_items_count); ?></span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-slate-400 italic">Đang cập nhật...</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sách theo phân loại (DDC) -->
                <div class="bg-vttu-dark rounded-3xl p-6 shadow-xl shadow-vttu-red/20 text-white">
                    <h3 class="text-sm font-black text-vttu-yellow uppercase tracking-widest border-b border-white/10 pb-4 mb-4">PHÂN LOẠI DDC</h3>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php $__empty_1 = true; $__currentLoopData = $sidebar['ddc']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ddc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('opac.search', ['ddc' => $ddc['code']])); ?>" class="flex justify-between items-start group border-b border-white/5 pb-2 last:border-0">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-vttu-yellow tracking-widest"><?php echo e($ddc['code']); ?></span>
                                <span class="text-xs font-bold text-white/70 group-hover:text-white transition-colors"><?php echo e($ddc['name']); ?></span>
                            </div>
                            <span class="text-[10px] font-black bg-white/10 px-2 py-0.5 rounded group-hover:bg-vttu-yellow group-hover:text-vttu-dark transition-all"><?php echo e($ddc['count']); ?></span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-white/40 italic text-center">Đang cập nhật...</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mượn nhiều -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-vttu-dark uppercase tracking-widest border-b border-slate-50 pb-4 mb-4">SÁCH PHỔ BIẾN</h3>
                    <div class="space-y-6">
                        <?php $__empty_1 = true; $__currentLoopData = $sidebar['mostBorrowed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mbTitle = $mb->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                            $mbPub = $mb->fields->where('tag', '260')->first()?->subfields->where('code', 'b')->first()?->value ?? 'Đang cập nhật';
                        ?>
                        <div class="group cursor-pointer">
                            <h4 class="text-xs font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2"><?php echo e($mbTitle); ?></h4>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest"><?php echo e($mbPub); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-slate-400 italic">Đang cập nhật...</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Từ khóa hot -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-vttu-dark uppercase tracking-widest border-b border-slate-50 pb-4 mb-4">TỪ KHÓA HOT</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__empty_1 = true; $__currentLoopData = $sidebar['hotKeywords']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('opac.search', ['q' => $tag])); ?>" class="px-4 py-2 bg-slate-50 hover:bg-vttu-red hover:text-white text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all border border-slate-100">
                            <?php echo e($tag); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-slate-400 italic">Đang cập nhật...</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #FFD700; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/opac.blade.php ENDPATH**/ ?>