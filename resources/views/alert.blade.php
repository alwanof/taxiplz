@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(Session::has('alert'))
<div class="alert alert-success alert-dismissible fade show my-2" role="alert">
    {{ Session::get('alert') }}
     @php
        Session::forget('alert');
    @endphp
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(Session::has('danger'))
<div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
    {{ Session::get('danger') }}
     @php
        Session::forget('danger');
    @endphp
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(Session::has('warning'))
<div class="alert alert-warning alert-dismissible fade show my-2" role="alert">
    {{ Session::get('warning') }}
     @php
        Session::forget('warning');
    @endphp
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif



