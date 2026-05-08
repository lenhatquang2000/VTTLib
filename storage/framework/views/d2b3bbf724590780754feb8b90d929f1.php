<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100"><?php echo e(__('Add New Node')); ?></h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?php echo e(__('Create new page or category for website')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.site-nodes.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i><?php echo e(__('Back')); ?>

            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="<?php echo e(route('admin.site-nodes.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-blue-600 dark:text-blue-400"><?php echo e(__('Basic Information')); ?></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Node Code')); ?> *</label>
                                <input type="text" name="node_code" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('e.g: about, contact')); ?>"
                                       value="<?php echo e(old('node_code')); ?>">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><?php echo e(__('Unique code, no accents, used for URL')); ?></p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Node Name')); ?> *</label>
                                <input type="text" name="node_name" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('Internal name')); ?>"
                                       value="<?php echo e(old('node_name')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Display Name')); ?> *</label>
                                <input type="text" name="display_name" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('Display name on menu')); ?>"
                                       value="<?php echo e(old('display_name')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Description')); ?></label>
                                <textarea name="description" rows="3"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200"
                                          placeholder="<?php echo e(__('Short description about the node')); ?>"><?php echo e(old('description')); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Parent Node')); ?></label>
                                <select name="parent_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
                                    <option value="">-- <?php echo e(__('Root')); ?> --</option>
                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>" <?php echo e(old('parent_id') == $id ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?php echo e(__('Icon')); ?></label>
                                <input type="text" name="icon" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('Font Awesome class')); ?>"
                                       value="<?php echo e(old('icon')); ?>">
                                <p class="text-xs text-gray-400 mt-1"><?php echo e(__('Font Awesome class')); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Display Settings -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-green-600 dark:text-green-400"><?php echo e(__('Display Settings')); ?></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Display Type')); ?></label>
                                <select name="display_type" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <?php $__currentLoopData = $displayTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $translatedLabel = is_string($label) ? __($label) : (is_array($label) ? 'Mixed' : (string)$label);
                                        ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('display_type') == $value ? 'selected' : ''); ?>>
                                            <?php echo e(is_string($translatedLabel) ? $translatedLabel : 'Mixed'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Order')); ?></label>
                                <input type="number" name="sort_order" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       value="<?php echo e(old('sort_order', 0)); ?>">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="mr-2" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                <label for="is_active" class="text-sm text-slate-700 dark:text-slate-300"><?php echo e(__('Active')); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Access Control -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-yellow-400"><?php echo e(__('Access Control')); ?></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Access Type')); ?></label>
                                <select name="access_type" id="access_type" 
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        onchange="toggleAccessOptions()">
                                    <?php $__currentLoopData = $accessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $translatedAccess = is_string($label) ? __($label) : (is_array($label) ? 'Mixed' : (string)$label);
                                        ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('access_type') == $value ? 'selected' : ''); ?>>
                                            <?php echo e(is_string($translatedAccess) ? $translatedAccess : 'Mixed'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_guests" id="allow_guests" 
                                       class="mr-2" <?php echo e(old('allow_guests', true) ? 'checked' : ''); ?>>
                                <label for="allow_guests" class="text-sm text-slate-700 dark:text-slate-300"><?php echo e(__('Allow Guests')); ?></label>
                            </div>
                        </div>
                    </div>

                    <!-- Link & Content -->
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-purple-600 dark:text-purple-400"><?php echo e(__('Link & Content')); ?></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('System Route')); ?></label>
                                <select name="route_name" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="">-- <?php echo e(__('Select route')); ?> --</option>
                                    <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($route); ?>" <?php echo e(old('route_name') == $route ? 'selected' : ''); ?>><?php echo e($route); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Custom URL')); ?></label>
                                <input type="text" name="custom_url" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="https://example.com"
                                       value="<?php echo e(old('custom_url')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Page Content')); ?></label>
                                <textarea name="content" rows="5"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="<?php echo e(__('Page content')); ?>"><?php echo e(old('content')); ?></textarea>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><?php echo e(__('Supports HTML. Leave empty if using route or URL.')); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-rose-600 dark:text-rose-400"><?php echo e(__('SEO and Meta Tags')); ?></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Meta Title')); ?></label>
                                <input type="text" name="meta_title" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('SEO title')); ?>"
                                       value="<?php echo e(old('meta_title')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Meta Description')); ?></label>
                                <textarea name="meta_description" rows="2"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="<?php echo e(__('SEO description')); ?>"><?php echo e(old('meta_description')); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Meta Keywords')); ?></label>
                                <input type="text" name="meta_keywords" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="<?php echo e(__('SEO keywords')); ?>"
                                       value="<?php echo e(old('meta_keywords')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300"><?php echo e(__('Additional Parameters (JSON)')); ?></label>
                                <textarea name="settings" rows="2"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder='{"key": "value"}'><?php echo e(old('settings')); ?></textarea>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><?php echo e(__('JSON format for custom settings')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
                <a href="<?php echo e(route('admin.site-nodes.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                    <?php echo e(__('Cancel')); ?>

                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-200 hover:scale-[1.02]">
                    <i class="fas fa-save mr-2"></i> <?php echo e(__('Create Node')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleAccessOptions() {
    const accessType = document.getElementById('access_type').value;
    const roleOptions = document.getElementById('role_options');
    
    if (accessType === 'roles') {
        roleOptions.classList.remove('hidden');
    } else {
        roleOptions.classList.add('hidden');
    }
}

function toggleContentOptions() {
    const routeName = document.getElementById('route_name').value;
    const url = document.getElementById('url').value;
    const contentOptions = document.getElementById('content_options');
    
    if (routeName || url) {
        contentOptions.style.opacity = '0.5';
        document.getElementById('content').disabled = true;
    } else {
        contentOptions.style.opacity = '1';
        document.getElementById('content').disabled = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAccessOptions();
    toggleContentOptions();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/create.blade.php ENDPATH**/ ?>