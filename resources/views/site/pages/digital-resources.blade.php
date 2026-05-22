@extends('layouts.site')

@section('content')
    @include('site.partials.inner-page', [
        'node' => $node,
        'sectionLabel' => __('Tài nguyên')
    ])
@endsection
