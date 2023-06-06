<x-admin.layout>
    <x-slot:title>ddd</x-slot:title>  
    <div col-6>
      <img src="{{ asset($croped) }}" width="500"> 
    </div>   
</x-admin.layout>

{{--<div class="modal fade modal-xl" id="bannerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Home Page Banner</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id='content'>
            <p class='info loading'>Loading full-size image...</p>

            <p class='notice hidden'>
                <strong>Drag</strong> the image to move it or <strong>resize</strong> the window to check its responsiveness.
            </p>

            <div class='frame'><img id='sample_picture'></div>

            <div id='controls' class='hidden'>
                <a href='#' id='rotate_left'  title='Rotate left'><i class='fa fa-rotate-left'></i></a>
                <a href='#' id='zoom_out'     title='Zoom out'><i class='fa fa-search-minus'></i></a>
                <a href='#' id='fit'          title='Fit image'><i class='fa fa-arrows-alt'></i></a>
                <a href='#' id='zoom_in'      title='Zoom in'><i class='fa fa-search-plus'></i></a>
                <a href='#' id='rotate_right' title='Rotate right'><i class='fa fa-rotate-right'></i></a>
            </div>

            <form action="/test/crop" method="POST">
                @csrf               

                <ul id='data' class='hidden'>
                    <div class='column'>
                        <li>x: <input type='text' id='x' name='x' class="input-group-text"></li>
                        <li>y: <input type='text' id='y' name='y' class="input-group-text"></li>
                    </div>
                    <div class='column'>
                        <li>width:  <input type='text' id='w' name='w' class="input-group-text"></li>
                        <li>height: <input type='text' id='h' name='h' class="input-group-text"></li>
                    </div>
                    <div class='column'>
                        <li>scale: <input type='text' id='scale' name='scale' class="input-group-text"></li>
                        <li>angle: <input type='text' id='angle' name='angle' class="input-group-text"></li>
                    </div>
                </ul>
               
                <input type="submit" class="btn btn-primary" value="Crop">
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Crop</button>
      </div>
    </div>
  </div>
</div>--}}