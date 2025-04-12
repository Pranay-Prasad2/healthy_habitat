<?php

$user_query = "SELECT r.user_id,r.user_name,r.user_email,a.area_name FROM users r JOIN area a ON r.area_id = a.area_id";

$user_list = mysqli_query($conn,$user_query);
if (!$user_list) {
    // Handle query error
    echo "Error fetching user data: " . mysqli_error($conn);
    exit;
}
?>

<div class="d-flex justify-content-center align-items-center mt-3">
    <div class="w-100 card shadow p-4" style="height: 500px;">
    <h3 class="text-center">Residents</h3>
        <!-- <?php echo '<a href="add-products.php" class="btn btn-secondary w-25" > Add product</a>'; ?> -->
        <div class="table-responsive" style="height: 100%; overflow-y: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Resident area</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user_row = mysqli_fetch_assoc($user_list)) { ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($user_row['user_id']); ?></th>
                        <td><?php echo htmlspecialchars($user_row["user_name"]); ?></td>
                        <td><?php echo htmlspecialchars($user_row["user_email"]); ?></td>
                        <td><?php echo htmlspecialchars($user_row["area_name"]); ?></td>
                        <td>
                                <a href="#" class="btn btn-danger delete-btn" data-url="remove-resident.php?user_id=<?php echo $user_row['user_id']; ?>">Delete</a>
                            </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
