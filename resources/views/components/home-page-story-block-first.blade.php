<div class="row g-5">
    <div class="col-lg-4">
      @if ( $home_post )
        <div class="post-entry-1 lg">
            <a href="{{ $home_post->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-1.jpg') }}" alt="" class="img-fluid"></a>
            <div class="post-meta"><span class="date">{{ $home_post->category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $home_post->published_at }}</span></div>
            <h2><a href="{{ $home_post->url }}">{{ $home_post->title }}</a></h2>
            <p class="mb-4 d-block">{{ $home_post->summary }}</p>

            <div class="d-flex align-items-center author">
                <div class="photo"><img src="{{ asset('frontend/assets/img/person-1.jpg') }}" alt="" class="img-fluid"></div>
                <div class="name">
                    <h3 class="m-0 p-0">{{ $home_post->author }}</h3>
                </div>
            </div>
        </div>
       @endisset
    </div>

    <div class="col-lg-8">
        <div class="row g-5">
            @foreach ($top_six_posts->chunk(3) AS $chunk)
            <div class="col-lg-4 border-start custom-border">    
                @foreach($chunk AS $post)
                <x-blog.post :posts=$post :summary=False />
                @endforeach    
            </div>
            @endforeach        
            
            <div class="col-lg-4">
                <div class="trending">
                    <h3>Trending</h3>
                    <ul class="trending-post">
                        @foreach($trending_posts AS $post)
                          <x-blog.trending-post :post=$post />
                        @endforeach                        
                    </ul>
                </div>
            </div> 
        </div>
    </div>
</div>