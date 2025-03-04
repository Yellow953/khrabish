@extends('shop.layouts.app')
@section('title', 'Home')

@section('content')
    <div class="slider">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img src="{{ asset('shop/images/hero.png') }}" class="d-block hero-img" alt="Hero Image">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('shop/images/hero-2.jpg') }}" class="d-block hero-img" alt="Hero Image 2">
                </div>
                <div class="carousel-item active">
                    <img src="{{ asset('shop/images/hero-3.png') }}" class="d-block hero-img" alt="Hero Image 3">
                </div>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div>
            <h2 class="hero-title fw-bold text-center mb-4">Categories</h2>
            <div class="owl-carousel owl-theme categories">
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/cleaning.png') }}" alt="Cleaning"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Cleaning</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/home-items.png') }}" alt="Home Items"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Home Items</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/bathroom.png') }}" alt="Bathroom"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Bathroom</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/kitchen.png') }}" alt="Kitchen"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Kitchen</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/kids.png') }}" alt="Kids"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Kids</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/party.png') }}" alt="Party & Deco"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Party & Deco</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/personal-care.png') }}" alt="Personal Care"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Personal Care</h5>
                        </div>
                    </a>
                </div>
                <div class="category-item bg-white">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                        <div class="category-image">
                            <img src="{{ asset('shop/images/categories/phone-accessories.png') }}" alt="Phone & Accessories"
                                class="img-fluid category-img">
                        </div>
                        <div class="d-flex flex-column category-title">
                            <h5 class="text-center mt-2">Phone & Accessories</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection