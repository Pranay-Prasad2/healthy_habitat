<?php
include("../navbar.php");
include("../db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'business') {
    header("Location: /healthy_habitat/index.php");
}
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

// Fetch products and other dashboard content
$b_id = $_SESSION["b_id"];

$fetch_products = "SELECT * FROM product p WHERE p.business_id = ?";

$stmt = mysqli_prepare($conn, $fetch_products);

if (!$stmt) {
    die("<div class='alert alert-danger text-center mt-4'>Failed to prepare product query.</div>");
}

mysqli_stmt_bind_param($stmt, "i", $b_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("<div class='alert alert-danger text-center mt-4'>Error fetching products.</div>");
}
?>
<br>

<div class="d-flex justify-content-center align-items-start mt-3" style="min-height: 60vh;">
    <div class="w-75 card shadow p-4">
        <?php echo '<a href="/healthy_habitat/crud/add-products.php" class="btn btn-secondary w-25" > Add Product / Service</a>'; ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Offering Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product_row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($product_row['product_id']); ?></th>
                        <td><?php echo htmlspecialchars($product_row["product_name"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_desc"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_type"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_price"]); ?></td>
                        <td>
                            <a class="btn btn-primary" href="/healthy_habitat/crud/update-product.php?product_id=<?php echo $product_row['product_id']; ?>"> Update</a>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-product-id="<?php echo $product_row['product_id']; ?>"> Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteProductButton" href="" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.btn-danger');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                var productId = event.target.getAttribute('data-product-id');

                if (productId) {
                    var deleteUrl = '/healthy_habitat/crud/delete-product.php?product_id=' + productId;
                    document.getElementById('deleteProductButton').setAttribute('href', deleteUrl);
                } else {
                    console.log('No product ID found.');
                }
            });
        });
    });
</script>


<?php
include("../footer.php");
?>