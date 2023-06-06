@if($banners->count())
<div class="container-md" data-aos="fade-in">
    <div class="row">
        <div class="col-12">
            <div class="swiper sliderFeaturedPosts">
                <div class="swiper-wrapper">
                    @foreach($banners AS $banner)
                    <div class="swiper-slide">
                        <a href="{{ $banner->url }}" class="img-bg d-flex align-items-end" style="background-image: url('{{ asset($banner->banner_image) }}');">
                        <div class="img-bg-inner">
                            <h2>{{ $banner->banner_heading ?? $banner->title }}</h2>
                            <p>{{ $banner->banner_text ?? $banner->summary }}</p>
                        </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="custom-swiper-button-next"><span class="bi-chevron-right"></span></div>
                <div class="custom-swiper-button-prev"><span class="bi-chevron-left"></span></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</div>
@endif