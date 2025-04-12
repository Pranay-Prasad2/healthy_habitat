<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['area_name'])) {
    $area_name = mysqli_real_escape_string($conn, $_POST['area_name']);
    if (!empty($area_name)) {
        $insert_query = "INSERT INTO area (area_name) VALUES ('$area_name')";
        mysqli_query($conn, $insert_query);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}



$area_query = "SELECT area_id,area_name FROM area ORDER BY 1";

$area_list = mysqli_query($conn, $area_query);

?>

<div class="d-flex justify-content-center align-items-center mt-3">
    <div class="w-100 card shadow p-4" style="height: 500px;">
        <h3 class="text-center">Listed Areas</h3>
        <form method="POST" class="d-flex gap-2 mb-3">
            <input type="text" name="area_name" class="form-control p-2" placeholder="Enter new area name" required>
            <button type="submit" class="btn btn-primary w-25">Add Area</button>
        </form>
        <div class="table-responsive" style="height: 100%; overflow-y: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($area_row = mysqli_fetch_assoc($area_list)) { ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($area_row['area_id']); ?></th>
                            <td><?php echo htmlspecialchars($area_row["area_name"]); ?></td>
                            <td>
                                <a href="#" class="btn btn-danger delete-btn" data-url="remove-area.php?area_id=<?php echo $area_row['area_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>