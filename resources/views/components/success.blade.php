@if(session('status'))
    <div class="alert alert-success fz-1-1 align-items-center" role="alert">
        <div>
            <span class="alert-icon text-success">
                <i class="fa fa-bell" aria-hidden="true"></i>
            </span>
        </div>
        <div class="text-center">
            {{ session('status') }}
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
