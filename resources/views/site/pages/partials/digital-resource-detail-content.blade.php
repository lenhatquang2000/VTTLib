<div class="space-y-6 animate-fade-in">
    <!-- Main Info Card -->
    <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
        <div class="p-4 md:p-6">
            <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                <!-- Thumbnail Column -->
                <div class="w-full md:w-1/3 lg:w-1/4 space-y-4">
                    <div class="aspect-[3/4] bg-muted rounded-md overflow-hidden border border-border shadow-md relative group">
                        <img src="{{ $resource->thumbnail_url ?? 'https://placehold.co/300x400/7B0000/FFFFFF?text=DOC' }}" 
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
                            {{ __('Bài giảng') }}
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-vttu-red tracking-tight leading-tight uppercase">
                            {{ $resource->title }}
                        </h1>
                    </div>

                    <!-- Description -->
                    <div class="prose prose-sm max-w-none text-muted-foreground leading-relaxed">
                        {!! $resource->description ?: __('Không có mô tả chi tiết cho tài liệu này...') !!}
                        <div class="text-right">
                            <button class="text-vttu-red font-bold text-xs hover:underline">...{{ __('Xem thêm') }}</button>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 pt-4 border-t border-border">
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Loại tài liệu') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ __('Tài liệu số') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tác giả') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->author ?: __('Đang cập nhật') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Đề mục') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->folder->folder_name ?? __('Khoa Dược') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Nhà xuất bản') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->publisher ?: __('Trường Đại học Võ Trường Toản') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Ngày xuất bản') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ $resource->publish_year ?: date('Y') }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Số trang/ tờ') }}:</span>
                            <span id="pdf-page-count" class="text-xs font-black text-primary uppercase">{{ $resource->pages ?: '---' }}</span>
                        </div>
                        <div class="flex items-start justify-between border-b border-border/50 pb-2">
                            <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Định dạng') }}:</span>
                            <span class="text-xs font-black text-primary uppercase">{{ strtoupper($resource->file_type ?? 'pdf') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Files Table Card -->
    <div class="bg-card text-card-foreground border border-border rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-3 px-6">{{ __('Loại file') }}</th>
                        <th class="py-3 px-6">{{ __('Tập tin đính kèm') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Dung lượng') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Chi tiết') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/40 text-xs">
                    <tr class="group hover:bg-muted/30 transition-colors">
                        <td class="py-4 px-6 text-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-vttu-red mx-auto"></i>
                        </td>
                        <td class="py-4 px-6 font-bold text-vttu-dark">
                            {{ $resource->file_name ?? ($resource->node_code . '.pdf') }}
                        </td>
                        <td class="py-4 px-6 text-center text-muted-foreground font-medium">
                            {{ $resource->file_size ? number_format($resource->file_size) . ' Kb' : '---' }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('site.digital-resources.view', $resource->id) }}"
                                   onclick="console.log('Button clicked, href:', this.href); window.location.href=this.href; return false;"
                                   class="inline-flex items-center px-4 py-1.5 bg-vttu-yellow text-vttu-dark rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-yellow-400 active:scale-95 transition-all shadow-sm">
                                    <i data-lucide="eye" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('Xem') }}
                                </a>

                                @auth
                                    @php
                                        $allowedGroups = json_decode(\App\Models\SystemSetting::get('digital_download_allowed_groups', '[]'), true) ?: [];
                                        $userGroupId = auth()->user()->patronDetail?->patron_group_id;
                                        $canDownload = in_array($userGroupId, $allowedGroups);
                                    @endphp

                                    @if($canDownload)
                                        <a href="{{ route('site.digital-resources.download', $resource->id) }}"
                                           class="inline-flex items-center px-4 py-1.5 bg-[#3b82f6] text-white rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 active:scale-95 transition-all shadow-sm">
                                            <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('Tải') }}
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Tự động lấy số trang PDF
        const pdfUrl = "{{ $resource->file_url }}";
        const pageCountSpan = document.getElementById('pdf-page-count');
        
        if (pdfUrl && pageCountSpan && pageCountSpan.textContent.trim() === '---') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            const loadingTask = pdfjsLib.getDocument(pdfUrl);
            loadingTask.promise.then(function(pdf) {
                pageCountSpan.textContent = pdf.numPages + ' {{ __("trang") }}';
            }).catch(function(error) {
                console.error('Error loading PDF to get page count:', error);
            });
        }
    });
</script>
