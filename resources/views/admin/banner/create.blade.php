<x-admin.layout>
   <x-slot:title>Add Home Page Banner</x-slot:title>
   <section class="section">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Banner</h5>
            <form action="{{ route('banner.store') }}" method="post">
                @csrf
                <div class="row mb-3">
                    @error('banner_image')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" name="banner_image">
                    </div>
                </div>
                <div class="row mb-3">
                    @error('banner_link')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Link To</label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Select Post" name="banner_link">                      
                        @foreach($posts AS $post) 
                          <option value="{{ $post->id }}">{{ $post->title }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                    @error('banner_text')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Text</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" style="height: 60px" name="banner_text"></textarea>
                  </div>
                </div>
                <div class="row mb-3">                  
                    <div class="col-sm-10">                  
                    <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   </section>
</x-admin.layout>