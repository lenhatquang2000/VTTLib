@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'indigo',
        'badgeText'    => 'Chức năng & Nhiệm vụ',
        'badgeIcon'    => 'fas fa-bullseye',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
