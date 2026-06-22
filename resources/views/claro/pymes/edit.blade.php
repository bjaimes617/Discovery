@extends('layouts.main')
@section('title','Claro Masivos|Editar')
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
                    Editar </a>
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
                            Pymes - Edicion de la Venta
                        </h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('claro.pymes.update',$venta->id) }}" id="masivaAdd" class="kt-form kt-form--label-right">
                    @csrf
                    @method('PUT')
                    <div class="kt-portlet__body text-center">  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="idcontacto">ID Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="idcontacto" id="idcontacto" class="form-control" value="{{ $venta->id_contacto }}" required placeholder="ID Contacto de Respond.io">                                
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tipo_cliente">Tipo de Cliente *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">                                 
                                    <select name="tipo_cliente" id="tipo_cliente" value="{{ old('tipo_cliente') }}" required class="form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($tipo_cliente as $key => $tipo_clientes)
                                        <option value="{{$key}}" @if($venta->tipo_venta == $key) selected @endif>{{$tipo_clientes}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div> 
                       
                        <!--DIV AFILIADOS-->                       
                        <div id="divAfiliados" @if($venta->tipo_venta === "0") style="" @else style="display:none;" @endif>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cedulatitular">Cedula Titular *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input name="cedulatitular" id="cedulatitular" value="{{ $venta->tipo_venta === "0" ? $venta->identificacion : ""}}" class="input-number form-control" placeholder="Cedula Titular">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="ordenpatronal">Orden Patronal *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input name="ordenpatronal" id="ordenpatronal" value="{{ $venta->tipo_venta === "0" ? $venta->ordenpatronal : ""}}" class="form-control" placeholder="Orden Patronal">
                                </div>
                            </div> 
                        </div>
                        <!--DIV PYMES-->
                        
                        <div id="divpymes" @if($venta->tipo_venta === "1")  style="" @else style="display:none;" @endif>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="personeriajuridica">Personeria Juridica *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input name="personeriajuridica" id="personeriajuridica" value="{{ $venta->tipo_venta === "1" ? $venta->identificacion : "" }}" class="form-control" placeholder="Personeria Juridica - RIF">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="representantelegal">Cedula Representante Legal *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input name="representantelegal" id="representantelegal" value="{{ $venta->tipo_venta === "1" ? $venta->representantelegal : "" }}" class="input-number form-control" placeholder="Cedula Representante Legal">
                                </div>
                            </div> 
                        </div>
                          <!--DIV SOHO-->
                        <div id="divsoho" @if($venta->tipo_venta === "2") style="" @else style="display:none;" @endif>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cedulatitularpymes">Cedula Titular *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" name="cedulatitularpymes" id="cedulatitularpymes" value="{{ $venta->tipo_venta === "2" ? $venta->identificacion : "" }}" class="input-number form-control"  placeholder="Cedula Titular">
                                </div>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre">Nombres Completos *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="nombre" id="nombre" class="form-control"  value="{{ $venta->nombre }}" required placeholder="Nombre Completo de la Persona">                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12">Telefono Movil de Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input name="telefono" id="telefono" value="{{ $venta->telefono_a_llamar  }}" class="form-control input-number" maxlength="15" required placeholder="Numero Telefonico de Contacto">                                                                                                                                    
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
                                        <input type="email" name="email" value="{{ $venta->email }}" id="email" class="form-control" required placeholder="example@example.com">                                     
                                </div>    
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="provincia">Provincia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="provincia" id="provincia" value="{{  $venta->provincia }}" class="form-control" required placeholder="Provincia">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="canton">Canton *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="canton" id="canton" value="{{ $venta->canton }}" class="form-control" required placeholder="Canton">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="distrito">Distrito *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="distrito" id="distrito" value="{{  $venta->distrito }}" class="form-control" required placeholder="Distrito">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="barrio">Barrio *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="barrio" id="barrio" value="{{  $venta->barrio }}" class="form-control" required placeholder="Barrio">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion">Detalle de la Direccion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                    <input name="direccion" id="direccion" value="{{  $venta->detalle_direccion }}" class="form-control" required placeholder="Direccion Exacta">                                                                                                
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
                                        <option value="{{$productos->id}}" @if($productos->id == $venta->producto_id) selected @endif>{{$productos->descripcion}}</option> 
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
                                        @foreach($group as $groups)
                                        <optgroup label="{{ $groups->group }}">
                                            @foreach($planes as $plan)
                                                @if($plan->group == $groups->group)
                                                    <option value="{{ $plan->id }}" @if($venta->plan_id == $plan->id) selected @endif>{{ $plan->descripcion }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                        <optgroup label="Sin Categoria"> 
                                            @foreach($planes as $plan)   
                                                @if($plan->group === null)
                                                    <option value="{{ $plan->id }}" @if($venta->plan_id == $plan->id) selected @endif>{{ $plan->descripcion }}</option>
                                                @endif                                            
                                            @endforeach
                                        </optgroup>
                                    </select>                                           
                                </div> 
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="precioplan">Precio del Plan *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">   
                                    <input type="number" name="precioplan" step='.01' placeholder= '0.00'  required id="precioplan"  value="{{ $venta->precio_plan}}"  class="form-control" >                                                                                                
                                </div>    
                            </div>
                        </div>
                        <div id="inputsgpon" @if($venta->producto_id == 1) style="" @php $attr ="required"; @endphp @else style="display:none;" @php $attr =""; @endphp @endif>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="coordenadas">Coordenadas *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input name="coordenadas" id="coordenadas" {{$attr}} value="{{$venta->producto_id == 1 ? $venta->cordenadas : "" }}" class="form-control" placeholder="Cordenadas">                                                                                                
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="cantidadstb">Cantidad STB *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input name="cantidadstb" id="cantidadstb" {{$attr}} value="{{ $venta->producto_id == 1 ? $venta->cantidad : "" }}" class="form-control" placeholder="Cantidad STB">                                                                                                
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div id="inputsmovil" @if($venta->producto_id == 2) style="" @php $attr2 ="required"; @endphp @else style="display:none;" @php $attr2 =""; @endphp @endif>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="equipo">Equipo *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input name="equipo" id="equipo" {{$attr2}} value="{{ $venta->producto_id == 2 ? $venta->equipo : "" }}" class="form-control" placeholder="Equipo">                                                                                                
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="portabilidad">Portabilidad *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="input-group">
                                        <input name="portabilidad" id="portabilidad" {{$attr2}} value="{{ $venta->producto_id == 2 ? $venta->portabilidad : "" }}" class="form-control" placeholder="Portabilidad">                                                                                                
                                    </div>
                                </div>
                            </div> 
                        </div>
                                                  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="observacion">Observacion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">   
                                    <input type="text" name="observacion" id="observacion"  value="{{ $venta->observaciones }}"  class="form-control" required placeholder="Nota Breve">                                                                                                
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