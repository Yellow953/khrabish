<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid #ddd;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Analytics Report</h1>

    <div class="section-title">Cash Flow Data</div>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Start Cash</th>
                <th>End Cash</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cashFlowData as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>{{ number_format($data->start_cash, 2) }}</td>
                <td>{{ number_format($data->end_cash, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Top Selling Products by Revenue</div>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topProductsByRevenue as $product)
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ number_format($product['total_revenue'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Top Selling Products by Quantity</div>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topProductsByQuantity as $product)
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['total_quantity'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Average Order Value</div>
    <p>${{ number_format($averageOrderValue, 2) }}</p>

    <div class="section-title">Debt Management</div>
    <table class="table">
        <thead>
            <tr>
                <th>Creditor</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debtManagement as $debt)
            <tr>
                <td>{{ $debt['creditor'] }}</td>
                <td>{{ number_format($debt['amount'], 2) }}</td>
                <td>{{ $debt['type'] }}</td>
                <td>{{ $debt['date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Sales Over Time</div>
    <table class="table">
        <thead>
            <tr>
                <th>Period</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesOverTime as $sale)
            <tr>
                <td>{{ $sale->period }}</td>
                <td>{{ number_format($sale->total_sales, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>