<script>
document.addEventListener("DOMContentLoaded", function() {
    toastr.options = {
    "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "8000",
            "hideDuration": "90000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
    }; 
});
</script>

@if(Session::has('success'))
<script>
    toastr.success("{{Session::get('success')}}");
</script>
@endif
@if(Session::has('error'))
<script>
toastr.error("{{Session::get('error')}}");
</script>
@endif
@if(Session::has('warning'))
<script>
toastr.warning("{{Session::get('warning')}}");
</script>
@endif
@if(count($errors) > 0)

<script>    
        var msg = "<ul style='text-align: left;'>";
        @foreach($errors->all() as $error)
        msg += "<li>{!!$error!!}</li>"
        @endforeach
        msg += "</ul>";
        toastr.error(msg);
                    
</script>
@endif

@if(session('verified'))
<script>
    toastr.info("{{trans('client.verifiedmsg')}}");
</script>
@endif

@if(session('status'))
<script>
    toastr.info("{{ session('status') }} ");
</script>
@endif


