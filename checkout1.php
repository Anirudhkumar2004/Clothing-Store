<?php
session_start();
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$address=$_POST['address'];
$city=$_POST['city'];
$state=$_POST['state'];
$zipcode=$_POST['zipcode'];



$_SESSION['name']="$name";
$_SESSION['email']="$email";
$_SESSION['phone']="$phone";
$_SESSION['address']="$address";
$_SESSION['city']="$city";
$_SESSION['state']="$city";
$_SESSION['zipcode']="$zipcode";

header("location: pay.php");

?>