@push('css')
  <style type="text/css">
      .bi-trash3:hover, .bi-pen-fill:hover { cursor: pointer }
  </style>  
  <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="{{ asset('backend/assets/js/jquery.guillotine.min.js') }}"></script>
<script>
    $(function(){     

      jQuery.ajaxSetup({
          beforeSend: function() {
              
          },                
      });
    
    });

</script>
@endpush

<x-admin.layout>
   <x-slot:title>Add Banner</x-slot:title>
   <section class="section">
    <div class="row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title" id="form-title">Edit Banner</h5>

            <div class="row mb-3">
              @if(Session::has('success'))              
              <div class="float-end fs-8 text-success" id="success-message">Banner added successfully</div>
              @endif
              @if(Session::has('error'))
              <div class="float-end fs-8 text-danger" id="error-message">Error! try again</div>
              @endif
            </div>
            
            <div class="row mb-3">
                @error('banner_image')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>                    
                <div class="col-sm-10">@include('admin.banner.dropzone')</div>
            </div>

            <form action="{{ route('banner.update', ['banner' => $banner->id ]) }}" method="POST">
                @csrf                                
                @method('PUT')
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Link To</label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Select Post" name="post_id">                      
                        @foreach($posts AS $post) 
                          <option value="{{ $post->id }}" @selected($post->id == $banner->post_id)>{{ $post->title }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Heading</label>                    
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="banner_heading" name="banner_heading" value="{{ $banner->banner_heading }}">                    
                    </div>
                </div>
                <div class="row mb-3">
                    @error('banner_text')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Text</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" style="height: 60px" name="banner_text">{{ $banner->banner_text }}</textarea>
                  </div>
                </div>
                <div class="row mb-3">                  
                    <div class="col-sm-10">
                      <input type="hidden" name="banner_image" id="banner_image">
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