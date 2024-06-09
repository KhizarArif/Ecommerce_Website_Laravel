<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h1,
        h2 {
            text-align: center;
        }

        h2 {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    @if ($mailData['userType'] == 'customer')
        <h1>Thank you For Your Order!!</h1>
        <h2>Your Order Id is #{{ $mailData['order']->id }}</h2>
        <h2>Your Order Details are</h2>
    @else
        <h1> You Received an Order!!</h1>
        <h2> Order Id is #{{ $mailData['order']->id }}</h2>
        <h2> Order Details are</h2>
    @endif

    <table cellpadding="3" cellspacing="3" border="0">
        <thead>
            <tr>
                <th>Product Image </th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if ($mailData['order']->orderItems)
                @foreach ($mailData['order']->orderItems as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center"> 
                                @if ($item->orderProductImages)
                                    @php
                                        $imageUrl = asset('uploads/product/small/' . $item->orderProductImages->image);
                                      
                                        // Extracting URL after #
                                        $imageUrlParts = explode('#', $imageUrl);
                                        $imageUrl = end($imageUrlParts); 
                                    @endphp
                                    <img src="{{ $imageUrl }}"
                                        alt="{{ $item->name }}" style="width: 50px; height: auto;">
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="text-left">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-left">Shipping:</th>
                <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-left">Discount:</th>
                <td>${{ number_format($mailData['order']->discount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-left">Grand Total:</th>
                <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
