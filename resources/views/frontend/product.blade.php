@extends('frontend.layouts.app')

@section('title', ucwords($product->name))

@section('content')
<section class="pb-5">
    <div class="container pb-5">
        <div class="row">
            <div class="col-md-6 mt-5">
                <div class="card mb-3 position-relative overflow-hidden">
                    <!-- Main Image -->
                    <img class="card-img img-fluid" src="{{ asset($product->image) }}" alt="Product image"
                        id="product-detail">
                </div>

                <!-- Secondary Images Carousel -->
                @if ($product->secondary_images)
                <div class="row">
                    <div id="multi-item-example" class="col-12 carousel slide carousel-multi-item pointer-event"
                        data-bs-ride="carousel">
                        <div class="carousel-inner product-links-wap" role="listbox">
                            <div class="carousel-item active">
                                <div class="row">
                                    @foreach ($product->secondary_images as $image)
                                    <div class="col-4 p-2">
                                        <a href="#" class="secondary-image" data-image="{{ asset($image->path) }}">
                                            <img class="card-img secondary-img border img-fluid"
                                                src="{{ asset($image->path) }}">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- col end -->
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h2 text-primary">{{ $translate ? $translator->translate($product->name) :
                            $product->name }}</h1>
                        <div class="my-3">
                            <div class="d-flex justify-content-between my-2">
                                <span class="fw-bold">{{__('landing.category')}}:</span> {{ $translate ?
                                $translator->translate($product->category->name) : $product->category->name }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-content-center">
                            <div class="fw-bold">{{__('landing.price')}}</div>
                            <div><span class="fw-bold text-success">{{ $currency->code }}{{
                                    number_format($product->price * $currency->rate) }}</span>
                                <span class="fs-7 text-muted text-decoration-line-through">{{ $currency->code
                                    }}{{number_format($product->compare_price * $currency->rate)
                                    }}</span>
                            </div>
                        </div>

                        <div class="mt-3 w-100">
                            <input type="number" min="0" step="1" value="1" name="quantity" id="quantity"
                                class="form-control my-2" required>
                            <a href="#" id="addToCart" class="btn btn-cta my-2 shake">
                                {{__('landing.addtocart')}} <i class="fa-solid fa-cart-shopping"></i>
                            </a>
                            <a href="#" id="buyNow" class="btn btn-cta my-2 shake">
                                {{__('landing.buynow')}} <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button text-primary fw-bold collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo"
                                    aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    {{__('landing.description')}}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    {{ $translate ? $translator->translate($product->description) :
                                    $product->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <h2 class="my-4 text-center text-primary">{{__('landing.similar_products')}}</h2>

            <!-- Start Carousel Wrapper -->
            <div id="multi-item-example" class="col-12 carousel slide carousel-multi-item pointer-event"
                data-bs-ride="carousel">
                <!-- Start Slides -->
                <div class="carousel-inner product-links-wap" role="listbox">
                    @foreach ($products->chunk(4) as $key => $chunk)
                    <!-- Group products into chunks of 4 -->
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <!-- Set the first slide as active -->
                        <div class="row">
                            @foreach ($chunk as $pr)
                            <div class="col-3">
                                <a href="{{ route('product', $pr->name) }}" class="text-decoration-none">
                                    <img class="card-img border img-fluid" src="{{ asset($pr->image) }}"
                                        alt="{{ $pr->name }}">
                                    <h5 class="category-title text-center text-primary mt-2">{{ $translate ?
                                        $translator->translate($pr->name) : $pr->name
                                        }}</h5>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- End Slides -->
            </div>
            <!-- End Carousel Wrapper -->

            {{-- <div class="row mt-5">
                <h2 class="my-4 text-center text-primary">{{__('landing.faq')}}</h2>

                <div class="accordion" id="accordionPanelsStayOpenExample1">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne1" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseOne1">
                                How do I determine my ring size when shopping online?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne1" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                To find your ring size, you can use a printable ring size chart available on most
                                jewelry
                                websites. Alternatively, you can visit a local jeweler to have your finger measured
                                professionally.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseTwo2" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseTwo2">
                                Are the gemstones in the jewelry natural or lab-created?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Many jewelry pieces offer both natural and lab-created gemstone options. Check the
                                product
                                description or inquire with the seller to know the origin of the gemstones.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseThree3" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseThree3">
                                How should I care for my jewelry to maintain its shine and durability?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Proper care varies depending on the type of jewelry and materials used. Generally, it's
                                recommended to store jewelry in a dry, clean place, away from moisture and chemicals.
                                Regular cleaning with a soft cloth and mild soap solution can help maintain its shine.
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const product = {
            id: "{{ $product->id }}",
            name: "{{ $product->name }}",
            image: "{{ asset($product->image) }}",
            price: "{{ $product->price }}",
        };

        const addToCartBtn = document.getElementById('addToCart');
        const buyNowBtn = document.getElementById('buyNow');
        const quantityInput = document.getElementById('quantity');

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

        addToCartBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const quantity = parseInt(quantityInput.value) || 1;

            let cart = getCart();

            const existingProduct = cart.find(item => item.id === product.id);
            if (existingProduct) {
                existingProduct.quantity += quantity;
            } else {
                cart.push({ ...product, quantity });
            }

            saveCart(cart);
            alert('Product added to cart!');
        });

        buyNowBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const quantity = parseInt(quantityInput.value) || 1;

            let cart = getCart();

            const existingProduct = cart.find(item => item.id === product.id);
            if (existingProduct) {
                existingProduct.quantity += quantity;
            } else {
                cart.push({ ...product, quantity });
            }

            saveCart(cart);

            window.location.href = "{{ route('checkout') }}";
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const secondaryImages = document.querySelectorAll('.secondary-image');
        const mainImage = document.getElementById('product-detail');

        secondaryImages.forEach(image => {
            image.addEventListener('click', function(event) {
                event.preventDefault();
                const newImageSrc = this.getAttribute('data-image');
                mainImage.setAttribute('src', newImageSrc);
            });
        });
    });
</script>
@endsection