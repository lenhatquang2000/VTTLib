@extends('layouts.site')

@section('title', $node->display_name)

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="container mx-auto px-6 py-20">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                <!-- Contact Info -->
                <div class="space-y-12">
                    <div>
                        <h1 class="text-5xl font-black text-vttu-dark mb-6 tracking-tight">{{ $node->display_name }}</h1>
                        <p class="text-xl text-vttu-red/70">{{ $node->description ?? 'Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn.' }}</p>
                    </div>

                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-lg flex items-center justify-center text-vttu-red shrink-0 border border-slate-100">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-vttu-dark">Địa chỉ trụ sở</h4>
                                <p class="text-vttu-dark/60 mt-1 font-medium">QL1A, Tân Phú Thạnh, Châu Thành A, Hậu Giang.</p>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-lg flex items-center justify-center text-vttu-red shrink-0 border border-slate-100">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-vttu-dark">Đường dây nóng</h4>
                                <p class="text-vttu-dark/60 mt-1 font-medium">(+84) 123 456 789</p>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-lg flex items-center justify-center text-vttu-red shrink-0 border border-slate-100">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-vttu-dark">Email hỗ trợ</h4>
                                <p class="text-vttu-dark/60 mt-1 font-medium">lib-support@vttu.edu.vn</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="flex gap-4 pt-8">
                        <a href="#" class="w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white p-10 rounded-3xl shadow-xl border border-slate-100 relative">
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-vttu-red rounded-full blur-3xl opacity-10"></div>
                    <form action="#" method="POST" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black uppercase tracking-widest text-vttu-dark/40 ml-2">Họ và tên</label>
                                <input type="text" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-vttu-red/10 focus:border-vttu-red transition-all font-bold" placeholder="Nguyễn Văn A">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-black uppercase tracking-widest text-vttu-dark/40 ml-2">Email</label>
                                <input type="email" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-vttu-red/10 focus:border-vttu-red transition-all font-bold" placeholder="email@example.com">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-vttu-dark/40 ml-2">Chủ đề</label>
                            <input type="text" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-vttu-red/10 focus:border-vttu-red transition-all font-bold" placeholder="Tôi cần hỗ trợ về...">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-vttu-dark/40 ml-2">Nội dung tin nhắn</label>
                            <textarea rows="5" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-vttu-red/10 focus:border-vttu-red transition-all font-bold" placeholder="Viết tin nhắn của bạn tại đây..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-5 bg-vttu-red hover:bg-vttu-dark text-white font-black rounded-2xl shadow-xl shadow-vttu-red/20 transition-all transform hover:-translate-y-1 uppercase tracking-widest text-sm">
                            Gửi yêu cầu ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
