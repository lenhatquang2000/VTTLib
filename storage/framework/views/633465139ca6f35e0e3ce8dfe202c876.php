<?php $__env->startSection('extra_css'); ?>
<style>
    /* 1. Base Styles (Light Mode) */
    .ck-editor__editable {
        min-height: 200px !important;
        border-radius: 0 0 0.75rem 0.75rem !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #d1d5db !important; /* gray-300 */
    }
    .ck.ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }

    /* 2. Dark Mode Overrides */
    .dark .ck-editor__editable {
        background-color: rgba(15, 23, 42, 0.6) !important; /* slate-900/60 */
        color: #f1f5f9 !important; /* slate-100 */
    }
    .dark .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #334155 !important; /* slate-700 */
    }
    .dark .ck.ck-toolbar {
        background-color: #1e293b !important; /* slate-800 */
        border-color: #334155 !important;
    }
    .dark .ck.ck-toolbar__separator {
        background-color: #334155 !important;
    }
    .dark .ck.ck-button {
        color: #94a3b8 !important; /* slate-400 */
    }
    .dark .ck.ck-button:hover:not(.ck-disabled), 
    .dark .ck.ck-button.ck-on:not(.ck-disabled) {
        background-color: #334155 !important;
        color: #fff !important;
    }
    .dark .ck.ck-dropdown__panel {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    .dark .ck.ck-list {
        background-color: #1e293b !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 bg-background min-h-screen font-sans">
    <form id="cataloging-form" action="<?php echo e(route('admin.digital-cataloging.store')); ?>" method="POST" enctype="multipart/form-data" class="max-w-7xl mx-auto grid grid-cols-12 gap-4">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="folder_id" value="<?php echo e($folder->id); ?>">

        <!-- Sidebar Left (3/12) -->
        <div class="col-span-12 lg:col-span-3 space-y-4">
            <!-- Cover Section -->
            <div class="text-center bg-card border border-border rounded-md p-4 shadow-sm">
                <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-3"><?php echo e(__('Ảnh bìa tài liệu')); ?></label>
                <div class="relative inline-block group">
                    <img id="cover-preview" src="https://placehold.co/300x450/1e293b/white?text=No+Cover" 
                         class="w-32 h-44 object-cover rounded-md border border-border shadow-sm transition-all group-hover:ring-2 group-hover:ring-primary/20">
                    <label class="absolute inset-0 flex items-center justify-center bg-background/40 opacity-0 group-hover:opacity-100 rounded-md cursor-pointer transition-opacity">
                        <i data-lucide="camera" class="w-6 h-6 text-foreground"></i>
                        <input type="file" name="cover_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                <p class="mt-2 text-[10px] text-muted-foreground font-medium uppercase tracking-tight">Hỗ trợ: JPG, PNG (Max 2MB)</p>
            </div>

            <!-- Folder Info -->
            <div class="p-3 bg-card border border-border rounded-md shadow-sm">
                <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-2"><?php echo e(__('Vị trí lưu trữ')); ?></label>
                <div class="flex items-center gap-2 p-2 bg-muted/50 rounded-md border border-border">
                    <div class="w-8 h-8 bg-primary/10 rounded flex items-center justify-center text-primary">
                        <i data-lucide="folder" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] font-bold text-muted-foreground uppercase tracking-wider leading-none mb-1">Phân mục</div>
                        <div class="text-xs font-bold text-foreground truncate"><?php echo e($folder->folder_name); ?></div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-border">
                    <button type="button" onclick="generateTestData()" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white rounded shadow-sm transition-all group font-bold text-[10px] uppercase tracking-wider">
                        <i data-lucide="wand-2" class="w-3.5 h-3.5 group-hover:rotate-12 transition-transform"></i>
                        <?php echo e(__('Dữ liệu mẫu nhanh')); ?>

                    </button>
                    <p class="mt-2 text-[9px] text-muted-foreground italic text-center leading-tight">Click để tự động điền các trường thông tin mẫu</p>
                </div>
            </div>
        </div>

        <!-- Main Form Right (9/12) -->
        <div class="col-span-12 lg:col-span-9 space-y-4">
            <!-- PDF Upload Section -->
            <div class="bg-primary/5 border border-primary/10 rounded-md p-3">
                <label class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-2"><?php echo e(__('Tệp tài liệu chính (PDF) *')); ?></label>
                <label class="flex items-center justify-between px-3 py-2 bg-background border border-border rounded-md cursor-pointer hover:bg-muted/50 transition-all shadow-sm">
                    <div class="flex items-center gap-2 text-foreground font-medium">
                        <i data-lucide="cloud-upload" class="w-4 h-4 text-primary"></i>
                        <span id="file-name-display" class="text-xs"><?php echo e(__('Chọn tệp PDF (Tối đa 50MB)')); ?></span>
                    </div>
                    <span class="px-2 py-1 bg-primary text-[10px] font-bold text-primary-foreground rounded uppercase tracking-wider"><?php echo e(__('Chọn file')); ?></span>
                    <input type="file" name="file_resource" class="hidden" accept="application/pdf" required onchange="displayFileName(this)">
                </label>
            </div>

            <!-- Form Fields Grid -->
            <div class="bg-card border border-border rounded-md p-4 shadow-sm space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Tiêu đề tài liệu *')); ?></label>
                        </div>
                        <input type="text" name="title" required placeholder="<?php echo e(__('Ví dụ: Giáo trình Lập trình Laravel 2026')); ?>"
                               class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Loại tài liệu *')); ?></label>
                        <select name="resource_type" class="w-full px-3 py-2 bg-background border border-border rounded text-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs appearance-none bg-[right_0.75rem_center] bg-no-repeat bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')]">
                            <option value="Tài liệu số">Tài liệu số</option>
                            <option value="Sách điện tử">Sách điện tử</option>
                            <option value="Bài giảng">Bài giảng</option>
                            <option value="Luận văn">Luận văn</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Ngôn ngữ *')); ?></label>
                        <select name="language" required class="w-full px-3 py-2 bg-background border border-border rounded text-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs appearance-none bg-[right_0.75rem_center] bg-no-repeat bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')]">
                            <option value="Tiếng Việt">Tiếng Việt</option>
                            <option value="Tiếng Anh">Tiếng Anh</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Tác giả chính')); ?></label>
                        <input type="text" name="authors" placeholder="<?php echo e(__('Họ và tên tác giả...')); ?>"
                               class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Nhà xuất bản')); ?></label>
                        <input type="text" name="publisher" placeholder="<?php echo e(__('Tên NXB...')); ?>"
                               class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Năm phát hành')); ?></label>
                        <input type="text" name="publish_year" placeholder="<?php echo e(__('Ví dụ: 2026')); ?>"
                               class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Định danh (Identifier)')); ?></label>
                        <input type="text" name="identifier" placeholder="<?php echo e(__('ISBN, ISSN hoặc Mã nội bộ...')); ?>"
                               class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                    </div>

                    <!-- TinyMCE Section -->
                    <div class="col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-foreground uppercase tracking-wider"><?php echo e(__('Mô tả / Tóm tắt nội dung')); ?></label>
                        <div class="rounded border border-border overflow-hidden">
                            <textarea name="description" id="editor"></textarea>
                        </div>
                    </div>

                    <!-- Metadata Row -->
                    <div class="col-span-2 grid grid-cols-3 gap-3 p-3 bg-muted/30 rounded-md border border-border border-dashed">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider"><?php echo e(__('Nguồn gốc')); ?></label>
                            <input type="text" name="source" placeholder="..." class="w-full px-2 py-1 bg-background border border-border rounded text-xs focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider"><?php echo e(__('Phạm vi')); ?></label>
                            <input type="text" name="coverage" placeholder="..." class="w-full px-2 py-1 bg-background border border-border rounded text-xs focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider"><?php echo e(__('Bản quyền')); ?></label>
                            <input type="text" name="copyright" placeholder="..." class="w-full px-2 py-1 bg-background border border-border rounded text-xs focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>

                    <!-- Links Section -->
                    <div class="col-span-2 grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-2 group">
                            <div class="w-8 h-8 bg-muted rounded flex items-center justify-center text-muted-foreground border border-border">
                                <i data-lucide="link" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 space-y-0.5">
                                <label class="block text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Liên kết tham chiếu (URL)')); ?></label>
                                <input type="text" name="link" placeholder="http://..." class="w-full bg-transparent border-b border-border focus:border-primary focus:ring-0 p-0 text-xs font-medium text-foreground transition-all">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 group">
                            <div class="w-8 h-8 bg-muted rounded flex items-center justify-center text-muted-foreground border border-border">
                                <i data-lucide="barcode" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 space-y-0.5">
                                <label class="block text-[9px] font-bold text-muted-foreground uppercase tracking-widest"><?php echo e(__('Liên kết biên mục (MARC)')); ?></label>
                                <input type="text" name="cataloging_link" placeholder="MARC21..." class="w-full bg-transparent border-b border-border focus:border-primary focus:ring-0 p-0 text-xs font-medium text-foreground transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-border">
                <p class="text-[10px] text-muted-foreground font-medium italic">* <?php echo e(__('Các trường đánh dấu sao là bắt buộc.')); ?></p>
                <div class="flex gap-2">
                    <button type="reset" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-muted hover:bg-muted/80 active:scale-95 text-muted-foreground font-bold rounded border border-border transition-all text-[10px] uppercase tracking-wider shadow-sm">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <?php echo e(__('Làm mới')); ?>

                    </button>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-primary hover:bg-primary/90 active:scale-95 text-primary-foreground font-bold rounded shadow-sm transition-all text-[10px] uppercase tracking-wider">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        <?php echo e(__('Hoàn tất Biên mục')); ?>

                    </button>
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
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic underline strikethrough | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'image media table emoticons | removeformat | fullscreen preview code',
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        language: 'vi',
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                const file = this.files[0];
                const reader = new FileReader();
                reader.onload = function () {
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
        content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:14px; }'
    });
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = (e) => document.getElementById('cover-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

function generateTestData() {
    const titles = [
        "Giáo trình Lập trình Laravel Core 2026",
        "Hướng dẫn triển khai Hệ thống Quản trị Thư viện",
        "Phân tích dữ liệu lớn trong giáo dục đại học",
        "Kỹ thuật lập trình PHP nâng cao và tối ưu hóa",
        "Kiến trúc Microservices với Docker và Kubernetes"
    ];
    
    const authors = ["GS.TS. Nguyễn Văn A", "ThS. Trần Thị B", "PGS.TS. Lê Văn C", "Đội ngũ kỹ thuật VTTU"];
    const publishers = ["NXB Đại học Quốc gia", "NXB Kỹ thuật", "Thư viện số VTTU", "Pearson Education"];
    const types = ["Tài liệu số", "Sách điện tử", "Bài giảng", "Luận văn"];
    
    // Fill fields
    document.querySelector('input[name="title"]').value = titles[Math.floor(Math.random() * titles.length)];
    document.querySelector('select[name="resource_type"]').value = types[Math.floor(Math.random() * types.length)];
    document.querySelector('select[name="language"]').value = "Tiếng Việt";
    document.querySelector('input[name="authors"]').value = authors[Math.floor(Math.random() * authors.length)];
    document.querySelector('input[name="publisher"]').value = publishers[Math.floor(Math.random() * publishers.length)];
    document.querySelector('input[name="publish_year"]').value = "2024";
    document.querySelector('input[name="identifier"]').value = "VTTU-" + Math.random().toString(36).substr(2, 9).toUpperCase();
    
    document.querySelector('input[name="source"]').value = "Thư viện Trung tâm VTTU";
    document.querySelector('input[name="coverage"]').value = "Lưu hành nội bộ";
    document.querySelector('input[name="copyright"]').value = "VTTU © 2024";
    
    document.querySelector('input[name="link"]').value = "https://vttu.edu.vn/ebook/" + Math.random().toString(36).substr(2, 5);
    document.querySelector('input[name="cataloging_link"]').value = "MARC-" + Math.floor(Math.random() * 1000000);

    // Fill TinyMCE
    if (tinymce.get('editor')) {
        tinymce.get('editor').setContent('<h3>Tóm tắt nội dung:</h3><p>Đây là tài liệu nghiên cứu chuyên sâu về các kỹ thuật lập trình và quản lý hệ thống hiện đại. Tài liệu bao gồm các phần:</p><ul><li>Chương 1: Giới thiệu tổng quan</li><li>Chương 2: Cơ sở lý thuyết</li><li>Chương 3: Thực nghiệm và kết quả</li></ul><p>Phù hợp cho sinh viên và giảng viên chuyên ngành Công nghệ thông tin.</p>');
    }
}

function displayFileName(input) {
    const display = document.getElementById('file-name-display');
    if (input.files && input.files[0]) {
        display.innerHTML = `<span class="text-blue-200 dark:text-blue-400 font-bold tracking-tight">${input.files[0].name}</span>`;
    } else {
        display.innerText = 'Chọn tệp PDF (Tối đa 50MB)';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/digital_cataloging/create.blade.php ENDPATH**/ ?>