<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e(__('Chỉnh sửa Node')); ?></h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo e(__('Cập nhật thông tin trang: ')); ?><?php echo e($siteNode->display_name); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.site-nodes.index', ['language' => $siteNode->language])); ?>" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i><?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card-admin p-6">
        <form action="<?php echo e(route('admin.site-nodes.update', $siteNode)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div class="border-b border-gray-700 pb-4">w
                        <h3 class="text-lg font-bold mb-4 text-blue-400">Thông tin cơ bản</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Mã Node *</label>
                                <input type="text" name="node_code" required
                                       class="input-field w-full" 
                                       placeholder="vi-du: about, contact"
                                       value="<?php echo e(old('node_code', $siteNode->node_code)); ?>">
                                <p class="text-xs text-gray-400 mt-1">Mã duy nhất, không dấu, dùng cho URL</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tên Node *</label>
                                <input type="text" name="node_name" required
                                       class="input-field w-full" 
                                       placeholder="Tên nội bộ"
                                       value="<?php echo e(old('node_name', $siteNode->node_name)); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tên hiển thị *</label>
                                <input type="text" name="display_name" required
                                       class="input-field w-full" 
                                       placeholder="Tên hiển thị trên menu"
                                       value="<?php echo e(old('display_name', $siteNode->display_name)); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Mô tả</label>
                                <textarea name="description" rows="3"
                                          class="input-field w-full"
                                          placeholder="Mô tả ngắn về node"><?php echo e(old('description', $siteNode->description)); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Node cha</label>
                                <select name="parent_id" class="input-field w-full">
                                    <option value="">-- Gốc (Root) --</option>
                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>" <?php echo e(old('parent_id', $siteNode->parent_id) == $id ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Biểu tượng</label>
                                <input type="text" name="icon" 
                                       class="input-field w-full" 
                                       placeholder="fas fa-home"
                                       value="<?php echo e(old('icon', $siteNode->icon)); ?>">
                                <p class="text-xs text-gray-400 mt-1">Font Awesome class</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Display Settings -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-green-400">Hiển thị</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Kiểu hiển thị</label>
                                <select name="display_type" class="input-field w-full">
                                    <?php $__currentLoopData = $displayTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('display_type', $siteNode->display_type) == $value ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Mở link</label>
                                <select name="target" class="input-field w-full">
                                    <option value="_self" <?php echo e(old('target', $siteNode->target) == '_self' ? 'selected' : ''); ?>>
                                        Cùng tab (_self)
                                    </option>
                                    <option value="_blank" <?php echo e(old('target', $siteNode->target) == '_blank' ? 'selected' : ''); ?>>
                                        Tab mới (_blank)
                                    </option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Thứ tự</label>
                                <input type="number" name="sort_order" 
                                       class="input-field w-full" 
                                       min="0"
                                       value="<?php echo e(old('sort_order', $siteNode->sort_order)); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngôn ngữ</label>
                                <select name="language" class="input-field w-full">
                                    <option value="vi" <?php echo e(old('language', $siteNode->language) == 'vi' ? 'selected' : ''); ?>>
                                        Tiếng Việt
                                    </option>
                                    <option value="en" <?php echo e(old('language', $siteNode->language) == 'en' ? 'selected' : ''); ?>>
                                        English
                                    </option>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="mr-2" <?php echo e(old('is_active', $siteNode->is_active) ? 'checked' : ''); ?>>
                                <label for="is_active" class="text-sm">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Access Control -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-yellow-400">Quyền truy cập</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Loại truy cập</label>
                                <select name="access_type" id="access_type" 
                                        class="input-field w-full"
                                        onchange="toggleAccessOptions()">
                                    <?php $__currentLoopData = $accessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('access_type', $siteNode->access_type) == $value ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div id="role_options" class="<?php echo e(old('access_type', $siteNode->access_type) !== 'roles' ? 'hidden' : ''); ?>">
                                <label class="block text-sm font-medium mb-1">Vai trò được phép</label>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="allowed_roles[]" value="admin" 
                                               class="mr-2" <?php echo e(in_array('admin', old('allowed_roles', $siteNode->allowed_roles ?? [])) ? 'checked' : ''); ?>>
                                        <label class="text-sm">Admin</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="allowed_roles[]" value="manager" 
                                               class="mr-2" <?php echo e(in_array('manager', old('allowed_roles', $siteNode->allowed_roles ?? [])) ? 'checked' : ''); ?>>
                                        <label class="text-sm">Manager</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="allowed_roles[]" value="librarian" 
                                               class="mr-2" <?php echo e(in_array('librarian', old('allowed_roles', $siteNode->allowed_roles ?? [])) ? 'checked' : ''); ?>>
                                        <label class="text-sm">Librarian</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_guest" id="allow_guest" 
                                       class="mr-2" <?php echo e(old('allow_guest', $siteNode->allow_guest) ? 'checked' : ''); ?>>
                                <label for="allow_guest" class="text-sm">Cho phép khách</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Link Settings -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-purple-400">Link & Nội dung</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Route hệ thống</label>
                                <select name="route_name" id="route_name" 
                                        class="input-field w-full"
                                        onchange="toggleContentOptions()">
                                    <option value="">-- Chọn route --</option>
                                    <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('route_name', $siteNode->route_name) == $value ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">URL tùy chỉnh</label>
                                <input type="text" name="url" id="url"
                                       class="input-field w-full" 
                                       placeholder="https://example.com"
                                       value="<?php echo e(old('url', $siteNode->url)); ?>">
                            </div>
                            
                            <div id="content_options" <?php echo e(old('route_name', $siteNode->route_name) || old('url', $siteNode->url) ? 'style="opacity: 0.5;"' : ''); ?>>
                                <label class="block text-sm font-medium mb-1">Nội dung trang</label>
                                <textarea name="content" id="content" rows="8"
                                          class="input-field w-full"
                                          <?php echo e(old('route_name', $siteNode->route_name) || old('url', $siteNode->url) ? 'disabled' : ''); ?>

                                          placeholder="Nội dung HTML của trang"><?php echo e(old('content', $siteNode->content)); ?></textarea>
                                <p class="text-xs text-gray-400 mt-1">Hỗ trợ HTML. Để trống nếu dùng route hoặc URL.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-red-400">SEO</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Title</label>
                                <input type="text" name="meta_title" 
                                       class="input-field w-full" 
                                       placeholder="Tiêu đề SEO"
                                       value="<?php echo e(old('meta_title', $siteNode->meta_title)); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="input-field w-full"
                                          placeholder="Mô tả SEO"><?php echo e(old('meta_description', $siteNode->meta_description)); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                                <input type="text" name="meta_keywords" 
                                       class="input-field w-full" 
                                       placeholder="từ khóa 1, từ khóa 2, từ khóa 3"
                                       value="<?php echo e(old('meta_keywords', $siteNode->meta_keywords)); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex justify-end gap-2 mt-6 pt-6 border-t border-gray-700">
                <a href="<?php echo e(route('admin.site-nodes.index', ['language' => $siteNode->language])); ?>" 
                   class="btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Cập nhật Node
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
    const contentTextarea = document.getElementById('content');
    
    if (routeName || url) {
        contentOptions.style.opacity = '0.5';
        contentTextarea.disabled = true;
    } else {
        contentOptions.style.opacity = '1';
        contentTextarea.disabled = false;
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/edit.blade.php ENDPATH**/ ?>