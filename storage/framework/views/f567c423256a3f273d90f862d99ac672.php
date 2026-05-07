<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    
    <?php if(session('success')): ?>
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Quản lý yêu cầu mượn sách</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Phê duyệt hoặc từ chối các yêu cầu đăng ký mượn từ độc giả qua OPAC</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.circulation.requests', ['status' => 'pending'])); ?>" 
               class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?php echo e($status == 'pending' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'); ?>">
                Đang chờ duyệt
            </a>
            <a href="<?php echo e(route('admin.circulation.requests', ['status' => 'ready'])); ?>" 
               class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?php echo e($status == 'ready' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'); ?>">
                Sẵn sàng lấy
            </a>
            <a href="<?php echo e(route('admin.circulation.requests', ['status' => 'all'])); ?>" 
               class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?php echo e($status == 'all' ? 'bg-slate-800 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'); ?>">
                Tất cả
            </a>
        </div>
    </div>

    <!-- Tabs Navigation (Same as Loan Desk) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <div class="flex space-x-1 p-2">
                <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>" 
                        class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-right mr-2"></i><?php echo e(__('Mượn sách')); ?>

                </a>
                <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>?tab=checkin"
                        class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i><?php echo e(__('Trả sách')); ?>

                </a>
                <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>?tab=reading-room"
                        class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-book-reader mr-2"></i><?php echo e(__('Mượn đọc')); ?>

                </a>
                <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>?tab=hold"
                        class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-bookmark mr-2"></i><?php echo e(__('Giữ lại')); ?>

                </a>
                <a href="<?php echo e(route('admin.circulation.requests')); ?>"
                        class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500">
                    <i class="fas fa-clipboard-list mr-2"></i><?php echo e(__('Yêu cầu mượn')); ?>

                </a>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Độc giả</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tài liệu yêu cầu</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ngày đăng ký</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Trạng thái</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $title = $req->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                        ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs border border-indigo-100 dark:border-indigo-900/30 shadow-sm">
                                        <?php echo e(substr($req->patron->display_name ?? $req->patron->user->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?php echo e($req->patron->display_name ?? $req->patron->user->name); ?></p>
                                        <p class="text-[10px] text-slate-400 font-mono"><?php echo e($req->patron->patron_code); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 line-clamp-1" title="<?php echo e($title); ?>"><?php echo e($title); ?></p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">Record ID: #<?php echo e($req->bibliographic_record_id); ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 dark:text-slate-400"><?php echo e($req->reservation_date->format('d/m/Y H:i')); ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($req->status == 'pending'): ?>
                                    <span class="px-2 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase rounded-lg border border-amber-100 dark:border-amber-900/30">Chờ duyệt</span>
                                <?php elseif($req->status == 'ready'): ?>
                                    <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase rounded-lg border border-emerald-100 dark:border-emerald-900/30">Đã duyệt - Chờ lấy</span>
                                <?php elseif($req->status == 'cancelled'): ?>
                                    <span class="px-2 py-1 bg-rose-50 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400 text-[10px] font-black uppercase rounded-lg border border-rose-100 dark:border-rose-900/30">Đã hủy/Từ chối</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase rounded-lg"><?php echo e($req->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if($req->status == 'pending'): ?>
                                <div class="flex justify-end gap-2">
                                    <button onclick="approveRequest(<?php echo e($req->id); ?>)" class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white rounded-lg transition-all" title="Phê duyệt">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="openRejectModal(<?php echo e($req->id); ?>)" class="p-2 bg-rose-50 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400 hover:bg-rose-600 hover:text-white rounded-lg transition-all" title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                <i class="fas fa-inbox text-4xl mb-4 opacity-20"></i>
                                <p>Không có yêu cầu mượn nào được tìm thấy.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <?php echo e($requests->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/75 backdrop-blur-sm" onclick="closeRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-800">
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="px-8 pt-8 pb-6">
                    <h3 class="text-xl font-black text-vttu-dark dark:text-slate-100 uppercase tracking-tight mb-4">Từ chối yêu cầu</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Lý do từ chối</label>
                            <textarea name="reason" rows="4" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-3xl focus:ring-2 focus:ring-vttu-red/20 text-sm font-medium text-slate-700 dark:text-slate-200" placeholder="Nhập lý do để thông báo cho độc giả..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="px-8 pb-8 flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Hủy bỏ</button>
                    <button type="submit" class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-200 dark:shadow-none">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(id) {
        const form = document.getElementById('rejectForm');
        form.action = `/topsecret/circulation/requests/${id}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    async function approveRequest(id) {
        if (typeof SwalHelper === 'undefined') {
            console.error('SwalHelper is not loaded');
            return;
        }

        const confirmed = await SwalHelper.showApproveConfirm();

        if (confirmed) {
            SwalHelper.showLoading('Đang phê duyệt...');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/topsecret/circulation/requests/${id}/approve`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/requests.blade.php ENDPATH**/ ?>