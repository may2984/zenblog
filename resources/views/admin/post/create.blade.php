<x-admin.layout>
    <x-slot:title>
        Add Post
    </x-slot>
    @push('css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style type="text/css">
        #post_body { height: 500px; }

        .sortable{
            border: 1px solid #eee;
            width: 300px;
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
            font-size: 1.2em;
            width: 280px;
        }

        .red-border{
            border: 1px solid red;
        }
    </style>
    @endpush
    @push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">

        $( function() {  

            var quill = new Quill('#post_body', { theme: 'snow' }); 

            quill.on('text-change', function(delta, oldDelta, source) {
                var content = quill.container.firstChild.innerHTML
                if( content != '<p><br></p>' ){
                    $('#body').val( content );
                }
                else{
                    $('#body').val('');
                }                
            });
            
            $('#title').blur( function() {
                var slug = $('#title').val().replace(/[^a-zA-Z0-9 ]/g, "").split(" ").join('-').toLowerCase();
                $('#slug').val( slug );
            });

            $( "#sortable_author_1, #sortable_author_2" ).sortable({
                connectWith: ".connectedSortable",               
            }).disableSelection();

            $( "#sortable_author_2" ).on( "sortupdate", function( event, ui ) {               

                $( "#sortable_author_1" ).find('li').removeClass('red-border');

                $(this).find('li').removeClass('red-border');
                $(this).find('li').first().addClass('red-border');

                var id_string = [];
                $(this).children().each(function(index) {
                    var id = $(this).attr('id');
                    id_string += `<input type="hidden" name="blog_author[]" value=${id}>`
                });
                $('#tr_author').html( id_string );
            });

            $( "#sortable_category_1, #sortable_category_2" ).sortable({
                connectWith: ".connectedSortable",               
            }).disableSelection();

            $( "#sortable_category_2" ).on( "sortupdate", function( event, ui ) {               

                $( "#sortable_category_1" ).find('li').removeClass('red-border');

                $(this).find('li').removeClass('red-border');
                $(this).find('li').first().addClass('red-border');

                var id_string = [];
                $(this).children().each(function(index) {
                    var id = $(this).attr('id');
                    id_string += `<input type="hidden" name="blog_category[]" value=${id}>`
                });
                $('#tr_category').html( id_string );
            });
        });
    </script>
    
    @endpush    
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Add Post</h5>
                    <x-admin.form.alert />
                    <form method="post" action="{{ route('post.store') }}" id="tag-add-form">   
                        @csrf 
                        <div class="mb-3 float-end"><span class="fs-7">Field marked with <span class="text-danger">*</span> are mandatory</span></div>
                        <div class="col-15 mb-3">
                            <label for="title" class="form-label">Title<span class="text-danger"> *</span></label>
                            @error('title')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror 
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                        </div>
                           
                        <div class="col-12 mb-3">
                            <label for="slug" class="form-label">Slug<span class="text-danger"> *</span></label>
                            @error('slug')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon3">{{ url('/') }}/</span>
                                <input type="text" class="form-control" name="slug" id="slug" aria-describedby="basic-addon3" value="{{ old('slug') }}">
                            </div>                           
                        </div>  

                        <div class="col-12 mb-3">
                            <label for="summary" class="form-label">Summary<span class="text-danger"> *</span></label>
                            @error('summary')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="summary" rows="4">{{ old('summary') }}</textarea>
                            </div>                           
                        </div>  

                        <div class="col-12 mb-3 row" id="tr_author"></div>

                        <div class="col-12 mb-3 row">                                                        
                            <label class="form-label">Author<span class="text-danger"> *</span></label>
                            @error('blog_author')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror
                            <div class="input-group mb-3">
                                <ul id="sortable_author_1" class="connectedSortable sortable">
                                    @foreach($blogAuthors AS $author)
                                        <li class="ui-state-default" id="{{ $author->id }}">{{ $author->full_name }}</li>                                   
                                    @endforeach       
                                </ul>
                                <ul id="sortable_author_2" class="connectedSortable sortable"></ul>
                            </div>
                        </div> 
              
                        <div class="col-12 mb-3 row" id="tr_category"></div>

                        <div class="col-12 mb-3 row">                               
                            <label class="form-label">Category<span class="text-danger"> *</span></label>
                            @error('blog_category')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror
                            <div class="input-group mb-3">
                                <ul id="sortable_category_1" class="connectedSortable sortable">
                                    @foreach($blogCategories AS $blogCategory) 
                                        <li class="ui-state-default" id="{{ $blogCategory->id }}">{{ $blogCategory->name }}</li>                                                                            
                                    @endforeach                                
                                </ul>
                                <ul id="sortable_category_2" class="connectedSortable sortable"></ul>
                            </div>
                        </div> 

                        <div class="col-12 mb-3">                             
                            <label for="inputPassword" class="form-label">Tags<span class="text-danger"> *</span></label>
                            @error('blog_category')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror
                            <div class="col-sm-10"> 
                                @foreach($tags AS $tag)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="blog_tag[]" id="blog_tag" value="{{ $tag->id }}" {{ (collect(old('blog_tag'))->contains($tag->id)) ? 'checked':'' }}>
                                        <label class="form-check-label" for="{{ $tag->id }}">{{ $tag->name }}</label>
                                    </div>
                                @endforeach  
                            </div>                  
                            @error('tags')
                            <div class="col-sm-4 text-danger">
                                {{ $message }}
                            </div>
                            @enderror   
                        </div>                                
                    </div>
                </div>
            </div>  
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">                     
                        <div class="mt-4 mb-4">
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" name="published" role="switch" @checked( old('published') === true || old('published') === null )>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Published</label>
                            </div>
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" name="comments_allowed" role="switch" @checked( old('comments_allowed') == '1' || old('comments_allowed') === null )>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Allow Comments</label>
                            </div>
                        </div> 
                        
                        <div class="row mb-3">
                            <label for="inputDate" class="col-sm-2 col-form-label">Publish On<span class="text-danger"> *</span></label>
                            <div class="col-sm-5">
                                <input type="date" name="publish_date" id="publish_date" class="form-control datepicker" value="{{ old('publish_date') === null ? '11-05-2023' : old('publish_date') }}">    
                            </div>                            
                            <div class="col-sm-5">
                                <input type="time" name="publish_time" id="publish_time" class="form-control" value="{{ old('publish_time') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDate" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-5">
                                @error('publish_date')
                                <div class="col-sm-4 text-danger">
                                    {{ $message }}
                                </div>
                                @enderror 
                            </div>                            
                            <div class="col-sm-5">
                                @error('publish_time')
                                <div class="col-sm-4 text-danger">
                                    {{ $message }}
                                </div>
                                @enderror 
                            </div>
                        </div> 
                        <label class="col-sm-5 col-form-label">
                            Details<span class="text-danger"> *</span>
                            @error('body')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror 
                        </label>  
                        <div class="col-sm-14">
                            <input type="hidden" name="body" id="body" value="{{ old('body') }}">
                            <div id="post_body">{{ old('body') }}</div>
                        </div>                                              
                        <label for="inputPassword" class="col-sm-7 col-form-label">
                            Meta title<span class="text-danger"> *</span>
                            @error('meta_title')
                            <span class="col-sm-5 text-danger">
                                {{ $message }}
                            </span>
                            @enderror 
                        </label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="meta_title" id="meta_title" rows="4">{{ old('meta_title') }}</textarea>
                        </div>
                        <div class="row mt-3 float-end">                  
                            <div class="col-sm-10">                  
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>               
        </div>
    </section>
</x-admin.layout>