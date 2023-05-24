<ul {{ $attributes }}>
    @foreach($posts as $post)
        <li>
            <a href="{{ $post->url }}" class="d-flex align-items-center">
                <img src="{{ asset('frontend/assets/img/post-sq-1.jpg') }}" alt="" class="img-fluid me-3">
                <div>
                    <div class="post-meta d-block">
                        <span class="date">{{ $post->category }}</span> 
                        <span class="mx-1">&bullet;</span> <span>{{ $post->published_at }}</span>
                    </div>
                    <span>{{ $post->title }}</span>
                </div>
            </a>
        </li>
    @endforeach
</ul>