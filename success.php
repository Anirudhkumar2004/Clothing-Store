<?php
session_start();
            $address = $_SESSION['address'];
            $city = $_SESSION['city'];
            $state = $_SESSION['state'];
            $zipcode = $_SESSION['zipcode'];
            $final_price = $_SESSION['final_price'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Succes-RABBIT(Ecommerece)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #ff523b;
            font-size: 36px;
        }
        .icon {
            font-size: 48px;
            color: #ff523b;
            margin-bottom: 10px;
        }
        .message {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-summary {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff523b;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .action-button:hover {
            background-color: #e03e25;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dddddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🛒</div>
            <h1>Order Confirmed</h1>
        </div>
        <div class="message">
            <p>Your order (#12345) has been successfully placed.</p>
        </div>
        <div class="order-summary">
            <h2>Order Summary</h2>
            <p><strong>Items:</strong> Product A, Product B, Product C</p>
            <p><strong>Total Amount:</strong><?php echo "₹"."$final_price"; ?></p>
            <p><strong>Delivery Address:</strong><?php echo "$address"; ?></p>
        </div>
        <div class="action">
            <a href="#" class="action-button">Continue Shopping</a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Your Company Name. All rights reserved.</p>
    </div>
</body>
</html>
