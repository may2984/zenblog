<div class="col-12 text-center mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="display-4">Our Team</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil sint sed, fugit distinctio ad eius itaque deserunt doloribus harum excepturi laudantium sit officiis et eaque blanditiis. Dolore natus excepturi recusandae.</p>
        </div>
    </div>
</div>
@foreach( $data AS $team )
<div class="col-lg-4 text-center mb-5">
    <img src="{{ asset('frontend/assets/img/person-1.jpg') }}" alt="" class="img-fluid rounded-circle w-50 mb-4">
    <h4>Cameron Williamson</h4>
    <span class="d-block mb-3 text-uppercase">Founder &amp; CEO</span>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis, perspiciatis repellat maxime, adipisci non ipsam at itaque rerum vitae, necessitatibus nulla animi expedita cumque provident inventore? Voluptatum in tempora earum deleniti, culpa odit veniam, ea reiciendis sunt ullam temporibus aut!</p>
</div>
@endforeach