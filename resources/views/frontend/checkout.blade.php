@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="pb-5">
    <div class="container">
        <div class="checkout-container">
            {{-- @include('app.layouts._flash') --}}

            <form class="form" action="{{ route('checkout.order') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="cart" id="cart-data" value="">
                <input type="hidden" name="shipping" id="shipping-cost" value="">

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-secondary text-center mb-4">Checkout</h2>
                    </div>
                    <!-- Left Column -->
                    <div class="col-md-7 mt-2">
                        <div class="card p-4">
                            <!-- Shipping Information -->
                            <div class="mb-4">
                                <h4 class="text-primary text-center mb-3">Shipping Address</h4>
                                <div class="mb-3">
                                    <label for="name" class="form-label text-secondary">Name *
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="John Doe"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label text-secondary">Phone *
                                    </label>
                                    <input type="tel" id="phone" name="phone" class="form-control"
                                        placeholder="+961 70 833 158" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label text-secondary">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="you@example.com">
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label text-secondary">Country *
                                    </label>
                                    <select name="country" id="country" class="form-select" required>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country }}" {{ $country=="Lebanon" ? 'selected' : '' }}>{{
                                            $country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label text-secondary">Address *
                                    </label>
                                    <input type="text" id="address" name="address" class="form-control"
                                        placeholder="123 Main St" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label text-secondary">City *
                                    </label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Beirut"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="zip" class="form-label text-secondary">Zip</label>
                                    <input type="number" min="0" step="1" id="zip" name="zip" class="form-control"
                                        placeholder="1234">
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-primary text-center mb-3">Payment Info</h4>

                                <div class="mb-3">
                                    <label for="method" class="form-label text-secondary">Payment Method</label>
                                    <select id="method" name="payment_method" class="form-select" required>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-primary text-center mb-3">Additional Info</h4>

                                <div class="mb-3">
                                    <label for="notes" class="form-label text-secondary">Notes (Optional)</label>
                                    <textarea type="text" id="notes" name="notes" class="form-control" rows="3"
                                        placeholder="Notes about your order..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-5 mt-2">
                        <div class="card p-4 border-primary">
                            <h4 class="text-primary text-center mb-4">Order Summary</h4>
                            <div class="summary-card" id="cart-items-container">
                                <!-- Cart Items will be populated here dynamically -->
                            </div>

                            <!-- Price Breakdown -->
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

                            <button type="submit" class="btn btn-cta">Complete Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shipping costs per country (per 1kg)
        const shippingCosts = {
            Qatar: 60,
            Kuwait: 60,
            Saudi: 60,
            Bahrain: 60,
            Egypt: 30,
            Jordan: 30,
            Turkey: 30,
            UAE: 50,
            Sweden: 60,
            Algeria: 70,
            London: 70,
            Libya: 45,
            France: 60,
            Germany: 70,
            Tunisia: 45,
            Iraq: 50,
            Oman: 45,
            Lebanon: 4,
            Zambia: 80,
            Gambia: 80,
            USA: 60,
            Others: 80,
        };

        const cart = document.cookie
            .split('; ')
            .find(row => row.startsWith('cart='))
            ?.split('=')[1];
        const cartData = cart ? JSON.parse(decodeURIComponent(cart)) : [];

        const cartItemsContainer = document.getElementById('cart-items-container');
        const subtotalElement = document.getElementById('subtotal-price');
        const shippingElement = document.getElementById('shipping-price');
        const totalElement = document.getElementById('total-price');
        const countrySelect = document.getElementById('country');
        const hiddenCartData = document.getElementById('cart-data');
        const hiddenShippingCost = document.getElementById('shipping-cost');

        let subtotal = 0;
        let totalQuantity = 0;

        cartData.forEach(item => {
            subtotal += item.price * item.quantity;
            totalQuantity += item.quantity;

            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item', 'd-flex', 'align-items-center', 'mb-3');

            cartItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <p class="mb-0">${item.name}</p>
                    <small>Quantity: ${item.quantity}</small>
                </div>
                <p class="ms-auto">$${(item.price * item.quantity).toFixed(2)}</p>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        const calculateShipping = () => {
            const selectedCountry = countrySelect.value;
            const countryShippingRate = shippingCosts[selectedCountry] || shippingCosts.Others;

            const weightInKg = Math.ceil(totalQuantity / 10);
            return countryShippingRate * weightInKg;
        };

        const updatePrices = () => {
            const shippingCost = calculateShipping();
            shippingElement.textContent = `$${shippingCost.toFixed(2)}`;
            totalElement.textContent = `$${(subtotal + shippingCost).toFixed(2)}`;

            hiddenCartData.value = JSON.stringify(cartData);
            hiddenShippingCost.value = shippingCost.toFixed(2);
        };

        countrySelect.addEventListener('change', updatePrices);

        subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
        updatePrices();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('country');
        const paymentMethodSelect = document.getElementById('method');
        const bankInfoSection = document.createElement('div');
        const whishInfoSection = document.createElement('div');

        bankInfoSection.innerHTML = `
            <h5>Bank_info</h5>
            <p>Bank Name: Franca Bank</p>
            <p>Account Name: MISS KHANSA FATIMA ZAHRAA</p>
            <p>Account Number: 0601USD1519220526801</p>
            <p>IBAN: LB47 0001 0601 USD1 5192 2052 6801</p>
            <p>Swift Code: FSABLBBXA</p>
        `;
        bankInfoSection.classList.add('bank_section');
        bankInfoSection.style.display = 'none';

        whishInfoSection.innerHTML = `
            <h5>Whish Transfer</h5>
            <p>Transfer To: +961 81 893 865</p>
        `;
        whishInfoSection.classList.add('whish_section');
        whishInfoSection.style.display = 'none';

        const paymentMethodContainer = paymentMethodSelect.parentNode;
        paymentMethodContainer.appendChild(bankInfoSection);
        paymentMethodContainer.appendChild(whishInfoSection);

        const updatePaymentMethods = (country) => {
            paymentMethodSelect.innerHTML = '';

            if (country === 'Lebanon') {
                const codOption = document.createElement('option');
                codOption.value = 'cash on delivery';
                codOption.textContent = 'Cash On Delivery';
                paymentMethodSelect.appendChild(codOption);

                const wireTransferOption = document.createElement('option');
                wireTransferOption.value = 'wire_transfer';
                wireTransferOption.textContent = 'Wire Transfer';
                paymentMethodSelect.appendChild(wireTransferOption);

                const whishOption = document.createElement('option');
                whishOption.value = 'whish';
                whishOption.textContent = 'Whish';
                paymentMethodSelect.appendChild(whishOption);
            } else {
                const wireTransferOption = document.createElement('option');
                wireTransferOption.value = 'wire_transfer';
                wireTransferOption.textContent = 'Wire Transfer';
                paymentMethodSelect.appendChild(wireTransferOption);

                const whishOption = document.createElement('option');
                whishOption.value = 'whish';
                whishOption.textContent = 'Whish';
                paymentMethodSelect.appendChild(whishOption);
            }
        };

        paymentMethodSelect.addEventListener('change', function () {
            const selectedMethod = paymentMethodSelect.value;

            if (selectedMethod === 'wire_transfer') {
                bankInfoSection.style.display = 'block';
                whishInfoSection.style.display = 'none';
            } else if (selectedMethod === 'whish') {
                bankInfoSection.style.display = 'none';
                whishInfoSection.style.display = 'block';
            } else {
                bankInfoSection.style.display = 'none';
                whishInfoSection.style.display = 'none';
            }
        });

        countrySelect.addEventListener('change', function () {
            const selectedCountry = countrySelect.value;
            updatePaymentMethods(selectedCountry);
        });

        updatePaymentMethods(countrySelect.value);
    });
</script>
@endsection