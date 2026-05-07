@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chỉnh sửa Tin tức') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Chỉnh sửa: ') }}{{ $news->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ $news->url }}" target="_blank"
               class="inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold text-sm transition-all duration-300 border border-indigo-200/50 dark:border-indigo-800/50 active:scale-95 group">
                <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                {{ __('Xem') }}
            </a>
            <a href="{{ route('admin.news.index') }}" 
               class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:shadow-sm active:scale-95 group">
                <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                {{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card-admin p-6">
        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                       value="{{ old('title', $news->title) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Slug (URL)</label>
                                <input type="text" name="slug" 
                                       class="input-field w-full" 
                                       placeholder="Tự động tạo từ tiêu đề"
                                       value="{{ old('slug', $news->slug) }}">
                                <p class="text-xs text-gray-400 mt-1">Để trống để tự động tạo</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tóm tắt</label>
                                <textarea name="summary" rows="3"
                                          class="input-field w-full"
                                          placeholder="Tóm tắt nội dung bài viết">{{ old('summary', $news->summary) }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Nội dung *</label>
                                <textarea name="content" id="content" rows="15"
                                          class="input-field w-full"
                                          placeholder="Nội dung chi tiết bài viết" required>{{ old('content', $news->content) }}</textarea>
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
                                       value="{{ old('meta_title', $news->meta_title) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="input-field w-full"
                                          placeholder="Mô tả SEO">{{ old('meta_description', $news->meta_description) }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                                <input type="text" name="meta_keywords" 
                                       class="input-field w-full" 
                                       placeholder="từ khóa 1, từ khóa 2, từ khóa 3"
                                       value="{{ old('meta_keywords', $news->meta_keywords) }}">
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
                                    <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    <option value="pending" {{ old('status', $news->status) == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                    <option value="archived" {{ old('status', $news->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày đăng</label>
                                <input type="datetime-local" name="published_at" 
                                       class="input-field w-full"
                                       value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                <p class="text-xs text-gray-400 mt-1">Để trống để sử dụng thời gian hiện tại</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày hết hạn</label>
                                <input type="datetime-local" name="expired_at" 
                                       class="input-field w-full"
                                       value="{{ old('expired_at', $news->expired_at ? $news->expired_at->format('Y-m-d\TH:i') : '') }}">
                                <p class="text-xs text-gray-400 mt-1">Để trống nếu không có hạn</p>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" 
                                       class="mr-2" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="text-sm">Tin nổi bật</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_comments" id="allow_comments" 
                                       class="mr-2" {{ old('allow_comments', $news->allow_comments) ? 'checked' : '' }}>
                                <label for="allow_comments" class="text-sm">Cho phép bình luận</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Media -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-purple-400">Media</h3>
                        <div class="space-y-4">
                            <div x-data="{ uploadType: 'url' }">
                                <div class="flex items-center gap-4 mb-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="image_source" value="url" x-model="uploadType" class="mr-2">
                                        <span class="text-sm font-medium">Đường dẫn URL</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="image_source" value="upload" x-model="uploadType" class="mr-2">
                                        <span class="text-sm font-medium">Tải ảnh lên</span>
                                    </label>
                                </div>

                                <div x-show="uploadType === 'url'">
                                    <label class="block text-sm font-medium mb-1">Hình đại diện (URL)</label>
                                    <input type="text" name="featured_image" 
                                           class="input-field w-full" 
                                           placeholder="Nhập URL hình ảnh (vd: https://...)"
                                           value="{{ $news->featured_image }}"
                                           oninput="handleUrlPreview(this.value)">
                                </div>
                                
                                <div x-show="uploadType === 'upload'" x-cloak>
                                    <label class="block text-sm font-medium mb-1">Tải ảnh đại diện mới</label>
                                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                                    <div class="relative group">
                                        <input type="file" name="featured_image_file" accept="image/*"
                                               class="hidden" id="featured_image_file"
                                               onchange="previewImage(this)">
                                        <label for="featured_image_file" 
                                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                                                <p class="text-xs text-slate-500">Click để chọn ảnh hoặc kéo thả</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="image-preview-container" class="{{ $news->featured_image ? '' : 'hidden' }} mt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Xem trước:</p>
                                    <button type="button" onclick="removeImage()" 
                                            class="text-[10px] font-black text-rose-500 hover:text-rose-700 uppercase tracking-widest flex items-center transition-colors">
                                        <i class="fas fa-trash-alt mr-1"></i> Xóa ảnh
                                    </button>
                                </div>
                                <div class="relative">
                                    <img id="image-preview" src="{{ $news->featured_image }}" 
                                         class="w-full h-48 object-cover rounded-2xl shadow-lg border border-slate-100">
                                    <div id="image-error-msg" class="hidden absolute inset-0 bg-black/60 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white text-xs font-bold p-4 text-center">
                                    </div>
                                </div>
                            </div>
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
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tags</label>
                                <input type="text" name="tags" 
                                       class="input-field w-full" 
                                       placeholder="tag1, tag2, tag3"
                                       value="{{ old('tags', $news->tags->pluck('name')->implode(', ')) }}">
                                <p class="text-xs text-gray-400 mt-1">Ngăn cách bằng dấu phẩy</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngôn ngữ</label>
                                <select name="language" class="input-field w-full">
                                    <option value="vi" {{ old('language', $news->language) == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                                    <option value="en" {{ old('language', $news->language) == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-orange-400">Thống kê</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-400">Lượt xem:</span>
                                <span class="text-sm font-medium">{{ $news->view_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-400">Lượt thích:</span>
                                <span class="text-sm font-medium">{{ $news->like_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-400">Bình luận:</span>
                                <span class="text-sm font-medium">{{ $news->comment_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-400">Ngày tạo:</span>
                                <span class="text-sm font-medium">{{ $news->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-400">Cập nhật:</span>
                                <span class="text-sm font-medium">{{ $news->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex flex-wrap justify-end gap-3 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.news.index') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm transition-all duration-300 hover:shadow-md active:scale-95 group">
                    <i class="fas fa-times-circle mr-2 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                    Hủy
                </a>
                <button type="submit" name="save_draft" value="1" 
                        class="inline-flex items-center px-6 py-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold text-sm transition-all duration-300 hover:shadow-md active:scale-95 group border border-indigo-100 dark:border-indigo-800/50">
                    <i class="fas fa-save mr-2 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                    Lưu nháp
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-black text-sm transition-all duration-300 shadow-lg shadow-indigo-200 dark:shadow-none hover:shadow-indigo-300 active:scale-95 group">
                    <i class="fas fa-check-circle mr-2 group-hover:scale-110 transition-transform"></i>
                    Cập nhật ngay
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
        errorMsg.innerText = "⚠️ Không thể tải ảnh từ URL này. Đang sử dụng ảnh mặc định.";
    };
}

function removeImage() {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const urlInput = document.querySelector('input[name="featured_image"]');
    const fileInput = document.getElementById('featured_image_file');
    const removedInput = document.getElementById('image_removed');
    
    // Clear values
    if (urlInput) urlInput.value = '';
    if (fileInput) fileInput.value = '';
    if (removedInput) removedInput.value = '1';
    
    // Hide preview
    container.classList.add('hidden');
    preview.src = '';
}

// Auto-generate slug from title
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === '{{ $news->slug }}') {
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
@endpush
@endsection
