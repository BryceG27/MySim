<x-layout page="{{ $user->Name }}'s profile">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="btn-group">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary" title="Home">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <button class="btn btn-outline-warning" type="button" title="Modifica profilo" @click="updateUser = !updateUser">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <p class="text-center fs-1">{{ __('messages.welcome') }}, <strong>{{ $user->Name }}</strong>.&nbsp;</p>
            </div>
        </div>

        @if (session('status'))
            <div class="row py-2">
                <div class="col-md-6 offset-md-3 alert alert-success">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <form action="{{ route('updateProfile') }}" method="post" class="row">
            @csrf
            @method('put')

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.Name') }}: <strong>{{ $user->Name ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="Name" name="Name" placeholder="{{ __('messages.fields.Name') }}" value="{{ $user->Name }}">
                    <label for="Name">{{ __('messages.fields.Name') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.Surname') }}: <strong>{{ $user->Surname ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="Surname" name="Surname" placeholder="{{ __('messages.fields.Surname') }}" value="{{ $user->Surname }}">
                    <label for="Surname">{{ __('messages.fields.Surname') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.Phone') }}: <strong>{{ $user->Phone ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="Phone" name="Phone" placeholder="{{ __('messages.fields.Phone') }}" value="{{ $user->Phone }}">
                    <label for="Phone">{{ __('messages.fields.Phone') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.Address') }}: <strong>{{ $user->Address ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="Address" name="Address" placeholder="{{ __('messages.fields.Address') }}" value="{{ $user->Address }}">
                    <label for="Address">{{ __('messages.fields.Address') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.City') }}: <strong>{{ $user->City ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="City" name="City" placeholder="{{ __('messages.fields.City') }}" value="{{ $user->City }}">
                    <label for="City">{{ __('messages.fields.City') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.county') }}: <strong>{{ $user->County ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="County" name="County" placeholder="Provincia" value="{{ $user->County }}">
                    <label for="County">{{ __('messages.fields.county') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.state') }}: <strong>{{ $user->Country ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="Country" name="Country" placeholder="Paese" value="{{ $user->Country }}">
                    <label for="Country">{{ __('messages.fields.state') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.fields.lang') }}: <strong>{{ $user->Lang ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <select name="Lang" id="Lang" class="form-select">
                        <option value="en" {{ $user->Lang == 'en' ? 'selected' : '' }}>English</option>
                        <option value="it" {{ $user->Lang == 'it' ? 'selected' : '' }}>Italiano</option>
                    </select>
                    <label for="Lang">{{ __('messages.fields.selectedLang') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.name') }}: <strong>{{ $user->CompanyName ?? __('messages.fields.not_setted')  }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyName" name="CompanyName" placeholder="John Doe SRL" value="{{ $user->CompanyName }}">
                    <label for="CompanyName">{{ __('messages.company.name') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.phone') }}: <strong>{{ $user->CompanyPhone ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyPhone" name="CompanyPhone" placeholder="Telephone" value="{{ $user->CompanyPhone }}">
                    <label for="CompanyPhone">{{ __('messages.company.phone') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.address') }}: <strong>{{ $user->CompanyAddress ?? __('messages.fields.not_setted')  }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyAddress" name="CompanyAddress" placeholder="Address" value="{{ $user->CompanyAddress }}">
                    <label for="CompanyAddress">{{ __('messages.company.address') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.city') }}: <strong>{{ $user->CompanyCity ?? __('messages.fields.not_setted')  }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyCity" name="CompanyCity" placeholder="City" value="{{ $user->CompanyCity }}">
                    <label for="CompanyCity">Citt&agrave; Azienda</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.county') }}: <strong>{{ $user->CompanyCounty ?? __('messages.fields.not_setted')  }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyCounty" name="CompanyCounty" placeholder="County" value="{{ $user->CompanyCounty }}">
                    <label for="CompanyCounty">{{ __('messages.company.county') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.country') }}: <strong>{{ $user->CompanyCountry ?? __('messages.fields.not_setted') }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyCountry" name="CompanyCountry" placeholder="Country" value="{{ $user->CompanyCountry }}">
                    <label for="CompanyCountry">{{ __('messages.company.country') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="mb-3" v-show="!updateUser">
                    {{ __('messages.company.vat') }}: <strong>{{ $user->CompanyVAT ?? __('messages.fields.not_setted')  }}</strong>
                </div>
                <div class="form-floating mb-3" v-show="updateUser">
                    <input type="text" class="form-control" id="CompanyVAT" name="CompanyVAT" placeholder="John Doe SRL" value="{{ $user->CompanyVAT }}">
                    <label for="CompanyVAT">{{ __('messages.company.vat') }}</label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <a href="{{ route('change-password') }}" class="btn btn-outline-warning" v-show="!updateUser">{{ __('messages.fields.changePassButton') }}</a>
            </div>

            <div>
                <button class="btn btn-outline-success" v-show="updateUser" type="submit">Salva</button>
            </div>
        </form>
    </div>
</x-layout>