<x-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
  $(document).ready(function(){

    $('#add').click(function(e){      
      e.preventDefault();
      $.ajax({   
        type: 'POST',        
        url : "{{ route('admin.blog.category.store') }}",           
        data: $('form').serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),          
        },        
        success: function( data ) {
          $('#category-list').append(data);
          $('#category-add-form').trigger("reset");
        },
        error: function( data ) {
          alert( data )
        }
      });
    })

    $(".edit-category").click(function() {

      var url = $(this).attr("url");

      $.ajax({
        type: 'GET',
        url: url,
        success: function( data ){
          var category = $.parseJSON( data );   
          $('#name').val(category.name);
          $('#description').val(category.description);
          $('#id').val(category.id);
        },
        error: function( data ){
          alert( 'error' )
        }
      });
    });

    $(".delete-category").click(function() {
      
      if(confirm('Are you sure you want to delete it?'))
      {
        var url = $(this).attr("url");
        $.ajax({
          type: 'GET',
          url: url,     
          success: function( id ){
            if( parseInt( id ) > 0 )
            {
              $('#data-row-'+id).html("");
            }
            else
            {
              alert('Error! try again')
            }
          },
          error: function( data ){
            alert( 'error' )
          }
        })
      }
    })

  });
</script>
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
              <h5 class="card-title">Add Blog Category</h5>
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
              <form method="post" action="{{ route('admin.blog.category.store') }}" id="category-add-form">
               <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                  </div>
                  @error('name')
                  <div class="col-sm-5 text-danger">
                    {{ $message }}
                  </div>
                  @enderror
                </div>  
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-5">
                    <textarea class="form-control" name="description" id="description" style="height: 100px"></textarea>
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
                      <input class="form-check-input" type="radio" name="status" id="gridRadios1" value="1" checked>
                      <label class="form-check-label" for="gridRadios1">
                        Active
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="status" id="gridRadios2" value="0">
                      <label class="form-check-label" for="gridRadios2">
                        Inactive
                      </label>
                    </div>
                  </div>
                </fieldset>             

                <div class="row mb-3">                  
                  <div class="col-sm-10">
                    <input type="thidden" id="id" value="0">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="submit" class="btn btn-primary" id="add">Save via Ajax</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-6">         
          <div class="card">
            <div class="card-body">           
              <h5 class="card-title">Category List</h5>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>                      
                      <th scope="col">Number of Posts</th>
                      <th scope="col">Date Created</th>     
                      <th scope="col">Acion</th>                   
                    </tr>
                  </thead>
                  <tbody id="category-list">     
                    @php($count = 0)              
                    @foreach($blogCategory AS $category)                    
                    <tr id="data-row-{{ $category->id }}">
                      <th scope="row">{{ ++$count }}</th>
                      <td>{{ $category->name }}</td>                     
                      <td>$</td>
                      <td>{{ $category->created_at->format('d-m-Y H:i') }}</td>
                      <td>
                        <a href="javascript:void(0)" url="{{ route('admin.blog.category.edit' , $category->id) }}" class="edit-category"><i class="bi bi-pen"></i></a>
                        <a href="javascript:void(0)" url="{{ route('admin.blog.category.dalete' , $category->id) }}" class="delete-category"><i class="bi bi-trash"></i></a>    
                      </td>
                    </tr>
                    @endforeach                    
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</x-layout>