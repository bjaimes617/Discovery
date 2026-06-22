@extends('layouts.main')
@section('title','Reporte de  Ventas')
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
                    Ventas </a>
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
                            Reportes de Ventas <small>Establezca los parametros para generar el reporte.</small>
                        </h3>
                    </div>
                </div>                
                {!! Form::open(['route' => 'concentra.reportes.generateventas','method' => 'post','id' => 'report-ventas']) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Fecha:</label>
                            <div class='input-group' id='fechar'>
                                <input type='text' class="form-control" name="fecha" id="fecha" readonly value="{{ date('d/m/Y')."-".date('d/m/Y')}}"/>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group col-lg-3 col-md-3">
                            <label>Supervisor:</label>
                            {{ Form::select('supervisor', $supervisores, null, ['id' => 'supervisor','class' => 'select form-control','placeholder' => 'Todos']) }}
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Tipificaci&oacute;n 1:</label>
                            {{ Form::select('tipificacion1', $tipificacion1, null, ['id' => 'tipificacion1','class' => 'select form-control','data-href'=>route('concentra.selectipificacion2'),'placeholder' => 'Todos']) }}
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Tipificaci&oacute;n 2:</label>
                            {{ Form::select('tipificacion2', [], null, ['id' => 'tipificacion2','class' => 'select form-control','placeholder' => 'Todos']) }}
                        </div>
                        <div class="form-group col-lg-1 col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-brand form-control">Generar</button>
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
    @push('styles')
    <!--Custom Style-->

    @endpush
    @push('scripts')
    {!! Html::script("js/concentra/ventas.js") !!}
    @endpush
