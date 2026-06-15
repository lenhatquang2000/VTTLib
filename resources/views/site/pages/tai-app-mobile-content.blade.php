<div class="space-y-8 animate-fade-in">
    <!-- Section Header -->
    <div class="border-b border-slate-100 pb-6">
        <h2 class="text-2xl md:text-3xl font-black text-vttu-dark tracking-tight leading-tight uppercase">
            HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG THƯ VIỆN ĐIỆN TỬ TRÊN ĐIỆN THOẠI
        </h2>
        <div class="w-20 h-1 bg-vttu-red mt-3 rounded-full"></div>
    </div>

    <!-- Intro Card (Grid Layout) -->
    <div class="grid md:grid-cols-12 gap-6 items-center bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="md:col-span-8 space-y-4">
            <p class="text-slate-700 leading-relaxed font-medium">
                App Thư viện cho phép bạn đọc tra cứu tài liệu và đọc sách điện tử (tài liệu số hóa) trong Thư viện mọi lúc - mọi nơi. Truy cập nhanh và xem ngay trên di động bằng công cụ xem tài liệu trực tuyến hỗ trợ hầu hết các định dạng tài liệu.
            </p>
            <p class="text-slate-600 text-sm leading-relaxed">
                Đồng thời, có nhiều công cụ tiện ích giúp bạn đọc linh hoạt đọc tài liệu: Xoay ngang, dọc thiết bị; phóng to, thu nhỏ nội dung tài liệu; ghi chú thông tin (note) ngay trên tài liệu.
            </p>
        </div>
        <div class="md:col-span-4 flex justify-center">
            <div class="relative w-28 h-28 bg-vttu-red/10 rounded-full flex items-center justify-center text-vttu-red shadow-inner">
                <i class="fas fa-mobile-alt text-5xl"></i>
                <div class="absolute -top-1 -right-1 w-8 h-8 bg-vttu-yellow rounded-full flex items-center justify-center text-vttu-dark font-bold text-xs animate-bounce shadow">
                    New
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Grid -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Feature 1 -->
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-2 h-full bg-vttu-red"></div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-vttu-red/10 rounded-xl flex items-center justify-center text-vttu-red flex-shrink-0">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-base mb-2">Đọc Sách Linh Hoạt</h4>
                    <ul class="text-sm text-slate-600 space-y-1.5 list-disc list-inside">
                        <li>Hỗ trợ xoay ngang/dọc màn hình thiết bị</li>
                        <li>Phóng to, thu nhỏ nội dung linh hoạt</li>
                        <li>Tạo ghi chú trực tiếp ngay trên tài liệu</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-2 h-full bg-vttu-yellow"></div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-vttu-yellow/15 rounded-xl flex items-center justify-center text-vttu-dark flex-shrink-0">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-base mb-2">Thông Báo Tự Động</h4>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Ứng dụng sẽ tự động hiển thị thông báo mới cảnh báo cho bạn đọc khi có tài liệu quá hạn hoặc khi các tài liệu đăng ký được phê duyệt giúp bạn đọc luôn kiểm soát thời gian được sử dụng các tài liệu đang mượn trong Thư viện.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Section -->
    <div class="space-y-4 pt-4 border-t border-slate-100">
        <h4 class="font-bold text-slate-800 text-base flex items-center gap-2">
            <i class="fas fa-play-circle text-vttu-red"></i> Video hướng dẫn chi tiết
        </h4>
        <div class="relative w-full rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-black aspect-video">
            <video class="w-full h-full" controls preload="metadata">
                <source src="{{ asset('assets/videos/uri_ifs___V_WtI9v9grunrG63xTO58s6aay8QabjwjK0FeNZBktX7I.mp4') }}" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ thẻ phát video này.
            </video>
        </div>
    </div>
</div>
