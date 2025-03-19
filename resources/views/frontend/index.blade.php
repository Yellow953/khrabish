@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
<div class="slider">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item">
                <img src="{{ asset('frontend/images/hero.png') }}" class="d-block hero-img" alt="Hero Image">
                <h1 class="z-index-1 hero-title text-shadow-lg">Shop Your Favorite Khrabish Online</h1>
                <a href="{{ route('shop') }}" class="btn btn-tertiary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
                <!-- phone image goes here -->
            </div>
            <div class="carousel-item">
                <img src="{{ asset('frontend/images/hero-2.png') }}" class="d-block hero-img" alt="Hero Image 2">
                <h1 class="z-index-1 hero-title text-shadow-tertiary-lg">Take A Look At Our Kitchen Items</h1>
                <a href="{{ route('shop') }}" class="btn btn-secondary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
                <!-- kitchen items image goes here -->
            </div>
            <div class="carousel-item active">
                <img src="{{ asset('frontend/images/hero-3.png') }}" class="d-block hero-img" alt="Hero Image 3">
                <h1 class="z-index-1 hero-title text-shadow-secondary-lg">Get Your Kids Toys Here</h1>
                <a href="{{ route('shop') }}" class="btn btn-primary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
                <!-- kids toys image goes here -->
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <h1 class="text-primary text-shadow fw-bold text-center mb-4 animate-on-scroll fade-in">Categories</h1>
    <div class="owl-carousel owl-theme categories">
        @foreach ($categories as $category)
        <div class="category-item y-on-hover my-2">
            <a href="{{ route('shop', ['category' => $category->name]) }}" class="text-decoration-none text-primary">
                <div class="category-image">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                        class="img-fluid category-img shadow">
                </div>
                <div class="d-flex flex-column category-title">
                    <h4 class="text-center mt-2 text-shadow-sm">{{ $category->name }}</h4>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<div class="container py-5">
    <div class="row">
        <div class="col-4">
            <div class="card rounded-5 py-5 text-start align-items-start ps-3 animate-on-scroll slide-left">
                <img src="{{ asset('frontend/images/categories/banners/home-items.png') }}" alt="Home Items"
                    class="banner-card-img">
                <h5 class="text-primary text-shadow-sm z-index-1 pt-5">Home Items</h5>
                <div class=" d-flex flex-column y-on-hover-sm">
                    <a href="{{ route('shop', ['category' => 'Home Items']) }}"
                        class="btn btn-primary z-index-1 mb-5">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card rounded-5 py-5 text-start align-items-start ps-3 animate-on-scroll slide-up">
                <img src="{{ asset('frontend/images/categories/banners/kids.png') }}" alt="Kids Toys"
                    class="banner-card-img">
                <h5 class="text-secondary-light text-shadow-secondary-sm z-index-1 pt-5">Kids Toys</h5>
                <div class=" d-flex flex-column y-on-hover-sm">
                    <a href="{{ route('shop', ['category' => 'Kids']) }}" class="btn btn-secondary z-index-1 mb-5">Shop
                        Now</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card rounded-5 py-5 text-start align-items-start ps-3 animate-on-scroll slide-right">
                <img src="{{ asset('frontend/images/categories/banners/bathroom.png') }}" alt="Bathroom"
                    class="banner-card-img">
                <h5 class="text-primary text-shadow-sm z-index-1 pt-5">Bathroom</h5>
                <div class=" d-flex flex-column y-on-hover-sm">
                    <a href="{{ route('shop', ['category' => 'Bathroom']) }}"
                        class="btn btn-primary z-index-1 mb-5">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="col-md-12 mt-5 text-center">
        <h2 class="text-tertiary text-shadow-tertiary">Best Sellers</h2>
        <div class="owl-carousel owl-theme products">
            @foreach ($products as $product)
            <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                <img src="{{ $product->image }}" class="img-fluid product-img">
                <div class="card-body text-start">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="text-black">{{ $product->name }}</h5>
                        <div class="d-flex justify-content-end">
                            <h6 class="text-muted"><s>$40.00</s></h6>
                            <h5 class="text-secondary ms-2">$30.00</h5>
                        </div>
                    </div>
                    <div class="d-flex flex-column y-on-hover">
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View
                            Product</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-5 parallax rounded-5 px-0">
            <div class="parallax-overlay rounded-5">
                <h1 class="text-center mt-2 text-primary-light text-shadow">Kids Toys</h1>
                <div class="y-on-hover-sm">
                    <a href="#" class="btn btn-secondary px-4 mt-2">View More</a>
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="row pb-3">
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-1.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 1">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 1</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-2.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 2">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 2</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-1.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 1">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 1</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-2.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 2">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 2</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-1.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 1">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 1</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-2.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 2">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 2</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-1.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 1">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 1</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card product-card rounded-5">
                        <img src="{{ asset('frontend/images/toy-2.jpg') }}" class="card-img-top rounded-top-5"
                            alt="Toy 2">
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-between">
                                <h5 class="text-primary text-shadow-sm">Toy 2</h5>
                                <div class="d-flex justify-content-end">
                                    <h6 class="text-muted"><s>$40.00</s>
                                    </h6>
                                    <h5 class="text-secondary">$29.00</h5>
                                </div>
                            </div>
                            <div class=" d-flex flex-column y-on-hover-sm">
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-6">
            <div class="card rounded-5 py-5 text-start align-items-start ps-3 animate-on-scroll slide-left">
                <img src="{{ asset('frontend/images/categories/banners/cleaning.png') }}" alt="Cleaning Supplies"
                    class="banner-card-img">
                <h5 class="text-secondary-light text-shadow-tertiary-sm z-index-1 pt-5">Cleaning Supplies</h5>
                <div class=" d-flex flex-column y-on-hover-sm">
                    <a href="{{ route('shop', ['category' => 'Cleaning']) }}"
                        class="btn btn-secondary z-index-1 mb-5">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card rounded-5 py-5 text-start align-items-start ps-3 animate-on-scroll slide-right">
                <img src="{{ asset('frontend/images/categories/banners/phone-accessories.png') }}"
                    alt="Phone Accessories" class="banner-card-img">
                <h5 class="text-secondary-light text-shadow-secondary-sm z-index-1 pt-5">Phone Accessories</h5>
                <div class=" d-flex flex-column y-on-hover-sm">
                    <a href="{{ route('shop', ['category' => 'Phone & Accessories']) }}"
                        class="btn btn-secondary z-index-1 mb-5">Shop Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-5 mb-4 text-center">
            <h2 class="text-tertiary text-shadow-tertiary">Latest Additions</h2>
            <div class="owl-carousel owl-theme products">
                @foreach ($products as $product)
                <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                    <img src="{{ $product->image }}" class="img-fluid product-img">
                    <div class="card-body text-start">
                        <div class="d-flex flex-column justify-content-between">
                            <h5 class="text-black">{{ $product->name }}</h5>
                            <div class="d-flex justify-content-end">
                                <h6 class="text-muted"><s>$40.00</s></h6>
                                <h5 class="text-secondary ms-2">$30.00</h5>
                            </div>
                        </div>
                        <div class="d-flex flex-column y-on-hover">
                            <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View
                                Product</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-12 my-4">
            <div class="card text-center lighter-secondary-bg rounded-5 animate-on-scroll fade-in py-4">
                <div class="card-body">
                    <div class="row ps-4">
                        <div class="col-md-6 text-start d-flex flex-column justify-content-center">
                            <h2 class="text-tertiary text-shadow-secondary-sm mb-3 fw-bold">We Deliver</h2>
                            <p class="text-white text-shadow-secondary-sm mb-3">You can now order and we will deliver
                                your khrabish all
                                over
                                Lebanon</p>
                            <div class="d-flex">
                                <a href="{{ route('shop') }}" class="btn btn-tertiary y-on-hover">Shop
                                    Now</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img src="{{asset('frontend/images/delivery.png')}}" alt="" class="img-fluid email-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-5 text-center">
        <h2 class="text-tertiary text-shadow-tertiary">Products For You</h2>
        <div class="owl-carousel owl-theme products">
            @foreach ($products as $product)
            <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                <img src="{{ $product->image }}" class="img-fluid product-img">
                <div class="card-body text-start">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="text-black">{{ $product->name }}</h5>
                        <div class="d-flex justify-content-end">
                            <h6 class="text-muted"><s>$40.00</s></h6>
                            <h5 class="text-secondary ms-2">$30.00</h5>
                        </div>
                    </div>
                    <div class="d-flex flex-column y-on-hover">
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View
                            Product</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection