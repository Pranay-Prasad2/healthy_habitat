<?php
include("../db.php");
include("../navbar.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $userEMAIL = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
    $userpassword = filter_input(INPUT_POST, "upassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $userage = filter_input(INPUT_POST, "uage", FILTER_SANITIZE_SPECIAL_CHARS);
    $usergender = $_POST['gender'];
    $userinterest = filter_input(INPUT_POST, "interest", FILTER_SANITIZE_SPECIAL_CHARS);
    $areaId = $_POST["area_id"];

    try {
        $register_user_querry = "INSERT INTO users(user_name,user_email,user_password,age,gender,interests,area_id) VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $register_user_querry);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssissi", $username, $userEMAIL, $userpassword, $userage, $usergender, $userinterest, $areaId);
            mysqli_stmt_execute($stmt);

            $_SESSION['message'] = 'User registered successfully';
            header("Location: /healthy_habitat/login.php");
            exit();
        } else {
            $_SESSION['message'] = 'Failed to prepare the statement.';
        }
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), "Duplicate entry")) {
            $_SESSION['message'] = "Email already registered. Please use a different email.";
        } else {
            $_SESSION['message'] = "Registration failed: " . $e->getMessage();
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>

<div class="d-flex flex-column justify-content-center align-items-center mt-3 gap-3">
    <h1>Register as Resident</h1>
    <div class="d-flex w-100 gap-5 justify-content-center align-items-center">
        <div style="height: 600px; width:40%">
            <img src="../assets/register.jpg" alt="Register" height="100%" width="100%" style="object-fit: cover;">
        </div>
    <div class="card shadow p-4 " style="width: 40%;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address*</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name*</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="name" required>
            </div>
            <div class="mb-3">
                <label for="upassword" class="form-label">Password*</label>
                <input type="password" class="form-control" name="upassword" id="upassword" required>
            </div>
            <div class="mb-3">
                <label for="uage" class="form-label">Age</label>
                <input type="number" class="form-control" name="uage" id="uage" min="1" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="area" class="form-label">Select Your area*</label>
                <select name="area_id" id="area_id" class="form-control" required>
                    <option value="">Select area</option>
                    <?php
                    while ($area_row = mysqli_fetch_assoc($area_list)) {
                        echo "<option value='" . $area_row['area_id'] . "'>" . $area_row['area_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="interest" class="form-label">Interest</label>
                <textarea name="interest" id="interest" class="form-control"></textarea>
            </div>
            <div class="d-flex justify-content-center">
                <input type="submit" class="btn btn-primary w-50 p-2" />
            </div>
        </form>
    </div>
    </div>
    <?php echo '<a href="/healthy_habitat/login.php" class="btn btn-secondary " > << Back to Login</a>'; ?>
</div>

<?php
include("../footer.php");
?>