@push('css')
  <style type="text/css">
      .bi-trash3:hover, .bi-pen-fill:hover { cursor: pointer }
      .hidden { display: none; }
  </style>
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(function(){      
     
      $.fn.makeRow = function( id, name, status ){
          return `<tr id=${id}>
                      <th scope="row">${id}</th>                     
                      <td class="handle" title="Status">${name}</td>                                                                                               
                      <td class="text-center handle" title="Status">${status}</td>     
                      <td class="text-center">
                         <form id='delete_${id}' method="post" action="/admin/member/${id}" style="display:inline;">
                          @csrf
                          <i class="bi bi-trash3" onclick="$.fn.delete(${id})" alt="Delete"></i>                                                                            
                         </form> 
                         <a href="/admin/member/${id}/edit">
                          <i class="bi bi-pen-fill edit-author" alt="Edit"></i>
                         </a>
                      </td>
                  </tr>`;
      };

      $.fn.toggleStatus = function( id, status, url ){
     
        $.ajax({
          method: 'GET',
          url: url,
          statusCode: {
            404: function() {
              alert( "Unable to find the requested page" );
            },
            500: function() {
              alert( "Internal Server Error" );
            }
          }
        })
        .done(function(response){
          
          if( response.type == 'success'){
            
            if(status === 0){              
              var message = '{{$label}} dectivated';
            }
            else{             
              var message = '{{$label}} activated';
            }

            $('#error-message-ajax').addClass('hidden');
            $('#success-message-ajax').removeClass('hidden').html(message);
            
            var status_button = $.fn.getStatus(status, id);
            $('span#status_'+id).parent('td').html(status_button)
          }
          else{
            $('#success-message-ajax').addClass('hidden');
            $('#error-message-ajax').removeClass('hidden').html(message);                                     
          } 

          setTimeout(function(){
            $('#success-message-ajax').addClass('hidden');
            $('#error-message-ajax').addClass('hidden');
          }, 2500);
        });
      };

      $.fn.getList = function(){

        $('#list').html('<img src="{{ asset("backend/assets/img/Bars-1s-200px.gif") }}" height=50>');

        $.get("{{ route('member.index') }}", function( data, response ){
          
          var count = 1;
          var header = {
                        "id": 
                            [{
                              "label": "Id",
                              "class": "",
                              "width": "10%"
                            }],
                        "name": 
                            [{
                              "label": "Name",
                              "class": "",
                              "width": "50%"
                            }],  
                        "status": 
                          [{
                            "label": "Status",
                            "class": "text-center",
                            "width": "30%"
                          }],
                        "action":
                          [{
                            "label": "Action",
                            "class": "text-center",
                            "width": "10%"
                          }]                          
                      };

          var row = `<thead><tr>`;
          
            $.each(header, function( header_key, header_value ){ 
                var class_name = "";
                var width = "";
                $.each(header_value, function( key, value ){
                  var label = value.label;
                  var class_name = value.class;
                  var width = value.width;
                  row += `<th scope="col" class="${class_name}" width="${width}">${label}</th>`;  
                });
            }); 

          row += `</tr></thead>`;

          row += `<tbody>`;
          
          if( data.length )
          {
              $.each(data, function( key, value ){                            
                  var id = value.id;
                  var name = value.name
                  var status = $.fn.getStatus(value.status, id);
                  row += $.fn.makeRow(id, name, status);                            
              });
          }
          else
          {
            row += `<tr><th colspan='4' class='text-center'>No data added</th></tr>`;
          }          

          row += `</tbody>`;
          
          $('#list').html( row );

          setTimeout(function(){
            $('#success-message').fadeOut()
          }, 2500);

        });
      };        
      
      $.fn.getStatus = function(status, id){ 

        if( status == 1 ){
          var badge_class = 'success'
          var toggle_status = 0;
          var status_text = 'Active';
          var text_color = 'white';
        }else{
          var badge_class = 'warning'                  
          var toggle_status = 1;
          var status_text = 'Inactive';
          var text_color = 'black';
        }

        var url = `/admin/member/toggle/status/${toggle_status}/${id}`;

        return `<span class="badge bg-${badge_class}" id="status_${id}">
                  <a class="text-${text_color}" href="javascript:void(0);" onclick="$.fn.toggleStatus( ${id}, ${toggle_status}, '${url}')">
                  ${status_text}
                  </a>
                </span`;
                  
      };

      $.fn.getList();

      $.fn.delete = function( id ){             

          if( confirm('Are you sure you want to delete it?') ){
            
            var form = $("#delete_"+id);
            var csrf = form.find('input[name=_token]').val();

            $.ajax({
              method: 'DELETE',
              url: form.attr('action')+'?_token='+csrf,
              statusCode: {
                404: function() {
                  alert( "Unable to find the requested page" );
                },
                500: function() {
                  alert( "Internal Server Error" );
                }
              }
            })
            .done(function(response) {
             
              if(response[0] == 'success'){
                $('#success-message-ajax').removeClass('hidden');
                $('#'+id).remove();                
                setTimeout(function(){                  
                  $('#success-message-ajax').addClass('hidden');
                }, 2500);
              }
              if(response[0] == 'error'){                
                $('#error-message-ajax').removeClass('hidden');            
                setTimeout(function(){                  
                  $('#error-message-ajax').addClass('hidden');    
                }, 2500);
              }
            });              
          }              
      }   
           
    });

</script>
@endpush

<x-admin.layout>
   <x-slot:title>Add {{$label}}</x-slot:title>
   <section class="section">
    <div class="row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title" id="form-title">
              Add {{$label}}
              @if(Session::has('success'))              
              <div class="float-end fs-8 text-success" id="success-message">{{ Session::get('success') }}</div>
              @endif
              @if(Session::has('error'))
              <div class="float-end fs-8 text-danger" id="error-message">{{ Session::get('error') }}<</div>
              @endif
            </h5>

            <form action="{{ $store }}" method="post">
                @csrf 
                <div class="row mb-3">
                    @error('name')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="name">
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
      <div class="col-lg-7">      
          <div class="card">
              <div class="card-body">                  
                  <h5 class="card-title">{{$label}} List                    
                    <div class="float-end fs-8 text-success hidden" id="success-message-ajax">{{$label}} deleted</div>
                    <div class="float-end fs-8 text-danger hidden" id="error-message-ajax">Error! try again</div>
                  </h5>
                  <table class="table" id="list"></table>                        
              </div>
          </div>
      </div>
    </div>
   </section>
</x-admin.layout>