@props(['trendingPosts','latestPosts','popularPosts'])
<ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill" data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="true">Popular</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-trending-tab" data-bs-toggle="pill" data-bs-target="#pills-trending" type="button" role="tab" aria-controls="pills-trending" aria-selected="false">Trending</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="false">Latest</button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">

    {{-- Popular Post --}}
    
    <div class="tab-pane fade show active" id="pills-popular" role="tabpanel" aria-labelledby="pills-popular-tab">
        @foreach($popularPosts AS $post)
         <x-news.tab.post :$post />
        @endforeach 
    </div>

    {{-- Trending Post --}}

    <div class="tab-pane fade" id="pills-trending" role="tabpanel" aria-labelledby="pills-trending-tab">
        @foreach($trendingPosts AS $post)
         <x-news.tab.post :$post />
        @endforeach       
    </div>
   
    {{-- Latest Post --}}
    <div class="tab-pane fade" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab">
        @foreach($latestPosts AS $post)
          <x-news.tab.post :$post />
        @endforeach
    </div>
</div>