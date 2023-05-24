<x-blog.index>
   
    <section id="hero-slider" class="hero-slider">@include('blog.slider')</section>

    <section id="posts" class="posts">
        <div class="container" data-aos="fade-up">
            <div class="row g-5">
                <x-home-page-story-block-first />
            </div>
        </div>
    </section>
 
    <section class="category-section">
        <div class="container" data-aos="fade-up">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
                <x-see-all-category category='Culture' />
            </div>
            <div class="row">             
                <x-home-page-story-block category-name='Culture' />            
            </div>
        </div>
    </section> 
    
    <section class="category-section">
        <div class="container" data-aos="fade-up">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
                <x-see-all-category category='Entertainment' />            
            </div>
            <div class="row">             
                <x-home-page-story-block-two category-name='Entertainment' />            
            </div>
        </div>
    </section>
  
     <section class="category-section">
        <div class="container" data-aos="fade-up">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
                <x-see-all-category category='Lifestyle' />              
            </div>
            <div class="row g-5">   
                <x-home-page-story-block-last category-name='Lifestyle' />  
            </div>
        </div>
    </section>
    
</x-blog.index>