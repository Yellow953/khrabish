@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
<div class="slider">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item">
                <img src="{{ asset('shop/images/hero.png') }}" class="d-block w-100 hero-img" alt="Hero Image">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('shop/images/hero-2.jpg') }}" class="d-block w-100 hero-img" alt="Hero Image 2">
            </div>
            <div class="carousel-item active">
                <img src="{{ asset('shop/images/hero-3.png') }}" class="d-block w-100 hero-img" alt="Hero Image 3">
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <h2 class="hero-title fw-bold text-center mb-4">Categories</h2>
    <div class="owl-carousel owl-theme categories">
        @foreach($categories as $category)
        <div class="category-item bg-white">
            <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                <div class="category-image">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-fluid category-img">
                </div>
                <div class="d-flex flex-column category-title">
                    <h5 class="text-center mt-2">{{ $category->name }}</h5>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                },
                576: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                992: {
                    items: 4,
                }
            }
        });
    });
</script>
@endsection