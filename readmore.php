<?php
include("db.php");
include("navbar.php");
$product_id = $_GET['product_id'];

$all_products = "SELECT 
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
     JOIN area a ON b.area_id = a.area_id WHERE p.product_id = $product_id";
$products = mysqli_query($conn, $all_products);
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Product Information</h2>
    <?php
    if ($row = mysqli_fetch_assoc($products)) {
    ?>
        <div class="card shadow-lg p-4 mb-5 bg-body rounded">
            <a href="/healthy_habitat/product.php" class="btn btn-secondary w-25 mb-4">Back to all products</a>
            <div class="row">
                <!-- Product Image and Title -->
                <div class="col-md-4">
                    <img src="./assets/product.jpg" alt="Product Image" class="img-fluid rounded shadow-sm mb-3">
                </div>
                <div class="col-md-8">
                    <h3 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <h5 class="text-muted"><?php echo htmlspecialchars($row['business_name']); ?> - <?php echo htmlspecialchars($row['area_name']); ?></h5>
                    <p><strong>Type: </strong><?php echo htmlspecialchars($row['product_type']); ?></p>
                    <p><strong>Price: </strong>$<?php echo number_format($row['product_price'], 2); ?></p>
                    <p><strong>Quantity Available: </strong><?php echo htmlspecialchars($row['quantity']); ?></p>
                    <p><strong>Likes: </strong><?php echo htmlspecialchars($row['product_likes']); ?></p>
                </div>
            </div>
            
            <div class="row mt-4">
                <!-- Product Description -->
                <div class="col-md-12">
                    <h5 class="mb-3">Description</h5>
                    <p><?php echo nl2br(htmlspecialchars($row['product_desc'])); ?></p>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Product Health Benefits -->
                <div class="col-md-12">
                    <h5 class="mb-3">Health Benefits</h5>
                    <ul>
                        <?php 
                            // Assuming health benefits are stored as a comma-separated list in the database
                            $health_benefits = explode(',', $row['health_benefits']);
                            foreach ($health_benefits as $benefit) {
                                echo "<li>" . htmlspecialchars(trim($benefit)) . "</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<div class="alert alert-warning text-center">No product found for this ID.</div>';
    }
    ?>
</div>

<?php
    include("./footer.php")
?>