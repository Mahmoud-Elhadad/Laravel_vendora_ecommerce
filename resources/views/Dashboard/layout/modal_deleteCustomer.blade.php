<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCustomerModal-{{ $customer->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteCustomerModalLabel-{{ $customer->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteCustomerModalLabel-{{ $customer->id }}">
          <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Delete
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <i class="fa-solid fa-user-xmark text-danger" style="font-size: 3rem;"></i>
        </div>
        <p class="text-center mb-3">
          Are you sure you want to delete <strong>{{ $customer->first_name }} {{ $customer->last_name }}</strong>?
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
        <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fa-regular fa-trash-can me-2"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
