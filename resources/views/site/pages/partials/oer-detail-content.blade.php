<div class="space-y-6 animate-fade-in">
    <!-- Main Info Card -->
    <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
        <div class="p-4 md:p-6">
            <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                <!-- Thumbnail Column -->
                <div class="w-full md:w-1/3 lg:w-1/4 space-y-4">
                    <div class="aspect-[3/4] bg-muted rounded-md overflow-hidden border border-border shadow-md relative group">
                        <img src="{{ $resource->thumbnail_url }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             alt="{{ $resource->title }}">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="flex items-center justify-center gap-4 py-2 px-4 bg-muted/30 rounded-full border border-border">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="eye" class="w-4 h-4 text-vttu-red"></i>
                            <span class="text-xs font-bold text-vttu-dark">{{ number_format($resource->view_count ?? 0) }}</span>
                        </div>
                        <div class="w-px h-3 bg-border"></div>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span class="text-xs font-bold text-vttu-dark">{{ number_format($resource->download_count ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="flex-1 space-y-6">
                    <div>
                        <div class="inline-flex items-center px-2 py-0.5 rounded-sm bg-vttu-red/10 text-vttu-red text-[10px] font-black uppercase tracking-widest border border-vttu-red/20 mb-3">
                            {{ __('Tài nguyên giáo dục mở') }}
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-vttu-red tracking-tight leading-tight uppercase">
                            {{ $resource->title }}
                        </h1>
                    </div>

                    <!-- Description -->
                    <div class="prose prose-sm max-w-none text-muted-foreground leading-relaxed">
                        {!! $resource->description ?: __('Không có mô tả chi tiết cho tài nguyên này...') !!}
                        <div class="text-right">
                            <button class="text-vttu-red font-bold text-xs hover:underline">...{{ __('Xem thêm') }}</button>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 pt-4 border-t border-border">
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Loại tài nguyên') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->resource_type }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tác giả') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->author ?: __('Đang cập nhật') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Chủ đề') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ is_array($resource->subjects) ? implode(', ', $resource->subjects) : ($resource->subjects ?? __('Đang cập nhật')) }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Cấp độ giáo dục') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ is_array($resource->educational_levels) ? implode(', ', $resource->educational_levels) : ($resource->educational_levels ?? __('Đang cập nhật')) }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Giấy phép') }}:</span>
                            @if($resource->license)
                            <a href="{{ $resource->license_url }}" target="_blank" class="text-xs font-black text-green-600 uppercase hover:underline">{{ $resource->license }}</a>
                            @else
                            <span class="text-xs font-black text-primary uppercase">{{ __('N/A') }}</span>
                            @endif
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Nhà xuất bản') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->publisher ?: __('Đang cập nhật') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Năm xuất bản') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->publish_year ?: __('Đang cập nhật') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Định dạng') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->format ?: __('N/A') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Ngôn ngữ') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->language }}</span>
                        </div>
                        @if($resource->external_link)
                        <div class="flex items-start justify-between border-b border-border/50 pb-2 md:col-span-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Liên kết ngoài') }}:</span>
                            <a href="{{ $resource->external_link }}" target="_blank" class="text-xs font-black text-vttu-red hover:underline">{{ $resource->external_link }}</a>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-4">
                        @if($resource->file_path)
                        <a href="{{ route('site.oer.download', $resource->id) }}" class="flex-1 min-w-[140px] px-6 py-3 bg-vttu-red text-white rounded-md hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-md shadow-vttu-red/20 flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span class="text-xs font-black uppercase tracking-wider">{{ __('Tải xuống') }}</span>
                        </a>
                        @endif
                        @if($resource->external_link)
                        <a href="{{ $resource->external_link }}" target="_blank" class="flex-1 min-w-[140px] px-6 py-3 bg-white text-vttu-dark border-2 border-vttu-red rounded-md hover:bg-vttu-red hover:text-white active:scale-[0.97] transition-all flex items-center justify-center gap-2">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span class="text-xs font-black uppercase tracking-wider">{{ __('Truy cập') }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keywords Section -->
    @if($resource->keywords)
    <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
        <div class="p-4 md:p-6">
            <h3 class="text-sm font-black text-vttu-dark uppercase tracking-widest mb-4">{{ __('Từ khóa') }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $resource->keywords) as $keyword)
                <span class="px-3 py-1 bg-muted text-muted-foreground text-xs font-bold uppercase tracking-widest rounded-sm border border-border">
                    {{ trim($keyword) }}
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
