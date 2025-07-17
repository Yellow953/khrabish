<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Purchases Report</title>
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
    <h2>Purchases Report</h2>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Supplier</th>
                <th>Purchase Date</th>
                <th>Total</th>
                <th>Invoice Number</th>
                <th>Notes</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->number }}</td>
                <td>{{ $purchase->supplier->name }}</td>
                <td>{{ $purchase->purchase_date }}</td>
                <td>{{ $purchase->total }}</td>
                <td>{{ $purchase->invoice_number }}</td>
                <td>{{ $purchase->notes }}</td>
                <td>{{ $purchase->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>