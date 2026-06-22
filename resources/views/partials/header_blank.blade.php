<?php
if (Auth::check()) {
    $arrName = explode(" ", Auth::user()->nombre_apellido);
    $nombre = $arrName[0];
}
?>
<div id="kt_header" class="kt-header  kt-header--fixed " data-ktheader-minimize="on">
    <div class="kt-container ">

        <!-- begin:: Brand -->
        <div class="kt-header__brand kt-grid__item" id="kt_header_brand" style="width: 30%">
            <a class="kt-header__brand-logo" href="{{route('dashboard')}}">
                <img alt="Discovery" src="{{ URL::asset('image/discovery_logo1.png') }}" width="80%" class="kt-header__brand-logo-default" />
                <img alt="Discovery" src="{{ URL::asset('image/discovery_logo1.png') }}" width="80%" class="kt-header__brand-logo-sticky" />
            </a>
        </div>

        <!-- end:: Brand -->

        <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar kt-grid__item">           


            <!--begin: User bar -->
            @if(Auth::check())
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                    <span class="kt-header__topbar-welcome">Hola,</span>
                    <span class="kt-header__topbar-username">{{ ucfirst(strtolower($nombre))}}</span>
                    <span class="kt-header__topbar-icon"><b>{{ strtoupper(substr($nombre, 0, 1)) }}</b></span>
                    <img alt="Pic" src="{{ URL::asset('assets/media/users/300_21.jpg') }}" class="kt-hidden" />
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

                    <!--begin: Head -->
                    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ URL::asset('image/6.jpg') }})">
                        <div class="kt-user-card__avatar">
                            <img src="{{URL::asset('image/profile4.jpg')}}" alt="Avatar">

                        </div>
                        <div class="kt-user-card__name">                            
                            {{ ucwords(strtolower(Auth::user()->nombre_apellido))}}
                        </div>
                    </div>

                    <!--end: Head -->

                    <!--begin: Navigation -->
                    <div class="kt-notification">

                        <div class="kt-notification__custom kt-space-between">
                            <a href="{{route('logout')}}" class="btn btn-label btn-label-brand btn-sm btn-bold">Cerrar Sesi&oacute;n</a>
                        </div>
                    </div>

                    <!--end: Navigation -->
                </div>
            </div>
            @endif
            <!--end: User bar -->
        </div>

        <!-- end:: Header Topbar -->
    </div>
</div>

