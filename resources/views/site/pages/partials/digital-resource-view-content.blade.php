<div class="space-y-4 animate-fade-in" 
     x-data="{ isFullscreen: false }"
     x-effect="document.body.style.overflow = isFullscreen ? 'hidden' : 'auto'">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between bg-card p-3 border border-border rounded-sm shadow-sm" x-show="!isFullscreen" x-transition>
        <div class="flex items-center gap-3">
            <a href="{{ route('site.digital-resources.show', $resource->id) }}" 
               class="p-2 rounded bg-muted hover:bg-primary/10 hover:text-primary active:scale-95 transition-all border border-border shadow-sm group"
               title="{{ __('Quay lại chi tiết') }}">
                <i data-lucide="arrow-left" class="w-4 h-4 transition-transform group-hover:-translate-x-1"></i>
            </a>
            <div class="w-1 h-4 bg-vttu-red rounded-full"></div>
            <h1 class="text-xs md:text-sm font-black text-vttu-dark uppercase tracking-tight line-clamp-1">
                {{ $resource->title }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            @auth
                @php
                    $allowedGroups = json_decode(\App\Models\SystemSetting::get('digital_download_allowed_groups', '[]'), true) ?: [];
                    $userGroupId = auth()->user()->patronDetail?->patron_group_id;
                    $canDownload = in_array($userGroupId, $allowedGroups);
                @endphp

                @if($canDownload)
                <a href="{{ route('admin.digital-resources.download', $resource->id) }}"
                   class="hidden md:flex items-center px-4 py-2 bg-[#3b82f6] text-white rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 active:scale-95 transition-all shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('Tải tài liệu') }}
                </a>
                @endif
            @endauth

            <button @click="isFullscreen = true; if(typeof sidebarOpen !== 'undefined') sidebarOpen = false" 
                    class="flex items-center px-4 py-2 bg-vttu-red text-white rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-vttu-dark active:scale-95 transition-all shadow-sm">
                <i data-lucide="maximize" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('Mở rộng tối đa') }}
            </button>
        </div>
    </div>

    <!-- PDF Viewer Container -->
    <div id="pdf-viewer-wrapper" class="bg-slate-950 border border-border rounded-sm shadow-lg overflow-hidden transition-all duration-500 ease-in-out"
         :class="isFullscreen ? 'fixed inset-0 z-[9999] m-0 rounded-none w-screen h-screen bg-black' : 'relative h-[750px] md:h-[calc(100vh-180px)]'">
        
        <!-- PDF.js Canvas Container -->
        <div id="pdf-render-container" class="w-full h-full overflow-y-auto custom-scrollbar flex flex-col items-center bg-black/40 p-4 gap-6">
            <!-- Loading State -->
            <div id="pdf-loading" class="flex flex-col items-center justify-center h-full">
                <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="text-[10px] font-bold uppercase tracking-widest mt-4 text-slate-400">{{ __('Đang chuẩn bị tài liệu...') }}</p>
            </div>
        </div>

        <!-- Floating Close Button in Fullscreen -->
        <div x-show="isFullscreen" class="absolute top-4 right-6 z-[10000]" x-transition>
            <button @click="isFullscreen = false; if(typeof sidebarOpen !== 'undefined') sidebarOpen = true" 
                    class="flex items-center gap-2 px-4 py-2 bg-vttu-red text-white rounded-full shadow-2xl hover:bg-vttu-dark active:scale-90 transition-all border-2 border-white/20 group">
                <i data-lucide="minimize" class="w-5 h-5"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Thu nhỏ') }}</span>
            </button>
        </div>
    </div>

    @php
        $viewLimits = json_decode(\App\Models\SystemSetting::get('digital_view_page_limits', '[]'), true) ?: [];
        $userGroupId = auth()->user()?->patronDetail?->patron_group_id;
        $pageLimit = (!auth()->check() || is_null($userGroupId)) ? ($viewLimits['guest'] ?? 10) : ($viewLimits[$userGroupId] ?? 0);
    @endphp

    <input type="hidden" id="pdf-data" 
           data-url="{{ $resource->file_url }}" 
           data-limit="{{ $pageLimit }}">

    @if(!$resource->file_url)
        <div class="w-full h-full flex flex-col items-center justify-center bg-muted/20">
            <div class="w-16 h-16 bg-vttu-red/10 rounded-full flex items-center justify-center text-vttu-red mb-4">
                <i data-lucide="file-warning" class="w-8 h-8"></i>
            </div>
            <h3 class="text-sm font-bold text-foreground uppercase tracking-widest">{{ __('Không thể tải file PDF') }}</h3>
            <p class="text-xs text-muted-foreground mt-2">{{ __('Vui lòng kiểm tra lại đường dẫn tệp tin hoặc liên hệ quản trị viên.') }}</p>
            <a href="{{ route('site.digital-resources.show', $resource->id) }}" class="mt-6 text-xs font-bold text-vttu-red hover:underline italic">
                <i data-lucide="arrow-left" class="w-3 h-3 inline mr-1"></i> {{ __('Quay lại trang chi tiết') }}
            </a>
        </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PDF.js worker setup
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfData = document.getElementById('pdf-data');
        const url = pdfData.getAttribute('data-url');
        const limit = parseInt(pdfData.getAttribute('data-limit') || 0);
        const container = document.getElementById('pdf-render-container');
        const loading = document.getElementById('pdf-loading');

        if (!url) return;

        // Load PDF document
        console.log('Loading PDF from:', url);
        const loadingTask = pdfjsLib.getDocument({
            url: url,
            withCredentials: true // Quan trọng để đọc file từ storage local/session
        });

        loadingTask.promise.then(async function(pdf) {
            loading.style.display = 'none';
            const totalPages = pdf.numPages;
            const pagesToRender = limit > 0 ? Math.min(limit, totalPages) : totalPages;

            console.log('PDF Loaded successfully!');
            console.log('Total pages:', totalPages);
            console.log('Pages to render:', pagesToRender);

            for (let pageNum = 1; pageNum <= pagesToRender; pageNum++) {
                try {
                    console.log('Rendering page:', pageNum);
                    await renderPage(pdf, pageNum);
                } catch (err) {
                    console.error('Error rendering page ' + pageNum + ':', err);
                }
            }

            // If limited, show a "Login for more" card at the end
            if (limit > 0 && totalPages > limit) {
                const promoCard = document.createElement('div');
                promoCard.className = 'w-full max-w-2xl bg-slate-900 border-2 border-dashed border-primary/20 rounded-md p-8 text-center my-12 shadow-2xl animate-bounce-subtle';
                promoCard.innerHTML = `
                    <div class="inline-flex p-3 bg-primary/10 rounded-full text-primary mb-4">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-bold uppercase tracking-widest text-slate-100">${window.innerWidth < 640 ? 'Hết số trang xem thử' : 'Bạn đã xem hết số trang được phép'}</h3>
                    <p class="text-[11px] text-slate-400 mt-2 mb-6">Vui lòng đăng nhập với tài khoản có quyền cao hơn để xem toàn bộ <b>${totalPages}</b> trang của tài liệu này.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="/login" class="px-6 py-2 bg-primary text-primary-foreground rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 text-white">Đăng nhập ngay</a>
                        <span class="text-[9px] font-bold text-slate-500 uppercase">Hoặc</span>
                        <a href="{{ route('site.digital-resources.show', $resource->id) }}" class="text-[10px] font-bold text-slate-300 hover:text-primary transition-colors">Quay lại chi tiết</a>
                    </div>
                `;
                container.appendChild(promoCard);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

        }, function (reason) {
            loading.innerHTML = `
                <div class="text-vttu-red flex flex-col items-center">
                    <i data-lucide="alert-circle" class="w-8 h-8 mb-2"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">Lỗi tải PDF: ${reason.message}</p>
                </div>
            `;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        async function renderPage(pdf, pageNum) {
            const page = await pdf.getPage(pageNum);
            
            // Log chi tiết để tìm nguyên nhân thu nhỏ
            const containerWidth = container.clientWidth;
            const containerHeight = container.clientHeight;
            const unscaledViewport = page.getViewport({ scale: 1 });
            
            console.log(`--- Debug Render Page ${pageNum} ---`);
            console.log('Container Width:', containerWidth);
            console.log('Container Height:', containerHeight);
            console.log('PDF Original Width:', unscaledViewport.width);
            console.log('PDF Original Height:', unscaledViewport.height);

            // Tính toán tỷ lệ thông minh theo kích thước màn hình
            const isMobile = window.innerWidth < 768;
            const targetRatio = isMobile ? 0.95 : 0.70; // Mobile dùng 95%, Desktop dùng 70% cho thoáng
            
            const availableWidth = (containerWidth - 32) * targetRatio;
            const scale = availableWidth / unscaledViewport.width;
            const viewport = page.getViewport({ scale: scale });
            
            console.log('Calculated Scale:', scale);
            console.log('Final Render Width:', viewport.width);
            console.log('Final Render Height:', viewport.height);

            // Create page wrapper with fixed width for stability
            const pageWrapper = document.createElement('div');
            pageWrapper.className = 'relative shadow-2xl border border-slate-800 bg-white rounded-sm overflow-hidden mb-8 flex justify-center items-start';
            pageWrapper.style.width = availableWidth + 'px';
            pageWrapper.style.minHeight = viewport.height + 'px';
            
            // Create canvas
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d', { alpha: false });
            
            // Đặt kích thước canvas vật lý (cho độ nét)
            const dpr = window.devicePixelRatio || 1;
            canvas.width = viewport.width * dpr;
            canvas.height = viewport.height * dpr;
            
            // Đặt kích thước hiển thị CSS
            canvas.style.width = viewport.width + 'px';
            canvas.style.height = viewport.height + 'px';
            canvas.className = 'block select-none pointer-events-none';

            pageWrapper.appendChild(canvas);
            container.appendChild(pageWrapper);

            // Scale context để vẽ đúng tỷ lệ dpr
            context.scale(dpr, dpr);

            // Render PDF page into canvas context
            const renderContext = {
                canvasContext: context,
                viewport: viewport,
                enableWebGL: true,
                intent: 'display'
            };
            
            await page.render(renderContext).promise;
            console.log(`Page ${pageNum} rendered successfully at scale ${scale}`);
            console.log('-----------------------------------');
        }

        @auth
            @php
                $patronGroup = auth()->user()->patronDetail?->patronGroup;
            @endphp
            console.log('--- User Debug Info ---');
            console.log('User ID:', {{ auth()->id() }});
            console.log('User Name:', '{{ auth()->user()->full_name }}');
            console.log('Patron Group Info:', {
                id: {{ $patronGroup?->id ?? 'null' }},
                name: '{{ $patronGroup?->name ?? "None" }}',
                code: '{{ $patronGroup?->code ?? "None" }}'
            });
            console.log('-----------------------');
        @else
            console.log('User is not authenticated');
        @endauth

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Disable right-click on the whole document
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable specific shortcuts (Ctrl+P, Ctrl+S)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'p' || e.key === 'P' || e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                if (window.SwalHelper) {
                    window.SwalHelper.showWarning('Cảnh báo!', 'Hệ thống đã chặn chức năng tải/in tài liệu để bảo vệ bản quyền.');
                }
            }
        });
    });
</script>
