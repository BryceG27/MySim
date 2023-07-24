<x-layout page="Sign-up">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center">{{ __('auth.sign_up') }}</h2>
                <p class="alert alert-danger text-center" v-show="simError" v-html="simError"></p>
                <div v-show="checkData">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6">
                            <label for="icc" class="form-label">ICC</label>
                            <input v-model="icc" type="numeric" class="form-control" id="icc">
                        </div>
    
                        <div class="mb-3 col-12 col-md-6">
                            <label for="msisdn" class="form-label">MSISDN</label>
                            <input v-model="msisdn" type="numeric" class="form-control" id="msisdn">
                        </div>
                    </div>

                    <button type="button" @click="checkSim" class="btn btn-warning">Controlla dati</button>
                </div>

                <form v-show="!checkData" action="{{ route("register") }}" method="post" class="pt-2">
                    @csrf

                    <input v-model="icc" type="hidden" class="form-control" name="iccid">
                    <input v-model="msisdn" type="hidden" class="form-control" name="msisdn">

                    <div class="row">
                        <div class="mb-3 col-12 col-md-6">
                            <label for="name" class="form-label">{{ __('messages.fields.Name') }}</label>
                            <input type="text" name="Name" class="form-control" id="name" value="{{ old('Name') }}">
                            @error('Name')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="surname" class="form-label">{{ __('messages.fields.Surname') }}</label>
                            <input type="text" class="form-control" id="surname" name="Surname" value="{{ old('Surname') }}">
                            @error('Surname')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="email" class="form-label">EMail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="Phone" class="form-label">{{ __('messages.fields.Phone') }}</label>
                            <input type="Phone" class="form-control" id="Phone" name="Phone" value="{{ old('Phone') }}">
                            @error('Phone')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="Mobile" class="form-label">{{ __('messages.fields.Mobile') }}</label>
                            <input type="Mobile" class="form-control" id="Mobile" name="Mobile" value="{{ old('Mobile') }}">
                            @error('Mobile')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="Address" class="form-label">{{ __('messages.fields.Address') }}</label>
                            <input type="text" class="form-control" id="Address" name="Address" value="{{ old('Address') }}">
                            @error('Address')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="City" class="form-label">{{ __('messages.fields.City') }}</label>
                            <input type="text" class="form-control" id="City" name="City" value="{{ old('City') }}">
                            @error('City')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="County" class="form-label">{{ __('messages.fields.County') }}</label>
                            <input type="text" class="form-control" id="County" name="County" value="{{ old('County') }}">
                            @error('County')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="Country" class="form-label">{{ __('messages.fields.Country') }}</label>
                            <input type="text" class="form-control" id="Country" name="Country" value="{{ old('Country') }}">
                            @error('Country')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            @error('password')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('messages.fields.password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            @error('password_confirmation')
                            <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check d-flex flex-row justify-content-center">
                            <input @click="isCompany = !isCompany" type="checkbox" class="form-check-input" id="isCompany" name="isCompany">
                            <label class="form-check-label ps-2" for="isCompany">{{ __('messages.fields.company') }}</label>
                        </div>

                    </div>

                    <div v-if="isCompany" class="row">
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyName" class="form-label">{{ __('messages.company.name') }}</label>
                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" value="{{ old('CompanyName') }}" required>
                            @error('CompanyName')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyPhone" class="form-label">{{ __('messages.company.phone') }}</label>
                            <input type="numeric" class="form-control" id="CompanyPhone" name="CompanyPhone" value="{{ old('CompanyPhone') }}" required>
                            @error('CompanyPhone')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyAddress" class="form-label">{{ __('messages.company.address') }}</label>
                            <input type="text" class="form-control" id="CompanyAddress" name="CompanyAddress" value="{{ old('CompanyAddress') }}" required>
                            @error('CompanyAddress')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCity" class="form-label">{{ __('messages.company.city') }}</label>
                            <input type="text" class="form-control" id="CompanyCity" name="CompanyCity" value="{{ old('CompanyCity') }}" required>
                            @error('CompanyCity')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCounty" class="form-label">{{ __('messages.company.county') }}</label>
                            <input type="text" class="form-control" id="CompanyCounty" name="CompanyCounty" value="{{ old('CompanyCounty') }}" required>
                            @error('CompanyCounty')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCountry" class="form-label">{{ __('messages.company.country') }}</label>
                            <input type="text" class="form-control" id="CompanyCountry" name="CompanyCountry" value="{{ old('CompanyCountry') }}" required>
                            @error('CompanyCountry')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyVAT" class="form-label">{{ __('messages.company.vat') }}</label>
                            <input type="text" class="form-control" id="CompanyVAT" name="CompanyVAT" value="{{ old('CompanyVAT') }}" required>
                            @error('CompanyVAT')
                                <span class="fs-6 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 d-flex justify-content-center">
                        <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        <span class="fs-6 text-danger" v-show="use_captcha">{{ __('auth.use_captcha') }}</span>
                    </div>

                    <button type="button" class="btn btn-primary mb-2" id="registerBtn" @click="verifyCaptcha($event.target.type)">{{ __('auth.sign_up') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function recaptchaCallback() {
            document.getElementById('registerBtn').type = 'submit'
        }
    </script>
</x-layout>