<div class="space-y-4" x-data="{ showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-border pb-3 gap-2">
        <div>
            <h3 class="text-sm font-bold text-foreground flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-primary"></i> Cẩm nang Hướng dẫn sử dụng Thư viện
            </h3>
            <p class="text-xs text-muted-foreground mt-1">Lật các trang bên dưới để xem hướng dẫn chi tiết về hệ thống thư viện.</p>
        </div>
        <div class="flex gap-2">
            <button @click="showModal = true" class="inline-flex items-center justify-center px-3 py-1.5 bg-primary text-primary-foreground hover:bg-primary/90 text-xs font-bold rounded shadow-sm transition-all gap-1.5 active:scale-95 whitespace-nowrap">
                <i data-lucide="maximize" class="w-4 h-4"></i> Đọc Fullscreen (Popup)
            </button>
        </div>
    </div>

    <!-- Embedded Iframe -->
    <div class="w-full h-[600px] md:h-[750px] bg-card rounded-md overflow-hidden shadow-sm border border-border">
        <iframe class="w-full h-full border-0" 
                src="https://online.fliphtml5.com/nawuj/cfon/#p=20" 
                allowfullscreen="true" 
                webkitallowfullscreen="true" 
                mozallowfullscreen="true">
        </iframe>
    </div>

    <!-- Fullscreen Popup Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-2 sm:p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"
         @keydown.escape.window="showModal = false">
        
        <div class="relative w-full h-full flex flex-col bg-background border border-border rounded-md shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-2 border-b border-border bg-card">
                <span class="font-bold text-xs uppercase tracking-wider text-foreground">Cẩm nang Hướng dẫn sử dụng</span>
                <button @click="showModal = false" class="p-1 rounded hover:bg-muted text-muted-foreground hover:text-foreground transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <!-- Modal Content (Iframe) -->
            <div class="flex-1 w-full bg-slate-900">
                <iframe class="w-full h-full border-0" 
                        src="https://online.fliphtml5.com/nawuj/cfon/#p=20" 
                        allowfullscreen="true" 
                        webkitallowfullscreen="true" 
                        mozallowfullscreen="true">
                </iframe>
            </div>
        </div>
    </div>
</div>
