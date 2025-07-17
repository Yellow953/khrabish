<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Currencies Report</title>
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
    <h2>Currencies Report</h2>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Rate</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($currencies as $currency)
            <tr>
                <td>{{ $currency->code }}</td>
                <td>{{ $currency->name }}</td>
                <td>{{ $currency->symbol }}</td>
                <td>{{ $currency->rate }}</td>
                <td>{{ $currency->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>