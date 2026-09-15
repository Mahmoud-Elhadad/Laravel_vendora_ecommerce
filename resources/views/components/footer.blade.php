



  <!-- =========================== FOOTER =========================== -->
  <footer class="site-footer">
    <div class="container">
      <div class="row g-4 g-lg-5">
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand">
            <img src="{{ asset("ecomm") }}/images/ui/logo-white.svg" alt="Vendora">
            <p>Vendora is your one-stop shop for premium electronics, fashion, home essentials and more — curated for quality and delivered fast.</p>
            <div class="footer-social">
              <a href="https://www.facebook.com/share/19TLKA4X6F/" target="_blank"  aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="https://x.com/mahmoud_el37242" target="_blank" aria-label="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
              <a href="https://www.instagram.com/mahmoudelhadad2005?igsi=OGI3aHZlbjE2a29y" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
              <a href="https://www.linkedin.com/in/mahmoud-elhadad-05937a376?utm_source=share_via&utm_content=profile&utm_medium=member_android" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-6 col-6">
          <h6>Shop</h6>
          <ul class="footer-list">
            <li><a href="{{ route('ecomm.shop.page') }}">All products</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['sort' => 'newest']) }}">New arrivals</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['sort' => 'popular']) }}">Best sellers</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['deal' => 1]) }}">Today's deals</a></li>
          </ul>
        </div>



        <div class="col-lg-2 col-md-6 col-6">
          <h6>Account</h6>
          <ul class="footer-list">
            <li><a href="{{ route('ecomm.show.accountPage') }}">My account</a></li>
            <li><a href="{{ route('ecomm.showAndEdit.profilePage') }}">My profile</a></li>
            <li><a href="{{ route('ecomm.whilistPage') }}">Wishlist</a></li>
            <li><a href="{{ route('show.cart') }}">Cart</a></li>
            @guest('ecomm')
              <li><a href="{{ route('ecomm.show.loginPage') }}">Sign in</a></li>
              <li><a href="{{ route('ecomm.show.registrPage') }}">Register</a></li>
            @endguest
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 col-6">
          <h6>Get help</h6>
          <ul class="footer-list">
            <li><a href="{{ route('ecomm.FAQ.page') }}">FAQ</a></li>
            <li><a href="{{ route('show.message') }}">Contact us</a></li>
            <li><a href="{{ route('ecomm.privacy.page') }}">Privacy policy</a></li>
            <li><a href="{{ route('ecomm.terms.page') }}">Terms of service</a></li>
            <li><a href="{{ route('ecomm.about.page') }}">About us</a></li>
          </ul>
        </div>
      </div>

      <div class="row g-4 mt-2">
        <div class="col-lg-4 col-md-6">
          <h6>Contact us</h6>
          <ul class="footer-contact">
            <li><i class="fa-solid fa-location-dot"></i><span>Altawila , Faqous , Sharqia , Egypt</span></li>
            <li><i class="fa-solid fa-phone"></i><span>01092842953</span></li>
            <li><i class="fa-regular fa-envelope"></i><span>mahmoudelhadad314@gmail.com</span></li>
          </ul>
        </div>
       
        <div class="col-lg-4 col-md-12">
          <h6>Why shop with us</h6>
          <ul class="footer-contact">
            <li><i class="fa-solid fa-shield-halved"></i><span>Secure payments &amp; buyer protection on every order.</span></li>
            <li><i class="fa-solid fa-truck-fast"></i><span>Fast, tracked delivery with free shipping over $75.</span></li>
            <li><i class="fa-solid fa-rotate-left"></i><span>Hassle-free 30-day returns and refunds.</span></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="fb-inner">
          <p>&copy; 2026 Vendora. All rights reserved. Built as a static front-end demo.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('ecomm.privacy.page') }}">Privacy</a>
            <a href="{{ route('ecomm.terms.page') }}">Terms</a>
            <a href="{{ route('ecomm.FAQ.page') }}">FAQ</a>
            <a href="{{ route('show.message') }}">Contact</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Quick View Modal — static markup, no JS rendering. Populate from Laravel. -->
  <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title" id="quickViewModalLabel">Quick view</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body" id="quickViewBody">
          <div class="row g-0">

            <!-- Product image -->
            <div class="col-md-5">
              <img
                id="qv-img"
                src="assets/images/products/p01.svg"
                alt="Product Name"
                class="img-fluid w-100 rounded-start"
                style="object-fit:cover;min-height:300px;max-height:420px;">
            </div>

            <!-- Product info -->
            <div class="col-md-7 p-4 d-flex flex-column gap-3">

              <!-- Category -->
              <div id="qv-cat" class="text-muted small">Electronics</div>

              <!-- Name -->
              <h4 id="qv-name" class="mb-0 lh-sm">Wireless Noise-Cancelling Headphones</h4>

              <!-- Rating -->
              <div class="rating-line">
                <span class="stars" aria-label="Rated 4.7 out of 5">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star-half-stroke"></i>
                </span>
                <span class="count">4.7 (214 reviews)</span>
              </div>

              <!-- Price -->
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <span id="qv-price" class="now fs-4 fw-bold">$199.99</span>
                <span id="qv-old" class="old text-muted text-decoration-line-through">$249.99</span>
                <span id="qv-save" class="badge-v badge-sale">Save 20%</span>
              </div>

              <!-- Stock -->
              <div>
                <span class="stock-in">
                  <i class="fa-solid fa-circle-check"></i> In stock
                </span>
              </div>

              <!-- Short description -->
              <p id="qv-short" class="text-muted mb-0">
                Immersive sound with adaptive noise cancellation and 40-hour battery life.
              </p>


            </div><!-- /col info -->
          </div><!-- /row -->
        </div><!-- /modal-body -->

        @if (Auth::guard("ecomm")->check())


        <div class="modal-footer qv-footer border-0 pt-0">

          <button product_id = "{{ @$product->id }}" class="btn btn-primary-v  qv-add">
            <i class="fa-solid fa-cart-plus"></i> Add to Cart
          </button>
          <a href="pages/product-details.html" class="btn btn-outline-v qv-details">
            View full details
          </a>
        </div>
        @endif

      </div>
    </div>
  </div><!-- /#quickViewModal -->

  <button class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

  <script src="{{ asset("ecomm") }}/vendor/jquery/jquery.min.js"></script>
  <script src="{{ asset("ecomm") }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="{{ asset("ecomm") }}/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ asset("ecomm") }}/vendor/aos/aos.js"></script>
  <script src="{{ asset("ecomm") }}/js/data.js"></script>
  <script src="{{ asset("ecomm") }}/js/main.js"></script>
  <script src="{{ asset("ecomm") }}/js/custom.js"></script>
  <script src="{{ asset("ecomm") }}/js/product.js"></script>

  <script>
   $(document).on("click" , ".qv-add" , function(e){
        e.preventDefault();

        var $btn     = $(this);
        var product_id = $btn.attr("product_id");
        var _token   = "{{ csrf_token() }}";

        // Try modal qty first, then product-details page qty, fallback to 1
        var $qtyInput = $('#quickViewModal').hasClass('show')
            ? $('#quickViewModal .qv-qty')
            : $('#pd-qty-input');
        var count = parseInt($qtyInput.val() || 1, 10);

        $.ajax({
            url    : "{{ route('store.cart') }}",
            method : "post",
            data   : { _token, product_id, count },

            success: function (res) {

                $(".navbar-cart-info").load(location.href+ " .navbar-cart-info");

                if (res.status === 'success') {
                    Store.toast(res.message, 'success');

                    // Close quick view modal if open
                    var modalEl = document.getElementById('quickViewModal');
                    var inst    = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                    if (inst) inst.hide();
                } else {
                    Store.toast(res.message || 'Could not add to cart.', 'error');
                }
            },

            error: function (xhr) {
                var res = xhr.responseJSON;
                Store.toast((res && res.message) ? res.message : 'Something went wrong.', 'error');
            }
        });
    });
  </script>

  <script>
    $(document).on("click" , ".cart-remove" , function(e){
        let cart_id = $(this).attr("data-cart-id");
        let _token = "{{ csrf_token() }}";


        $.ajax({
            url : "{{ route('remove.item.cart') }}" ,
            method : "post" ,
            data : {
                cart_id , _token
            },success : (data)=>{

                $(this).closest("tr").remove();
                 $(".navbar-cart-info").load(location.href+ " .navbar-cart-info");
                 $(".all_total_cart").load(location.href+ " .all_total_cart");

            },error : function(error){
                console.log(error);

            }
        })


    })
  </script>

  {{-- Wishlist remove-confirmation modal --}}
  <div class="modal fade" id="wishlistRemoveModal" tabindex="-1" aria-labelledby="wishlistRemoveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title" id="wishlistRemoveModalLabel">
            <i class="fa-regular fa-heart text-danger me-1"></i> Remove from Wishlist
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          Are you sure you want to remove this item from your wishlist?
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-sm btn-danger" id="wishlistRemoveConfirm">Remove</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      var _wishlistToken = "{{ csrf_token() }}";
      var _wishlistUrl   = "{{ route('ecomm.store.whilist') }}";
      var $pendingBtn    = null;

      function getRemoveModal() {
        var el = document.getElementById('wishlistRemoveModal');
        return el ? (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)) : null;
      }

      /* ── Helper: sync every button for this product with the new state ── */
      function syncWishlistButtons(productId, isAdded) {
        $('.wishlist-btn[data-id="' + productId + '"]').each(function () {
          var $btn = $(this);
          $btn.toggleClass('active', isAdded)
              .find('i')
              .attr('class', (isAdded ? 'fa-solid' : 'fa-regular') + ' fa-heart');

          /* Update text label on the product-details main button */
          if ($btn.attr('id') === 'pd-wishlist') {
            $btn.contents().filter(function () {
              return this.nodeType === 3; /* text node */
            }).last().replaceWith(isAdded ? ' Remove from Wishlist' : ' Add to Wishlist');
          }
        });
      }

      /* ── Helper: reload the navbar wishlist badge without a full page refresh ── */
      function refreshNavbarWishlistCount() {
        $(".navbar-wishlist-info").load(location.href + " .navbar-wishlist-info");
      }

      /* ── Helper: fire AJAX and handle response ── */
      function doWishlistRequest($btn, productId) {
        $.ajax({
          url    : _wishlistUrl,
          method : 'post',
          data   : { product_id: productId, _token: _wishlistToken },
          success: function (res) {
            var added = res.status === 'added';
            syncWishlistButtons(productId, added);
            refreshNavbarWishlistCount();
            Store.toast(
              added ? res.name + ' added to wishlist' : res.name + ' removed from wishlist',
              added ? 'success' : 'info'
            );

            /* ── On the wishlist page: remove the card from DOM when item is removed ── */
            if (! added && $('#wishlist-grid').length) {
              var $card = $btn.closest('.product-card');
              $card.fadeOut(300, function () {
                $card.remove();

                /* Update the count badge */
                var remaining = $('#wishlist-grid .product-card').length;
                if (remaining === 0) {
                  $('#wishlist-filled').hide();
                  $('#wishlist-empty').show();
                } else {
                  var label = remaining + ' item' + (remaining === 1 ? '' : 's');
                  $('#wishlist-filled .text-muted.fw-normal').text(label);
                }
              });
            }
          },
          error: function () {
            Store.toast('Something went wrong. Please try again.', 'error');
          }
        });
      }

      /* ── Main click handler ── */
      $(document).on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        e.stopPropagation(); /* prevent bubbling to other modal triggers */
        var $btn      = $(this);
        var productId = String($btn.attr('product_id') || $btn.data('id') || '').trim();

        if (! productId) { return; } /* no product id — do nothing */

        if ($btn.hasClass('active')) {
          /* Already wishlisted → ask before removing */
          $pendingBtn = $btn;
          getRemoveModal().show();
        } else {
          /* Not wishlisted → add immediately */
          doWishlistRequest($btn, productId);
        }
      });

      /* ── Confirm removal inside modal ── */
      $(document).on('click', '#wishlistRemoveConfirm', function () {
        if (! $pendingBtn) { return; }
        var productId = $pendingBtn.attr('product_id') || $pendingBtn.data('id');
        getRemoveModal().hide();
        doWishlistRequest($pendingBtn, productId);
        $pendingBtn = null;
      });

      /* ── Reset pending button if modal is dismissed without confirming ── */
      document.getElementById('wishlistRemoveModal').addEventListener('hidden.bs.modal', function () {
        $pendingBtn = null;
      });
    }());
  </script>

  <script>
    /* ── Clear all wishlist (AJAX) ── */
    $(document).on('click', '#clearWhilistConfirm', function () {
      var _token = "{{ csrf_token() }}";
      var $btn   = $(this);

      $btn.prop('disabled', true);

      $.ajax({
        url    : "{{ route('clear.items.whilist') }}",
        method : 'post',
        data   : { _token: _token, _method: 'DELETE' },
        success: function (res) {
          /* Close the modal */
          var modalEl = document.getElementById('deleteWhilistModal');
          if (modalEl) {
            (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)).hide();
          }

          /* Clear all product cards from DOM */
          $('#wishlist-grid').empty();
          $('#wishlist-filled').hide();
          $('#wishlist-empty').removeClass('d-none').show();

          /* Refresh navbar wishlist badge */
          $(".navbar-wishlist-info").load(location.href + " .navbar-wishlist-info");

          Store.toast('Your wishlist has been cleared.', 'info');
        },
        error: function () {
          $btn.prop('disabled', false);
          Store.toast('Something went wrong. Please try again.', 'error');
        }
      });
    });
  </script>
</html>
