<header class="header_area">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Brand and toggle get grouped for better mobile display -->
            <a class="navbar-brand logo_h" href="{{ url('/') }}"><img src="{{ asset('landing/image/Logo.png') }}"
                    alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                <ul class="nav navbar-nav menu_nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    @if (Route::currentRouteName() == 'home')
                        <li class="nav-item"><a class="nav-link" href="#about">About us</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    @endif
                    @if (Route::has('login'))
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link"">
                                        Dashboard
                                    </a></li>
                            @else
                                <li class="nav-item"><a href="{{ route('user.reservation') }}" class="nav-link"">
                                        Your Booking
                                    </a></li>
                                <li class="nav-item">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                     document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">
                                    Log in
                                </a></li>

                            @if (Route::has('register'))
                                <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">
                                        Register
                                    </a></li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>
