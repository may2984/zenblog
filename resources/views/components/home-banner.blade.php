<div class="container-md" data-aos="fade-in">
    <div class="row">
        <div class="col-12">
            <div class="swiper sliderFeaturedPosts">
                <div class="swiper-wrapper">
                    @for( $count=0; $count < 4; $count++ ) 
                    <div class="swiper-slide">
                        <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('{{ asset('frontend/assets/img/post-slide-1.jpg')}}');">
                        <div class="img-bg-inner">
                            <h2>The Best Homemade Masks for Face (keep the Pimples Away)</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                        </div>
                        </a>
                    </div>
                    @endfor
                </div>
                <div class="custom-swiper-button-next"><span class="bi-chevron-right"></span></div>
                <div class="custom-swiper-button-prev"><span class="bi-chevron-left"></span></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</div>