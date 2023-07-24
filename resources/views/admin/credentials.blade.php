<x-layout page="CSL Credentials">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">
                    Set CSL credentials for API
                </h1>
            </div>
        </div>
        
        @if (session('message'))
        <div class="row my-3">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
        </div>
        @endif
        
        <div class="row my-3 d-flex justify-content-center">
            <div class="col-md-auto">    
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Client ID</th>
                            <th>Client Secret</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{ $CSL->client_id }}</td>
                            <td>{{ $CSL->client_secret }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('updateCredentials') }}" method="POST">
                    @csrf
                    @method('put')
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="client_id" placeholder="Client ID" value="{{ $CSL->client_id }}" id="client_id">
                        <label for="client_id">Client ID</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="client_secret" placeholder="Client Secret" value="{{ $CSL->client_secret }}" id="client_secret">
                        <label for="client_secret">Client Secret</label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        
</x-layout>