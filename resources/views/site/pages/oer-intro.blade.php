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
                    <a href="{{ route('site.oer.intro') }}" class="text-sm font-bold text-vttu-red uppercase tracking-wider">{{ __('Giới thiệu') }}</a>
                    <a href="{{ route('site.page', 'tai-nguyen-giao-duc-mo') }}" class="text-sm font-bold text-slate-600 hover:text-vttu-red transition-colors uppercase tracking-wider">{{ __('Kho tài liệu mở') }}</a>
                    <a href="{{ route('site.oer.contribute') }}" class="text-sm font-bold text-slate-600 hover:text-vttu-red transition-colors uppercase tracking-wider">{{ __('Đóng góp tài liệu') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- New Navigation Buttons at the beginning -->
            <div class="flex flex-wrap gap-4 mb-12">
                <a href="#intro" class="flex-1 min-w-[200px] py-4 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-sm rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
                    {{ __('Giới thiệu TNGDM') }}
                </a>
                <a href="#license" class="flex-1 min-w-[200px] py-4 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-sm rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
                    {{ __('Giấy phép truy cập mở') }}
                </a>
                <a href="#support" class="flex-1 min-w-[200px] py-4 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-sm rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
                    {{ __('Hỗ trợ tìm kiếm') }}
                </a>
            </div>

            <h1 id="intro" class="text-4xl md:text-5xl font-black text-vttu-dark tracking-tight mb-8 uppercase">
                {{ __('Tài nguyên giáo dục mở') }}
            </h1>

            <div class="prose prose-lg max-w-none space-y-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <p class="text-slate-600 leading-relaxed italic">
                        {{ __('Tài nguyên giáo dục mở (OER) là nguồn tài nguyên truy cập miễn phí hỗ trợ cho việc học tập, nghiên cứu và giảng dạy thuộc mọi đối tượng, hướng đến một xã hội học tập suốt đời. OER cung cấp đa dạng các loại hình tài nguyên, như: Giáo trình số, tạp chí truy cập mở, bản ghi âm (recording), các khóa tập huấn miễn phí (free learning course), dữ liệu nghiên cứu (dataset), video... OER không chỉ cung cấp tài nguyên để sử dụng, mà tại đó, người dùng có thể tài sử dụng, chỉnh sửa tùy theo mục đích và giấy phép truy cập mở.') }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight border-b pb-2">
                        {{ __('Những lợi ích mang lại') }}
                    </h2>
                    <ul class="space-y-6 text-slate-600">
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="edit-3" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Khả năng tùy chỉnh') }}</strong>
                                <span class="text-sm">{{ __('OER thuộc giấy phép CC-SA người sử dụng dùng nguồn tài nguyên có thể tùy chỉnh tài liệu, trở thành một tài liệu phái sinh.') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="refresh-cw" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Tính cập nhật nhanh chóng') }}</strong>
                                <span class="text-sm">{{ __('OER được cập nhật nhanh chóng theo thời gian, giúp người dùng theo dõi và tiếp nhận các tri thức mới;') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="globe" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Đa dạng văn hóa') }}</strong>
                                <span class="text-sm">{{ __('OER phản ánh các sắc màu văn hóa khác nhau, thể hiện sự đại diện của từng quốc gia;') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="unlock" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Dễ dàng tiếp cận') }}</strong>
                                <span class="text-sm">{{ __('OER hỗ trợ tài nguyên có kinh phí thấp, giúp nhiều người học có thể tiếp cận nhanh chóng;') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="clock" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Truy cập thuận tiện') }}</strong>
                                <span class="text-sm">{{ __('OER mang đến nguồn tài nguyên số, truy cập khắp mọi nơi và mọi lúc;') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="scale" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Tính công bằng') }}</strong>
                                <span class="text-sm">{{ __('OER hòa hợp giữa các nguồn tài nguyên trong quá trình truy cập, không phân biệt về đối tượng và khả năng tài chính;') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0 mt-1">
                                <i data-lucide="trending-up" class="w-4 h-4 text-vttu-red"></i>
                            </div>
                            <div>
                                <strong class="text-vttu-dark block mb-1 uppercase tracking-wider text-sm">{{ __('Phát triển chuyên sâu') }}</strong>
                                <span class="text-sm">{{ __('OER giúp người học nâng cao trải nghiệm học tập thông qua nhiều loại hình tài liệu, cộng đồng tương tác và các khóa học với các chuyên gia.') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- YouTube Video Section -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight">
                        {{ __('Video giới thiệu tài nguyên giáo dục mở') }}
                    </h2>
                    <div class="aspect-video rounded-xl overflow-hidden shadow-inner bg-slate-100 border border-slate-200">
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/ZFeyCc6we-s" 
                                title="Video giới thiệu tài nguyên giáo dục mở" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen></iframe>
                    </div>
                </div>

                <div id="license" class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                        {{ __('Giấy phép Creative Commons') }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed mb-6 text-sm">
                        {{ __('Hầu hết các tài nguyên OER sử dụng giấy phép Creative Commons, cho phép người dùng biết cách họ có thể sử dụng, tái sử dụng và chia sẻ tài liệu.') }}
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <h3 class="font-black text-vttu-dark mb-2 text-sm uppercase">CC BY</h3>
                            <p class="text-[11px] text-slate-600">{{ __('Ghi nhận tác giả - Tự do sử dụng, chia sẻ, điều chỉnh') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <h3 class="font-black text-vttu-dark mb-2 text-sm uppercase">CC BY-SA</h3>
                            <p class="text-[11px] text-slate-600">{{ __('Ghi nhận tác giả - Chia sẻ tương tự') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <h3 class="font-black text-vttu-dark mb-2 text-sm uppercase">CC BY-NC</h3>
                            <p class="text-[11px] text-slate-600">{{ __('Ghi nhận tác giả - Phi thương mại') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <h3 class="font-black text-vttu-dark mb-2 text-sm uppercase">CC BY-NC-SA</h3>
                            <p class="text-[11px] text-slate-600">{{ __('Ghi nhận tác giả - Phi thương mại - Chia sẻ tương tự') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="support" class="mt-12 text-center">
                <a href="{{ route('site.page', 'tai-nguyen-giao-duc-mo') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-vttu-red text-white rounded-xl hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-lg shadow-vttu-red/20 font-black uppercase tracking-wider text-sm">
                    <span>{{ __('Khám phá kho tài liệu OER') }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
