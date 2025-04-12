<?php
include("../db.php");
include("../navbar.php");

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
    unset($_SESSION['message']);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /healthy_habitat/index.php");
}
?>

<h1 class="text-center mt-2">Super Admin</h1>
<div style="grid-template-columns: 35% 60%;" class="d-grid gap-3 m-3 p-4">
    <?php include("./area-list.php"); ?>
    <?php include("./business-list.php"); ?>
</div>
<div style="grid-template-columns: 35% 60%;" class="d-grid gap-3 m-3 p-4">
    <?php include("./user-list.php"); ?>
    <?php include("./product-list.php"); ?>
</div>

<!-- Bootstrap Modal for Confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this item? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="deleteLink" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<!-- Script to handle the modal -->
<script>
    function setDeleteLink(deleteUrl) {
        document.getElementById('deleteLink').setAttribute('href', deleteUrl);
    }
    
    // Add event listener to all delete links to open modal
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var deleteUrl = this.getAttribute('data-url'); 
            console.log(deleteUrl); // Get the delete URL from the link
            setDeleteLink(deleteUrl);  // Set the delete URL in the modal
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            myModal.show();  // Show the confirmation modal
        });
    });
</script>
