<x-admin.layout> 
  <x-slot:title>
    Add Tag
  </x-slot> 
  @push('scripts')  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script>
    $(function() {
      $.fn.deleteRow = function( id ) {                   ;
        if( confirm( 'Are you sure you want to delete it?' ) )
        {           
          $('#delete_'+id).submit();
        }
      }
    });
  </script>    
  @endpush  
  @push('css')
  <style type="text/css">
    #name{ height: 150px; }
  </style> 
  @endpush
  <section class="section">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Tag</h5>
            <x-admin.form.alert/>
            <form method="post" action="{{ route('admin.tag.store') }}" id="tag-add-form">   
              @csrf          
              <div class="row mb-3">                                                                          
                <x-form.textarea id="name" name="name" label="Name" placeholder="Separate multiple tag with comma" />                                       
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
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6" id="page-start">         
        <div class="card">
          <div class="card-body">           
            <h5 class="card-title">Tag List<div class="float-end fs-8 text-success" id="header-message"></div></h5>   
            <div class="container">{{ $tags->links() }}</div>
            <div class="mb-3 mt-1">
              <form method="get" action="{{ route('tag.add') }}">
                <div class="input-group"> 
                  <a href="{{ route('tag.add') }}" class="btn btn-primary" role="button">Reset</a>                                 
                  <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-addon" name="search" value="{{ $search }}" />
                  <button type="submit" class="btn btn-primary">Search</button>                                
                </div>              
              </form>
            </div>
            <!--div class="form-outline">
              <input type="search" id="form1" class="form-control" placeholder="Type query" aria-label="Search" />
            </div-->        
              <table class="table">
                <thead>                          
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>                                            
                    <th scope="col" class="text-center">Status</th>                         
                    <th scope="col" class="text-center">Post Count</th>                                               
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="tag-list">                   
                  @php($count = 0)              
                  @foreach($tags AS $tag)            
                  <tr id="{{ $tag->id }}" class="tag-name">                      
                    <th scope="row">{{ ++$count }}</th>
                    <td>{{ $tag->name }}</td>                                                                 
                    <td class="text-center">
                      @if( $tag->status )
                      <span class="badge bg-success">Active</span>
                      @else
                      <span class="badge bg-warning text-dark">Inactive</span>
                      @endif
                    </td>
                    <td class="text-center">{{ $tag->posts_count }}</td>                                        
                    <td class="text-center">
                      <a href="{{ route('admin.tag.edit', ['id' => $tag->id]) }}" class="edit-category link-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pen"></i></a>
                      <form method="post" action="{{ route('admin.tag.delete' , ['id' => $tag->id]) }}" id="delete_{{$tag->id}}" style="display: inline;">
                        @csrf
                        <a href="javascript:void(0)" class="delete link-secondary" data-toggle="tooltip" data-placement="top" title="Delete">
                          <i class="bi bi-trash" onclick="$.fn.deleteRow({{$tag->id}});"></i>
                        </a>
                      </form>                            
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