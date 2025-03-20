<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
</head>

<body>
    <h1>Thank you for your order, {{ $user->name }}!</h1>
    <p>Your order number is <strong>{{ $order->order_number }}</strong>.</p>
    <p>We will process your order soon. Below are your order details:</p>
    <ul>
        <li>Subtotal: ${{ number_format($order->sub_total, 2) }}</li>
        <li>Total: ${{ number_format($order->total, 2) }}</li>
        <li>Products Count: {{ $order->products_count }}</li>
    </ul>
    <p>Thank you for shopping with us!</p>
</body>

</html>