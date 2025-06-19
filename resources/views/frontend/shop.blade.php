@extends('frontend.layouts.app')

@section('title', 'Shop')

@section('content')
<section class="pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="text-primary fw-semibold mb-3">Shop By Category</h3>

                        <div class="owl-carousel owl-theme categories">
                            @foreach ($categories as $category)
                            <div class="category-item y-on-hover my-2">
                                <a href="{{ route('shop', ['category' => $category->name]) }}"
                                    class="text-decoration-none text-primary">
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
                </div>
                <div class="row mt-4">
                    @foreach($products as $product)
                    <div class="col-6 col-md-3 mb-3">
                        <a href="{{ route('product', $product->name) }}" class="text-decoration-none">
                            <div class="card item-card product-card overflow-hidden y-on-hover">
                                <img src="{{ $product->image }}" class="img-fluid product-img">
                                <div class="card-body">
                                    <div class="d-flex flex-column justify-content-between">
                                        <h5 class="text-black">{{ $product->name }}</h5>
                                        <p class="text-muted">{{ $product->category->name }}</p>
                                        <div class="d-flex justify-content-end">
                                            @if ($product->compare_price)
                                            <h6 class="text-muted"><s>${{ number_format($product->compare_price, 2)
                                                    }}</s></h6>
                                            @endif
                                            <h5 class="text-secondary ms-2">${{ number_format($product->getSalePrice(),
                                                2) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column y-on-hover">
                                        @if ($product->variants->count() == 0)
                                        <a href="{{ route('product', $product->name) }}"
                                            class="btn btn-tertiary mt-3 addToCart" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}" data-image="{{ $product->image }} "
                                            data-price="{{ $product->getSalePrice() }}">Add to cart</a>
                                        @else
                                        <a href="{{ route('product', $product->name) }}"
                                            class="btn btn-tertiary mt-3">View Product</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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