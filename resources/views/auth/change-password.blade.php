<x-layout page="Change Password">
    <div class="container">
        @if (session('passwordChanged'))
        <div class="row">
            <div class="alert alert-success">
                {{ session('passwordChanged') }}
            </div>
        </div>
        @endif

        <div class="row pt-3">
            <h1 class="text-center mb-4">Cambia password</h1>
        </div>
        @if ($errors->any())
        <div class="row">
            <div class="col-md-6 offset-md-3 offset-1 col-10">
                <div class="alert alert-danger">
                    <ul class="">
                        @foreach ($errors->all() as $error)
                        <li class="">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="{{ route('change-password') }}" method="post">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Current Password">
                        <label for="current_password">Password corrente</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="New Password">
                        <label for="password">Nuova Password</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password">
                        <label for="password_confirmation">Conferma Password</label>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-primary">Conferma cambio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>