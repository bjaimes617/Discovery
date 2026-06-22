@extends('layouts.main')
@section('title','Claro Masivos|Edicion')
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
                    Edicion </a>
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
                            Edicion del Registro
                        </h3>
                    </div>
                </div>               
                <form action="{{ route('claro.masivos.update', $venta->id) }}" id="masivaAdd" method="POST" class="kt-form kt-form--label-right">
                    @csrf
                    @method('PUT')
                    <div class="kt-portlet__body text-center">      
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="producto">Producto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">                                    
                                    <select name="producto" id="producto" data-href="{{ route('claro.masivos.getPlanes') }}" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($producto as $key =>  $productos)
                                        <option value="{{$productos->id}}" @if($venta->producto_id == $productos->id) selected @endif>{{$productos->descripcion}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div>        
                        <div id="displayTipoVenta">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="tipoventa">Tipo de Venta *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                        <select name="tipoventa" id="tipoventa" @if($venta->tipo_venta == "Hogar") Disabled @endif class="select form-control" style="width: 100%;">                                                   
                                            <option value="">[Seleccione]</option>
                                            @foreach($tipo_venta as $tipo_ventas)
                                            <option value="{{$tipo_ventas}}" @if($venta->tipo_venta == $tipo_ventas) selected @endif>{{$tipo_ventas}}</option> 
                                            @endforeach   
                                        </select>                                           
                                    </div> 
                                </div>
                            </div>  
                        </div>     
                          <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="idcontacto">ID Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="idcontacto" id="idcontacto" class="form-control" value="{{ $venta->id_contacto }}" required placeholder="ID Contacto de Response">                                
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="agencia">Agencia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="agencia" id="agencia" class="form-control" disabled value="Directa Group" required placeholder="Razon Social de la Agencia">                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre">Nombres Completos *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="nombre" id="nombre" class="form-control" value="{{ $venta->nombre }}"  required placeholder="Nombre Completo de la Persona">                                
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="apellido1">1er. Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group"> 
                                    <input name="apellido1" id="apellido1" class="form-control" value="{{ $venta->apellido_1 }}"  required placeholder="1er Apellido de la Persona">                                                                                                                                   
                                </div>    
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="apellido2">2do. Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                     
                                    <input name="apellido2" id="apellido2" class="form-control" value="{{ $venta->apellido_2 }}"  required placeholder="2do Apellido de la Persona"> 
                                </div>    
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="identificacion">Identificacion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="identificacion" id="identificacion" class="form-control" value="{{ $venta->identificacion }}"  required placeholder="Identificador">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tipo_cliente">Tipo de Cliente *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-bullhorn"></i></span>
                                    </div>
                                    <select name="tipo_cliente" id="tipo_cliente" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($tipo_cliente as $tipo_clientes)
                                        <option value="{{$tipo_clientes}}" @if($venta->segmento == $tipo_clientes) selected @endif>{{$tipo_clientes}}</option> 
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
                        <label class="col-form-label col-lg-4 col-sm-12" for="precioplan" > Precio Total Plan *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="number" name="precioplan" required id="precioplan" step="0.01" min="0" 
                            value="{{ $venta->precio }}" class="form-control" required placeholder="Precio Total Plan">                                                                                                
                        </div>
                    </div> 
                        <div class="form-group row">
                        <label class="col-form-label col-lg-4 col-sm-12" for="coordenadas">Coordenadas *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="text" name="coordenadas" required id="coordenadas" value="{{ $venta->coordenadas }}" class="form-control" required placeholder="Cordenadas">                                                                                                
                        </div>
                    </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="provincia">Provincia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="provincia" id="provincia" value="{{ $venta->provincia }}" class="form-control" required placeholder="Provincia">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="canton">Canton *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="canton" id="canton" value="{{ $venta->canton }}" class="form-control" required placeholder="Provincia">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="distrito">Distrito *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="distrito" id="distrito" value="{{ $venta->distrito }}" class="form-control" required placeholder="Distrito">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion">Detalle de la Direccion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                    <input name="direccion" id="direccion" value="{{ $venta->detalle_direccion }}" class="form-control" required placeholder="Direccion Exacta">                                                                                                
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefono">Telefono Movil de Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input name="telefono" id="telefono" value="{{ $venta->telefono_a_llamar }}" class="form-control input-number" maxlength="15" required placeholder="Numero Telefonico de Contacto">                                                                                                                                    
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="email">EMail de Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                        <input type="email" name="email" value="{{ $venta->email }}" id="email" class="form-control col-lg-12 col-md-12 col-sm-12" required placeholder="example@example.com">                                     
                                </div>    
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="equipo">Equipo *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                    </div>
                                    <select name="equipo" id="equipo" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($equipos as $equips)
                                           <option value="{{ $equips->id }}" @if($equips->id == $venta->equipo_id) selected @endif>{{ $equips->descripcion }}</option>
                                       @endforeach
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="numero_portar">Numero a Portar *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="numero_portar" id="numero_portar" value="{{ $venta->numero_portar }}" class="form-control" required placeholder="Numero a Portar">                                                                                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="anticipo">Anticipo *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                                    </div>
                                    <select name="anticipo" id="anticipo" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($anticipo as $anticipos)
                                           <option value="{{ $anticipos }}" @if($anticipos == $venta->anticipo) selected @endif>{{ $anticipos }}</option>
                                       @endforeach
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="observacion">Observacion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">   
                                    <input type="text" name="observacion" id="observacion" value="{{ $venta->observaciones }}"  class="form-control" required placeholder="Nota Breve">                                                                                                
                                </div>    
                            </div>
                        </div>
                        <!---Referencia 1---->
                        <div class="form-group row">
                            <div class=col-md-4>
                                <p><i class="fas fa-users"></i> Referencia 1</p>
                            </div>
                             <div class=col-md-8>
                                <hr>
                            </div>
                        </diV>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombreref1">Nombre Y apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input name="nombreref1" id="nombreref1" value="{{ $venta->nombre_refencia_1 }}"  class="form-control" required placeholder="Nombre y Apellido">                                                                                                                                    
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefonoref1">Telefono Referencia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>                              
                                    <input type="text" name="telefonoref1" value="{{ $venta->telefono_refencia_1 }}"  id="telefonoref1" class="form-control input-number" required placeholder="Telefono 1"> 
                                        
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="parentescoref1">Parentesco de la Referencia*</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <select name="parentescoref1" id="parentescoref1" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}" 
                                            @if($parentescos->id == $venta->parentesco_refencia_1) selected @endif 
                                            >{{ $parentescos->descripcion }}</option>
                                       @endforeach
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class=col-md-4>
                                <p><i class="fas fa-users"></i> Referencia 2</p>
                            </div>
                             <div class=col-md-8>
                                <hr>
                            </div>
                        </diV>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombreref2">Nombre y Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input name="nombreref2" id="nombreref2" value="{{ $venta->nombre_refencia_2 }}"  class="form-control" placeholder="Nombre y Apellido">                                                                                                                                    
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefonoref2">Telefono contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>                              
                                    <input type="text" name="telefonoref2" id="telefonoref2" value="{{ $venta->telefono_refencia_2 }}"  class="form-control input-number" placeholder="Telefono 2">                                                                     
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="parentescoref2">Parentesco de la Referencia*</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <select name="parentescoref2" id="parentescoref2" class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}"
                                             @if($parentescos->id == $venta->parentesco_refencia_2) selected @endif >{{ $parentescos->descripcion }}</option>
                                       @endforeach
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class=col-md-4>
                                <p><i class="fas fa-users"></i> Referencia 3</p>
                            </div>
                             <div class=col-md-8>
                                <hr>
                            </div>
                        </diV>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombreref3">Nombre y Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input name="nombreref3" id="nombreref3" class="form-control"  value="{{ $venta->nombre_refencia_3 }}"  placeholder="Nombre y Apellido">                                                                                                
                                    
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="telefonoref3">Telefono Contacto  *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>                              
                                    <input type="text" name="telefonoref3" id="telefonoref3" value="{{ $venta->telefono_refencia_3 }}"  class="form-control input-number" placeholder="Telefono 1"> 
                                </div>    
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="parentescoref3">Parentesco de la Referencia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <select name="parentescoref3" id="parentescoref3" class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}"
                                             @if($parentescos->id == $venta->parentesco_refencia_3) selected @endif >{{ $parentescos->descripcion }}</option>
                                       @endforeach
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-center ml-lg-auto">
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
<!-- end:: Content -->
@push('scripts')
<script src="{{ asset("js/claro/masivos/resources.js") }}"  type="text/javascript"></script> 
@endpush