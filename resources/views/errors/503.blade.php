@extends('layouts.errors')
@section('title','503 Site En Mantenimiento')
@section('content')
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v5" style="background-image: url({{ URL::asset('image/503.jpg') }});">
        <div class="kt-error_container">            
            <span class="kt-error_title">                
                <h1>Oops!</h1>
            </span>
            <p class="kt-error_subtitle">
                Temporalmente fuera de servicio<br> Ya regresamos...<br>
                <img src="{{ asset('image/discovery_logo1.png') }}" alt="Discovery" width="350px">
            </p>
        </div>
    </div>
</div>

<!-- end:: Page -->
@endsection
@push('styles')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/pages/error/error-5.css') }}">
@endpush
@push('scripts')

@endpush