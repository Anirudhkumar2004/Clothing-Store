<?php
session_start();
$servername="localhost";
$username="root";
$password="";
$db="sociallogin";
$conn=mysqli_connect($servername,$username,$password,$db);

$a=$_POST['email'];
$b=$_POST['password'];

$sql="select * from users where email='".$a."' and password='".$b."' ";
$sql1="select * from users where email='".$a."'";
$sql2="select name from users where email='".$a."'";
$result=mysqli_query($conn,$sql);
$result1=mysqli_query($conn,$sql1);
$result2=mysqli_query($conn,$sql2);
$num=mysqli_num_rows($result);
$num1=mysqli_num_rows($result1);
$name=mysqli_fetch_assoc($result2);
if($num1>0)
{
$ani=array_values($name);
$ani1=$ani[0];
if($num and $num1>0)
{
if(isset($_SESSION['google_name'])==TRUE){
  echo '<script type="text/javascript">
alert("You Are Already Logged In");
window.location = "index.php";
</script>';  
}else{
    $_SESSION['google_name']="$ani1";
    $_SESSION['google_email']="$a";
echo '<script type="text/javascript">
alert("Welcome to Rabbit{Ecommerce}");
window.location = "index.php";
</script>';
/*while($rows=mysqli_fetch_assoc($result))
{
echo var_dump($rows);
}*/
}}
else
{
    echo '<script type="text/javascript">
    alert("Incorrect Password");
    window.location = "account.php";
    </script>';
}
}
else
{
echo '<script type="text/javascript">
alert("You Need to Sign Up");
window.location = "account.php";
</script>';
}

?>