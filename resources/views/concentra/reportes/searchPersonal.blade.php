@extends('layouts.main')
@section('title','Reporte de  Personal')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Concentra </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-print"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Reportes </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Personal </a>
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
                            Reportes de Personal <small>Establezca los parametros para generar el reporte.</small>
                        </h3>
                    </div>
                </div>                
                {!! Form::open(['route' => 'concentra.reportes.generatepersonal','method' => 'post','id' => 'report-ventas']) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Estatus:</label>
                            {{ Form::select('estatus', ['1' => 'Activo','2' => 'Baja'], null, ['id' => 'estatus','class' => 'select form-control','placeholder' => 'Todos']) }}
                        </div>
                        <div class="form-group col-lg-1 col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-brand form-control" id="search-ventas">Generar</button>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
                    <!--begin: Datatable -->
                </div>
                {!! Form::close() !!}
                <!--end::Portlet-->
            </div>    
        </div>
    </div>
@endsection
@push('scripts')
{!! Html::script("js/imperia/personal.js") !!}
@endpush
