<x-admin.layout>
    <x-slot:title>{{ env('app.name') }} Create Author</x-slot:title>
    @push('css')
    <style type="text/css">
        .bi-trash3:hover, .bi-pen-fill:hover { cursor: pointer }
    </style>
    @endpush
    @push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(function(){     

            $('#rest-form').click(function(){
                $.fn.resetForm();
            });

            $.fn.resetForm = function(){
                $('#author-form')[0].reset();
                $('#form-title').html('Add Author');
                $('#author-form').attr('action', "{{ route('author.store') }}");
            };
            
            $.fn.editAuthor = function( author_id ){                
                $.get("{{ route('author.edit', '') }}/"+author_id, function(data, response){
                    $('#form-title').html('Edit Author');
                    $.each(data, function( key, value ){
                        var elm = '#'+key;
                        $(elm).val(value);
                    });
                    $('#author-form').attr('action', "{{ route('author.update', '') }}/"+author_id);
                });
            };

            $.fn.makeAuthorRow = function( count, name, pen_name, id ){
                return `<tr id=${id}>
                            <th scope="row">${count}</th>
                            <td>${name}</td>                                       
                            <td>${pen_name}</td>                                       
                            <td class="text-center">
                                <i data-bs-target="#confirmDeleteModal" data-bs-toggle="modal" class="bi bi-trash3" id="${id}" alt="Delete"></i>
                                <i class="bi bi-pen-fill edit-author" onclick="$.fn.editAuthor(${id});" alt="Edit"></i>
                            </td>
                        </tr>`;
            };

            jQuery.ajaxSetup({
                beforeSend: function() {
                    $('#author-list').html('<img src="{{ asset("backend/assets/img/Bars-1s-200px.gif") }}" height=50>');
                },                
            });

            $.fn.getAuthorList = function(){

                $.get("{{ route('author.list') }}", function( data, response ){   
                    
                    var count = 1;
                    var row = '';
                    
                    $.each(data, function( key, value ){                            
                        var index = count++;
                        var name = value.first_name+' '+value.last_name;
                        var pen_name = value.pen_name;
                        var id = value.id;
                        row += $.fn.makeAuthorRow(index, name, pen_name, id);
                                           
                    });   
                    
                    $('#author-list').html( row );
                });
            };            

            $.fn.getAuthorList();

            $('#first_name').blur(function() {

                var full_name = $.fn.getFullName();

                if( full_name.indexOf(" ") > 0 ){
                    var first_name = full_name.substring(0, full_name.indexOf(" ")).trim();
                    var last_name = [full_name.substring(full_name.indexOf(" "))].join(' ').trim();

                    $('#first_name').val( first_name );
                    $('#last_name').val( last_name ); 
                }
                
                $.fn.setAuthorUrl();
            });

            $.fn.getFullName = function() {
                return [$('#first_name').val(), $('#last_name').val()].join(' ').trim();
            }

            $('#last_name').blur( function() {
                $.fn.setAuthorUrl();
            });

            $.fn.setAuthorUrl = function() {       

                var full_name = $.fn.getFullName();
                var last_name = $('#last_name').val().trim();               

                if( last_name != '' ){                    
                    var url = full_name.replace(/[^a-zA-Z ]/g, "").split(" ").join('-').toLowerCase();
                }
                else{
                    var url = full_name.toLowerCase();
                }
                
                $( '#url' ).val( url ); 
                $( "#pen_name" ).focus();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#author-form').submit(function(e){
                e.preventDefault();
                var form = this;
                var formData = $(form).serialize()
                 
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: formData,              
                    dataType: 'json',                    
                    beforeSend: function(){
                        $(form).find('.error_text').html('');
                    },
                    success: function(response){
                        if(response.type == 'error'){
                            $('#exampleModal').modal('show');
                            $('#messages').html(response.message);
                        }
                        if(response.type == 'success'){
                            var id = parseInt( response.id );
                            if( id > 0 ){
                                $.fn.resetForm();
                                $('#messages').html(response.message);
                                $('#exampleModal').modal('show',  setTimeout(() => {                                
                                    $('#exampleModal').modal('hide');                                  
                                    $('tr#'+id).find('td').first().html( response.author_name );
                                }, "2500"));
                            }
                            else{                                  
                                $.fn.resetForm();
                                $('#messages').html(response.message);
                                $.fn.getAuthorList();
                                $('#exampleModal').modal('show',  setTimeout(() => {                                
                                    $('#exampleModal').modal('hide');                                    
                                }, "2500"));
                            }                            
                        }
                    },
                    error: function(response){                                  
                        $.each(response.responseJSON.errors, function(prefix, val){                           
                            var error_elm = '#'+prefix+'_error';                          
                            $(form).find('#'+prefix+'_error').html(val);
                        });
                    },
                    statusCode: {
                        500: function() {
                            alert( "Internal server error" );
                        }
                    }
                });
            });

            const exampleModal = document.getElementById('confirmDeleteModal')
            if (exampleModal) {
                exampleModal.addEventListener('show.bs.modal', (e) => {                  
                    const author_id = e.relatedTarget.id;
                    $('#author_delete_button').attr('onclick' , "$.fn.deleteAuthor("+`${author_id}`+")" );
                })
            }
            // BWGPP2504R

            $.fn.deleteAuthor = function( author_id ){             
                $('#confirmDeleteModal').modal('hide');                
                $.get( "{{ route('author.delete', '') }}"+"/"+author_id, function( response ){
                    if( response.type == 'success'){
                        $('tr#'+author_id).html('');
                        $.fn.getAuthorList()                    

                        $('#success-message').removeClass('visually-hidden').html(response.message);                                                
                        setTimeout(function(){
                            $('#success-message').addClass('visually-hidden').html('');
                        }, 2500);                                    
                    }
                    else{
                        $('#error-message').removeClass('visually-hidden').html(response.message);
                        setTimeout(function(){
                            $('#error-message').addClass('visually-hidden').html('');
                        }, 2500);                                              
                    }                    
                })              
                .fail(function() {
                    $('#header-message').addClass('text-success').html('Error! try again');
                })               
            }
            
        })
    </script>
    @endpush

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">  
                <div class="modal-header">                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>     
                <div class="modal-body float-center" id="messages"></div>       
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModal" aria-hidden="true">    
        <div class="modal-dialog">
            <div class="modal-content">           
            <div class="modal-body mt-3">
                <p>Are you sure you want to delete it?</p>
            </div>
            <div class="modal-footer float-right">                
                <button type="button" class="btn btn-primary" id="author_delete_button" onclick="">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-lg-6">      
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" id="form-title">Add Author</h5>                                                                 
                        <form class="row g-3" method="post" action="{{ route('author.store') }}" id="author-form">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                                        
                            <div class="col-md-6">
                                <label for="inputEmail5" class="form-label">First Name&nbsp;<span class="col-sm-5 ml-2 text-danger error_text" id="first_name_error"></span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name">
                            </div>                           
                            <div class="col-md-6">
                                <label for="inputPassword5" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name">
                            </div>                            
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Author URL&nbsp;<span class="col-sm-5 ml-2 text-danger error_text" id="url_error"></span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">{{ url('/', 'author') }}/</span>
                                    <input type="text" class="form-control" name="url" id="url">
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Pen Name&nbsp;<span class="col-sm-5 ml-2 text-danger error_text" id="pen_name_error"></span></label>
                                <input type="text" class="form-control" id="pen_name" name="pen_name">
                            </div>                                            
                            
                            <div class="col-md-7 mt-3">
                                <div class="col-sm-10">                                          
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-secondary" id="rest-form">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">      
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Author List<div class="float-end fs-8"></div></h5>
                        <table class="table" id="author-table">
                        <div class="alert alert-success visually-hidden" role="alert" id="success-message"></div>
                        <div class="alert alert-danger visually-hidden" role="alert" id="error-message"></div>
                            <thead>                
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>                           
                                    <th scope="col">Pen Name</th> 
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="author-list"></tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        </div>            
    </section>
</x-admin.layout>