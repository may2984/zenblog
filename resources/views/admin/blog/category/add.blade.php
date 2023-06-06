<x-admin.layout>
  <x-slot:title>  
    Add Category
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-ui-sortable@1.0.0/jquery-ui.min.js"></script>
  
  <script>
    $(document).ready(function(){
      $('#category-list').sortable({
        appendTo: document.body,
        axis: "y",
        cancel: "a,button",
        //containment: "parent",
        cursor: "move",
        handle: ".handle",
        revert: false,
        classes: {
          "ui-sortable": "highlight",
        }
      });

      $( "table tbody" ).sortable( {
        update: function( event, ui ) {

          categoryIdInOrder = {};

          $(this).children().each(function(index) {
            $(this).find('th').first().html(index + 1)   
            categoryIdInOrder[ $(this).attr('id') ] = index+1;            
          });

          $.ajax({
            method: "POST",
            url: "{{ route('admin.blog.category.sort') }}",      
            data: 'sorted_ids=' + JSON.stringify(categoryIdInOrder)
          })
          .done(function( msg ) {
            $("#header-message").html( msg );
            setTimeout( function() {
              $("#header-message").fadeOut();
            }, 2000)
          });
        }
      });
    })
  </script>

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
              @csrf          
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
                <label for="inputText" class="col-sm-2 col-form-label">URL</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="url" id="url" value="{{ old('url') }}">
                </div>
                @error('url')
                <div class="col-sm-5 text-danger">
                  {{ $message }}
                </div>
                @enderror
              </div> 
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-5">
                  <textarea class="form-control" name="description" id="description" style="height: 100px">{{ old('description') }}</textarea>
                </div>                  
                @error('description')
                <div class="col-sm-5 text-danger">
                  {{ $message }}
                </div>
                @enderror                  
              </div>
              <div>
                
              </div>
              <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="1" @checked( old('status') === null || old('status') == '1' ) />
                    <label class="form-check-label" for="gridRadios1">
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="0" @checked( old('status') == '0' ) />
                    <label class="form-check-label" for="gridRadios2">
                      Inactive
                    </label>
                  </div>
                </div>
              </fieldset>             

              <div class="row mb-3">                  
                <div class="col-sm-10">                  
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6">         
        <div class="card">
          <div class="card-body">           
            <h5 class="card-title">Category List<div class="float-end fs-8 text-success" id="header-message"></div></h5>
              <form id="category-list-form">
                <table class="table">
                  <thead>                
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>                      
                      <th scope="col">URL</th>  
                      <th scope="col" class="text-center">Status</th>                         
                      <th scope="col">Post Count</th>                         
                      <th scope="col" class="text-center">Order</th> 
                      <th scope="col" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody id="category-list">                   
                    @php($count = 0)              
                    @foreach($blogCategory AS $category)                    
                    <tr id="{{ $category->id }}" class="w">                      
                      <th scope="row">{{ ++$count }}</th>
                      <td>{{ $category->name }}</td>                                         
                      <td>{{ $category->url }}</td>    
                      <td class="text-center">
                        @if( $category->status )
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-warning text-dark">Inactive</span>
                        @endif
                      </td>
                      <td class="text-center">{{ $category->posts_count }}</td>                  
                      <td class="text-center handle" data-toggle="tooltip" data-placement="top" title="Order"><i class="bi bi-arrows-move"></i></td> 
                      <td class="text-center">
                        <a href="{{ route('admin.blog.category.edit' , ['id' => $category->id]) }}" class="edit-category link-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pen"></i></a>
                        <form method="post" action="{{ route('admin.blog.category.dalete' , ['id' => $category->id]) }}" name="pages_delete_{{$category->id}}" style="display: inline">
                          @csrf
                          <a href="javascript:void(0)" class="delete-category link-secondary" data-toggle="tooltip" data-placement="top" title="Delete">
                            <i class="bi bi-trash" data-confirm-message="Are you sure you want to delete it?" onclick="if (confirm(this.dataset.confirmMessage)) { document.pages_delete_{{$category->id}}.submit(); } return false; return false;"></i>
                          </a>
                        </form>                            
                      </td>                    
                    </tr>
                    @endforeach                    
                  </tbody>
                </table>
              </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layout>