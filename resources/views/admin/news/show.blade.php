@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chi tiết Tin tức') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Thông tin: ') }}{{ $news->title }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ $news->url }}" target="_blank" class="btn-secondary">
                <i class="fas fa-eye mr-2"></i>{{ __('Xem trang') }}
            </a>
            <a href="{{ route('admin.news.edit', $news) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>{{ __('Chỉnh sửa') }}
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- News Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-blue-400">Thông tin cơ bản</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-400">Tiêu đề</label>
                        <h2 class="text-xl font-semibold">{{ $news->title }}</h2>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-400">Slug</label>
                        <p class="font-medium">{{ $news->slug }}</p>
                    </div>
                    
                    @if($news->summary)
                    <div>
                        <label class="text-sm text-gray-400">Tóm tắt</label>
                        <p class="font-medium">{{ $news->summary }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm text-gray-400">Nội dung</label>
                        <div class="bg-gray-900/50 p-4 rounded-lg mt-2">
                            <div class="prose prose-invert max-w-none">
                                {!! $news->content !!}
                            </div>
                        </div>
                    </div>
                    
                    @if($news->featured_image)
                    <div>
                        <label class="text-sm text-gray-400">Hình đại diện</label>
                        <div class="mt-2">
                            <img src="{{ $news->featured_image }}" alt="{{ $news->title }}" 
                                 class="max-w-full h-auto rounded-lg">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-red-400">SEO</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400">Meta Title</label>
                        <p class="font-medium">{{ $news->meta_title ?: 'Không có' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Meta Description</label>
                        <p class="font-medium">{{ $news->meta_description ?: 'Không có' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Meta Keywords</label>
                        <p class="font-medium">{{ $news->meta_keywords ?: 'Không có' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            @if($news->tags->count() > 0)
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-purple-400">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($news->tags as $tag)
                        <span class="px-3 py-1 bg-purple-900/30 text-purple-400 rounded-full text-sm">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-green-400">Trạng thái</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Trạng thái</span>
                        <span class="px-2 py-1 text-xs rounded bg-{{ $news->status_color }}-900/30 text-{{ $news->status_color }}-400">
                            {{ $news->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Nổi bật</span>
                        <span class="px-2 py-1 text-xs rounded {{ $news->is_featured ? 'bg-yellow-900/30 text-yellow-400' : 'bg-gray-700 text-gray-300' }}">
                            {{ $news->is_featured ? 'Có' : 'Không' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Bình luận</span>
                        <span class="px-2 py-1 text-xs rounded {{ $news->allow_comments ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }}">
                            {{ $news->allow_comments ? 'Cho phép' : 'Không' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Ngày đăng</span>
                        <span class="text-sm font-medium">{{ $news->formatted_published_at }}</span>
                    </div>
                    @if($news->expired_at)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Hết hạn</span>
                        <span class="text-sm font-medium">{{ $news->expired_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Categorization -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-yellow-400">Phân loại</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400">Chuyên mục</label>
                        @if($news->category)
                            <a href="{{ route('admin.news-categories.show', $news->category) }}" 
                               class="text-blue-400 hover:text-blue-300">
                                {{ $news->category->name }}
                            </a>
                        @else
                            <span class="text-gray-400">Không có</span>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Tác giả</label>
                        @if($news->author)
                            <span class="text-sm font-medium">{{ $news->author->name }}</span>
                        @else
                            <span class="text-gray-400">Không có</span>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Ngôn ngữ</label>
                        <span class="text-sm font-medium">{{ $news->language }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-orange-400">Thống kê</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-400">Lượt xem</span>
                        <span class="text-sm font-medium">{{ $news->view_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-400">Lượt thích</span>
                        <span class="text-sm font-medium">{{ $news->like_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-400">Bình luận</span>
                        <span class="text-sm font-medium">{{ $news->comment_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-400">Thời gian đọc</span>
                        <span class="text-sm font-medium">{{ $news->getReadingTime() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-400">Hành động</h3>
                <div class="space-y-2">
                    @if($news->url)
                    <a href="{{ $news->url }}" target="_blank" 
                       class="w-full btn-secondary text-center block">
                        <i class="fas fa-eye mr-2"></i>Xem trang
                    </a>
                    @endif
                    
                    @if($news->status !== 'published')
                    <button onclick="publishNews({{ $news->id }})" 
                            class="w-full btn-secondary text-center">
                        <i class="fas fa-paper-plane mr-2"></i>Đăng tin
                    </button>
                    @endif
                    
                    @if($news->status !== 'archived')
                    <button onclick="archiveNews({{ $news->id }})" 
                            class="w-full btn-secondary text-center">
                        <i class="fas fa-archive mr-2"></i>Lưu trữ
                    </button>
                    @endif
                    
                    <button onclick="toggleFeatured({{ $news->id }})" 
                            class="w-full btn-secondary text-center">
                        <i class="fas fa-star mr-2"></i>
                        {{ $news->is_featured ? 'Bỏ nổi bật' : 'Đặt nổi bật' }}
                    </button>
                    
                    <form action="{{ route('admin.news.destroy', $news) }}" 
                          method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa tin tức này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-danger text-center">
                            <i class="fas fa-trash mr-2"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-indigo-400">Thông tin thời gian</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400">Ngày tạo</label>
                        <p class="text-sm font-medium">{{ $news->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Cập nhật cuối</label>
                        <p class="text-sm font-medium">{{ $news->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @if($news->published_at)
                    <div>
                        <label class="text-sm text-gray-400">Ngày đăng</label>
                        <p class="text-sm font-medium">{{ $news->published_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function publishNews(newsId) {
    if (confirm('Bạn có chắc muốn đăng tin tức này?')) {
        fetch(`/topsecret/news/${newsId}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Lỗi khi đăng tin tức!', 'error');
        });
    }
}

function archiveNews(newsId) {
    if (confirm('Bạn có chắc muốn lưu trữ tin tức này?')) {
        fetch(`/topsecret/news/${newsId}/archive`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Lỗi khi lưu trữ tin tức!', 'error');
        });
    }
}

function toggleFeatured(newsId) {
    fetch(`/topsecret/news/${newsId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi cập nhật trạng thái nổi bật!', 'error');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-600 text-white' : 
        type === 'error' ? 'bg-red-600 text-white' : 
        'bg-blue-600 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection
