@extends('layouts.app')

@section('title', 'analytics')

@section('sub-title', 'weekly report')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Weekly Summary Cards -->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Sales</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($weekly_total_sales * $currency->rate, 2) }}
                        </div>
                        <div class="text-muted">
                            {{ $start_date }} to {{ $end_date }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Profit</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($weekly_total_profit * $currency->rate, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Order Count</h3>
                        <div class="fs-2 fw-bold">
                            {{ $weekly_order_count }} Orders
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

            <!-- Daily Sales Chart -->
            <div class="card shadow-sm p-4 mb-5">
                <h3 class="mb-4 text-primary">Daily Sales Breakdown</h3>
                <div style="height: 300px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            <!-- Weekly Orders Table -->
            <div class="card shadow-sm p-4 mb-5">
                <h3 class="mb-4 text-primary">Weekly Orders</h3>
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
                            @foreach ($weekly_orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ ucwords($order->cashier->name) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->sub_total, 2) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->tax, 2) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->discount, 2) }}</td>
                                <td>{{ $order->currency->symbol }}{{ number_format($order->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $weekly_orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dailySalesCtx = document.getElementById('dailySalesChart');

        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: @json($daily_sales_labels),
                datasets: [{
                    label: 'Daily Sales',
                    data: @json($daily_sales_data),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '{{ $currency->symbol }}' + value.toLocaleString();
                            }
                        },
                        title: {
                            display: true,
                            text: 'Sales Amount'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Day of Week'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Sales: {{ $currency->symbol }}' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection