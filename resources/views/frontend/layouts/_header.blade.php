<div class="bg-white border-bottom">
    <div class="d-flex justify-content-md-between justify-content-center align-items-center">
        <a class="navbar-brand ms-md-5" href="{{ route('home')}}">
            <img src="{{ asset('frontend/images/green-logo.png') }}" alt="Khrabish Logo" class="logo" />
        </a>
        <div class="position-relative">
            <div class="align-items-center desktop-display">
                <input type="text" class="form-control input px-5" name="q" id="searchInput"
                    placeholder="Type To Search" autocomplete="off">
                <a class="nav-link y-on-hover ms-2 cartButton" data-bs-toggle="offcanvas" href="#offcanvasCart"
                    role="button" aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </div>
            <div class="align-items-center tab-display">
                <input type="text" class="form-control input px-5" name="q" id="searchInput"
                    placeholder="Type To Search" autocomplete="off">
                <a class="nav-link y-on-hover ms-2 cartButton" data-bs-toggle="offcanvas" href="#offcanvasCart"
                    role="button" aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </div>
            <div id="searchResults" class="list-group position-absolute w-100 mt-1 shadow bg-white"></div>
        </div>

        <div class="bg-tertiary p-4 shadow desktop-display">
            <a href="{{ route('login') }}"
                class="text-decoration-none text-white text-shadow-tertiary-sm text-lg">Login</a>
        </div>
        <div class="bg-tertiary p-4 shadow tab-display">
            <a href="{{ route('login') }}"
                class="text-decoration-none text-white text-shadow-tertiary-sm text-lg">Login</a>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg fixed-top bg-white">
    <div class="navbar-nav ms-auto me-auto m-display justify-content-center align-items-center">
        <input type="text" class="form-control input px-4" name="q" id="searchInput" placeholder="Type To Search"
            autocomplete="off">
        <div class="nav-item">
            <a class="nav-link cartButton" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button"
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