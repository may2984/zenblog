<x-blog.index>
    <section class="single-post-content">
        <div class="container">
            <div class="row">
                <div class="col-md-9" data-aos="fade-up">
                   <x-category-landing-page/>                    
                </div>

                <div class="col-md-3">
                    <!-- ======= Sidebar ======= -->
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
                        <x-home.footer.category-link class="aside-links list-unstyled" />
                    </div>
                    <div class="aside-block">
                        <h3 class="aside-title">Tags</h3>
                        <x-side-tags/>                    
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-blog.index>