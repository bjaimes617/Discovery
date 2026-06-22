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
                    Mavivos </a>
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
                            Registrar Nueva Venta
                        </h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('claro.masivos.store') }}" id="masivaAdd" class="kt-form kt-form--label-right">
                    @csrf
                    <div class="kt-portlet__body text-center">  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12">Producto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">                                    
                                    <select name="producto" id="producto" 
                                    data-href="{{ route('claro.masivos.getPlanes') }}" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                        @foreach($producto as $key =>  $productos)
                                        <option value="{{$productos->id}}">{{$productos->descripcion}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div>   
                         <div id="displayTipoVenta" style="display: none">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-sm-12" for="tipoventa">Tipo de Venta *</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                    
                                        <select name="tipoventa" id="tipoventa" class="select form-control" style="width: 100%;">                                                   
                                            <option value="">[Seleccione]</option>
                                            @foreach($tipo_venta as $tipo_ventas)
                                            <option value="{{$tipo_ventas}}">{{$tipo_ventas}}</option> 
                                            @endforeach   
                                        </select>                                           
                                    </div> 
                                </div>
                            </div>  
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="idcontacto">ID Contacto *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="idcontacto" id="idcontacto" class="form-control" value="{{ old('idcontacto') }}" required placeholder="ID Contacto de Respond.io">                                
                            </div>
                        </div>              
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="agencia">Agencia *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="agencia" id="agencia" class="form-control"  disabled value="Directa Group" required placeholder="Razon Social de la Agencia">                                
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="nombre">Nombres Completos *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="nombre" id="nombre" class="form-control"  value="{{ old('nombre') }}" required placeholder="Nombre Completo de la Persona">                                
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="apellido1">1er. Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group"> 
                                    <input name="apellido1" id="apellido1"  value="{{ old('apellido1') }}"  class="form-control" required placeholder="1er Apellido de la Persona">                                                                                                                                   
                                </div>    
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="apellido2">2do. Apellido *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">                                     
                                    <input name="apellido2" id="apellido2" value="{{ old('apellido2') }}"  class="form-control" required placeholder="2do Apellido de la Persona"> 
                                </div>    
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="identificacion">Identificacion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input name="identificacion" id="identificacion" value="{{ old('identificacion') }}" class="input-number form-control" required placeholder="Identificador">                                                                                                
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
                                        <option value="{{$tipo_clientes}}">{{$tipo_clientes}}</option> 
                                        @endforeach   
                                    </select>                                           
                                </div> 
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="tipo_plan">Tipo de Plan *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-bullhorn"></i></span>
                                    </div>
                                    <select name="tipo_plan" id="tipo_plan" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                      
                                    </select>                                           
                                </div> 
                            </div>
                        </div>
                        <div class="form-group row">
                        <label class="col-form-label col-lg-4 col-sm-12" for="precioplan">Precio Total Plan *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="number" name="precioplan" required id="precioplan" step="0.01" min="0" value="{{ old('precioplan') }}" class="form-control" required placeholder="Precio Total Plan">                                                                                                
                        </div>
                        </div> 
                        <div class="form-group row">
                        <label class="col-form-label col-lg-4 col-sm-12" for="coordenadas">Coordenadas *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="text" name="coordenadas" required id="coordenadas" value="{{ old('coordenadas') }}" class="form-control" required placeholder="Cordenadas">                                                                                                
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
                            <label class="col-form-label col-lg-4 col-sm-12" for="direccion">Detalle de la Direccion *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" maxlength="255" class="form-control" required placeholder="Direccion Exacta">                                                                                                
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
                            <label class="col-form-label col-lg-4 col-sm-12" for="equipo">Equipo *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                    </div>
                                    <select name="equipo" id="equipo" required class="select form-control">                                                   
                                        <option value="">[Seleccione]</option>     
                                         @foreach ($equipos as $equips)
                                           <option value="{{ $equips->id }}">{{ $equips->descripcion }}</option>
                                       @endforeach                                 
                                    </select>                                           
                                </div> 
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4 col-sm-12" for="numero_portar">Numero a Portar *</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" name="numero_portar" id="numero_portar"  value="{{ old('numero_portar') }}"  class="form-control" required placeholder="Numero a Portar">                                                                                                
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
                                           <option value="{{ $anticipos }}">{{ $anticipos }}</option>
                                       @endforeach
                                    </select>                                           
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
                                    <input name="nombreref1" id="nombreref1"  value="{{ old('nombreref1') }}" required class="form-control" placeholder="Nombre y Apellido">                                                                                                                                    
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
                                    <input type="text" name="telefonoref1"  value="{{ old('telefonoref1') }}" id="telefonoref1" class="form-control input-number" required placeholder="Telefono 1"> 
                                        
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
                                    <select name="parentescoref1" id="parentescoref1" required class="form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}">{{ $parentescos->descripcion }}</option>
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
                                    <input name="nombreref2" id="nombreref2" value="{{ old('nombreref2') }}"  class="form-control" placeholder="Nombre y Apellido">                                                                                                                                    
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
                                    <input type="text" name="telefonoref2"  value="{{ old('telefonoref2') }}"  id="telefonoref2" class="form-control input-number" placeholder="Telefono 2">                                                                     
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
                                    <select name="parentescoref2" id="parentescoref2" class="form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}">{{ $parentescos->descripcion }}</option>
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
                                    <input name="nombreref3" id="nombreref3"  value="{{ old('nombreref3') }}"  class="form-control" placeholder="Nombre y Apellido">                                                                                                
                                    
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
                                    <input type="text" name="telefonoref3"  value="{{ old('telefonoref3') }}" id="telefonoref3" class="form-control input-number" placeholder="Telefono 1"> 
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
                                    <select name="parentescoref3" id="parentescoref3" class="form-control">                                                   
                                        <option value="">[Seleccione]</option>
                                       @foreach ($parentesco as $parentescos)
                                           <option value="{{ $parentescos->id }}">{{ $parentescos->descripcion }}</option>
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
<script src="{{ asset("js/claro/masivos/resources.js") }}"  type="text/javascript"></script> 
@endpush