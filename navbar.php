<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
$user_name = isset($_SESSION['user']) ? $_SESSION['user'] : "";
// echo $_SESSION['name']
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary px-4">
    <div class="container-fluid">
        <a class="navbar-brand" style="font-size: 25px;" href="/healthy_habitat/index.php">HealthyHabitat</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/healthy_habitat/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/healthy_habitat/product.php">Products & Services</a>
                </li>
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'business') {
                    echo '<li class="nav-item">
                    <a class="nav-link" href="/healthy_habitat/business/dashboard.php">Dashboard</a>
                    </li>';
                }
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                    echo '<li class="nav-item">
                    <a class="nav-link" href="/healthy_habitat/super_admin/dashboard.php">Dashboard</a>
                    </li>';
                }

                if (isset($_SESSION['user'])) {
                    echo '<li class="nav-item">
                    <a class="nav-link" href="/healthy_habitat/logout.php">Logout</a>
                    </li>';
                }
                else {
                    echo '<li class="nav-item">
                        <a class="nav-link" href="/healthy_habitat/login.php">Login</a>
                        </li>';
                }
                ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="" style="text-transform: capitalize;"><?php echo $user_name ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
