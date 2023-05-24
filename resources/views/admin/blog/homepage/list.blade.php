<x-admin.layout> 
  <x-slot:title>
    Add Tag
  </x-slot> 
  @push('scripts')  
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
    $( function() {

      jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }                 
      });

      $( "#sortable_news_1, #sortable_news_2" ).sortable({
          connectWith: ".connectedSortable",               
      }).disableSelection();

      $.fn.getPost = function( post_category_id = 0 ){

        $('#sortable_news_2').html('');
        $('#sortable_news_1').html('<img src="{{ asset("backend/assets/img/Bars-1s-200px.gif") }}" height=50>');
        
        $.get("{{ route('category.post.list', '') }}"+'/'+post_category_id, function( data, response ){

          var posts = '';
          var savedPosts = '';  

          $.each(data.posts, function(key, value){
            posts += $.fn.postRow( value );
          });

          $.each(data.savedPosts, function(key, value){
            savedPosts += $.fn.postRow( value );
          });
          
          $('#sortable_news_1').html(posts);
          $('#sortable_news_2').html(savedPosts);
        });              
      };

      $.fn.postRow = function( value ){
        var id = value.id;
        var title = value.title;
        var published_at = value.published_at
        return  `<li class="ui-state-default" id=${id}>${title}<br><b>${published_at}</b></li>`
      }

      $.fn.getPost();

      $('#post_category').on('change', function(){        
        $('#home_page_category_name').html('Home Page >> '+$('#post_category option:selected').text()+' Posts');
        $.fn.getPost(this.value);
      })

      $('#save_news_order').on('click', function(){

        var allPosts = $( "#sortable_news_2>li" );
        var selectedCategory = $('#post_category option:selected').val();

        postIdsInOrder = {};

        $.each(allPosts, function(index){
          postIdsInOrder[ $(this).attr('id') ] = index + 1;        
        });

        console.log(postIdsInOrder);

        $.ajax({
          method: 'POST',
          url: "{{ route('category.post.save') }}",
          data: 'category_id='+selectedCategory+'&sorted_ids=' + JSON.stringify(postIdsInOrder)
        })
        .done(function( msg ) {
          alert('News Saved');
        });
      })

    });
  </script>   
  @endpush  
  @push('css')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style type="text/css">
    #name{ 
      height: 150px; 
    }
    .sortable{
        border: 1px solid #eee;
        width: 700px;
        min-height: 20px;
        list-style-type: none;
        margin: 0;
        padding: 5px 0 0 0;
        float: left;
        margin-right: 10px;
    }
    .sortable li{
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 15px;
        width: 690px;
    }
    .red-border{
        border: 1px solid red;
    }
  </style> 
  @endpush
  <section class="section">
    <div class="row">     
      <div class="col-lg-12" id="page-start">         
        <div class="card">
          <div class="card-body">                     
            <h5 class="card-title" id="home_page_category_name">Home Page >> All Category Posts</h5>                        
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">News Category</label>
              <div class="col-sm-2">
                <select class="form-select" aria-label="Default select example" id="post_category">      
                  <option value="0">Home Page</option>
                  @foreach($postCategory AS $category)             
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>                
              </div>             
                
              <div class="col-sm-4 float-right">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <button class="btn btn-primary" id="save_news_order">Save News Order</button>
              </div>
            </div>

            <div class="col-12 mb-3 row" type="reset" id="page_posts"></div>            

            <div class="col-12 mb-3 row">   
              <div class="input-group mb-3">
                  <ul id="sortable_news_1" class="connectedSortable sortable"></ul>
                  <ul id="sortable_news_2" class="connectedSortable sortable"></ul>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layout>