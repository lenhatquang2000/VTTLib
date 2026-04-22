@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện VTTU')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'blue',
        'badgeText'    => 'Giới thiệu chung',
        'badgeIcon'    => 'fas fa-circle-info',
        'sectionLabel' => 'Giới thiệu',
    ])
@endsection
