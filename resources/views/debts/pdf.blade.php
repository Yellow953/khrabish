<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Debts Report</title>
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
    <h2>Debts Report</h2>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Supplier</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Date</th>
                <th>Note</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debts as $debt)
            <tr>
                <td>{{ $debt->type }}</td>
                <td>{{ $debt->supplier->name ?? '-' }}</td>
                <td>{{ $debt->client->name ?? '-' }}</td>
                <td>{{ $debt->amount }}</td>
                <td>{{ $debt->currency->code }}</td>
                <td>{{ $debt->date }}</td>
                <td>{{ $debt->note }}</td>
                <td>{{ $debt->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>