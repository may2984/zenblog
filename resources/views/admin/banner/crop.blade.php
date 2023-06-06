@push('scripts') 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>    
        <script src="{{ asset('backend/assets/js/jquery.guillotine.min.js') }}"></script>

        <script type="text/javascript">
            jQuery(function() {

                var picture = $('#sample_picture')

                var camelize = function() {
                    var regex = /[\W_]+(.)/g
                    var replacer = function (match, submatch) { return submatch.toUpperCase() }
                    return function (str) { return str.replace(regex, replacer) }
                }()

                var showData = function (data) {
                    data.scale = parseFloat(data.scale.toFixed(4))
                    console.log( data.scale);
                    for(var k in data) { $('#'+k).val(data[k]) }
                }

                picture.on('load', function() {

                 //   picture.guillotine({width: 1947, height: 843});

                    picture.guillotine({ 
                        width: 1947, 
                        height: 843,
                        eventOnChange: 'guillotinechange' 
                    })

                    picture.guillotine('fit')

                    for (var i=0; i<5; i++) { picture.guillotine('zoomIn') }

                    // Show controls and data
                    $('.loading').remove()
                    $('.notice, #controls, #data').removeClass('hidden')
                    showData( picture.guillotine('getData') )

                    // Bind actions
                    $('#controls a').click(function(e) {
                        e.preventDefault()
                        action = camelize(this.id)
                        picture.guillotine(action)
                    })

                    // Update data on change
                    picture.on('guillotinechange', function(e, data, action) { showData(data) })
                })  
                
                    // Display random picture
                    picture.attr('src', '{{ asset("$original") }}')
            })
        </script>
    @endpush

    @push('css') 
        <link href='https://guillotine.js.org/css/jquery.guillotine.css' rel='stylesheet'>
        <link href='https://guillotine.js.org/css/demo.min.css' rel='stylesheet'>
        <link href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'>
    @endpush

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
            <input type="hidden" name="image" value="{{ $original }}">
            <input type="submit" class="btn btn-primary" value="Crop">
        </form>
    </div>