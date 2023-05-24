<ul {{ $attributes }}>
    @foreach($tags AS $tag)
      <li><a href="/tags/{{ $tag->name }}">{{ $tag->name }}</a></li>
    @endforeach
</ul>