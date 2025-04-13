<?php
session_start();
include('db.php');
include('navbar.php');

// Display success or error messages from session if set
if (isset($_SESSION['message'])) {
    echo '
    <div class="toast-container position-fixed  bottom-0 end-0 p-3">
        <div id="loginToast" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Login Status</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['message'] . '
            </div>
        </div>
    </div>';

    unset($_SESSION['message']); // Clear the message from session
}

// Fetching the 4 most liked products
$most_liked_products = "SELECT 
        p.product_id, 
        p.product_name, 
        p.product_desc, 
        p.quantity, 
        p.health_benefits, 
        p.product_price, 
        p.product_likes, 
        p.product_type, 
        b.business_name, 
        a.area_name
     FROM product p
     JOIN business b ON p.business_id = b.business_id
     JOIN area a ON b.area_id = a.area_id ORDER BY product_likes DESC LIMIT 4";
$products = mysqli_query($conn, $most_liked_products);
if (!$products) {
    echo "Error fetching products: " . mysqli_error($conn);
    exit;
}
?>

<!-- Video Section -->
<div class="mt-5" style="max-width: 1800px; margin:auto;">
<div class="d-flex justify-content-between align-items-center gap-2 w-100 px-5 flex-wrap">
    <div class="banner" style="height: 500px; width: 65%;">
        <img src="./assets/banner.png" alt="websitebanner" style="height: 100%; width: 100%; object-fit: cover; border-radius: 8px;">
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center px-4" style="width: 30%;">
        <h4 class="text-center">Healthy products/services on HealthyHabitat</h4>
        <p class="text-center">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eaque rerum asperiores, deleniti architecto aspernatur sit omnis nobis reiciendis a molestias, quo sequi natus itaque quis!
        </p>
        <a href="./product.php" class="btn btn-primary">Explore Products</a>
    </div>
</div>


    <!-- Most Liked Products Section -->
    <div class="mt-5 w-100 px-5">
        <h2 class="text-center">Our Most Liked Products</h2>
        <div class="d-grid gap-5 mt-4 w-100" style="grid-template-columns: repeat(4, 1fr);">
            <?php while ($product_row = mysqli_fetch_assoc($products)) { ?>
                <div class="card" style="width: 100%;">
                    <img class="card-img-top" src="./assets/product.jpg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product_row['product_name']; ?></h5>
                        <p class="card-text"><?php echo $product_row['product_desc']; ?></p>
                        <a href="#" class="btn btn-primary">View Product</a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="text-center mt-4">
            <a href="/healthy_habitat/product.php" class="btn btn-secondary">See All Products</a>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="about-us-section container mt-5" style="background-color: #f8f9fa; padding: 40px 0;">
        <h2 class="text-center">About Us</h2>
        <p class="text-center" style="font-size: 18px; line-height: 1.6; max-width: 800px; margin: 0 auto;">
            Welcome to our platform, where we offer a wide range of products designed to meet your needs. We are passionate about providing top-quality products at affordable prices, and our mission is to make your shopping experience as smooth and enjoyable as possible. Our team works tirelessly to ensure that we offer the best products that are loved by our customers. Thank you for being part of our journey!
        </p>
    </div>
</div>
<script>
    // JavaScript to toggle mute/unmute functionality
    function toggleMute() {
        var video = document.getElementById("coverVideo");
        var muteButton = document.getElementById("muteButton");

        if (video.muted) {
            video.muted = false;
            muteButton.innerText = "Mute";
        } else {
            video.muted = true;
            muteButton.innerText = "Unmute";
        }
    }
</script>

<?php include('footer.php'); ?>