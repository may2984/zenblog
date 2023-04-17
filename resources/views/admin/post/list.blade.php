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
      <div class="col-lg-12" id="page-start">         
        <div class="card">
          <div class="card-body">                     
            <h5 class="card-title">Post List</h5>   
            <x-admin.form.alert/>            
            <div class="container">{{ $posts->links() }}</div>
            <div class="mb-3 mt-1">
              <form method="get" action="{{ route('post.list') }}">
                <div class="input-group"> 
                  <a href="{{ route('post.list') }}" class="btn btn-primary" role="button">Reset</a>                                 
                  <input type="search" class="form-control" placeholder="Search" aria-label="Search" name="search" value="{{ $search }}" />
                  <button type="submit" class="btn btn-primary">Search</button>                                
                </div>              
              </form>
            </div>
              
            <table class="table">
                <thead>                          
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>                                            
                    <th scope="col">Author</th>
                    <th scope="col" class="text-center">Status</th>                                                                         
                    <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="tag-list">                   
                    @php($count = 0)              
                    @foreach($posts AS $post)            
                    <tr id="{{ $post->id }}" class="tag-name">                      
                    <th scope="row">{{ ++$count }}</th>
                    <td>{{ $post->title }}</td>    
                    <td>{{-- $post->author --}}</td>                                                               
                    <td class="text-center">
                        @if( $post->status )
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-warning text-dark">Inactive</span>
                        @endif
                    </td>                                                           
                    <td class="text-center">
                        <a href="{{ route('post.edit', ['id' => $post->id]) }}" class="edit-category link-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pen"></i></a>
                        <form method="post" action="{{ route('post.delete' , ['id' => $post->id]) }}" id="delete_{{$post->id}}" style="display: inline;">
                        @csrf
                        <a href="javascript:void(0)" class="delete link-secondary" data-toggle="tooltip" data-placement="top" title="Delete">
                            <i class="bi bi-trash" onclick="$.fn.deleteRow({{$post->id}});"></i>
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