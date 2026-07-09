@extends('layouts.main')
@section('title','Renovaciones|Exportar')
@section('content')
<!-- begin:: Subheader -->
<div @class(['kt-subheader', 'kt-grid__item']) id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Renovaciones </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fas fa-plus-circle"></i></a>               
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Reportes </a>                
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    <!--begin::Portlet-->
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                           Generacion de Reportes
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">        
                     <form method="POST" action="{{ route('renovaciones.export.download') }}" id="reportesDescargar" class="kt-form kt-form--label-right">
                        @csrf      
                        <div class="row"> 
                            <div class="col-lg-6 col-md-12">
                                <label>Fecha:</label>
                                <div class="form-group ">
                                    <div class='input-group' id='fechar'>
                                        <input type='text' class="form-control" autocomplete="off" required name="fecha" id="fecha" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                                        </div>
                                    </div> 
                                </div>                                     
                            </div>  
                            <div class="col-lg-6 col-md-12">
                                <label>Tipo de Reporte:</label>
                                <div class="form-group">
                                    <div class='input-group'>                                       
                                        <select name="reporte" id="reporte" required class="select form-control">
                                            <option value="">[SELECCIONE]</option>
                                           @foreach ($reportes as $key => $value)
                                               <option value="{{ $key }}">{{ $value }}</option>
                                           @endforeach
                                        </select>
                                    </div> 
                                </div>
                            </div>  
                             <div class="col-lg-12 col-md-12">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-brand form-control" id="search-ventas">Descargar</button>
                            </div> 
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
 <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/select2/select2-bootstrap4-theme/select2-bootstrap4.css')}}">

@endpush
<!-- end:: Content -->
@push('scripts')
<script src="{{ asset("js/renovaciones/resources.js") }}"  type="text/javascript"></script> 
@endpush