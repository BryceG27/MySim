<x-layout page="Login">
    <link rel="stylesheet" href="{{ asset('css/fortify.css') }}">

    <div class="container">

        @if ($errors->any())
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="row d-none d-xxl-block">
            <div style="height: 5vh"></div>
            <div class="col-xl-8 offset-xl-2 border border-secondary rounded">
                <div class="row">
                    <div class="col-xl-6 d-flex flex-column align-items-center align-self-center" style="height: 55vh">
                        <div class="my-auto p-5 rounded-2">

                            <form action="{{ route('login') }}" class="form-signin form-login m-auto p-5 rounded-2" method="post">
                                @csrf
                                <h1 class="text-center mb-3">
                                    Login
                                </h1>
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <a href="/forgot-password" class="mb-3">Forgot password?</a>

                                <div class="text-center">
                                    <button class="btn btn-info text-white">Enter&nbsp;<i class="bi bi-door-open fs-5"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-6 bg-info d-flex flex-column align-items-center align-self-center" style="height: 55vh">
                        <div class="my-auto p-5 text-white flip-card-inner rounded-2" id="flip_card_inner">
                            <div class="flip-card-front" :class="{'d-none' : showRegister}" id="flip_card_front">

                                <div class="m-auto py-4 p-0">

                                    <h1 class="text-center mb-3">
                                        Hello there
                                    </h1>
                                    <div class="">
                                        <p class="mb-3">Not registered yet? Click the button <strong>Sign-up</strong> <i class="bi bi-box-arrow-in-up"></i> to insert your data!</p>
                                        <p class="mb-3">If you have already your sim datas, press on the<strong> sim <i class="bi bi-sim"></i></strong>. </p>
                                    </div>
                                    <div class="text-center mt-4">
                                        <div class="btn-group">
                                            <a href="{{ route('register') }}" class="btn btn-outline-light"><i class="bi bi-box-arrow-in-up fs-5"></i></a>
                                            <button type="button" @click="iHaveASim" class="btn btn-light"><i class="bi bi-sim fs-5"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flip-card-back" :class="{'d-none' : !showRegister}" id="flip_card_back">
                                <div class="form-signin m-auto text-dark py-4 p-0" method="get">
                                    <h1 class="text-center text-white mb-3">
                                        Insert sim
                                    </h1>
                                    <p class="alert alert-danger text-center" v-show="simError" v-html="simError"></p>
                                    <div class="form-floating mb-3">
                                        <input type="numeric" class="form-control" id="iccid" placeholder="ICCID" name="iccid" v-model="icc">
                                        <label for="iccid">ICCID</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="numeric" class="form-control" id="iccid" placeholder="MSISDN" name="msisdn" v-model="msisdn">
                                        <label for="iccid">MSISDN</label>
                                    </div>
        
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-light" @click="iDontHaveASim"><i class="bi bi-arrow-left"></i></button>
                                            <button type="button" class="btn btn-light" @click="register"><i class="bi bi-check-lg"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="height: 5vh"></div>
        </div>

        <div class="row d-xxl-none">
            <form action="{{ route('login') }}" class="form-signin m-auto p-5 rounded-2" method="post">
                @csrf
                <h1 class="text-center mb-3">
                    Login
                </h1>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                    <label for="floatingPassword">Password</label>
                </div>

                <a href="/forgot-password" class="mb-3">Forgot password?</a>
                <div class="text-center mb-3">
                    <button class="btn btn-primary">Enter&nbsp;<i class="bi bi-door-open fs-5"></i></button>
                </div>
            </form>
        </div>
    </div>

</x-layout>