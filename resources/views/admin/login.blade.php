<x-login>    
    <form method="POST" action="{{ route('admin.login.check') }}">
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
            @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @enderror
        </div>          
        
        <div class="container-sm pt-5">
            <div class="row align-items-center">
                <div class="col"></div>                
                
                @if(Session::has('message'))
                <div class="alert alert-danger col">                   
                    {{ Session::get('message') }}
                </div>
                @endif

                @if(Session::has('success'))
                <div class="alert alert-success col">  
                    {{ Session::get('success') }}
                </div>
                @endif               

                <div class="col"></div>
            </div>
        </div>       

        <div class="container-sm pt-5">            
            <div class="row align-items-center">
                <div class="col"></div>
                <div class="col card p-3">
                    <div class="form-floating mb-3"><h1>Login</h1></div>               
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3">
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>

        <div class="container mt-3">
            <div class="row align-items-start">
                <div class="col"> </div>
                <div class="col">Don't have an account! Please <a href="{{ route('admin.register') }}">Register</a> </div>
                <div class="col"> </div>
            </div>
        </div>
    </form> 
</x-guest>