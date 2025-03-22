@extends('layouts.app')

@section('title', 'clients')

@section('sub-title', 'history')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Client Information Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Client Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-user text-white" style="font-size:40px"></i>
                            </div>
                        </div>
                        <div class="contact-info">
                            <h6 class="fw-bold mb-1">{{ $client->name }}</h6>
                            <p class="text-muted mb-1 small">
                                <i class="fas fa-envelope me-1"></i> {{ $client->email ?? 'N/A' }}
                            </p>
                            <p class="text-muted mb-0 small">
                                <i class="fas fa-phone me-1"></i> {{ $client->phone }}
                            </p>
                        </div>
                    </div>
                    <div class="additional-info">
                        <p class="mb-2"><strong>Address:</strong><br>{{ $client->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <img src="{{ asset('assets/images/history.png') }}" class="img-fluid" alt="Description of image"
                    style="border-radius: 10px">
            </div>
        </div>

        <!-- Order History Card -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="col-3">Order Number</th>
                                    <th class="col-3">Items</th>
                                    <th class="col-3">Total</th>
                                    <th class="col-3">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td>ORDER #{{ $order->order_number }}</td>
                                    <td>
                                        @foreach ($order->items as $item)
                                        {{ $item->product->name }}: {{ $item->quantity }} pc(s) -> {{ $item->total }}
                                        <br>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection