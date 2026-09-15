
  <!-- ============ Footer ============ -->
  <br>
  <footer class="app-footer mt-2">
    <span>&copy; 2026 NovaCart. All rights reserved.</span>
    <span class="footer-links">
      <a href="#">Documentation</a>
      <a href="#">Support</a>
      <span class="text-body-tertiary">v1.0.0</span>
    </span>
  </footer>
    </div>

  <!-- ============ Global UI: confirm dialog + toasts ============ -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-confirm">
      <div class="modal-content">
        <div class="modal-body text-center p-4">
          <span class="confirm-icon" data-confirm-icon><i class="fa-solid fa-triangle-exclamation"></i></span>
          <h5 class="modal-title mt-3 mb-1" id="confirmModalLabel">Are you sure?</h5>
          <p class="text-body-secondary small mb-4" data-confirm-text>This action cannot be undone.</p>
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-danger" data-confirm-accept>Delete</button>
            <button type="button" class="btn btn-subtle" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">You are about to sign out of the NovaCart admin console.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-subtle" data-bs-dismiss="modal">Cancel</button>
          <a href="{{ route("dash.logout") }}" class="btn btn-danger" >
            <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <button type="button" class="scroll-top" data-scroll-top aria-label="Back to top" title="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
  </button>

  <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastStack" aria-live="polite" aria-atomic="true"></div>


    <script src="{{ asset('dashboard') }}/js/jquery.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/chart.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/data.js"></script>
    <script src="{{ asset('dashboard') }}/js/custom.js"></script>
    <script src="{{ asset('dashboard') }}/js/charts.js"></script>
    <script src="{{ asset('dashboard') }}/js/dashboard.js"></script>

    <!-- Check if Bootstrap is loaded -->
    <script>
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap JS is not loaded!');
        } else {
            console.log('Bootstrap JS loaded successfully');
        }
    </script>

    <script>
        $(".view_ms").click(function(e){

            let message_id = $(this).attr("message_id");
            let _token = "{{ csrf_token() }}";

            $.ajax({
                url : "{{ route('dash.update.viewMessage') }}" ,
                method : "post" ,
                data : {
                    message_id , _token
                },success:function(data){

                    $(this).closest('tr').find("._view").html("Seen")
                    $(".unseen_ms").html(data)

                },error:function(error){
                    console.log(error);

                }
            })



        })
    </script>

  </body>
</html>

