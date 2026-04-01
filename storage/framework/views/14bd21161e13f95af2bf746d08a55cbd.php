

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
            <h1 class="text-2xl font-bold"><?php echo e(__('Fines_Management')); ?></h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo e(__('Manage_patron_fines_and_payments')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.circulation.index')); ?>" class="btn-secondary">
                <?php echo e(__('Policies')); ?>

            </a>
            <a href="<?php echo e(route('admin.circulation.loan-desk')); ?>" class="btn-secondary">
                <?php echo e(__('Loan_Desk')); ?>

            </a>
        </div>
    </div>

    <!-- Unpaid Fines Table -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold"><?php echo e(__('Unpaid_Fines')); ?></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left"><?php echo e(__('Date')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Patron')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Type')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Description')); ?></th>
                        <th class="p-3 text-right"><?php echo e(__('Amount')); ?></th>
                        <th class="p-3 text-right"><?php echo e(__('Paid')); ?></th>
                        <th class="p-3 text-right"><?php echo e(__('Balance')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $unpaidFines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3 text-xs text-gray-400"><?php echo e($fine->created_at->format('d/m/Y')); ?></td>
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($fine->patron->display_name ?? $fine->patron->user->name); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($fine->patron->patron_code); ?></div>
                        </td>
                        <td class="p-3">
                            <?php
                                $typeClass = match($fine->fine_type) {
                                    'overdue' => 'bg-yellow-900/50 text-yellow-400',
                                    'lost' => 'bg-red-900/50 text-red-400',
                                    'damaged' => 'bg-orange-900/50 text-orange-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-bold <?php echo e($typeClass); ?>">
                                <?php echo e(__(ucfirst($fine->fine_type))); ?>

                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-400"><?php echo e($fine->description); ?></td>
                        <td class="p-3 text-right font-mono"><?php echo e(number_format($fine->amount)); ?>đ</td>
                        <td class="p-3 text-right font-mono text-green-400"><?php echo e(number_format($fine->paid_amount)); ?>đ</td>
                        <td class="p-3 text-right font-mono text-red-400 font-bold"><?php echo e(number_format($fine->balance)); ?>đ</td>
                        <td class="p-3">
                            <button onclick="openPayModal(<?php echo e($fine->id); ?>, <?php echo e($fine->balance); ?>)" 
                                class="text-green-400 hover:text-green-300 text-xs mr-2">
                                <?php echo e(__('Pay')); ?>

                            </button>
                            <button onclick="openWaiveModal(<?php echo e($fine->id); ?>, <?php echo e($fine->balance); ?>)" 
                                class="text-yellow-400 hover:text-yellow-300 text-xs">
                                <?php echo e(__('Waive')); ?>

                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500"><?php echo e(__('No_unpaid_fines')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($unpaidFines->hasPages()): ?>
        <div class="p-4 border-t border-gray-700">
            <?php echo e($unpaidFines->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pay Fine Modal -->
<div id="payFineModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('payFineModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4"><?php echo e(__('Record_Payment')); ?></h3>
        <form id="payFineForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Amount')); ?> (VND) *</label>
                    <input type="number" name="amount" id="payAmount" required min="1" class="input-field w-full">
                    <p class="text-xs text-gray-400 mt-1"><?php echo e(__('Max')); ?>: <span id="payMaxAmount">0</span>đ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Payment_Method')); ?> *</label>
                    <select name="payment_method" required class="input-field w-full">
                        <option value="cash"><?php echo e(__('Cash')); ?></option>
                        <option value="transfer"><?php echo e(__('Bank_Transfer')); ?></option>
                        <option value="card"><?php echo e(__('Card')); ?></option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('payFineModal')" class="btn-secondary"><?php echo e(__('Cancel')); ?></button>
                <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700"><?php echo e(__('Record_Payment')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- Waive Fine Modal -->
<div id="waiveFineModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('waiveFineModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4"><?php echo e(__('Waive_Fine')); ?></h3>
        <form id="waiveFineForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Amount_to_Waive')); ?> (VND) *</label>
                    <input type="number" name="amount" id="waiveAmount" required min="1" class="input-field w-full">
                    <p class="text-xs text-gray-400 mt-1"><?php echo e(__('Max')); ?>: <span id="waiveMaxAmount">0</span>đ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Reason')); ?></label>
                    <textarea name="notes" class="input-field w-full" rows="2" placeholder="<?php echo e(__('Reason_for_waiving')); ?>"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('waiveFineModal')" class="btn-secondary"><?php echo e(__('Cancel')); ?></button>
                <button type="submit" class="btn-primary bg-yellow-600 hover:bg-yellow-700"><?php echo e(__('Waive_Fine')); ?></button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; }
    .modal.hidden { display: none; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
    .modal-content { position: relative; background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1.5rem; max-width: 28rem; width: 100%; }
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-primary { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openPayModal(fineId, balance) {
        document.getElementById('payFineForm').action = `<?php echo e(route('admin.circulation.fines.pay', ['fine' => ':id'])); ?>`.replace(':id', fineId);
        document.getElementById('payAmount').max = balance;
        document.getElementById('payAmount').value = balance;
        document.getElementById('payMaxAmount').textContent = balance.toLocaleString();
        openModal('payFineModal');
    }

    function openWaiveModal(fineId, balance) {
        document.getElementById('waiveFineForm').action = `<?php echo e(route('admin.circulation.fines.waive', ['fine' => ':id'])); ?>`.replace(':id', fineId);
        document.getElementById('waiveAmount').max = balance;
        document.getElementById('waiveAmount').value = balance;
        document.getElementById('waiveMaxAmount').textContent = balance.toLocaleString();
        openModal('waiveFineModal');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/fines.blade.php ENDPATH**/ ?>