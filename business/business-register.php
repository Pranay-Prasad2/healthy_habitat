<?php
// session_start();
include("../db.php");
include("../navbar.php");

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

$fetch_area_query = "SELECT * FROM area";
$area_list = mysqli_query($conn, $fetch_area_query);

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_SPECIAL_CHARS);
    $userEMAIL = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
    $userpassword = filter_input(INPUT_POST, "upassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $areaId = $_POST["area_id"];

    // Check for empty fields
    if (empty($username) || empty($description) || empty($userEMAIL) || empty($userpassword) || empty($areaId)) {
        $_SESSION['message'] = 'All fields are required. Please fill in all the details.';
    } else {
        // Proceed with inserting the data if all fields are filled
        $register_user_query = "INSERT INTO business(business_name, business_description, business_email, business_password, area_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $register_user_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssi", $username, $description, $userEMAIL, $userpassword, $areaId);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = 'Business registered successfully';
                header("Location: /healthy_habitat/login.php");
            } else {
                $_SESSION['message'] = "Error: " . mysqli_stmt_error($stmt);
            }
        } else {
            $_SESSION['message'] = 'Database error, please try again later.';
        }
    }
}

?>
<div class="d-flex flex-column justify-content-center align-items-center mt-3 gap-3">
    <h1 class="h1">Register your Business</h1>
    <div class="d-flex w-100 gap-5 justify-content-center align-items-center">
        <div style="height: 600px; width:40%">
            <img src="../assets/register.jpg" alt="Register" height="100%" width="100%" style="object-fit: cover;">
        </div>
        <div class="card shadow p-4" style="width: 35%;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address*</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?php echo isset($userEMAIL) ? $userEMAIL : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Business Name*</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($username) ? $username : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="desc" class="form-label">Business description*</label>
                    <textarea name="desc" id="desc" class="form-control"><?php echo isset($description) ? $description : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="upassword" class="form-label">Password*</label>
                    <input type="password" class="form-control" name="upassword" id="upassword">
                </div>
                <div class="mb-3">
                    <label for="area" class="form-label">Select Your area*</label>
                    <select name="area_id" id="area_id" class="form-control">
                        <option value="">Select area</option>
                        <?php
                        while ($area_row = mysqli_fetch_assoc($area_list)) {
                            echo "<option value='" . $area_row['area_id'] . "'>" . $area_row['area_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary w-50 p-2" />
                </div>
            </form>
        </div>
    </div>
    <?php echo '<a href="/healthy_habitat/login.php" class="btn btn-secondary"> << Back to Login</a>'; ?>
</div>

<?php
include("../footer.php");
?>