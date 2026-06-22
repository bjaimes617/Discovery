@extends('layouts.main')
@section('title','Claro Masivo|Listado de Ventas')
@section('content')
<!-- begin:: Subheader -->
<div @class(['kt-subheader', 'kt-grid__item']) id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Claro </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fas fa-plus-circle"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Mavivos </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Ventas </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Auditoria </a>
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
                            Auditoria de Ventas
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">        
                    <form method="POST" action="{{ route('claro.masivos.auditoriaSearch') }}" id="AuditSearch" class="kt-form kt-form--label-right">

                        <input type="hidden" name="urlSeguimiento" id="urlSeguimiento" value="{{ route('claro.masivos.seguimientosItems') }}">
                        @csrf      
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <label>Fecha:</label>
                                <div class="form-group">
                                    <div class='input-group' id='fechar'>
                                        <input type='text' class="form-control" autocomplete="off" required name="fecha" id="fecha" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                                        </div>
                                    </div> 
                                </div>                                     
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <label>Identificador:</label>
                                <div class="form-group">
                                    <div class='input-group'>                                       
                                        <input type="text" name="identificador" id="identificador" class="form-control" placeholder="Ingrese el número de identificación del Cliente">
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <label>Producto:</label>
                                <div class="form-group">
                                    <div class='input-group'>                                       
                                        <select name="productos" required id="productos" class="select form-control">
                                            <option value="todos">[TODOS]</option>
                                            @foreach ($producto as $key => $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                            </div> 
                            <div class="col-lg-3 col-md-12">
                                <label>Supervisor:</label>
                                <div class="form-group">
                                    <div class='input-group'>                                       
                                        <select name="supervisor" id="supervisor" required class="select form-control">
                                            <option value="todos">[TODOS]</option>
                                            @foreach ($supervisores as $sups)
                                            <option value="{{ $sups->id }}">{{ $sups->nombre_apellido }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <label>Estatus:</label>
                                <div class="form-group">
                                    <div class='input-group'>                                       
                                        <select name="estatus" id="estatus" class="select form-control">
                                            <option value="">[TODOS]</option>
                                            @foreach ($estatus as $esta)
                                            <option value="{{ $esta->id }}">{{ $esta->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-brand form-control" id="search-ventas">Buscar</button>
                            </div>                                                                                    
                        </div>
                    </form>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
                    <!--begin: Datatable -->                     
                    <div id="ventas-result" style="display:none;" > 
                        <table class="table text-center table-striped compact table-sm table-bordered compact table-hover table-checkable"  id="datatable-ventas">
                            <thead>
                                <tr>
                                    <th>Registrado</th> 
                                    <th>Producto</th>                 
                                    <th>Identificador</th>                                 
                                    <th>Nombre y Apellido</th>                                   
                                    <th>Plan</th>
                                    <th>Agente</th> 
                                    <th>Supervisor</th>
                                    <th>Auditada Por</th>   
                                    <th>Estatus</th>                                    
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>                     
                    </div> 
                    
                    <div class="modal fade" id="registerSegumiento">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Registrar Seguimiento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <form method="post" action="{{ route('claro.masivos.seguimientosStore') }}" id="formsegumientos">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="venta" id="venta">
                                    <div class="modal-body">
                                        <div class="row text-center">
                                            <div class="col-lg-12 col-md-12">
                                                <label>Nuevo Estatus:</label>
                                                <div class="form-group">
                                                    <div class='input-group'>                                       
                                                        <select name="newestatus" id="newestatus" required class="form-control">
                                                            <option value="">[Seleccione]</option>
                                                            @foreach ($estatussegumientos as $newestatu)
                                                            <option value="{{ $newestatu->id }}">{{ $newestatu->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12">
                                                <div class="row" id="PasteContent">
                                                    
                                                </div>                                                                              
                                            </div>                                                                     
                                            <div class="col-lg-12 col-md-12">
                                                <label>Observaciones:</label>
                                                <div class="form-group">
                                                    <div class='input-group'>                                       
                                                        <textarea name="observaciones" required id="observaciones" class="form-control"></textarea>
                                                    </div> 
                                                </div>
                                            </div> 

                                        </div>                            
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar </button>
                                    </div>
                                </form>
                            </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4" id="copyCheckDocuments" style="display: none;">
        <label class="col-form-label" for="checkdocumentos"></label>
        <div class="form-group">
            <div class='input-group'>                                       
                <span class="kt-switch kt-switch--icon">
                    <label>
                        <input type="checkbox" name="checkdocumentos" id="checkdocumentos"/>
                        <span></span>
                    </label>
                </span>
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

@endpush
<!-- end:: Content -->
@push('scripts')
<script src="{{ asset("js/claro/masivos/resources.js") }}"  type="text/javascript"></script> 
<script src="{{ asset("js/claro/masivos/ventas.js") }}"  type="text/javascript"></script> 
@endpush