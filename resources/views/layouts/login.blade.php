<!DOCTYPE html>
<html lang="en">

    <!-- begin::Head -->
    <head>
        <base href="../../../">
        <meta charset="utf-8" />
        <title>Discovery | @yield('title')</title>
        <meta name="description" content="Directa Group - Portabilidad">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Deivis Henriquez deivis(at)vozip.net"/>
        <meta name="copyright" content="Powered by Deivis Henriquez. <?php echo date('Y'); ?>">

        <!--begin::Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

        <!--end::Fonts -->

        <!--begin::Page Custom Styles(used by this page) -->
        <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/pages/login/login-3.css') }}">
        <!--end::Page Custom Styles -->

        <!--begin::Global Theme Styles(used by all pages) -->

        <!--begin:: Vendor Plugins -->
       <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/general/perfect-scrollbar/css/perfect-scrollbar.css') }}">
       <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/general/tether/dist/css/tether.css') }}">
        <!--end:: Vendor Plugins -->
        <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}">
        <!--begin:: Vendor Plugins for custom pages -->
        <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/custom/plugins/jquery-ui/jquery-ui.min.css') }}">

        

        <!--end:: Vendor Plugins for custom pages -->

        <!--end::Global Theme Styles -->
        @stack('styles')
        <!--begin::Layout Skins(used by all pages) -->

        <!--end::Layout Skins -->
        <link rel="shortcut icon" href="{{ URL::asset('image/icon.png') }}" />
    </head>

    <!-- end::Head -->

    <!-- begin::Body -->
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">
                @yield('content')
		

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#22b9ff",
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
        <script src="{{ asset('assets/plugins/general/jquery/dist/jquery.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/popper.js/dist/umd/popper.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/js-cookie/src/js.cookie.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/sticky-js/dist/sticky.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/jquery-form/dist/jquery.form.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/jquery-validation/dist/jquery.validate.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/jquery-validation/dist/additional-methods.js') }}"></script>
        <script src="{{ asset('assets/plugins/general/js/global/integration/plugins/jquery-validation.init.js') }}"></script>
        <!--end:: Vendor Plugins -->
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <!--begin:: Vendor Plugins for custom pages -->
        <script src="{{ asset('assets/plugins/custom/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!--end:: Vendor Plugins for custom pages -->

        <!--end:: Vendor Plugins for custom pages -->

        <!--end::Global Theme Bundle -->

        <!--begin::Page Scripts(used by this page) -->
        <!--end::Page Scripts -->
        @stack('scripts')  
    </body>

    <!-- end::Body -->
</html>