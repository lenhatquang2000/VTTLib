@php
    $resources = $resources ?? collect();
    $totalCount = $totalCount ?? 0;
@endphp

<div class="space-y-[6px] animate-fade-in">
    <!-- Tabs Navigation -->
    <div class="flex items-end border-b border-border bg-muted/20 rounded-t-sm overflow-hidden">
        <button onclick="changeSort('oldest_updated')" class="px-5 py-2.5 text-[11px] uppercase tracking-widest {{ $currentSort === 'oldest_updated' ? 'font-black bg-card text-vttu-red border-t-2 border-vttu-red shadow-[0_-2px_10px_rgba(123,0,0,0.05)] relative z-10' : 'font-bold text-muted-foreground hover:bg-muted/50 hover:text-foreground active:bg-muted' }} transition-all">
            {{ __('Cũ đến mới') }}
        </button>
        <button onclick="changeSort('latest')" class="px-5 py-2.5 text-[11px] uppercase tracking-widest {{ $currentSort === 'latest' ? 'font-black bg-card text-vttu-red border-t-2 border-vttu-red shadow-[0_-2px_10px_rgba(123,0,0,0.05)] relative z-10' : 'font-bold text-muted-foreground hover:bg-muted/50 hover:text-foreground active:bg-muted' }} transition-all">
            {{ __('Mới nhất') }}
        </button>
        <button onclick="changeSort('most_viewed')" class="px-5 py-2.5 text-[11px] uppercase tracking-widest {{ $currentSort === 'most_viewed' ? 'font-black bg-card text-vttu-red border-t-2 border-vttu-red shadow-[0_-2px_10px_rgba(123,0,0,0.05)] relative z-10' : 'font-bold text-muted-foreground hover:bg-muted/50 hover:text-foreground active:bg-muted' }} transition-all">
            {{ __('Xem nhiều') }}
        </button>
        <button onclick="changeSort('most_downloaded')" class="px-5 py-2.5 text-[11px] uppercase tracking-widest {{ $currentSort === 'most_downloaded' ? 'font-black bg-card text-vttu-red border-t-2 border-vttu-red shadow-[0_-2px_10px_rgba(123,0,0,0.05)] relative z-10' : 'font-bold text-muted-foreground hover:bg-muted/50 hover:text-foreground active:bg-muted' }} transition-all">
            {{ __('Tải nhiều') }}
        </button>
    </div>

    <!-- Search Header -->
    <div class="bg-card border border-border shadow-sm p-3 flex flex-col sm:flex-row gap-2 transition-colors duration-200">
        <div class="relative min-w-[140px]">
            <select class="w-full h-9 pl-3 pr-8 text-xs font-bold bg-muted/30 border border-border rounded-sm appearance-none outline-none focus:ring-1 focus:ring-vttu-red/30 focus:border-vttu-red/30 transition-all cursor-pointer">
                <option value="title">{{ __('Tiêu đề') }}</option>
                <option value="author">{{ __('Tác giả') }}</option>
                <option value="subject">{{ __('Chủ đề') }}</option>
            </select>
            <i data-lucide="chevron-down" class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-muted-foreground pointer-events-none"></i>
        </div>
        
        <div class="flex-1 relative group">
            <input type="text" 
                   placeholder="{{ __('Nhập từ khóa tìm kiếm tài liệu số...') }}"
                   class="w-full h-9 pl-3 pr-10 text-xs bg-background border border-border rounded-sm outline-none focus:ring-1 focus:ring-vttu-red/30 focus:border-vttu-red/30 transition-all placeholder:text-muted-foreground/60">
            <div class="absolute right-0 top-0 h-9 px-3 flex items-center justify-center text-muted-foreground opacity-40 group-focus-within:opacity-100 transition-opacity">
                <i data-lucide="keyboard" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="flex gap-1.5 h-9">
            <button class="flex-1 sm:flex-none px-5 bg-vttu-red text-white rounded-sm hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-md shadow-vttu-red/20 flex items-center justify-center">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                <span class="text-xs font-black uppercase tracking-wider">{{ __('Tìm') }}</span>
            </button>
            <button class="w-9 bg-vttu-red text-white rounded-sm hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-md shadow-vttu-red/20 flex items-center justify-center">
                <i data-lucide="more-vertical" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Results Info -->
    <div class="flex items-center gap-2 text-vttu-red px-1 pt-1">
        <div class="w-7 h-7 rounded-sm bg-vttu-red/10 flex items-center justify-center shadow-sm">
            <i data-lucide="database" class="w-3.5 h-3.5"></i>
        </div>
        <span class="text-[11px] font-black uppercase tracking-[0.15em]">
            {{ __('Tổng số') }}: <span class="text-sm ml-0.5">{{ number_format($totalCount) }}</span>
        </span>
    </div>

    <!-- Data Table -->
    <div class="bg-card border border-border rounded-sm shadow-sm overflow-hidden transition-colors duration-200 w-full">
        <table class="w-full text-left border-collapse table-fixed">
            <thead class="bg-muted/50 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b border-border">
                <tr>
                    <th class="py-2.5 px-4 w-12 text-center border-r border-border/50">#</th>
                    <th class="py-2.5 px-4">{{ __('Tiêu đề') }}</th>
                    <th class="py-2.5 px-4 w-48 text-center hidden md:table-cell border-l border-border/50">{{ __('Tác giả') }}</th>
                    <th class="py-2.5 px-4 w-32 text-center hidden md:table-cell border-l border-border/50">{{ __('Ngày cập nhật') }}</th>
                    <th class="py-2.5 px-4 w-32 text-right hidden sm:table-cell border-l border-border/50">{{ __('Loại tài liệu') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/40 text-xs">
                @forelse($resources as $index => $item)
                <tr class="group hover:bg-muted/30 active:bg-muted/50 transition-colors cursor-pointer"
                    onclick="window.location.href='{{ route('site.digital-resources.show', $item->id) }}'">
                    <td class="py-4 px-4 text-center font-bold text-muted-foreground group-hover:text-vttu-red transition-colors border-r border-border/30 w-12">
                        {{ $resources->firstItem() + $index }}
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-20 bg-muted rounded-sm flex-shrink-0 overflow-hidden border border-border group-hover:border-vttu-red/30 transition-colors shadow-sm relative">
                                <img src="{{ $item->thumbnail_url ?? 'https://placehold.co/100x140/7B0000/FFFFFF?text=DOC' }}" 
                                     class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500"
                                     alt="{{ $item->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="space-y-2 overflow-hidden flex-1">
                                <h4 class="font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 text-sm tracking-tight leading-snug uppercase">
                                    {{ $item->title }}
                                </h4>
                                <div class="flex flex-wrap gap-2 pt-1 md:hidden">
                                    <span class="text-[10px] text-vttu-red/70 font-bold italic"><i data-lucide="user" class="w-3 h-3 inline mr-0.5"></i> {{ $item->author ?: __('Đang cập nhật') }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold italic"><i data-lucide="calendar" class="w-3.5 h-3.5 inline mr-0.5"></i> {{ $item->updated_at ? $item->updated_at->format('d-m-Y') : 'N/A' }}</span>
                                    <span class="text-[10px] text-muted-foreground font-bold italic"><i data-lucide="file-text" class="w-3 h-3 inline mr-0.5"></i> {{ __('Tài liệu số') }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 w-48 text-center hidden md:table-cell border-l border-border/30">
                        <span class="font-black text-vttu-dark/70 group-hover:text-vttu-dark transition-colors uppercase tracking-tight text-[11px] line-clamp-2">
                            {{ $item->author ?: __('Đang cập nhật') }}
                        </span>
                    </td>
                    <td class="py-4 px-4 w-32 text-center hidden md:table-cell border-l border-border/30">
                        <span class="text-muted-foreground group-hover:text-foreground transition-colors font-bold text-[11px]">
                            {{ $item->updated_at ? $item->updated_at->format('d-m-Y') : 'N/A' }}
                        </span>
                    </td>
                    <td class="py-4 px-4 w-32 text-right hidden sm:table-cell border-l border-border/30">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-sm bg-muted text-muted-foreground text-[10px] font-black uppercase tracking-widest border border-border group-hover:bg-vttu-red/10 group-hover:text-vttu-red group-hover:border-vttu-red/20 transition-all">
                            {{ __('Tài liệu số') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center opacity-20">
                            <i data-lucide="folder-open" class="w-12 h-12 mb-4"></i>
                            <p class="text-sm font-bold uppercase tracking-widest">{{ __('Không tìm thấy tài liệu nào') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($resources instanceof \Illuminate\Pagination\LengthAwarePaginator && $resources->hasPages())
        <div class="pt-4 px-1">
            {{ $resources->links('site.partials.pagination-compact') }}
        </div>
    @endif
</div>

<script>
function changeSort(sortType) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortType);
    window.location.href = url.toString();
}
</script>
