@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
<div class="slider">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item">
                <img src="{{ asset('frontend/images/hero.png') }}" class="d-block hero-img" alt="Hero Image">
                <h1 class="z-index-1 hero-title">Shop Your Favorite Khrabish Online</h1>
                <a href="{{ route('shop') }}?category=Cleaning"
                    class="btn btn-tertiary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('frontend/images/hero-2.png') }}" class="d-block hero-img" alt="Hero Image 2">
                <h1 class="z-index-1 hero-title">Take A Look At Our Kitchen Items</h1>
                <a href="{{ route('shop') }}?category=Kitchen"
                    class="btn btn-secondary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
            </div>
            <div class="carousel-item active">
                <img src="{{ asset('frontend/images/hero-3.png') }}" class="d-block hero-img" alt="Hero Image 3">
                <h1 class="z-index-1 hero-title">Get Your Kids Toys Here</h1>
                <a href="{{ route('shop') }}?category=Kids"
                    class="btn btn-primary px-4 mt-2 hero-button y-on-hover">Shop Now</a>
            </div>
        </div>
    </div>
</div>

<div class="container py-3">
    <div class="col-md-12 mt-5 text-center">
        <h2 class="text-tertiary">Booming Offers</h2>
        <div class="owl-carousel owl-theme products">
            @forelse ($booming_offers as $product)
            <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                <a href="{{ route('product', $product->name) }}" class="text-decoration-none">
                    <img src="{{ $product->image }}" class="img-fluid product-img">
                </a>
                <div class="card-body text-start">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="text-black">{{ $product->name }}</h5>
                        <div class="d-flex justify-content-end">
                            @if ($product->compare_price)
                            <h6 class="text-muted"><s>${{ number_format($product->compare_price, 2) }}</s></h6>
                            @endif
                            <h5 class="text-secondary ms-2">${{ number_format($product->getSalePrice(), 2) }}</h5>
                        </div>
                    </div>
                    <div class="d-flex flex-column y-on-hover">
                        @if ($product->variants->count() == 0)
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3 addToCart"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                            data-image="{{ $product->image }} " data-price="{{ $product->getSalePrice() }}">Add to
                            cart</a>
                        @else
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View Product</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center mt-4">No Booming Offers Yet...</p>
            @endforelse
        </div>
    </div>
</div>

<div class="container pt-2 pt-md-5">
    <h1 class="text-primary fw-bold text-center mb-4 animate-on-scroll fade-in">Categories</h1>
    <div class="owl-carousel owl-theme categories">
        @foreach ($categories as $category)
        <div class="category-item y-on-hover my-2">
            <a href="{{ route('shop', ['category' => $category->name]) }}" class="text-decoration-none text-primary">
                <div class="category-image">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                        class="img-fluid category-img shadow">
                </div>
                <div class="d-flex flex-column category-title">
                    <h4 class="text-center mt-2">{{ $category->name }}</h4>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<div class="container py-3">
    <div class="col-md-12 mt-5 text-center">
        <h2 class="text-tertiary">Best Sellers</h2>
        <div class="owl-carousel owl-theme products">
            @foreach ($best_sellers as $product)
            <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                <a href="{{ route('product', $product->name) }}" class="text-decoration-none">
                    <img src="{{ $product->image }}" class="img-fluid product-img">
                </a>
                <div class="card-body text-start">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="text-black">{{ $product->name }}</h5>
                        <div class="d-flex justify-content-end">
                            @if ($product->compare_price)
                            <h6 class="text-muted"><s>${{ number_format($product->compare_price, 2) }}</s></h6>
                            @endif
                            <h5 class="text-secondary ms-2">${{ number_format($product->getSalePrice(), 2) }}</h5>
                        </div>
                    </div>
                    <div class="d-flex flex-column y-on-hover">
                        @if ($product->variants->count() == 0)
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3 addToCart"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                            data-image="{{ $product->image }} " data-price="{{ $product->getSalePrice() }}">Add to
                            cart</a>
                        @else
                        <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View Product</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-3 py-md-5">
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card text-center lighter-secondary-bg rounded-5 animate-on-scroll fade-in py-4">
                <div class="card-body">
                    <div class="row ps-4">
                        <div class="col-7 col-md-6 text-start d-flex flex-column justify-content-center">
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

        <div class="col-md-12 mb-5 text-center">
            <h2 class="text-tertiary mt-5">Latest Additions</h2>
            <div class="owl-carousel owl-theme products">
                @foreach ($latest_additions as $product)
                <div class="card item-card product-card overflow-hidden y-on-hover mx-2 my-3">
                    <a href="{{ route('product', $product->name) }}" class="text-decoration-none">
                        <img src="{{ $product->image }}" class="img-fluid product-img">
                    </a>
                    <div class="card-body text-start">
                        <div class="d-flex flex-column justify-content-between">
                            <h5 class="text-black">{{ $product->name }}</h5>
                            <div class="d-flex justify-content-end">
                                @if ($product->compare_price)
                                <h6 class="text-muted"><s>${{ number_format($product->compare_price, 2) }}</s></h6>
                                @endif
                                <h5 class="text-secondary ms-2">${{ number_format($product->getSalePrice(), 2) }}</h5>
                            </div>
                        </div>
                        <div class="d-flex flex-column y-on-hover">
                            @if ($product->variants->count() == 0)
                            <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3 addToCart"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-image="{{ $product->image }} " data-price="{{ $product->getSalePrice() }}">Add to
                                cart</a>
                            @else
                            <a href="{{ route('product', $product->name) }}" class="btn btn-tertiary mt-3">View
                                Product</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getCart() {
            const cart = document.cookie
                .split('; ')
                .find(row => row.startsWith('cart='))
                ?.split('=')[1];
            return cart ? JSON.parse(decodeURIComponent(cart)) : [];
        }

        function saveCart(cart) {
            document.cookie = `cart=${encodeURIComponent(JSON.stringify(cart))}; path=/; max-age=${30 * 24 * 60 * 60}`;
        }

        document.querySelectorAll('.addToCart').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const image = this.getAttribute('data-image');
                const price = parseFloat(this.getAttribute('data-price')) || 0;

                const quantity = 1;
                const variantKey = `${id}-default`;

                let cart = getCart();
                const existingProduct = cart.find(item => item.id === id);

                if (existingProduct) {
                    existingProduct.quantity += quantity;
                } else {
                    cart.push({
                        id,
                        name,
                        image,
                        basePrice: price,
                        finalPrice: price,
                        quantity,
                        variantKey,
                        variants: []
                    });
                }

                saveCart(cart);
                alert('Product added to cart!');
            });
        });
    });
</script>
@endsection