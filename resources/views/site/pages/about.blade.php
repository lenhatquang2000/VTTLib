@extends('layouts.site')

@section('title', $node->meta_title ?: 'Giới Thiệu - Thư viện số')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header Section -->
    <section class="bg-blue-900 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="container mx-auto px-6 relative z-10">
            <h1 class="text-4xl md:text-6xl font-black mb-4">{{ $node->display_name }}</h1>
            <p class="text-xl text-blue-200 max-w-2xl">{{ $node->description ?? 'Tìm hiểu về sứ mệnh và giá trị cốt lõi của chúng tôi.' }}</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl font-bold text-slate-900">Sứ mệnh của chúng tôi</h2>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Thư viện số được thành lập với mục tiêu mang tri thức đến gần hơn với mọi người. Chúng tôi không chỉ cung cấp sách, mà còn là nơi nuôi dưỡng những ý tưởng sáng tạo và hỗ trợ nghiên cứu học thuật đỉnh cao.
                    </p>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">Kho tài liệu đồ sộ</h4>
                                <p class="text-slate-500">Hàng ngàn đầu sách thuộc mọi lĩnh vực chuyên môn.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">Công nghệ hiện đại</h4>
                                <p class="text-slate-500">Hệ thống tra cứu thông minh và bảo mật tuyệt đối.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://img.freepik.com/free-vector/learning-concept-illustration_114360-6186.jpg" alt="About Us" class="rounded-3xl shadow-xl">
                    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-lg border border-slate-100 max-w-xs hidden md:block">
                        <p class="italic text-slate-600">"Tri thức là chìa khóa mở ra cánh cửa tương lai."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content from Page Builder -->
    @if($node->activeItems()->count() > 0)
        @foreach($node->activeItems()->ordered()->get() as $item)
            @include('site.items.' . $item->item_type, ['item' => $item])
        @endforeach
    @endif
</div>
@endsection
