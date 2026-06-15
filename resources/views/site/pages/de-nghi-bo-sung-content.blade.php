<div class="space-y-3 animate-fade-in">
    <!-- Section Header -->
    <div class="border-b border-border pb-2">
        <h2 class="text-lg md:text-xl font-black text-foreground tracking-tight leading-tight uppercase flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-vttu-red"></i>
            Đề đề nghị bổ sung tài liệu
        </h2>
        <div class="w-16 h-1 bg-vttu-red mt-1 rounded"></div>
    </div>

    <!-- Intro Card -->
    <div class="bg-card border border-border rounded p-3 shadow-sm">
        <p class="text-xs text-muted-foreground leading-relaxed">
            Nhằm phục vụ tốt nhất cho nhu cầu nghiên cứu, giảng dạy và học tập, bạn đọc có thể gửi đề xuất bổ sung các tài liệu, sách hoặc giáo trình chưa có trong thư viện. Thư viện sẽ tiếp nhận và tiến hành xét duyệt mua sắm.
        </p>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="flex items-center gap-2 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded text-xs">
            <i data-lucide="check-circle-2" class="w-4 h-4 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3 bg-destructive/10 border border-destructive/20 text-destructive rounded text-xs space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span>Đã xảy ra lỗi vui lòng kiểm tra lại:</span>
            </div>
            <ul class="list-disc list-inside pl-2 text-[11px] space-y-0.5 text-destructive/90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Proposal Form -->
    <form action="{{ route('site.proposal.store') }}" method="POST" class="bg-card border border-border rounded p-3 shadow-sm space-y-3">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Fullname -->
            <div>
                <label for="fullname" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Họ và tên <span class="text-vttu-red">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="fullname" 
                           name="fullname" 
                           required 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           placeholder="Nhập họ và tên của bạn"
                           value="{{ auth()->check() ? auth()->user()->name : old('fullname') }}">
                </div>
            </div>

            <!-- Email/Phone -->
            <div>
                <label for="email_phone" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Email / Số điện thoại <span class="text-vttu-red">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="email_phone" 
                           name="email_phone" 
                           required 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           placeholder="Nhập email hoặc số điện thoại liên hệ"
                           value="{{ auth()->check() ? (auth()->user()->email ?? auth()->user()->username) : old('email_phone') }}">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Book Title -->
            <div class="md:col-span-2">
                <label for="book_title" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Tên sách / Tài liệu <span class="text-vttu-red">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="book_title" 
                           name="book_title" 
                           required 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           placeholder="Tên sách, giáo trình hoặc tài liệu cần đề xuất"
                           value="{{ old('book_title') }}">
                </div>
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Số lượng đề xuất <span class="text-vttu-red">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           required 
                           min="1" 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           value="{{ old('quantity', 1) }}">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Author -->
            <div>
                <label for="author" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Tác giả
                </label>
                <div class="relative">
                    <input type="text" 
                           id="author" 
                           name="author" 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           placeholder="Nhập tên tác giả (nếu biết)"
                           value="{{ old('author') }}">
                </div>
            </div>

            <!-- Publisher / Year -->
            <div>
                <label for="publisher_year" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                    Nhà xuất bản / Năm xuất bản
                </label>
                <div class="relative">
                    <input type="text" 
                           id="publisher_year" 
                           name="publisher_year" 
                           class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all" 
                           placeholder="Nhập nhà xuất bản, năm xuất bản"
                           value="{{ old('publisher_year') }}">
                </div>
            </div>
        </div>

        <!-- Reason -->
        <div>
            <label for="reason" class="block text-xs font-bold text-foreground mb-1 uppercase tracking-wider">
                Lý do đề xuất / Thông tin thêm
            </label>
            <div class="relative">
                <textarea id="reason" 
                          name="reason" 
                          rows="3" 
                          class="w-full px-3 py-2 text-xs border border-border bg-background text-foreground rounded-sm focus:ring-1 focus:ring-vttu-red/50 focus:border-vttu-red/50 outline-none transition-all resize-none" 
                          placeholder="Mô tả lý do đề xuất, mục đích sử dụng (học tập, nghiên cứu...) hoặc thông tin liên kết ngoài...">{{ old('reason') }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-1">
            <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-vttu-red text-white text-xs font-bold uppercase tracking-wider rounded-sm hover:bg-vttu-red/90 active:scale-[0.98] transition-all cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                Gửi đề nghị bổ sung
            </button>
        </div>
    </form>
</div>
