<div class="aside-block">
    <x-news-tabs/>                     
</div>
<div class="aside-block">
    <h3 class="aside-title">Video</h3>
    <div class="video-post">
    <a href="https://www.youtube.com/watch?v=AiFfDjmd0jU" class="glightbox link-video">
        <span class="bi-play-fill"></span>
        <img src="{{ asset('frontend/assets/img/post-landscape-5.jpg') }}" alt="" class="img-fluid">
    </a>
    </div>
</div>
<div class="aside-block">
    <h3 class="aside-title">Categories</h3>
    <x-home.footer.category-link class="aside-links list-unstyled" :number-of-category-to-show=6/>
</div>
<div class="aside-block">
    <h3 class="aside-title">Tags</h3>
    <x-tag-links class="aside-tags list-unstyled" :number-of-links=15/>
</div>