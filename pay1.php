<?php
session_start();
require_once("dbcontroller1.php");
$db_handle = new DBController1();

// Function to get access token from PayPal
function getAccessToken($clientID, $secret, $baseURL) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseURL . 'v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $clientID . ':' . $secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $headers = [];
    $headers[] = "Accept: application/json";
    $headers[] = "Accept-Language: en_US";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null;
    }
    curl_close($ch);

    $json = json_decode($result);
    return $json->access_token;
}

// PayPal API configuration
$paypalClientID = 'AVuVGfzI6yAL8b__dvITvDV9IKcif1FqWTU3YCZRS3k5fiXkSuWprmCLWWcZCWAOwoigy71sHPRqcT4g';
$paypalSecret = 'EKL6G8P1vX6tCaEGfKRdpIxNj9bBjBB71xqYr-nNHhn27ymUdxfKpfSAtUnpGlDzdfwMo5uRisDddSuO';
$paypalBaseURL = 'https://api.sandbox.paypal.com/';

// Check if paymentID and PayerID are set in the query string
if (isset($_GET['paymentId'], $_GET['PayerID'])) {
    $paymentID = $_GET['paymentId'];
    $payerID = $_GET['PayerID'];

    // Get PayPal access token
    $accessToken = getAccessToken($paypalClientID, $paypalSecret, $paypalBaseURL);
    if ($accessToken) {
        // Execute payment via PayPal API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalBaseURL . "v1/payments/payment/$paymentID/execute");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['payer_id' => $payerID]));

        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return;
        }
        curl_close($ch);

        $json = json_decode($result);

        // Check if payment was approved
        if (isset($json->state) && $json->state == 'approved') {
            // Payment successful, now store data in database

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
          
            $name = $_SESSION['name'];
            $email = $_SESSION['email'];
            $phone = $_SESSION['phone'];
            $address = $_SESSION['address'];
            $city = $_SESSION['city'];
            $state = $_SESSION['state'];
            $zipcode = $_SESSION['zipcode'];
            $paymentMethod ="Paypal";
            $total_price = $_SESSION['total_price'];
            $final_price = $_SESSION['final_price'];
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

                // Insert payment data into `payments` table
                $insertPaymentQuery = "INSERT INTO payments (payment_id, payer_id, amount, status, order_id)
                                       VALUES (?, ?, ?, ?, ?)";
                $stmtPayment = $conn->prepare($insertPaymentQuery);
                $amount = $json->transactions[0]->amount->total; // Assuming you retrieve the amount from PayPal API response
                $status = $json->state;
                $stmtPayment->bind_param("ssdsi", $paymentID, $payerID, $amount, $status, $orderID);

                // Execute payment data insertion
                if ($stmtPayment->execute()) {
                    echo '<script type="text/javascript">
                    alert("Thanks For Shopping With Us");
                    window.location = "index.php";
                    </script>';
                    echo "Payment Successful! Data stored in database.";
                } else {
                    echo "Error storing payment data: " . $stmtPayment->error;
                }
                $stmtPayment->close();
            } else {
                echo "Error storing order data: " . $stmtOrder->error;
            }
            $stmtOrder->close();
            $conn->close();
        } else {
            echo "Payment Failed!";
        }
    } else {
        echo "Unable to get access token from PayPal.";
    }
} else {
    echo "Invalid request!";
}
?>