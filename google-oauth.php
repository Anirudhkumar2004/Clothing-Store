<?php
// Initialize the session
session_start();
require 'vendor/autoload.php';
// Database connection configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'sociallogin';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the following variables
$google_oauth_client_id = '156982889690-pq7bvvcahrqa5bi41s01b8dhla16eim3.apps.googleusercontent.com';
$google_oauth_client_secret = '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';
$google_oauth_redirect_uri = 'http://localhost/Minor/google-oauth.php';
$google_oauth_version = 'v3';
// Create the Google Client object
$client = new Google_Client();
$client->setClientId($google_oauth_client_id);
$client->setClientSecret($google_oauth_client_secret);
$client->setRedirectUri($google_oauth_redirect_uri);
$client->addScope("https://www.googleapis.com/auth/userinfo.email");
$client->addScope("https://www.googleapis.com/auth/userinfo.profile");

// If the captured code param exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Exchange the one-time authorization code for an access token
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($accessToken);
    // Make sure access token is valid
    if (isset($accessToken['access_token']) && !empty($accessToken['access_token'])) {
        // Now that we have an access token, we can fetch the user's profile data
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        // Make sure the profile data exists
        if (isset($google_account_info->email)) {
            // Store user data in the database
            $email = $conn->real_escape_string($google_account_info->email);
            $name = $conn->real_escape_string($google_account_info->name);
            $picture = $conn->real_escape_string($google_account_info->picture);
            $password = $conn->real_escape_string($google_account_info->password);

            // Check if user already exists in the database
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // User exists, update user data
                $sql = "UPDATE users SET name = '$name', picture = '$picture' WHERE email = '$email'";
                $conn->query($sql);
            } else {
                // User doesn't exist, insert new user data
                $sql = "INSERT INTO users (email, name, picture,password,type) VALUES ('$email', '$name', '$picture','$password','google')";
                $conn->query($sql);
            }

            // Authenticate the user
            session_regenerate_id();
            $_SESSION['google_loggedin'] = TRUE;
            $_SESSION['google_email'] = $email;
            $_SESSION['google_name'] = $name;
            $_SESSION['google_picture'] = $picture;
            $_SESSION['password'] = $password;
            // Redirect to profile page
            header('Location: index.php');
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
} else {
    // Redirect to Google Authentication page
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
}

// Close database connection
$conn->close();
?>

