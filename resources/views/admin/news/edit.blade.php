@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chỉnh sửa Tin tức') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Chỉnh sửa: ') }}{{ $news->title }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.news.show', $news) }}" class="btn-secondary">
                <i class="fas fa-eye mr-2"></i>{{ __('Xem') }}
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
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
                            <div>
                                <label class="block text-sm font-medium mb-1">Hình đại diện</label>
                                <input type="text" name="featured_image" 
                                       class="input-field w-full" 
                                       placeholder="URL hình ảnh"
                                       value="{{ old('featured_image', $news->featured_image) }}">
                                <p class="text-xs text-gray-400 mt-1">Nhập URL hình ảnh</p>
                            </div>
                            
                            @if(old('featured_image', $news->featured_image))
                                <div class="mt-2">
                                    <img src="{{ old('featured_image', $news->featured_image) }}" alt="Preview" 
                                         class="w-full h-32 object-cover rounded">
                                </div>
                            @endif
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
            <div class="flex justify-end gap-2 mt-6 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.news.index') }}" class="btn-secondary">
                    Hủy
                </a>
                <button type="submit" name="save_draft" value="1" class="btn-secondary">
                    <i class="fas fa-save mr-2"></i>Lưu nháp
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
