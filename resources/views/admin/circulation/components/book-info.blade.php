<!-- Book Information Component -->
<div class="bg-card text-foreground rounded-md border border-border shadow-sm p-3">
    <h4 class="text-xs font-bold text-foreground uppercase tracking-wider mb-2 flex items-center gap-1.5 border-b border-border pb-1.5">
        <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
        <span>{{ __('Thông tin Sách') }}</span>
    </h4>
    <div id="{{ $id ?? 'bookSearchResult' }}" class="space-y-2">
        <div class="text-center text-muted-foreground text-xs py-6">
            <i data-lucide="book-open" class="w-8 h-8 mx-auto mb-2 text-muted-foreground/50"></i>
            <p>{{ __('Nhập mã vạch sách để hiển thị thông tin') }}</p>
        </div>
    </div>
</div>
