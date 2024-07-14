<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .checkout-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .checkout-container h2 {
            margin-bottom: 20px;
        }
        .payment-method input {
            margin-right: 10px;
        }
        .complete-checkout-btn {
            background-color: #8e44ad;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .complete-checkout-btn:hover {
            background-color: #732d91;
        }
        .summary {
            text-align: right;
        }
        .form-check-label {
            margin-left: 10px;
        }
        .card-details input {
            margin-bottom: 10px;
        }
        .secure-save {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="checkout-container">
    <h2>Checkout</h2>
    <form>
        <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" required>
                <option value="Pakistan">Pakistan</option>
                <!-- Add other countries as needed -->
            </select>
        </div>
        <div class="form-group">
            <label>Payment method</label>
            <div class="payment-method">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="creditCard" checked>
                    <label class="form-check-label" for="creditCard">
                        Credit/Debit Card
                    </label>
                    <div class="card-details">
                        <input type="text" class="form-control" placeholder="Card number" required>
                        <input type="text" class="form-control" placeholder="MM/YY" required>
                        <input type="text" class="form-control" placeholder="CVC/CVV" required>
                        <input type="text" class="form-control" placeholder="Name on card" required>
                        <div class="secure-save">
                            <input class="form-check-input" type="checkbox" value="" id="saveCard">
                            <label class="form-check-label" for="saveCard">
                                Securely save this card for my later purchase
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal">
                    <label class="form-check-label" for="paypal">
                        PayPal
                    </label>
                </div>
            </div>
        </div>
        <div class="order-details">
            <h4>Order details</h4>
            <p>CSS, Bootstrap And JavaScript And Python Stack Course - $59.99</p>
        </div>
        <div class="summary">
            <p>Original Price: $59.99</p>
            <p>Total: $59.99</p>
        </div>
        <button type="submit" class="complete-checkout-btn">Complete Checkout</button>
        <p class="mt-3"><a href="#">30-Day Money-Back Guarantee</a></p>
    </form>
</div>
</body>
</html>
