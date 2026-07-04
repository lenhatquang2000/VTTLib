@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Notifications -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 p-3 rounded-md text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-destructive/10 border border-destructive/20 text-destructive p-3 rounded-md text-xs font-bold flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Quản lý siêu dữ liệu') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Quản lý siêu dữ liệu và các loại phân loại') }}</p>
        </div>
    </div>

    <div x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'document-types' }" class="space-y-4">
        <!-- Navigation Tabs & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-card p-2 rounded-md border border-border">
            <!-- Tabs -->
            <div class="flex items-center gap-1 p-0.5 bg-muted rounded-md w-fit border border-border/50">
                <button @click="activeTab = 'document-types'; window.history.replaceState({}, '', '?tab=document-types')" 
                    :class="activeTab === 'document-types' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                    class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all">
                    {{ __('Kiểu tài liệu') }}
                </button>
                <button @click="activeTab = 'bibliographic-levels'; window.history.replaceState({}, '', '?tab=bibliographic-levels')" 
                    :class="activeTab === 'bibliographic-levels' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                    class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all">
                    {{ __('Cấp độ thư mục') }}
                </button>
            </div>
            <!-- Actions -->
            <div class="flex items-center">
                <button x-show="activeTab === 'document-types'" @click="$dispatch('open-modal', 'add-doc-type')" class="btn-compact-primary">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    {{ __('Thêm mới') }}
                </button>
                <a x-show="activeTab === 'bibliographic-levels'" href="{{ route('admin.bibliographic-levels.create') }}" class="btn-compact-primary inline-flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Thêm mới') }}
                </a>
            </div>
        </div>

        <!-- Card Container -->
        <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
            <!-- Tab Content: Document Types -->
            <div x-show="activeTab === 'document-types'" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-muted/50 text-[11px] font-bold uppercase tracking-wider text-muted-foreground border-b border-border">
                            <th class="py-2 px-3 w-10 text-center">#</th>
                            <th class="py-2 px-3">{{ __('Kiểu tài liệu') }}</th>
                            <th class="py-2 px-3 w-24">{{ __('Mã') }}</th>
                            <th class="py-2 px-3 w-28">{{ __('Kiểu MARC') }}</th>
                            <th class="py-2 px-3 w-28 text-center">{{ __('Số ngày mượn') }}</th>
                            <th class="py-2 px-3 w-32 text-center">{{ __('Cho phép mượn') }}</th>
                            <th class="py-2 px-3 w-32 text-center">{{ __('Trạng thái') }}</th>
                            <th class="py-2 px-3 w-32 text-right">{{ __('Hành động') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border" id="sortable-doc-types">
                        @forelse($documentTypes as $type)
                        <tr class="table-row-hover group" data-id="{{ $type->id }}">
                            <td class="py-2 px-3 text-center cursor-move drag-handle text-muted-foreground/40 hover:text-primary transition-colors">
                                <i data-lucide="grip-vertical" class="w-4 h-4 mx-auto"></i>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-sm bg-muted flex items-center justify-center text-muted-foreground group-hover:bg-primary/10 group-hover:text-primary transition-colors shrink-0">
                                        @if($type->icon)
                                            <i data-lucide="{{ $type->icon }}" class="w-4 h-4"></i>
                                        @else
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-semibold text-foreground leading-tight">{{ $type->name }}</div>
                                        @if($type->description)
                                            <div class="text-[10px] text-muted-foreground truncate max-w-[220px] mt-0.5" title="{{ $type->description }}">{{ $type->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <code class="font-mono text-[10px] bg-muted border border-border px-1.5 py-0.5 rounded text-foreground font-semibold">{{ $type->code }}</code>
                            </td>
                            <td class="py-2 px-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground whitespace-nowrap">
                                    {{ $type->marc_type ?: __('Chưa xác định') }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="text-xs font-bold text-foreground">{{ $type->default_loan_days }}</span>
                                <span class="text-[9px] text-muted-foreground lowercase block">{{ __('ngày') }}</span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider {{ $type->is_loanable ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20' : 'bg-muted text-muted-foreground border border-border' }}">
                                    {{ $type->is_loanable ? __('Có') : __('Không') }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider {{ $type->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-destructive/10 text-destructive border border-destructive/20' }}">
                                    {{ $type->is_active ? __('Hoạt động') : __('Không hoạt động') }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right space-x-1 whitespace-nowrap">
                                <button @click="$dispatch('open-modal', 'edit-doc-type'); $dispatch('set-edit-doc-type', @js($type))" class="p-1 bg-muted hover:bg-accent border border-border rounded-sm text-muted-foreground hover:text-foreground transition-all" title="{{ __('Chỉnh sửa') }}">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.document-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm(@js(__('Xóa kiểu tài liệu này?')))">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 bg-muted hover:bg-destructive/10 border border-border hover:border-destructive/20 rounded-sm text-muted-foreground hover:text-destructive transition-all" title="{{ __('Xóa') }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-muted-foreground/50"></i>
                                    <p class="text-xs font-semibold">{{ __('Không tìm thấy kiểu tài liệu nào') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab Content: Bibliographic Levels -->
            <div x-show="activeTab === 'bibliographic-levels'" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-muted/50 text-[11px] font-bold uppercase tracking-wider text-muted-foreground border-b border-border">
                            <th class="py-2 px-3 w-10 text-center">#</th>
                            <th class="py-2 px-3 w-20 text-center">{{ __('Mã') }}</th>
                            <th class="py-2 px-3">{{ __('Tên (Anh)') }}</th>
                            <th class="py-2 px-3">{{ __('Tên (Việt)') }}</th>
                            <th class="py-2 px-3 w-20 text-center">{{ __('Thứ tự') }}</th>
                            <th class="py-2 px-3 w-32 text-center">{{ __('Trạng thái') }}</th>
                            <th class="py-2 px-3 w-32 text-right">{{ __('Hành động') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($bibliographicLevels as $level)
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3 text-center text-muted-foreground text-xs font-semibold">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-2 px-3 text-center">
                                <code class="font-mono text-[10px] bg-muted border border-border px-2 py-0.5 rounded text-foreground font-semibold">{{ $level->code }}</code>
                            </td>
                            <td class="py-2 px-3 text-xs text-foreground font-medium">
                                {{ $level->name_en }}
                            </td>
                            <td class="py-2 px-3 text-xs text-foreground font-medium">
                                {{ $level->name_vi }}
                            </td>
                            <td class="py-2 px-3 text-center text-xs font-bold text-foreground">
                                {{ $level->order }}
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider {{ $level->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-destructive/10 text-destructive border border-destructive/20' }}">
                                    {{ $level->is_active ? __('Hoạt động') : __('Không hoạt động') }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.bibliographic-levels.edit', $level) }}" class="p-1 bg-muted hover:bg-accent border border-border rounded-sm text-muted-foreground hover:text-foreground inline-block transition-all" title="{{ __('Chỉnh sửa') }}">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.bibliographic-levels.destroy', $level) }}" method="POST" class="inline-block" onsubmit="return confirm(@js(__('Bạn chắc chắn muốn xóa?')))">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 bg-muted hover:bg-destructive/10 border border-border hover:border-destructive/20 rounded-sm text-muted-foreground hover:text-destructive transition-all" title="{{ __('Xóa') }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-muted-foreground/50"></i>
                                    <p class="text-xs font-semibold">{{ __('Không tìm thấy dữ liệu') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manager -->
<div x-data="{ 
    showAdd: false, 
    showEdit: false, 
    docType: {},
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === 'add-doc-type') this.showAdd = true;
            if (e.detail === 'edit-doc-type') this.showEdit = true;
        });
        window.addEventListener('set-edit-doc-type', (e) => {
            this.docType = e.detail;
            this.docType.is_loanable = !!this.docType.is_loanable;
            this.docType.is_active = !!this.docType.is_active;
        });
    }
}">
    <!-- Add Modal -->
    <template x-if="showAdd">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity animate-in fade-in duration-250" @click="showAdd = false"></div>
            
            <!-- Content container -->
            <div class="bg-card rounded-md shadow-lg border border-border overflow-hidden w-full max-w-md relative z-10 animate-in fade-in zoom-in-95 duration-200">
                <!-- Modal Header -->
                <div class="p-4 border-b border-border flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-foreground leading-tight">{{ __('Thêm kiểu tài liệu') }}</h3>
                        <p class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest mt-0.5">{{ __('Định nghĩa một loại tài liệu thư viện mới') }}</p>
                    </div>
                    <button type="button" @click="showAdd = false" class="btn-icon-compact">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Modal Body/Form -->
                <form action="{{ route('admin.document-types.store') }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Tên phân loại') }} *</label>
                            <input type="text" name="name" required class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Mã hệ thống') }} *</label>
                            <input type="text" name="code" required class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono" maxlength="20">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Bộ chọn MARC') }} (L/06)</label>
                            <input type="text" name="marc_type" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Biểu tượng (Icon)') }}</label>
                            <input type="text" name="icon" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Mô tả') }}</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Thời hạn mượn') }} ({{ __('Ngày') }})</label>
                            <input type="number" name="default_loan_days" value="14" required min="0" max="365" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Thứ tự') }}</label>
                            <input type="number" name="order" value="0" min="0" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6 px-1 py-1">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="is_loanable" value="1" checked class="rounded-sm border-input text-primary shadow-sm focus:ring-primary focus:ring-offset-background">
                            <span class="text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __('Cho phép lưu thông') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded-sm border-input text-primary shadow-sm focus:ring-primary focus:ring-offset-background">
                            <span class="text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __('Hoạt động') }}</span>
                        </label>
                    </div>
                    
                    <div class="flex gap-2 pt-4">
                        <button type="button" @click="showAdd = false" class="btn-compact-secondary flex-1">{{ __('Hủy bỏ') }}</button>
                        <button type="submit" class="btn-compact-primary flex-1">{{ __('Lưu định nghĩa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-if="showEdit">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity animate-in fade-in duration-250" @click="showEdit = false"></div>
            
            <!-- Content container -->
            <div class="bg-card rounded-md shadow-lg border border-border overflow-hidden w-full max-w-md relative z-10 animate-in fade-in zoom-in-95 duration-200">
                <!-- Modal Header -->
                <div class="p-4 border-b border-border flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-foreground leading-tight">{{ __('Chỉnh sửa kiểu tài liệu') }}</h3>
                        <p class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest mt-0.5">{{ __('Cập nhật định nghĩa cho') }} <span class="text-primary" x-text="docType.name"></span></p>
                    </div>
                    <button type="button" @click="showEdit = false" class="btn-icon-compact">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Modal Body/Form -->
                <form :action="'{{ url('topsecret/document-types') }}/' + docType.id" method="POST" class="p-4 space-y-3">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Tên phân loại') }} *</label>
                            <input type="text" name="name" x-model="docType.name" required class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Mã hệ thống') }} *</label>
                            <input type="text" name="code" x-model="docType.code" required class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono" maxlength="20">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Bộ chọn MARC') }} (L/06)</label>
                            <input type="text" name="marc_type" x-model="docType.marc_type" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Biểu tượng (Icon)') }}</label>
                            <input type="text" name="icon" x-model="docType.icon" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Mô tả') }}</label>
                        <textarea name="description" x-model="docType.description" rows="2" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Thời hạn mượn') }} ({{ __('Ngày') }})</label>
                            <input type="number" name="default_loan_days" x-model="docType.default_loan_days" required min="0" max="365" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Thứ tự') }}</label>
                            <input type="number" name="order" x-model="docType.order" min="0" class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6 px-1 py-1">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="is_loanable" x-model="docType.is_loanable" value="1" class="rounded-sm border-input text-primary shadow-sm focus:ring-primary focus:ring-offset-background">
                            <span class="text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __('Cho phép lưu thông') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="is_active" x-model="docType.is_active" value="1" class="rounded-sm border-input text-primary shadow-sm focus:ring-primary focus:ring-offset-background">
                            <span class="text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __('Hoạt động') }}</span>
                        </label>
                    </div>
                    
                    <div class="flex gap-2 pt-4">
                        <button type="button" @click="showEdit = false" class="btn-compact-secondary flex-1">{{ __('Hủy bỏ') }}</button>
                        <button type="submit" class="btn-compact-primary flex-1">{{ __('Lưu thay đổi') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        const el = document.getElementById('sortable-doc-types');
        if (el) {
            Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-muted/50',
                onEnd: function() {
                    let orders = [];
                    el.querySelectorAll('tr').forEach((row, index) => {
                        orders.push({
                            id: row.dataset.id,
                            order: index
                        });
                    });

                    fetch('{{ route('admin.document-types.order') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ orders: orders })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: { message: '{{ __("Cập nhật thứ tự thành công") }}', type: 'success' }
                            }));
                        }
                    });
                }
            });
        }
    });

</script>
@endpush
@endsection
