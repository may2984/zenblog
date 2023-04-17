<x-blog.index>
    <section class="single-post-content">
        <div class="container">
        <div class="row">
            <div class="col-md-9 post-content" data-aos="fade-up">

            <!-- ======= Single Post Content ======= -->
            <div class="single-post">
                <div class="post-meta"><span class="date">{{ $category }}</span> <span class="mx-1">&bullet;</span> <span>@blog_date($post->published_at)</span></div>
                <h1 class="mb-5">{{ $post->title }}</h1>
                <p><span class="firstcharacter"></span>{{ $post->summary }}</p>
                <figure class="my-4">
                    <img src="{{ asset('frontend/assets/img/post-landscape-1.jpg') }}" alt="" class="img-fluid">
                    <figcaption>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, odit? </figcaption>
                </figure>
                {!! $post->body !!}
                <!--figure class="my-4">
                    <img src="{{ asset('frontend/assets/img/post-landscape-5.jpg') }}" alt="" class="img-fluid">
                    <figcaption>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, odit? </figcaption>
                </figure>
                <p>Quis molestiae, dolorem consequuntur labore perferendis enim accusantium commodi optio, sequi magnam ad consectetur iste omnis! Voluptatibus, quia officia esse necessitatibus magnam tempore reprehenderit quo aspernatur! Assumenda, minus dolorem repellendus corporis corrupti quia temporibus repudiandae in. Sit rem aut, consectetur repudiandae perferendis nemo alias, iure ipsam omnis quam soluta, nobis animi quis aliquam blanditiis at. Dicta nemo vero sequi exercitationem.</p>
                <p>Architecto ex id at illum aperiam corporis, quidem magnam doloribus non eligendi delectus laborum porro molestiae beatae eveniet dolor odit optio soluta dolores! Eaque odit a nihil recusandae, error repellendus debitis ex autem ab commodi, maiores officiis doloribus provident optio, architecto assumenda! Nihil cum laboriosam eos dolore aliquid perferendis amet doloremque quibusdam odio soluta vero odit, ipsa, quisquam quod nulla.</p>
                <p>Consequuntur corrupti fugiat quod! Ducimus sequi nemo illo ad necessitatibus amet nobis corporis et quasi. Optio cum neque fuga. Ad excepturi magnam quisquam ex voluptatibus vitae aut nam quidem doloribus, architecto perspiciatis sit consequatur pariatur alias animi expedita quas? Et doloribus voluptatibus perferendis qui fugiat voluptatum autem facere aspernatur quidem quae assumenda iste, sit similique, necessitatibus laborum magni. Ea, dolores!</p>
                <p>Possimus temporibus rerum illo quia repudiandae provident sed quas atque. Ipsam adipisci accusamus iste optio illo aliquam molestias? Voluptatibus, veniam recusandae facilis nobis perspiciatis rem similique, nisi ad explicabo ipsa voluptatum, inventore molestiae natus adipisci? Fuga delectus quia assumenda totam aspernatur. Nobis hic ea rem, quaerat voluptate vero illum laboriosam omnis aspernatur labore, natus ex iusto ducimus exercitationem a officia.</p-->
            </div>
            <!-- End Single Post Content -->

            <!-- ======= Comments ======= -->
            <div class="comments">
                <h5 class="comment-title py-4">2 Comments</h5>
                <div class="comment d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-sm rounded-circle">
                    <img class="avatar-img" src="{{ asset('frontend/assets/img/person-5.jpg') }}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="flex-grow-1 ms-2 ms-sm-3">
                    <div class="comment-meta d-flex align-items-baseline">
                    <h6 class="me-2">Jordan Singer</h6>
                    <span class="text-muted">2d</span>
                    </div>
                    <div class="comment-body">
                       Lorem ipsum, dolor sit amet consectetur adipisicing elit. Non minima ipsum at amet doloremque qui magni, placeat deserunt pariatur itaque laudantium impedit aliquam eligendi repellendus excepturi quibusdam nobis esse accusantium.
                    </div>

                    <div class="comment-replies bg-light p-3 mt-3 rounded">
                    <h6 class="comment-replies-title mb-4 text-muted text-uppercase">2 replies</h6>

                    <div class="reply d-flex mb-4">
                        <div class="flex-shrink-0">
                        <div class="avatar avatar-sm rounded-circle">
                            <img class="avatar-img" src="{{ asset('frontend/assets/img/person-4.jpg') }}" alt="" class="img-fluid">
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-2 ms-sm-3">
                        <div class="reply-meta d-flex align-items-baseline">
                            <h6 class="mb-0 me-2">Brandon Smith</h6>
                            <span class="text-muted">2d</span>
                        </div>
                        <div class="reply-body">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                        </div>
                        </div>
                    </div>
                    <div class="reply d-flex">
                        <div class="flex-shrink-0">
                        <div class="avatar avatar-sm rounded-circle">
                            <img class="avatar-img" src="{{ asset('frontend/assets/img/person-3.jpg') }}" alt="" class="img-fluid">
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-2 ms-sm-3">
                        <div class="reply-meta d-flex align-items-baseline">
                            <h6 class="mb-0 me-2">James Parsons</h6>
                            <span class="text-muted">1d</span>
                        </div>
                        <div class="reply-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio dolore sed eos sapiente, praesentium.
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="comment d-flex">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-sm rounded-circle">
                    <img class="avatar-img" src="{{ asset('frontend/assets/img/person-2.jpg') }}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="flex-shrink-1 ms-2 ms-sm-3">
                    <div class="comment-meta d-flex">
                    <h6 class="me-2">Santiago Roberts</h6>
                    <span class="text-muted">4d</span>
                    </div>
                    <div class="comment-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto laborum in corrupti dolorum, quas delectus nobis porro accusantium molestias sequi.
                    </div>
                </div>
                </div>
            </div>
            <!-- End Comments -->

            <!-- ======= Comments Form ======= -->
            <div class="row justify-content-center mt-5">

                <div class="col-lg-12">
                    <h5 class="comment-title">Leave a Comment</h5>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="comment-name">Name</label>
                            <input type="text" class="form-control" id="comment-name" placeholder="Enter your name">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="comment-email">Email</label>
                            <input type="text" class="form-control" id="comment-email" placeholder="Enter your email">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="comment-message">Message</label>
                            <textarea class="form-control" id="comment-message" placeholder="Enter your name" cols="30" rows="10"></textarea>
                        </div>
                        <div class="col-12">
                            <input type="submit" class="btn btn-primary" value="Post comment">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Comments Form -->

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
                    <ul class="aside-tags list-unstyled">
                        <li><a href="category.html">Business</a></li>
                        <li><a href="category.html">Culture</a></li>
                        <li><a href="category.html">Sport</a></li>
                        <li><a href="category.html">Food</a></li>
                        <li><a href="category.html">Politics</a></li>
                        <li><a href="category.html">Celebrity</a></li>
                        <li><a href="category.html">Startups</a></li>
                        <li><a href="category.html">Travel</a></li>
                    </ul>
                </div>

            </div>
        </div>
        </div>
    </section>
</x-blog.index>