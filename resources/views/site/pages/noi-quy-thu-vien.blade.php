@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'amber',
        'badgeText'    => 'Nội quy Thư viện',
        'badgeIcon'    => 'fas fa-scale-balanced',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
