@extends('layouts.main')
@section('title','Permisos')
@section('content')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Permisos </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-protected"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Configuraci&oacute;n </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Privilegios </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Permisos </a>
            </div>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-protected"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Permisos del Sistema
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#addpermission" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Nuevo Permiso
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Accion</th>
                        <th>Descripci&oacute;n</th>
                        <th>Creado En</th>  
                        <th>Acciones</th>
                    </tr>
                </thead>

            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
<!-- end:: Content -->
<!--begin::Modal Add Role-->
<div class="modal fade" id="addpermission" tabindex="-1" role="dialog" aria-labelledby="addgroup_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('permisos.store') }}" method="POST" id="kt_form_permission" class="kt-form" data-error="Los campos resaltados son obligatorios">
                @csrf
                <div class="form-group">
                    <label for="role-name" class="form-control-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="slug" class="form-control-label">Accion</label>
                    <input type="text" class="form-control" id="slug" name="slug">
                </div>
                <div class="form-group">
                    <label for="description" class="form-control-label">Descripci&oacute;n</label>
                    <input type="text" class="form-control" id="description" name="description">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savepermission">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Add Group-->


<!--begin::Modal Edit Group-->
<div class="modal fade" id="editpermission" tabindex="-1" role="dialog" aria-labelledby="editpermission_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('permisos.update') }}" method="POST" id="kt_form_update_permission" class="kt-form" data-error="{{ trans('Todos los campos son obligatorios') }}">
                @csrf
                <input type="hidden" name="id"  id="editid">

                <div class="form-group">
                    <label for="role-name" class="form-control-label">Nombre</label>
                    <input type="text" class="form-control" id="editname" name="name">
                </div>
                <div class="form-group">
                    <label for="slug" class="form-control-label">Accion</label>
                    <input type="text" class="form-control" id="editslug" name="slug">
                </div>
                <div class="form-group">
                    <label for="description" class="form-control-label">Descripci&oacute;n</label>
                    <input type="text" class="form-control" id="editdescription" name="description">
                </div>                  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updatepermission">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Add Group-->
<!-- Delete -->
@include('partials.general.modal-delete')

@endsection
@push('styles')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">

@endpush
@push('scripts')
<script src="{{ asset('assets/plugins/custom/datatables.net/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/js/global/integration/plugins/datatables.init.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-autofill/js/dataTables.autoFill.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons/js/buttons.colVis.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons/js/buttons.flash.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons/js/buttons.html5.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-buttons/js/buttons.print.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-colreorder/js/dataTables.colReorder.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-rowgroup/js/dataTables.rowGroup.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-rowreorder/js/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables.net-select/js/dataTables.select.min.js') }}"></script>
@include('partials.js.allpermissions')
<script src="{{ asset('js/config/permission.js') }}"></script>
<script src="{{ asset('js/general/delete.js') }}"></script>
@endpush