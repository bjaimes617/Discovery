@extends('layouts.main')
@section('title','Renovaciones|Agregar')
@section('content')
<!-- begin:: Subheader -->
<div @class(['kt-subheader', 'kt-grid__item']) id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Renovaciones </h3>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="fas fa-plus-circle"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Renovaciones </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Ventas </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="" class="kt-subheader__breadcrumbs-link">
                    Agregar </a>
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
                            Registrar Nueva Venta
                        </h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('renovaciones.store') }}" id="masivaAdd" class="kt-form kt-form--label-right">
                    @csrf              
                      <div class="kt-portlet__body text-center">                           
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="dn">DN <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="dn" id="dn" value="{{ old('dn') }}" class="form-control input-number" minlength="10" maxlength="10" data-check="{{ route('portabilidad.bait.check') }}" required placeholder="Numero a portar"> 
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre_apellido">Nombre y Apellido <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="nombre_apellido" id="nombre_apellido" value="{{ old('nombre_apellido') }}" class="form-control sinCaracteres" required placeholder="Nombre y Apellido"> 
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="equipo">Equipo <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="equipo" id="equipo" autocomplete="off"  value="{{ old('equipo') }}" class="form-control" required placeholder="Equipo"> 
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="plazos">Plazos <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                <select name="plazos" id="plazos"  class="form-control select" required>
                                    <option value="">Seleccione</option>
                                    @foreach($plazos as $key => $value)
                                        <option value="{{ $value }}" {{ old('plazos') == $value ? 'selected' : '' }}>{{ $value }} Meses</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="entrega_en">Entrega en <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <input type="text" name="entrega_en" id="entrega_en" value="Domicilio"  class="form-control " required placeholder="entrega_en"> 
                                </div>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="ordenonix">Nro de Orden ONIX <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <input type="text" name="ordenonix" id="ordenonix" data-check='{{ route("renovaciones.check.dn") }}' autocomplete="off"  value="{{ old('ordenonix') }}" minlength="10" maxlength="10" class="form-control input-number" required placeholder="Nro de Orden ONIX"> 
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="map">Seleccione la direccion <span style="color:red;">*</span></label>
                             <input type="hidden" id="corddefault" name="corddefault" data-zoom="5" data-cord="{!! $cordmap !!}">
                            <div class="col-lg-7 col-md-12 col-sm-12" id="map"></div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion">Direccion Seleccionada <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">                             
                                <input type="text" id="direccion" name="direccion" required disabled value="{{ old('direccion') }}" class="form-control " placeholder="Usa el buscador del mapa o mueve el pin...">                                                                 
                            </div>
                        </div>

                         <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="referencia">Punto de Referencia <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">                             
                                <input type="text" id="referencia" name="referencia" required value="{{ old('referencia') }}" class="form-control " placeholder="Punto de Referencia...">                                                                 
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="precio">Precio del equipo <span style="color:red;">*</span></label>
                            <div class="col-lg-7 col-md-12 col-sm-12">                             
                                <input type="number" id="precio" name="precio"  required value="{{ old('precio') }}" class="form-control " placeholder="Precio del equipo...">                                                                 
                            </div>
                        </div>

                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="observaciones">Observaciones Principales </label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="input-group"> 
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Observaciones Principales">{{ old('observaciones') }}</textarea> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-xl-12 text-center ml-lg-auto">
                                    <button type="submit" class="btn btn-brand btn-block">Guardar</button>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />    
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    
<style>
    #map {
    position: relative; /* Asegura el contexto de posicionamiento */
    z-index: 0;         /* Evita que se sobreponga a los elementos del formulario o modales */
        height: 500px;
        width: 100%;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }     
</style>
@endpush
<!-- end:: Content -->
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="{{ asset('js/renovaciones/resources.js') }}"  type="text/javascript"></script>
@endpush