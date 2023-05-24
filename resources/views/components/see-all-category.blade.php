@props([
    'category',
])

<h2>{{ $category }}</h2>
<div><a href="{{ route('category.url', Str::lower($category)) }}" class="more">See All {{ $category }}</a></div>