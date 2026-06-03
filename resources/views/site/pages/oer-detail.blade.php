@extends('layouts.site')

@section('content')
@include('site.partials.inner-page', [
    'node' => $node,
    'sectionLabel' => __('Tài nguyên giáo dục mở'),
    'content' => view('site.pages.partials.oer-detail-content', compact('resource'))
])
@endsection
