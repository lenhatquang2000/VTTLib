<div class="space-y-4 animate-fade-in">
    <!-- Banner Header -->
    <div class="relative w-full rounded-md overflow-hidden border border-border shadow-sm bg-[#FEF9DD] py-6 px-4 text-center">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap');
            .handwritten-title {
                font-family: 'Dancing Script', cursive;
                color: #8B0000;
            }
        </style>
        <div class="flex items-center justify-between">
            <!-- Left clip decorations -->
            <div class="hidden sm:flex flex-col gap-3 opacity-60">
                <i data-lucide="paperclip" class="w-5 h-5 text-[#8B0000] rotate-45"></i>
                <i data-lucide="paperclip" class="w-5 h-5 text-[#8B0000] -rotate-12"></i>
            </div>
            
            <div class="flex-1 space-y-1">
                <h1 class="text-3xl md:text-4xl font-black handwritten-title leading-tight">
                    Hướng dẫn tra cứu
                </h1>
                <h2 class="text-2xl md:text-3xl font-black handwritten-title leading-tight border-b border-dashed border-[#8B0000]/40 pb-2 max-w-xs mx-auto">
                    tài liệu giấy
                </h2>
            </div>

            <!-- Right paper/pen decorations -->
            <div class="hidden sm:flex flex-col gap-3 opacity-60">
                <i data-lucide="pen-tool" class="w-5 h-5 text-[#8B0000] -rotate-45"></i>
                <i data-lucide="file-text" class="w-5 h-5 text-[#8B0000] rotate-12"></i>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-card border border-border rounded-md p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-vttu-red/10 rounded flex items-center justify-center text-vttu-red flex-shrink-0">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <div class="space-y-1">
                <h4 class="font-bold text-foreground text-sm">Tra cứu trực tuyến trước khi tìm sách:</h4>
                <p class="text-xs text-muted-foreground leading-relaxed">
                    Hệ thống tra cứu trực tuyến OPAC cho phép bạn tìm kiếm sách giấy nhanh chóng và xác định chính xác vị trí kệ sách cần tìm để tiết kiệm thời gian.
                </p>
            </div>
        </div>
    </div>

    <!-- Step Instructions Section -->
    <div class="space-y-3 pt-3 border-t border-border">
        <h4 class="font-bold text-foreground text-sm flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-vttu-red"></i> Các bước tra cứu sách giấy:
        </h4>
        
        <div class="grid gap-2 text-xs">
            <!-- Step 1 -->
            <div class="flex gap-3 p-3 bg-card border border-border rounded-md shadow-sm">
                <div class="w-6 h-6 bg-vttu-red/10 text-vttu-red rounded flex items-center justify-center font-bold flex-shrink-0">
                    1
                </div>
                <div class="space-y-0.5">
                    <h5 class="font-bold text-foreground">Truy cập cổng tra cứu OPAC</h5>
                    <p class="text-muted-foreground leading-relaxed">
                        Vào mục <a href="/opac" class="text-vttu-red font-bold hover:underline">Tra cứu OPAC</a> trên thanh trình đơn chính của Cổng thông tin Thư viện.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex gap-3 p-3 bg-card border border-border rounded-md shadow-sm">
                <div class="w-6 h-6 bg-vttu-red/10 text-vttu-red rounded flex items-center justify-center font-bold flex-shrink-0">
                    2
                </div>
                <div class="space-y-0.5">
                    <h5 class="font-bold text-foreground">Nhập từ khóa tìm kiếm</h5>
                    <p class="text-muted-foreground leading-relaxed">
                        Nhập Tên sách, Tên tác giả, hoặc Chủ đề của cuốn sách cần tìm vào ô tìm kiếm và nhấn Tìm kiếm.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex gap-3 p-3 bg-card border border-border rounded-md shadow-sm">
                <div class="w-6 h-6 bg-vttu-red/10 text-vttu-red rounded flex items-center justify-center font-bold flex-shrink-0">
                    3
                </div>
                <div class="space-y-0.5">
                    <h5 class="font-bold text-foreground">Xác định Vị trí kệ sách</h5>
                    <p class="text-muted-foreground leading-relaxed">
                        Bấm vào chi tiết cuốn sách, xem thông tin <strong>Vị trí lưu trữ</strong> (ví dụ: Phòng mượn giáo trình) và ghi lại <strong>Ký hiệu xếp giá</strong> ghi trên gáy sách.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex gap-3 p-3 bg-card border border-border rounded-md shadow-sm">
                <div class="w-6 h-6 bg-vttu-red/10 text-vttu-red rounded flex items-center justify-center font-bold flex-shrink-0">
                    4
                </div>
                <div class="space-y-0.5">
                    <h5 class="font-bold text-foreground">Tìm sách trên kệ tại Thư viện</h5>
                    <p class="text-muted-foreground leading-relaxed">
                        Đến khu vực kệ tương ứng tại Thư viện và tìm cuốn sách theo đúng ký hiệu xếp giá đã ghi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
