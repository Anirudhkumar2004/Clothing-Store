<?php
session_start();
require_once("dbcontroller1.php"); // Adjust this include based on your actual file structure
$db_handle = new DBController1(); // Assuming this is your database controller class

// Database configuration
$dbHost = 'localhost'; // Update with your database host
$dbUsername = 'root'; // Update with your database username
$dbPassword = ''; // Update with your database password
$dbName = 'sociallogin'; // Update with your database name

// Connect to MySQL database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve session data for order insertion
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$address=$_POST['address'];
$city=$_POST['city'];
$state=$_POST['state'];
$zipcode=$_POST['zipcode'];
$paymentMethod = 'Cash on Delivery'; // Set payment method to COD
$total_price=$_SESSION['total_price'];
$final_price=$_SESSION['final_price'];
$subtotal = $total_price; // Assuming $total_price is calculated
$shipping = 50; // Fixed shipping cost
$tax = ($total_price * 2) / 100; // Assuming tax calculation
$finalAmount = $final_price; // Assuming $final_price is calculated

// Insert order data into `orders` table
$insertOrderQuery = "INSERT INTO orders (email, name, phone, address, city, state, zipcode, payment_method, subtotal, shipping, tax, final_amount, order_date)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmtOrder = $conn->prepare($insertOrderQuery);
$stmtOrder->bind_param("ssssssssdddd", $email, $name, $phone, $address, $city, $state, $zipcode, $paymentMethod, $subtotal, $shipping, $tax, $finalAmount);

// Execute order data insertion
if ($stmtOrder->execute()) {
    $orderID = $stmtOrder->insert_id; // Get the ID of the inserted order

    // Insert each product in `order_details` table
    foreach ($_SESSION["cart_item"] as $item) {
        $productCode = $item["code"];
        $productName = $item["name"];
        $quantity = $item["quantity"];
        $unitPrice = $item["price"];
        $totalPrice = $quantity * $unitPrice;

        $insertDetailQuery = "INSERT INTO order_details (order_id, product_code, product_name, quantity, unit_price, total_price)
                              VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDetail = $conn->prepare($insertDetailQuery);
        $stmtDetail->bind_param("isssdd", $orderID, $productCode, $productName, $quantity, $unitPrice, $totalPrice);
        $stmtDetail->execute();
        $stmtDetail->close();
    }

    // Generate random payment ID and payer ID
    $paymentID = uniqid('pay_'); // Generate a unique payment ID
    $payerID = uniqid('payer_'); // Generate a unique payer ID
    $amount = $finalAmount; // Use the final amount calculated
    $status = 'Pending'; // Set the status to Pending

    // Insert payment data into `payments` table
    $insertPaymentQuery = "INSERT INTO payments (payment_id, payer_id, amount, status, order_id)
                           VALUES (?, ?, ?, ?, ?)";
    $stmtPayment = $conn->prepare($insertPaymentQuery);
    $stmtPayment->bind_param("ssdsi", $paymentID, $payerID, $amount, $status, $orderID);
    $stmtPayment->execute();
    $stmtPayment->close();

    echo '<script type="text/javascript">
    alert("Thanks For Shopping With Us");
    window.location = "index.php";
    </script>';

} else {
    echo "Error storing order data: " . $stmtOrder->error;
}

$stmtOrder->close();
$conn->close();
?>
