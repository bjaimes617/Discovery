@extends('layouts.main')
@section('title','Actualización Masiva Usuarios')
@section('content')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Configuraci&oacute;n </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-user"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Usuarios </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Actualización Masiva </a>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->


<!-- begin:: Content -->
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col">
            <div class="alert alert-light alert-elevate fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-businesswoman kt-font-brand"></i></div>
                <div class="alert-text">
                    Descargue el archivo de ejemplo, y asegurese de rellenar cada celda seg&uacute;n las indicaciones <small>(Ver los comentarios en el encabezado del archivo)</small>. <a class="kt-link kt-font-bold" href="{{ url('/').'/example/ActualizacionMasivaEjemplo.xlsx'}}">Presione Aqu&iacute;</a>
                </div>
            </div>
        </div>
    </div>    
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Actualización Masiva <small>Solo operadores, los usuarios de mayor jerarquia asociados deben ser cargados previamente</small>
                </h3>
            </div>
            @permission('users.create')
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{route('user.create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Nuevo Usuario
                        </a>
                    </div>
                </div>
            </div>
            @endpermission            
        </div>
        <div class="kt-portlet__body">
            @include('message.massive')
            <form action="{{ route('user.update.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Seleccione el Archivo de Carga</label>
                <div></div>
                <div class="custom-file col-lg-6">
                    <input type="file" class="custom-file-input" id="archivo" name="archivo">
                    <label class="custom-file-label" for="customFile">ActualizacionMasiva.xlsx</label>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-brand">Cargar</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- end:: Content -->

<!-- Delete -->

@endsection
@push('styles')


@endpush
@push('scripts')

@endpush

