<?php
session_start();
$servername="localhost";
$username="root";
$password="";
$db="sociallogin";
$conn=mysqli_connect($servername,$username,$password,$db);

$z=$_POST['email'];
$sql="select * from users where email='".$z."' ";
$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==1)
{
die('<script type="text/javascript">
alert("Your Alredy Exist");
window.location = "account.php";
</script>');
}
else
{
$sql=$conn->prepare("insert into users (email,name,picture,password,type) values (?,?,?,?,?)");
$sql->bind_param("sssss",$b,$a,$d,$c,$e);
$a=$_POST['name'];
$b=$_POST['email'];
$c=$_POST['password'];
$d="images/profile.png";
$e="Normal";
$sql->execute();
$_SESSION['google_loggedin'] = TRUE;
$_SESSION['google_name']="$a";
$_SESSION['google_email']="$b";
echo '<script type="text/javascript">
alert("Welcome to Rabbit{Ecommerce}");
window.location = "index.php";
</script>';
}
?>
