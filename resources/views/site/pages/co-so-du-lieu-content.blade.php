{{-- 
    Nội dung trang Cơ sở dữ liệu trực tuyến
    Hiển thị danh sách CSDL liên kết / mua bản quyền
--}}

<div class="space-y-8">
    <!-- Banner -->
    <div class="relative w-full rounded-lg overflow-hidden border border-border shadow-sm">
        <img src="{{ asset('assets/info/cosodulieu.png') }}" 
             alt="Cơ sở dữ liệu trực tuyến" 
             class="w-full h-auto object-cover max-h-[300px]">
    </div>

    <!-- Section Header -->
    <div class="border-b border-border pb-3">
        <h2 class="text-sm font-bold text-vttu-red uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-database text-xs"></i>
            Cơ sở dữ liệu trực tuyến thư viện
        </h2>
    </div>

    <!-- Database Items -->
    <div class="space-y-6">
        @if(isset($onlineDatabases) && $onlineDatabases->count() > 0)
            @foreach($onlineDatabases as $item)
                <div class="bg-card border border-border rounded-md shadow-sm p-4 md:p-6 flex flex-col md:flex-row gap-6 items-center md:items-start transition-all hover:shadow-md">
                    <!-- Logo Container -->
                    <div class="w-48 h-28 bg-white border border-slate-200 rounded-md p-4 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="max-w-full max-h-full object-contain">
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-300">
                                <i class="fas fa-database text-3xl mb-1"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $item->title }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Details Container -->
                    <div class="flex-1 space-y-4 text-center md:text-left">
                        <div class="text-sm text-muted-foreground leading-relaxed">
                            <span class="text-vttu-red font-bold">{{ $item->title }}</span>
                            <div class="mt-2 text-slate-600 dark:text-slate-300">
                                {!! $item->content !!}
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                            @if($item->url)
                                <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" 
                                   class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                                    Truy cập
                                </a>
                            @endif
                            @if($item->hd_url)
                                <a href="{{ $item->hd_url }}" target="_blank" rel="noopener noreferrer" 
                                   class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                                    Tài liệu hướng dẫn
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Fallback to Mock Data (SpringerLink & Can Tho University LRC) -->
            <!-- Item 1: SpringerLink -->
            <div class="bg-card border border-border rounded-md shadow-sm p-4 md:p-6 flex flex-col md:flex-row gap-6 items-center md:items-start transition-all hover:shadow-md">
                <!-- Logo Container -->
                <div class="w-48 h-28 bg-white border border-slate-200 rounded-md p-4 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg viewBox="0 0 300 120" xmlns="http://www.w3.org/2000/svg" class="h-16 w-auto">
                        <!-- Blue Horse Icon in Circle -->
                        <g transform="translate(150, 40) scale(0.7)">
                            <circle cx="0" cy="0" r="30" fill="none" stroke="#004C8C" stroke-width="3"/>
                            <path d="M-15,10 C-15,10 -18,-5 -10,-15 C-5,-20 5,-22 12,-18 C15,-16 18,-10 18,-5 C18,5 10,12 5,14 L5,18 L-5,18 L-5,14 C-10,12 -15,10 -15,10 Z" fill="#004C8C"/>
                            <path d="M -12,-4 C -10,-12 -3,-18 5,-15 C 10,-13 14,-8 11,2 C 10,6 6,10 0,11 C -4,11 -9,8 -11,4 C -12,2 -13,0 -12,-4 Z" fill="#004C8C"/>
                        </g>
                        <!-- Line -->
                        <line x1="60" y1="75" x2="240" y2="75" stroke="#004C8C" stroke-width="1.5" />
                        <!-- SpringerLink text -->
                        <text x="150" y="98" font-family="'Helvetica Neue', Helvetica, Arial, sans-serif" font-weight="bold" font-size="22" fill="#2D2D2D" text-anchor="middle">SpringerLink</text>
                    </svg>
                </div>
                
                <!-- Details Container -->
                <div class="flex-1 space-y-4 text-center md:text-left">
                    <p class="text-sm text-muted-foreground leading-relaxed">
                        <span class="text-vttu-red font-bold">Springer Link</span> là một trong những CSDL điện tử trực tuyến hàng đầu thế giới chuyên cung cấp truy cập đến các nguồn thông tin điện tử chất lượng cao với hơn 3.460 tạp chí, hơn 170 tài liệu tham khảo điện tử, hơn 4 triệu chương sách điện tử,... tổng cộng với hơn 10 triệu dữ liệu đóng góp. Bạn đọc có thể xem tạp chí toàn văn từ năm 1997 đến nay và sách toàn văn từ năm 2005 đến nay thuộc tất cả các chủ đề: Khoa học máy tính, khoa học kỹ thuật, khoa học đời sống, kinh tế, môi trường, luật, tâm lý học,...
                    </p>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <a href="https://link.springer.com" target="_blank" rel="noopener noreferrer" 
                           class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                            Truy cập
                        </a>
                        <a href="#" 
                           class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                            Tài liệu hướng dẫn
                        </a>
                    </div>
                </div>
            </div>

            <!-- Item 2: Trung tâm Học liệu ĐH Cần Thơ -->
            <div class="bg-card border border-border rounded-md shadow-sm p-4 md:p-6 flex flex-col md:flex-row gap-6 items-center md:items-start transition-all hover:shadow-md">
                <!-- Logo Container -->
                <div class="w-48 h-28 bg-white border border-slate-200 rounded-md p-4 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg" class="h-14 w-auto">
                        <!-- Rhombus CTU Logo -->
                        <g transform="translate(10, 5)">
                            <polygon points="35,0 70,35 35,70 0,35" fill="#005AAB" />
                            <polygon points="35,5 65,35 35,65 5,35" fill="#FFD700" />
                            <circle cx="35" cy="35" r="14" fill="#005AAB" />
                            <circle cx="35" cy="35" r="10" fill="#FFD700" />
                            <path d="M35,18 L35,52 M26,35 L44,35" stroke="#005AAB" stroke-width="2"/>
                        </g>
                        <!-- Text -->
                        <text x="90" y="32" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="#005AAB">TRUNG TÂM HỌC LIỆU</text>
                        <text x="90" y="52" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="#005AAB">ĐẠI HỌC CẦN THƠ</text>
                    </svg>
                </div>
                
                <!-- Details Container -->
                <div class="flex-1 space-y-4 text-center md:text-left">
                    <p class="text-sm text-muted-foreground leading-relaxed">
                        Nhằm đáp ứng nhu cầu nguồn học liệu phục vụ giảng dạy và nghiên cứu, Thư viện Trường Đại học Võ Trường Toản đã làm việc với <strong class="text-foreground">Trung tâm Học liệu - Đại học Cần Thơ</strong> để được hỗ trợ, chia sẻ số tài liệu số của Trung tâm Học liệu Trường Đại học Cần Thơ. Thư viện xin trân trọng giới thiệu nguồn dữ liệu này đến Quý bạn đọc để sử dụng.
                    </p>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <a href="https://lrc.ctu.edu.vn" target="_blank" rel="noopener noreferrer" 
                           class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                            Truy cập
                        </a>
                        <a href="#" 
                           class="px-5 py-1.5 border border-vttu-red hover:border-vttu-red/80 text-vttu-red hover:text-white hover:bg-vttu-red rounded font-bold text-xs shadow-sm transition-all active:scale-95 duration-200 inline-block">
                            Tài liệu hướng dẫn
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
