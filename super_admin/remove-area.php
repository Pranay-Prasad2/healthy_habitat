<?php
include("../db.php");

if (isset($_GET['area_id'])) {
    $area_id = $_GET['area_id'];

    // Start the deletion process for related data
    $remove_votes = "DELETE FROM vote WHERE product_id IN (SELECT product_id FROM product WHERE business_id IN (SELECT business_id FROM business WHERE area_id = $area_id))";
    $remove_users = "DELETE FROM users WHERE area_id = $area_id";
    $remove_products = "DELETE FROM product WHERE business_id IN (SELECT business_id FROM business WHERE area_id = $area_id)";
    $remove_business = "DELETE FROM business WHERE area_id = $area_id";
    $remove_area = "DELETE FROM area WHERE area_id = $area_id";

    $result1 = mysqli_query($conn, $remove_votes);
    $result2 = mysqli_query($conn, $remove_users);
    $result3 = mysqli_query($conn, $remove_products);
    $result4 = mysqli_query($conn, $remove_business);
    $result5 = mysqli_query($conn, $remove_area);

    if ($result1 && $result2 && $result3 && $result4 && $result5) {
        $_SESSION['message'] = "Area Removed";
        header("Location: /healthy_habitat/super_admin/dashboard.php");
    } else {
        $_SESSION['message'] =  "Error:". mysqli_error($conn);
        header("Location: /healthy_habitat/super_admin/dashboard.php");
    }
}
?>
