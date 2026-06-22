<!DOCTYPE html>
<html lang="es">
    <!-- begin::Head -->
    <head>
        <base href="">
        <meta charset="utf-8" />
        <title>Discovery | @yield('title')</title>
        <meta name="description" content="Directa Group - Discovery">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Deivis Henriquez deivis(at)vozip.net"/>
        <meta name="copyright" content="Powered by Deivis Henriquez. Ft. Braian Jaimes <?php echo date('Y'); ?>">

        <!--begin::Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

        <!--end::Fonts -->

        <!--begin::Page Vendors Styles(used by this page) -->

        <!--end::Page Vendors Styles -->

        <!--begin::Global Theme Styles(used by all pages) -->

        <!--begin:: Vendor Plugins -->
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/perfect-scrollbar/css/perfect-scrollbar.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/tether/dist/css/tether.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-timepicker/css/bootstrap-timepicker.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-daterangepicker/daterangepicker.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-select/dist/css/bootstrap-select.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/select2/dist/css/select2.css') }}">
        
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/nouislider/distribute/nouislider.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/owl.carousel/dist/assets/owl.carousel.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/owl.carousel/dist/assets/owl.theme.default.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/dropzone/dist/dropzone.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/quill/dist/quill.snow.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/@yaireo/tagify/dist/tagify.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/summernote/dist/summernote.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/bootstrap-markdown/css/bootstrap-markdown.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/animate.css/animate.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/toastr/build/toastr.css')}}">
        <!--
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/dual-listbox/dist/dual-listbox.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/morris.js/morris.css')}}">
        -->
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/sweetalert2/dist/sweetalert2.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/socicon/css/socicon.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/plugins/line-awesome/css/line-awesome.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/plugins/flaticon/flaticon.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/plugins/flaticon2/flaticon.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}">

        <!--end:: Vendor Plugins -->
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/css/style.bundle.css')}}">

        <!--begin:: Vendor Plugins for custom pages -->
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/plugins/jquery-ui/jquery-ui.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}">

        <!-----
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/@fullcalendar/core/main.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/@fullcalendar/daygrid/main.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/@fullcalendar/list/main.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/@fullcalendar/timegrid/main.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/jstree/dist/themes/default/style.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/jqvmap/dist/jqvmap.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/plugins/custom/uppy/dist/uppy.min.css')}}">
        -->

        <!--end:: Vendor Plugins for custom pages -->

        <!--end::Global Theme Styles -->
        @stack('styles')
        <!--begin::Layout Skins(used by all pages) -->

        <!--end::Layout Skins -->
        <link rel="shortcut icon" href="{{ URL::asset('image/icon.png') }}" />
    </head>

    <!-- end::Head -->

    <!-- begin::Body -->
    <body style="background-image: url({{ URL::asset('image/1.jpg') }}); background-position: center top; background-size: 100% 350px;background-repeat: no-repeat;" class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading">

        <!-- begin::Page loader -->
        @include('partials.header_mobile')
        <div class="kt-grid kt-grid--hor kt-grid--root">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    <!-- end::Page Loader -->
                    @include('partials.header')
                    <div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
                        <div class="kt-content kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                            @yield('content')
                        </div>
                    </div>
                    @include('partials.footer')                 
                </div>
            </div>
        </div>
        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>
            var KTAppOptions = {
                "colors": {
                    "state": {
                        "brand": "#366cf3",
                        "light": "#ffffff",
                        "dark": "#282a3c",
                        "primary": "#5867dd",
                        "success": "#34bfa3",
                        "info": "#36a3f7",
                        "warning": "#ffb822",
                        "danger": "#fd3995"
                    },
                    "base": {
                        "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                        "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                    }
                }
            };
        </script>

        <!-- end::Global Config -->

        <!--begin::Global Theme Bundle(used by all pages) -->

        <!--begin:: Vendor Plugins -->
        <script src="{{asset("assets/plugins/general/jquery/dist/jquery.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/popper.js/dist/umd/popper.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap/dist/js/bootstrap.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js-cookie/src/js.cookie.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/moment/min/moment.min.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/perfect-scrollbar/dist/perfect-scrollbar.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/sticky-js/dist/sticky.min.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/jquery-form/dist/jquery.form.min.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/bootstrap-datepicker.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/bootstrap-timepicker.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap-daterangepicker/daterangepicker.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/bootstrap-maxlength/src/bootstrap-maxlength.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/plugins/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/bootstrap-switch/dist/js/bootstrap-switch.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/bootstrap-switch.init.js")}}"  type="text/javascript"></script>
        <script src="{{asset("assets/plugins/general/select2/dist/js/select2.full.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/typeahead.js/dist/typeahead.bundle.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/handlebars/dist/handlebars.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/inputmask/dist/jquery.inputmask.bundle.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/inputmask/dist/inputmask/inputmask.date.extensions.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/nouislider/distribute/nouislider.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/owl.carousel/dist/owl.carousel.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/autosize/dist/autosize.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/clipboard/dist/clipboard.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/dropzone/dist/dropzone.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/dropzone.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/quill/dist/quill.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/@yaireo/tagify/dist/tagify.polyfills.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/@yaireo/tagify/dist/tagify.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/summernote/dist/summernote.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/markdown/lib/markdown.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap-markdown/js/bootstrap-markdown.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/bootstrap-markdown.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/bootstrap-notify/bootstrap-notify.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/bootstrap-notify.init.js")}}"  type="text/javascript"></script>
        <script src="{{asset("assets/plugins/general/jquery-validation/dist/jquery.validate.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/jquery-validation/dist/additional-methods.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/jquery-validation.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset('assets/plugins/general/jquery-validation/localization/messages_'.env('APP_LOCALE').'.js')}}"></script>    
        <script src="{{asset("assets/plugins/general/toastr/build/toastr.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/dual-listbox/dist/dual-listbox.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/raphael/raphael.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/morris.js/morris.js")}}"  type="text/javascript"></script>            
        <script src="{{asset("assets/plugins/general/plugins/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/plugins/jquery-idletimer/idle-timer.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/waypoints/lib/jquery.waypoints.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/counterup/jquery.counterup.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/es6-promise-polyfill/promise.min.js")}}"  type="text/javascript"></script>   
        <script src="{{asset("assets/plugins/general/sweetalert2/dist/sweetalert2.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/js/global/integration/plugins/sweetalert2.init.js")}}"  type="text/javascript"></script>    
      
        <script src="{{asset("assets/plugins/general/jquery.repeater/src/jquery.input.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/jquery.repeater/src/repeater.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/general/dompurify/dist/purify.js")}}"  type="text/javascript"></script>    

        <script src="{{asset("assets/js/scripts.bundle.js")}}"  type="text/javascript"></script>    

        <script src="{{asset("assets/plugins/custom/plugins/jquery-ui/jquery-ui.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js")}}"  type="text/javascript"></script>    

        <script src="{{asset("assets/plugins/custom/@fullcalendar/core/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/@fullcalendar/daygrid/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/@fullcalendar/google-calendar/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/@fullcalendar/interaction/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/@fullcalendar/list/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/@fullcalendar/timegrid/main.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/gmaps/gmaps.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/dist/es5/jquery.flot.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.resize.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.categories.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.pie.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.stack.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.crosshair.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/flot/source/jquery.flot.axislabels.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net/js/jquery.dataTables.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-bs4/js/dataTables.bootstrap4.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/js/global/integration/plugins/datatables.init.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-autofill/js/dataTables.autoFill.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jszip/dist/jszip.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/pdfmake/build/pdfmake.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/pdfmake/build/vfs_fonts.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/dataTables.buttons.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.colVis.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.flash.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.html5.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-buttons/js/buttons.print.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-colreorder/js/dataTables.colReorder.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-keytable/js/dataTables.keyTable.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-responsive/js/dataTables.responsive.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-rowgroup/js/dataTables.rowGroup.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-rowreorder/js/dataTables.rowReorder.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-scroller/js/dataTables.scroller.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/datatables.net-select/js/dataTables.select.min.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jstree/dist/jstree.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/jquery.vmap.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/maps/jquery.vmap.world.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/maps/jquery.vmap.russia.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/maps/jquery.vmap.usa.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/maps/jquery.vmap.germany.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/jqvmap/dist/maps/jquery.vmap.europe.js")}}"  type="text/javascript"></script>    
        <script src="{{asset("assets/plugins/custom/uppy/dist/uppy.min.js")}}"  type="text/javascript"></script>
          
        <script src="{{asset("assets/js/pages/dashboard.js")}}"  type="text/javascript"></script> 
        
        @stack('scripts') 
        @if(Route::currentRouteName() != 'user.massive')
            @include('message.notification')
        @endif
    </body>
</html>