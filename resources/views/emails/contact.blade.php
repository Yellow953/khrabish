<!DOCTYPE html>
<html>

<head>
    <title>New Contact</title>
</head>

<body>
    <h1>New Contact</h1>
    <p>Client Name: {{ $data->name }}</p>
    <p>Client Email: {{ $data->email }}</p>
    <p>Client Phone: {{ $data->phone }}</p>
    <p>Message: {{ $data->message }}</p>
</body>

</html>