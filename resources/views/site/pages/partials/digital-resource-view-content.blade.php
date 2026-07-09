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
                <a href="{{ route('site.digital-resources.download', $resource->id) }}"
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

    @php
        $viewLimits    = json_decode(\App\Models\SystemSetting::get('digital_view_page_limits', '[]'), true) ?: [];
        $allowedGroups = json_decode(\App\Models\SystemSetting::get('digital_download_allowed_groups', '[]'), true) ?: [];
        $userGroupId   = auth()->user()?->patronDetail?->patron_group_id;

        $canDownload = in_array($userGroupId, $allowedGroups);
        $pageLimit   = (!auth()->check() || is_null($userGroupId))
                     ? ($viewLimits['guest'] ?? 10)
                     : ($viewLimits[$userGroupId] ?? 0);
    @endphp

    @if($canDownload)
        {{-- ===== GIÁO VIÊN: Iframe stream thẳng file gốc ===== --}}
        <div class="bg-card border border-border rounded-sm shadow-lg overflow-hidden relative"
             :class="isFullscreen ? 'fixed inset-0 z-[9999] m-0 rounded-none w-screen h-screen' : 'relative h-[750px] md:h-[calc(100vh-180px)]'">

            <iframe src="{{ route('site.digital-resources.stream', $resource->id) }}#toolbar=1&navpanes=1&scrollbar=1"
                    class="w-full h-full border-none"
                    allow="autoplay">
            </iframe>

            <div x-show="isFullscreen" class="absolute top-4 right-6 z-[10000]" x-transition>
                <button @click="isFullscreen = false; if(typeof sidebarOpen !== 'undefined') sidebarOpen = true"
                        class="flex items-center gap-2 px-4 py-2 bg-vttu-red text-white rounded-full shadow-2xl hover:bg-vttu-dark active:scale-90 transition-all border-2 border-white/20">
                    <i data-lucide="minimize" class="w-5 h-5"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Thu nhỏ') }}</span>
                </button>
            </div>
        </div>
    @else
        {{-- ===== SINH VIÊN / KHÁCH: PDF.js 3 layers + stream mã hóa ===== --}}
        <div id="pdf-viewer-wrapper"
             class="bg-slate-950 border border-border rounded-sm shadow-lg overflow-hidden"
             :class="isFullscreen ? 'fixed inset-0 z-[9999] m-0 rounded-none w-screen h-screen bg-black' : 'relative h-[750px] md:h-[calc(100vh-180px)]'">

            <div id="pdf-render-container"
                 class="w-full h-full overflow-y-auto flex flex-col items-center bg-black/40 p-4 gap-8">

                {{-- Loading spinner --}}
                <div id="pdf-loading" class="flex flex-col items-center justify-center min-h-full">
                    <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[10px] font-bold uppercase tracking-widest mt-4 text-slate-400">{{ __('Đang chuẩn bị tài liệu...') }}</p>
                </div>
            </div>

            <div x-show="isFullscreen" class="absolute top-4 right-6 z-[10000]" x-transition>
                <button @click="isFullscreen = false; if(typeof sidebarOpen !== 'undefined') sidebarOpen = true"
                        class="flex items-center gap-2 px-4 py-2 bg-vttu-red text-white rounded-full shadow-2xl hover:bg-vttu-dark active:scale-90 transition-all border-2 border-white/20">
                    <i data-lucide="minimize" class="w-5 h-5"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Thu nhỏ') }}</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Hidden config cho JS --}}
    <input type="hidden" id="pdf-data"
           data-stream-url="{{ route('site.digital-resources.stream', $resource->id) }}"
           data-key-url="{{ auth()->check() ? route('site.digital-resources.stream-key', $resource->id) : '' }}"
           data-limit="{{ $pageLimit }}"
           data-mode="{{ $canDownload ? 'iframe' : 'secure' }}"
           data-authenticated="{{ auth()->check() ? '1' : '0' }}">

    @if(!$resource->file_url)
        <div class="w-full p-8 flex flex-col items-center justify-center bg-muted/20 rounded-sm border border-border">
            <i data-lucide="file-warning" class="w-10 h-10 text-vttu-red mb-3"></i>
            <h3 class="text-sm font-bold text-foreground uppercase tracking-widest">{{ __('Không thể tải file PDF') }}</h3>
            <p class="text-xs text-muted-foreground mt-2">{{ __('Vui lòng kiểm tra lại đường dẫn tệp tin hoặc liên hệ quản trị viên.') }}</p>
        </div>
    @endif
