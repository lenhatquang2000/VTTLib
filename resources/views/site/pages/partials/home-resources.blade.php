<div id="resources-container" class="space-y-4 animate-in fade-in duration-500">
    @forelse($newResources as $resource)
    <a href="#" class="flex gap-4 items-center group">
        <div class="w-10 h-12 bg-slate-100 rounded-lg flex-shrink-0 flex items-center justify-center text-slate-400 group-hover:bg-vttu-red group-hover:text-white transition-all">
            <i class="fas fa-file-pdf"></i>
        </div>
        <span class="text-xs font-bold text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-2 leading-tight">{{ $resource->title }}</span>
    </a>
    @empty
        <p class="text-[10px] text-slate-400 italic text-center py-4">Chưa có dữ liệu</p>
    @endforelse
</div>
