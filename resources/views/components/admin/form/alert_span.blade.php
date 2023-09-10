@if(Session::has('error'))
<div class="alert alert-danger col">
    {{ Session::get('error') }}
</div>
@endif
@if(Session::has('success'))
<div class="alert alert-success col"> 
    {{ Session::get('success') }}
</div>
@endif