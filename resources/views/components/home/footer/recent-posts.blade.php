<ul class="footer-links footer-blog-entry list-unstyled">
    @foreach($posts as $post)
    <li>
        <a href="{{ route('post.url', ['category' => $category, 'slug' => $post->slug, 'id' => $post->id ]) }}" class="d-flex align-items-center">
            <img src="{{ asset('frontend/assets/img/post-sq-1.jpg') }}" alt="" class="img-fluid me-3">
            <div>
            <div class="post-meta d-block"><span class="date">Culture</span> <span class="mx-1">&bullet;</span> <span>@blog_date($post->published_at)</span></div>
            <span>{{ $post->title }}</span>
            </div>
        </a>
    </li>
    @endforeach
</ul>