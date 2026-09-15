/* ==========================================================================
   Vendora E-commerce — Wishlist (wishlist.js)
   Fills the static wishlist markup. Does not build page HTML.
   Depends on: jQuery, main.js (Store)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store;

  function fillCard($card, item) {
    var id = item.id;
    var url = item.url || ('product-details.html?id=' + id);
    $card.attr('data-id', id);
    $card.find('.pc-img').attr({ src: item.image, alt: item.name });
    $card.find('.pc-link, .pc-title-link').attr('href', url);
    $card.find('.pc-link').attr('aria-label', item.name);
    $card.find('.pc-name').text(item.name);
    $card.find('.pc-cat').text(item.category || '');
    $card.find('.pc-desc').text(item.short || '');
    $card.find('.pc-price .now').text(item.priceText || Store.money(item.price));
    if (item.oldPriceText) $card.find('.pc-price .old').text(item.oldPriceText).show();
    else $card.find('.pc-price .old').hide();
    $card.find('.wishlist-btn, .compare-btn, .quickview-btn, .add-to-cart').attr('data-id', id);
  }

  function render() {
    if (!$('#wishlist-page').length) return;
    var items = Store.getWishlist();
    var tpl = document.getElementById('wishlist-card-template');
    var $grid = $('#wishlist-grid');
    $grid.empty();
    $('.wishlist-header-count').text(items.length ? '(' + items.length + ' product' + (items.length === 1 ? '' : 's') + ')' : '');

    if (!items.length) {
      $('#wishlist-empty').removeClass('d-none');
      $('#wishlist-filled').addClass('d-none');
      $('#wishlist-actions').addClass('d-none');
      return;
    }

    $('#wishlist-empty').addClass('d-none');
    $('#wishlist-filled').removeClass('d-none');
    $('#wishlist-actions').removeClass('d-none');
    if (!tpl) return;
    items.forEach(function (item) {
      var $card = $(tpl.content.cloneNode(true)).find('.product-card');
      fillCard($card, item);
      $grid.append($card);
    });
  }

  function init() {
    if (!$('#wishlist-page').length) return;
    render();
    $(window).on('wishlist:changed', render);

    $('#wishlist-actions').on('click', '#add-all-to-cart', function () {
      var ids = Store.getWishlist();
      if (!ids.length) return;
      ids.forEach(function (item) { Store.addToCart(item, 1); });
      Store.toast(ids.length + ' item(s) moved to your cart', 'success');
    });

    $('#wishlist-actions').on('click', '#clear-wishlist', function () {
      if (confirm('Remove all items from your wishlist?')) Store.saveWishlist([]);
    });
  }

  $(init);
})(window, jQuery);
