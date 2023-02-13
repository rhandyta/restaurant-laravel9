@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible show fade">
        {{ session()->get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
