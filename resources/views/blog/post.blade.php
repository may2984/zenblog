@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        $(function() {
            $("a.reply-to-comments").click(function(){
               $('#comment_id').val($(this).attr('id'));
            })            
        });
    </script>  
@endpush

<x-blog.index>
    <section class="single-post-content">
        <div class="container">
            <div class="row">
                <div class="col-md-9 post-content" data-aos="fade-up">
                    
                    <div class="single-post">
                        <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
                        <h1 class="mb-5">{{ $post->title }}</h1>
                        <p><span class="firstcharacter"></span>{{ $post->summary }}</p>                   
                        <div>
                        {!! $post->body !!}
                        </div>                
                    </div>
                    
                    @if( $post->comments_allowed )
                    <div class="comments">                        
                        <h5 id="comments" class="comment-title py-4"><a href="#comments">{{ $comment_count }} Comments</a></h5>
                        @foreach($comments AS $comment)
                        <div class="comment d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm rounded-circle">
                                <img class="avatar-img" src="{{ asset('frontend/assets/img/person-5.jpg') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2 ms-sm-3">
                                <x-post.comment :$comment/>

                                @if( $comment->comments->count() )
                                    <div class="comment-replies bg-light p-3 mt-3 rounded">
                                        <h6 class="comment-replies-title mb-4 text-muted text-uppercase">
                                            {{ $comment->comments->count() }}
                                            @php  
                                              echo $comment->comments->count() > 1 ? 'replies' : 'Reply'
                                            @endphp                                             
                                        </h6>
                                        @foreach($comment->comments AS $comment)
                                        <div class="reply d-flex mb-4">
                                            <div class="flex-shrink-0">
                                            <div class="avatar avatar-sm rounded-circle">
                                                <img class="avatar-img" src="{{ asset('frontend/assets/img/person-4.jpg') }}" alt="" class="img-fluid">
                                            </div>
                                            </div>
                                            <div class="flex-grow-1 ms-2 ms-sm-3">
                                            <div class="reply-meta d-flex align-items-baseline">
                                                <h6 class="mb-0 me-2">{{ $comment->name }}</h6>
                                                <span class="text-muted">{{ Carbon\Carbon::now()->shortAbsoluteDiffForHumans($comment->created_at) }}</span>
                                            </div>
                                            <div class="reply-body">{{ $comment->message }}</div>
                                            </div>
                                        </div>   
                                        @endforeach                               
                                    </div>
                                @endif
                                
                            </div>
                        </div>
                        @endforeach
                    </div>            
                
                    <div class="row justify-content-center mt-5" id="reply">
                        <x-post.comment-form :$post/>
                    </div>                
                    @endif()

                </div>
                <div class="col-md-3">                    
                    {{--<x-sidebar/>--}}
                </div>
            </div>
        </div>
    </section>
</x-blog.index>