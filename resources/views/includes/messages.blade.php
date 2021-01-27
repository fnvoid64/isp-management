@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show my-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {!! session()->get('message') !!}
    </div>
@endif
