@push('css')
  <style type="text/css">
      .bi-trash3:hover, .bi-pen-fill:hover { cursor: pointer }
  </style>
  <link href='https://guillotine.js.org/css/jquery.guillotine.css' rel='stylesheet'>
  <link href='https://guillotine.js.org/css/demo.min.css' rel='stylesheet'>
  <link href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'>
  <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-sortable@1.0.0/jquery-ui.min.js"></script>
<script src="{{ asset('backend/assets/js/jquery.guillotine.min.js') }}"></script>
<script>
    $(function(){      
     
      $.fn.makeAuthorRow = function( count, id, column_1, column_2, column_3, column_4 ){
          return `<tr id=${id}>
                      <th scope="row">${count}</th>
                      <td>
                        <a href="{{ asset('${column_2}') }}"><img src="{{ asset('${column_2}') }}" width="120" /></a>
                      </td>                                                             
                      <td>
                        <a style="color:black;" target="_blank" href="${column_3}">${column_1}</a>
                      </td> 
                      <td class="text-center handle" title="Status">
                      ${column_4}
                      </td>                                       
                      <td class="text-center handle" data-toggle="tooltip" data-placement="top" title="Order">
                         <i class="bi bi-arrows-move"></i>
                      </td>                                       
                      <td class="text-center">
                         <form id='delete_${id}' method="post" action="/admin/banner/${id}" style="display:inline;">
                          @csrf
                          <i class="bi bi-trash3" onclick="$.fn.delete(${id})" alt="Delete"></i>                                                                            
                         </form> 
                         <a href="/admin/banner/${id}/edit">
                          <i class="bi bi-pen-fill edit-author" alt="Edit"></i>
                         </a>
                      </td>
                  </tr>`;
      };

      jQuery.ajaxSetup({
          beforeSend: function() {
              
          },                
      });

      $.fn.toggleStatus = function( id, status ){

        var url = `/admin/banner/toggle/status/${status}/${id}`;

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
              var message = 'Banner dactivated';
            }
            else{             
              var message = 'Banner activated';
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

        $.get("{{ route('banner.index') }}", function( data, response ){ 
            
          var count = 1;
          var row = '';
          
          $.each(data, function( key, value ){                            
              var index = count++;
              var id = value.banner_id;
              var column_1 = ( value.banner_text ?? value.title ).substr(0,50)+'...' ;
              var column_2 = value.banner_image;
              var column_3 = value.url;
              var status = value.status;
              var column_4 = $.fn.getStatus(status, id);
              row += $.fn.makeAuthorRow(index, id, column_1, column_2, column_3, column_4);                                  
          });   
          
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

        return `<span class="badge bg-${badge_class}" id="status_${id}">
                  <a class="text-${text_color}" href="javascript:void(0);" onclick="$.fn.toggleStatus(${id}, ${toggle_status})">
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
                $.fn.setIndex();
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
      
      $('#list').sortable({
        appendTo: document.body,
        axis: "y",
        cancel: "a,button",        
        cursor: "move",
        handle: ".handle",
        revert: false,
        classes: {
          "ui-sortable": "highlight",
        }
      });

      $.fn.setIndex = function(){

        itemIdInOrder = {};

        $('table tbody').children().each(function(index) {
          $(this).find('th').first().html(index + 1)               
          itemIdInOrder[ $(this).attr('id') ] = index+1;              
        });

        return itemIdInOrder;
      };

      $( "table tbody" ).sortable( {
        update: function( event, ui ) {

          var itemIdInOrder = $.fn.setIndex();

          $.ajax({
            method: "POST",
            url: "{{ route('banner.sort') }}",      
            data: 'sorted_ids=' + JSON.stringify(itemIdInOrder)
          })
          .done(function( msg ) {
            $("#header-message").html( msg );
            setTimeout( function() {
              $("#header-message").fadeOut();
            }, 2000)
          });
        }
      });

     $('#banner_image').change(function(){
        
        $('#bannerModal').modal('show');

        var picture = $('#sample_picture')

        var camelize = function() {
            var regex = /[\W_]+(.)/g
            var replacer = function (match, submatch) { return submatch.toUpperCase() }
            return function (str) { return str.replace(regex, replacer) }
        }()

        var showData = function (data) {
            data.scale = parseFloat(data.scale.toFixed(4))
            console.log( data.scale);
            for(var k in data) { $('#'+k).val(data[k]) }
        }

        picture.on('load', function() {

            picture.guillotine({ 
                width: 1947, 
                height: 843,
                eventOnChange: 'guillotinechange' 
            })

            picture.guillotine('fit')

            for (var i=0; i<5; i++) { picture.guillotine('zoomIn') }

            // Show controls and data
            $('.loading').remove()
            $('.notice, #controls, #data').removeClass('hidden')
            showData( picture.guillotine('getData') )

            // Bind actions
            $('#controls a').click(function(e) {
                e.preventDefault()
                action = camelize(this.id)
                picture.guillotine(action)
            })

            // Update data on change
            picture.on('guillotinechange', function(e, data, action) { showData(data) })
        })         
            
            // Display random picture
            picture.attr('src', '{{ asset("banners/yRQve6T6qabWMR81n44Z3CnOZl4Ga0OFemUDGLl6.jpg") }}')
      }) 

    
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
            <h5 class="card-title" id="form-title">
              Add Banner
              @if(Session::has('success'))              
              <div class="float-end fs-8 text-success" id="success-message">{{ Session::get('success') }}</div>
              @endif
              @if(Session::has('error'))
              <div class="float-end fs-8 text-danger" id="error-message">{{ Session::get('error') }}<</div>
              @endif
            </h5>
            
            <div class="row mb-3">
                @error('banner_image')<div class="col-sm-5 text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>                    
                <div class="col-sm-10">@include('admin.banner.dropzone')</div>
            </div>

            <form action="{{ route('banner.store') }}" method="post">
                @csrf                
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Link To</label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Select Post" name="post_id">                      
                        @foreach($posts AS $post) 
                          <option value="{{ $post->id }}">{{ $post->title }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Heading</label>                    
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="banner_heading" name="banner_heading">                    
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
                      <input type="hidden" name="banner_image" id="banner_image">
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
                  <h5 class="card-title">Banner List                    
                    <div class="float-end fs-8 text-success hidden" id="success-message-ajax">Banner deleted</div>
                    <div class="float-end fs-8 text-danger hidden" id="error-message-ajax">Error! try again</div>
                  </h5>
                  <table class="table">                  
                      <thead>                
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col" class="text-center">Image</th> 
                              <th scope="col">Text</th>    
                              <th scope="col" class="text-center">Status</th>                        
                              <th scope="col" class="text-center">Order</th>                        
                              <th scope="col" class="text-center">Action</th>
                          </tr>
                      </thead>
                      <tbody id="list"></tbody>
                  </table>                        
              </div>
          </div>
      </div>
    </div>
   </section>
</x-admin.layout>