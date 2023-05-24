@props(['posts'])

<div class="post-entry-1">
    <a href="{{ $posts->url }}"><img src="{{ asset('frontend/assets/img/post-landscape-2.jpg') }}" alt="" class="img-fluid"></a>
    <div class="post-meta"><span class="date">{{ $posts->category }}</span> <span class="mx-1">&bullet;</span> <span>{{ $posts->published_at }}</span></div>
    <h2><a href="{{ $posts->url }}">{{ $posts->title }}</a></h2>
</div>