<?php

$fetch_products = "SELECT p.product_id,p.product_name,p.product_desc,p.quantity,p.product_price,p.product_likes,p.product_type,b.business_name
 FROM product p JOIN business b ON p.business_id = b.business_id";

$products = mysqli_query($conn, $fetch_products);

if (!$products) {
    // Handle query error
    echo "Error fetching products data: " . mysqli_error($conn);
    exit;
}
?>
    <div class="d-flex flex-column justify-content-center align-items-center mt-3">
        <div class="w-100 card shadow p-4 " style="height: 500px;">
        <h3 class="text-center">Listed products</h3>
        <!-- <?php echo '<a href="add-products.php" class="btn btn-secondary w-25" > Add product</a>'; ?> -->
        <div class="table-responsive" style="height: 100%; overflow-y: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">description</th>
                    <th scope="col">Business</th>
                    <th scope="col">Price</th>
                    <th scope="col">Votes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product_row = mysqli_fetch_assoc($products)) { ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($product_row['product_id']); ?></th>
                        <td><?php echo htmlspecialchars($product_row["product_name"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_desc"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["business_name"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_price"]); ?></td>
                        <td><?php echo htmlspecialchars($product_row["product_likes"]); ?></td>
                        <td>
                                <a href="#" class="btn btn-danger delete-btn" data-url="remove-product.php?product_id=<?php echo $product_row['product_id']; ?>">Delete</a>
                            </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>