@extends('layouts.login')
@section('title', 'Login')
@section('content')
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
            style="background-image: url({{ URL::asset('image/4.jpeg') }}); background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container" style="background: #FFF;padding: 20px; opacity: 0.9;border-radius:  15px 50px">

                    <div class="kt-login__signin">
                        <div class="kt-login__logo" style="padding-top: 10px;">
                            <a href="#">
                                <img src="{{asset('image/logo_directa.png')}}" alt="Logo Directa" width="150px">
                                <img src="{{asset('image/discovery_logo1.png') }}" alt="Logo Discovery" width="350px">
                            </a>
                        </div>
                        <div class="kt-login__head" style="margin-top: -40px;">
                            <h3 class="kt-login__title">Inicio de Sesi&oacute;n</h3>                            
                        </div>
                        <form class="kt-form" id='kt_login_form' method="post">                            
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                            <input type="hidden" id="url" value="{{route('signin')}}"/>    
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Usuario" name="usuario" autocomplete="off">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="Contraseña" name="password">
                            </div>
                            <div class="row kt-login__extra">
                                <!--<div class="col">
                                    <label class="kt-checkbox">
                                        <input type="checkbox" name="remember"> Recordarme
                                        <span></span>
                                    </label>
                                </div>-->
                                <div class="col kt-align-left">
                                    <a href="javascript:;" id="kt_login_forgot" class="kt-login__link">&iquest;Olvid&oacute; su Contrase&ntilde;a&quest;</a>
                                </div>
                            </div>
                            <div class="kt-login__actions">
                                <button id="kt_login_signin_submit" disabled="disabled" class="btn btn-brand btn-elevate kt-login__btn-primary">Despegar</button>
                            </div>
                        </form>
                    </div>
                    <!--<div class="kt-login__signup">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Sign Up</h3>
                            <div class="kt-login__desc">Hasta que sea asombroso</div>
                        </div>
                        <form class="kt-form" action="">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Fullname" name="fullname">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="Password" name="password">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="Confirm Password" name="rpassword">
                            </div>
                            <div class="row kt-login__extra">
                                <div class="col kt-align-left">
                                    <label class="kt-checkbox">
                                        <input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                                        <span></span>
                                    </label>
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="kt-login__actions">
                                <button id="kt_login_signup_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign Up</button>&nbsp;&nbsp;
                                <button id="kt_login_signup_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>-->
                    <div class="kt-login__forgot">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">&iquest;Olvidaste tu Contrase&ntilde;a&quest;</h3>
                            <div class="kt-login__desc">Ingrese su email para restablecer su contrase&ntilde;a:</div>
                        </div>
                        <form action="{{ route('password.email') }}" method="POST" class="kt-form" novalidate="novalidate">                    
                        @csrf
                        <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_forgot_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Enviar</button>&nbsp;&nbsp;
                                <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancelar</button>
                            </div>                        
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- end:: Page -->
@endsection
@push('styles')
<!--Custom Style-->


@endpush
@push('scripts')
<!-- Custom Script -->
<script src="{{asset('js/login.js')}}" type="text/javascript"></script>
@endpush