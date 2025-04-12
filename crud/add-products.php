<?php
include("../navbar.php");
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productname = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $productdesc = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_SPECIAL_CHARS);
    $price = $_POST["price"];
    $productType = $_POST["type"];
    $quantity = $_POST["quantity"];
    $healthBenefits = filter_input(INPUT_POST, "health_benefits", FILTER_SANITIZE_SPECIAL_CHARS);
    $b_id = $_SESSION["b_id"];

    // Check if all required fields are filled
    if (empty($productname) || empty($productdesc) || empty($price) ||  empty($productType) || empty($quantity) || empty($healthBenefits)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: /healthy_habitat/crud/add-products.php");
        exit();
    }

    $insert_query = "INSERT INTO product (product_name, product_desc, product_price, business_id, product_type, quantity, health_benefits) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdisis", $productname, $productdesc, $price, $b_id, $productType, $quantity, $healthBenefits);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Product added successfully!";
            header("Location: /healthy_habitat/business/dashboard.php");
            exit();
        } else {
            // Check for duplicate product name error
            if (mysqli_errno($conn) == 1062) { // Error code for duplicate entry
                $_SESSION['message'] = "Error: A product with this name already exists!";
            } else {
                $_SESSION['message'] = "Error: " . mysqli_stmt_error($stmt);
            }
            header("Location: /healthy_habitat/crud/add-products.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Error preparing the SQL statement.";
        header("Location: /healthy_habitat/crud/add-products.php");
        exit();
    }
}
?>

<!-- Page Content -->
<div class="container my-5">
    <h2 class="text-center mb-4">Add New Product</h2>

    <!-- Toast Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="messageToast" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Action Status</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']);
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center align-items-center">
        <!-- Illustration -->
        <div class="col-md-6 text-center">
            <img src="../assets/add-product.jpg" 
                 alt="Add product illustration" class="img-fluid" style="max-height: 800px; min-height:400px">
        </div>

        <!-- Form -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="desc" class="form-label">Product Description</label>
                        <textarea name="desc" id="desc" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Product Price</label>
                        <input type="number" class="form-control" id="price" name="price" required step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Product Type</label>
                        <select name="type" id="type" class="form-control" required onchange="toggleQuantityField()">
                            <option value="">Select Type</option>
                            <option value="product">Product</option>
                            <option value="service">Service</option>
                        </select>
                    </div>

                    <div class="mb-3" id="quantity-field">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label for="health_benefits" class="form-label">Health Benefits</label>
                        <textarea name="health_benefits" id="health_benefits" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Hidden field for business_id -->
                    <input type="hidden" name="business_id" value="<?php echo $_SESSION['b_id']; ?>">

                    <button type="submit" class="btn btn-primary w-100">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Function to toggle the quantity field visibility based on product type
function toggleQuantityField() {
    var productType = document.getElementById("type").value;
    var quantityField = document.getElementById("quantity-field");

    // If 'service' is selected, hide the quantity field and set default value to 1
    if (productType === "service") {
        quantityField.style.display = "none";
        document.getElementById("quantity").value = 1; // Set default value
    } else {
        quantityField.style.display = "block";
    }
}

// Call the function on page load to set the initial state
document.addEventListener("DOMContentLoaded", function() {
    toggleQuantityField(); // Initialize based on selected type on page load
});
</script>

<?php
include("../footer.php");
?>