<?php
session_start(); // Start session to access session variables

// Set logout message
$_SESSION['message'] = "You have logged out successfully.";

// Destroy all session variables
session_unset();
session_destroy();

// Redirect to the home page (or another page after logout)
header("Location: /healthy_habitat/index.php");
exit;
?>