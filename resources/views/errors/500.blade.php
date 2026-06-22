@extends('layouts.errors')
@section('title','500 Internal Error Server')
@section('content')
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v3" style="background-image: url({{ URL::asset('image/505.jpg') }});">
        <div class="kt-error_container">
            <span class="kt-error_number">
                <h1>500</h1>
            </span>
            <p class="kt-error_title kt-font-light">
                INTERNAL SERVER ERROR
            </p>
        </div>
    </div>
</div>

<!-- end:: Page -->
@endsection
@push('styles')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/pages/error/error-3.css') }}">
@endpush
@push('scripts')

@endpush