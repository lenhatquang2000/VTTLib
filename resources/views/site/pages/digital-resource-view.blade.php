@extends('layouts.site')

@section('content')
@include('site.partials.inner-page', [
    'node' => $node,
    'sectionLabel' => __('Tài nguyên'),
    'content' => view('site.pages.partials.digital-resource-view-content', compact('resource'))
])
@endsection
