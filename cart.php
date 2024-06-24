<?php
session_start();
require_once("dbcontroller1.php");
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
  <title>Cart-RABBIT(Ecommerece)</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="cart1.css">
</head>
<body>
<?php
include 'header.php';
?>
  <main class="main">
    <div class="cart-items">
	<?php
	if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
	?>
    <a href="cart.php?action=empty"><button class="empty-cart-btn"><span class="glyphicon glyphicon-trash"></span> Empty Cart</button></a>
	<?php	
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
	  <div class="cart-item">
        <img src="<?php echo $item["image"]; ?>" alt="Product 1">
        <div class="item-details">
          <div class="item-name"><?php echo $item["name"]; ?></div>
          <div class="item-price"><?php echo "₹ ".$item["price"]; ?></div>
          <div class="item-quantity">
            <label for="quantity1">Quantity: </label>
            <?php echo $item["quantity"]; ?>
          </div>
        </div>
        <a href="cart.php?action=remove&code=<?php echo $item["code"]; ?>"><button class="remove-btn"><span>&#128473</span></button></a>
      </div>
	  <?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
				$shipping=50;
				$tax=($total_price*2)/100;
				$final_price=$total_price+$shipping+$tax;
		}		
	?>
      <div class="cart-total">
        <h3>Total: <?php echo "₹ ".number_format($total_price, 2); ?></h3>
      </div>
    </div>

    <div class="cart-summary">
      <h3>Order Summary</h3>
      <div class="summary-details">
        <div>Subtotal: <?php echo "₹ ".number_format($total_price, 2); ?></div>
        <div>Shipping: <?php echo "₹ ".$shipping; ?></div>
        <div>Tax: <?php echo "₹ ".number_format($tax, 2); ?></div>
      </div>
      <h3>Final Amount: <?php echo "₹ ".number_format($final_price, 2); ?></h3>
      <a href="checkout.php"><button class="checkout-btn"><span class="glyphicon glyphicon-ok"></span> Checkout</button></a>
    </div>
<?php
} else {
	?>
<div class="empty-cart-message"><center><h3>Your Cart is Empty</h3><center></div>
<?php 
}
?>
  </main>

    <?php
	include 'footer.php';
	?>


</body>
</html>