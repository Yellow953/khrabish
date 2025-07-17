@extends('layouts.app')

@section('title', 'analytics')

@section('sub-title', 'daily report')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Daily Summary Cards -->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Sales</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($daily_total_sales * $currency->rate, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Total Profit</h3>
                        <div class="fs-2 fw-bold">
                            {{ $currency->symbol }}{{ number_format($daily_total_profit * $currency->rate, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-4">
                        <h3 class="mb-3 text-primary">Order Count</h3>
                        <div class="fs-2 fw-bold">
                            {{ $daily_order_count }} Orders
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

            <!-- Hourly Sales Chart -->
            <div class="card shadow-sm p-4 mb-5">
                <h3 class="mb-4 text-primary">Sales by Hour ({{ $report_date }})</h3>
                <div style="height: 300px;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>

            <!-- Daily Orders Table -->
            <div class="card shadow-sm p-4 mb-5">
                <h3 class="mb-4 text-primary">Today's Orders</h3>
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Order No.</th>
                                <th>Time</th>
                                <th>Cashier</th>
                                <th>Sub Total</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daily_orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('H:i') }}</td>
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
                    {{ $daily_orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hourlyCtx = document.getElementById('hourlyChart');

        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: @json($hourly_labels),
                datasets: [{
                    label: 'Orders per Hour',
                    data: @json($hourly_data),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hour of Day'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return 'Hour: ' + tooltipItems[0].label;
                            },
                            label: function(context) {
                                return 'Orders: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection