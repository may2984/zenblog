<div class="col-lg-4">
    <div class="post-entry-1 lg">
        <a href="{{ $first_post->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-8.jpg') }}" alt="" class="img-fluid"></a>
        <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $first_post->published_at }}</span></div>
        <h2><a href="{{ $first_post->url }}">{{ $first_post->title }}</a></h2>
        <p class="mb-4 d-block">{{ $first_post->summary }}</p>

        <div class="d-flex align-items-center author">
            <div class="photo">
                <img src="{{ asset('frontend/assets/img/person-7.jpg') }}" alt="" class="img-fluid">
            </div>
            <div class="name">
                <h3 class="m-0 p-0">{{ $first_post->author }}</h3>
            </div>
        </div>
    </div>

    @foreach($next_two_posts AS $post)
        <div class="post-entry-1 @if ($loop->first) border-bottom @endif">
            <div class="post-meta"><span class="date">{{ $category }}<</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
            <h2 class="mb-2"><a href="{{ $first_post->url }}">{{ $post->title }}</a></h2>
            <span class="author mb-3 d-block">{{ $post->author }}</span>
        </div>
    @endforeach    
</div>

<div class="col-lg-8">
    <div class="row g-5">
        @foreach($next_four_posts->chunk(3) AS $postChunk)
        <div class="col-lg-4 border-start custom-border">
            @foreach($postChunk AS $post)
            <div class="post-entry-1">
                <a href="{{ $post->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-6.jpg') }}" alt="" class="img-fluid"></a>
                <div class="post-meta"><span class="date">{{ $category }}<</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
                <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
            </div>
            @endforeach            
        </div>
        @endforeach     
        <div class="col-lg-4">
            @foreach($last_six_posts AS $post)
                <div class="post-entry-1 border-bottom">
                    <div class="post-meta"><span class="date">{{ $category }}<</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
                    <h2 class="mb-2"><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
                    <span class="author mb-3 d-block">{{ $post->author }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
