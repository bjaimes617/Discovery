@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Dashboard BAIT</h3>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">Inicio</a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">Dashboard</a>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container kt-grid__item kt-grid__item--fluid">
    @csrf
    <input type="hidden" name="url_data" id="url_data" value="{{ route('dashboard.bait.data') }}">
    <input type="hidden" name="url_sinventas" id="url_sinventas" value="{{ route('dashboard.bait.sin-ventas') }}">
    <!--begin::Dashboard Row 1 (Stats)-->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h5 class="kt-portlet__head-title">
                            Panel de Control                             
                        </h5>                       
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <span class="pr-2"> Refresh: </span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="la la-clock-o"></i></span>
                                </div>
                                <select name="auto" id="auto" class="form-control">
                                    <option value="0">No Refresh</option>                                    
                                    <option value="30">30 Segundos</option>
                                    <option value="60">1 Minuto</option>
                                    <option value="180">3 Minutos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit ml-4 mr-4">
                    <label>Fecha:</label>
                    <div class="form-group ">
                        <div class='input-group' id='fechar'>
                            <input type='text' class="form-control" autocomplete="off" onchange="UpdateDisplayAll();" required name="fecha" id="fecha" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div>                                     
        </div>  
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Leads</h4>
                                <span class="kt-widget24__desc">Leads Asignados</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" id="stat-lead_asignados" style="font-size: 1.5rem; font-weight: bold; float: right;">{{ $row["lead_asignados"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>        
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Meta Ventas</h4>
                                <span class="kt-widget24__desc">Meta Diaria</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-success" id="stat-meta_venta" style="font-size: 1.5rem; font-weight: bold; float: right;">{{ $row["meta_venta"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Discovery</h4>
                                <span class="kt-widget24__desc">Ventas Cargadas</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-purple" id="stat-ventas_discovery" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                {{ $row["ventas_discovery"] }}</span>
                        </div>
                    </div>
                </div>
            </div>            
            <!--end::Portlet-->
        </div>
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Respond.io</h4>
                                <span class="kt-widget24__desc">Ventas Cargadas</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-info" id="stat-ventas_respondio" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                {{ $row["ventas_respondio"] }}</span>
                        </div>
                    </div>
                </div>
            </div>            
            <!--end::Portlet-->
        </div>
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit ">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Conversion</h4>
                                <span class="kt-widget24__desc">Venta C. / Leads</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-warning" id="stat-conversion_global" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                {{ $row["conversion_global"] }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>        
        <div class="col-lg-2 col-xl-2 order-lg-1 order-xl-1">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #8C8F93 !important;">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget24" style="padding: 10px !important;">
                        <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Ingresadas</h4>
                                <span class="kt-widget24__desc">Cargadas Intelix</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-success" id="stat-ingresadas_intelix" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                {{ $row["ingresadas_intelix"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>        
    </div>
    <!--end::Dashboard Row 1-->

    <!--begin::Dashboard Row 2-->
    <div class="row"> 
        <div class="col-lg-12 col-xl-12 order-lg-2 order-xl-2">
            <div class="row">                
                <div class="col-lg-4 col-xl-4 order-lg-1 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #BE3A8D !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">FVC 24H</h4>
                                            <span class="kt-widget24__desc">Cargadas</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-cyan" id="stat-fvc24" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["fvc24"] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #BE3A8D !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">FVC 48H</h4>
                                            <span class="kt-widget24__desc">Cargadas</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-warning" id="stat-fvc48" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["fvc48"] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div>  
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #BE3A8D !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">Por Ingresar</h4>
                                            <span class="kt-widget24__desc">No Cargadas Intelix</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-danger" id="stat-no_cargado" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["no_cargado"] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div>       
                <div class="col-lg-4 col-xl-4 order-lg-1 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #1B3155 !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">% Ventas Por Conv. N.</h4>
                                            <span class="kt-widget24__desc">Venta C. /Conv. N.</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-success" id="stat-conversacionXventa" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["conversacionXventa"] }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div>     
                <div class="col-lg-4 col-xl-4 order-lg-1 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #1B3155 !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">% Conv. N. a Lead A.</h4>
                                            <span class="kt-widget24__desc">Lead A. / Conv. N.</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-warning" id="stat-contactoAlead" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["contacto_a_lead"] }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div> 
                <div class="col-lg-4 col-xl-4 order-lg-1 order-xl-2">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid" style="border: 1px solid #1B3155; box-shadow: 5px 5px 0px #1B3155 !important;">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <div class="kt-widget24" style="padding: 10px !important;">
                                    <div class="kt-widget24__details" style="padding: 15px !important; padding-top: 20px !important;">
                                        <div class="kt-widget24__info">
                                            <h4 class="kt-widget24__title" style="margin-bottom: 5px;">% Perdida de Conv. N</h4>
                                            <span class="kt-widget24__desc">Conv. N. a Lead A. - 100%</span>
                                        </div>
                                        <span class="kt-widget24__stats kt-font-danger" id="stat-perdida_contacto" style="font-size: 1.5rem; font-weight: bold; float: right;">
                                            {{ $row["perdida_contacto"] }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--end::Portlet-->
                </div>           
            </div>
        </div> 
        <div class="col-lg-12 col-xl-12 order-lg-2 order-xl-2">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Estadísticas de Ventas por Operador</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button href="#" onclick="asignadossinventas()" class="btn btn-primary btn-elevate btn-icon-sm">
                                    <i class="flaticon-users"></i>
                                    Asignados Sin Ventas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <!-- Placeholder para gráfico -->
                    <div class="row">
                      <div class="col-lg-12 col-xl-12">
                          <table  id="metricasusers" class="table text-center table-striped- table-sm table-bordered compact table-checkable">
                            <thead>
                                <tr>
                                    <th>Supervisor</th>
                                    <th>Agente</th>
                                    <th>Lead Asignados</th>
                                    <th>Meta Ventas</th>
                                    <th>Total Ventas Discovery</th>
                                    <th>Total Ventas Respond.io</th>
                                    <th>Conversion</th>                                     
                                </tr>
                            </thead>
                            <tbody id="table-usuarios-body">
                                @php $sumaleads = 0; $sumaventas = 0;   @endphp  
                                    @foreach ($row['usuarios'] as $usuario)    
                                @php
                                    $sumaleads = $sumaleads + $usuario['leads']; 
                                    $sumaventas =  $sumaventas+ $usuario['cargadas'];
                                @endphp                         
                                    <tr>
                                        <td>{{ $usuario['supervisor'] }}</td>
                                        <td>{!! $usuario['nombre'] !!}</td>
                                        <td>{{ $usuario['leads'] }}</td>
                                        <td>{{ $usuario['meta']}}</td>
                                        <td>
                                            @if($usuario['cargadas']  < $usuario['venta_respondido']) 
                                                <span style="color: red; font-weight: bold;"><i class="fas fa-times-circle"></i><span> 
                                            @endif
                                            {{ $usuario['cargadas'] }}
                                        </td>
                                        <td> 
                                            {{ $usuario['venta_respondido'] }}
                                        </td>
                                        <td>{!! $usuario['conversion'] !!}</td>                                        
                                    </tr>
                                @endforeach                            
                            </tbody>   
                            <tfoot id="table-usuarios-foot">
                                <tr>
                                    <th></th>
                                    <th><b>Totales:</b></th>                                   
                                    <th>{{ $sumaleads }}</th>
                                    <th>-</th>
                                    <th>{{ $sumaventas }}</th>
                                    <th>-</th>                                     
                                    <th>-</th>                                     
                                </tr>
                            </tfoot>                         
                        </table>
                      </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="modal fade" id="asignadossinventas" tabindex="-1" role="dialog" aria-labelledby="asignadossinventas_modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignadossinventas">Asignados Sin Ventas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <label>Fecha:</label>
                            <div class="form-group ">
                                <div class='input-group' id='fechar'>
                                    <input type='text' class="form-control" autocomplete="off" required name="fechaasignados" id="fechaasignados" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                                    </div>
                                </div> 
                            </div> 
                    </div>    
                    <div class="col-lg-12 col-md-12">    
                     <table  id="table-asignados" class="table text-center table-striped- table-sm table-bordered compact table-checkable" width="100%">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>ID Contacto</th>
                                    <th>Ciclo de Vida</th>
                                    <th>numero_contacto</th>
                                    <th>Vendedor</th>
                                    <th>Supervisor</th>                                    
                                </tr>
                            </thead>  
                            <tbody id="table-asignados-body"></tbody>                                                  
                        </table>         
                        </div>    
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>            
            </div>
        </div>
    </div>
</div> 
</div>
<!-- end:: Content -->
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
<script src="{{ asset("js/bait/dashboard.js") }}"  type="text/javascript"></script> 

@endpush