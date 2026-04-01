<!-- Bulk Edit Modal -->
<div id="bulkEditModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeBulkEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <!-- Header -->
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                        Thay đổi thông tin hàng loạt
                    </h3>
                    <button type="button" onclick="closeBulkEditModal()" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                    <span id="selectedPatronsCount">0</span> độc giả được chọn
                </p>
            </div>

            <!-- Form -->
            <form id="bulkEditForm" method="POST" action="<?php echo e(route('admin.patrons.bulk.update')); ?>" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="patron_ids" id="bulkEditPatronIds">
                
                <!-- Field Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                        Chọn trường thông tin cần thay đổi
                    </label>
                    <div class="space-y-3">
                        <!-- Patron Group -->
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_patron_group_id" name="fields[]" value="patron_group_id" 
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_patron_group_id" class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                Nhóm độc giả
                            </label>
                        </div>
                        
                        <!-- Branch -->
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_branch_id" name="fields[]" value="branch_id" 
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_branch_id" class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                Chi nhánh/Đơn vị
                            </label>
                        </div>
                        
                        <!-- Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_is_active" name="fields[]" value="is_active" 
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_is_active" class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                Trạng thái hoạt động
                            </label>
                        </div>
                        
                        <!-- Expiry Date -->
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_expiry_date" name="fields[]" value="expiry_date" 
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_expiry_date" class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                Ngày hết hạn
                            </label>
                        </div>
                        
                        <!-- Phone -->
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_phone" name="fields[]" value="phone" 
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_phone" class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                Số điện thoại
                            </label>
                        </div>
                    </div>
                </div>

                <!-- New Values -->
                <div class="space-y-4">
                    <!-- Patron Group Select -->
                    <div id="patron_group_id_field" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Nhóm độc giả mới
                        </label>
                        <select name="patron_group_id" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Chọn nhóm độc giả</option>
                            <?php $__currentLoopData = App\Models\PatronGroup::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Branch Select -->
                    <div id="branch_id_field" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Chi nhánh mới
                        </label>
                        <select name="branch_id" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Chọn chi nhánh</option>
                            <?php if(isset($branches)): ?>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Status Select -->
                    <div id="is_active_field" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Trạng thái mới
                        </label>
                        <select name="is_active" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Expiry Date -->
                    <div id="expiry_date_field" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Ngày hết hạn mới
                        </label>
                        <input type="date" name="expiry_date" 
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Phone -->
                    <div id="phone_field" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Số điện thoại mới
                        </label>
                        <input type="tel" name="phone" placeholder="Nhập số điện thoại mới"
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Warning -->
                <div class="m-0 p-4 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-100 dark:border-amber-500/20 text-sm text-amber-600 dark:text-amber-400 italic leading-relaxed transition-colors">
                    <strong>Lưu ý:</strong> Hành động này sẽ thay đổi thông tin của tất cả độc giả đã chọn. Không thể hoàn tác sau khi thực hiện.
                </div>

                <!-- Actions -->
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeBulkEditModal()" 
                            class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-md hover:bg-indigo-500 transition-all">
                        Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Field visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const fieldCheckboxes = document.querySelectorAll('input[name="fields[]"]');
    
    fieldCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const fieldName = this.value;
            const fieldDiv = document.getElementById(fieldName + '_field');
            
            if (fieldDiv) {
                if (this.checked) {
                    fieldDiv.classList.remove('hidden');
                } else {
                    fieldDiv.classList.add('hidden');
                    // Clear the field value
                    const input = fieldDiv.querySelector('input, select');
                    if (input) input.value = '';
                }
            }
        });
    });
});

function closeBulkEditModal() {
    document.getElementById('bulkEditModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/patrons/bulk-edit.blade.php ENDPATH**/ ?>