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
                
                <!-- Filter and Sort Section -->
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <!-- Filter Toggle Button (Mobile) -->
                            <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="offcanvas" 
                                    data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                                <i class="fa-solid fa-filter me-2"></i>Filters
                            </button>
                            
                            <!-- Sort Dropdown -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="mb-0 fw-semibold">Sort by:</label>
                                <form method="GET" action="{{ route('shop') }}" id="sortForm" class="d-inline">
                                    @if(request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    @if(request('category_id'))
                                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                    @endif
                                    @if(request('min_price'))
                                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                    @endif
                                    @if(request('max_price'))
                                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                    @endif
                                    @if(request('on_sale'))
                                        <input type="hidden" name="on_sale" value="{{ request('on_sale') }}">
                                    @endif
                                    <select name="sort_by" id="sortBy" class="form-select" style="width: auto; display: inline-block;">
                                        <option value="newest" {{ request('sort_by', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                        <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                        <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <!-- Filter Sidebar (Desktop) -->
                    <div class="col-md-3 d-none d-md-block">
                        <div class="filter-sidebar bg-light p-4 rounded shadow-sm">
                            <h5 class="fw-bold mb-4 text-primary">
                                <i class="fa-solid fa-filter me-2"></i>Filters
                            </h5>
                            
                            <form method="GET" action="{{ route('shop') }}" id="filterForm">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('sort_by'))
                                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                @endif
                                
                                <!-- Category Filter -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Price Range Filter -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Price Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_price" class="form-control" 
                                                   placeholder="Min" value="{{ request('min_price') }}" 
                                                   min="0" step="0.01">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_price" class="form-control" 
                                                   placeholder="Max" value="{{ request('max_price') }}" 
                                                   min="0" step="0.01">
                                        </div>
                                    </div>
                                    <small class="text-muted">Range: ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</small>
                                </div>
                                
                                <!-- On Sale Filter -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="on_sale" 
                                               value="1" id="onSaleCheck" {{ request('on_sale') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="onSaleCheck">
                                            On Sale Only
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Filter Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-search me-2"></i>Apply Filters
                                    </button>
                                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-times me-2"></i>Clear All
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="col-md-9">
                        <div class="row">
                            @forelse($products as $product)
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
                            @empty
                            <div class="col-12 text-center py-5">
                                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No products found</h4>
                                <p class="text-muted">Try adjusting your filters</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Clear Filters</a>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Pagination -->
                        @if($products->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Offcanvas (Mobile) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold text-primary" id="filterOffcanvasLabel">
            <i class="fa-solid fa-filter me-2"></i>Filters
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('shop') }}" id="filterFormMobile">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('sort_by'))
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
            @endif
            
            <!-- Category Filter -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Price Range Filter -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Price Range</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="min_price" class="form-control" 
                               placeholder="Min" value="{{ request('min_price') }}" 
                               min="0" step="0.01">
                    </div>
                    <div class="col-6">
                        <input type="number" name="max_price" class="form-control" 
                               placeholder="Max" value="{{ request('max_price') }}" 
                               min="0" step="0.01">
                    </div>
                </div>
                <small class="text-muted">Range: ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</small>
            </div>
            
            <!-- On Sale Filter -->
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="on_sale" 
                           value="1" id="onSaleCheckMobile" {{ request('on_sale') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="onSaleCheckMobile">
                        On Sale Only
                    </label>
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search me-2"></i>Apply Filters
                </button>
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-times me-2"></i>Clear All
                </a>
            </div>
        </form>
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

        // Auto-submit sort form when selection changes
        const sortSelect = document.getElementById('sortBy');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                document.getElementById('sortForm').submit();
            });
        }
    });
</script>
@endsection