<?php
session_start();
require_once("dbcontroller1.php");
require_once("vendor/autoload.php");
$db_handle = new DBController1();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM product1 WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RABBIT(Ecommerece)</title>
    <link rel="stylesheet" href="1.css">
    <link rel="stylesheet" href="2.css">
    <link rel="stylesheet" href="3.css">
    <link rel="stylesheet" href="4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="156982889690-lkc8rqufcmlllq4kc0k6i7pqhqqt4j0m.apps.googleusercontent.com">
</head>
</head>
<body>
    <div class="header" bgcolor="black">
    <div class="container" bgcolor="black">
        <div class="navbar" bgcolor="black">
        
<?php
//if(isset($_SESSION['google_loggedin'])==TRUE)
if(isset($_SESSION['google_name'])==TRUE)
    {
    $ax=$_SESSION['google_name'];
    echo "Welcome" . " " . "$ax";
    echo '<nav><a href="profile.php"><button1 style="--clr:#FF3131"><span>PROFILE</span><i></i></button1></a></nav>';
    }
else{
    echo "User Not Registered";
    echo '<nav><a href="account.php"><button1 style="--clr:#39FF14"><span>LOGIN</span><i></i></button1></a></nav>';
    }
    ?>
    </div>
    </div>
</div>
    <div class="header">
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
    <!--<form method="post" action="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">-->
    <div class="row">
  
        <div class="col-2">
            <h1>Give me a tittle<br>New Style</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<br>
             Dolorum deleniti error sint voluptate hic, similique, inventore est<br>
              amet dolores sequi incidunt eum doloribus ratione fugit odio vel?.</p>
              <?php
    $product_array = $db_handle->runQuery("SELECT * FROM product1 WHERE id = 1");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
            <a href="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>" class="btn">Explore Now &#10170</a>
            <?php
        }
        }?>   
        </div>
        <div class="col-2">
        <img src="images/Col1.jpeg">
        </div>

    </div>
    </div>
    </div>

<!----Featured Category---->
<div class="categories">
    <div class="small-container">
    <div class="row">
        <div class="col-3">
            <img src="images/fc1.jpg">
        </div>
        <div class="col-3">
            <img src="images/fc2.jpg">
        </div>
        <div class="col-3">
            <img src="images/fc3.jpg">
        </div>
    </div>
    </div>
</div> 

<!----Featured Products---->
<div class="products">
<div class="small-container">
    <div class="title">
    <h2>Featured Products</h2>
    </div>
<div class="row-2">
	<?php
	$product_array = $db_handle->runQuery("SELECT * FROM product1 ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="col-4">
		<div class="product-item">
			<form method="post" action="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "₹".$product_array[$key]["price"]; ?></div>
			<div class="cart-action"><!--<input type="text" class="product-quantity" name="quantity" value="1" size="2" />--><input type="submit" value="View More" class="btnAddAction" /></div>
			</div>
			</form>
        </div>
		</div>
	<?php
		}
	}
	?>
	</div>
<!--Latest Products-->
    <div class="title"><h2>Latest Products</h2></div>
    <div class="row-2">
    <?php
	$product_array = $db_handle->runQuery("SELECT * FROM product1 ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
	
		<div class="col-4">
		<div class="product-item">
			<form method="post" action="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "₹".$product_array[$key]["price"]; ?></div>
			<div class="cart-action"><!--<input type="text" class="product-quantity" name="quantity" value="1" size="2" />--><input type="submit" value="View More" class="btnAddAction" /></div>
			</div>
			</form>
			</div>
		</div>
	<?php
		}
	}
	?>
   
    </div>
</div>
</div>

<!--Offer-->
<div class="offer">
    <div class="small-container">
        <div class="row">
            <div class="col-2">
                <img src="images/y.jpg" alt="Exclisive Offer" class="offer-img">
            </div>
            <div class="col-2">
                <div class="exclusive">
                <p>Exclusively Available Offer</p>
                <h1>Shoes</h1>
                <small>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque mollitia esse saepe quas, exercitationem dicta,
                     distinctio quo sequi consectetur officiis excepturi ipsam? Veniam unde culpa voluptatum et eius. Distinctio,
                </small>
                <br>
                <?php
    $product_array = $db_handle->runQuery("SELECT * FROM product1 WHERE code = 'Shoe1'");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
                <a href="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>" class="btn">Buy Now &#10170</a>
                <?php
        }
    }
    ?>
                </div>             
            </div>
        </div>
    </div>
</div>
<!--Footer-->
<div id="ft">
<?php
include 'footer.php';
?>
</div>
<script type="text/javascript">
function searchinput() {
let filter = document.getElementById('find').value.toUpperCase();
let item = document.querySelectorAll('.product-item');
let l = document.getElementsByClassName('product-title');
for(var i = 0;i<=l.length;i++){
let a=item[i].getElementsByClassName('product-title')[0];
let value=a.innerHTML || a.innerText || a.textContent;
if(value.toUpperCase().indexOf(filter) > -1) {
item[i].style.display="";
}
else
{
item[i].style.display="none";
}
}
}
</script>
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