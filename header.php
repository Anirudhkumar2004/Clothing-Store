<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="1.css">
    <link rel="stylesheet" href="2.css">
    <link rel="stylesheet" href="3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container">
<div class="navbar">
    <div class="logo">
        <img src="images/Logo.png" width="125px">
    </div>
    <div class="search-box">
    <button class="btn-search"><i class="fa fa-search"></i></button>
    <input type="text" class="input-search" id="find" placeholder="Search..." onkeyup="searchinput()">
    </div>
    <nav>
        <ul id="MenuItems"> 
            <li><a href="index.php">Home</a></li>
            <li><a href="4.php">Products</a></li>
            <li><a href="#ft">About us</a></li>
            <!--<li><a href="Contact">Contact</a></li>-->
            <li><a href="account.php">Register</a></li>
        </ul>
    </nav>
    <a href="cart.php"><img src="images/add-cart.png" width="30px" height="30px"></a>
    <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
</div>
</div>



<!--js for toggle menu-->
<script>
    var MenuItems = document.getElementById("MenuItems");
    MenuItems.style.maxHeight == "0px";
    function menutoggle(){
        if(MenuItems.style.maxHeight == "0px")
        {
            MenuItems.style.maxHeight="200px"; 
        }
        else
        {
            MenuItems.style.maxHeight="0px"; 
        }
    }
</script>
</body>
</html>