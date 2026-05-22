@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-fade-in" 
     x-data="{ 
        sidebarOpen: {{ request('collapsed') ? 'false' : 'true' }},
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            const url = new URL(window.location.href);
            if (!this.sidebarOpen) {
                url.searchParams.set('collapsed', '1');
            } else {
                url.searchParams.delete('collapsed');
            }
            window.history.replaceState({}, '', url);
        }
     }">
    <!-- Compact Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 bg-card p-3 rounded-md border border-border shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center text-primary-foreground shadow-sm">
                <i data-lucide="file-signature" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-0.5">
                    <span>{{ __('Biên mục') }}</span>
                    <i data-lucide="chevron-right" class="w-3 h-3 opacity-50"></i>
                    <span>{{ __('Tài liệu số') }}</span>
                </div>
                <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Quản lý Biên mục Tài liệu số') }}</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="toggleSidebar()" 
                    class="inline-flex items-center whitespace-nowrap px-3 py-2 bg-muted hover:bg-muted/80 text-muted-foreground active:scale-[0.98] transition-all shadow-sm border border-border rounded">
                <i x-show="sidebarOpen" data-lucide="panel-left-close" class="w-4 h-4"></i>
                <i x-show="!sidebarOpen" data-lucide="panel-left-open" class="w-4 h-4"></i>
                <span class="ml-2 font-bold text-xs" x-text="sidebarOpen ? '{{ __('Thu gọn') }}' : '{{ __('Mở rộng') }}'"></span>
            </button>
            @if(request('category_id'))
            <a href="{{ route('admin.digital-cataloging.create', ['category_id' => request('category_id'), 'collapsed' => request('collapsed')]) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-black rounded shadow-md shadow-primary/20 active:scale-[0.98] transition-all border border-primary/10 group">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                {{ __('Biên Mục Tài Liệu Số') }}
            </a>
            @endif
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <!-- Sidebar: Folders -->
        <div class="transition-all duration-300 ease-in-out" 
             :class="sidebarOpen ? 'w-56 lg:w-64 opacity-100' : 'w-0 opacity-0 overflow-hidden hidden lg:block'"
             x-data="{ showAddCategory: false }">
            <div class="bg-card border border-border rounded-md p-3 shadow-sm sticky top-4 transition-colors duration-200">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider flex items-center">
                        <i data-lucide="folder-tree" class="w-4 h-4 mr-2 text-primary"></i>
                        {{ __('Phân mục tài liệu') }}
                    </h3>
                    <button @click="showAddCategory = true" class="w-6 h-6 rounded bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground transition-all flex items-center justify-center border border-primary/20" title="{{ __('Thêm phân mục mới') }}">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </button>
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('admin.digital-cataloging.index', ['collapsed' => request('collapsed')]) }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-md transition-all text-xs {{ !request('category_id') ? 'bg-primary text-primary-foreground font-bold' : 'text-muted-foreground hover:bg-muted hover:text-foreground font-medium' }}">
                        <div class="flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span>{{ __('Tất cả tài liệu') }}</span>
                        </div>
                    </a>

                    @foreach($categories as $category)
                    <a href="{{ route('admin.digital-cataloging.index', ['category_id' => $category->id, 'collapsed' => request('collapsed')]) }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-md transition-all text-xs {{ request('category_id') == $category->id ? 'bg-primary text-primary-foreground font-bold' : 'text-muted-foreground hover:bg-muted hover:text-foreground font-medium' }}">
                        <div class="flex items-center gap-2">
                            @if(request('category_id') == $category->id)
                                <i data-lucide="folder-open" class="w-4 h-4"></i>
                            @else
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            @endif
                            <span class="truncate">{{ $category->folder_name }}</span>
                        </div>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-sm {{ request('category_id') == $category->id ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground border border-border' }}">
                            {{ $category->resources_count }}
                        </span>
                    </a>
                    @endforeach
                </nav>
            </div>
            
            <!-- Modal Thêm Phân Mục -->
            <div x-show="showAddCategory" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @keydown.escape.window="showAddCategory = false"
                 style="display: none;">
                <div class="bg-card border border-border rounded-md w-full max-w-[320px] p-4 shadow-lg relative" @click.away="showAddCategory = false">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-bold text-foreground tracking-tight">{{ __('Thêm Phân Mục') }}</h4>
                        <button @click="showAddCategory = false" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-muted text-muted-foreground transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.digital-cataloging.category.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider px-1">{{ __('Mã phân mục') }}</label>
                            <input type="text" name="folder_code" required placeholder="BG-VTTU"
                                   class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider px-1">{{ __('Tên phân mục') }}</label>
                            <input type="text" name="folder_name" required placeholder="Bài Giảng VTTU"
                                   class="w-full px-3 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                        </div>
                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="showAddCategory = false" 
                                    class="flex-1 py-2 bg-muted hover:bg-muted/80 text-foreground font-medium rounded-md transition-all border border-border text-xs">
                                {{ __('Hủy') }}
                            </button>
                            <button type="submit" 
                                    class="flex-1 py-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-md shadow-sm transition-all text-xs">
                                {{ __('Lưu') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'w-full lg:w-3/4' : 'w-full'">
            <!-- Search & Filters -->
            <div class="bg-card border border-border rounded-md p-3 shadow-sm mb-4 transition-colors duration-200">
                <form action="{{ route('admin.digital-cataloging.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="collapsed" value="{{ request('collapsed') }}">
                    <div class="flex-1 relative group">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                               placeholder="{{ __('Tìm kiếm tiêu đề, mã số...') }}"
                               oninput="debounceSubmit(this)"
                               class="w-full pl-9 pr-10 py-2 bg-background border border-border rounded text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary transition-all text-xs">
                        @if(request('search'))
                            <a href="{{ route('admin.digital-cataloging.index', array_merge(request()->except('search', 'page'))) }}" 
                               class="absolute right-3 top-1/2 -translate-y-1/2 p-1 hover:bg-muted rounded-full text-muted-foreground hover:text-vttu-red transition-all"
                               title="{{ __('Xóa tìm kiếm') }}">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="btn-compact-primary">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Table Container -->
            <div class="bg-card overflow-hidden border border-border shadow-sm rounded-md transition-colors duration-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-muted/50 border-b border-border text-foreground transition-colors duration-200">
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">{{ __('Thông tin tài liệu') }}</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">{{ __('Định danh') }}</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">{{ __('Tác giả') }}</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">{{ __('Trạng thái') }}</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground text-right">{{ __('Thao tác') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border transition-colors duration-200">
                            @forelse($resources as $res)
                            <tr class="table-row-hover group cursor-pointer" 
                                ondblclick="window.location.href='{{ route('admin.digital-cataloging.edit', $res->id) }}'"
                                title="{{ __('Nhấn đúp để chỉnh sửa') }}">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-8 h-8 shrink-0 bg-muted rounded flex items-center justify-center text-muted-foreground border border-border shadow-sm">
                                            @php
                                                $icon = 'file-text';
                                                $format = strtolower($res->format);
                                                if($format == 'pdf') $icon = 'file-digit';
                                                elseif(in_array($format, ['doc','docx'])) $icon = 'file-text';
                                                elseif(in_array($format, ['mp4','avi','mov'])) $icon = 'video';
                                                elseif(in_array($format, ['jpg','png','jpeg'])) $icon = 'image';
                                            @endphp
                                            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-foreground truncate max-w-[200px] mb-0.5">{{ $res->title }}</div>
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="paperclip" class="w-3 h-3 text-muted-foreground"></i>
                                                <span class="text-[10px] font-medium text-primary truncate max-w-[150px]">{{ $res->file_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-foreground font-mono">{{ $res->identifier }}</span>
                                        <span class="text-[9px] text-muted-foreground font-medium uppercase tracking-tight mt-0.5">{{ $res->resource_type }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap gap-1 items-center">
                                        @php $authors = is_array($res->authors) ? $res->authors : [$res->authors]; @endphp
                                        @foreach(array_slice($authors, 0, 1) as $author)
                                            <span class="text-[11px] font-medium text-foreground">{{ $author }}</span>
                                        @endforeach
                                        @if(count($authors) > 1)
                                            <span class="px-1 py-0.5 rounded-sm bg-muted text-[9px] text-primary font-bold border border-border">+{{ count($authors)-1 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    @if($res->status === 'published')
                                        <div class="inline-flex items-center px-2 py-0.5 bg-emerald-500/10 text-emerald-600 text-[10px] font-bold rounded-sm border border-emerald-500/20">
                                            <span class="w-1 h-1 bg-emerald-500 rounded-full mr-1.5"></span>
                                            {{ __('Đã ban hành') }}
                                        </div>
                                    @else
                                        <div class="inline-flex items-center px-2 py-0.5 bg-amber-500/10 text-amber-600 text-[10px] font-bold rounded-sm border border-amber-500/20">
                                            <span class="w-1 h-1 bg-amber-500 rounded-full mr-1.5"></span>
                                            {{ __('Chờ duyệt') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('site.digital-resources.show', $res->id) }}" target="_blank" class="btn-icon-compact" title="Xem">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="{{ route('admin.digital-cataloging.edit', $res->id) }}" class="btn-icon-compact" title="Biên tập">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <button class="btn-icon-danger btn-delete" 
                                                data-id="{{ $res->id }}" 
                                                data-title="{{ $res->title }}"
                                                title="Xóa">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-muted rounded-md flex items-center justify-center text-muted-foreground mb-3 border border-border border-dashed">
                                            <i data-lucide="folder-open" class="w-6 h-6 opacity-20"></i>
                                        </div>
                                        <h3 class="text-sm font-bold text-foreground mb-1">{{ __('Không tìm thấy tài liệu') }}</h3>
                                        <p class="text-[11px] text-muted-foreground max-w-[200px] mx-auto">Thử thay đổi điều kiện lọc hoặc chọn một phân mục khác.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($resources->hasPages())
                <div class="px-4 py-3 bg-muted/30 border-t border-border pagination-admin">
                    {{ $resources->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;
function debounceSubmit(input) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        input.closest('form').submit();
    }, 1000); // 1 giây
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle Delete Action
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            
            if (window.SwalHelper) {
                const confirmed = await window.SwalHelper.showConfirm(
                    'Xác nhận xóa?',
                    `Bạn có chắc chắn muốn xóa tài liệu "${title}" không? Hành động này không thể hoàn tác.`,
                    'Xóa ngay',
                    'Hủy bỏ'
                );

                if (confirmed) {
                    try {
                        const response = await fetch(`/topsecret/digital-cataloging/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.SwalHelper.showSuccess('Đã xóa!', result.message);
                            // Xóa hàng trong bảng ngay lập tức
                            this.closest('tr').classList.add('opacity-0', 'scale-95');
                            setTimeout(() => {
                                this.closest('tr').remove();
                                // Nếu không còn hàng nào, tải lại trang để hiện thông báo trống
                                if (document.querySelectorAll('tbody tr').length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            window.SwalHelper.showError('Lỗi!', result.message || 'Không thể xóa tài liệu.');
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        window.SwalHelper.showError('Lỗi hệ thống!', 'Đã có lỗi xảy ra khi thực hiện lệnh xóa.');
                    }
                }
            }
        });
    });
});
</script>
@endsection

