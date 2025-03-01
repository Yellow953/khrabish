@extends('layouts.app')

@section('title', 'analytics')

@section('sub-title', 'monthly report')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Monthly Summary Cards -->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Sales</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($monthly_total_sales * $currency->rate, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Profit</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($monthly_total_profit * $currency->rate, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Order Count</h3>
                        <div class="fs-2 fw-bold">
                            {{ $monthly_order_count }} Orders
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Top Seller</h3>
                        @if ($top_seller && is_array($top_seller))
                        <div class="fs-2 fw-bold">
                            {{ $top_seller['name'] }}
                        </div>
                        <div class="text-muted">
                            {{ $currency->symbol }}{{ number_format($top_seller['total_sales'] * $currency->rate, 2) }}
                        </div>
                        @elseif ($top_seller && is_object($top_seller))
                        <div class="fs-2 fw-bold">
                            {{ $top_seller->name }}
                        </div>
                        <div class="text-muted">
                            {{ $currency->symbol }}{{ number_format($top_seller->total_sales * $currency->rate, 2) }}
                        </div>
                        @else
                        <div class="fs-2 fw-bold">N/A</div>
                        <div class="text-muted">No sales data available</div>
                        @endif

                    </div>
                </div>

            </div>

            <!-- Monthly Orders Table -->
            <div class="card shadow-sm p-4 mb-5">
                <h3 class="mb-4 text-primary">Monthly Orders</h3>
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Order No.</th>
                                <th>Date</th>
                                <th>Cashier</th>

                                <th>Sub Total</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthly_orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ ucwords($order->cashier->name) }}</td>

                                <td>{{ $order->currency->symbol }}{{ number_format($order->sub_total, 2) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->tax_amount, 2) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->discount_amount, 2) }}
                                </td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->total, 2) }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $monthly_orders->links() }}
                </div>
            </div>


        </div>
    </div>
</div>


@endsection