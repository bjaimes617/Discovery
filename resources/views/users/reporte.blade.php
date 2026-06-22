@extends('layouts.main')
@section('title','Reporte de Usuarios')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Configuraci&oacute;n </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-print"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Usuarios </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Reporte </a>
            </div>
        </div>
    </div>
</div>
<div class="kt-container  kt-grid__item kt-grid__item--fluid">

    <!--begin::Portlet-->
    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Reportes de Usuarios <small>Establezca los parametros para generar el reporte.</small>
                        </h3>
                    </div>
                </div>                
                <form action="{{ route('user.generate') }}" method="POST" id="report-ventas">
                @csrf
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Estatus:</label>
                            <select name="estatus" id="estatus" class="select form-control">
                                <option value="" selected>Todos</option>
                                <option value="1">Activo</option>
                                <option value="2">Baja</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Campaña:</label>
                            <select name="campana" id="campana" class="select form-control">
                                <option value="" selected>Todos</option>
                                @foreach($campana as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-1 col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-brand form-control" id="search-ventas">Generar</button>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
                    <!--begin: Datatable -->
                </div>
                </form>
                <!--end::Portlet-->
            </div>    
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('js/config/reporte.js') }}"></script>
@endpush
