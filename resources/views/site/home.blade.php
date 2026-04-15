@extends('layouts.site')

@section('title', 'Trang Chủ - Thư viện')

@section('content')
<div class="min-h-screen">
    <!-- Dynamic Hero Section from SiteNode -->
    @if($homeNode && $homeNode->content)
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="prose max-w-none text-center">
                {!! $homeNode->content !!}
            </div>
        </div>
    </section>
    @endif

    <!-- Dynamic Features Section from SiteNodes -->
    @if($menuItems && $menuItems->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Tính năng nổi bật</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($menuItems->take(3) as $item)
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="{{ $item->icon ?? 'fas fa-star' }} text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $item->display_name }}</h3>
                    <p class="text-gray-600">{{ $item->description ?? 'Tính năng nổi bật của hệ thống' }}</p>
                    @if($item->url || $item->route_name)
                    <a href="{{ $item->url ?? route($item->route_name) }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Xem chi tiết →
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
