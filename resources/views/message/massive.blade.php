@if(count($errors) > 0)
<div class="row">
    <div class="col">
        <div class="alert alert-outline-danger alert-elevate fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning kt-font-danger"></i></div>
            <div class="alert-text">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{!!$error!!}</li>
                    @endforeach
                </ul>
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if(Session::has('successImport'))
<div class="row">
    <div class="col">
        <div class="alert alert-outline-dark alert-elevate fade show" role="alert">
            <div class="alert-icon"><i class="flaticon2-checkmark kt-font-success"></i></div>
            <div class="alert-text">
                {{Session::get('successImport')}}
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif


