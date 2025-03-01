@extends('layouts.app')

@section('title', 'analytics')

@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Quick Stats Row -->
            <div class="row g-5 g-xl-10 mb-5">
                <div class="col-md-4">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column">
                                <div class="text-primary fw-semibold mb-2">Today's Sales</div>
                                <div class="fs-2x fw-bold">
                                    {{ $currency->symbol }}{{ number_format($todays_sales * $currency->rate, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column">
                                <div class="text-success fw-semibold mb-2">Today's Profit</div>
                                <div class="fs-2x fw-bold">
                                    {{ $currency->symbol }}{{ number_format($todays_profit * $currency->rate, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column">
                                <div class="text-info fw-semibold mb-2">Today's Orders</div>
                                <div class="fs-2x fw-bold">{{ $todays_orders_count }} Orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Detail and Report Generation -->
            <div class="row g-5 g-xl-10 mb-5">
                <!-- Today's Orders List -->
                <div class="col-md-8">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Today's Orders</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-hover">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800">
                                            <th>Order No</th>
                                            <th>Cashier</th>
                                            <th>Sub Total</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todays_orders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ ucwords($order->cashier->name) }}</td>
                                            <td>{{ $order->currency->symbol }}{{ number_format($order->sub_total, 2) }}
                                            </td>
                                            <td>{{ $order->currency->symbol }}{{ number_format($order->total, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Generation -->
                <div class="col-md-4">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Generate Reports</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-5">
                                <h4 class="fs-6 fw-semibold mb-3">Monthly Report</h4>
                                <a href="{{ route('analytics.monthly-report') }}" class="btn btn-primary w-100">Generate
                                    Monthly Report</a>
                            </div>

                            <div class="separator separator-dashed my-5"></div>

                            <div>
                                <h4 class="fs-6 fw-semibold mb-3">Custom Report</h4>
                                <form action="{{ route('analytics.custom-report') }}" method="GET">
                                    <div class="mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Generate Custom Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-5 g-xl-10 mb-5">
                <!-- Peak Hours Analysis -->
                <div class="col-md-6 mb-auto">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Peak Hours Analysis</h3>
                            <div>
                                <label for="datePicker" class="me-2">Select Date:</label>
                                <input type="date" id="datePicker" class="form-control" style="width: 200px;"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="peakHoursChart" class="w-100"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Product Performance -->
                <div class="col-md-6">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Product Performance</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-hover align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-175px">ITEM</th>
                                            <th class="text-end min-w-100px">PROFIT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list"></tbody>
                                </table>
                                <div id="pagination-controls" class="d-flex justify-content-center mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 g-xl-10 mb-5">
                <!-- Supplier and Client Debt Chart -->
                <div class="col-md-6 mb-auto">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Supplier and Client Debt</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="debtChart" class="w-100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Cash Flow Chart -->
                <div class="col-md-6 mb-auto">
                    <div class="card card-flush h-100 shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Daily Cash Flow</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="cashFlowChart" class="w-100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize data from PHP
        const products = @json($products);
        const assetBaseUrl = "{{ asset('') }}";
        const hourlyOrders = @json($hourly_orders);
        const currency = @json($currency);
        const cashFlowDates = @json($cash_flow_dates);
        const cashFlowDiff = @json($cash_flow_diff);
        const reports = @json($reports);

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            initializeProductList();
        });

        function formatCurrency(amount) {
            return currency.symbol + parseFloat(amount).toFixed(2);
        }

        function initializeProductList() {
            let currentPage = 1;
            const productsPerPage = 10;
            const productList = document.getElementById('product-list');
            const paginationControls = document.getElementById('pagination-controls');

            if (!productList || !paginationControls) return;

            function displayProducts() {
                productList.innerHTML = '';
                const startIndex = (currentPage - 1) * productsPerPage;
                const endIndex = Math.min(startIndex + productsPerPage, products.length);

                for (let i = startIndex; i < endIndex; i++) {
                    const product = products[i];
                    const productRow = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px me-3">
                                <img src="${assetBaseUrl}${product.image}" class="" alt="${product.name}" />
                            </div>
                            <div class="d-flex justify-content-start flex-column">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                    ${product.name}
                                </a>
                                <span class="text-gray-400 fw-semibold d-block fs-7">
                                    ${product.category}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="text-gray-800 fw-bold">${formatCurrency(product.profit)}</span>
                    </td>
                </tr>
            `;
                    productList.insertAdjacentHTML('beforeend', productRow);
                }
            }

            function displayPaginationControls() {
                paginationControls.innerHTML = '';
                const totalPages = Math.ceil(products.length / productsPerPage);

                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.classList.add('btn', 'btn-primary', 'mx-1');
                    if (i === currentPage) {
                        button.classList.add('active');
                    }

                    button.addEventListener('click', function() {
                        currentPage = i;
                        displayProducts();
                        displayPaginationControls();
                    });

                    paginationControls.appendChild(button);
                }
            }

            displayProducts();
            displayPaginationControls();
        }

        function initializeCharts() {
            initializePeakHoursChart();
            initializeDebtChart();
            initializeCashFlowChart();
        }

        function initializePeakHoursChart() {
            const peakHoursCtx = document.getElementById('peakHoursChart');
            const datePicker = document.getElementById('datePicker');
            if (!peakHoursCtx || !datePicker) return;

            let chart = null;

            async function fetchHourlyData(date) {
                try {
                    const response = await fetch(`/app/analytics/hourly-orders?date=${date}`);
                    const data = await response.json();
                    return data.hourly_orders;
                } catch (error) {
                    console.error('Error fetching hourly data:', error);
                    return [];
                }
            }

            async function updateChart(date) {
                const hourlyData = await fetchHourlyData(date);
                const filledHourlyData = Array.from({
                    length: 24
                }, (_, i) => {
                    const existingData = hourlyData.find(order => order.hour === i);
                    return {
                        hour: i,
                        count: existingData ? existingData.count : 0
                    };
                });

                const formatHourLabel = (hour) => {
                    const period = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour % 12 || 12;
                    return `${displayHour}${period}`;
                };

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(peakHoursCtx, {
                    type: 'bar',
                    data: {
                        labels: filledHourlyData.map(item => formatHourLabel(item.hour)),
                        datasets: [{
                            label: `Orders per Hour (${date})`,
                            data: filledHourlyData.map(item => item.count),
                            backgroundColor: 'rgba(51, 102, 204, 0.5)',
                            borderColor: 'rgba(51, 102, 204, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: `Order Distribution for ${date}`
                            },
                            tooltip: {
                                callbacks: {
                                    title: (tooltipItems) => {
                                        return `Time: ${tooltipItems[0].label}`;
                                    },
                                    label: (context) => {
                                        return `Orders: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Orders'
                                },
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hour of Day'
                                }
                            }
                        }
                    }
                });
            }

            // Initialize with current date
            updateChart(datePicker.value);

            // Add event listener for date changes
            datePicker.addEventListener('change', (e) => {
                updateChart(e.target.value);
            });
        }

        function initializeCashFlowChart() {
            const cashFlowCtx = document.getElementById('cashFlowChart');
            if (!cashFlowCtx) return;

            const dailyCashFlow = reports.map(report => ({
                date: report.date,
                netFlow: report.end_cash - report.start_cash
            }));

            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: dailyCashFlow.map(flow => flow.date),
                    datasets: [{
                        label: 'Net Cash Flow',
                        data: dailyCashFlow.map(flow => flow.netFlow),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    return `Net Flow: ${formatCurrency(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function calculateMovingAverage(data, windowSize = 3) {
            const result = [];
            for (let i = 0; i < data.length; i++) {
                let sum = 0;
                let count = 0;

                for (let j = Math.max(0, i - Math.floor(windowSize / 2)); j <= Math.min(data.length - 1, i + Math.floor(
                        windowSize / 2)); j++) {
                    sum += data[j].count;
                    count++;
                }
                result.push(sum / count);
            }
            return result;
        }

        function initializeDebtChart() {
    const debtChartCtx = document.getElementById('debtChart');
    if (!debtChartCtx) return;

    new Chart(debtChartCtx, {
        type: 'doughnut',
        data: {
            labels: ['Supplier Debt', 'Client Debt'],
            datasets: [{
                data: [
                    {{ $totalSupplierDebt * $currency->rate }},
                    {{ $totalClientDebt * $currency->rate }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.85)',
                    'rgba(75, 192, 192, 0.85)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                        },
                        generateLabels: (chart) => {
                            const datasets = chart.data.datasets;
                            const total = datasets[0].data.reduce((acc, curr) => acc + curr, 0);

                            return chart.data.labels.map((label, index) => {
                                const value = datasets[0].data[index];
                                const percentage = ((value / total) * 100).toFixed(1);
                                return {
                                    text: `${label}: ${formatCurrency(value)} (${percentage}%)`,
                                    fillStyle: datasets[0].backgroundColor[index],
                                    strokeStyle: datasets[0].borderColor[index],
                                    lineWidth: datasets[0].borderWidth,
                                    hidden: isNaN(value) || value === 0,
                                    index: index
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyColor: '#333',
                    bodyFont: {
                        size: 13
                    },
                    padding: 12,
                    borderColor: '#ddd',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: (context) => {
                            const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return `${context.label}: ${formatCurrency(context.raw)} (${percentage}%)`;
                        }
                    }
                }
            },
            layout: {
                        padding: {
                            top: 20,
                            bottom: 20,
                            left: 20,
                            right: 20
                        }
                    }
        }
    });
}
</script>
@endsection