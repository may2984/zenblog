@foreach($posts AS $post)
<li>
    <a href="{{ route('post.url', ['category' => $post['category'], 'slug' => $post['slug'], 'id' => $post['id'] ]) }}"><h3>{{ $post->title }}</h3>
        <span class="author"><b>{{ $post->author }}</b></span>
    </a>
</li>
@endforeach