<?php
session_start();
require_once("dbcontroller1.php");
$db_handle = new DBController1();

// PayPal API configuration
$paypalClientID = 'AVuVGfzI6yAL8b__dvITvDV9IKcif1FqWTU3YCZRS3k5fiXkSuWprmCLWWcZCWAOwoigy71sHPRqcT4g';
$paypalSecret = 'EKL6G8P1vX6tCaEGfKRdpIxNj9bBjBB71xqYr-nNHhn27ymUdxfKpfSAtUnpGlDzdfwMo5uRisDddSuO';
$paypalBaseURL = 'https://api.sandbox.paypal.com/';

function getAccessToken($clientID, $secret, $baseURL) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseURL . 'v1/oauth2/token');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $clientID . ":" . $secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $result = curl_exec($ch);
    $json = json_decode($result);
    curl_close($ch);
    return $json->access_token;
}

$accessToken = getAccessToken($paypalClientID, $paypalSecret, $paypalBaseURL);

// Ensure that the amount is set in the session
if (!isset($_SESSION['final_price'])) {
    die('Error: No amount set in session');
}

$amount = number_format($_SESSION['final_price'], 2, '.', ''); // Format the amount to two decimal places

$paymentData = json_encode([
    'intent' => 'sale',
    'redirect_urls' => [
        'return_url' => 'https://localhost/Minor/pay1.php',
        'cancel_url' => 'https://localhost/Minor/checkout.php'
    ],
    'payer' => ['payment_method' => 'paypal'],
    'transactions' => [
        [
            'amount' => [
                'total' => $amount,
                'currency' => 'USD'
            ],
            'description' => 'Payment description'
        ]
    ]
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paypalBaseURL . 'v1/payments/payment');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $paymentData);

$result = curl_exec($ch);
$json = json_decode($result);
curl_close($ch);

if (isset($json->links[1]->href)) {
    $approvalUrl = $json->links[1]->href;
    header("Location: " . $approvalUrl);
} else {
    echo "Error creating PayPal payment: " . json_encode($json);
}
?>

