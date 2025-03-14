<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{Route('home')}}">
            <img src="{{ asset('frontend/images/khrabish-white.png') }}" alt="Khrabish Logo" class="logo" />
        </a>
        <ul class="navbar-nav ms-auto tab-display me-4">
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fa fa-search"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                    aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto desktop-hidden">
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fa fa-search"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                    aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto center-menu text-center floating-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }} y-on-hover"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('shop') ? 'active' : '' }} y-on-hover"
                        href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('contact') ? 'active' : '' }} y-on-hover"
                        href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto m-hidden floating-nav">
                <li class="nav-item">
                    <a class="nav-link y-on-hover" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fa fa-search"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link y-on-hover" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                        id="cartButton" aria-controls="offcanvasCart">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>