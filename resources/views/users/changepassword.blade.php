@extends('layouts.password')
@section('title','Cambio de Contraseña')
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
                    Cambio de Contrase&ntilde;a </a>
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
                        <h3 class="kt-portlet__head-title">Cambio de Contrase&ntilde;a <small>utilice una combinaci&oacute;n f&aacute;cil de recordar.</small></h3>
                    </div>
                </div>
                <form action="{{ route('user.password.update') }}" method="POST" id="kt_form_update_password" class="kt-form kt-form--label-right" data-error="{{ trans('admin.msgrequiredfields') }}">
                @csrf
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40" role="alert">
                                    <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
                                    <div class="alert-text">@if(Session::has('password')){{Session::get('password')}}@endif, Por politicas de seguridad es necesario que ingrese una nueva contrase&ntilde;a, la misma debe contener una letra mayuscula, un n&uacute;mero y como m&iacute;nimo 12 caracteres de longitud.</div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h3 class="kt-section__title kt-section__title-sm">Cambie su Contrase&ntilde;a:</h3>
                                    </div>
                                </div>
                                <!--<div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Contrase&ntilde;a Actual</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control" name="currentpassword" id="currentpassword" placeholder="" data-check='{{route("user.checkcurrentpassword")}}'>                                      
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Nueva Contrase&ntilde;a</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="" data-check='{{route("user.checknewpassword")}}'> 
                                    </div>
                                </div>
                                <div class="form-group form-group-last row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Verifique la Contrase&ntilde;a</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder=""> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-3 col-xl-3">
                                </div>
                                <div class="col-lg-9 col-xl-9">
                                    <button type="submit" class="btn btn-brand btn-bold">Guardar</button>
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
<!--Custom Style-->

@endpush
@push('scripts')
<script src="{{ asset('js/config/profile.js') }}"></script>

@endpush

