<x-layout page="Aggiungi SIM">
    
    <link rel="stylesheet" href="{{ asset('css/fortify.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="container-fluid pt-5">
        
        @if(isset($message))

        <div class="row py-3">
            <h1 class="text-center text-white">{{ $message }}</h1>
        </div>
        
        @else
        
        <div class="row pt-4" v-show="!registered">
            <div class="col-lg-3 offset-md-2 rounded-3 my-2 border border-2 bg-light">
                <div class="row p-3">
                    <h3 class="text-center mb-2">
                        Gi&agrave; registrato?
                    </h3>
                    <p class="card-text mb-3">
                        Hai scansionato correttamente il codice, ma manca un ulteriore passaggio per ultimare la richiesta. Sei sei già registrato, clicca su <i class="fw-bold bi bi-check"></i> per fare l'accesso, altrimenti premi su <i class="fw-bold bi bi-x"></i> per registrarti.
                    </p>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" type="button" @click="registered = true">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <a class="btn btn-primary" href="/register/{{ $sim->iccid }}/{{ $sim->msisdn }}/ok">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row pt-4" v-show="registered">
            <div class="col-lg-3 offset-md-2 rounded-3 my-2 border border-2 bg-light">
                <div class="row p-3">
                    <h3 class="text-center mb-3">
                        Immetti qui sotto i dati
                    </h3>
                    <form method="post" action="{{ route('login', compact('sim')) }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                        <div class="btn-group col-12">
                            <button class="btn btn-outline-primary" id="loginBTN" type="button">
                                <i class="bi bi-door-open"></i>
                            </button>
                        </div>
                    </form>
                    <hr>
                    <p class="mx-4 mb-3 text-center">
                        {{ __('auth.sign_up_message') }} <a @click="registered = false" href="#">{{ __('auth.sign_up') }}</a>
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    <script>
        function recaptchaCallback() {
            document.getElementById('loginBTN').type = 'submit'
        }
    </script>
</x-layout>