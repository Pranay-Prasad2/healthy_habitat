<?php
include("../db.php");
include("../navbar.php");

$user_id = $_GET['user_id'];


$remove_votes = "DELETE FROM vote WHERE user_id = $user_id";
$remove_users = "DELETE FROM users WHERE user_id = $user_id";

$result1 = mysqli_query($conn, $remove_votes);
$result2 = mysqli_query($conn, $remove_users);
if($result1 && $result2){
    $_SESSION['message'] = "Resident Removed";
    header("Location: /healthy_habitat/super_admin/dashboard.php");
}
else{
    $_SESSION['message'] =  "Error:". mysqli_error($conn);
    header("Location: /healthy_habitat/super_admin/dashboard.php");
}

?>