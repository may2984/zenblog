<ul {{ $attributes }}>
    @foreach( $blog_category AS $category )
    <li><a href="{{ route('category.url', ['category' => $category->url]) }}"><i class="bi bi-chevron-right"></i>{{ $category->name }}</a></li>
    @endforeach
</ul>