<x-admin.layout>
    <x-slot:title>ddd</x-slot:title>
    @push('css')
    @kropifyStyles 
    @endpush   
    
    @push('scripts')    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    @kropifyScripts

    <script>
        $('#profile_picture').Kropify({                
            setRatio:1,
            preview:'.profile-picture-box',
            /*
            cancelButtonText:'Cancel',
            resetButtonText:'Reset',
            cropButtonText:'Crop',
            maxSize:2097152, //2 MB (maximum size)
            */
            errors:function(type, message){
                alert(message);
            }
        });

        $(function(){   

            $( "#registerForm" ).submit(function( event ) {
                alert( "Handler for .submit() called." );
                event.preventDefault();
            });

        });

    </script>   
    @endpush
    <div class="container">
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" id="registerForm">
            @csrf
            <div class="form-group">
                <div class="profile-picture-box"></div>
                <label for="" class="d-block">Profile picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                @error('profile-picture')
                    <span class="text-danger">{{ $message }}</span>
                @enderror                            
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    Register
                </button>
            </div>
        </form>
    </div>
</x-admin.layout>