</div>

{{-- ===================================================
     CSS: 3 Layers stacked chính xác trên nhau
     Thứ tự từ dưới lên: Canvas → Text → Annotation
     =================================================== --}}
<style>
    /* Wrapper mỗi trang: relative container để các layer xếp chồng */
    .pdf-page-wrapper {
        position: relative;         /* Anchor cho absolute children */
        display: inline-block;      /* Vừa đúng với kích thước nội dung */
        margin-bottom: 2.5rem;
        box-shadow: 0 20px 60px -10px rgba(0,0,0,0.7);
        background: #fff;
        line-height: 1;
    }

    /* ─── LAYER 1: CANVAS ─────────────────────────────
       Đây là "hình ảnh" của trang PDF.
       Canvas render nội dung đồ họa của PDF lên đây.
       display: block để không có gap bên dưới.               */
    .pdf-page-wrapper canvas {
        display: block;
        /* Width/Height được set bằng JS theo viewport        */
    }

    /* ─── LAYER 2: TEXT LAYER ─────────────────────────
       Nổi PHÍA TRÊN canvas, căn đúng tọa độ PDF.
       Các <span> chứa từng đoạn chữ, trong suốt (color: transparent)
       nhưng selectable để search/ctrl+F hoạt động.           */
    .pdf-page-wrapper .textLayer {
        position: absolute;
        inset: 0;               /* top:0, right:0, bottom:0, left:0 */
        overflow: hidden;
        /* pointer-events: auto → cho phép bôi đen / chọn text */
        pointer-events: auto;
        cursor: text;
    }

    .pdf-page-wrapper .textLayer span,
    .pdf-page-wrapper .textLayer br {
        color: transparent;       /* Chữ trong suốt — canvas hiển thị thay */
        position: absolute;
        white-space: pre;
        cursor: text;
        transform-origin: 0% 0%;
    }

    /* Khi bôi đen: nền đỏ nhẹ, chữ vẫn transparent */
    .pdf-page-wrapper .textLayer span::selection {
        background: rgba(180, 20, 20, 0.25);
        color: transparent;
    }

    /* ─── LAYER 3: ANNOTATION LAYER ──────────────────
       Nổi CAO NHẤT, xử lý link, button, form, bookmark.
       pointer-events: auto để click được vào hyperlink.      */
    .pdf-page-wrapper .annotationLayer {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: auto;
    }

    .pdf-page-wrapper .annotationLayer .linkAnnotation > a {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        border: 2px solid transparent;
        border-radius: 2px;
        transition: border-color 0.15s;
    }

    .pdf-page-wrapper .annotationLayer .linkAnnotation > a:hover {
        border-color: rgba(59, 130, 246, 0.5);
        background: rgba(59, 130, 246, 0.08);
    }
</style>

