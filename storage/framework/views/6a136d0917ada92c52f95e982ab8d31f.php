<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e(__('Tạo Tin tức mới')); ?></h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo e(__('Tạo bài viết tin tức mới')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.news.index')); ?>" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i><?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card-admin p-6">
        <form action="<?php echo e(route('admin.news.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-blue-400">Thông tin cơ bản</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tiêu đề *</label>
                                <input type="text" name="title" required
                                       class="input-field w-full" 
                                       placeholder="Nhập tiêu đề bài viết"
                                       value="<?php echo e(old('title')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Slug (URL)</label>
                                <input type="text" name="slug" 
                                       class="input-field w-full" 
                                       placeholder="Tự động tạo từ tiêu đề"
                                       value="<?php echo e(old('slug')); ?>">
                                <p class="text-xs text-gray-400 mt-1">Để trống để tự động tạo</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tóm tắt</label>
                                <textarea name="summary" rows="3"
                                          class="input-field w-full"
                                          placeholder="Tóm tắt nội dung bài viết"><?php echo e(old('summary')); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Nội dung *</label>
                                <textarea name="content" id="content" rows="15"
                                          class="input-field w-full"
                                          placeholder="Nội dung chi tiết bài viết" required><?php echo e(old('content')); ?></textarea>
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
                                       value="<?php echo e(old('meta_title')); ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="input-field w-full"
                                          placeholder="Mô tả SEO"><?php echo e(old('meta_description')); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                                <input type="text" name="meta_keywords" 
                                       class="input-field w-full" 
                                       placeholder="từ khóa 1, từ khóa 2, từ khóa 3"
                                       value="<?php echo e(old('meta_keywords')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publication Settings -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-green-400">Xuất bản</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Trạng thái *</label>
                                <select name="status" class="input-field w-full">
                                    <option value="draft" <?php echo e(old('status') == 'draft' ? 'selected' : ''); ?>>Bản nháp</option>
                                    <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                                    <option value="published" <?php echo e(old('status') == 'published' ? 'selected' : ''); ?>>Đã đăng</option>
                                    <option value="archived" <?php echo e(old('status') == 'archived' ? 'selected' : ''); ?>>Lưu trữ</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày đăng</label>
                                <input type="datetime-local" name="published_at" 
                                       class="input-field w-full"
                                       value="<?php echo e(old('published_at') ?? now()->format('Y-m-d\TH:i')); ?>">
                                <p class="text-xs text-gray-400 mt-1">Để trống để sử dụng thời gian hiện tại</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày hết hạn</label>
                                <input type="datetime-local" name="expired_at" 
                                       class="input-field w-full"
                                       value="<?php echo e(old('expired_at')); ?>">
                                <p class="text-xs text-gray-400 mt-1">Để trống nếu không có hạn</p>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" 
                                       class="mr-2" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                                <label for="is_featured" class="text-sm">Tin nổi bật</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_comments" id="allow_comments" 
                                       class="mr-2" <?php echo e(old('allow_comments') ? 'checked' : ''); ?>>
                                <label for="allow_comments" class="text-sm">Cho phép bình luận</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Media -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-purple-400">Media</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Hình đại diện</label>
                                <input type="text" name="featured_image" 
                                       class="input-field w-full" 
                                       placeholder="URL hình ảnh"
                                       value="<?php echo e(old('featured_image')); ?>">
                                <p class="text-xs text-gray-400 mt-1">Nhập URL hình ảnh</p>
                            </div>
                            
                            <?php if(old('featured_image')): ?>
                                <div class="mt-2">
                                    <img src="<?php echo e(old('featured_image')); ?>" alt="Preview" 
                                         class="w-full h-32 object-cover rounded">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Categorization -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-yellow-400">Phân loại</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Chuyên mục</label>
                                <select name="category_id" class="input-field w-full">
                                    <option value="">-- Chọn chuyên mục --</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tags</label>
                                <input type="text" name="tags" 
                                       class="input-field w-full" 
                                       placeholder="tag1, tag2, tag3"
                                       value="<?php echo e(old('tags') ? implode(', ', old('tags')) : ''); ?>">
                                <p class="text-xs text-gray-400 mt-1">Ngăn cách bằng dấu phẩy</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngôn ngữ</label>
                                <select name="language" class="input-field w-full">
                                    <option value="vi" <?php echo e(old('language') == 'vi' ? 'selected' : ''); ?>>Tiếng Việt</option>
                                    <option value="en" <?php echo e(old('language') == 'en' ? 'selected' : ''); ?>>English</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex justify-end gap-2 mt-6 pt-6 border-t border-gray-700">
                <a href="<?php echo e(route('admin.news.index')); ?>" class="btn-secondary">
                    Hủy
                </a>
                <button type="submit" name="save_draft" value="1" class="btn-secondary">
                    <i class="fas fa-save mr-2"></i>Lưu nháp
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>Đăng tin
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-generate slug from title
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value) {
                // Simple slug generation
                const slug = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });
    }
});

// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const submitButton = e.submitter;
    
    if (submitButton && submitButton.name === 'save_draft') {
        // Set status to draft when saving draft
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = 'draft';
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/news/create.blade.php ENDPATH**/ ?>