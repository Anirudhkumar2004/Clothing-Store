<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="a1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
</head>
<body>
  <div class="nav1" id="contain">
  <?php
  include 'header.php';
  ?>
  </div>
  <div class="container" id="container">
    <div class="form-container sign-up-container">
      <form method="post" action="21.php">
        <h1>Create Account</h1>
        <div class="social-container">
          <a href="google-oauth.php" class="social"><i><img src="images/goog.png"></i></a>
        </div>
        <span>-- OR --</span>
        <br>
         <input type="text" name="name" id="name" placeholder="Enter Your Name...">
         <input type="email" name="email" id="email" placeholder="Enter Your Email...">
         <input type="password" name="password" id="password" placeholder="Enter Your Password...">
         <button>Sign Up</button>
      </form>
    </div>
    <div class="form-container sign-in-container">
      <form method="post" action="22.php">
        <h1>Sign in</h1>
        <div class="social-container">
          <a href="google-oauth.php" class="social"><img src="images/goog.png"></a>
        </div>
        <span>-- OR --</span>
        <br>
         <input type="email" name="email" id="email" placeholder="Enter Your Email..."/>
         <input type="password" name="password" id="password" placeholder="Enter Your Password..."/>
         <a href="#">Forgot your password?</a>
         <button>Log In</button>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To keep connected with us please login</p>
          <button class="ghost" id="signIn">Log In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Friend!</h1>
          <p>Enter your details and start journey with us</p>
          <button class="ghost" id="signUp">Sign Up</button>
        </div>
      </div>
    </div>
  </div>
  <br><br><br>
<script>
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});
</script>
</body>
</html>