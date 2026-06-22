@extends('layouts.errors')
@section('title','404 Not Found')
@section('content')
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v3" style="background-image: url({{ URL::asset('image/404.jpg') }});">
        <div class="kt-error_container">
            <span class="kt-error_number">
                <h1>404</h1>
            </span>
            <p class="kt-error_title kt-font-light">
                &iquest;Como llegaste aqu&iacute;&quest;
            </p>
            <p class="kt-error_subtitle">
                Lo sentimos no podemos encontrar lo que estas buscando
            </p>
            <p class="kt-error_description">
                Probablemente ingresaste una URL inv&aacute;lida,<br>
                o la p&aacute;gina que buscas ya no existe.
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