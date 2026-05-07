<!-- Lock Patron Modal -->
<div id="lockModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeLockModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase"><?php echo e(__('Khóa thẻ độc giả')); ?></h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="lockPatronName"></p>
            </div>
            <form id="lockForm" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1"><?php echo e(__('Lý do khóa thẻ')); ?></label>
                    <textarea name="reason" id="lock_reason" required rows="3"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập lý do khóa thẻ..."></textarea>
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeLockModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all"><?php echo e(__('Hủy')); ?></button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-red-500 transition-all"><?php echo e(__('Khóa thẻ')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Unlock Patron Modal -->
<div id="unlockModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeUnlockModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase"><?php echo e(__('Mở khóa thẻ độc giả')); ?></h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="unlockPatronName"></p>
            </div>
            <form id="unlockForm" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1"><?php echo e(__('Lý do mở khóa')); ?></label>
                    <textarea name="reason" id="unlock_reason" required rows="3"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập lý do mở khóa thẻ..."></textarea>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="charge_fee" id="charge_fee" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-slate-700"><?php echo e(__('Thu tiền mở khóa')); ?></span>
                    </label>
                    <input type="number" name="unlock_fee" id="unlock_fee" step="0.01" min="0" 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập phí mở khóa..." disabled>
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeUnlockModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all"><?php echo e(__('Hủy')); ?></button>
                    <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-green-500 transition-all"><?php echo e(__('Mở khóa')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Modal functions
function openLockModal(patron) {
    document.getElementById('lockForm').action = `<?php echo e(route('admin.patrons.lock', ['id' => ':id'])); ?>`.replace(':id', patron.id);
    document.getElementById('lockPatronName').textContent = patron.name;
    document.getElementById('lockModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLockModal() {
    document.getElementById('lockModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openUnlockModal(patron) {
    document.getElementById('unlockForm').action = `<?php echo e(route('admin.patrons.unlock', ['id' => ':id'])); ?>`.replace(':id', patron.id);
    document.getElementById('unlockPatronName').textContent = patron.name;
    document.getElementById('unlockModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUnlockModal() {
    document.getElementById('unlockModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('charge_fee').checked = false;
    document.getElementById('unlock_fee').disabled = true;
}


// Toggle payment method based on transaction type
document.addEventListener('DOMContentLoaded', function() {
    const transactionType = document.getElementById('transaction_type');
    const paymentMethodDiv = document.getElementById('paymentMethodDiv');
    
    if (transactionType) {
        transactionType.addEventListener('change', function() {
            if (this.value === 'deposit') {
                paymentMethodDiv.style.display = 'block';
            } else {
                paymentMethodDiv.style.display = 'none';
            }
        });
    }
    
    // Toggle unlock fee field
    const chargeFee = document.getElementById('charge_fee');
    const unlockFee = document.getElementById('unlock_fee');
    
    if (chargeFee) {
        chargeFee.addEventListener('change', function() {
            unlockFee.disabled = !this.checked;
            if (this.checked) {
                unlockFee.focus();
            }
        });
    }
});
</script>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/modals.blade.php ENDPATH**/ ?>