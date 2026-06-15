@extends('layouts.site')

@section('title', $node->meta_title ?: $node->display_name . ' - Thư viện số')

@section('content')
    @include('site.partials.inner-page', [
        'node'         => $node,
        'accent'       => 'red',
        'badgeText'    => 'Hướng dẫn',
        'badgeIcon'    => 'fas fa-book-open-reader',
        'sectionLabel' => 'Hướng dẫn',
    ])
@endsection
