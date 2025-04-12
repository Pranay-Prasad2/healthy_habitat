<?php
include("../navbar.php");
include("../db.php");

$product = null;
$product_id = $_GET['product_id'] ?? $_POST['product_id'] ?? null;

if ($product_id) {
    $product_id = (int)$product_id; // Ensure it's an integer
    $query = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = "Product not found.";
    }
} else {
    $_SESSION['message'] = "Invalid Product ID.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $product_id && isset($_SESSION["b_id"])) {
    $productname     = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $productdesc     = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_SPECIAL_CHARS);
    $price           = floatval($_POST["price"]);
    $productType     = $_POST["type"];
    $quantity        = intval($_POST["quantity"]);
    $healthBenefits  = filter_input(INPUT_POST, "health_benefits", FILTER_SANITIZE_SPECIAL_CHARS);
    $business_id     = intval($_SESSION["b_id"]);

    // Validation
    if (!$productname || !$productdesc || !$price || !$productType || !$quantity || !$healthBenefits) {
        $_SESSION['message'] = "All fields are required!";
    } else {
        $update_query = "UPDATE product 
                         SET product_name = ?, product_desc = ?, product_price = ?, business_id = ?, product_type = ?, quantity = ?, health_benefits = ? 
                         WHERE product_id = ?";
        $stmt = $conn->prepare($update_query);

        if ($stmt) {
            $stmt->bind_param("ssdisisi", $productname, $productdesc, $price, $business_id, $productType, $quantity, $healthBenefits, $product_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Product updated successfully!";
                header("Location: /healthy_habitat/business/dashboard.php");
                exit();
            } else {
                $_SESSION['message'] = ($conn->errno === 1062)
                    ? "A product with this name already exists!"
                    : "Update failed: " . $stmt->error;
            }
        } else {
            $_SESSION['message'] = "Error preparing SQL statement.";
        }
    }
}
?>

<!-- Page Content -->
<div class="container my-5">
    <h2 class="text-center mb-4">Update Product</h2>

    <!-- Toast Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Status</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($product): ?>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 text-center">
                <img src="../assets/add-product.jpg" alt="Product illustration" class="img-fluid" style="max-height: 800px; min-height:400px">
            </div>

            <div class="col-md-6">
                <div class="card shadow p-4">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">

                        <div class="mb-3">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= $product['product_name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="desc">Description</label>
                            <textarea name="desc" id="desc" class="form-control" rows="3" required><?= $product['product_desc']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" value="<?= $product['product_price']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="product" <?= ($product['product_type'] === "product" ? "selected" : ""); ?>>Product</option>
                                <option value="service" <?= ($product['product_type'] === "service" ? "selected" : ""); ?>>Service</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="<?= $product['quantity']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="health_benefits">Health Benefits</label>
                            <textarea name="health_benefits" id="health_benefits" class="form-control" rows="3"><?= $product['health_benefits']; ?></textarea>
                        </div>

                        <div class="d-flex gap-4">
                            <a class="btn btn-danger w-50" href="../business//dashboard.php">Cancel</a>
                            <button type="submit" class="btn btn-primary w-50">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No product found to edit.</div>
    <?php endif; ?>
</div>

<?php include("../footer.php"); ?>
