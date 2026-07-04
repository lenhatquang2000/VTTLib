@extends('layouts.admin')

@section('content')
<div class="space-y-4 px-4 md:px-6 py-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-card border border-border p-3 rounded-md shadow-sm gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground">Quản lý Cơ sở dữ liệu trực tuyến</h1>
            <p class="text-xs text-muted-foreground mt-0.5">Cấu hình các liên kết CSDL trực tuyến phục vụ người dùng khai thác học liệu.</p>
        </div>
        <div>
            <a href="{{ route('admin.online-databases.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary/95 text-primary-foreground rounded text-xs font-bold transition-all active:scale-[0.98]">
                <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i>
                Thêm cơ sở dữ liệu
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-muted border border-border text-foreground p-3 rounded-md text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main List Section -->
    <div class="bg-card border border-border rounded-md shadow-sm overflow-hidden">
        <!-- Toolbar / Filters -->
        <div class="p-3 border-b border-border">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-muted-foreground">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" 
                           class="w-full pl-9 pr-3 py-1.5 bg-background border border-border text-foreground rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary" 
                           placeholder="Tìm kiếm theo tiêu đề hoặc mô tả...">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-1.5 bg-primary hover:bg-primary/95 text-primary-foreground rounded text-xs font-bold transition-all active:scale-[0.98]">
                        Lọc kết quả
                    </button>
                    @if($search)
                        <a href="{{ route('admin.online-databases.index') }}" class="flex-1 sm:flex-none px-4 py-1.5 bg-muted hover:bg-accent text-muted-foreground hover:text-foreground border border-border rounded text-xs font-bold transition-all text-center active:scale-[0.98]">
                            Xóa lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Container -->
        @if($databases->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-muted/50 text-[10px] font-bold text-muted-foreground uppercase tracking-wider border-b border-border">
                            <th class="py-2 px-3">Logo</th>
                            <th class="py-2 px-3">Tên cơ sở dữ liệu</th>
                            <th class="py-2 px-3">Đường dẫn truy cập</th>
                            <th class="py-2 px-3">Tài liệu HD</th>
                            <th class="py-2 px-3 text-center">Thứ tự</th>
                            <th class="py-2 px-3">Trạng thái</th>
                            <th class="py-2 px-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($databases as $item)
                            <tr class="hover:bg-muted/50 transition-colors group cursor-pointer" 
                                onclick="window.location.href='{{ route('admin.online-databases.edit', $item->id) }}'">
                                <td class="py-2 px-3" onclick="event.stopPropagation()">
                                    <div class="w-16 h-8 bg-background border border-border rounded flex items-center justify-center p-0.5 overflow-hidden">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                        @else
                                            <i data-lucide="image" class="w-4 h-4 text-muted-foreground/40"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-2 px-3 font-semibold text-foreground">
                                    {{ $item->title }}
                                </td>
                                <td class="py-2 px-3 text-xs text-muted-foreground max-w-[200px] truncate" onclick="event.stopPropagation()">
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline inline-flex items-center gap-1">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> {{ $item->url }}
                                        </a>
                                    @else
                                        <span class="text-muted-foreground/50 italic">Không có</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-xs text-muted-foreground max-w-[150px] truncate" onclick="event.stopPropagation()">
                                    @if($item->hd_url)
                                        <a href="{{ $item->hd_url }}" target="_blank" rel="noopener noreferrer" class="text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Hướng dẫn
                                        </a>
                                    @else
                                        <span class="text-muted-foreground/50 italic">Không có</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center font-medium" onclick="event.stopPropagation()">
                                    {{ $item->sort_order }}
                                </td>
                                <td class="py-2 px-3" onclick="event.stopPropagation()">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-muted text-muted-foreground border border-border">
                                            Khóa
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.online-databases.edit', $item->id) }}" 
                                           class="p-1.5 text-muted-foreground hover:text-foreground hover:bg-muted rounded transition-colors"
                                           title="Sửa">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.online-databases.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="inline-block" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa cơ sở dữ liệu này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-muted-foreground hover:text-destructive hover:bg-muted rounded transition-colors"
                                                    title="Xóa">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
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
            <div class="p-3 border-t border-border bg-muted/20">
                {{ $databases->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-8 text-center text-muted-foreground">
                <i data-lucide="database" class="w-8 h-8 mx-auto text-muted-foreground/30 mb-2"></i>
                <p class="text-xs">Chưa có cơ sở dữ liệu trực tuyến nào được cấu hình.</p>
            </div>
        @endif
    </div>
</div>
@endsection
