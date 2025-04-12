<?php
include("../db.php");
include("../navbar.php");

$business_id = $_GET['business_id'];

$remove_votes = "DELETE FROM vote WHERE product_id IN (SELECT product_id FROM product WHERE business_id = $business_id)";
$remove_products = "DELETE FROM product WHERE business_id = $business_id";
$remove_business = "DELETE FROM business WHERE business_id = $business_id";

$result1 = mysqli_query($conn, $remove_votes);
$result2 = mysqli_query($conn, $remove_products);
$result3 = mysqli_query($conn, $remove_business);
if ($result1 && $result2 && $result3 ) {
    $_SESSION['message'] = "Business Removed ";
    header("Location: /healthy_habitat/super_admin/dashboard.php");
} else {
    $_SESSION['message'] =  "Error: " . mysqli_error($conn);
    header("Location: /healthy_habitat/super_admin/dashboard.php");
}

?>


