@extends('layouts.main')
@section('title','Usuarios')
@section('content')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Usuarios </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-user"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Configuraci&oacute;n </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Usuarios </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Listar </a>
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
                    <i class="kt-font-brand flaticon2-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Usuarios del Sistema
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

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Nombre y Apellido</th>
                        <th>Usuario</th>
                        <th>Email</th>                         
                        <th>Ficha de Personal</th> 
                        <th>Autenticaci&oacute;n 2FA</th>
                        <th>Campa&ntilde;a</th> 
                        <th>Rol</th>
                        <th>Creado El</th> 
                        <th>Estatus</th> 
                        <th>Acciones</th>
                    </tr>
                </thead>

            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
<!-- end:: Content -->

<!-- Delete -->
@include('partials.general.modal-delete')

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
@push('scripts')
<script src="{{asset("assets/plugins/custom/datatables.net/js/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-bs4/js/dataTables.bootstrap4.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/js/global/integration/plugins/datatables.init.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-autofill/js/dataTables.autoFill.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/jszip/dist/jszip.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/pdfmake/build/pdfmake.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/pdfmake/build/vfs_fonts.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/dataTables.buttons.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.colVis.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.flash.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.html5.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.print.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-colreorder/js/dataTables.colReorder.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-keytable/js/dataTables.keyTable.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-responsive/js/dataTables.responsive.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-rowgroup/js/dataTables.rowGroup.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-rowreorder/js/dataTables.rowReorder.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-scroller/js/dataTables.scroller.min.js")}} " type="text/javascript"></script>
<script src="{{asset("assets/plugins/custom/datatables.net-select/js/dataTables.select.min.js")}} " type="text/javascript"></script>
@include('partials.js.allusers')
<script src="{{asset("js/general/delete.js")}} " type="text/javascript"></script>
@endpush