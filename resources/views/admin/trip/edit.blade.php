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
            <h5 class="card-title" id="form-title">Edit {{$label}}</h5>

            <div class="row mb-3">
              @if(Session::has('success'))              
              <div class="float-end fs-8 text-success" id="success-message">Banner added successfully</div>
              @endif
              @if(Session::has('error'))
              <div class="float-end fs-8 text-danger" id="error-message">Error! try again</div>
              @endif
            </div>       

            <form action="{{ route('trip.update', ['trip' => $data->id ]) }}" method="POST">
                @csrf                                
                @method('PUT')
                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Name</label>                    
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="name" value="{{ $data->name }}">                    
                    </div>
                </div>               
                <div class="row mb-3">                  
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   </section>
</x-admin.layout>