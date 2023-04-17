<x-login>    
    <form method="POST" action="{{ route('admin.register.save') }}">
        @csrf

        <div class="container-sm pt-5">        
            <div class="row align-items-center">
                <div class="col"> </div>    
                <div class="col">
                    @if($errors->any())
                        <ul class="list-group">
                            @foreach($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>        
                <div class="col"> </div>
            </div>
        </div>          
        
        <div class="container-sm pt-5">
            <div class="row align-items-center">
                <div class="col"> </div>
                @if(Session::has('error'))
                <div class="col">
                    {{ Session::get('error') }}
                </div>
                @endif
                <div class="col"> </div>
            </div>
        </div>   
        
        <div class="container-sm pt-5">            
            <div class="row align-items-center">
                <div class="col"></div>
                <div class="col card p-3">
                <div class="form-floating mb-3"><h1>Register</h1></div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" name="name" value="{{ old('name') }}">
                        <label for="floatingInput">Name</label>
                    </div>               
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Confirm Password" name="confirm_password">
                        <label for="floatingConfirmPassword">Confirm Password</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3">
                        <input class="btn btn-primary" type="submit" value="Sign Up">
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>

        <div class="container mt-3">
            <div class="row align-items-start">
                <div class="col"> </div>
                <div class="col">Already registered! <a href="{{ route('admin.login') }}">Login</a></div>
                <div class="col"> </div>
            </div>
        </div>         
    </form>
</x-guest>