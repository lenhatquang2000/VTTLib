@extends('layouts.admin')

@section('content')
<div class="space-y-8 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-slate-100 tracking-tight">{{ __('News_Management') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Manage_News_Instruction') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.news.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Create_News') }}
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total News -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group hover:border-indigo-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Total') }}</p>
                    <p class="text-2xl font-black text-gray-800 dark:text-slate-100">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Published') }}</p>
                    <p class="text-2xl font-black text-gray-800 dark:text-slate-100">{{ $stats['published'] }}</p>
                </div>
            </div>
        </div>

        <!-- Drafts -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group hover:border-slate-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Draft') }}</p>
                    <p class="text-2xl font-black text-gray-800 dark:text-slate-100">{{ $stats['draft'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group hover:border-amber-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Pending') }}</p>
                    <p class="text-2xl font-black text-gray-800 dark:text-slate-100">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <!-- Featured -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm group hover:border-purple-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Featured') }}</p>
                    <p class="text-2xl font-black text-gray-800 dark:text-slate-100">{{ $stats['featured'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <!-- Toolbar / Filters -->
        <div class="p-6 border-b border-gray-50 dark:border-slate-800">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all" 
                               placeholder="{{ __('Search_Placeholder') }}">
                    </div>

                    <!-- Status Filter -->
                    <div class="min-w-[160px]">
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                            <option value="">{{ __('All_Status') }}</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>{{ __('Archived') }}</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="min-w-[180px]">
                        <select name="category_id" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                            <option value="">{{ __('All_Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-6 py-2.5 bg-gray-800 dark:bg-slate-700 text-white rounded-xl text-sm font-bold hover:bg-gray-900 transition-all">
                        {{ __('Filter') }}
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'category_id']))
                        <a href="{{ route('admin.news.index') }}" class="flex-1 lg:flex-none px-6 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all text-center">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bulk Actions & Table Container -->
        <div class="p-6 bg-gray-50/30 dark:bg-slate-900/50 border-b border-gray-50 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center px-3 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded text-indigo-600 focus:ring-0 w-4 h-4 cursor-pointer">
                    <label for="selectAll" class="ml-2.5 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest cursor-pointer">{{ __('Select_All') }}</label>
                </div>

                <div class="h-8 w-[1px] bg-gray-200 dark:bg-slate-700"></div>

                <select id="bulkAction" onchange="performBulkAction()" class="bg-transparent border-none text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest focus:ring-0 cursor-pointer">
                    <option value="">{{ __('Hành động') }}</option>
                    <option value="publish">{{ __('Publish') }}</option>
                    <option value="archive">{{ __('Archive') }}</option>
                    <option value="delete">{{ __('Delete') }}</option>
                    <option value="feature">{{ __('Feature') }}</option>
                    <option value="unfeature">{{ __('Unfeature') }}</option>
                </select>
            </div>
            
            <div class="text-xs font-bold text-gray-400 dark:text-slate-500 tracking-widest uppercase">
                {{ __('Found :count records', ['count' => $news->total()]) }}
            </div>
        </div>

        <!-- Content Area -->
        @if($news->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-800/50 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 w-10"></th>
                            <th class="px-6 py-4">{{ __('News_Content') }}</th>
                            <th class="px-6 py-4">{{ __('Category') }}</th>
                            <th class="px-6 py-4">{{ __('Author') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('Stats') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @foreach($news as $item)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer" onclick="window.location.href='{{ route('admin.news.edit', $item) }}'">
                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="news-checkbox rounded text-indigo-600 focus:ring-0 w-4 h-4 cursor-pointer" value="{{ $item->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative w-12 h-12 shrink-0 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm">
                                            @if($item->featured_image)
                                                <img src="{{ $item->featured_image }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i class="fas fa-image text-lg"></i>
                                                </div>
                                            @endif
                                            @if($item->is_featured)
                                                <div class="absolute top-0 right-0 w-5 h-5 bg-purple-500 text-white flex items-center justify-center text-[8px] rounded-bl-lg shadow-sm">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="max-w-[300px]">
                                            <div class="font-bold text-gray-800 dark:text-slate-100 truncate group-hover:text-indigo-600 transition-colors">{{ $item->title }}</div>
                                            <div class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 font-mono">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ $item->formatted_published_at ?: __('Chưa đăng') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-wider">
                                            {{ $item->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-slate-700">---</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($item->author->name ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 dark:text-slate-400">{{ $item->author->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-{{ $item->status_color }}-50 dark:bg-{{ $item->status_color }}-900/30 text-{{ $item->status_color }}-600 dark:text-{{ $item->status_color }}-400 border border-{{ $item->status_color }}-100 dark:border-{{ $item->status_color }}-800/50">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-3">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[10px] font-black text-gray-700 dark:text-slate-200">{{ $item->view_count }}</span>
                                            <span class="text-[8px] text-gray-400 uppercase tracking-tighter">{{ __('Views') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('admin.news.edit', $item) }}" 
                                           class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="toggleFeatured({{ $item->id }})" 
                                                class="p-2 {{ $item->is_featured ? 'text-purple-600' : 'text-gray-300' }} hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors" title="Nổi bật">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <form action="{{ route('admin.news.destroy', $item) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-50 dark:border-slate-800">
                {{ $news->links() }}
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-24 bg-white dark:bg-slate-900 border-t border-gray-50 dark:border-slate-800">
                <div class="relative inline-block mb-6">
                    <div class="w-24 h-24 rounded-3xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-400 dark:text-indigo-600 animate-pulse">
                        <i class="fas fa-newspaper text-4xl"></i>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-900 border-4 border-gray-50 dark:border-slate-800 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-gray-800 dark:text-slate-100 mb-2 uppercase tracking-tight">{{ __('No_News_Found') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 max-w-sm mx-auto text-sm leading-relaxed">{{ __('No_News_Instruction') }}</p>
                <a href="{{ route('admin.news.create') }}" 
                   class="inline-flex items-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-black transition-all shadow-xl shadow-indigo-200 dark:shadow-none hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i> {{ __('Create_First_News') }}
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let selectedNews = new Set();

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.news-checkbox');
    const selectAll = document.getElementById('selectAll').checked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
        const newsId = checkbox.value;
        if (selectAll) selectedNews.add(newsId);
        else selectedNews.delete(newsId);
    });
}

function performBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action || selectedNews.size === 0) {
        if (action) Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một bài viết', 'info');
        return;
    }
    
    if (action === 'delete') {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Bạn có chắc muốn xóa ${selectedNews.size} bài viết đã chọn?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) executeBulkAction(action);
        });
    } else {
        executeBulkAction(action);
    }
}

function executeBulkAction(action) {
    const newsIds = Array.from(selectedNews);
    fetch('{{ route("admin.news.bulk-action") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ action: action, news_ids: newsIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Lỗi', data.message, 'error');
        }
    });
}

function toggleFeatured(newsId) {
    fetch(`/topsecret/news/${newsId}/toggle-featured`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
