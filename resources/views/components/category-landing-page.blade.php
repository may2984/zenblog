@props(['post','categoryName'])
<div class="d-md-flex post-entry-2 half">
    <a href="{{ route('post.url', ['blog_category' => $post->url, 'slug' => $post->slug, 'post' => $post->id]) }}" class="me-4 thumbnail">
        <img src="{{ asset('frontend/assets/img/post-landscape-6.jpg') }}" alt="" class="img-fluid">
    </a>
    <div>
        <div class="post-meta"><span class="date">{{ $categoryName }}</span> <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span></div>
        <h3><a href="{{ route('post.url', ['blog_category' => $post->url, 'slug' => $post->slug, 'post' => $post->id]) }}">{{ $post->title }}</a></h3>
        <p>{{ $post->summary }}</p>
        <div class="d-flex align-items-center author">
            <div class="photo"><img src="{{ asset('frontend/assets/img/person-2.jpg') }}" alt="" class="img-fluid"></div>
            <div class="name">
                <h3 class="m-0 p-0">{{ App\Models\Post::getPostAuthors($post) }}</h3>
            </div>
        </div>
    </div>
</div>