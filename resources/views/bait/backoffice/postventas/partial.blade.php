 <form method="POST" action="{{ route('bait.backoffice.postventa.update', $venta->id) }}" id="postventaupdate" class="kt-form kt-form--label-right">
    @csrf      
    @method('PUT')        
    <div class="kt-portlet__body text-center">  
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="idcontacto">ID Contacto <span style="color:red;">*</span></label>
                <div class="from-group">
                    <input name="idcontacto" id="idcontacto" class="form-control input-number" value="{{ $venta->idcontacto }}" maxlength="10" required placeholder="ID Contacto de Respond.io">                                
                </div>
            </div>   
            <div class="col-md-4">
                <label for="numero_portabilidad">Numero portabilidad <span style="color:red;">*</span></label>
                <div class="from-group">
                    <input type="text" name="numero_portabilidad" id="numero_portabilidad" value="{{ $venta->numero_portar }}" class="form-control input-number" minlength="10" maxlength="10" required placeholder="Numero a portar"> 
                </div>
            </div> 
            <div class="col-md-4">
                <label for="nombre_apellido">Nombre y Apellido <span style="color:red;">*</span></label>
                <div class="from-group">
                    <input type="text" name="nombre_apellido" id="nombre_apellido" value="{{ $venta->nombre_apellido }}" class="form-control sinCaracteres" required placeholder="Nombre y Apellido"> 
                </div>
            </div> 
            <div class="col-md-4">
                <label for="fecha_nacimiento">Fecha de Nacimiento <span style="color:red;">*</span></label>
                <div class="from-group">
                    <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" autocomplete="off"  value="{{ date('d/m/Y', strtotime($venta->fecha_nacimiento)) }}" class="form-control datepicker_single" required placeholder="Fecha de Nacimiento"> 
                </div>
            </div> 

            <div class="col-md-4">
                <label for="genero">Genero <span style="color:red;">*</span></label>
                <div class="from-group">
                    <select name="genero" id="genero" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($sexo as $key => $value)
                            <option value="{{ $key }}" {{ $venta->genero == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div> 
                <div class="col-md-4">
                <label for="imei">IMEI <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="imei" id="imei" value="{{ $venta->imei }}" class="form-control  input-number" minlength="15" maxlength="15" required placeholder="IMEI"> 
                    </div>
                </div>
            </div> 
                <div class="col-md-6">
                <label for="codigo_nip">Codigo NIP <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="codigo_nip" id="codigo_nip" value="{{$venta->nip }}" minlength="4"  maxlength="4" class="form-control input-number" required placeholder="Codigo NIP"> 
                    </div>
                </div>
            </div> 
                <div class="col-md-6">
                <label for="fecha_vigencia">Fecha de Vigencia <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="fecha_vigencia" id="fecha_vigencia" autocomplete="off"  value="{{ date('d/m/Y', strtotime($venta->vigencia_nip)) }}" class="form-control datepicker_single" required placeholder="Fecha de Vigencia"> 
                    </div>
                </div>
            </div>                         
            <div class="col-md-4">
                <label for="estado">Estado <span style="color:red;">*</span></label>
                <div class="from-group">
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
            <div class="col-md-4">
                <label for="municipio">Municipio <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="municipio" id="municipio" class="form-control select" data-href="{{ route('bait.getTiendas') }}" required>
                        <option value="{{ $municipio->id }}" selected>{{ $municipio->municipio }}</option>                                   
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="tienda">Tienda <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="tienda" id="tienda" class="form-control select" required>
                        <option value="{{ $tienda->id }}" selected>{{ $tienda->id . ' - ' . $tienda->unidad }}</option>                                   
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label for="direccion_tienda">Direccion <span style="color:red;">*</span></label>
                <div class="from-group">
                    <b class="text-info"><p id="direccion_tienda">{{ $tienda->direccion }}</p></b>
                </div>
            </div>
                <div class="col-md-4">
                <label for="fecha_vigencia">Fecha y Hora de Cita <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="fecha_cita" id="fecha_cita" autocomplete="off" value="{{ date('d/m/Y', strtotime($venta->fecha_cita)) }}" class="form-control datepicker_single" required placeholder="Fecha de la Cita">    
                                                        
                            <input type="text" name="hora_cita" id="hora_cita" autocomplete="off" value="{{ date('h:i A', strtotime($venta->fecha_cita)) }}" class="form-control datetimepickerHour" required placeholder="Hora de la Cita"> 
                        
                    </div>
                </div>
            </div> 
            <div class="col-md-4">
                <label for="correo_electronico">Correo Electronico <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="email" name="correo_electronico" id="correo_electronico" value="{{ $venta->email }}" class="form-control" required placeholder="Correo Electronico"> 
                    </div>
                </div>
            </div> 
            <div class="col-md-4">
                <label for="telefono_contacto">Telefono de Contacto <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="telefono_contacto" id="telefono_contacto" value="{{ $venta->telefono_contacto }}" class="form-control input-number" maxlength="10" required placeholder="Telefono de Contacto"> 
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="fvc">FVC <span style="color:red;">*</span></label>
                <div class="from-group">
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
            <div class="col-md-4">
                <label for="modalidad">Modalidad <span style="color:red;">*</span></label>
                <div class="from-group">
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
            <div class="col-md-4">
                <label for="gestion">Gestion <span style="color:red;">*</span></label>
                <div class="from-group">
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
            <div class="col-md-4">
                <label for="telefono_contacto">Folio de Intelix <span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                        <input type="text" name="folio_intelix" id="folio_intelix" value="{{ $venta->folio_venta }}" class="form-control input-number" minlength="10" maxlength="20" required placeholder="Folio de Intelix"> 
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label for="observaciones">Observaciones Principales de la Venta </label>
                <div class="from-group">
                    <div class="input-group"> 
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2" placeholder="Observaciones Principales">{{ $venta->observaciones }}</textarea> 
                    </div>
                </div>
            </div>           
            <div class="col-md-12">
                <br>
                <h5 class="text-primary mt-3"> ==== Actualizar Estatus de la Venta ==== </h5>            
                <hr>
            </div>
            <div class="col-md-4">
                <label for="sns">SNS<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="sns" id="sns" class="form-control" required>
                        <option value="">Seleccione</option>   
                         @foreach ($sns as $values)
                          <option value="{{  $values }}" {{ $venta->sns == $values ? 'selected' : '' }}>{{ $values }}</option>
                      @endforeach                   
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="concentra">Concentra<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="concentra" id="concentra" class="form-control" required>
                        <option value="">Seleccione</option>
                      @foreach ($concentra as $value)
                          <option value="{{  $value->id }}" {{ $venta->bait_concentra_id == $value->id ? 'selected' : '' }}>{{ $value->descripcion }}</option>
                      @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="intelix">Intelix<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="intelix" id="intelix" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach ($intelix as $value)
                            <option value="{{  $value->descripcion }}" {{ $venta->estatus_intelix == $value->descripcion ? 'selected' : '' }}>{{ $value->descripcion }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
              <div class="col-md-4">
                <label for="validar_bo">Validacion de BO<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="validar_bo" id="validar_bo" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach ($validatebo as $value)
                            <option value="{{  $value }}" {{ $venta->estatus_backoffice == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="estatus_final">Estatus Final<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="estatus_final" id="estatus_final" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach ($estatus_final as $value)
                            <option value="{{  $value->id }}" {{ $venta->estatus_id == $value->id ? 'selected' : '' }}>{{ $value->descripcion }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="validar_alta">Validacion del Alta<span style="color:red;">*</span></label>
                <div class="from-group">
                    <div class="input-group"> 
                    <select name="validar_alta" id="validar_alta" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach ($validacionalta as $value)
                            <option value="{{  $value }}" {{ $venta->validador_alta == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label for="observaciones_aclara">Observaciones de la Actualización</label>
                <div class="from-group">
                    <div class="input-group"> 
                        <textarea name="observaciones_aclara" id="observaciones_aclara" class="form-control" rows="3" 
                        placeholder="Observaciones de la Actualización"></textarea> 
                    </div>
                </div>
            </div>  
              <div class="col-md-12">
                <p class="text-danger"><B>Atención: Los Estatus Finales aqui seleccionados, se liberan Una vez la Coordinadora de BO lo autorice</B></p>
              </div>
            <div class="col-lg-12 ml-lg-auto mt-3">
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-12 ml-lg-auto">
                                <button type="submit" class="btn btn-primary btn-brand btn-block"><i class="fa fas-sync"></i>Actualizar</button>                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                    
</form>