<x-admin.layout>
  <x-slot:title>
    Edit Category
  </x-slot>
  <div class="pagetitle">
    <h1>Form Elements</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
        <li class="breadcrumb-item">Forms</li>
        <li class="breadcrumb-item active">Elements</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-6">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit Blog Category</h5>
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
            <form method="post" action="{{ route('admin.blog.category.edit.save') }}" id="category-edit-form">
              @csrf
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="name" id="name" value="{{ $blogCategory->name }}">
                </div>
                @error('name')
                <div class="col-sm-5 text-danger">
                  {{ $message }}
                </div>
                @enderror
              </div>  
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">URL</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="url" id="url" value="{{ $blogCategory->url }}">
                </div>
                @error('name')
                <div class="col-sm-5 text-danger">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-5">
                  <textarea class="form-control" name="description" id="description" style="height: 100px">{{ $blogCategory->description }}</textarea>
                </div>                  
                @error('description')
                <div class="col-sm-5 text-danger">
                  {{ $message }}
                </div>
                @enderror                  
              </div>
              <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="gridRadios1" value="1" @if($blogCategory->status == '1') checked @endif>
                    <label class="form-check-label" for="gridRadios1">Active</label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="gridRadios2" value="0" @if($blogCategory->status == '0') checked @endif>
                    <label class="form-check-label" for="gridRadios2">Inactive</label>
                  </div>
                </div>
              </fieldset>             

              <div class="row mb-3">                  
                <div class="col-sm-10"> 
                  <input type="hidden" value="{{ $blogCategory->id }}" name="id">
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