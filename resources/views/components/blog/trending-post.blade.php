@props(['post'])

<li>
    <a href="{{ $post->url }}">    
        <h3>{{ $post->title }}</h3>
        <span class="author">{{ $post->author }}</span>
    </a>
</li>