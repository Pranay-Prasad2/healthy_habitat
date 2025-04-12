<?php
include("../db.php");
include("../navbar.php");

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    $_SESSION['message'] = "Product ID not provided.";
    header("Location: /healthy_habitat/business/dashboard.php");
    exit;
}

$delete_vote = "DELETE FROM vote WHERE product_id = $product_id";
$result1 = $conn->query($delete_vote);
$delete_query = "DELETE FROM product WHERE product_id = $product_id";
$result2 = $conn->query($delete_query);
if($result1 == true && $result2 == true){
    $_SESSION['message'] = "Product deleted successfully.";
    header("Location: /healthy_habitat/business/dashboard.php");
}
else{
    $_SESSION['message'] = "Error deleting product: " . $conn->error;
    echo "Error:". $delete_query . "<br>". $conn->error;; 
}

// Redirect back to the dashboard
header("Location: /healthy_habitat/business/dashboard.php");
exit;
?>
