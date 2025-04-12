<?php
include('db.php');
include('navbar.php');

$product_id = $_GET['product_id'];
$user_id = $_SESSION['user_id'];

$addcount_query = "DELETE FROM vote WHERE user_id = $user_id AND product_id = $product_id";
$result1 = mysqli_query($conn,$addcount_query);

$update_query = "UPDATE product SET product_likes = product_likes - 1 WHERE product_id = $product_id";
$result2 = mysqli_query($conn,$update_query);

if($result1 == true && $result2 == true){
    header("Location: /healthy_habitat/product.php");
}
else{
    echo "Some error occoured";
}
?>