{{-- ===================================================
     JavaScript: Load PDF.js, giải mã XOR, render 3 layers
     =================================================== --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {

    // ─── Đọc config ───────────────────────────────────────
    const pdfData    = document.getElementById('pdf-data');
    const mode       = pdfData.dataset.mode;
    const streamUrl  = pdfData.dataset.streamUrl;
    const keyUrl     = pdfData.dataset.keyUrl;
    const limit      = parseInt(pdfData.dataset.limit || '0');
    const isAuth     = pdfData.dataset.authenticated === '1';

    // Giáo viên dùng iframe → dừng tại đây
    if (mode === 'iframe') {
        if (window.lucide) lucide.createIcons();
        return;
    }

    // ─── Worker ───────────────────────────────────────────
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const container = document.getElementById('pdf-render-container');
    const loading   = document.getElementById('pdf-loading');

    if (!streamUrl || !container) return;

    try {

        // ══════════════════════════════════════════════════
        // BƯỚC 1: Fetch encryption key từ server
        //   Server gắn key theo session (random 32 bytes).
        //   Client nhận key dạng base64 → decode → Uint8Array
        // ══════════════════════════════════════════════════
        let decryptKey = null;
        if (isAuth && keyUrl) {
            const keyRes = await fetch(keyUrl, { credentials: 'include' });
            if (keyRes.ok) {
                const { key } = await keyRes.json();
                decryptKey = base64ToBytes(key);
            }
        }

        // ══════════════════════════════════════════════════
        // BƯỚC 2: Tải stream (binary đã XOR encrypt từ server)
        //   Server chỉ gửi file gốc nếu là giáo viên (iframe mode).
        //   Sinh viên/guest nhận binary đã mã hóa XOR.
        // ══════════════════════════════════════════════════
        const streamRes = await fetch(streamUrl, { credentials: 'include' });
        if (!streamRes.ok) throw new Error('Không thể tải tài liệu (HTTP ' + streamRes.status + ')');

        const rawBuf = await streamRes.arrayBuffer();

        // ══════════════════════════════════════════════════
        // BƯỚC 3: Giải mã XOR
        //   XOR là symmetric: encrypt = decrypt (cùng key)
        //   Mỗi byte của file XOR với key[i % keyLen]
        // ══════════════════════════════════════════════════
        let pdfBytes;
        if (decryptKey) {
            pdfBytes = xorCipher(new Uint8Array(rawBuf), decryptKey);
        } else {
            pdfBytes = new Uint8Array(rawBuf); // Guest fallback
        }

        // ══════════════════════════════════════════════════
        // BƯỚC 4: PDF.js load từ Uint8Array (không có URL gốc)
        // ══════════════════════════════════════════════════
        const loadTask = pdfjsLib.getDocument({ data: pdfBytes });
        const pdf      = await loadTask.promise;

        loading.style.display = 'none';

        const totalPages    = pdf.numPages;
        const pagesToRender = limit > 0 ? Math.min(limit, totalPages) : totalPages;

        // ══════════════════════════════════════════════════
        // BƯỚC 5: Render từng trang — 3 layers chồng nhau
        // ══════════════════════════════════════════════════
        for (let pageNum = 1; pageNum <= pagesToRender; pageNum++) {
            await renderPage(pdf, pageNum);
        }

        // ══════════════════════════════════════════════════
        // BƯỚC 6: Card giới hạn trang
        // ══════════════════════════════════════════════════
        if (limit > 0 && totalPages > limit) {
            const card = document.createElement('div');
            card.className = 'w-full max-w-xl bg-slate-900 border-2 border-dashed border-primary/30 rounded-md p-8 text-center my-8 shadow-2xl';
            card.innerHTML = `
                <div class="inline-flex p-3 bg-primary/10 rounded-full text-primary mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-100 mb-2">Bạn đã xem hết ${limit} trang cho phép</h3>
                <p class="text-[11px] text-slate-400 mb-6">Tài liệu có tổng cộng <b class="text-white">${totalPages}</b> trang.<br>Vui lòng đăng nhập với tài khoản giáo viên để xem toàn bộ.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="/login" class="px-6 py-2 bg-primary text-white rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Đăng nhập ngay</a>
                    <a href="{{ route('site.digital-resources.show', $resource->id) }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-200 transition-colors">← Quay lại chi tiết</a>
                </div>
            `;
            container.appendChild(card);
        }

    } catch (err) {
        console.error('[PDF Viewer Error]', err);
        if (loading) {
            loading.innerHTML = `
                <div class="flex flex-col items-center gap-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-vttu-red" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-200">Lỗi tải tài liệu</p>
                    <p class="text-[10px] text-slate-400 max-w-xs">${err.message}</p>
                </div>
            `;
        }
    }

    // ══════════════════════════════════════════════════════
    // renderPage: Tạo 3 layer chồng lên nhau cho 1 trang PDF
    //
    //  ┌──────────────────────────────────┐  ← pageWrapper (position: relative)
    //  │  [3] Annotation Layer (inset:0)  │  ← links, hyperlinks
    //  │  [2] Text Layer (inset:0)        │  ← text spans trong suốt
    //  │  [1] Canvas (block)              │  ← hình ảnh PDF render
    //  └──────────────────────────────────┘
    // ══════════════════════════════════════════════════════
    async function renderPage(pdf, pageNum) {
        const page = await pdf.getPage(pageNum);

        // Tính scale để vừa với container
        const containerWidth   = container.clientWidth - 32;
        const baseViewport     = page.getViewport({ scale: 1 });
        const isMobile         = window.innerWidth < 768;
        const fitRatio         = isMobile ? 0.95 : 0.72;
        const targetWidth      = containerWidth * fitRatio;
        const scale            = targetWidth / baseViewport.width;
        const viewport         = page.getViewport({ scale });

        // devicePixelRatio để canvas sắc nét trên màn hình HiDPI
        const dpr         = window.devicePixelRatio || 1;
        const renderVP    = page.getViewport({ scale: scale * dpr });

        // ── pageWrapper ──────────────────────────────────
        const wrapper = document.createElement('div');
        wrapper.className = 'pdf-page-wrapper';
        wrapper.setAttribute('data-page', pageNum);
        // Kích thước bằng viewport CSS (không nhân DPR)
        wrapper.style.width  = viewport.width  + 'px';
        wrapper.style.height = viewport.height + 'px';

        // ── LAYER 1: Canvas ──────────────────────────────
        // Canvas có kích thước pixel = viewport * DPR (sắc nét)
        // nhưng CSS width/height = viewport (hiển thị đúng kích thước)
        const canvas = document.createElement('canvas');
        const ctx    = canvas.getContext('2d');
        canvas.width         = Math.floor(renderVP.width);
        canvas.height        = Math.floor(renderVP.height);
        canvas.style.width   = viewport.width  + 'px';
        canvas.style.height  = viewport.height + 'px';
        canvas.style.display = 'block';

        await page.render({ canvasContext: ctx, viewport: renderVP }).promise;
        wrapper.appendChild(canvas);

        // ── LAYER 2: Text Layer ──────────────────────────
        // Div có cùng kích thước viewport CSS,
        // PDF.js sẽ inject các <span> được transform
        // đúng tọa độ văn bản của trang.
        const textDiv = document.createElement('div');
        textDiv.className   = 'textLayer';
        textDiv.style.width  = viewport.width  + 'px';
        textDiv.style.height = viewport.height + 'px';
        wrapper.appendChild(textDiv);

        const textContent = await page.getTextContent();
        const renderTask  = pdfjsLib.renderTextLayer({
            textContentSource: textContent,
            container:         textDiv,
            viewport:          viewport,
            textDivs:          [],
        });
        await renderTask.promise;

        // ── LAYER 3: Annotation Layer ────────────────────
        // Div cùng kích thước, chứa link / annotation
        const annotDiv = document.createElement('div');
        annotDiv.className   = 'annotationLayer';
        annotDiv.style.width  = viewport.width  + 'px';
        annotDiv.style.height = viewport.height + 'px';
        wrapper.appendChild(annotDiv);

        const annotations = await page.getAnnotations();
        if (annotations.length > 0) {
            // Minimal link service để scroll tới trang đích
            const linkService = {
                getDestinationHash: () => '',
                getAnchorUrl:       () => '',
                setHash:            () => {},
                navigateTo:         () => {},
                scrollPageIntoView: ({ pageNumber }) => {
                    const target = document.querySelector(`.pdf-page-wrapper[data-page="${pageNumber}"]`);
                    if (target) target.scrollIntoView({ behavior: 'smooth' });
                },
            };

            pdfjsLib.AnnotationLayer.render({
                viewport:        viewport.clone({ dontFlip: true }),
                div:             annotDiv,
                annotations:     annotations,
                page:            page,
                linkService:     linkService,
                downloadManager: null,
                renderForms:     false,
            });
        }

        container.appendChild(wrapper);
    }

    // ── Helpers ──────────────────────────────────────────
    function xorCipher(data, key) {
        const out = new Uint8Array(data.length);
        const kl  = key.length;
        for (let i = 0; i < data.length; i++) out[i] = data[i] ^ key[i % kl];
        return out;
    }

    function base64ToBytes(b64) {
        const bin = atob(b64);
        const arr = new Uint8Array(bin.length);
        for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
        return arr;
    }

    // ── Bảo vệ UI ────────────────────────────────────────
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if (e.ctrlKey && ['p', 'P', 's', 'S'].includes(e.key)) {
            e.preventDefault();
            if (window.SwalHelper) {
                window.SwalHelper.showWarning('Cảnh báo!', 'Hệ thống đã chặn chức năng tải/in tài liệu để bảo vệ bản quyền.');
            }
        }
    });

    if (window.lucide) lucide.createIcons();

    @auth
        @php $patronGroup = auth()->user()->patronDetail?->patronGroup; @endphp
        console.log('[PDF Viewer] User:', {{ auth()->id() }},
                    '| Group:', '{{ $patronGroup?->name ?? "None" }} ({{ $patronGroup?->code ?? "None" }})',
                    '| Mode:', '{{ $canDownload ? "iframe" : "secure-encrypted" }}',
                    '| Page limit:', {{ $pageLimit }});
    @else
        console.log('[PDF Viewer] Guest | Mode: secure-encrypted | Page limit:', {{ $pageLimit }});
    @endauth
});
</script>
