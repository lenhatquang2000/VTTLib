@extends('layouts.site')

@section('title', $node->meta_title ?: 'Giới Thiệu - Thư viện số')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'blue',
        'badgeText'    => 'Giới thiệu',
        'badgeIcon'    => 'fas fa-info-circle',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
