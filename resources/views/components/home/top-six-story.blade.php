@foreach ($stories->chunk(3) as $chunk)
<div class="col-lg-4 border-start custom-border">    
    @foreach($chunk AS $story)
    <div class="post-entry-1">
        <a href="{{ route('post.url', ['category' => $story->category , 'slug' => $story->slug, 'id' => $story->id]) }}"><img src="{{ asset('frontend/assets/img/post-landscape-2.jpg') }}" alt="" class="img-fluid"></a>
        <div class="post-meta"><span class="date">{{ $story->category }}</span> <span class="mx-1">&bullet;</span> <span>@blog_date($story->published_at)</span></div>
        <h2><a href="{{ route('post.url', ['category' => $story->category , 'slug' => $story->slug, 'id' => $story->id]) }}">{{ $story->title }}</a></h2>
    </div>  
    @endforeach    
</div>
@endforeach