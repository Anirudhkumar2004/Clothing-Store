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
<html>
<head>
    <title>All Products-RABBIT(Ecommerce)</title>
    <link rel="stylesheet" href="4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php
    include 'header.php';
    ?>
    <div class="products">
        <div class="small-container">
            <div class="row-2">
                <h2>All Products</h2>
                <select>
                    <option>Default Sorting</option>
                    <option>Sort By Price</option>
                    <option>Sort By Popularity</option>
                    <option>Sort By Rating</option>
                    <option>Sort By Sale</option>
                </select>
            </div>
            <br>
            <div class="row">
                <?php
                $product_array = $db_handle->runQuery("SELECT * FROM product1 ORDER BY id ASC");
                if (!empty($product_array)) { 
                    foreach($product_array as $key=>$value){
                ?>
                    <div class="col-4">
                        <form method="post" action="5.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                            <div class="product-item">
                                <div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>" alt="<?php echo $product_array[$key]["name"]; ?>"></div>
                                <div class="product-tile-footer">
                                    <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                                    <div class="product-price"><?php echo "₹".$product_array[$key]["price"]; ?></div>
                                    <div class="cart-action">
                                        <input type="submit" value="View More" class="btnAddAction" />
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="page-btn">
                <span>1</span>
                <span>2</span>
                <span>3</span>
                <span>&#10170</span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function searchinput() {
        let filter = document.getElementById('find').value.toUpperCase();
        let item = document.querySelectorAll('.product-item');
        let l = document.getElementsByClassName('product-title');
        for(var i = 0; i <= l.length; i++){
            let a = item[i].getElementsByClassName('product-title')[0];
            let value = a.innerHTML || a.innerText || a.textContent;
            if(value.toUpperCase().indexOf(filter) > -1) {
                item[i].style.display = "";
            } else {
                item[i].style.display = "none";
            }
        }
    }
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
