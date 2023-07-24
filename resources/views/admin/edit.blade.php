<x-layout page="{{ $user->Name }} {{ $user->Surname }}">
    <div class="container">
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

        @if (session('message'))
        <div class="row">
            <div class="col-md-6 col-10 offset-md-3 offset-1">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-3 text-start">
                <div class="btn-group">
                    <a href="{{ route("userManagement") }}" class="btn btn-outline-primary" title="Go to users management">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <button class="btn btn-outline-warning" title="Show sims" data-bs-toggle="modal" data-bs-target="#simModal">
                        <i class="bi bi-sim"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="text-center">Edit <strong>{{ $user->Name }} {{ $user->Surname }}</strong>'s profile</h1>
                
                <form v-show="!checkData" action="{{ route("updateUser", ['user' => $user]) }}" method="post">
                    @csrf
                    @method('put')
                    <div v-show="page == 1" class="row">

                        <div class="mb-3 col-12 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="Name" class="form-control" id="name" value="{{ $user->Name }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" name="Surname" value="{{ $user->Surname }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="email" class="form-label">EMail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->EMail }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="Phone" class="form-label">Phone</label>
                            <input type="Phone" class="form-control" id="Phone" name="Phone" value="{{ $user->Phone }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="Address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="Address" name="Address" value="{{ $user->Address }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="City" class="form-label">City</label>
                            <input type="text" class="form-control" id="City" name="City" value="{{ $user->City }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="County" class="form-label">County</label>
                            <input type="text" class="form-control" id="County" name="County" value="{{ $user->County }}">
                        </div>
                        
                        <div class="mb-3 col-12 col-md-6">
                            <label for="Country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="Country" name="Country" value="{{ $user->Country }}">
                        </div>
                        
                    </div>

                    <div v-show="page == 1">
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here about the customer" id="notes" name="Notes" style="height: 100px">{{ $user->Notes }}</textarea>
                            <label for="notes">Comments</label>
                        </div>
                        <div class="row">
                            <div class="btn-group col-md-1 offset-md-5">
                                <button type="submit" class="btn btn-outline-success" title="Save user informations">
                                    <i class="bi bi-check-lg"></i>
                                </button>

                                <button @click="changeUserStatus({{ $user->id }})" type="button" title="{{ $user->Active ? 'Disable user' : 'Activate user' }}" class="btn {{ $user->Active ? 'btn-outline-danger' : 'btn-outline-info' }}">
                                    <i class="bi {{ $user->Active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div v-show="!checkData && page == 2" class="row">
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" value="{{ $user->CompanyName }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyPhone" class="form-label">Company Phone number</label>
                            <input type="numeric" class="form-control" id="CompanyPhone" name="CompanyPhone" value="{{ $user->CompanyPhone }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyAddress" class="form-label">Company Address</label>
                            <input type="text" class="form-control" id="CompanyAddress" name="CompanyAddress" value="{{ $user->CompanyAddress }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCity" class="form-label">Company City</label>
                            <input type="text" class="form-control" id="CompanyCity" name="CompanyCity" value="{{ $user->CompanyCity }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCounty" class="form-label">Company County</label>
                            <input type="text" class="form-control" id="CompanyCounty" name="CompanyCounty" value="{{ $user->CompanyCounty }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyCountry" class="form-label">Company Country</label>
                            <input type="text" class="form-control" id="CompanyCountry" name="CompanyCountry" value="{{ $user->CompanyCountry }}">
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="CompanyVAT" class="form-label">Company VAT</label>
                            <input type="text" class="form-control" id="CompanyVAT" name="CompanyVAT" value="{{ $user->CompanyVAT }}">
                        </div>
                    </div>
                    
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end">
                            <li class="page-item" :class="{disabled : page == 1}">
                                <a class="page-link" @click="page = 1">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item" :class="{disabled : page == 2}">
                                <a class="page-link" @click="page = 2">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="simModal" tabindex="-1" aria-labelledby="simModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="simModalLabel"><strong>{{ $user->Name }} {{ $user->Surname }}</strong></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @forelse ($user->sims as $sim)
                <div class="card" :class="{}">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>Sim #<strong>{{ $sim->id }}</strong></h2>
                                </div>

                                <div class="col-md-4">
                                    {!! QrCode::size(50)->generate(route('scanSim', ['iccid' => $sim->iccid, 'msisdn' => $sim->msisdn])) !!}
                                    QR di registrazione
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-outline-warning" @click="updateSimData({{ $sim->id }})">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row pt-2" v-show="updating">
                                <div class="col-md-10 offset-md-1">
                                    <div class="alert alert-warning text-center">
                                        - Updating Sim
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p class="card-text"><strong>ICCID</strong>: <span id="iccid_{{ $sim->id }}">{{ $sim->iccid }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text"><strong>MSISDN</strong>: <span id="msisdn_{{ $sim->id }}">{{ $sim->msisdn }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Status</strong>: <span id="status_{{ $sim->id }}">{{ $sim->status }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Rate</strong>: <span id="rate_{{ $sim->id }}">{{ $sim->rate }}</span></p>
                                </div>
                                <div class="col-12 pt-4">
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Voice</th>
                                                <th>Data</th>
                                                <th>SMS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <td id="voice_{{ $sim->id }}">{{ $sim->voice }}</td>
                                                <td id="data_{{ $sim->id }}">{{ $sim->data }}</td>
                                                <td id="sms_{{ $sim->id }}">{{ $sim->sms }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <h2 class="text-center py-3">Empty</h2>
                @endforelse
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary"><i class="bi bi-check-lg"></i></button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-layout>