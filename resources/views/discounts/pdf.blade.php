<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Discounts Report</title>
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
    <h2>Discounts Report</h2>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>CODE</th>
                <th>Value</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
            <tr>
                <td>{{ $discount->type }}</td>
                <td>{{ $discount->code }}</td>
                <td>{{ $discount->value }}</td>
                <td>{{ $discount->description }}</td>
                <td>{{ $discount->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>