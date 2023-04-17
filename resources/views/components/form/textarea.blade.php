<label for="{{$name}}<" class="col-sm-2 col-form-label">{{$label}}</label>
<div class="col-sm-6">
 <textarea class="form-control" name="{{$name}}" id="{{$name}}" placeholder="{{$placeholder}}">{{ old($name) }}</textarea> 
</div>
@error($name)
<div class="col-sm-4 text-danger">
    {{ $message }}
</div>
@enderror