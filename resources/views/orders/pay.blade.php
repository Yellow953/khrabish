@extends('layouts.app')

@section('title', 'orders')

@section('sub-title', 'pay')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card border-custom">
        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf

            <div class="card-header bg-primary text-white pt-5">
                <h1 class="text-center">Pay Order #{{ $order->order_number }}</h1>
                <p class="text-center">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $order->status === 'unpaid' ? 'warning' : 'success' }} text-dark">
                        {{ strtoupper($order->status) }}
                    </span>
                </p>
            </div>
            <div class="card-body">
                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Order Details</h5>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}
                                </p>
                                <p class="mb-1"><strong>Cashier:</strong> {{ $order->cashier->name }}</p>
                                @if($order->client)
                                <p class="mb-1"><strong>Client:</strong> {{ $order->client->name }}</p>
                                @endif
                                <p class="mb-1"><strong>Note:</strong> {{ $order->note ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Amount Due</h5>
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>{{ number_format($order->sub_total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Tax:</span>
                                    <span>{{ number_format($order->tax, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Discount:</span>
                                    <span>-{{ number_format($order->discount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Delivery:</span>
                                    <span>{{ number_format($order->delivery, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>{{ number_format($order->total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Amount Paid:</span>
                                    <span>{{ number_format($order->amount_paid, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold text-primary">
                                    <span>Amount Due:</span>
                                    <span>{{ number_format($order->change_due, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}"
                                            class="img-thumbnail me-2" width="50">
                                        @endif
                                        <div>
                                            {{ $item->product->name }}
                                            @if($item->variant_details)
                                            <div class="text-muted small">
                                                @foreach(json_decode($item->variant_details, true) as $variant)
                                                {{ $variant['value'] }}@if(!$loop->last), @endif
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Payment Section -->
                <div class="border-top pt-4">
                    <h4 class="mb-4">Payment Details</h4>

                    <!-- Payment Method -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="">Select method</option>
                                @foreach (Helper::get_payment_methods() as $pm)
                                <option value="{{ $pm }}" {{ $pm==old('payment_method' ? 'selected' : '' ) }}>{{ $pm }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Currency</label>
                            <select class="form-select" name="payment_currency" required>
                                <option value="">Select Payment</option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ $currency->code == old('payment_currency') ?
                                    'selected' : '' }}>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Amount Paid -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Amount Paid</label>
                            <input type="number" class="form-control" name="amount_paid"
                                value="{{ old('amount_paid', $order->change_due) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Exchange Rate (LBP per USD)</label>
                            <input type="number" class="form-control" name="exchange_rate"
                                value="{{ old('exchange_rate', $rate) }}" min="1" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer pt-0">
                <div class="d-flex align-items-center justify-content-around">
                    <button type="reset" class="btn btn-danger clear-btn py-2 px-4">Clear</button>
                    <button type="submit" class="btn btn-primary">Complete Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection