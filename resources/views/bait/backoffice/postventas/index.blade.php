@extends('layouts.main')
@section('title','BAIT|Backoffice')
@section('content')
<!-- begin:: Subheader -->
<div @class(['kt-subheader', 'kt-grid__item']) id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                BAIT </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fas fa-plus-circle"></i></a>               
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Backoffice </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Ingresadas Intelix - Proceso PostVentas </a>
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
                            Listado de Ventas Ingresadas En Intelix - Proceso PostVentas
                        </h3>                       
                    </div>
                    @permission('bait.unlock.seguimientos')
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button class="btn btn-brand btn-elevate btn-warning btn-icon-sm" id="unlockseguimientos" data-href="{{ route('bait.unlock.seguimientos') }}" data-toggle="tooltip" data-placement="top" title="Se Libera Todos los Seguimientos Realizados por este Panel">
                                    <i class="la la-check-circle"></i>
                                    Autorizar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endpermission
                </div>
                 <input type="hidden" id="DataVentas" value="{{ route('bait.backoffice.postventa.search') }}">
                   @csrf
                <div class="kt-portlet__body">       
                   <p>Estos datos son extraidos Integros desde la Base de Datos.</p>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-sm"></div>
                                 
                    <div class="row"> 
                        <div class="col-lg-12">
                            <table class="table text-center table-striped table-sm table-bordered compact table-hover table-checkable" id="datatable-postventas" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Registrada</th>   
                                        <th>FVC</th>                                
                                        <th>Id Contacto</th>      
                                        <th>Numero Portabilidad</th>                                 
                                        <th>Nombre y Apellido</th>
                                        <th>Ciclo de Vida</th>
                                        <th>Estatus Intelix</th>   
                                        <th>Agente</th> 
                                        <th>Supervisor</th>                                    
                                        <th>Estatus Discovery</th>         
                                        <th>Estatus Concentra</th>                            
                                        <th>Bloqueo</th>   
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>  
                        </div>                   
                    </div>  
                    <div class="modal fade" id="HistoricoModalShow">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Informacion de la Venta e Historico</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                <div class="row mb-3"> 
                                    <div class="col-lg-12">
                                        <div class="row mb-3"> 
                                            <div id="appendVenta"></div>
                                        </div>
                                    </div>   
                                    <div class="col-lg-12">
                                        <h5 class="text-primary mt-3">Historico de la Venta</h5>            
                                        <hr>
                                    </div>                              
                                    <div class="col-lg-12">                                      
                                        <table class="table text-center table-striped table-sm table-bordered compact table-hover table-checkable" id="historico-venta-table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Registrada</th>   
                                                    <th>sns</th>
                                                    <th>Gestionado</th>
                                                    <th>Estatus Concentra</th>                                
                                                    <th>Estatus Intelix</th>
                                                    <th>Estatus Final</th>
                                                    <th>Observaciones</th>
                                                </tr>
                                            </thead>
                                        </table>  
                                    </div>                   
                                </div>                                
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                        
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{ asset("js/bait/resources.js") }}"  type="text/javascript"></script> 
@endpush