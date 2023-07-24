<x-layout page='Home'>
    {{-- @dd(Auth::user()->Lang) --}}
    @if (Auth::user()->Active == 1 && Auth::user()->email_verified_at != null)
    <div class="container">
        <div class="row justify-content-between py-3">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">{{ __('messages.welcome') }}, {{ Auth::user()->Name }}</h1>
            </div>
            <div class="col-md-3 text-end">
                <div class="btn-group">
                    <button title="Aggiungi una SIM" type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#addSim">
                        <i class="bi bi-sim"></i>
                    </button>
                    <button title="Aggiorna dati delle SIM" type="button" class="btn btn-outline-primary" @click="updateSimDataByUser({{ Auth::user()->id }})">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
        </div>

        @if (isset($_GET['verified']) && $_GET['verified'] == 1)
            <div class="row pb-3">
                <div class="col-md-6 offset-md-3 alert alert-success">
                    - {{ __('auth.verified') }}
                </div>
            </div>
        @endif
        
        @if (session('message'))
            <div class="row py-3">
                <div class="col-md-6 offset-md-3 alert alert-success">
                    - {{ session('message') }}
                </div>
            </div>
        @endif

        <div class="row my-3" v-show="updating">
            <div class="col-md-10 offset-md-1">
                <div class="alert alert-warning text-center">
                    - {{ __('messages.sim_updating') }}
                </div>
            </div>
        </div>

        <div class="row my-3" v-show="aliasError">
            <div class="col-md-10-offset-md-1">
                <div class="alert alert-danger text-center">
                    <span v-show="errorName == 'empty'">
                        - {{ __('messages.empty_alias') }}
                    </span>
                    <span v-show="errorName == 'length'">
                        - {{ __('messages.length') }}
                    </span>
                    <span v-show="errorName == 'special'">
                        - {{ __('messages.special') }}:<br>
                        <strong>a-z , 0-9 , _ , -</strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse (Auth::user()->sims as $sim)
            <div class="col-sm-6 pt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="row pb-2" id="divSim-{{ $sim->id }}">
                                <div class="col-md-10">
                                    <h2>
                                        <span id="spanSimName-{{ $sim->id }}">{{ empty($sim->name) ? __('messages.sim_not_named') : $sim->name}}</span>
                                    </h2>
                                </div>
                                <div class="col-md-2">
                                    <i class="fs-5 bi bi-pencil link-dark" @click="editName({{ $sim->id }})"></i>
                                </div>
                            </div>
                            <div class="row pb-2 d-none" id="divSimInput-{{ $sim->id }}">
                                <div class="col-md-10">
                                    <input type="text" max="25" class="form-control" v-model="simName" placeholder="{{ empty($sim->name) ? __('messages.sim_not_named') : $sim->name }}">
                                </div>
                                <div class="col-md-2">
                                    <i class="fs-5 bi bi-pencil link-dark" @click="saveSimName({{ $sim->id }})"></i>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>ICCID</strong>: {{ $sim->iccid }}</p>
                                <p class="card-text"><strong>MSISDN</strong>: {{ $sim->msisdn }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text">
                                    <strong>Rate</strong>: {{ $sim->rate }}
                                </p>
                                <p class="card-text">
                                    <strong>Voice</strong>: {{ $sim->voice }}
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text">
                                    <strong>Data</strong>: {{ $sim->data }}
                                </p>
                                <p class="card-text">
                                    <strong>SMS</strong>: {{ $sim->sms }}
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text">
                                    <strong>Status</strong>: {{ __("messages.sim_status.{$sim->status}") }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-sm-6 offset-md-3">
                <div class="alert alert-warning text-center">
                    - {{ __('messages.no_sim') }}
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="addSim" tabindex="-1" aria-labelledby="addSimLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newSimLabel">{{ __('messages.associate_sim') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="simStatus">
                        <div class="col-md-8 offset-md-2 alert" :class="simStatus">@{{ simError }}</div>
                    </div>
                    <form action="">
                        <div class="form-floating mb-3">
                            <input type="numeric" class="form-control" id="iccid" placeholder="1111111111" v-model="icc">
                            <label for="iccid">ICCID</label>
                            <i class="text-danger" ref="iccid"></i>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="numeric" class="form-control" id="msisdn" placeholder="1111111111" v-model="msisdn">
                            <label for="msisdn">MSISDN</label>
                            <i class="text-danger" ref="msisdn"></i>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="submit" @click.prevent="associateSim({{ Auth::user()->id }})" class="btn btn-outline-success">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="window.location.reload()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        @if (session('status') == 'verification-link-sent')
        <div class="col-6 offset-3">
            <div class="mb-4 alert alert-success text-center pt-3">
                {{ __('auth.resend-email') }}
            </div>
        </div>
        @endif
        <div style="height: 60vh" class="d-flex align-items-center text-center justify-content-center">
            @if (Auth::user()->Active == 0)

            <h1>{{ __('messages.inactive') }}</h1>    

            @else

            <div class="flex-column">
                <h1>{{ __('messages.not_verified') }}</h1>
                
                <form action="/email/verification-notification" method="post">
                    @csrf
                    <button type="submit" class="btn btn-link">{{ __('messages.email_not_received') }}</button>
                </form>
            </div>

            @endif
        </div>
    @endif
</x-layout>  