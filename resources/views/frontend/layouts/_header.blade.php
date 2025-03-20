<div class="bg-tertiary text-center py-1 overflow-hidden">
    <div class="announcement-slider">
        <p class="text-white text-shadow-tertiary-sm mb-0">We provide delivery all over Lebanon!</p>
        <p class="text-white text-shadow-tertiary-sm mb-0">We're ready to serve you 24/7</p>
        <p class="text-white text-shadow-tertiary-sm mb-0">Shop your Favorite Khrabish Online</p>
    </div>
</div>
<div class="bg-white border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <a class="navbar-brand ms-md-5" href="{{ route('home')}}">
            <img src="{{ asset('frontend/images/green-logo.png') }}" alt="Khrabish Logo" class="logo" />
        </a>
        <div class="d-flex align-items-center">
            <ul class="navbar-nav text-center desktop-display flex-row">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }} y-on-hover"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('contact') ? 'active' : '' }} y-on-hover"
                        href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav text-center tab-display flex-row">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }} y-on-hover"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('contact') ? 'active' : '' }} y-on-hover"
                        href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
        <div class="position-relative">
            <div class="align-items-center desktop-display">
                <input type="text" class="form-control input px-5" name="q" id="searchInput"
                    placeholder="Type To Search" autocomplete="off">
                <a class="nav-link y-on-hover ms-2" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                    id="cartButton" aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </div>
            <div class="align-items-center tab-display">
                <input type="text" class="form-control input px-5" name="q" id="searchInput"
                    placeholder="Type To Search" autocomplete="off">
                <a class="nav-link y-on-hover ms-2" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                    id="cartButton" aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </div>
            <div id="searchResults" class="list-group position-absolute w-100 mt-1 shadow bg-white"></div>
        </div>

        <div class="bg-tertiary p-4 shadow">
            <a href="tel:+96170231446" class="text-decoration-none text-white text-shadow-tertiary-sm text-lg"><i
                    class="fa fa-phone"></i> +961 70 231 446</a>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg fixed-top bg-white">
    <div class="navbar-nav ms-auto m-display">
        <input type="text" class="form-control input px-5" name="q" id="searchInput" placeholder="Type To Search"
            autocomplete="off">
        <div class="nav-item">
            <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
                aria-controls="offcanvasCart">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
        </div>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center shadow border-bottom py-3" id="navbarNav">
        <ul class="navbar-nav text-center">
            <div class="m-display flex-column text-center">
                <li class="nav-item"><a href="{{ route('home') }}"
                        class="text-decoration-none nav-link y-on-hover">Home</a>
                </li>
                <li class="nav-item"><a href="{{ route('contact') }}"
                        class="text-decoration-none nav-link y-on-hover">Contact</a>
            </div>
            <li class="nav-item"><a href="{{ route('shop') }}" class="text-decoration-none nav-link y-on-hover">All
                    Products</a>
            </li>
            @foreach ($categories as $category)
                <li class="nav-item"><a class="text-decoration-none nav-link y-on-hover"
                        href="{{ route('shop', ['category' => $category->name]) }}">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>

    </div>
</nav>