<!-- CREATE BARCODE MODAL -->
<div id="createBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Initialize Barcode Rule')); ?></h3>
            </div>
            <form action="<?php echo e(route('admin.settings.barcode.store')); ?>" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Rule Label')); ?></label>
                    <input type="text" name="name" required placeholder="<?php echo e(__('e.g. Sách tiếng Việt')); ?>"
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Prefix ID')); ?></label>
                        <input type="text" name="prefix" placeholder="VTTU"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Sequence Width')); ?></label>
                        <input type="number" name="length" value="6" required min="1" max="20"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Start From')); ?></label>
                        <input type="number" name="start_number" value="1" required min="0"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Target Node')); ?></label>
                        <select name="target_type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none appearance-none transition-all">
                            <option value="item"><?php echo e(__('Book Item')); ?></option>
                            <option value="patron"><?php echo e(__('Patron ID')); ?></option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold group-hover:text-indigo-600 transition-colors"><?php echo e(__('Set As Primary Rule')); ?></span>
                </label>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('createBarcodeModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Execute')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BARCODE MODAL -->
<div id="editBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('editBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Modify Barcode Rule')); ?></h3>
            </div>
            <form id="editBarcodeForm" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Rule Label')); ?></label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Prefix ID')); ?></label>
                        <input type="text" name="prefix" id="edit_prefix"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Sequence Width')); ?></label>
                        <input type="number" name="length" id="edit_length" required min="1" max="20"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                </div>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold group-hover:text-indigo-600 transition-colors"><?php echo e(__('Set As Primary Rule')); ?></span>
                </label>
                <div class="m-0 p-3 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-100 dark:border-amber-500/20 text-[10px] text-amber-600 dark:text-amber-400 italic leading-relaxed transition-colors">
                    * <?php echo e(__('Changing the prefix only affects new barcodes. Existing ones remain unchanged.')); ?>

                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('editBarcodeModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Update')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE BRANCH MODAL -->
<div id="createBranchModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createBranchModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Initialize New Branch')); ?></h3>
            </div>
            <form action="<?php echo e(route('admin.settings.branches.store')); ?>" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Branch Name')); ?></label>
                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Branch Code')); ?></label>
                    <input type="text" name="code" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Phone')); ?></label>
                        <input type="text" name="phone" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Address')); ?></label>
                        <input type="text" name="address" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('createBranchModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Execute')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE LOCATION MODAL -->
<div id="createLocationModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createLocationModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Initialize Storage Location')); ?></h3>
            </div>
            <form action="<?php echo e(route('admin.settings.locations.store')); ?>" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Parent Branch')); ?></label>
                    <select name="branch_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none appearance-none transition-all transition-colors leading-[1.25rem]">
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Location Name')); ?></label>
                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Location Code')); ?></label>
                    <input type="text" name="code" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all transition-colors">
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('createLocationModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Execute')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE SUPPLIER MODAL -->
<div id="createSupplierModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createSupplierModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Register New Supplier')); ?></h3>
            </div>
            <form action="<?php echo e(route('admin.settings.suppliers.store')); ?>" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Supplier Name')); ?></label>
                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Supplier Code')); ?></label>
                    <input type="text" name="code" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Contact Person')); ?></label>
                        <input type="text" name="contact_name" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Phone Number')); ?></label>
                        <input type="text" name="phone" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Email')); ?></label>
                    <input type="email" name="email" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('createSupplierModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Execute')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT SUPPLIER MODAL -->
<div id="editSupplierModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('editSupplierModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Modify Supplier Info')); ?></h3>
            </div>
            <form id="editSupplierForm" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Supplier Name')); ?></label>
                    <input type="text" name="name" id="edit_sup_name" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Supplier Code')); ?></label>
                    <input type="text" name="code" id="edit_sup_code" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Contact Person')); ?></label>
                        <input type="text" name="contact_name" id="edit_sup_contact"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Phone Number')); ?></label>
                        <input type="text" name="phone" id="edit_sup_phone"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-1 tracking-widest"><?php echo e(__('Email')); ?></label>
                    <input type="email" name="email" id="edit_sup_email"
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" id="edit_sup_is_active" value="1" class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold group-hover:text-indigo-600 transition-colors"><?php echo e(__('Active Status')); ?></span>
                </label>
                <div class="flex space-x-4 pt-4 border-t border-slate-50 dark:border-slate-800 transition-colors">
                    <button type="button" onclick="closeModal('editSupplierModal')" class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transition-colors"><?php echo e(__('Abort')); ?></button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-500 transition-all"><?php echo e(__('Update')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/settings/modals.blade.php ENDPATH**/ ?>