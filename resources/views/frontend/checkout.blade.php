@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="pb-5">
    <div class="container">
        <div class="checkout-container">
            @include('layouts._flash')

            <form class="form" action="{{ route('checkout.order') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="cart" id="cart-data" value="">
                <input type="hidden" name="shipping" id="shipping-cost" value="">

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-primary text-shadow text-center mb-4">Checkout</h2>
                    </div>
                    <div class="col-md-7 mt-2">
                        <div class="card p-4 lighter-secondary-bg rounded-5 border-secondary">
                            <div class="mb-4">
                                <h4 class="text-secondary text-shadow-secondary-sm text-center mb-3">Shipping Address
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-secondary">Name *
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="John Doe" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label text-secondary">Phone *
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-control"
                                                placeholder="+961 70 231 446" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label text-secondary">Country *
                                            </label>
                                            <select name="country" id="country" class="form-select" required>
                                                @foreach ($countries as $country)
                                                <option value="{{ $country }}" {{ $country=="Lebanon" ? 'selected' : ''
                                                    }}>{{
                                                    $country }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-secondary">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="you@example.com">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="city" class="form-label text-secondary">City *
                                            </label>
                                            <select name="city" id="city" class="form-select" required>
                                                @foreach ($cities as $city)
                                                <option value="{{ $city }}">{{ $city }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="zip" class="form-label text-secondary">Zip</label>
                                            <input type="number" min="0" step="1" id="zip" name="zip"
                                                class="form-control" placeholder="1234">
                                        </div>
                                    </div> --}}
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label text-secondary">Address *
                                            </label>
                                            <textarea name="address" id="address" rows="3" class="form-control"
                                                placeholder="123 Main St" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-secondary text-shadow-secondary-sm text-center mb-3">Payment Info</h4>

                                <div class="mb-3">
                                    <label for="method" class="form-label text-secondary">Payment Method</label>
                                    <select id="method" name="payment_method" class="form-select" required>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-secondary text-shadow-secondary-sm text-center mb-3">Additional Info
                                </h4>

                                <div class="mb-3">
                                    <label for="notes" class="form-label text-secondary">Notes (Optional)</label>
                                    <textarea type="text" id="notes" name="notes" class="form-control" rows="3"
                                        placeholder="Notes about your order..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mt-2">
                        <div class="card p-4 lighter-secondary-bg rounded-5 border-secondary">
                            <h4 class="text-secondary text-shadow-secondary-sm text-center mb-4">Order Summary</h4>
                            <div class="summary-card" id="cart-items-container">
                                <!-- Cart Items will be populated here dynamically -->
                            </div>

                            <div class="summary-item">
                                <span>Subtotal</span>
                                <span id="subtotal-price">$0.00</span>
                            </div>
                            <div class="summary-item">
                                <span>Shipping</span>
                                <span id="shipping-price">$10.00</span>
                            </div>
                            <div class="summary-item total-price">
                                <span>Total</span>
                                <span id="total-price">$0.00</span>
                            </div>

                            <button type="submit" class="btn btn-primary">Complete Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shippingCosts = {
            Beirut: 0,
            Others: 3
        };

        function getCart() {
            const cart = document.cookie
                .split('; ')
                .find(row => row.startsWith('cart='))
                ?.split('=')[1];
            return cart ? JSON.parse(decodeURIComponent(cart)) : [];
        }

        const cartData = getCart();
        const cartItemsContainer = document.getElementById('cart-items-container');
        const subtotalElement = document.getElementById('subtotal-price');
        const shippingElement = document.getElementById('shipping-price');
        const totalElement = document.getElementById('total-price');
        const citySelect = document.getElementById('city');
        const hiddenCartData = document.getElementById('cart-data');
        const hiddenShippingCost = document.getElementById('shipping-cost');

        let subtotal = 0;
        cartItemsContainer.innerHTML = '';

        cartData.forEach(item => {
            subtotal += item.finalPrice * item.quantity;

            let variantDetails = item.variants.map(v => `${v.value} (+$${v.price_adjustment.toFixed(2)})`).join(', ');

            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item', 'd-flex', 'align-items-center', 'mb-3');

            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <p class="mb-0">${item.name}</p>
                    <small>${variantDetails ? `Variants: ${variantDetails} <br>` : ''}
                        Quantity: ${item.quantity}
                    </small>
                </div>
                <p class="ms-auto">$${(item.finalPrice * item.quantity).toFixed(2)}</p>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        function calculateShipping() {
            if (subtotal >= 25) {
                return 0;
            }
            const selectedCity = citySelect.value;
            return selectedCity === 'Beirut' ? 0 : 3;
        }

        function updatePrices() {
            const shippingCost = calculateShipping();
            shippingElement.textContent = `$${shippingCost.toFixed(2)}`;
            totalElement.textContent = `$${(subtotal + shippingCost).toFixed(2)}`;

            hiddenCartData.value = JSON.stringify(cartData);
            hiddenShippingCost.value = shippingCost.toFixed(2);
        }

        citySelect.addEventListener('change', updatePrices);

        subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
        updatePrices();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodSelect = document.getElementById('method');
        const whishInfoSection = document.createElement('div');

        whishInfoSection.innerHTML = `
            <h5>Whish Transfer</h5>
            <p>Transfer To: <strong>+961 70 231 446</strong></p>`;
        whishInfoSection.classList.add('whish_section');
        whishInfoSection.style.display = 'none';

        const paymentMethodContainer = paymentMethodSelect.parentNode;
        paymentMethodContainer.appendChild(whishInfoSection);

        paymentMethodSelect.innerHTML = `
            <option value="cash on delivery">Cash On Delivery</option>
            <option value="whish">Whish</option>`
        ;

        paymentMethodSelect.addEventListener('change', function () {
            if (paymentMethodSelect.value === 'whish') {
                whishInfoSection.style.display = 'block';
            } else {
                whishInfoSection.style.display = 'none';
            }
        });
    });
</script>
@endsection