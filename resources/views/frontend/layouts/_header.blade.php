<div class="contact-header text-center fixed-top">
    <a href="tel:+96170231446" class="text-secondary-light text-shadow-secondary-sm text-decoration-none"><i
            class="fa fa-phone"></i>
        +961 70 231 446</a>
    <p class="text-shadow-sm"></p>
</div>
<nav class="navbar navbar-expand-lg fixed-top bg-white mt-4">
    <div class="container">
        <a class="navbar-brand" href="{{Route('home')}}">
            <img src="{{ asset('frontend/images/green-logo.png') }}" alt="Khrabish Logo" class="logo" />
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
            <ul class="navbar-nav ms-auto center-menu text-center">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }} y-on-hover"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a data-bs-toggle="dropdown" type="button"
                        class="nav-link dropdown-toggle {{ Route::is('shop') ? 'active' : '' }} y-on-hover"
                        href="{{ route('shop') }}">Shop</a>
                    <ul class="dropdown-menu z-index-1 mt-3 py-4 px-4 floating-nav text-center w-fit-content">
                        <li class="nav-item"><a href="{{ route('shop') }}" class="text-decoration-none nav-link">All
                                Products</a>
                        </li>
                        @foreach ($categories as $category)
                            <li class="nav-item"><a class="text-decoration-none nav-link"
                                    href="{{ route('shop', ['category' => $category->id]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('contact') ? 'active' : '' }} y-on-hover"
                        href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto m-hidden">
                <li class="nav-item">
                    <input type="text" class="form-control input" name="q" id="searchInput" placeholder="Type To Search"
                        autocomplete="off" autofocus>
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