<?php
session_start();
require_once("dbcontroller1.php");
$db_handle = new DBController1();

$total_quantity = 0;
$total_price = 0;
$shipping = 50;
$tax = 0;

if (isset($_SESSION["cart_item"])) {
    foreach ($_SESSION["cart_item"] as $item) {
        $total_quantity += $item["quantity"];
        $total_price += ($item["price"] * $item["quantity"]);
    }
    $tax = ($total_price * 2) / 100;
    $final_price = $total_price + $shipping + $tax;
    $_SESSION["total_price"] = $total_price;
    $_SESSION["final_price"] = $final_price;
}

// Initialize session variables if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['name'] = isset($_POST['name']) ? $_POST['name'] : '';
    $_SESSION['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    $_SESSION['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
    $_SESSION['address'] = isset($_POST['address']) ? $_POST['address'] : '';
    $_SESSION['city'] = isset($_POST['city']) ? $_POST['city'] : '';
    $_SESSION['state'] = isset($_POST['state']) ? $_POST['state'] : '';
    $_SESSION['zipcode'] = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
    $_SESSION['payment_method'] = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout-RABBIT(Ecommerece)</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="checkout.css">
    <script>
        function handlePaymentMethod() {
            var form = document.getElementById("checkoutForm");
            var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            if (paymentMethod === "COD") {
                form.action = "cod.php";  // Replace with the actual URL for COD
            } else {
                form.action = "checkout1.php";
            }
            form.submit();
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="checkout">
                    <h1>Shipping Information</h1>
                    <form id="checkoutForm" method="POST" onsubmit="handlePaymentMethod();">
                        <div class="shipping-address">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="form-group">
                                <label for="zipcode">Zip Code</label>
                                <input type="text" class="form-control" id="zipcode" name="zipcode">
                            </div>
                        </div>
                        <div class="payment-method">
                            <h2>Payment Method</h2>
                            <div class="form-group">
                                <div class="radio">
                                    <input type="radio" id="cod" name="payment_method" value="COD" required>
                                    <label for="cod">Cash on Delivery</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="online" name="payment_method" value="Online" required>
                                    <label for="online">Online Payment</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <p>Subtotal: <?php echo "₹ " . number_format($total_price, 2); ?></p>
                    <p>Shipping: <?php echo "₹ " . $shipping; ?></p>
                    <p>Tax: <?php echo "₹ " . number_format($tax, 2); ?></p>
                    <p class="final-amount">Final Amount: <?php echo "₹ " . number_format($final_price, 2); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
