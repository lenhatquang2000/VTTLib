@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 px-4 md:px-6 py-4">
    <!-- Header Section -->
    <div class="flex items-center gap-3 bg-card border border-border p-3 rounded-md shadow-sm">
        <a href="{{ route('admin.online-databases.index') }}" 
           class="w-8 h-8 rounded bg-muted hover:bg-accent text-muted-foreground hover:text-foreground flex items-center justify-center transition-all duration-200 border border-border"
           title="Quay lại">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-foreground">Thêm cơ sở dữ liệu trực tuyến</h1>
            <p class="text-xs text-muted-foreground mt-0.5">Điền đầy đủ thông tin để thêm một cơ sở dữ liệu mới vào danh sách.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-card border border-border rounded-md shadow-sm p-4">
        <form action="{{ route('admin.online-databases.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Title -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="title" class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Tên cơ sở dữ liệu <span class="text-destructive">*</span></label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                           class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary @error('title') border-destructive focus:ring-destructive @enderror"
                           placeholder="Ví dụ: SpringerLink, Trung tâm Học liệu CTU">
                    @error('title')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access URL -->
                <div class="space-y-1.5">
                    <label for="url" class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Đường dẫn truy cập (URL)</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}"
                           class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary @error('url') border-destructive focus:ring-destructive @enderror"
                           placeholder="https://link.springer.com">
                    @error('url')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guide URL -->
                <div class="space-y-1.5">
                    <label for="hd_url" class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Tài liệu hướng dẫn (URL)</label>
                    <input type="url" name="hd_url" id="hd_url" value="{{ old('hd_url') }}"
                           class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary @error('hd_url') border-destructive focus:ring-destructive @enderror"
                           placeholder="https://vttu.edu.vn/huong-dan-csdl.pdf">
                    @error('hd_url')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload / URL -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Ảnh/Logo cơ sở dữ liệu</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <input type="file" name="image_file" accept="image/*"
                                   class="w-full px-3 py-1 bg-background border border-border text-foreground rounded-sm text-xs focus:ring-1 focus:ring-primary focus:border-primary cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-sm file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-primary-foreground hover:file:bg-primary/90">
                            <span class="text-[10px] text-muted-foreground">Tải ảnh lên từ thiết bị (jpeg, png, svg...)</span>
                        </div>
                        <div>
                            <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}"
                                   class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary"
                                   placeholder="Hoặc nhập liên kết ảnh trực tiếp...">
                            <span class="text-[10px] text-muted-foreground">Ví dụ: https://example.com/logo.png</span>
                        </div>
                    </div>
                    @error('image_file')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                    @error('image_url')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="content" class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Mô tả chi tiết</label>
                    <textarea name="content" id="content" rows="4"
                              class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary @error('content') border-destructive focus:ring-destructive @enderror"
                              placeholder="Nhập mô tả giới thiệu về cơ sở dữ liệu này...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-destructive mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div class="space-y-1.5">
                    <label for="sort_order" class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full px-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center space-x-2 mt-4 md:col-span-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           class="w-4 h-4 text-primary border-border bg-background rounded-sm focus:ring-primary focus:ring-offset-0 cursor-pointer">
                    <label for="is_active" class="text-xs font-bold text-muted-foreground uppercase tracking-wider cursor-pointer">Hoạt động / Hiển thị công khai</label>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end gap-2 border-t border-border pt-3 mt-4">
                <a href="{{ route('admin.online-databases.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-muted hover:bg-accent text-muted-foreground hover:text-foreground border border-border rounded text-xs font-bold transition-all active:scale-[0.98]">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary/95 text-primary-foreground rounded text-xs font-bold transition-all active:scale-[0.98]">
                    <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                    Lưu cơ sở dữ liệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ env('TinyEMC', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    tinymce.init({
        selector: '#content',
        height: 300,
        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'],
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | fullscreen preview code',
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        language: 'vi',
        content_style: 'body { font-family:sans-serif; font-size:13px; }'
    });
});
</script>
@endpush

