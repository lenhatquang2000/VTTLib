@extends('layouts.admin')

@section('content')
<div class="max-w-4xl space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center text-xs font-bold text-muted-foreground hover:text-primary transition-colors uppercase tracking-widest mb-2 group">
                <i data-lucide="chevron-left" class="w-4 h-4 mr-1 transform group-hover:-translate-x-0.5 transition-transform"></i>
                {{ __('Quay lại') }}
            </a>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Thêm Vai Trò Mới') }}</h1>
            <p class="text-xs text-muted-foreground mt-0.5">{{ __('Khởi tạo vai trò bảo mật mới và phân quyền các thẻ chức năng trên thanh sidebar.') }}</p>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="bg-card rounded-md p-4 space-y-4 border border-border shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider pl-0.5">{{ __('Tên định danh hệ thống (Slug)') }}</label>
                    <input type="text" name="name" placeholder="Ví dụ: librarian" required
                        class="w-full h-9 px-3 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    @error('name') 
                        <p class="text-[10px] text-destructive font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> 
                    @enderror
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider pl-0.5">{{ __('Tên hiển thị (Display Name)') }}</label>
                    <input type="text" name="display_name" placeholder="Ví dụ: Thủ thư" required
                        class="w-full h-9 px-3 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    @error('display_name') 
                        <p class="text-[10px] text-destructive font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Permission Checkbox List -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between border-b border-border pb-2">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Quyền truy cập menu (Sidebar)') }}</h3>
                    <span class="text-[9px] bg-primary/10 text-primary px-2 py-0.5 rounded-sm font-bold uppercase tracking-widest border border-primary/20">Mẫu menu</span>
                </div>
                
                <!-- Tree Permissions Container -->
                <div class="grid grid-cols-1 gap-3">
                    @foreach($sidebars as $sidebar)
                        <div class="p-3 bg-muted/30 border border-border rounded-sm space-y-3">
                            <!-- Parent Menu Checkbox -->
                            <label class="flex items-start cursor-pointer select-none group">
                                <input type="checkbox" name="sidebars[]" value="{{ $sidebar->id }}"
                                    class="w-4 h-4 rounded-sm border-input text-primary focus:ring-primary mt-0.5">
                                <div class="ml-2.5">
                                    <span class="block text-xs font-bold text-foreground uppercase tracking-wide group-hover:text-primary transition-colors">{{ __($sidebar->name_vi ?? $sidebar->name) }}</span>
                                    <span class="block text-[9px] text-muted-foreground mt-0.5 uppercase tracking-widest">{{ __('Menu cấp 1') }}</span>
                                </div>
                            </label>
                            
                            <!-- Child Menu Checkboxes -->
                            @if($sidebar->children->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pl-6 pt-1 border-t border-dashed border-border">
                                    @foreach($sidebar->children as $child)
                                        <label class="flex items-start p-2 bg-background border border-border rounded-sm cursor-pointer select-none group hover:border-primary/50 transition-colors">
                                            <input type="checkbox" name="sidebars[]" value="{{ $child->id }}"
                                                class="w-3.5 h-3.5 rounded-sm border-input text-primary focus:ring-primary mt-0.5">
                                            <div class="ml-2">
                                                <span class="block text-[11px] font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __($child->name_vi ?? $child->name) }}</span>
                                                <span class="block text-[9px] font-mono text-muted-foreground/60 mt-0.5">{{ $child->route_name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-4 border-t border-border flex justify-end">
                <button type="submit" class="btn-compact-primary px-8 py-2 text-xs uppercase font-bold tracking-widest">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-1.5 text-primary-foreground"></i>
                    {{ __('Tạo vai trò') }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: hsl(var(--border)); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: hsl(var(--muted-foreground)); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
@endsection
