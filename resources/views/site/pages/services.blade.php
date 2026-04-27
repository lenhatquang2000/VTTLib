@extends('layouts.site')

@section('title', $node->display_name)

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Premium Header -->
    <section class="bg-white border-b border-slate-100 py-24 relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="px-4 py-1 bg-vttu-red/5 rounded-full border border-vttu-red/10 text-vttu-red text-xs font-bold uppercase tracking-widest mb-6 inline-block">
                Library Solutions
            </span>
            <h1 class="text-4xl md:text-6xl font-black mb-6 text-vttu-dark">{{ $node->display_name }}</h1>
            <p class="text-xl text-vttu-red/70 max-w-2xl mx-auto">{{ $node->description ?? 'Cung cấp các giải pháp hỗ trợ học tập và nghiên cứu đỉnh cao.' }}</p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @php
                    $services = [
                        ['icon' => 'fa-book-reader', 'title' => 'Mượn trả tài liệu', 'desc' => 'Hệ thống mượn trả tự động, gia hạn trực tuyến dễ dàng.'],
                        ['icon' => 'fa-laptop-code', 'title' => 'Truy cập số', 'desc' => 'Khai thác kho dữ liệu số khổng lồ mọi lúc mọi nơi.'],
                        ['icon' => 'fa-users-class', 'title' => 'Phòng học nhóm', 'desc' => 'Không gian hiện đại, trang bị đầy đủ thiết bị trình chiếu.'],
                        ['icon' => 'fa-microscope', 'title' => 'Hỗ trợ nghiên cứu', 'desc' => 'Dịch vụ cung cấp trích dẫn và tìm kiếm tài liệu chuyên sâu.'],
                        ['icon' => 'fa-print', 'title' => 'In ấn & Số hóa', 'desc' => 'Dịch vụ in ấn chất lượng cao và số hóa tài liệu theo yêu cầu.'],
                        ['icon' => 'fa-headset', 'title' => 'Tư vấn thông tin', 'desc' => 'Đội ngũ thủ thư sẵn sàng hỗ trợ giải đáp mọi thắc mắc.'],
                    ];
                @endphp

                @foreach($services as $s)
                <div class="group p-8 bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100">
                    <div class="w-16 h-16 bg-vttu-red/5 rounded-2xl flex items-center justify-center text-vttu-red mb-6 group-hover:bg-vttu-red group-hover:text-white transition-all">
                        <i class="fas {{ $s['icon'] }} text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-vttu-dark mb-4">{{ $s['title'] }}</h3>
                    <p class="text-vttu-dark/70 leading-relaxed text-sm">{{ $s['desc'] }}</p>
                    <div class="mt-6 pt-6 border-t border-slate-50">
                        <a href="#" class="text-vttu-red font-black flex items-center group/link uppercase text-xs tracking-wider">
                            Tìm hiểu thêm 
                            <i class="fas fa-arrow-right ml-2 group-hover/link:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Page Builder Content -->
    @if($node->activeItems()->count() > 0)
        @foreach($node->activeItems()->ordered()->get() as $item)
            @include('site.items.' . $item->item_type, ['item' => $item])
        @endforeach
    @endif
</div>
@endsection
