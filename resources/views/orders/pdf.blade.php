<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Orders Report</h2>

    <table>
        <thead>
            <tr>
                <th>Cashier</th>
                <th>Client</th>
                <th>Order Number</th>
                <th>Sub Total</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Products Count</th>
                <th>Note</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->cashier->name }}</td>
                <td>{{ $order->client->name }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->sub_total }}</td>
                <td>{{ $order->tax }}</td>
                <td>{{ $order->discount }}</td>
                <td>{{ $order->total }}</td>
                <td>{{ $order->products_count }}</td>
                <td>{{ $order->note }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>