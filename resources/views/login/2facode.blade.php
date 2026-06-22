@extends('layouts.password')
@section('title','2FA Autenticacion')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Alerta de Seguridad </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shield"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Autenticaci&oacute;n 2FA </a>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Autenticaci&oacute;n 2FA <small>@if(Session::has('urlQR'))Para generar su token es necesario escanear el c&oacute;digo QR desde la app de su tel&eacute;fono m&oacute;vil.@else Los token se regeneran en la aplicaci&oacute;n cada cierto tiempo ingresalo mientras se encuentre activo.@endif</small></h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('auth.fa2',$user) }}" aria-label="login">
                    @csrf
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @if(Session::has('error2fa'))
                                <div class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40" role="alert">
                                    <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
                                    <div class="alert-text">{{Session::get('error2fa')}}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>    
                                </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        &nbsp;
                                    </div>    
                                    <div class="col-lg-2">
                                        @if(Session::has('urlQR'))
                                        <img id="imgQR" src="{{Session::get('urlQR')}}"/>
                                        @endif    
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="code_verification" class="col-form-label">
                                                C&oacute;digo de Verificaci&oacute;n
                                            </label>
                                            <input id="code_verification" type="text" class="form-control" name="code_verification" required maxlength="6" autofocus>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    #code_verification {
        padding-left: 28px;
        letter-spacing: 10px;
        font-size: 18px;
        width: 170px;
    }
</style>

@endpush
@push('scripts')


@endpush

