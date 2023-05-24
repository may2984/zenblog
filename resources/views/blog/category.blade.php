<x-blog.index>
    <section class="single-post-content">
        <div class="container">
            <div class="row">             
                <div class="col-md-9" data-aos="fade-up">                
                    <h3 class="category-title">Category: {{ $categoryName }}</h3>

                    @foreach($categoryPosts AS $post)
                        <x-category-landing-page :$post :$categoryName/>
                    @endforeach

                    <div class="text-start py-4">
                        <div class="custom-pagination">
                            {{ $categoryPosts->links('vendor.pagination.zenblog') }}
                        </div>
                    </div>                    
                </div>             
                <div class="col-md-3"> 
                    <x-sidebar/>
                </div>
            </div>
        </div>
    </section>
</x-blog.index>