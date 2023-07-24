<x-layout page="Login">
    <link rel="stylesheet" href="{{ asset('css/fortify.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="container-fluid pt-xl-3" v-cloak>

        @if (session('status'))
        <div class="row">
            <div class="col-lg-3 offset-md-2 rounded-3 ">
                <div class="mb-4 alert alert-success p-4">
                    {{ session('status') }}
                </div>
            </div>
        </div>
        @endif

        @if (session('warning'))
        <div class="row">
            <div class="col-lg-3 offset-md-2 rounded-3 ">
                <div class="mb-4 alert alert-warning p-4">
                    {{ session('warning') }}
                </div>
            </div>
        </div>
        @endif

        <div class="row pt-2">
            <div class="bg-light col-xxl-3 col-md-4 offset-lg-2 col-10 offset-1 rounded-3 my-2 border border-2" v-show="loginPanel == 'Login'">
                <div class="row py-3">
                    <h3 class="text-center">Login</h3>
                </div>
                <form action="{{ route('login') }}" class="" method="post">
                    @csrf
                    <div class="form-floating text-dark m-3">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="john.doe@me.com">
                        <label for="email">{{ __('auth.email_address') }}</label>
                        @error('email')
                        <span class="fs-6 text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating text-dark m-3 mt-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                        @error('password')
                        <span class="fs-6 text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="text-center">
                            <button type="button" @click="loginPanel = 'Forgot'" class="btn btn-link">{{ __('auth.password_forgotten') }}?</button>
                        </div>
                    </div>

                    <p class="text-danger text-center" v-show="use_captcha">{{ __('auth.use_captcha') }}</p>
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>

                    <div class="mx-4 row pb-3">
                        <button class="btn btn-primary rounded-3" type="button" id="loginBTN" @click="verifyCaptcha($event.target.type)">
                            <i class="bi bi-door-open fs-5"></i>
                        </button>
                    </div>
                </form>
                <hr>
                <p class="mx-4 mb-3 text-center">
                    {{ __('auth.sign_up_message') }} <a @click="loginPanel = 'Register'" href="#">{{ __('auth.sign_up') }}</a>
                </p>
            </div>

            <div class="bg-light col-xxl-3 col-md-4 offset-lg-2 col-10 offset-1 rounded-3 my-2 border border-2" v-show="loginPanel == 'Register'">
                <div class="row py-3">
                    <h3 class="text-center">{{ __('auth.welcome') }}</h3>
                </div>
                <div class="mx-4 row">
                    <p class="mb-3">
                        {{ __('auth.sim') }} <strong>SIM <i class="bi bi-sim"></i></strong>.
                    </p>
                    <p class="mb-3">
                        {{ __('auth.register') }} <i class="bi bi-box-arrow-in-up"></i>.
                    </p>
                </div>
                <div class="row mb-4">
                    <div class="btn-group">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-in-up"></i>
                        </a>
                        <button type="button" class="btn btn-primary" @click="loginPanel = 'insertSim'">
                            <i class="bi bi-sim"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="mb-3 text-center">
                    {{ __('auth.sign_in_message') }} <a @click="loginPanel = 'Login'" href="#">{{ __('auth.sign_in') }}</a>
                </div>
            </div>

            <div class="bg-light col-xxl-3 col-md-4 offset-lg-2 col-10 offset-1 rounded-3 my-2 border border-2" v-show="loginPanel == 'insertSim'">
                <div class="row py-3">
                    <h3 class="text-center">{{ __('auth.insert_data') }}</h3>
                </div>
                <form class="">

                    <p class="alert alert-danger text-center" v-show="simError" v-html="simError"></p>

                    <div class="form-floating text-dark mb-3">
                        <input type="numeric" class="form-control" id="iccid" placeholder="ICCID" name="iccid" v-model="icc">
                        <label for="iccid">ICCID</label>
                    </div>
                    <div class="form-floating text-dark mb-4">
                        <input type="numeric" class="form-control" id="msisdn" placeholder="MSISDN" name="msisdn" v-model="msisdn">
                        <label for="iccid">MSISDN</label>
                    </div>

                    <div class="mx-4 row pb-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" @click="loginPanel = 'Register'">
                                <i class="bi bi-arrow-left fs-5"></i>
                            </button>
                            <button type="button" class="btn btn-primary" @click.prevent="register">
                                <i class="bi bi-door-open fs-5"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                <p class="mx-4 mb-3 text-center">
                    {{ __('auth.sign_in_message') }} <a @click="loginPanel = 'Login'" href="#">{{ __('auth.sign_in') }}</a>
                </p>
            </div>

            <div class="bg-light col-xxl-3 col-md-4 offset-lg-2 col-10 offset-1 rounded-3 my-2 border border-2" v-show="loginPanel == 'Forgot'">
                <div class="row py-3">
                    <h3 class="text-center">{{ __('auth.password_forgotten') }}</h3>
                </div>
                <form action="/forgot-password" method="post">
                    @csrf

                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="email-reset" name="email" placeholder="email">
                        <label for="email-reset">{{ __('auth.your_email') }}</label>
                    </div>
                    <div class="mx-4 row pb-3">
                        <button type="submit" class="btn btn-primary">{{ __('auth.password_reset') }}</button>
                    </div>
                    <hr>
                </form>
                <div class="mb-3 text-center">
                    {{ __('auth.sign_in_message') }} <a @click="loginPanel = 'Login'" href="#">{{ __('auth.sign_in') }}</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function recaptchaCallback() {
            document.getElementById('loginBTN').type = 'submit'
        }
    </script>
</x-layout>