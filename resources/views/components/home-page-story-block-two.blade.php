<div class="col-md-3">
    @foreach($first_six_posts AS $post)
        <div class="post-entry-1 border-bottom">
            <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
            <h2 class="mb-2"><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
            <span class="author mb-3 d-block">{{ $post->author }}</span>
        </div>
    @endforeach
</div>

<div class="col-md-9 order-md-2">
    <div class="d-lg-flex post-entry-2">
        <a href="{{ $seventh_post->url }}" class="me-4 thumbnail d-inline-block mb-4 mb-lg-0">
            <img src="{{ asset('frontend/assets/img/post-landscape-3.jpg') }}" alt="" class="img-fluid">
        </a>
        <div>
            <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $seventh_post->published_at }}</span></div>
            <h3><a href="{{ $seventh_post->url }}">{{ $seventh_post->title }}</a></h3>
            <p>{{ $seventh_post->summary }}</p>
            <div class="d-flex align-items-center author">
                <div class="photo"><img src="{{ asset('frontend/assets/img/person-4.jpg') }}" alt="" class="img-fluid"></div>
                <div class="name">
                    <h3 class="m-0 p-0">{{ $seventh_post->author }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="post-entry-1 border-bottom">
                <a href="{{ $last_three_posts[7]->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-5.jpg') }}" alt="" class="img-fluid"></a>
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $last_three_posts[7]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $last_three_posts[7]->url }}">{{ $last_three_posts[7]->title }}</a></h2>
                <span class="author mb-3 d-block">>{{ $last_three_posts[7]->author }}</span>
                <p class="mb-4 d-block">{{ $last_three_posts[7]->summary }}</p>
            </div>

            <div class="post-entry-1">
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $last_three_posts[8]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $last_three_posts[8]->url }}">{{ $last_three_posts[8]->title }}</a></h2>
                <span class="author mb-3 d-block">{{ $last_three_posts[8]->author }}</span>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="post-entry-1">
                <a href="{{ $last_three_posts[9]->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-7.jpg') }}" alt="" class="img-fluid"></a>
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $last_three_posts[9]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $last_three_posts[9]->url }}">{{ $last_three_posts[9]->title }}</a></h2>
                <span class="author mb-3 d-block">{{ $last_three_posts[9]->author }}</span>
                <p class="mb-4 d-block">{{ $last_three_posts[9]->summary }}</p>
            </div>
        </div>
    </div>
</div>