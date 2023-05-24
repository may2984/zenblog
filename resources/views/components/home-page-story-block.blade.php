<div class="col-md-9">
    <div class="d-lg-flex post-entry-2">
        <a href="{{ $first_posts->url }}" class="me-4 thumbnail mb-4 mb-lg-0 d-inline-block">
        <img src="{{ asset('frontend/assets/img/post-landscape-6.jpg') }}" alt="" class="img-fluid">
        </a>
        <div>
            <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $first_posts->published_at }}</span></div>
            <h3><a href="{{ $first_posts->url }}">{{ $first_posts->title }}</a></h3>
            <p>{{ $first_posts->summary }}</p>
            <div class="d-flex align-items-center author">
                <div class="photo"><img src="{{ asset('frontend/assets/img/person-2.jpg') }}" alt="" class="img-fluid"></div>
                <div class="name">
                <h3 class="m-0 p-0">{{ $first_posts->author }}</h3>
                </div>
            </div>
        </div>
    </div>

   <div class="row">
        <div class="col-lg-4">
            <div class="post-entry-1 border-bottom">
                <a href="{{ $posts[1]->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-1.jpg') }}" alt="" class="img-fluid"></a>
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $posts[1]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $posts[1]->url }}">{{ $posts[1]->title }}</a></h2>
                <span class="author mb-3 d-block">{{ $posts[1]->author }}</span>
                <p class="mb-4 d-block">{{ $posts[1]->summary }}</p>
            </div>

            <div class="post-entry-1">
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $posts[2]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $posts[2]->url }}">{{ $posts[2]->title }}</a></h2>
                <span class="author mb-3 d-block">{{ $posts[2]->author }}</span>
            </div>
        </div>
        <div class="col-lg-8">
        <div class="post-entry-1">
                <a href="{{ $posts[3]->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-2.jpg') }}" alt="" class="img-fluid"></a>
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $posts[3]->published_at }}</span></div>
                <h2 class="mb-2"><a href="{{ $posts[3]->url }}">{{ $posts[3]->title }}</a></h2>
                <span class="author mb-3 d-block">{{ $posts[3]->author }}</span>
                <p class="mb-4 d-block">{{ $posts[3]->summary }}</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    @foreach($last_six_posts AS $post)
        <div class="post-entry-1 border-bottom">
            <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
            <h2 class="mb-2"><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
            <span class="author mb-3 d-block">{{ $post->author }}</span>
        </div>
    @endforeach
</div>