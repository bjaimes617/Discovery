@extends('layouts.main')
@section('title','Usuarios')
@section('content')
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
                    Nuevo </a>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
<!-- begin:: Content -->
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid  kt-wizard-v1 kt-wizard-v1--white" id="kt_contacts_add" data-ktwizard-state="step-first">
                <div class="kt-grid__item">

                    <!--begin: Form Wizard Nav -->
                    <div class="kt-wizard-v1__nav">
                        <div class="kt-wizard-v1__nav-items">

                            <!--doc: Replace A tag with SPAN tag to disable the step link click -->
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--xl">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.98630124,11 4.48466491,11.0516454 4,11.1500272 L4,7 C4,5.8954305 4.8954305,5 6,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,16 C22,17.1045695 21.1045695,18 20,18 L12.9835977,18 Z M19.1444251,6.83964668 L13,10.1481833 L6.85557487,6.83964668 C6.4908718,6.6432681 6.03602525,6.77972206 5.83964668,7.14442513 C5.6432681,7.5091282 5.77972206,7.96397475 6.14442513,8.16035332 L12.6444251,11.6603533 C12.8664074,11.7798822 13.1335926,11.7798822 13.3555749,11.6603533 L19.8555749,8.16035332 C20.2202779,7.96397475 20.3567319,7.5091282 20.1603533,7.14442513 C19.9639747,6.77972206 19.5091282,6.6432681 19.1444251,6.83964668 Z" fill="#000000" />
                                            </g>
                                        </svg> </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        Cuenta de Usuario
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--xl">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg> </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        Ficha de Personal 
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>

                    <!--end: Form Wizard Nav -->
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">

                    <!--begin: Form Wizard Form-->
                    <form action="{{ route('user.update', $user) }}" method="POST" id="kt_contacts_edit_form" class="kt-form" data-error="{{ trans('admin.msgrequiredfields') }}">
                    @csrf
                    @method('PUT')
                        <input type="hidden" name="id" value="{{$user->id}}" id="id">
                            <div class="kt-wizard-v1__content" data-ktwizard-type="step-content" data-ktwizard-state="current"> 
                                <div class="kt-section kt-section--first">
                                    <div class="kt-wizard-v1__form">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="kt-section__body">
                                                    <div class="form-group row">
                                                        <div class="col-lg-9 col-xl-6">
                                                            <h3 class="kt-section__title kt-section__title-md">Datos de Usuario</h3>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Nombre y Apellido</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" name="nombre_apellido" value="{{ old('nombre_apellido', $user->nombre_apellido) }}" class="form-control" id="nombre_apellido" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Usuario</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" name="usuario" value="{{ old('usuario', $user->usuario) }}" class="form-control" id="usuario" required data-check="{{ route('user.checkusername') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                                                                <input type="text" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" required data-check="{{ route('user.checkemail') }}">
                                                            </div>
                                                        </div>
                                                    </div>                                 
                                                    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-9 col-xl-6">
                                                            <h3 class="kt-section__title kt-section__title-md">Configuraci&oacute;n de Cuenta</h3>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Autenticaci&oacute;n 2FA</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <span class="kt-switch kt-switch--icon">
                                                                <label>
                                                                    <input type="checkbox" name="auth2fa" id="auth2fa" @if($user->fa2 == 'Si') checked='checked' @endif />
                                                                           <span></span>
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Reiniciar Contraseña</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <span class="kt-switch kt-switch--icon">
                                                                <label>
                                                                    <input type="checkbox" name="resetpass" id="resetpass" />
                                                                           <span></span>
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($user->fa2 == 'Si')
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Restaurar C&oacute;digo QR 2FA</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <span class="kt-switch kt-switch--icon">
                                                                <label>
                                                                    <input type="checkbox" name="reset2fa" id="reset2fa"/>
                                                                           <span></span>
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                  
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Roles</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select name="roles[]" id="roles" class="form-control kt-select2 kt_select2_1" data-href="{{route('users.permissions.add')}}" style="width: 100%">
                                                            <option value="" selected>[Seleccione]</option>
                                                            @foreach($roles as  $val)
                                                                <option value="{{ $val->id }}" {{ $val->id == $roleuser->id ? 'selected' : '' }}>{{ $val->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>  
                                                    
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Permisos Adicionales</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select name="permisos[]" id="permisos" multiple class="form-control kt-select2 kt_select2_1" style="width: 100%" placeholder="Seleccione una o mas opciones" >                                                           
                                                            @foreach($permission as $key => $permisos)                                                                
                                                            <option value="{{$permisos->id}}" 
                                                                    @if(in_array($permisos->id, $permisosadicionales)) selected @endif 
                                                                    @if(in_array($permisos->id, $permisosrol)) disabled @endif 
                                                                >{{$permisos->name}}</option>                                                                                                      
                                                            @endforeach
                                                            
                                                        </select>
                                                        </div>
                                                    </div>  
                                                    <div class="form-group text-center">
                                                        <p>Seleccione permisos adicionales al perfil asignado, <span style="color:red;"><b>Dejar vacio para omitir</b></span></p>
                                                    </div>
                                                    @if($user->estatus_id != 3)
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Estatus</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select name="estatus" id="estatus" class="form-control kt-select2 kt_select2_1" style="width: 100%" required>
                                                                <option value="" selected>[Seleccione]</option>
                                                                @foreach($estatus as $key => $val)
                                                                    <option value="{{ $key }}" {{ old('estatus', $user->estatus_id) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end: Form Wizard Step 1-->
                            <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                                <div class="kt-heading kt-heading--md">Ficha Personal:</div>
                                <div class="kt-section kt-section--first">
                                    <div class="kt-wizard-v1__form">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="kt-section__body">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Activar</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <span class="kt-switch kt-switch--icon">
                                                                <label>
                                                                    <input type="checkbox" name="valida_ficha_personal" id="valida_ficha_personal" />
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div id="ficha_personal" style="display: none;">
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">User Respond</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" name="user_response" value="{{ old('user_response', (!empty($personal) ? $personal->in_telefonico : ($user->in_telefonico ?? ''))) }}" class="form-control" id="user_response" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">N&uacute;mero de Empleado</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <input type="text" name="numero_empleado" value="{{ old('numero_empleado', (!empty($personal) ? $personal->numero_empleado : ($user->numero_empleado ?? ''))) }}" class="form-control" id="numero_empleado" required data-check="{{ route('user.checknumeroempleado') }}">
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Fecha de Ingreso</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                    <input type="text" name="fecha_ingreso" value="{{ old('fecha_ingreso', (!empty($personal) ? $personal->fecha_ingreso : ($user->fecha_ingreso ?? ''))) }}" class="form-control dater" id="fecha_ingreso" required placeholder="Seleccione una fecha">
                                                            </div>
                                                        </div>  
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Estatus</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select name="estatus_personal" id="estatus_personal" class="form-control kt-select2 kt_select2_1" style="width: 100%" required>
                                                                    <option value="" selected>[Seleccione]</option>
                                                                    @foreach(['1' => 'Activo','2' => 'Baja'] as $key => $val)
                                                                        <option value="{{ $key }}" {{ old('estatus_personal', (!empty($personal) ? $personal->getRawOriginal('estatus') : '')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id='fechab' style="display: none">
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Fecha de Baja</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input type="text" name="fecha_baja" value="{{ old('fecha_baja', !empty($personal) ? $personal->fecha_baja : '') }}" class="form-control dater" id="fecha_baja" required placeholder="Seleccione una fecha">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Campa&ntilde;a</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select name="campania" id="campania" class="form-control kt-select2 kt_select2_1" style="width: 100%">
                                                                    <option value="" selected>[No aplica]</option>
                                                                    @foreach($campanias as $key => $val)
                                                                        <option value="{{ $key }}" {{ old('campania', (!empty($personal) ? $personal->campana_id : '')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Cargo</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select name="cargo" id="cargo" class="form-control kt-select2 kt_select2_1" style="width: 100%" required>
                                                                    <option value="" selected>[Seleccione]</option>
                                                                    @foreach($cargos as $key => $val)
                                                                        <option value="{{ $key }}" {{ old('cargo', (!empty($personal) ? $personal->cargo_id : '')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                                                    @endforeach
                                                                </select>                                                                
                                                            </div>
                                                        </div>
                                                        <div id="supervisores" style="display: none;">
                                                            <div data-repeater-list="supervisores">
                                                                <div class="form-group row" data-repeater-item>
                                                                    <label class="col-xl-3 col-lg-3 col-form-label" for="cargo">Supervisor Asignado
                                                                    </label>
                                                                    <div class="col-lg-9 col-xl-9">
                                                                        <div class="input-group">
                                                                            <select name="supervisor" id="supervisor" class="form-control" style="width: 80%" required>
                                                                            <option value="" selected>Seleccione una opcion</option>
                                                                            @foreach($supervisores as $key => $val)
                                                                                <option value="{{ $val->id }}">{{ $val->nombre_apellido }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                            <div class="input-group-append"><button type="button" class="btn btn-danger" data-repeater-delete=""><i class="la la-trash" style="color: #FFF;"></i></button></div>                                                                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label" for="cargo">&nbsp; <span class="required"></span>
                                                                </label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <button type="button" class="btn btn-info" data-repeater-create="">Agregar Supervisor</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                      
                                                        <div class="form-group row">                                                             
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Jefe Inmediato</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select name="jefe_inmediato" id="jefe_inmediato" class="form-control kt-select2 kt_select2_1" style="width: 100%">
                                                                    <option value="" selected>N/A</option>
                                                                    @foreach($jefes as $key => $val)
                                                                   
                                                                        <option value="{{ $val->id }}" {{ old('jefe_inmediato', (!empty($personal) ? $personal->jefe_inmediato_id : '')) == $val->id ? 'selected' : '' }}>{{ $val->nombre_apellido }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">Segundo Jefe Inmediato</label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <select name="segundo_jefe_inmediato" id="segundo_jefe_inmediato" class="form-control kt-select2 kt_select2_1" style="width: 100%">
                                                                    <option value="" selected>N/A</option>                                                                    
                                                                    @foreach($jefes as $key => $val)
                                                                        <option value="{{ $val->id }}" {{ old('segundo_jefe_inmediato', (!empty($personal) ? $personal->jefe_inmediato_segundo_id : '')) == $val->id ? 'selected' : '' }}>{{ $val->nombre_apellido }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-form__actions">
                                <div class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                    Anterior
                                </div>
                                <div class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
                                    Guardar
                                </div>
                                <div class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                    Pr&oacute;ximo
                                </div>
                            </div>
                            </form>
                            </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Content -->
@endsection
@push('styles')
<!--Custom Style-->
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}">
@endpush
@push('scripts')
@include('partials.js.edituser')
    <script src="{{ asset('assets/plugins/general/jquery.repeater/src/lib.js') }}"></script>
    <script src="{{ asset('assets/plugins/general/jquery.repeater/src/jquery.input.js') }}"></script>
    <script src="{{ asset('assets/plugins/general/jquery.repeater/src/repeater.js') }}"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    
@endpush

