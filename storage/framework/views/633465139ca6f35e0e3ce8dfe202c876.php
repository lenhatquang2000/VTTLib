<?php $__env->startSection('extra_css'); ?>
<style>
    /* CKEditor/TinyMCE Dark Mode Overrides */
    .tox-tinymce { border-radius: 0.375rem !important; border-color: hsl(var(--border)) !important; }
    .dark .tox .tox-edit-area__iframe { background-color: transparent !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 bg-card p-3 rounded-md border border-border shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 rounded-md flex items-center justify-center text-primary border border-primary/10">
                <i data-lucide="<?php echo e($resource->id ? 'edit' : 'plus-circle'); ?>" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-0.5">
                    <span><?php echo e(__('Biên mục')); ?></span>
                    <i data-lucide="chevron-right" class="w-3 h-3 opacity-50"></i>
                    <span><?php echo e(__('Tài liệu số')); ?></span>
                </div>
                <h1 class="text-lg font-bold text-foreground tracking-tight">
                    <?php echo e($resource->id ? __('Chỉnh sửa tài liệu số') : __('Biên mục tài liệu số mới')); ?>

                </h1>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.digital-cataloging.index', ['category_id' => $folder->id])); ?>" 
               class="inline-flex items-center px-3 py-2 bg-muted hover:bg-muted/80 text-muted-foreground text-xs font-bold rounded border border-border shadow-sm active:scale-95 transition-all group">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <?php echo e(__('Quay lại')); ?>

            </a>
        </div>
    </div>

    <form id="cataloging-form" action="<?php echo e(route('admin.digital-cataloging.store')); ?>" method="POST" enctype="multipart/form-data" novalidate class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo e($resource->id); ?>">
        <input type="hidden" name="folder_id" value="<?php echo e($folder->id); ?>">

        <!-- Left Column: Media & Sidebar (4/12) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-card border border-border rounded-md p-4 shadow-sm text-center space-y-3">
                <h3 class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Ảnh bìa tài liệu')); ?></h3>
                <div class="relative group inline-block">
                    <img id="cover-preview" 
                         src="<?php echo e($resource->id ? $resource->thumbnail_url : 'https://placehold.co/300x450/1e293b/white?text=No+Cover'); ?>" 
                         class="w-32 h-44 object-cover rounded-sm border border-border shadow-sm transition-all group-hover:ring-2 group-hover:ring-primary/30">
                    <label class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 rounded-sm cursor-pointer transition-opacity">
                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                        <input type="file" name="cover_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                <p class="text-[9px] text-muted-foreground italic italic"><?php echo e(__('Kích thước gợi ý: 300x450px. Max 2MB.')); ?></p>
            </div>

            <!-- Storage Info Card -->
            <div class="bg-card border border-border rounded-md p-3 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider"><?php echo e(__('Thông tin lưu trữ')); ?></h3>
                </div>
                <div class="p-2 bg-muted/50 rounded-sm border border-border space-y-1">
                    <div class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Phân mục hiện tại')); ?></div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="folder" class="w-3.5 h-3.5 text-primary"></i>
                        <span class="text-xs font-bold text-foreground"><?php echo e($folder->folder_name); ?></span>
                    </div>
                </div>
                <?php if(!$resource->id): ?>
                <button type="button" onclick="generateTestData()" class="w-full py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 font-bold text-[10px] uppercase tracking-widest rounded border border-amber-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i data-lucide="wand-2" class="w-3.5 h-3.5"></i>
                    <?php echo e(__('Dữ liệu mẫu')); ?>

                </button>
                <?php endif; ?>
            </div>

            <!-- Status Toggle Card -->
            <div class="bg-card border border-border rounded-md p-3 shadow-sm space-y-3" x-data="{ isPublished: <?php echo e($resource->status === 'published' ? 'true' : ($resource->id ? 'false' : 'true')); ?> }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-foreground">
                        <i data-lucide="send" class="w-4 h-4" :class="isPublished ? 'text-emerald-500' : 'text-muted-foreground'"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider"><?php echo e(__('Trạng thái')); ?></h3>
                    </div>
                    <!-- Toggle Switch -->
                    <button type="button" @click="isPublished = !isPublished" 
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="isPublished ? 'bg-emerald-500' : 'bg-muted'">
                        <input type="hidden" name="status" :value="isPublished ? 'published' : 'draft'">
                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                              :class="isPublished ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
                <div class="p-2 rounded-sm border border-dashed transition-colors"
                     :class="isPublished ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-muted/30 border-border'">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full" :class="isPublished ? 'bg-emerald-500 animate-pulse' : 'bg-muted-foreground'"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="isPublished ? 'text-emerald-600' : 'text-muted-foreground'">
                            <span x-text="isPublished ? '<?php echo e(__('Đã ban hành')); ?>' : '<?php echo e(__('Đang chờ duyệt')); ?>'"></span>
                        </span>
                    </div>
                    <p class="text-[9px] text-muted-foreground mt-1 leading-tight" x-text="isPublished ? '<?php echo e(__('Tài liệu sẽ hiển thị công khai cho độc giả.')); ?>' : '<?php echo e(__('Tài liệu sẽ được lưu dưới dạng bản nháp.')); ?>'"></p>
                </div>
            </div>

            <!-- PDF File Card -->
            <div class="bg-primary/5 border border-primary/10 rounded-md p-3 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <i data-lucide="file-digit" class="w-4 h-4"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider"><?php echo e(__('Tệp tài liệu (PDF)')); ?></h3>
                </div>
                
                <?php if($resource->file_path): ?>
                <div class="flex items-center gap-2 p-2 bg-background border border-border rounded-sm mb-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                    <div class="min-w-0 flex-1 text-[10px]">
                        <div class="font-bold text-foreground truncate"><?php echo e($resource->file_name); ?></div>
                        <div class="text-muted-foreground uppercase"><?php echo e(number_format($resource->file_size / 1024, 1)); ?> KB</div>
                    </div>
                </div>
                <?php endif; ?>

                <label class="flex flex-col items-center justify-center w-full py-4 border-2 border-dashed border-primary/20 rounded-md bg-background hover:bg-primary/5 cursor-pointer transition-all group">
                    <i data-lucide="upload-cloud" class="w-6 h-6 text-primary/50 group-hover:scale-110 transition-transform mb-2"></i>
                    <span id="file-name-display" class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">
                        <?php echo e($resource->id ? __('Thay đổi tệp PDF') : __('Tải lên tệp PDF')); ?>

                    </span>
                    <input type="file" name="file_resource" class="hidden" accept=".pdf" onchange="displayFileName(this)">
                </label>
            </div>
        </div>

        <!-- Right Column: Form Fields (8/12) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-card border border-border rounded-md p-4 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Tiêu đề tài liệu *')); ?></label>
                        <input type="text" name="title" value="<?php echo e(old('title', $resource->title)); ?>"
                               class="w-full h-10 px-3 bg-background border border-border rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50">
                    </div>

                    <!-- Authors -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Tác giả chính')); ?></label>
                        <?php $authorsValue = is_array($resource->authors) ? implode(', ', $resource->authors) : $resource->authors; ?>
                        <input type="text" name="authors" value="<?php echo e(old('authors', $authorsValue)); ?>"
                               class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- Resource Type -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Loại tài liệu')); ?></label>
                        <select name="resource_type" class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                            <option value="Tài liệu số" <?php echo e($resource->resource_type == 'Tài liệu số' ? 'selected' : ''); ?>>Tài liệu số</option>
                            <option value="Sách điện tử" <?php echo e($resource->resource_type == 'Sách điện tử' ? 'selected' : ''); ?>>Sách điện tử</option>
                            <option value="Bài giảng" <?php echo e($resource->resource_type == 'Bài giảng' ? 'selected' : ''); ?>>Bài giảng</option>
                        </select>
                    </div>

                    <!-- Language -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Ngôn ngữ')); ?></label>
                        <select name="language" class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                            <option value="Tiếng Việt" <?php echo e($resource->language == 'Tiếng Việt' ? 'selected' : ''); ?>>Tiếng Việt</option>
                            <option value="Tiếng Anh" <?php echo e($resource->language == 'Tiếng Anh' ? 'selected' : ''); ?>>Tiếng Anh</option>
                        </select>
                    </div>

                    <!-- Publisher -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Nhà xuất bản')); ?></label>
                        <input type="text" name="publisher" value="<?php echo e(old('publisher', $resource->publisher)); ?>"
                               class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- Publish Year -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Năm phát hành')); ?></label>
                        <input type="text" name="publish_year" value="<?php echo e(old('publish_year', $resource->publish_year)); ?>"
                               class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- Identifier -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Mã định danh (ISBN/ISSN)')); ?></label>
                        <input type="text" name="identifier" value="<?php echo e(old('identifier', $resource->identifier)); ?>"
                               class="w-full h-9 px-3 bg-background border border-border rounded-sm text-xs focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1"><?php echo e(__('Mô tả / Tóm tắt nội dung')); ?></label>
                        <div class="rounded-sm border border-border overflow-hidden shadow-inner">
                            <textarea name="description" id="editor"><?php echo e(old('description', $resource->description)); ?></textarea>
                        </div>
                    </div>

                    <!-- Additional Metadata (3 columns) -->
                    <div class="md:col-span-2 grid grid-cols-3 gap-3 p-3 bg-muted/20 rounded-md border border-border border-dashed mt-2">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Nguồn gốc')); ?></label>
                            <input type="text" name="source" value="<?php echo e(old('source', $resource->source)); ?>" class="w-full h-8 px-2 bg-background border border-border rounded-sm text-[11px] outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Phạm vi')); ?></label>
                            <input type="text" name="coverage" value="<?php echo e(old('coverage', $resource->coverage)); ?>" class="w-full h-8 px-2 bg-background border border-border rounded-sm text-[11px] outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Bản quyền')); ?></label>
                            <input type="text" name="copyright" value="<?php echo e(old('copyright', $resource->copyright)); ?>" class="w-full h-8 px-2 bg-background border border-border rounded-sm text-[11px] outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-border">
                    <div class="text-[10px] text-muted-foreground italic flex items-center gap-1.5">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                        <?php echo e(__('Dữ liệu sẽ được công khai ngay sau khi lưu.')); ?>

                    </div>
                    <div class="flex gap-2">
                        <button type="reset" class="px-4 py-2 bg-muted hover:bg-muted/80 text-muted-foreground font-bold text-[10px] uppercase tracking-widest rounded border border-border active:scale-95 transition-all">
                            <?php echo e(__('Làm mới')); ?>

                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-primary hover:bg-primary/90 text-primary-foreground font-black text-[10px] uppercase tracking-widest rounded shadow-md shadow-primary/20 active:scale-[0.98] transition-all border border-primary/10">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                            <?php echo e($resource->id ? __('Cập nhật biên mục') : __('Hoàn tất biên mục')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/<?php echo e(env('TinyEMC', 'no-api-key')); ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    tinymce.init({
        selector: '#editor',
        height: 350,
        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'],
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | fullscreen preview code',
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        language: 'vi',
        content_style: 'body { font-family:sans-serif; font-size:13px; }'
    });
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = (e) => document.getElementById('cover-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

function displayFileName(input) {
    const display = document.getElementById('file-name-display');
    if (input.files && input.files[0]) {
        display.innerHTML = `<span class="text-primary font-bold">${input.files[0].name}</span>`;
    }
}

// Form validation with SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cataloging-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const titleInput = document.querySelector('input[name="title"]');
            const fileInput = document.querySelector('input[name="file_resource"]');
            const idInput = document.querySelector('input[name="id"]');
            const isEdit = idInput && idInput.value !== '';
            
            // Xóa khoảng trắng thừa
            if (titleInput) titleInput.value = titleInput.value.trim();

            if (!titleInput || titleInput.value === '') {
                e.preventDefault();
                if (window.SwalHelper) {
                    window.SwalHelper.showWarning('Thiếu thông tin!', 'Vui lòng nhập tiêu đề tài liệu.');
                } else {
                    alert('Vui lòng nhập tiêu đề tài liệu.');
                }
                titleInput?.focus();
                return false;
            }

            if (!isEdit && (!fileInput || !fileInput.files || fileInput.files.length === 0)) {
                e.preventDefault();
                if (window.SwalHelper) {
                    window.SwalHelper.showWarning('Thiếu tệp tin!', 'Vui lòng chọn tệp PDF để tải lên.');
                } else {
                    alert('Vui lòng chọn tệp PDF để tải lên.');
                }
                return false;
            }
        });
    }
});

