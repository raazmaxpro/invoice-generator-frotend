<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Invoice for {{ $data['client_name'] }}</h2>
    <p>Email: {{ $data['client_email'] }}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['items'] as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>₹{{ $item['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Subtotal:</strong> ₹{{ $data['subtotal'] }}</p>
    <p><strong>GST:</strong> ₹{{ $data['gst'] }}</p>
    <p><strong>Total:</strong> ₹{{ $data['total'] }}</p>
</body>
</html>
