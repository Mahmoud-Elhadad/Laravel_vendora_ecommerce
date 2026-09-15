/* ==========================================================================
   Vendora E-commerce — Cart (cart.js)
   Fills the static cart markup. Does not build page HTML.
   Depends on: jQuery, main.js (Store)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store;

  function fillRow($row, item) {
    var p = item.product;
    $row.attr('data-key', item.key);
    $row.find('.ci-img').attr({ src: p.image, alt: p.name });
    $row.find('.ci-link, .ci-name-link').attr('href', p.url || ('product-details.html?id=' + p.id));
    $row.find('.ci-name-link').text(p.name);
    $row.find('.ci-brand-cat').text([p.brand, p.categoryName].filter(Boolean).join(' · '));
    var bits = [];
    if (item.color) bits.push('Color: ' + item.color);
    if (item.size) bits.push('Size: ' + item.size);
    $row.find('.ci-opts').text(bits.join(' · ')).toggle(!!bits.length);
    $row.find('.ci-unit, .ci-unit-mobile').text(Store.money(p.price));
    $row.find('.ci-line').text(Store.money(item.lineTotal));
    $row.find('.qty-input').val(item.qty).attr('data-key', item.key);
    $row.find('.qty-minus, .qty-plus, .cart-remove').attr('data-key', item.key);
    $row.find('.cart-move-wishlist').attr({ 'data-key': item.key, 'data-id': p.id });
  }

  function render() {
    if (!$('#cart-page').length) return;
    var items = Store.cartDetailed();
    var $rows = $('#cart-rows');
    var tpl = document.getElementById('cart-row-template');
    $rows.empty();

    if (!items.length) {
      $('#cart-empty').removeClass('d-none');
      $('#cart-filled').addClass('d-none');
      $('.cart-header-count').text('');
    } else if (tpl) {
      $('#cart-empty').addClass('d-none');
      $('#cart-filled').removeClass('d-none');
      items.forEach(function (item) {
        var $row = $(tpl.content.cloneNode(true)).find('tr');
        fillRow($row, item);
        $rows.append($row);
      });
      $('.cart-header-count').text(Store.cartCount() + ' item' + (Store.cartCount() === 1 ? '' : 's'));
    }

    var t = Store.calcTotals();
    var count = Store.cartCount();
    $('#sum-count').text(count);
    $('#sum-subtotal').text(Store.money(t.subtotal));
    $('#sum-tax').text(Store.money(t.tax));
    $('#sum-total').text(Store.money(t.total));
    if (t.shipping === 0 && t.subtotal > 0) $('#sum-shipping').text('FREE').addClass('free');
    else $('#sum-shipping').text(Store.money(t.shipping)).removeClass('free');

    if (t.discount > 0) {
      $('#sum-discount-row').removeClass('d-none');
      $('#sum-coupon').text(t.coupon || '');
      $('#sum-discount').text('− ' + Store.money(t.discount));
      $('#coupon-code').val(t.coupon || '');
      $('#apply-coupon').addClass('d-none');
      $('#remove-coupon').removeClass('d-none');
    } else {
      $('#sum-discount-row').addClass('d-none');
      $('#apply-coupon').removeClass('d-none');
      $('#remove-coupon').addClass('d-none');
    }

    if (t.subtotal > 0 && t.subtotal - t.discount < 75) {
      $('#ship-hint').removeClass('d-none');
      $('#ship-hint-text').text('Add ' + Store.money(75 - (t.subtotal - t.discount)) + ' more for FREE shipping.');
    } else {
      $('#ship-hint').addClass('d-none');
    }
  }

  function init() {
    if (!$('#cart-page').length) return;
    render();
    $(window).on('cart:changed', render);

    $('#cart-page').on('click', '.qty-plus', function () {
      var key = $(this).data('key');
      var $i = $(this).siblings('.qty-input');
      var max = parseInt($i.attr('max') || Infinity, 10);
      var item = Store.getCart().filter(function (i) { return i.key === key; })[0];
      if (item && item.qty < max) Store.updateCartQty(key, item.qty + 1);
    }).on('click', '.qty-minus', function () {
      var key = $(this).data('key');
      var item = Store.getCart().filter(function (i) { return i.key === key; })[0];
      if (!item) return;
      if (item.qty <= 1) { if (confirm('Remove this item from your cart?')) Store.removeFromCart(key); }
      else Store.updateCartQty(key, item.qty - 1);
    }).on('input change', '.qty-input', function () {
      var $i = $(this);
      var min = parseInt($i.attr('min') || 1, 10);
      var max = parseInt($i.attr('max') || Infinity, 10);
      var val = parseInt($i.val(), 10);
      if (isNaN(val) || val < min) { val = min; $i.val(min); }
      else if (val > max) { val = max; $i.val(max); }
      Store.updateCartQty($i.data('key'), val);
    });

    $('#cart-page').on('click', '.cart-remove', function () {
      Store.removeFromCart($(this).data('key'));
    }).on('click', '.cart-move-wishlist', function () {
      var key = $(this).data('key');
      var item = Store.getCart().filter(function (i) { return i.key === key; })[0];
      if (item) Store.toggleWishlist(item);
      Store.removeFromCart(key);
    });

    $('#cart-page').on('click', '#clear-cart', function () {
      if (confirm('Remove all items from your cart?')) Store.clearCart();
    });

    $('#cart-summary').on('click', '#apply-coupon', function () {
      var code = $('#coupon-code').val();
      if (Store.setCoupon(code)) { Store.toast('Coupon "' + String(code).toUpperCase() + '" applied!', 'success'); render(); }
      else Store.toast('Invalid or expired coupon code.', 'error');
    }).on('click', '#remove-coupon', function () {
      Store.clearCoupon(); Store.toast('Coupon removed', 'info'); render();
    });
  }

  $(init);
})(window, jQuery);
