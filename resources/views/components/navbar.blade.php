<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between justify-content-xxl-start">
            <a href="/" class="d-flex align-items-center mb-0 text-decoration-none link-danger">
                {{-- <i class="bi bi-building"></i>&nbsp; {{ $company ?? '' }} --}}
                <img src="{{ Storage::url('img/mysim.png') }}" alt="{{ $company ?? '' }}" width="150">
            </a>
            
            @auth
            @if (Auth::user()->UserType != 2)
                
            <ul class="nav nav-pills col-12 col-lg-auto ms-3 me-lg-auto mb-2 justify-content-center mb-md-0 d-none d-xxl-flex">
                <li class="me-1">
                    <a href="{{ route('userManagement') }}" class="nav-link px-2 {{Route::is('userManagement') ? 'active' : 'link-dark' }}">Utenti</a>
                </li>
                <li class="me-1">
                    <a href="{{ route('simManagement') }}" class="nav-link px-2 {{ Route::is('simManagement') ? 'active' : 'link-dark' }}">Sim</a>
                </li>
                
                @if (Auth::user()->UserType == 0)
                <li class="me-1">
                    <a href="{{ route('adminManagement') }}" class="nav-link px-2 {{ Route::is('adminManagement') ? 'active' : 'link-dark' }}">Admin</a>
                </li>
                @endif
                
                <li class="me-1">
                    <a href="{{ route('credentials') }}" class="nav-link px-2 {{ Route::is('credentials') ? 'active' : 'link-dark' }}">Credenziali API</a>
                </li>

                <li class="me-1">
                    <a href="{{ route('rates') }}" class="nav-link px-2 {{ Route::is('rates') ? 'active' : 'link-dark' }}">Profili SIM</a>
                </li>
            </ul>
            @endif
            
            <div class="d-flex flex-row ms-auto">
                <div class="dropdown text-end d-none d-xxl-block pe-4">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ App::getLocale() }}
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li>
                            <form action="{{ route('setLocale', ['lang' => 'en']) }}" method="post" class="{{ App::isLocale('en') ? 'd-none' : '' }}">
                                @csrf
                                <button type="submit" class="dropdown-item">en</button>
                            </form>
                            <form action="{{ route('setLocale', ['lang' => 'it']) }}" method="post" class="{{ App::isLocale('it') ? 'd-none' : '' }}">
                                @csrf
                                <button type="submit" class="dropdown-item">it</button>
                            </form>
                        </li>
                    </ul>
                </div>
  
                <div class="dropdown text-end d-none d-xxl-block ms-auto">
                    <a href="#" class="d-block text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->Name }} {{ Auth::user()->Surname }}
                    </a>

                    <ul class="dropdown-menu text-small">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">{{ __('messages.edit_profile') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>

                <div class="text-end me-0 nav d-xxl-none">
                    
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav" aria-controls="offcanvasNav">
                        {{ Auth::user()->Name }} {{ Auth::user()->Surname }}
                    </button>
                </div>

                <div class="offcanvas offcanvas-end d-xxl-none" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavLabel">{{ __('messages.welcome') }}, {{ Auth::user()->Name }}.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav me-auto mb-2">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile') }}">{{ __('messages.edit_profile') }}</a>
                            </li>
                            
                            
                            @if (Auth::user()->UserType != 2)
                            <hr>
                            
                            
                            <li class="nav-item">
                                <a href="{{ route('userManagement') }}" class="nav-link {{ Route::is('userManagement') ? 'link-secondary' : '' }}">Utenti</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('simManagement') }}" class="nav-link {{ Route::is('simManagement') ? 'link-secondary' : '' }}">Sim</a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('credentials') }}" class="nav-link {{ Route::is('credentials') ? 'link-secondary' : '' }}">Credenziali</a>
                            </li>
                            @endif

                            @if (Auth::user()->UserType == 0)
                            
                            <li class="nav-item"><a href="{{ route('adminManagement') }}" class="nav-link {{ Route::is('adminManagement') ? 'link-secondary' : '' }}">Admin</a></li>
                            
                            @endif
                            <hr>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        @guest
        <div class="dropdown text-end pe-4 ms-auto">
            <a class="nav-link dropdown-toggle {{ Route::is('login') ? 'text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ App::getLocale() }}
            </a>
            <ul class="dropdown-menu text-small">
                <li>
                    <form action="{{ route('setLocale', ['lang' => 'en']) }}" method="post" class="{{ App::isLocale('en') ? 'd-none' : '' }}">
                        @csrf
                        <button type="submit" class="dropdown-item">en</button>
                    </form>
                    <form action="{{ route('setLocale', ['lang' => 'it']) }}" method="post" class="{{ App::isLocale('it') ? 'd-none' : '' }}">
                        @csrf
                        <button type="submit" class="dropdown-item">it</button>
                    </form>
                </li>
            </ul>
        </div>
        @endguest
    </div>
</header>