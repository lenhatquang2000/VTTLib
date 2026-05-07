<?php $__env->startSection('title', 'Hồ sơ cá nhân - VTTLib'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen pt-24 pb-12" x-data="{ activeTab: 'info' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar: User Info -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 sticky top-28">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-full bg-vttu-red/5 flex items-center justify-center border-4 border-white shadow-xl mb-6 overflow-hidden">
                            <?php if($user->profile_photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $user->profile_photo_path)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-vttu-red flex items-center justify-center">
                                    <span class="text-4xl font-black text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-xl font-black text-vttu-dark uppercase tracking-tight"><?php echo e($user->name); ?></h2>
                        <p class="text-slate-400 font-bold text-xs mt-1 uppercase tracking-widest"><?php echo e($patron->patronGroup->name ?? 'Độc giả'); ?></p>
                        
                        <div class="w-full grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-slate-50">
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Mã độc giả</p>
                                <p class="text-sm font-bold text-vttu-dark"><?php echo e($patron->patron_code ?? 'N/A'); ?></p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Trạng thái thẻ</p>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg">Hoạt động</span>
                            </div>
                        </div>

                        <!-- Menu Tabs -->
                        <div class="w-full mt-8 space-y-2">
                            <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'bg-vttu-red text-white shadow-lg shadow-vttu-red/20' : 'text-slate-600 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all text-sm font-black uppercase tracking-widest">
                                <i class="fas fa-user-circle"></i>
                                <span>Thông tin cá nhân</span>
                            </button>
                            <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'bg-vttu-red text-white shadow-lg shadow-vttu-red/20' : 'text-slate-600 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all text-sm font-black uppercase tracking-widest">
                                <i class="fas fa-history"></i>
                                <span>Lịch sử mượn sách</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Tab: Information -->
                <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mb-4 text-lg">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Đã mượn</p>
                            <h4 class="text-2xl font-black text-vttu-dark"><?php echo e($stats['total_borrowed']); ?></h4>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center mb-4 text-lg">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Đang mượn</p>
                            <h4 class="text-2xl font-black text-vttu-dark"><?php echo e($stats['active_loans']); ?></h4>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center mb-4 text-lg">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Quá hạn</p>
                            <h4 class="text-2xl font-black text-vttu-dark"><?php echo e($stats['overdue_loans']); ?></h4>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center mb-4 text-lg">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiền nợ</p>
                            <h4 class="text-2xl font-black text-vttu-dark"><?php echo e(number_format($stats['total_fines'])); ?>đ</h4>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                            <span class="w-8 h-1 bg-vttu-red rounded-full"></span>
                            Thông tin chi tiết
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Họ và tên</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($user->name); ?></p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Email</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($user->email); ?></p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Số điện thoại</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($patron->phone ?? 'Chưa cập nhật'); ?></p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Địa chỉ</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($patron->address ?? 'Chưa cập nhật'); ?></p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Ngày sinh</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($patron->dob ? \Carbon\Carbon::parse($patron->dob)->format('d/m/Y') : 'Chưa cập nhật'); ?></p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Đơn vị/Lớp</label>
                                    <p class="text-sm font-bold text-vttu-dark bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100"><?php echo e($patron->department ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Loan History -->
                <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    <!-- Section: Reservations -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
                            Yêu cầu mượn & Đăng ký
                        </h3>
                        
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $resTitle = $res->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                            ?>
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-white rounded-lg flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            <?php if($res->bibliographicRecord->cover_image): ?>
                                                <img src="<?php echo e(asset('storage/' . $res->bibliographicRecord->cover_image)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-book text-xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-vttu-dark line-clamp-1"><?php echo e($resTitle); ?></h4>
                                            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-widest">Đăng ký: <?php echo e($res->reservation_date->format('d/m/Y')); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <?php if($res->status == 'pending'): ?>
                                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-full border border-amber-100">Đang chờ duyệt</span>
                                        <?php elseif($res->status == 'ready'): ?>
                                            <div class="text-right">
                                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-full border border-emerald-100">Đã duyệt - Sẵn sàng nhận sách</span>
                                                <?php if($res->expiry_date): ?>
                                                <p class="text-[9px] text-emerald-600 font-bold mt-1 uppercase tracking-widest">
                                                    Còn <?php echo e(now()->diffInDays($res->expiry_date, false)); ?> ngày để lấy sách
                                                </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif($res->status == 'cancelled' || $res->status == 'rejected'): ?>
                                            <span class="px-3 py-1 bg-rose-50 text-rose-500 text-[10px] font-black uppercase rounded-full border border-rose-100">Đã hủy / Từ chối</span>
                                        <?php elseif($res->status == 'completed'): ?>
                                            <span class="px-3 py-1 bg-blue-50 text-blue-500 text-[10px] font-black uppercase rounded-full border border-blue-100">Đã hoàn tất mượn</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12">
                                <p class="text-slate-400 font-bold">Không có yêu cầu nào.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Section: Current Loans -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                            <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                            Sách đang mượn
                        </h3>
                        
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $activeLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $loanTitle = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                                $isOverdue = $loan->isOverdue();
                            ?>
                            <div class="p-6 <?php echo e($isOverdue ? 'bg-rose-50 border-rose-100' : 'bg-slate-50 border-slate-100'); ?> rounded-3xl border">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-white rounded-lg flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            <?php if($loan->bookItem->bibliographicRecord->cover_image): ?>
                                                <img src="<?php echo e(asset('storage/' . $loan->bookItem->bibliographicRecord->cover_image)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-book text-xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-vttu-dark line-clamp-1"><?php echo e($loanTitle); ?></h4>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Ngày mượn: <?php echo e($loan->loan_date->format('d/m/Y')); ?></p>
                                                <p class="text-[10px] font-bold uppercase tracking-widest <?php echo e($isOverdue ? 'text-rose-500' : 'text-slate-400'); ?>">
                                                    Hạn trả: <?php echo e($loan->due_date->format('d/m/Y')); ?> 
                                                    <?php if($isOverdue): ?> (Quá hạn <?php echo e($loan->getOverdueDays()); ?> ngày) <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 <?php echo e($isOverdue ? 'bg-rose-100 text-rose-600 border-rose-200' : 'bg-blue-50 text-blue-600 border-blue-100'); ?> text-[10px] font-black uppercase rounded-full border">
                                            <?php echo e($isOverdue ? 'Quá hạn' : 'Đang mượn'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12">
                                <p class="text-slate-400 font-bold">Hiện không mượn cuốn sách nào.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Section: Returned Loans -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                            <span class="w-8 h-1 bg-slate-300 rounded-full"></span>
                            Lịch sử đã trả
                        </h3>
                        
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $returnedLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $loanTitle = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                            ?>
                            <div class="p-6 bg-slate-50/50 rounded-3xl border border-slate-100 opacity-75 hover:opacity-100 transition-opacity">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-white rounded-lg flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            <?php if($loan->bookItem->bibliographicRecord->cover_image): ?>
                                                <img src="<?php echo e(asset('storage/' . $loan->bookItem->bibliographicRecord->cover_image)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-book text-xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-vttu-dark line-clamp-1"><?php echo e($loanTitle); ?></h4>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Đã trả: <?php echo e($loan->return_date ? $loan->return_date->format('d/m/Y') : 'N/A'); ?></p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Mã vạch: <?php echo e($loan->bookItem->barcode); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase rounded-full border border-slate-200">Đã trả</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12">
                                <p class="text-slate-400 font-bold">Chưa có lịch sử trả sách.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/profile.blade.php ENDPATH**/ ?>