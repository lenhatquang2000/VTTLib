@extends('layouts.site')

@section('title', $node->display_name)

@section('content')
<div class="w-full bg-white">
    <!-- Clean Minimalist Header -->
    <div class="py-12 border-b border-slate-100">
        <div class="container mx-auto px-6">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $node->display_name }}</h1>
            @if($node->description)
                <p class="text-slate-500 mt-2 font-medium">{{ $node->description }}</p>
            @endif
        </div>
    </div>

    <!-- Full Width Content Area -->
    <div class="w-full py-12">
        @if($node->activeItems()->count() > 0)
            @foreach($node->activeItems()->ordered()->get() as $item)
                @include('site.items.' . $item->item_type, ['item' => $item])
            @endforeach
        @else
            <div class="container mx-auto px-6 py-20 text-center text-slate-400">
                <i class="fas fa-layer-group text-6xl mb-6 opacity-20"></i>
                <p>Nội dung đang được cập nhật...</p>
            </div>
        @endif
    </div>
</div>
@endsection
