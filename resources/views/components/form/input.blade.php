<label for="{{$name}}" class="col-sm-2 col-form-label">{{$label}}</label>
<div class="col-sm-5">
 <input type="{{$type}}" class="form-control" name="{{$name}}" id="{{$name}}" value="{{$value}}">
</div>
@error($name)
<div class="col-sm-5 text-danger">
    {{ $message }}
</div>
@enderror  