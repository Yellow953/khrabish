@extends('layouts.app')

@section('title', 'analytics')
@section('sub-title', 'custom report')

@section('content')
<div class="card shadow-sm p-4 mb-5">
    <h3 class="mb-4 text-primary">Report from {{ $start_date }} to {{ $end_date }}</h3>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h4>Total Sales</h4>
                <p>{{ auth()->user()->currency->symbol }}{{ number_format($total_sales, 2) }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h4>Total Profit</h4>
                <p>{{ auth()->user()->currency->symbol }}{{ number_format($total_profit, 2) }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h4>Order Count</h4>
                <p>{{ $order_count }} Orders</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h4>Top Seller</h4>
                @if($top_seller)
                <p>{{ $top_seller->name }}</p>
                <small>{{ $top_seller->total_sold }} Units Sold</small>
                @else
                <p>No sales data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm p-4 mb-5">
        <h3 class="mb-4 text-primary">Orders List</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Items</th>
                        <th>Subtotal</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Total</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ ucwords($order->cashier->name) }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td>{{ $order->currency->symbol }}{{ number_format($order->sub_total, 2) }}</td>
                        <td>{{ $order->currency->symbol }}{{ number_format($order->tax_amount, 2) }}</td>
                        <td>{{ $order->currency->symbol }}{{ number_format($order->discount_amount, 2) }}</td>
                        <td>{{ $order->currency->symbol }}{{ number_format($order->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No orders found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection