
<button type="button" class="btn btn-primary view_ms" message_id = "{{ $user_message->id }}" data-bs-toggle="modal" data-bs-target="#showMessageModal-{{ $user_message->id }}">
    <i class="fa-regular fa-eye"></i>
</button>



<div class="modal fade" id="showMessageModal-{{ $user_message->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showMessageModalLabel-{{ $user_message->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <button type="button" class="btn-close btn-close-warning" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <i class="fa-solid fa-comment-dots text-primary" style="font-size: 3rem;"></i>
        </div>



          <div class="text-center h3">
           {{ $user_message->message }}
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-2"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>
