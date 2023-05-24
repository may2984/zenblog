<div class="post-entry-1 lg">
    <a href="{{ $post_url }}">
        <img src="{{ asset('frontend/assets/img/post-landscape-1.jpg') }}" alt="{{ $post->title }}" class="img-fluid">
    </a>
    <div class="post-meta"><span class="date">{{ $post_category }}</span>&nbsp;<span class="mx-1">&bullet;</span> 
        <span>{{ $post->published_at }}</span>       
    </div>
    <h2><a href="{{ $post_url }}">{{ $post->title }}</a></h2>
    <p class="mb-4 d-block">{{ $post->summary }}</p>
    <div class="d-flex align-items-center author">
        <div class="photo"><img src="{{ asset('frontend/assets/img/person-1.jpg') }}" alt="{{ $post->title }}" class="img-fluid"></div>
        <div class="name">
            <h3 class="m-0 p-0">{{ $post_author }}</h3>
        </div>
    </div>
</div>