@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'emerald',
        'badgeText'    => 'Thời gian phục vụ',
        'badgeIcon'    => 'fas fa-clock',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
