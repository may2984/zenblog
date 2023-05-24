<div class="post-entry-1 border-bottom">
    <div class="post-meta"><span class="date">{{ $post->category }}</span> <span class="mx-1">•</span> <span>{{ $post->published_at }}</span></div>
    <h2 class="mb-2"><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
    <span class="author mb-3 d-block">{{ $post->author }}</span>
</div>