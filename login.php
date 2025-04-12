<?php
session_start();
include("db.php");
include("navbar.php");

if (isset($_SESSION['message'])) {
    echo '
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="messageToast" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Action Status</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['message'] . '
            </div>
        </div>
    </div>';

    // Clear the message after displaying it
    unset($_SESSION['message']);
}
// Initialize a variable for error or success message
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["login"];
    $useremail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
    $userpassword = filter_input(INPUT_POST, "upassword", FILTER_SANITIZE_SPECIAL_CHARS);

    // Construct query based on user role
    $login_query = "";
    if ($role == 'resident') {
        $login_query = "SELECT * FROM users WHERE user_email = '$useremail' AND user_password = '$userpassword'";
    } else if ($role == 'business') {
        $login_query = "SELECT * FROM business WHERE business_email = '$useremail' AND business_password = '$userpassword'";
    } else {
        $login_query = "SELECT * FROM admin WHERE admin_name = '$useremail' AND admin_password = '$userpassword'";
    }

    // Execute query and handle result
    $result = $conn->query($login_query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Successful login: set session variables and redirect
        if ($role == 'resident') {
            $_SESSION['user'] = $row['user_name'];
            $_SESSION['role'] = 'user';
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['message'] = 'Welcome, Resident! You have successfully logged in.';
            header("Location: /healthy_habitat/index.php");
        } else if ($role == 'business') {
            $_SESSION['user'] = $row['business_name'];
            $_SESSION['role'] = 'business';
            $_SESSION['b_id'] = $row['business_id'];
            $_SESSION['message'] = 'Welcome, Business! You have successfully logged in.';
            header("Location: /healthy_habitat/business/dashboard.php");
        } else {
            $_SESSION['user'] = $row['admin_name'];
            $_SESSION['role'] = 'admin';
            $_SESSION['message'] = 'Welcome, Admin! You have successfully logged in.';
            header("Location: /healthy_habitat/super_admin/dashboard.php");
        }
    } else {
        // Invalid login: display an error message
        $message = "Invalid credentials. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<div class="d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 55vh;">
    <div class="w-25 card shadow p-4">
        <h2>User Login</h2>

        <!-- Display error or success message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="login" class="form-label">Login As</label>
                <select name="login" id="login" class="form-control">
                    <option value="resident">Resident</option>
                    <option value="business">Business</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
            </div>

            <div class="mb-3">
                <label for="upassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="upassword" id="upassword" required>
            </div>

            <input type="submit" value="Login" class="btn btn-success w-100" />
        </form>
    </div>

    <div class="mt-3 w-25 d-flex justify-content-between gap-2">
        <?php echo '<a href="/healthy_habitat/user/register.php" class="btn btn-secondary w-50">Register As User</a>'; ?>
        <?php echo '<a href="/healthy_habitat/business/business-register.php" class="btn btn-primary w-50">Register As Business</a>'; ?>
    </div>
</div>


<?php include("./footer.php") ?>