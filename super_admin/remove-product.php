<?php
include("../db.php");
include("../navbar.php");

$product_id = $_GET['product_id'];

$remove_votes = "DELETE FROM vote WHERE product_id IN (SELECT product_id FROM product WHERE product_id = $product_id)";
$remove_products = "DELETE FROM product WHERE product_id = $product_id";

$result1 = mysqli_query($conn, $remove_votes);
$result2 = mysqli_query($conn, $remove_products);
if ($result1 && $result2) {
    $_SESSION['message'] = "Product Removed";
    header("Location: /healthy_habitat/super_admin/dashboard.php");
} else {
    $_SESSION['message'] =  "Error:". mysqli_error($conn);
    header("Location: /healthy_habitat/super_admin/dashboard.php");
}
?>