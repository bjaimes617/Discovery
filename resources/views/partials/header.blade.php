<?php
$rutaMain = Route::currentRouteName();
$ruta = explode(".", $rutaMain);
$arrName = explode(" ", Auth::user()->nombre_apellido);
$nombre = $arrName[0];

?> 
<div id="kt_header" class="kt-header  kt-header--fixed " data-ktheader-minimize="on">
    <div class="kt-container ">

        <!-- begin:: Brand -->
        <div class="kt-header__brand kt-grid__item" id="kt_header_brand" style="width: 30%">
            <a class="kt-header__brand-logo" href="{{route('dashboard')}}">
                <img alt="Discovery" src="{{ URL::asset('image/discovery_logo1.png') }}" width="100%" class="kt-header__brand-logo-default" />
                <img alt="Discovery" src="{{ URL::asset('image/discovery_logo1.png') }}" width="100%" class="kt-header__brand-logo-sticky" />
            </a>
        </div>

        <!-- end:: Brand -->

        <!-- begin: Header Menu -->
        <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
        <div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
            <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
                <ul class="kt-menu__nav ">
                    <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel 
                        @if($ruta[0] == 'dashboard') kt-menu__item--open kt-menu__item--here @endif">
                        <a href="{{ route('dashboard') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Dashboard</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                    </li>
                    @permission('claro.masivos.category|claro.pymes.category')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @if(in_array($ruta[0], ['claro'])) 
                        kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Claro</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left" style="width: 220px">
                            <ul class="kt-menu__subnav">   
                                @permission('claro.masivos.category')               
                                <li class="kt-menu__item  kt-menu__item--submenu  @if(in_array($ruta[0], ['claro.masivos']) && in_array($ruta[1], ['index'])) 
                                    kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon"><i class="fa fa-cart-arrow-down"></i></span>
                                        <span class="kt-menu__link-text">Masivos</span><i class="kt-menu__hor-arrow la la-angle-right"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right" style="width: 220px">
                                        <ul class="kt-menu__subnav">   
                                            @permission('claro.masivos.reportes')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.masivos.reportesIndex') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.masivos.reportesIndex')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Reportes</span>
                                                </a>
                                            </li>                                        
                                            @endpermission  
                                            @permission('claro.masivos.auditoria')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.masivos.auditoriaIndex') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.masivos.auditoriaIndex') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Auditadas</span>
                                                </a>
                                            </li>                                    
                                            @endpermission
                                            @permission('claro.masivos.index')
                                            <li class="kt-menu__item @if(in_array($ruta[0], ['claro.masivos.index']) && in_array($ruta[1], ['masivos']) && !isset($ruta[2])) 
                                            kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.masivos.index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Registradas</span>
                                                </a>
                                            </li>         
                                            @endpermission                               
                                            @permission('claro.masivos.create')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.masivos.create') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.masivos.create')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Agregar</span>
                                                </a>
                                            </li>                                        
                                            @endpermission
                                        </ul>
                                    </div>
                                </li>
                                @endpermission 
                                @permission('claro.pymes.category')               
                                <li class="kt-menu__item  kt-menu__item--submenu  @if(in_array($ruta[0], ['claro.pymes']) && in_array($ruta[1], ['index'])) 
                                    kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon"><i class="fas fa-building"></i></span>
                                        <span class="kt-menu__link-text">Pymes</span><i class="kt-menu__hor-arrow la la-angle-right"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right" style="width: 220px">
                                        <ul class="kt-menu__subnav">  
                                            @permission('claro.pymes.reportes')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.pymes.reportesIndex') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.pymes.reportesIndex')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Reportes</span>
                                                </a>
                                            </li>                                        
                                            @endpermission     
                                            @permission('claro.pymes.auditoria')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.pymes.auditoriaIndex') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.pymes.auditoriaIndex') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Auditadas</span>
                                                </a>
                                            </li>                                    
                                            @endpermission
                                            @permission('claro.pymes.index')
                                            <li class="kt-menu__item @if(in_array($ruta[0], ['claro.pymes.index']) && in_array($ruta[1], ['pymes']) && !isset($ruta[2])) 
                                            kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.pymes.index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Registradas</span>
                                                </a>
                                            </li>         
                                            @endpermission                               
                                            @permission('claro.pymes.create')
                                            <li class="kt-menu__item @if($rutaMain == 'claro.pymes.create') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('claro.pymes.create')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Agregar</span>
                                                </a>
                                            </li>                                        
                                            @endpermission
                                        </ul>
                                    </div>
                                </li>
                                @endpermission 
                            </ul>
                        </div>
                    </li>                    
                    @endpermission     
                   @permission('bait.module|bait.reportes')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @if(in_array($ruta[0], ['bait'])) kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Bait </span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                           <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left" style="width: 220px">
                            <ul class="kt-menu__subnav"> 
                                <!--sub categoria dentro del menu-->
                                @permission('bait.ventas.create| bait.ventas.index')
                                <li class="kt-menu__item  kt-menu__item--submenu  @if(in_array($ruta[0], ['bait']) && in_array($ruta[1], ['index'])) 
                                    kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon"><i class="fa fa-cart-arrow-down"></i></span>
                                        <span class="kt-menu__link-text">Ventas</span><i class="kt-menu__hor-arrow la la-angle-right"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>

                                      <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right" style="width: 220px">
                                            <!--3re nivel categoria dentro del menu-->
                                        <ul class="kt-menu__subnav">  
                                            @permission('bait.ventas.create')                                           
                                            <li class="kt-menu__item @if($rutaMain == 'bait.create') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.create')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Agregar</span>
                                                </a>
                                            </li> 
                                             @endpermission
                                            @permission('bait.ventas.index')
                                            <li class="kt-menu__item @if($rutaMain == 'bait.index') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.index')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Registradas</span>
                                                </a>
                                            </li>
                                            @endpermission
                                             
                                        </ul>
                                    </div>
                                </li> 
                                @endpermission
                                @permission('bait.backoffice.index|bait.backoffice.postventas|bait.backoffice.uploadcm')
                                <li class="kt-menu__item  kt-menu__item--submenu  @if(in_array($ruta[0], ['bait']) && in_array($ruta[1], ['backoffice'])) 
                                    kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon"><i class="fa fa-cart-arrow-down"></i></span>
                                        <span class="kt-menu__link-text">Backoffice</span><i class="kt-menu__hor-arrow la la-angle-right"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                      <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right" style="width: 220px">
                                            <!--3re nivel categoria dentro del menu-->
                                        <ul class="kt-menu__subnav">  
                                            @permission('bait.backoffice.index')                                           
                                            <li class="kt-menu__item @if($rutaMain == 'bait.backoffice.index') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.backoffice.index')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Ingresar Intelix</span>
                                                </a>
                                            </li> 
                                            @endpermission
                                            @permission('bait.backoffice.postventas')                                           
                                            <li class="kt-menu__item @if($rutaMain == 'bait.backoffice.postventa') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.backoffice.postventa')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Post Venta</span>
                                                </a>
                                            </li> 
                                            @endpermission
                                            @permission('bait.backoffice.seguimientos')
                                            <li class="kt-menu__item @if($rutaMain == 'bait.uploads.seguimientos.index') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.uploads.seguimientos.index')}}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Seguimientos Masivos</span>
                                                </a>
                                            </li> 
                                            @endpermission
                                            @permission('bait.backoffice.uploadcm')   
                                            <li class="kt-menu__item @if($rutaMain == 'bait.uploads.concentra.index') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                                <a href="{{ route('bait.uploads.concentra.index')}}" class="kt-menu__link">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">CM Concentra</span>
                                                </a>
                                            </li>
                                            @endpermission  
                                        </ul>
                                    </div>
                                </li> 
                                @endpermission
                                @permission('bait.reportes')
                                <li class="kt-menu__item @if($rutaMain == 'bait.reportes.index') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true">
                                    <a href="{{ route('bait.reportes.index')}}" class="kt-menu__link ">
                                            <span class="kt-menu__link-icon"><i class="fa fa-download"></i></span>
                                        <span class="kt-menu__link-text">Reportes</span>
                                    </a>
                                </li>
                                @endpermission
                            </ul>
                        </div>
                    </li>   
                   @endpermission
                    @permission('configuracion.module')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel  @if(in_array($ruta[0], ['permisos','roles','user'])) kt-menu__item--open kt-menu__item--here @endif" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Configuraci&oacute;n</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--center" style="width:600px">
                            <div class="kt-menu__subnav">
                                <ul class="kt-menu__content">
                                    @permission('permissions.module|roles.module')
                                    <li class="kt-menu__item ">
                                        <h3 class="kt-menu__heading kt-menu__toggle"><span class="kt-menu__link-text"><i class="flaticon2-protected"></i>&nbsp;Privilegios</span></h3>
                                        <ul class="kt-menu__inner">
                                            @permission('permissions.view|permissions.edit|permissions.delete|permissions.create')
                                            <li class="kt-menu__item @if($rutaMain == 'permisos.list') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('permisos.list')}}" class="kt-menu__link"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Permisos</span></a></li>
                                            @endpermission
                                            @permission('roles.view|roles.edit|roles.delete|roles.create')
                                            <li class="kt-menu__item @if($rutaMain == 'roles.list') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('roles.list')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Roles</span></a></li>
                                            @endpermission
                                        </ul>
                                    </li>
                                    @endpermission
                                    @permission('users.module')
                                    <li class="kt-menu__item ">
                                        <h3 class="kt-menu__heading kt-menu__toggle"><span class="kt-menu__link-text"><i class="flaticon2-user"></i>&nbsp;Usuarios</span><i class="kt-menu__ver-arrow la la-angle-right"></i></h3>
                                        <ul class="kt-menu__inner">
                                            @permission('users.view|users.edit|users.delete')
                                            <li class="kt-menu__item @if($rutaMain == 'user.list') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('user.list')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Ver Usuarios</span></a></li>
                                            @endpermission
                                            @permission('users.create')
                                            <li class="kt-menu__item @if($rutaMain == 'user.create') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('user.create')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Agregar</span></a></li>
                                            @endpermission
                                            @permission('users.massive')
                                            <li class="kt-menu__item @if($rutaMain == 'user.massive') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('user.massive')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Carga Masiva</span></a></li>
                                            <li class="kt-menu__item @if($rutaMain == 'user.update.massive') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('user.update.massive')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Actualización Masiva</span></a></li>
                                            @endpermission
                                            @permission('users.report')
                                            <li class="kt-menu__item @if($rutaMain == 'user.report') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('user.report')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Reporte</span></a></li>
                                            @endpermission
                                        </ul>
                                    </li>
                                    @endpermission
                                    @permission('audit.module')
                                    <li class="kt-menu__item ">
                                        <h3 class="kt-menu__heading kt-menu__toggle"><span class="kt-menu__link-text"><i class="fa fa-database"></i>&nbsp;Auditoria</span><i class="kt-menu__ver-arrow la la-angle-right"></i></h3>
                                        <ul class="kt-menu__inner">
                                            @permission('audit.logs')
                                            <li class="kt-menu__item @if($rutaMain == 'activity') kt-menu__item--open kt-menu__item--here @endif" aria-haspopup="true"><a href="{{route('activity')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Logs</span></a></li>                                            
                                            @endpermission
                                        </ul>
                                    </li>
                                    @endpermission
                                </ul>
                            </div>
                        </div>
                    </li>
                    @endpermission
                </ul>
            </div>
        </div>
        <div class="kt-header__topbar kt-grid__item">           

            <!--begin: Notifications -->
            <div class="kt-header__topbar-item dropdown">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                    <span class="kt-header__topbar-icon kt-pulse kt-pulse--light">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                        </g>
                        </svg>

                                                                                        <!--<i class="flaticon2-bell-alarm-symbol"></i>-->
                        <span class="kt-pulse__ring"></span>
                    </span>

                                                                                <!--<span class="kt-badge kt-badge--light"></span>-->
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
                   <!-- <form>

                        <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b" style="background-image: url({{ URL::asset('assets/media/misc/bg-1.jpg') }})">
                            <h3 class="kt-head__title">
                                User Notifications
                                &nbsp;
                                <span class="btn btn-success btn-sm btn-bold btn-font-md">23 new</span>
                            </h3>
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Alerts</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events" role="tab" aria-selected="false">Events</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Logs</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                                <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-line-chart kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New order has been received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                2 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-box-1 kt-font-brand"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer is registered
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-chart2 kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Application has been approved
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-image-file kt-font-warning"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New file has been uploaded
                                            </div>
                                            <div class="kt-notification__item-time">
                                                5 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-drop kt-font-info"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New user feedback received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                8 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                System reboot has been successfully completed
                                            </div>
                                            <div class="kt-notification__item-time">
                                                12 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-favourite kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New order has been placed
                                            </div>
                                            <div class="kt-notification__item-time">
                                                15 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item kt-notification__item--read">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-safe kt-font-primary"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Company meeting canceled
                                            </div>
                                            <div class="kt-notification__item-time">
                                                19 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-psd kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New report has been received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                23 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon-download-1 kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Finance report has been generated
                                            </div>
                                            <div class="kt-notification__item-time">
                                                25 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon-security kt-font-warning"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer comment recieved
                                            </div>
                                            <div class="kt-notification__item-time">
                                                2 days ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-pie-chart kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer is registered
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 days ago
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                                <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-psd kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New report has been received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                23 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon-download-1 kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Finance report has been generated
                                            </div>
                                            <div class="kt-notification__item-time">
                                                25 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-line-chart kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New order has been received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                2 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-box-1 kt-font-brand"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer is registered
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-chart2 kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Application has been approved
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-image-file kt-font-warning"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New file has been uploaded
                                            </div>
                                            <div class="kt-notification__item-time">
                                                5 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-drop kt-font-info"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New user feedback received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                8 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                System reboot has been successfully completed
                                            </div>
                                            <div class="kt-notification__item-time">
                                                12 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-favourite kt-font-brand"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New order has been placed
                                            </div>
                                            <div class="kt-notification__item-time">
                                                15 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item kt-notification__item--read">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-safe kt-font-primary"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Company meeting canceled
                                            </div>
                                            <div class="kt-notification__item-time">
                                                19 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-psd kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New report has been received
                                            </div>
                                            <div class="kt-notification__item-time">
                                                23 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon-download-1 kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Finance report has been generated
                                            </div>
                                            <div class="kt-notification__item-time">
                                                25 hrs ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon-security kt-font-warning"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer comment recieved
                                            </div>
                                            <div class="kt-notification__item-time">
                                                2 days ago
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-pie-chart kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                New customer is registered
                                            </div>
                                            <div class="kt-notification__item-time">
                                                3 days ago
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                                <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                                    <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                        <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                            All caught up!
                                            <br>No new notifications.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>-->
                </div>
            </div>

            <!--end: Notifications -->

            <!--begin: User bar -->
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                    <span class="kt-header__topbar-welcome">Hola,</span>
                    <span class="kt-header__topbar-username">{{ ucfirst(strtolower($nombre))}}</span>
                    <span class="kt-header__topbar-icon"><b>{{ strtoupper(substr($nombre, 0, 1)) }}</b></span>
                    <img alt="Pic" src="{{URL::asset('assets/media/users/300_21.jpg')}}" class="kt-hidden" />
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

                    <!--begin: Head -->
                    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ URL::asset('image/6.jpg') }})">
                        <div class="kt-user-card__avatar">
                            <img src="{{URL::asset('image/profile4.jpg')}}" alt="Avatar">

                            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                            <!--<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ strtoupper(substr($nombre, 0, 1)) }}</span>-->
                        </div>
                        <div class="kt-user-card__name">
                            {{ ucwords(strtolower(Auth::user()->nombre_apellido))}}
                        </div>
                        <!--<div class="kt-user-card__badge">
                            <span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
                        </div>-->
                    </div>

                    <!--end: Head -->

                    <!--begin: Navigation -->
                    <div class="kt-notification">
                        <a href="{{ route('user.profile') }}" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-calendar-3 kt-font-success"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    Mi Perfil
                                </div>
                                <div class="kt-notification__item-time">
                                    Informaci&oacute;n de Usuario y Contrase&ntilde;a
                                </div>
                            </div>
                        </a>
                        <!--<a href="custom/apps/user/profile-3&demo=demo4.html" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-mail kt-font-warning"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    My Messages
                                </div>
                                <div class="kt-notification__item-time">
                                    Inbox and tasks
                                </div>
                            </div>
                        </a>
                        <a href="custom/apps/user/profile-2&demo=demo4.html" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-rocket-1 kt-font-danger"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    My Activities
                                </div>
                                <div class="kt-notification__item-time">
                                    Logs and notifications
                                </div>
                            </div>
                        </a>
                        <a href="custom/apps/user/profile-3&demo=demo4.html" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-hourglass kt-font-brand"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    My Tasks
                                </div>
                                <div class="kt-notification__item-time">
                                    latest tasks and projects
                                </div>
                            </div>
                        </a>
                        <a href="custom/apps/user/profile-1/overview&demo=demo4.html" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-cardiogram kt-font-warning"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    Billing
                                </div>
                                <div class="kt-notification__item-time">
                                    billing & statements <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">2 pending</span>
                                </div>
                            </div>
                        </a>-->
                        <div class="kt-notification__custom kt-space-between">
                            <a href="{{route('logout')}}" class="btn btn-label btn-label-brand btn-sm btn-bold">Cerrar Sesi&oacute;n</a>
                        </div>
                    </div>

                    <!--end: Navigation -->
                </div>
            </div>

            <!--end: User bar -->
        </div>

        <!-- end:: Header Topbar -->
    </div>
</div>

