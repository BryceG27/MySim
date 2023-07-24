<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom">
        <div class="col-md-1 mb-2 mb-md-0">
            <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                {{ $company ?? '' }}
            </a>
        </div>
        
        @auth
        @if (Auth::user()->UserType != 2)
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 d-none d-xxl-flex nav-pills">
            <li><a href="{{ route('userManagement') }}" class="nav-link px-2 {{ Route::is('userManagement') ? 'active' : '' }}">Utenti</a></li>
            <li><a href="{{ route('simManagement') }}" class="nav-link px-2 {{ Route::is('simManagement') ? 'active' : '' }}">Sim</a></li>
            
            @if (Auth::user()->UserType == 0)
            
            <li><a href="{{ route('adminManagement') }}" class="nav-link px-2 {{ Route::is('adminManagement') ? 'link-secondary' : '' }}">Admin</a></li>
            
            @endif
            <li><a href="{{ route('credentials') }}" class="nav-link px-2 {{ Route::is('credentials') ? 'link-secondary' : '' }}">Credenziali</a></li>
        </ul>
        @endif
        @endauth
        
        @guest
        
        <div class="col-md-3 text-end d-xxl-none">
            <div class="btn-group">
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign-up</a>
            </div>
        </div>
        
        @endguest
        
        @auth
        
        <div class="dropdown text-end nav d-none d-xxl-block">
            <a href="#" class="d-block nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->Name }} {{ Auth::user()->Surname }}
            </a>
            
            <ul class="dropdown-menu text-small" style="">
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">Gestisci profilo</a>
                </li>
                
                @if (Auth::user()->UserType == 0)
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="{{ route('adminManagement') }}" class="dropdown-item">Admin</a>
                </li>
                @endif
                
                <li>
                    <hr class="dropdown-divider">
                </li>
                
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
            
        </div>
        
        
        <div class="text-end nav d-xxl-none">
            <div class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav" aria-controls="offcanvasNav">{{ Auth::user()->Name }} {{ Auth::user()->Surname }}</div>
        </div>
        
        <div class="offcanvas offcanvas-end d-xxl-none" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavLabel">Hi, {{ Auth::user()->Name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Gestisci profilo</a>
                    </li>
                    
                    <hr>
                    
                    <li class="nav-item">
                        <a href="{{ route('userManagement') }}" class="nav-link {{ Route::is('userManagement') ? 'link-secondary' : '' }}">Utenti</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('simManagement') }}" class="nav-link {{ Route::is('simManagement') ? 'link-secondary' : '' }}">Sim</a>
                    </li>
                    
                    @if (Auth::user()->UserType == 0)
                    
                    <li class="nav-item"><a href="{{ route('adminManagement') }}" class="nav-link {{ Route::is('adminManagement') ? 'link-secondary' : '' }}">Admin</a></li>
                    
                    @endif
                    <li class="nav-item"><a href="{{ route('credentials') }}" class="nav-link {{ Route::is('credentials') ? 'link-secondary' : '' }}">Credenziali</a></li>
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
        @endauth
    </header>
</div>