<?php $__env->startSection('content'); ?>
<div class="space-y-4 pb-10 px-4 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-card p-4 rounded-md border border-border sticky top-0 z-20 backdrop-blur-sm">
        <div>
            <h1 class="text-lg font-bold text-foreground"><?php echo e(__('Chỉnh sửa Tin tức')); ?></h1>
            <p class="text-xs text-muted-foreground mt-0.5"><?php echo e(__('Cập nhật bài viết: ')); ?> <span class="font-medium text-primary"><?php echo e(Str::limit($news->title, 50)); ?></span></p>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a href="<?php echo e($news->url); ?>" target="_blank"
               class="flex-1 sm:flex-none inline-flex items-center justify-center h-9 px-4 rounded-sm bg-accent hover:bg-accent/80 text-accent-foreground font-medium text-xs transition-all active:scale-95 group border border-border">
                <i data-lucide="eye" class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform"></i>
                <?php echo e(__('Xem')); ?>

            </a>
            <a href="<?php echo e(route('admin.news.index')); ?>" 
               class="flex-1 sm:flex-none inline-flex items-center justify-center h-9 px-4 rounded-sm bg-muted hover:bg-muted/80 text-muted-foreground font-medium text-xs transition-all active:scale-95 group border border-border">
                <i data-lucide="chevron-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="<?php echo e(route('admin.news.update', $news)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-4">
                <!-- Basic Information -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Thông tin cơ bản
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tiêu đề *</label>
                            <input type="text" name="title" required
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" 
                                   placeholder="Nhập tiêu đề bài viết"
                                   value="<?php echo e(old('title', $news->title)); ?>">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Slug (URL)</label>
                            <input type="text" name="slug" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
                                   placeholder="Tự động tạo từ tiêu đề"
                                   value="<?php echo e(old('slug', $news->slug)); ?>">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tóm tắt</label>
                            <textarea name="summary" rows="2"
                                      class="flex min-h-[60px] w-full rounded-sm border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                                      placeholder="Tóm tắt nội dung bài viết"><?php echo e(old('summary', $news->summary)); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Nội dung *</label>
                            <div class="border border-input rounded-sm overflow-hidden">
                                <textarea name="content" id="content" rows="15"
                                          class="w-full bg-background text-sm focus:outline-none"><?php echo e(old('content', $news->content)); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        SEO
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Meta Title</label>
                            <input type="text" name="meta_title" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
                                   placeholder="Tiêu đề SEO"
                                   value="<?php echo e(old('meta_title', $news->meta_title)); ?>">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Meta Description</label>
                            <textarea name="meta_description" rows="2"
                                      class="flex min-h-[60px] w-full rounded-sm border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                                      placeholder="Mô tả SEO"><?php echo e(old('meta_description', $news->meta_description)); ?></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Meta Keywords</label>
                            <input type="text" name="meta_keywords" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
                                   placeholder="từ khóa 1, từ khóa 2, từ khóa 3"
                                   value="<?php echo e(old('meta_keywords', $news->meta_keywords)); ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-4">
                <!-- Publication Settings -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Xuất bản
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Trạng thái *</label>
                            <select name="status" class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
                                <option value="draft" <?php echo e(old('status', $news->status) == 'draft' ? 'selected' : ''); ?>>Bản nháp</option>
                                <option value="pending" <?php echo e(old('status', $news->status) == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                                <option value="published" <?php echo e(old('status', $news->status) == 'published' ? 'selected' : ''); ?>>Đã đăng</option>
                                <option value="archived" <?php echo e(old('status', $news->status) == 'archived' ? 'selected' : ''); ?>>Lưu trữ</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Ngày đăng</label>
                            <input type="datetime-local" name="published_at" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                                   value="<?php echo e(old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '')); ?>">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Ngày hết hạn</label>
                            <input type="datetime-local" name="expired_at" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                                   value="<?php echo e(old('expired_at', $news->expired_at ? $news->expired_at->format('Y-m-d\TH:i') : '')); ?>">
                        </div>
                        
                        <div class="space-y-2 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="is_featured" id="is_featured" 
                                       class="rounded-sm border-input bg-background text-primary focus:ring-primary w-4 h-4" <?php echo e(old('is_featured', $news->is_featured) ? 'checked' : ''); ?>>
                                <span class="text-xs font-medium text-foreground group-hover:text-primary transition-colors">Tin nổi bật</span>
                            </label>
                            
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="allow_comments" id="allow_comments" 
                                       class="rounded-sm border-input bg-background text-primary focus:ring-primary w-4 h-4" <?php echo e(old('allow_comments', $news->allow_comments) ? 'checked' : ''); ?>>
                                <span class="text-xs font-medium text-foreground group-hover:text-primary transition-colors">Cho phép bình luận</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Media -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        Media
                    </h3>
                    <div class="space-y-4" x-data="{ uploadType: 'url' }">
                        <div class="flex p-1 bg-muted rounded-sm border border-border">
                            <button type="button" @click="uploadType = 'url'" 
                                    :class="uploadType === 'url' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                                    class="flex-1 py-1 text-[10px] font-bold uppercase tracking-widest rounded-sm transition-all">
                                URL
                            </button>
                            <button type="button" @click="uploadType = 'upload'" 
                                    :class="uploadType === 'upload' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                                    class="flex-1 py-1 text-[10px] font-bold uppercase tracking-widest rounded-sm transition-all">
                                Tải lên
                            </button>
                        </div>

                        <div x-show="uploadType === 'url'">
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Hình đại diện (URL)</label>
                            <input type="text" name="featured_image" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring" 
                                   placeholder="Nhập URL hình ảnh"
                                   value="<?php echo e(old('featured_image', $news->featured_image)); ?>"
                                   oninput="handleUrlPreview(this.value)">
                        </div>
                        
                        <div x-show="uploadType === 'upload'" x-cloak>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tải ảnh đại diện mới</label>
                            <input type="hidden" name="image_removed" id="image_removed" value="0">
                            <div class="relative group">
                                <input type="file" name="featured_image_file" accept="image/*"
                                       class="hidden" id="featured_image_file"
                                       onchange="previewImage(this)">
                                <label for="featured_image_file" 
                                       class="flex flex-col items-center justify-center w-full h-24 border border-dashed border-input rounded-sm bg-background/50 cursor-pointer hover:bg-accent/50 transition-all">
                                    <i data-lucide="upload-cloud" class="w-6 h-6 text-muted-foreground mb-1"></i>
                                    <p class="text-[10px] text-muted-foreground font-medium">Click hoặc kéo thả ảnh</p>
                                </label>
                            </div>
                        </div>
                        
                        <div id="image-preview-container" class="<?php echo e($news->featured_image ? '' : 'hidden'); ?>">
                            <div class="flex justify-between items-center mb-1.5">
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Xem trước:</p>
                                <button type="button" onclick="removeImage()" 
                                        class="text-[10px] font-bold text-destructive hover:underline flex items-center">
                                    <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i> Xóa
                                </button>
                            </div>
                            <div class="relative rounded-sm overflow-hidden border border-border aspect-video bg-muted">
                                <img id="image-preview" src="<?php echo e($news->featured_image); ?>" 
                                     class="w-full h-full object-cover">
                                <div id="image-error-msg" class="hidden absolute inset-0 bg-destructive/10 backdrop-blur-[2px] flex items-center justify-center text-destructive text-[10px] font-bold p-2 text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Categorization -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                        Phân loại
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Chuyên mục</label>
                            <select name="category_id" class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
                                <option value="">-- Chọn chuyên mục --</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $news->category_id) == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tags</label>
                            <input type="text" name="tags" 
                                   class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring" 
                                   placeholder="tag1, tag2, tag3"
                                   value="<?php echo e(old('tags', $news->tags->pluck('name')->implode(', '))); ?>">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Ngôn ngữ</label>
                            <select name="language" class="flex h-9 w-full rounded-sm border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring">
                                <option value="vi" <?php echo e(old('language', $news->language) == 'vi' ? 'selected' : ''); ?>>Tiếng Việt</option>
                                <option value="en" <?php echo e(old('language', $news->language) == 'en' ? 'selected' : ''); ?>>English</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-card rounded-md border border-border p-4 shadow-sm">
                    <h3 class="text-sm font-bold mb-4 text-primary flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        Thống kê
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-muted/50 p-2 rounded-sm border border-border">
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Lượt xem</p>
                            <p class="text-sm font-black text-foreground mt-0.5"><?php echo e(number_format($news->view_count)); ?></p>
                        </div>
                        <div class="bg-muted/50 p-2 rounded-sm border border-border">
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Bình luận</p>
                            <p class="text-sm font-black text-foreground mt-0.5"><?php echo e(number_format($news->comment_count)); ?></p>
                        </div>
                        <div class="col-span-2 bg-muted/50 p-2 rounded-sm border border-border">
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Cập nhật lần cuối</p>
                            <p class="text-xs font-medium text-foreground mt-0.5"><?php echo e($news->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-border">
            <button type="submit" name="save_draft" value="1" 
                    class="h-9 px-5 rounded-sm bg-accent hover:bg-accent/80 text-accent-foreground font-bold text-xs transition-all active:scale-95 border border-border">
                <i data-lucide="save" class="w-4 h-4 mr-2 inline-block"></i>
                Lưu nháp
            </button>
            <button type="submit" 
                    class="h-9 px-6 rounded-sm bg-primary hover:bg-primary/90 text-primary-foreground font-black text-xs transition-all active:scale-95 shadow-sm">
                <i data-lucide="check" class="w-4 h-4 mr-2 inline-block"></i>
                Cập nhật bài viết
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.tiny.cloud/1/<?php echo e(env('TinyEMC')); ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        branding: false,
        promotion: false,
        language: 'vi',
        skin: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide'),
        content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'),
    });

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const errorMsg = document.getElementById('image-error-msg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
            errorMsg.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function handleUrlPreview(url) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const errorMsg = document.getElementById('image-error-msg');
    const defaultImage = "https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1000&q=80";

    if (url.trim() === '') {
        container.classList.add('hidden');
        return;
    }

    container.classList.remove('hidden');
    preview.src = url;
    errorMsg.classList.add('hidden');

    preview.onerror = function() {
        preview.src = defaultImage;
        errorMsg.classList.remove('hidden');
        errorMsg.innerText = "⚠️ Lỗi URL ảnh";
    };
}

function removeImage() {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const urlInput = document.querySelector('input[name="featured_image"]');
    const fileInput = document.getElementById('featured_image_file');
    const removedInput = document.getElementById('image_removed');
    
    if (urlInput) urlInput.value = '';
    if (fileInput) fileInput.value = '';
    if (removedInput) removedInput.value = '1';
    
    container.classList.add('hidden');
    preview.src = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === '<?php echo e($news->slug); ?>') {
                const slug = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });
    }
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

// Fix TinyMCE validation
document.querySelector('form').addEventListener('submit', function(e) {
    const submitButton = e.submitter;
    
    // Manual validation for TinyMCE content
    if (tinymce.get('content')) {
        const content = tinymce.get('content').getContent();
        if (!content || content.trim() === '') {
            e.preventDefault();
            alert('Vui lòng nhập nội dung bài viết!');
            tinymce.get('content').focus();
            return false;
        }
    }

    if (submitButton && submitButton.name === 'save_draft') {
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = 'draft';
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/news/edit.blade.php ENDPATH**/ ?>