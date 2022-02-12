@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger fz-1-1 align-items-center">
            <div>
                        <span class="alert-icon text-danger">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        </span>
            </div>
            <div class="text-center">{{ $error }}</div>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif

@if(session('error'))
    <div
        class="alert alert-danger spacing-35 fz-1-1 align-items-center"
        role="alert"
    >
        <div>
            <span class="alert-icon text-danger">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            </span>
        </div>
        <div class="text-center">
            {!! session('error') !!}
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
