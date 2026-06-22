@extends('layouts.login')
@section('title', 'Recuperar Password')
@section('content')
<!-- begin:: Page -->
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url({{ URL::asset('image/4.jpeg') }}); background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container" style="background: #FFF;padding: 20px; opacity: 0.9;border-radius:  15px 50px">

                    <div class="kt-login__signin">
                        <div class="kt-login__logo" style="padding-top: 10px;">
                            <a href="#">
                                <img src="{{ asset('image/logo_directa.png') }}" alt="Discovery" width="150px">
                                <img src="{{ asset('image/discovery_logo1.png') }}" alt="Discovery" width="350px">
                            </a>
                        </div>
                        <div class="kt-login__head" style="margin-top: -40px;">
                            <h3 class="kt-login__title">Restablecer Contrase&ntilde;a</h3>                            
                        </div>
                        <form class="kt-form" id='kt_password_form' method="post">
                        <input type="hidden" id="url" value="{{ route('password.update') }}"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokencsfr">
                        <input type="hidden" name="token" id="token" value="{{ $token }}">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autocomplete="off">
                        </div>
                        <div class="input-group"> 
                            <input type="password" class="form-control" name="password" id="password" placeholder="Nueva Contrase&ntilde;a"> 
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Verifique la Contrase&ntilde;a"> 
                        </div>
                        <div class="kt-login__actions">
                            <button id="kt_password_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Restablecer</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@push('styles')
<!--Custom Style-->

@endpush
@push('scripts')
<!-- Custom Script -->
<script src="{{ asset('js/reset-password.js') }}"></script>
@endpush