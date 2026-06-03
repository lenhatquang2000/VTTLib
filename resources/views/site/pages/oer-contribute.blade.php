@extends('layouts.site')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-white">
    <!-- Navigation Bar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-vttu-dark hover:text-vttu-red transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span class="text-sm font-bold uppercase tracking-wider">{{ __('Quay về trang chủ') }}</span>
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('site.oer.intro') }}" class="text-sm font-bold text-slate-600 hover:text-vttu-red transition-colors uppercase tracking-wider">{{ __('Giới thiệu') }}</a>
                    <a href="{{ route('site.page', 'tai-nguyen-giao-duc-mo') }}" class="text-sm font-bold text-slate-600 hover:text-vttu-red transition-colors uppercase tracking-wider">{{ __('Kho tài liệu mở') }}</a>
                    <a href="{{ route('site.oer.contribute') }}" class="text-sm font-bold text-vttu-red uppercase tracking-wider">{{ __('Đóng góp tài liệu') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-black text-vttu-dark tracking-tight mb-8 uppercase">
                {{ __('Đóng góp tài liệu OER') }}
            </h1>

            <div class="prose prose-lg max-w-none space-y-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Tại sao đóng góp OER?') }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        {{ __('Đóng góp tài nguyên giáo dục mở giúp cộng đồng học thuật phát triển, cho phép giảng viên và sinh viên trên toàn thế giới tiếp cận các tài liệu chất lượng cao miễn phí. Sự đóng góp của bạn có thể tạo ra tác động lớn đến giáo dục.') }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Quy trình đóng góp') }}
                    </h2>
                    <ol class="space-y-4 text-slate-600 list-decimal list-inside">
                        <li>{{ __('Chuẩn bị tài liệu của bạn (sách, bài giảng, video, v.v.)') }}</li>
                        <li>{{ __('Đảm bảo tài liệu có chất lượng cao và phù hợp với mục tiêu giáo dục') }}</li>
                        <li>{{ __('Chọn giấy phép Creative Commons phù hợp') }}</li>
                        <li>{{ __('Điền thông tin metadata (tiêu đề, tác giả, mô tả, từ khóa)') }}</li>
                        <li>{{ __('Tải lên tài liệu qua form đóng góp') }}</li>
                        <li>{{ __('Chờ xét duyệt từ đội ngũ thư viện') }}</li>
                        <li>{{ __('Tài liệu sẽ được công bố trong kho OER sau khi được duyệt') }}</li>
                    </ol>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Yêu cầu tài liệu') }}
                    </h2>
                    <ul class="space-y-4 text-slate-600">
                        <li class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                            <span>{{ __('Tài liệu phải có nội dung giáo dục rõ ràng') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                            <span>{{ __('Định dạng file hỗ trợ (PDF, MP4, DOCX, v.v.)') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                            <span>{{ __('Có mô tả chi tiết và từ khóa phù hợp') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                            <span>{{ __('Không vi phạm bản quyền của bên thứ ba') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                            <span>{{ __('Chấp nhận sử dụng giấy phép mở') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gradient-to-r from-vttu-red/10 to-vttu-red/5 rounded-2xl p-8 border border-vttu-red/20">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Form đóng góp') }}
                    </h2>
                    <p class="text-slate-600 mb-6">
                        {{ __('Để đóng góp tài liệu OER, vui lòng liên hệ với thư viện hoặc sử dụng form dưới đây. Chúng tôi sẽ xem xét và phản hồi trong vòng 3-5 ngày làm việc.') }}
                    </p>
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Họ và tên') }}</label>
                                <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all" placeholder="{{ __('Nhập họ và tên của bạn') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Email') }}</label>
                                <input type="email" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all" placeholder="{{ __('email@example.com') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Tiêu đề tài liệu') }}</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all" placeholder="{{ __('Nhập tiêu đề tài liệu') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Mô tả') }}</label>
                            <textarea rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all resize-none" placeholder="{{ __('Mô tả ngắn về nội dung tài liệu') }}"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Loại tài liệu') }}</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all">
                                <option value="">{{ __('Chọn loại tài liệu') }}</option>
                                <option value="textbook">{{ __('Giáo trình') }}</option>
                                <option value="course">{{ __('Khóa học') }}</option>
                                <option value="lecture">{{ __('Bài giảng') }}</option>
                                <option value="video">{{ __('Video') }}</option>
                                <option value="simulation">{{ __('Mô phỏng') }}</option>
                                <option value="guide">{{ __('Hướng dẫn') }}</option>
                                <option value="other">{{ __('Khác') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Giấy phép mong muốn') }}</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all">
                                <option value="">{{ __('Chọn giấy phép') }}</option>
                                <option value="CC BY">CC BY (Ghi nhận tác giả)</option>
                                <option value="CC BY-SA">CC BY-SA (Ghi nhận - Chia sẻ tương tự)</option>
                                <option value="CC BY-NC">CC BY-NC (Ghi nhận - Phi thương mại)</option>
                                <option value="CC BY-NC-SA">CC BY-NC-SA (Ghi nhận - Phi thương mại - Chia sẻ tương tự)</option>
                                <option value="CC0">CC0 (Public Domain)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Tải lên file') }}</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-vttu-red/50 transition-colors cursor-pointer">
                                <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                                <p class="text-sm text-slate-600">{{ __('Kéo và thả file vào đây hoặc click để chọn') }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ __('Hỗ trợ: PDF, DOCX, MP4, ZIP (tối đa 50MB)') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-vttu-dark mb-2 uppercase tracking-wider">{{ __('Ghi chú thêm') }}</label>
                            <textarea rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-vttu-red/30 focus:border-vttu-red/30 outline-none transition-all resize-none" placeholder="{{ __('Bất kỳ thông tin bổ sung nào bạn muốn chia sẻ') }}"></textarea>
                        </div>
                        <button type="submit" class="w-full px-8 py-4 bg-vttu-red text-white rounded-xl hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-lg shadow-vttu-red/20 font-black uppercase tracking-wider text-sm">
                            {{ __('Gửi đóng góp') }}
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Liên hệ') }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        {{ __('Nếu bạn có câu hỏi hoặc cần hỗ trợ, vui lòng liên hệ với chúng tôi:') }}
                    </p>
                    <div class="mt-4 space-y-2">
                        <p class="flex items-center gap-2 text-slate-600">
                            <i data-lucide="mail" class="w-5 h-5 text-vttu-red"></i>
                            <span>library@vttu.edu.vn</span>
                        </p>
                        <p class="flex items-center gap-2 text-slate-600">
                            <i data-lucide="phone" class="w-5 h-5 text-vttu-red"></i>
                            <span>(028) 1234 5678</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
