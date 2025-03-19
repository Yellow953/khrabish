<!DOCTYPE html>
<html>

<head>
    <title>New Order Notification</title>
</head>

<body>
    <h1>New Order Received</h1>
    <p>Order Number: <strong>{{ $order->order_number }}</strong></p>
    <p>Client: {{ $order->client->name }} ({{ $order->client->email }})</p>
    <p>Total: ${{ number_format($order->total, 2) }}</p>
    <p>Products Count: {{ $order->products_count }}</p>
    <p>Notes: {{ $order->notes }}</p>
</body>

</html>