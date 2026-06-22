@extends('layouts.main')
@section('title','Claro Masivos|Agregar')
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
                    Pymes </a>
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
  
        <!--begin::Portlet-    <div class="row">    
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                @include('message.notificationVentas')
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Pymes - Registrar Nueva Venta
                        </h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('claro.pymes.store') }}" id="masivaAdd" class="kt-form kt-form--label-right">
                    @csrf
                    <div class="kt-portlet__body text-center">  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="idcontacto">ID Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="idcontacto" id="idcontacto" class="form-control" value="{{ old('idcontacto') }}" required placeholder="ID Contacto de Respond.io">                                
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tipo_cliente">Tipo de Cliente *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">                                 
                                    <select name="tipo_cliente" id="tipo_cliente" value="{{ old('tipo_cliente') }}" required class="form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($tipo_cliente as $key => $tipo_clientes)
                                        <option value="{{$key}}">{{$tipo_clientes}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div> 
                        <!--DIV AFILIADOS-->
                        <div id="divAfiliados" style="display:none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cedulatitular">Cedula Titular *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="cedulatitular" id="cedulatitular" value="{{ old('cedulatitular') }}" class="input-number form-control" required placeholder="Cedula Titular">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="ordenpatronal">Orden Patronal *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="ordenpatronal" id="ordenpatronal" value="{{ old('ordenpatronal') }}" class="form-control" required placeholder="Orden Patronal">
                                </div>
                            </div> 
                        </div>
                        <!--DIV PYMES-->
                        <div id="divpymes" style="display:none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="personeriajuridica">Personeria Juridica *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="personeriajuridica" id="personeriajuridica" value="{{ old('personeriajuridica') }}" class="form-control" placeholder="Personeria Juridica - RIF">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="representantelegal">Cedula Representante Legal *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="representantelegal" id="representantelegal" value="{{ old('representantelegal') }}" class="input-number form-control" placeholder="Cedula Representante Legal">
                                </div>
                            </div> 
                        </div>
                          <!--DIV SOHO-->
                        <div id="divsoho" style="display:none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cedulatitularpymes">Cedula Titular *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="cedulatitularpymes" id="cedulatitularpymes" value="{{ old('cedulatitularpymes') }}" class="input-number form-control" required placeholder="Cedula Titular">
                                </div>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre">Nombres Completos *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="nombre" id="nombre" class="form-control"  value="{{ old('nombre') }}" required placeholder="Nombre Completo de la Persona">                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefono">Telefono Movil de Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" class="form-control input-number" maxlength="15" required placeholder="Numero Telefonico de Contacto">                                                                                                                                    
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="email">Email de Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                        <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control col-lg-12 col-md-12 col-sm-12" required placeholder="example@example.com">                                     
                                </div>    
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="provincia">Provincia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="provincia" id="provincia" value="{{ old('provincia') }}" class="form-control" required placeholder="Provincia">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="canton">Canton *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="canton" id="canton" value="{{ old('canton') }}" class="form-control" required placeholder="Canton">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="distrito">Distrito *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="distrito" id="distrito" value="{{ old('distrito') }}" class="form-control" required placeholder="Distrito">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="barrio">Barrio *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="barrio" id="barrio" value="{{ old('barrio') }}" class="form-control" required placeholder="Barrio">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion">Detalle de la Direccion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" class="form-control" required placeholder="Direccion Exacta">                                                                                                
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="producto">Producto *</label>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                               <div class="input-group<">                                    
                                    <select name="producto" id="producto" 
                                    data-href="{{ route('claro.pymes.getPlanes') }}" required class="select form-control" width="100%">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($producto as $key =>  $productos)
                                        <option value="{{$productos->id}}">{{$productos->descripcion}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tipo_plan">Tipo de Plan *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                                                      
                                    <select name="tipo_plan" id="tipo_plan" required class="select form-control" width="100%">                                                   
                                        <option value="">[Seleccione]</option>                                      
                                    </select>                                           
                                </div> 
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="precioplan">Precio del Plan *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">   
                                    <input type="number" name="precioplan" step='.01' placeholder= '0.00'  required id="precioplan"  value="{{ old('precioplan') }}"  class="form-control" >                                                                                                
                                </div>    
                            </div>
                        </div>
                        <div id="inputsgpon" style="display:none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="coordenadas">Coordenadas *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" name="coordenadas" id="coordenadas" value="{{ old('coordenadas') }}" class="form-control" placeholder="Cordenadas">                                                                                                
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cantidadstb">Cantidad STB *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" name="cantidadstb" id="cantidadstb" value="{{ old('cantidadstb') }}" class="form-control" placeholder="Cantidad STB">                                                                                                
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div id="inputsmovil" style="display:none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="equipo">Equipo *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" name="equipo" id="equipo" value="{{ old('equipo') }}" class="form-control" placeholder="Equipo">                                                                                                
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="portabilidad">Portabilidad *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" name="portabilidad" id="portabilidad" value="{{ old('portabilidad') }}" class="form-control" placeholder="Portabilidad">                                                                                                
                                    </div>
                                </div>
                            </div> 
                        </div>
                                                  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="observacion">Observacion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">   
                                    <input type="text" name="observacion" id="observacion"  value="{{ old('observacion') }}"  class="form-control" required placeholder="Nota Breve">                                                                                                
                                </div>    
                            </div>
                        </div>
                                             
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-center ml-lg-auto">
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
<!-- end:: Content -->
@push('scripts')
<script src="{{ asset("js/claro/pymes/resources.js") }}"  type="text/javascript"></script> 
@endpush