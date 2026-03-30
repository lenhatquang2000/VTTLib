<!-- Lock Patron Modal -->
<div id="lockModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeLockModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ __('Khóa thẻ độc giả') }}</h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="lockPatronName"></p>
            </div>
            <form id="lockForm" method="POST" class="p-8 space-y-5">
                @csrf @method('PATCH')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Lý do khóa thẻ') }}</label>
                    <textarea name="reason" id="lock_reason" required rows="3"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập lý do khóa thẻ..."></textarea>
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeLockModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all">{{ __('Hủy') }}</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-red-500 transition-all">{{ __('Khóa thẻ') }}</button>
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
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ __('Mở khóa thẻ độc giả') }}</h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="unlockPatronName"></p>
            </div>
            <form id="unlockForm" method="POST" class="p-8 space-y-5">
                @csrf @method('PATCH')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Lý do mở khóa') }}</label>
                    <textarea name="reason" id="unlock_reason" required rows="3"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập lý do mở khóa thẻ..."></textarea>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="charge_fee" id="charge_fee" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-slate-700">{{ __('Thu tiền mở khóa') }}</span>
                    </label>
                    <input type="number" name="unlock_fee" id="unlock_fee" step="0.01" min="0" 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập phí mở khóa..." disabled>
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeUnlockModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all">{{ __('Hủy') }}</button>
                    <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-green-500 transition-all">{{ __('Mở khóa') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div id="transactionModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeTransactionModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ __('Giao dịch tài chính') }}</h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="transactionPatronName"></p>
                <p class="text-slate-500 text-xs mt-1">Số dư hiện tại: <span id="currentBalance" class="font-bold"></span></p>
            </div>
            <form id="transactionForm" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Loại giao dịch') }}</label>
                    <select name="type" id="transaction_type" required 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        <option value="">{{ __('Chọn loại giao dịch') }}</option>
                        <option value="deposit">{{ __('Thêm tiền') }}</option>
                        <option value="withdraw">{{ __('Rút tiền') }}</option>
                        <option value="fee">{{ __('Phí dịch vụ') }}</option>
                        <option value="fine">{{ __('Phạt') }}</option>
                        <option value="penalty">{{ __('Phạt khác') }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Số tiền') }}</label>
                    <input type="number" name="amount" id="transaction_amount" required step="0.01" min="0.01"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập số tiền...">
                </div>
                <div class="space-y-2" id="paymentMethodDiv" style="display: none;">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Phương thức thanh toán') }}</label>
                    <select name="payment_method" id="payment_method"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        <option value="cash">{{ __('Tiền mặt') }}</option>
                        <option value="transfer">{{ __('Chuyển khoản') }}</option>
                        <option value="card">{{ __('Thẻ') }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Mô tả') }}</label>
                    <input type="text" name="description" required
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Nhập mô tả giao dịch...">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Ghi chú') }}</label>
                    <textarea name="notes" rows="2"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        placeholder="Ghi chú thêm (không bắt buộc)..."></textarea>
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeTransactionModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all">{{ __('Hủy') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-indigo-500 transition-all">{{ __('Thực hiện') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function openLockModal(patron) {
    document.getElementById('lockForm').action = `/topsecret/patrons/${patron.id}/lock`;
    document.getElementById('lockPatronName').textContent = patron.name;
    document.getElementById('lockModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLockModal() {
    document.getElementById('lockModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openUnlockModal(patron) {
    document.getElementById('unlockForm').action = `/topsecret/patrons/${patron.id}/unlock`;
    document.getElementById('unlockPatronName').textContent = patron.name;
    document.getElementById('unlockModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUnlockModal() {
    document.getElementById('unlockModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openTransactionModal(patron) {
    document.getElementById('transactionForm').action = `/topsecret/patrons/${patron.id}/transactions`;
    document.getElementById('transactionPatronName').textContent = patron.name;
    document.getElementById('currentBalance').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(patron.balance);
    document.getElementById('transactionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
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
