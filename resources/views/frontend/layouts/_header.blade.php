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
            <a href="{{ route('login') }}" class="text-decoration-none text-white text-lg">Login</a>
        </div>
        <div class="bg-tertiary p-4 shadow tab-display">
            <a href="{{ route('login') }}" class="text-decoration-none text-white text-lg">Login</a>
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
            <li class="nav-item"><a href="{{ route('booming') }}"
                    class="text-decoration-none nav-link y-on-hover text-tertiary">Booming Offers</a>
            </li>
            <li class="nav-item"><a href="{{ route('shop') }}" class="text-decoration-none nav-link y-on-hover">All
                    Products</a>
            </li>
            @foreach ($parentCategories ?? [] as $parentCategory)
            <li
                class="nav-item {{ $parentCategory->subCategories->count() > 0 ? 'dropdown' : '' }} desktop-category-item">
                <a class="text-decoration-none nav-link y-on-hover {{ $parentCategory->subCategories->count() > 0 ? 'dropdown-toggle' : '' }}"
                    href="{{ route('shop', ['category' => $parentCategory->name]) }}"
                    id="categoryDropdown{{ $parentCategory->id }}" role="button" aria-expanded="false">
                    {{ $parentCategory->name }}
                </a>
                @if($parentCategory->subCategories->count() > 0)
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown{{ $parentCategory->id }}">
                    @foreach($parentCategory->subCategories as $subCategory)
                    <li>
                        <a href="{{ route('shop', ['category' => $subCategory->name]) }}"
                            class="dropdown-item text-dark y-on-hover-sm">
                            {{ $subCategory->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            <li class="nav-item mobile-category-item">
                <a class="text-decoration-none nav-link y-on-hover d-flex justify-content-center align-items-center"
                    data-bs-toggle="collapse" href="#mobileCategory{{ $parentCategory->id }}" role="button"
                    aria-expanded="false" aria-controls="mobileCategory{{ $parentCategory->id }}">
                    <span>{{ $parentCategory->name }}</span>
                    @if($parentCategory->subCategories->count() > 0)
                    <i class="fa-solid fa-chevron-down ms-1"></i>
                    @endif
                </a>
                @if($parentCategory->subCategories->count() > 0)
                <div class="collapse" id="mobileCategory{{ $parentCategory->id }}">
                    <ul class="list-unstyled ps-4">
                        @foreach($parentCategory->subCategories as $subCategory)
                        <li class="py-2">
                            <a href="{{ route('shop', ['category' => $subCategory->name]) }}"
                                class="text-decoration-none text-dark y-on-hover-sm">
                                {{ $subCategory->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </li>
            @endforeach
        </ul>

    </div>
</nav>
