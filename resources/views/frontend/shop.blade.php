@extends('frontend.layouts.app')

@section('title', 'Shop')

@section('content')
    <section class="pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="text-primary fw-semibold mb-3 text-shadow">Shop By Category</h3>

                            <div class="owl-carousel owl-theme categories">
                                @foreach ($categories as $category)
                                    <div class="category-item y-on-hover my-2">
                                        <a href="{{ route('shop') }}" class="text-decoration-none text-primary">
                                            <div class="category-image">
                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                                    class="img-fluid category-img">
                                            </div>
                                            <div class="d-flex flex-column category-title">
                                                <h4 class="text-center mt-2 text-shadow-sm">{{ $category->name }}</h4>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        @foreach($products as $product)
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <a href="{{ route('product', $product->name) }}" class="text-decoration-none">
                                    <div class="card item-card lighter-primary-bg overflow-hidden shadow-sm">
                                        <img src="{{ $product->image }}" class="img-fluid product-img">
                                        <div class="card-body">
                                            <h5 class="card-title text-center text-shadow-sm">{{ $product->name }}</h5>
                                            <div class="d-flex flex-column y-on-hover">
                                                <a href="{{ route('product', $product->name) }}"
                                                    class="btn btn-primary mt-3">View
                                                    Product</a>
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
@endsection