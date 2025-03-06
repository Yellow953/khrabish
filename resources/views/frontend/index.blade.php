@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    <div class="slider">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img src="{{ asset('frontend/images/hero.png') }}" class="d-block w-100 hero-img" alt="Hero Image">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/images/hero-2.jpg') }}" class="d-block w-100 hero-img" alt="Hero Image 2">
                </div>
                <div class="carousel-item active">
                    <img src="{{ asset('frontend/images/hero-3.png') }}" class="d-block w-100 hero-img" alt="Hero Image 3">
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="text-primary text-shadow fw-bold text-center mb-4">Categories</h2>
        <div class="owl-carousel owl-theme categories">
            @foreach($categories as $category)
                <div class="category-item bg-primary my-2">
                    <a href="{{ route('shop') }}" class="text-decoration-none text-primary-light">
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

    <div class="container py-5">
        <div class="row">
            <div class="col-5 parallax rounded-5 px-0">
                <div class="parallax-overlay rounded-5">
                    <h1 class="text-center mt-2 text-primary-light text-shadow">Kids Toys</h1>
                </div>
            </div>
            <div class="col-7">
                <div class="row">
                    <div class="col-6">
                        <div class="card lighter-primary-bg rounded-5">
                            <img src="{{ asset('frontend/images/toy-1.jpg') }}" class="card-img-top rounded-top-5"
                                alt="Toy 1">
                            <div class="card-body">
                                <h5 class="text-primary text-shadow">Toy 1</h5>
                                <p class="text-secondary-light text-shadow-sm">Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit.</p>
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card lighter-primary-bg rounded-5">
                            <img src="{{ asset('frontend/images/toy-2.jpg') }}" class="card-img-top rounded-top-5"
                                alt="Toy 2">
                            <div class="card-body">
                                <h5 class="text-primary text-shadow">Toy 2</h5>
                                <p class="text-secondary-light text-shadow-sm">Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit.</p>
                                <a href="#" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
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