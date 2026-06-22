@extends('layouts.main')
@section('title','Ver Ventas Registradas')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Concentra </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fa fa-cart-arrow-down"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Ventas </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Registradas </a>
            </div>
        </div>
        @permission('concentra.ventas.create')
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="{{route('concentra.ventas.agregar')}}" class="btn btn-danger kt-subheader__btn-options">
                    Agregar Venta
                </a>                
            </div>
        </div>
        @endpermission
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
                            Buscador de Ventas
                        </h3>
                    </div>
                </div>                
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <input type="hidden" name="url" value="{{ route("concentra.ventas.show") }}" id="url">
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
                            <button type="button" class="btn btn-brand form-control" id="search-ventas">Buscar</button>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
                    <!--begin: Datatable -->
                    <div id="ventas-result" style="display: none;">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable-ventas">
                            <thead>
                                <tr>
                                    <th>DN</th>
                                    <th>Cliente</th>                         
                                    <th>CURP</th> 
                                    <th>NIP</th> 
                                    <th>Agente</th> 
                                    <th>Supervisor</th>
                                    <th>Fecha</th>
                                    <th>Tipificaci&oacute;n 1</th>
                                    <th>Tipificaci&oacute;n 2</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                        </table>
                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>    
        </div>
    </div>
@endsection
@push('styles')
{!!Html::style('assets/plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css')!!}
{!!Html::style('assets/plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css')!!}

@endpush
@push('scripts')
{!! Html::script("assets/plugins/custom/datatables.net/js/jquery.dataTables.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-bs4/js/dataTables.bootstrap4.js") !!}
{!! Html::script("assets/plugins/custom/js/global/integration/plugins/datatables.init.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-autofill/js/dataTables.autoFill.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js") !!}
{!! Html::script("assets/plugins/custom/jszip/dist/jszip.min.js") !!}
{!! Html::script("assets/plugins/custom/pdfmake/build/pdfmake.min.js") !!}
{!! Html::script("assets/plugins/custom/pdfmake/build/vfs_fonts.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons/js/dataTables.buttons.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons/js/buttons.colVis.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons/js/buttons.flash.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons/js/buttons.html5.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-buttons/js/buttons.print.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-colreorder/js/dataTables.colReorder.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-keytable/js/dataTables.keyTable.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-responsive/js/dataTables.responsive.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-rowgroup/js/dataTables.rowGroup.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-rowreorder/js/dataTables.rowReorder.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-scroller/js/dataTables.scroller.min.js") !!}
{!! Html::script("assets/plugins/custom/datatables.net-select/js/dataTables.select.min.js") !!}
{!! Html::script("js/concentra/ventas.js") !!}
@endpush
