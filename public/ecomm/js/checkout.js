/* ==========================================================================
   Vendora E-commerce — Checkout & Orders (checkout.js)
   Handles: checkout summary + validation + place order, order success,
   orders list, and order details (demo data + locally placed orders).
   Depends on: jQuery, Bootstrap, main.js (Store), data.js (VendoraDB)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store, DB = window.VendoraDB;
  var PLACED_KEY = 'vendora_orders';

  function placedOrders() { return Store.read(PLACED_KEY, []); }
  function allOrders() { return placedOrders().concat(DB.orders); }
  function findOrder(id) {
    var list = allOrders();
    for (var i = 0; i < list.length; i++) if (list[i].id === id) return list[i];
    return null;
  }
  function genOrderId() {
    return 'VDR-2026-' + String(Math.floor(Math.random() * 90000) + 10000);
  }

  /* ==================================================================
     CHECKOUT PAGE
  ================================================================== */
  var selectedShipping = 'standard';
  var selectedPayment = 'card';

  function renderCheckoutSummary() {
    var $sum = $('#checkout-summary');
    if (!$sum.length) return;
    var items = Store.cartDetailed();
    if (!items.length) {
      $sum.html('<div class="empty-state"><img src="' + DB.IMG + 'ui/empty-cart.svg" alt="Empty" style="max-width:220px">' +
        '<h5>Your cart is empty</h5><p>Add some products before checking out.</p>' +
        '<a href="shop.html" class="btn btn-primary-v">Shop now</a></div>');
      $('#place-order').prop('disabled', true);
      return;
    }
    var t = Store.calcTotals(selectedShipping);
    var itemsHtml = items.map(function (i) {
      var p = i.product;
      var meta = [i.color ? 'Color: ' + Store.esc(i.color) : '', i.size ? 'Size: ' + Store.esc(i.size) : ''].filter(Boolean).join(' · ');
      return '<div class="os-item"><div class="position-relative"><img src="' + p.image + '" alt="' + Store.esc(p.name) + '">' +
        '<span class="badge bg-primary-v rounded-pill position-absolute top-0 start-100 translate-middle">' + i.qty + '</span></div>' +
        '<div><div class="osi-name">' + Store.esc(p.name) + '</div><div class="osi-meta">' + Store.esc(p.brand) + (meta ? ' · ' + meta : '') + '</div></div>' +
        '<div class="osi-price">' + Store.money(i.lineTotal) + '</div></div>';
    }).join('');

    $sum.html(itemsHtml +
      '<div class="coupon-row mt-3">' +
        '<input type="text" class="form-control" id="co-coupon" placeholder="Coupon code" value="' + (t.coupon ? Store.esc(t.coupon) : '') + '">' +
        (t.coupon ? '<button class="btn btn-outline-v" id="co-remove-coupon">Remove</button>' : '<button class="btn btn-dark-v" id="co-apply-coupon">Apply</button>') +
      '</div>' +
      '<div class="mt-3">' +
        '<div class="summary-line"><span class="lbl">Subtotal</span><span class="val">' + Store.money(t.subtotal) + '</span></div>' +
        (t.discount > 0 ? '<div class="summary-line"><span class="lbl">Discount (' + Store.esc(t.coupon) + ')</span><span class="val text-success">− ' + Store.money(t.discount) + '</span></div>' : '') +
        '<div class="summary-line"><span class="lbl">Shipping</span><span class="val ' + (t.shipping === 0 ? 'free' : '') + '">' + (t.shipping === 0 ? 'FREE' : Store.money(t.shipping)) + '</span></div>' +
        '<div class="summary-line"><span class="lbl">Tax (7%)</span><span class="val">' + Store.money(t.tax) + '</span></div>' +
        '<div class="summary-line total"><span class="lbl">Total</span><span class="val">' + Store.money(t.total) + '</span></div>' +
      '</div>');
  }

  function initCheckout() {
    if (!$('#checkout-page').length) return;

    // Redirect if cart empty (but still render friendly empty state)
    renderCheckoutSummary();
    $(window).on('cart:changed', renderCheckoutSummary);

    // Coupon
    $('#checkout-summary').on('click', '#co-apply-coupon', function () {
      var code = $('#co-coupon').val();
      if (Store.setCoupon(code)) { Store.toast('Coupon applied!', 'success'); renderCheckoutSummary(); }
      else Store.toast('Invalid coupon code.', 'error');
    }).on('click', '#co-remove-coupon', function () { Store.clearCoupon(); renderCheckoutSummary(); });

    // Shipping method
    $(document).on('change', 'input[name="shipping-method"]', function () {
      selectedShipping = $(this).val();
      $('.method-option').removeClass('selected');
      $(this).closest('.method-option').addClass('selected');
      renderCheckoutSummary();
    });
    // Payment method
    $(document).on('change', 'input[name="payment-method"]', function () {
      selectedPayment = $(this).val();
      $('.payment-option').removeClass('selected');
      $(this).closest('.payment-option').addClass('selected');
      $('#card-fields').toggleClass('d-none', selectedPayment !== 'card');
      $('#cod-note').toggleClass('d-none', selectedPayment !== 'cod');
    });
    // default selection states
    $('input[name="shipping-method"]:checked').closest('.method-option').addClass('selected');
    $('input[name="payment-method"]:checked').closest('.payment-option').addClass('selected');
    $('#card-fields').toggleClass('d-none', selectedPayment !== 'card');

    // Card number formatting (demo)
    $(document).on('input', '#card-number', function () {
      var v = $(this).val().replace(/\D/g, '').slice(0, 16);
      $(this).val(v.replace(/(.{4})/g, '$1 ').trim());
      $('#card-preview-num').text(($(this).val() || '•••• •••• •••• ••••').padEnd(19, '•'));
    });
    $(document).on('input', '#card-name', function () { $('#card-preview-name').text($(this).val().toUpperCase() || 'CARD HOLDER'); });
    $(document).on('input', '#card-expiry', function () {
      var v = $(this).val().replace(/\D/g, '').slice(0, 4);
      if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
      $(this).val(v);
    });

    // Place order
    $('#place-order').on('click', function (e) {
      e.preventDefault();
      if (!Store.cartDetailed().length) { Store.toast('Your cart is empty.', 'error'); return; }
      var form = document.getElementById('checkout-form');
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        Store.toast('Please complete the required fields.', 'error');
        var firstInvalid = $(form).find(':invalid').first();
        if (firstInvalid.length) $('html,body').animate({ scrollTop: firstInvalid.offset().top - 120 }, 300);
        return;
      }
      placeOrder();
    });
  }

  function placeOrder() {
    var items = Store.cartDetailed();
    var t = Store.calcTotals(selectedShipping);
    var payLabels = { card: 'Credit Card', cod: 'Cash on Delivery', paypal: 'PayPal' };
    var now = new Date();
    var isoDate = now.toISOString().slice(0, 10);
    var order = {
      id: genOrderId(),
      date: isoDate,
      status: selectedPayment === 'cod' ? 'pending' : 'processing',
      statusLabel: selectedPayment === 'cod' ? 'Pending' : 'Processing',
      payment: selectedPayment === 'cod' ? 'Pending' : 'Paid',
      paymentMethod: payLabels[selectedPayment] || 'Credit Card',
      subtotal: t.subtotal, discount: t.discount, coupon: t.coupon,
      total: t.total, shipping: t.shipping, tax: t.tax,
      items: items.map(function (i) { return { productId: i.id, qty: i.qty, price: i.product.price, color: i.color, size: i.size }; }),
      customer: {
        firstName: $('#first-name').val(), lastName: $('#last-name').val(), email: $('#email').val(), phone: $('#phone').val()
      },
      shippingAddress: {
        name: ($('#first-name').val() + ' ' + $('#last-name').val()).trim(),
        phone: $('#phone').val(),
        line1: $('#address').val(),
        line2: $('#apartment').val(),
        city: $('#city').val(),
        state: $('#state').val(),
        zip: $('#postal').val(),
        country: $('#country').val()
      },
      timeline: [
        { label: 'Order placed', desc: now.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }), state: 'done' },
        { label: selectedPayment === 'cod' ? 'Awaiting payment' : 'Payment confirmed', desc: selectedPayment === 'cod' ? 'Pay on delivery' : 'Just now', state: selectedPayment === 'cod' ? 'active' : 'done' },
        { label: 'Shipped', desc: 'Pending', state: '' },
        { label: 'Delivered', desc: 'Pending', state: '' }
      ]
    };
    var placed = placedOrders();
    placed.unshift(order);
    Store.write(PLACED_KEY, placed);
    Store.write(Store.lsKeys.order, order);
    Store.clearCart();
    Store.clearCoupon();
    window.location.href = 'order-success.html?id=' + encodeURIComponent(order.id);
  }

  /* ==================================================================
     ORDER SUCCESS
  ================================================================== */
  function initOrderSuccess() {
    var $root = $('#order-success-content');
    if (!$root.length) return;
    var id = Store.urlParam('id');
    var order = id ? findOrder(id) : Store.read(Store.lsKeys.order, null);
    if (!order) {
      $root.html('<div class="empty-state"><h4>No recent order found</h4><a href="shop.html" class="btn btn-primary-v">Continue shopping</a></div>');
      return;
    }
    var addr = order.shippingAddress;
    var itemsHtml = order.items.map(function (i) {
      var p = DB.productById(i.productId);
      if (!p) return '';
      return '<div class="os-item"><img src="' + p.image + '" alt="' + Store.esc(p.name) + '">' +
        '<div><div class="osi-name">' + Store.esc(p.name) + '</div><div class="osi-meta">Qty: ' + i.qty + '</div></div>' +
        '<div class="osi-price">' + Store.money(p.price * i.qty) + '</div></div>';
    }).join('');

    $('#order-number').text(order.id);
    $('#order-success-email').text(order.customer ? order.customer.email : '');
    $root.html('' +
      '<div class="row g-4">' +
        '<div class="col-lg-7">' +
          '<div class="checkout-card"><h5 class="mb-3">Order summary</h5>' + itemsHtml +
            '<hr class="v-divider">' +
            '<div class="summary-line"><span class="lbl">Subtotal</span><span class="val">' + Store.money(order.subtotal != null ? order.subtotal : order.total) + '</span></div>' +
            (order.discount ? '<div class="summary-line"><span class="lbl">Discount</span><span class="val text-success">− ' + Store.money(order.discount) + '</span></div>' : '') +
            '<div class="summary-line"><span class="lbl">Shipping</span><span class="val">' + (order.shipping ? Store.money(order.shipping) : 'FREE') + '</span></div>' +
            '<div class="summary-line"><span class="lbl">Tax</span><span class="val">' + Store.money(order.tax) + '</span></div>' +
            '<div class="summary-line total"><span class="lbl">Total paid</span><span class="val">' + Store.money(order.total) + '</span></div>' +
          '</div>' +
        '</div>' +
        '<div class="col-lg-5">' +
          '<div class="checkout-card mb-4"><h5 class="mb-3">Delivery details</h5>' +
            '<p class="mb-1"><strong>' + Store.esc(addr.name) + '</strong></p>' +
            '<p class="text-muted mb-1">' + Store.esc(addr.line1) + (addr.line2 ? ', ' + Store.esc(addr.line2) : '') + '</p>' +
            '<p class="text-muted mb-1">' + Store.esc(addr.city) + ', ' + Store.esc(addr.state) + ' ' + Store.esc(addr.zip) + '</p>' +
            '<p class="text-muted mb-0">' + Store.esc(addr.country) + '</p>' +
            '<p class="text-muted mt-2 mb-0"><i class="fa-solid fa-phone"></i> ' + Store.esc(addr.phone) + '</p>' +
          '</div>' +
          '<div class="checkout-card"><h5 class="mb-3">Payment method</h5>' +
            '<p class="mb-0"><i class="fa-solid fa-credit-card text-primary-v"></i> ' + Store.esc(order.paymentMethod) + '</p>' +
            '<p class="text-muted mt-2 mb-0">Status: <span class="status-badge status-' + order.status + '">' + Store.esc(order.payment) + '</span></p>' +
          '</div>' +
        '</div>' +
      '</div>');
  }

  /* ==================================================================
     ORDERS LIST
  ================================================================== */
  function initOrders() {
    var $body = $('#orders-table-body');
    if (!$body.length) return;
    var orders = allOrders();
    $('#orders-count').text(orders.length);
    if (!orders.length) {
      $body.html('<tr><td colspan="6"><div class="empty-state"><h5>No orders yet</h5><p>When you place an order it will appear here.</p><a href="shop.html" class="btn btn-primary-v">Start shopping</a></div></td></tr>');
      return;
    }
    $body.html(orders.map(function (o) {
      var itemCount = o.items.reduce(function (s, i) { return s + i.qty; }, 0);
      var cancellable = (o.status === 'pending' || o.status === 'processing');
      return '<tr>' +
        '<td><span class="order-num">' + Store.esc(o.id) + '</span><div class="text-muted small">' + itemCount + ' item' + (itemCount === 1 ? '' : 's') + '</div></td>' +
        '<td>' + Store.formatDate(o.date) + '</td>' +
        '<td><span class="status-badge status-' + o.status + '">' + Store.esc(o.statusLabel) + '</span></td>' +
        '<td>' + Store.esc(o.payment) + '<div class="text-muted small">' + Store.esc(o.paymentMethod) + '</div></td>' +
        '<td><strong>' + Store.money(o.total) + '</strong></td>' +
        '<td><div class="d-flex gap-2 justify-content-end flex-wrap">' +
          '<a href="order-details.html?id=' + encodeURIComponent(o.id) + '" class="btn btn-sm btn-outline-v"><i class="fa-regular fa-eye"></i> View</a>' +
          '<button class="btn btn-sm btn-soft-v track-order" data-id="' + Store.esc(o.id) + '"><i class="fa-solid fa-location-dot"></i> Track</button>' +
          (cancellable ? '<button class="btn btn-sm btn-outline-danger cancel-order" data-id="' + Store.esc(o.id) + '"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>' : '') +
        '</div></td></tr>';
    }).join(''));

    $body.on('click', '.track-order', function () {
      window.location.href = 'order-details.html?id=' + encodeURIComponent($(this).data('id')) + '#track';
    }).on('click', '.cancel-order', function () {
      var id = $(this).data('id');
      if (!confirm('Cancel order ' + id + '?')) return;
      var placed = placedOrders();
      var found = false;
      placed.forEach(function (o) { if (o.id === id) { o.status = 'cancelled'; o.statusLabel = 'Cancelled'; o.payment = 'Refunded'; found = true; } });
      if (found) Store.write(PLACED_KEY, placed);
      Store.toast('Order ' + id + ' cancelled.', 'info');
      initOrders();
    });
  }

  /* ==================================================================
     ORDER DETAILS
  ================================================================== */
  function initOrderDetails() {
    var $root = $('#order-details-content');
    if (!$root.length) return;
    var id = Store.urlParam('id');
    var order = id ? findOrder(id) : allOrders()[0];
    if (!order) { $root.html('<div class="empty-state"><h4>Order not found</h4></div>'); return; }
    document.title = 'Order ' + order.id + ' — Vendora';

    var itemsHtml = order.items.map(function (i) {
      var p = DB.productById(i.productId);
      if (!p) return '';
      var meta = [i.color ? 'Color: ' + Store.esc(i.color) : '', i.size ? 'Size: ' + Store.esc(i.size) : ''].filter(Boolean).join(' · ');
      return '<tr>' +
        '<td><div class="cart-item"><img src="' + p.image + '" alt="' + Store.esc(p.name) + '" style="width:64px;height:64px"><div>' +
          '<div class="ci-name"><a href="product-details.html?id=' + p.id + '">' + Store.esc(p.name) + '</a></div>' +
          '<div class="ci-meta">' + Store.esc(p.brand) + (meta ? ' · ' + meta : '') + '</div></div></div></td>' +
        '<td>' + Store.money(p.price) + '</td><td>' + i.qty + '</td><td><strong>' + Store.money(p.price * i.qty) + '</strong></td></tr>';
    }).join('');

    var timelineHtml = (order.timeline || []).map(function (t) {
      return '<div class="tl-item ' + (t.state || '') + '"><div class="tl-dot"><i class="fa-solid fa-check"></i></div>' +
        '<div class="tl-content"><h6>' + Store.esc(t.label) + '</h6><p>' + Store.esc(t.desc) + '</p></div></div>';
    }).join('');

    var addr = order.shippingAddress;
    $('#od-number').text(order.id);
    $('#od-date').text(Store.formatDate(order.date));
    $('#od-status').html('<span class="status-badge status-' + order.status + '">' + Store.esc(order.statusLabel) + '</span>');

    $root.html('' +
      '<div class="row g-4">' +
        '<div class="col-lg-8">' +
          '<div class="checkout-card"><div class="cc-title"><h5><i class="fa-solid fa-box-open text-primary-v"></i> Order items</h5></div>' +
            '<div class="table-responsive"><table class="table-v mb-0"><thead><tr><th>Product</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead><tbody>' + itemsHtml + '</tbody></table></div>' +
            '<hr class="v-divider my-4">' +
            '<div class="row justify-content-end"><div class="col-md-6">' +
              '<div class="summary-line"><span class="lbl">Subtotal</span><span class="val">' + Store.money(order.subtotal != null ? order.subtotal : (order.total - order.shipping - order.tax)) + '</span></div>' +
              (order.discount ? '<div class="summary-line"><span class="lbl">Discount</span><span class="val text-success">− ' + Store.money(order.discount) + '</span></div>' : '') +
              '<div class="summary-line"><span class="lbl">Shipping</span><span class="val">' + (order.shipping ? Store.money(order.shipping) : 'FREE') + '</span></div>' +
              '<div class="summary-line"><span class="lbl">Tax</span><span class="val">' + Store.money(order.tax) + '</span></div>' +
              '<div class="summary-line total"><span class="lbl">Total</span><span class="val">' + Store.money(order.total) + '</span></div>' +
            '</div></div>' +
          '</div>' +
          '<div class="checkout-card mt-4" id="track"><div class="cc-title"><h5><i class="fa-solid fa-truck-fast text-primary-v"></i> Track your order</h5></div>' +
            '<div class="timeline">' + timelineHtml + '</div>' +
          '</div>' +
        '</div>' +
        '<div class="col-lg-4">' +
          '<div class="checkout-card mb-4"><h5 class="mb-3">Shipping address</h5>' +
            '<p class="mb-1"><strong>' + Store.esc(addr.name) + '</strong></p>' +
            '<p class="text-muted mb-1">' + Store.esc(addr.line1) + (addr.line2 ? ', ' + Store.esc(addr.line2) : '') + '</p>' +
            '<p class="text-muted mb-1">' + Store.esc(addr.city) + ', ' + Store.esc(addr.state) + ' ' + Store.esc(addr.zip) + '</p>' +
            '<p class="text-muted mb-0">' + Store.esc(addr.country) + '</p>' +
            '<p class="text-muted mt-2 mb-0"><i class="fa-solid fa-phone"></i> ' + Store.esc(addr.phone) + '</p>' +
          '</div>' +
          '<div class="checkout-card mb-4"><h5 class="mb-3">Payment</h5>' +
            '<p class="mb-1"><i class="fa-solid fa-credit-card text-primary-v"></i> ' + Store.esc(order.paymentMethod) + '</p>' +
            '<p class="text-muted mb-0">Payment status: <strong>' + Store.esc(order.payment) + '</strong></p>' +
          '</div>' +
          '<div class="checkout-card"><h5 class="mb-3">Need help?</h5>' +
            '<p class="text-muted">Our support team is available 24/7 to assist you.</p>' +
            '<a href="contact.html" class="btn btn-outline-v btn-block mb-2"><i class="fa-regular fa-comment-dots"></i> Contact support</a>' +
            '<a href="returns.html" class="btn btn-soft-v btn-block"><i class="fa-solid fa-rotate-left"></i> Return policy</a>' +
          '</div>' +
        '</div>' +
      '</div>');
  }

  $(function () {
    initCheckout();
    initOrderSuccess();
    initOrders();
    initOrderDetails();
  });

})(window, jQuery);
