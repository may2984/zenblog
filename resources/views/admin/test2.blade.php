<x-admin.layout>
    <x-slot:title>ddd</x-slot:title>  
    @push('scripts') 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Image-Cropping-Library-Jcrop/js/jquery.Jcrop.js"></script>

    <script type="text/javascript">


        jQuery(function($){

            var jcrop_api;

            $('#target').Jcrop({
            onChange:   showCoords,
            onSelect:   showCoords,
            onRelease:  clearCoords
            },function(){
            jcrop_api = this;
            });

            $('#coords').on('change','input',function(e){
            var x1 = $('#x1').val(),
                x2 = $('#x2').val(),
                y1 = $('#y1').val(),
                y2 = $('#y2').val();
            jcrop_api.setSelect([x1,y1,x2,y2]);
            });
        });

        // Simple event handler, called from onChange and onSelect
        // event handlers, as per the Jcrop invocation above
        function showCoords(c)
        {
            $('#x1').val(c.x);
            $('#y1').val(c.y);
            $('#x2').val(c.x2);
            $('#y2').val(c.y2);
            $('#w').val(c.w);
            $('#h').val(c.h);
        };

        function clearCoords()
        {
            $('#coords input').val('');
        };



        </script>

    @endpush

    @push('css') 
        <link href='{{ asset("backend/assets/css/jquery.Jcrop.css") }}' rel='stylesheet'>
    @endpush

    <div col-6>
      <img src="{{ asset($original) }}" id="target">
    </div>   

    <form action="/test/crop2" method="POST">
        @csrf

        <div class="inline-labels">
            <label>X1 <input type="text" size="4" id="x1" name="x1" /></label>
            <label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>
            <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
            <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
            <label>W <input type="text" size="4" id="w" name="w" /></label>
            <label>H <input type="text" size="4" id="h" name="h" /></label>
        </div>
        <input type="hidden" name="image" value="{{ $original }}">
        <input type="submit" value="Submit">
  </form>
</x-admin.layout>