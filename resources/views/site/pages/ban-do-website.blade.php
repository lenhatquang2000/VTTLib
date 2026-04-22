@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'violet',
        'badgeText'    => 'Bản đồ Website',
        'badgeIcon'    => 'fas fa-sitemap',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
