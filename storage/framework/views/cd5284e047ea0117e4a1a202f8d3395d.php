

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100"><?php echo e(__('Cataloged_Records')); ?></h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?php echo e(__('Catalog_Instruction_Index')); ?></p>
        </div>
        <a href="<?php echo e(route('admin.marc.book.form')); ?>"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center shadow-lg shadow-indigo-100 dark:shadow-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <?php echo e(__('New_Cataloging')); ?>

        </a>
    </div>

    <!-- Advanced Search Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4"><?php echo e(__('Advanced Search')); ?></h3>

            <form method="GET" class="space-y-4">
                <?php echo csrf_field(); ?>

                <!-- Basic Search -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Search in Title, Author, ISBN, Publisher, Subject, Notes')); ?></label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="<?php echo e(__('Enter search terms...')); ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Sort By')); ?></label>
                        <div class="flex space-x-2">
                            <select name="sort_by"
                                class="flex-1 px-3 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>><?php echo e(__('Created Date')); ?></option>
                                <option value="title" <?php echo e(request('sort_by') == 'title' ? 'selected' : ''); ?>><?php echo e(__('Title')); ?></option>
                                <option value="author" <?php echo e(request('sort_by') == 'author' ? 'selected' : ''); ?>><?php echo e(__('Author')); ?></option>
                                <option value="updated_at" <?php echo e(request('sort_by') == 'updated_at' ? 'selected' : ''); ?>><?php echo e(__('Updated Date')); ?></option>
                            </select>
                            <select name="sort_order"
                                class="px-3 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>><?php echo e(__('Desc')); ?></option>
                                <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>><?php echo e(__('Asc')); ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Filters Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Framework')); ?></label>
                        <select name="framework"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Frameworks')); ?></option>
                            <?php $__currentLoopData = $frameworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e(request('framework') == $code ? 'selected' : ''); ?>><?php echo e($code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Record Type')); ?></label>
                        <select name="record_type"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Types')); ?></option>
                            <?php $__currentLoopData = $recordTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('record_type') == $type ? 'selected' : ''); ?>><?php echo e(ucfirst($type)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Status')); ?></label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Status')); ?></option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('Pending')); ?></option>
                            <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>><?php echo e(__('Approved')); ?></option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Subject Category')); ?></label>
                        <select name="subject_category"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All Categories')); ?></option>
                            <?php $__currentLoopData = $subjectCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>" <?php echo e(request('subject_category') == $category ? 'selected' : ''); ?>><?php echo e($category); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <!-- Filters Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Date From')); ?></label>
                        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('Date To')); ?></label>
                        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('MARC Tag')); ?></label>
                        <select name="marc_tag"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('Select Tag')); ?></option>
                            <?php $__currentLoopData = $commonMarcTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tag); ?>" <?php echo e(request('marc_tag') == $tag ? 'selected' : ''); ?>><?php echo e($tag); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?php echo e(__('MARC Value')); ?></label>
                        <input type="text" name="marc_value" value="<?php echo e(request('marc_value')); ?>"
                            placeholder="<?php echo e(__('Tag value...')); ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-search mr-2"></i>
                        <?php echo e(__('Search')); ?>

                    </button>
                    <a href="<?php echo e(route('admin.marc.book')); ?>"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                        <?php echo e(__('Clear')); ?>

                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <?php if(request()->anyFilled(['search', 'framework', 'record_type', 'status', 'subject_category', 'date_from', 'date_to', 'marc_tag', 'marc_value'])): ?>
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <?php echo e(__('Found :count records matching your criteria', ['count' => $records->total()])); ?>

                </p>
                <?php if(request()->filled('search')): ?>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                    <?php echo e(__('Search term: ":term"', ['term' => request('search')])); ?>

                </p>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('admin.marc.book')); ?>"
                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                <?php echo e(__('Clear filters')); ?>

            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Results Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-gray-500 dark:text-slate-400 tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4"><?php echo e(__('Leader_Type')); ?></th>
                    <th class="px-6 py-4"><?php echo e(__('Main_Content')); ?></th>
                    <th class="px-6 py-4"><?php echo e(__('Fields')); ?></th>
                    <th class="px-6 py-4"><?php echo e(__('Status')); ?></th>
                    <th class="px-6 py-4 text-right"><?php echo e(__('Actions')); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $title = '';
                $author = '';
                foreach ($record->fields as $field) {
                if ($field->tag === '245') {
                foreach ($field->subfields as $sub) {
                if ($sub->code === 'a') $title = $sub->value;
                }
                }
                if ($field->tag === '100') {
                foreach ($field->subfields as $sub) {
                if ($sub->code === 'a') $author = $sub->value;
                }
                }
                }
                ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition cursor-pointer"
                    data-edit-url="<?php echo e(route('admin.marc.book.form', $record->id)); ?>"
                    title="<?php echo e(__('Edit')); ?>">
                    <td class="px-6 py-4 font-mono text-gray-400 dark:text-slate-500">#<?php echo e($record->id); ?></td>
                    <td class="px-6 py-4">
                        <span class="block font-mono text-[10px] text-gray-500 dark:text-slate-500"><?php echo e($record->leader); ?></span>
                        <span
                            class="inline-block px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded text-[10px] font-bold uppercase mt-1"><?php echo e($record->record_type); ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 dark:text-slate-100"><?php echo e($title ?: __('No_Title_Defined')); ?></div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-0.5"><?php echo e($author ?: __('Unknown_Author')); ?></div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-slate-400 text-xs">
                        <?php echo e(__('Tags_Included', ['count' => $record->fields->count()])); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($record->isApproved()): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400">
                            <?php echo e(__('Approved')); ?>

                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400">
                            <?php echo e(__('Pending')); ?>

                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="<?php echo e(route('admin.marc.book.form', $record->id)); ?>?tab=0"
                                class="inline-flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                                title="<?php echo e(__('Leader_Info')); ?>">
                                <i class="fas fa-info-circle text-xs"></i>
                            </a>
                            <a href="<?php echo e(route('admin.marc.book.form', $record->id)); ?>?tab=1"
                                class="inline-flex items-center px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded text-xs font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors"
                                title="<?php echo e(__('Cataloging')); ?>">
                                <i class="fas fa-book text-xs"></i>
                            </a>
                            <a href="<?php echo e(route('admin.marc.book.form', $record->id)); ?>?tab=2"
                                class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors"
                                title="<?php echo e(__('Distribution')); ?>">
                                <i class="fas fa-share-alt text-xs"></i>
                            </a>
                            <a href="<?php echo e(route('admin.marc.book.form', $record->id)); ?>?tab=3"
                                class="inline-flex items-center px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded text-xs font-medium hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors"
                                title="<?php echo e(__('Preview')); ?>">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <button type="button"
                                class="delete-record inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
                                data-id="<?php echo e($record->id); ?>"
                                data-title="<?php echo e($title); ?>"
                                title="<?php echo e(__('Delete')); ?>">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <?php echo e(__('No_Records_Found')); ?>

                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-50 dark:border-slate-800">
            <?php echo e($records->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // MARC tag and value validation
        const marcTag = document.querySelector('select[name="marc_tag"]');
        const marcValue = document.querySelector('input[name="marc_value"]');

        function validateMarcFields() {
            if (marcTag && marcValue) {
                if (marcTag.value && !marcValue.value) {
                    marcValue.setCustomValidity("<?php echo e(__('Please enter a MARC value when tag is selected')); ?>");
                } else if (!marcTag.value && marcValue.value) {
                    marcTag.setCustomValidity("<?php echo e(__('Please select a MARC tag when value is entered')); ?>");
                } else {
                    marcValue.setCustomValidity('');
                    marcTag.setCustomValidity('');
                }
            }
        }

        if (marcTag && marcValue) {
            marcTag.addEventListener('change', validateMarcFields);
            marcValue.addEventListener('input', validateMarcFields);
        }

        // Delete functionality
        document.querySelectorAll('.delete-record').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                const title = this.dataset.title;

                Swal.fire({
                    title: '<?php echo e(__("Are you sure?")); ?>',
                    text: `<?php echo e(__("You are about to delete")); ?>: ${title}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<?php echo e(__("Yes, delete it!")); ?>',
                    cancelButtonText: '<?php echo e(__("Cancel")); ?>',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/topsecret/marc-books/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        '<?php echo e(__("Deleted!")); ?>',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        '<?php echo e(__("Error!")); ?>',
                                        data.message,
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    '<?php echo e(__("Error!")); ?>',
                                    '<?php echo e(__("An error occurred while deleting")); ?>',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    });
    
    // Double-click to edit with tab=0
    document.querySelectorAll('tbody tr[data-edit-url]').forEach(row => {
        row.addEventListener('dblclick', function() {
            const editUrl = this.getAttribute('data-edit-url');
            window.location.href = editUrl + '?tab=0';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_books/index.blade.php ENDPATH**/ ?>