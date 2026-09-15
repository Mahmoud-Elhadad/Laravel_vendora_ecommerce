<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteWhilistModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteWhilistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteWhilistModalLabel">
          <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Delete All Wishlist Products
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
         <span class="fa-stack" style="font-size: 1.5rem;">
            <i class="fa-solid fa-box fa-stack-2x text-danger"></i>
            <i class="fa-solid fa-xmark fa-stack-1x" style="color: white; font-size: 0.9rem; margin-top: 0.4rem;"></i>
        </span>
        </div>
        <p class="text-center mb-3">
          Are you sure you want to delete <strong>All Wishlist Products</strong>?
        </p>
        <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
          <i class="fa-solid fa-circle-exclamation me-2"></i>
          <div>
            <strong>Warning:</strong> This action cannot be undone. All data associated with this staff member will be permanently deleted.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-2"></i>Cancel
        </button>
        <button type="button" id="clearWhilistConfirm" class="btn btn-danger">
          <i class="fa-regular fa-trash-can me-2"></i>Delete
        </button>
      </div>
    </div>
  </div>
</div>