function generateTestData() {
    document.querySelector('input[name="title"]').value = "Tài liệu mẫu " + Math.floor(Math.random() * 1000);
    document.querySelector('input[name="authors"]').value = "GS.TS. Nguyễn Văn A, ThS. Trần Thị B";
    document.querySelector('input[name="publisher"]').value = "NXB Đại học Võ Trường Toản";
    document.querySelector('input[name="publish_year"]').value = "2024";
    document.querySelector('input[name="identifier"]').value = "VTTU-ISBN-" + Math.floor(Math.random() * 1000000);
    
    // Fill Metadata fields
    document.querySelector('input[name="source"]').value = "Thư viện Trung tâm VTTU";
    document.querySelector('input[name="coverage"]').value = "Lưu hành nội bộ / Đào tạo";
    document.querySelector('input[name="copyright"]').value = "VTTU © 2024 - Bảo lưu mọi quyền";

    // Fill TinyMCE (Description)
    if (typeof tinymce !== 'undefined' && tinymce.get('editor')) {
        tinymce.get('editor').setContent('<h3>Tóm tắt nội dung:</h3><p>Đây là tài liệu nghiên cứu chuyên sâu về các kỹ thuật lập trình và quản lý hệ thống hiện đại.</p><ul><li>Chương 1: Giới thiệu tổng quan</li><li>Chương 2: Cơ sở lý thuyết</li><li>Chương 3: Thực nghiệm và kết quả</li></ul><p>Phù hợp cho sinh viên và giảng viên chuyên ngành Công nghệ thông tin.</p>');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/digital_cataloging/create.blade.php ENDPATH**/ ?>