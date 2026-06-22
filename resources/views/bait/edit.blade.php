@extends('layouts.main')
@section('title','Bait|Editar')
@section('content')
<!-- begin:: Subheader -->
<div @class(['kt-subheader', 'kt-grid__item']) id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                BAIT </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fas fa-plus-circle"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Bait </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Ventas </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Portabilidad: {{ $venta->numero_portar }}</a>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
  
        <!--begin::Portlet-->
        <div class="row">    
        <div class="col-xl-12">            
            <div class="kt-portlet">
                @include('message.notificationVentas')
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                           Actualizar Datos de Venta
                        </h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('bait.update', $id) }}" id="masivaAdd" class="kt-form kt-form--label-right">
                    @csrf      
                    @method('PUT')        
                      <div class="kt-portlet__body text-center">  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="idcontacto">ID Contacto <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input name="idcontacto" id="idcontacto" class="form-control input-number" value="{{ $venta->idcontacto }}" maxlength="10" required placeholder="ID Contacto de Respond.io">                                
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="numero_portabilidad">Numero portabilidad <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="numero_portabilidad" id="numero_portabilidad" value="{{ $venta->numero_portar }}" class="form-control input-number" minlength="10" maxlength="10" data-check="{{ route('portabilidad.bait.check') }}" data-idventa="{{ $id }}" required placeholder="Numero a portar"> 
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre_apellido">Nombre y Apellido <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="nombre_apellido" id="nombre_apellido" value="{{ $venta->nombre_apellido }}" class="form-control sinCaracteres" required placeholder="Nombre y Apellido"> 
                            </div>
                        </div> 

                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="fecha_nacimiento">Fecha de Nacimiento <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" autocomplete="off"  value="{{ date('d/m/Y', strtotime($venta->fecha_nacimiento)) }}" class="form-control datepicker_single" required placeholder="Fecha de Nacimiento"> 
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="genero">Genero <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <select name="genero" id="genero" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($sexo as $key => $value)
                                        <option value="{{ $key }}" {{ $venta->genero == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="imei">IMEI <span style="color:red;">*</span></label>
                           <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="imei" id="imei" value="{{ $venta->imei }}" class="form-control  input-number" minlength="15" maxlength="15" required placeholder="IMEI"> 
                                </div>
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="codigo_nip">Codigo NIP <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="codigo_nip" id="codigo_nip" value="{{$venta->nip }}" minlength="4"  maxlength="4" class="form-control input-number" required placeholder="Codigo NIP"> 
                                </div>
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="fecha_vigencia">Fecha de Vigencia <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="fecha_vigencia" id="fecha_vigencia" autocomplete="off"  value="{{ date('d/m/Y', strtotime($venta->vigencia_nip)) }}" class="form-control datepicker_single" required placeholder="Fecha de Vigencia"> 
                                </div>
                            </div>
                        </div>                         
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="estado">Estado <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="estado" id="estado" data-href="{{ route('bait.getMunicipio') }}"  class="form-control select" required>
                                    <option value="">Seleccione</option>
                                    @foreach($estados as $value)
                                        <option value="{{ $value->id }}" {{ $estado_id == $value->id ? 'selected' : '' }}>{{ $value->estado }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="municipio">Municipio <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="municipio" id="municipio" class="form-control select" data-href="{{ route('bait.getTiendas') }}" required>
                                    <option value="{{ $municipio->id }}" selected>{{ $municipio->municipio }}</option>                                   
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tienda">Tienda <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="tienda" id="tienda" class="form-control select" required>
                                    <option value="{{ $tienda->id }}" selected>{{ $tienda->id . ' - ' . $tienda->unidad }}</option>                                   
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion_tienda">Direccion <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <b class="text-info"><p id="direccion_tienda">{{ $tienda->direccion }}</p></b>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="fecha_vigencia">Fecha y Hora de Cita <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="fecha_cita" id="fecha_cita" autocomplete="off" value="{{ date('d/m/Y', strtotime($venta->fecha_cita)) }}" class="form-control datepicker_single" required placeholder="Fecha de la Cita">    
                                                                  
                                        <input type="text" name="hora_cita" id="hora_cita" autocomplete="off" value="{{ date('h:i A', strtotime($venta->fecha_cita)) }}" class="form-control datetimepickerHour" required placeholder="Hora de la Cita"> 
                                 
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="correo_electronico">Correo Electronico <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="email" name="correo_electronico" id="correo_electronico" value="{{ $venta->email }}" class="form-control" required placeholder="Correo Electronico"> 
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefono_contacto">Telefono de Contacto <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="telefono_contacto" id="telefono_contacto" value="{{ $venta->telefono_contacto }}" class="form-control input-number" maxlength="10" required placeholder="Telefono de Contacto"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="fvc">FVC <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="fvc" id="fvc" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($fvc as $key => $value)
                                        <option value="{{ $key }}" {{ $venta->fvc == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="modalidad">Modalidad <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="modalidad" id="modalidad" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($modalidades as $key => $value)
                                        <option value="{{ $key }}" {{ $venta->modalidad == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="gestion">Gestion <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="gestion" id="gestion" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($gestion as $key => $value)
                                        <option value="{{ $key }}" {{ $venta->grupo_gestion == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="observaciones">Observaciones Principales </label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Observaciones Principales">{{ $venta->observaciones }}</textarea> 
                                </div>
                            </div>
                        </div>
                       <input type="hidden" name="autorizado" id="autorizado" value="{{ $autorizado }}">
                       <input type="hidden" name="agente_venta" id="agente_venta" value="{{ $venta->personal_id }}">
                        @if($autorizado)                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="operador">Reasignar Venta al Operador <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <select name="operador" id="operador" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($operadores as $operador)
                                        <option value="{{ $operador->id }}" {{ $venta->personal_id == $operador->id ? 'selected' : '' }}>{{ $operador->nombre_apellido }} | {{ $operador->usuario }}</option>
                                    @endforeach
                                </select>
                            </div>                           
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="habilitar_operador">Habilitar Al operador Asignado la Edicion de la Venta <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group">
                                    <select name="habilitar_operador" id="habilitar_operador" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="1" selected>Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>                           
                        </div>                        
                        @endif
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-xl-12 text-center ml-lg-auto">
                                    <button type="submit" class="btn btn-brand btn-block">Actualizar</button>
                                </div>
                            </div>
                        </div>  
                    </div>                  
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
<link href="{{ asset('assets/plugins/general/select2/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
<!-- end:: Content -->
@push('scripts')
<script src="{{ asset('js/bait/resources.js') }}"  type="text/javascript"></script> 
@endpush