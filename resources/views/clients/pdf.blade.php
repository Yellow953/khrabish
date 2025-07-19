<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Clients Report</title>
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
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Clients Report</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Address</th>
                <th>Note</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->status }}</td>
                <td>{{ $client->country }}</td>
                <td>{{ $client->state }}</td>
                <td>{{ $client->city }}</td>
                <td>{{ $client->address }}</td>
                <td>{{ $client->note }}</td>
                <td>{{ $client->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>