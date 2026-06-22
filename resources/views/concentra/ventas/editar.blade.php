@extends('layouts.main')
@section('title','Editar Venta')
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
                    Editar </a>
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
                            Editar Venta
                        </h3>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::model($venta,['route' => ['concentra.venta.update'],'method' => 'PUT','id' => 'edit-sale','class' => 'kt-form kt-form--label-right']) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <input type="hidden" name="id" value="{{$venta->id}}" id="id">
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">DN Cliente *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('dn',null,['class' => 'form-control numero required','id' => 'dn']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Titularidad *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           {{ Form::select('titularidad', ['' => '[Seleccione]','SI' => 'SI','NO' => 'NO'], $venta->titularidad, ['id' => 'titularidad','class' => 'select form-control required']) }}
                        </div>
                    </div>-->
                     <!--<div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Tipo de Linea *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           {{ Form::select('tipo_linea', ['' => '[Seleccione]','Recarga' => 'Recarga','Plan de Renta' => 'Plan de Renta'], $venta->tipo_linea, ['id' => 'tipo_linea','class' => 'select form-control required']) }}
                        </div>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Recarga *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           {{ Form::select('recarga', ['' => '[Seleccione]','50$' => '50$','100$' => '100$'], $venta->recarga, ['id' => 'recarga','class' => 'select form-control required']) }}
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Liberado *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           {{ Form::select('liberado', ['' => '[Seleccione]','SI' => 'SI','NO' => 'NO'], $venta->liberado, ['id' => 'liberado','class' => 'select form-control required']) }}
                        </div>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Nombre y Apellido Cliente *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('nombre_cliente',null,['class' => 'form-control','id' => 'nombre_cliente']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Sexo *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           {{ Form::select('sexo', ['' => '[Seleccione]','M' => 'M','F' => 'F'], $venta->sexo, ['id' => 'sexo','class' => 'select form-control required']) }}
                        </div>
                    </div>-->
                     <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Fecha de Nacimiento *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('fechaNacimiento',$venta->fecha_nacimiento,['class' => 'form-control datefree','id' => 'fechaNacimiento']) !!}
                        </div>
                    </div>
                      <!--<div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Entidad Nacimiento *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('entidad_nacimiento',null,['class' => 'form-control required','id' => 'entidad_nacimiento',]) !!}
                        </div>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">CURP *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('curp',null,['class' => 'form-control','id' => 'curp']) !!}
                        </div>
                    </div>
                   <!-- <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Centro de Atenci&oacute;n *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('centro_atencion',null,['class' => 'form-control required','id' => 'centro_atencion']) !!}
                        </div>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">DN Contacto Alterno *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('dn_alterno',null,['class' => 'form-control numero','id' => 'dn_alterno']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">DN Contacto Alterno2 *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('dn_alterno2',null,['class' => 'form-control numero','id' => 'dn_alterno2']) !!}
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Correo Electronico *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('email',null,['class' => 'form-control','id' => 'email']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">NIP *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('nip',null,['class' => 'form-control','id' => 'nip']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Fecha de la Venta *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('fechaVenta',$venta->fecha_venta,['class' => 'form-control dater required','id' => 'fechaVenta']) !!}
                        </div>
                    </div>-->
                     <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Folio de la Venta *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {!! Form::text('folio_venta',null,['class' => 'form-control','id' => 'folio_venta']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Tipificaci&oacute;n 1 *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {{ Form::select('tipificacion1', $tipificacion1, $tipificacion2Venta->tipificacion1_id, ['id' => 'tipificacion1','data-href'=>route('concentra.selectipificacion2'),'class' => 'select form-control required','placeholder' => '[Seleccione]']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Tipificaci&oacute;n 2 *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {{ Form::select('tipificacion2', $tipificacion2, $tipificacion2Venta->id, ['id' => 'tipificacion2','class' => 'select form-control required']) }}
                        </div>
                    </div>
                      <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Ejecutivo a Cargo *</label>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {{ Form::select('ejecutivo', $ejecutivos, $venta->personal_id, ['id' => 'ejecutivo','class' => 'select form-control required','placeholder' => '[Seleccione]']) }}
                        </div>
                    </div>
                    @if(isset(Auth::user()->personal) && Auth::user()->personal->cargo->nombre_cargo == "Validador")
                    <input type="hidden" name="validador_id" value="{{Auth::user()->personal->id}}"/>
                    @else 
                    <div id="validacion" style="display: none">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">BackOffice *</label>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                {{ Form::select('backoffice_id', $backoffices, null, ['id' => 'backoffice_id','class' => 'select form-control','required','placeholder' => '[Seleccione]']) }}
                            </div>
                        </div>  
                    </div>    
                    @endif
                <div class="form-group row">
                    <label class="col-form-label col-lg-3 col-sm-12">Comentarios </label>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        {!! Form::textarea('comentarios',null,['class' => 'form-control','id' => 'comentarios','rows' => 2]) !!}
                    </div>
                </div> 
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" class="btn btn-brand">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!--end::Form-->
        </div>

        <!--end::Portlet-->
    </div>    
</div>
</div>

@endsection
@push('styles')
<!--Custom Style-->

@endpush
@push('scripts')
{!!Html::script('js/jquery-number/jquery.number.min.js')!!}
{!! Html::script("js/concentra/editarVenta.js") !!}
@endpush