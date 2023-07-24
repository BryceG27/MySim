<x-layout page="Reset Password">
    <div class="container">

        <div class="row">
            <div class="py-3">
                <h1 class="text-center">
                    Reset della password
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="/reset-password" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="hidden" value="{{ $request->email }}" name="email">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Conferma Password">
                        <label for="password_confirmation">Conferma Password</label>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-sim"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout>