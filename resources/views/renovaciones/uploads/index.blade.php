@extends('layouts.main')
@section('title','Renovaciones|Seguimientos')
@section('content')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Renovaciones</h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-user"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Seguimientos </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>               
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
                    Descargue el archivo de ejemplo, y asegurese de rellenar cada celda seg&uacute;n las indicaciones <small>(Ver los comentarios en el encabezado del archivo)</small>. <a class="kt-link kt-font-bold" href="{{ Storage::disk('public')->url('formato_seguimientos_renovaciones.xlsx') }}">Presione Aqu&iacute;</a>
                    @if(Auth::user()->hasPermission('renovaciones.cargador.payments'))
                    <br>
                    Descargue el archivo de ejemplo para <span style="color:red; font-weight: bold;">Cargar los Pagos </span><a class="kt-link kt-font-bold" href="{{ Storage::disk('public')->url('formato_pagos_renovaciones.xlsx') }}">Presione Aqu&iacute;</a>
                    @endif
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
                   Carga de Seguimientos <small>Utilice el formato suministrado para realizar el registro de los seguimientos de las Ventas.</small>                
                </h3>
            </div>                    
        </div>
        <div class="kt-portlet__body">
            @include('message.massive')
            <form action="{{ route('renovaciones.import.storage') }}" method="POST" id="cargadorArchivo" enctype="multipart/form-data">
            @csrf
                <div class="row">
                @if(Auth::user()->hasPermission('renovaciones.cargador.payments'))
                <div class="col-lg-4 col-md-12">
                    <label>Tipo de Carga</label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                            <select name="tipo" id="tipo" required class="select form-control">
                                <option value="">[SELECCIONE]</option>
                                    @foreach ($tipo as $k => $value)
                                        <option value="{{ $k }}">{{ $value }}</option>
                                    @endforeach                                
                                </select>
                            </div>                       
                        </div>
                    </div>
                </div> 
                @endif
                <div class="col-lg-6 col-md-12">
                    <label>Seleccione el Archivo de Carga</label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">                            
                                <input type="file" class="custom-file-input" id="archivo" name="archivo">
                                <label class="custom-file-label" for="customFile">Bucar Achivo en...</label>
                            </div>
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="submit">Cargar</button>
                            </span>
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
<link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/select2/select2-bootstrap4-theme/select2-bootstrap4.css')}}">
@endpush
@push('scripts')
<script src="{{ asset('js/renovaciones/resources.js') }}"  type="text/javascript"></script> 
@endpush

