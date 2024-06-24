<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM latestproduct WHERE code='" . $_GET["code"] . "'");
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
    <title>Document</title>
    <link rel="stylesheet" href="1.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php
    include 'header.php';
    ?> 

<!---Single Product Details--->

    <div class="small-container single-product">
        <div class="row">
            <div class="col-2">
                <img src="images/col1.jpeg" width="100%" id="ProductImg">
                <div class="small-img-row">
                    <div class="small-img-col">
                        <img src="images/col1.jpeg" width="100%" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="images/col2.jpeg" width="100%" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="images/col3.jpeg" width="100%" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="images/col4.jpeg" width="100%" class="small-img">
                    </div>
                </div>          
            </div>
            <div class="col-2">
                <p>Home / Sports Wear</p> 
                <h1>Sports Wear</h1>
                <h4>₹ 1299</h4>
                <select>
                    <option>Select Size</option>
                    <option>XXL</option>
                    <option>XL</option>
                    <option>L</option>
                    <option>M</option>
                    <option>S</option>
                </select>
                <input type="number" value="1">
                <a href="cart.php" class="btn">Add To Cart</a>
                <h3>Product Detail</h3>
                <br>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Dolorem corporis repellendus necessitatibus qui cumque.
                    Pariatur et obcaecati neque voluptates adipisci dolores 
                    possimus accusantium
                </p>
            </div>
        </div>
    </div>

<!--Title-->
        <div class="small-container">
            <div class="title">
                <h2>Related Product</h2>
            </div>
        </div>

<div class="small-container">
    <div class="row-2">
    <?php
	$product_array = $db_handle->runQuery("SELECT * FROM latestproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
	
		<div class="col-4">
		<div class="product-item">
			<form method="post" action="cart.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "₹".$product_array[$key]["price"]; ?></div>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
			</div>
			</form>
			</div>
		</div>
	<?php
		}
	}
	?>

    </div>

    <?php
    include 'footer.php';
    ?>  
</body>
</html>


<!-------js for product gallerty------->

<script>
    var ProductImg=document.getElementById("ProductImg");
    var SmallImg=document.getElementsByClassName("small-img")

    SmallImg[0].onclick=function()
    {
        ProductImg.src = SmallImg[0].src;
    }
    SmallImg[1].onclick=function()
    {
        ProductImg.src = SmallImg[1].src;
    }
    SmallImg[2].onclick=function()
    {
        ProductImg.src = SmallImg[2].src;
    }
    SmallImg[3].onclick=function()
    {
        ProductImg.src = SmallImg[3].src;
    }

</script>