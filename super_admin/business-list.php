<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../db.php");

$business_query = "SELECT b.business_id,b.business_name,b.business_description,b.business_email,a.area_name FROM business b JOIN area a ON b.area_id = a.area_id";

$business_list = mysqli_query($conn,$business_query);
if (!$business_list) {
    // Handle query error
    echo "Error fetching business data: " . mysqli_error($conn);
    exit;
}
?>



<div class="d-flex justify-content-center align-items-center mt-3">
<div class="w-100 card shadow p-4" style="height: 500px;">
<h3 class="text-center">Business</h3>
        <!-- <?php echo '<a href="add-products.php" class="btn btn-secondary w-25" > Add product</a>'; ?> -->
        <div class="table-responsive" style="height: 100%; overflow-y: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">description</th>
                    <th scope="col">Email</th>
                    <th scope="col">Area</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($business_row = mysqli_fetch_assoc($business_list)) { ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($business_row['business_id']); ?></th>
                        <td><?php echo htmlspecialchars($business_row["business_name"]); ?></td>
                        <td><?php echo htmlspecialchars($business_row["business_description"]); ?></td>
                        <td><?php echo htmlspecialchars($business_row["business_email"]); ?></td>
                        <td><?php echo htmlspecialchars($business_row["area_name"]); ?></td>
                        <td>
                                <a href="#" class="btn btn-danger delete-btn" data-url="remove-business.php?business_id=<?php echo $business_row['business_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>