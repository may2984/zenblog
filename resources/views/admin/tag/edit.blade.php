<x-admin.layout>
  <x-slot:title>Edit Tag</x-slot>
  <div class="pagetitle">
    <h1>Form Elements</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>        
        <li class="breadcrumb-item active">Tag</li>
      </ol>
    </nav>
  </div>
  
  <section class="section">
    <div class="row">
      <div class="col-lg-6">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit Tag</h5>
            <x-admin.form.alert/>
            <form method="post" action="{{ route('admin.tag.modify') }}" id="tag-add-form">   
              @csrf          
              <div class="row mb-3">                
                <x-form.input type="text" name="name" label="Name" value="{{ $tag->name }}" />                                             
              </div>            
              <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="gridRadios1" value="1" {{$tag->status == '1' ? 'checked' : ''}}>
                    <label class="form-check-label" for="gridRadios1">
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="gridRadios2" value="0" {{$tag->status == '0' ? 'checked' : ''}}>
                    <label class="form-check-label" for="gridRadios2">
                      Inactive
                    </label>
                  </div>
                </div>
              </fieldset>             

              <div class="row mb-3">                  
                <div class="col-sm-10">     
                  <input type="hidden" value="{{ $tag->id }}" name="id">             
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>   
    </div>
  </section>
</x-layout>