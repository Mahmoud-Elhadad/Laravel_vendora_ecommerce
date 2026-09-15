/* ==========================================================================
   Vendora E-commerce — Core (main.js)
   Defines the global `Store` engine: localStorage state (cart / wishlist /
   compare / recently-viewed), reusable rendering helpers, toasts, header
   behaviour, countdowns and delegated UI events.
   Depends on: jQuery, Bootstrap 5, data.js (window.VendoraDB)
   ========================================================================== */
(function (window, $) {
  'use strict';

  var DB = window.VendoraDB;
  var LS = {
    cart: 'vendora_cart',
    wishlist: 'vendora_wishlist',
    compare: 'vendora_compare',
    recent: 'vendora_recent',
    coupon: 'vendora_coupon',
    order: 'vendora_last_order',
    announcement: 'vendora_announcement_closed'
  };

  /* Temporary in-tab display only (not localStorage / not DB).
     Survives clicking to cart.html / wishlist.html.
     Cleared on browser refresh so Laravel can own persistence later. */
  var mem = { cart: [], wishlist: [], compare: [], coupon: null };
  var TMP = { cart: 'vendora_tmp_cart', wishlist: 'vendora_tmp_wishlist', compare: 'vendora_tmp_compare', coupon: 'vendora_tmp_coupon' };

  function navType() {
    try {
      var n = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
      if (n && n.type) return n.type;
      if (performance.navigation) {
        if (performance.navigation.type === 1) return 'reload';
        if (performance.navigation.type === 2) return 'back_forward';
      }
    } catch (e) {}
    return 'navigate';
  }
  function persistMem() {
    try {
      sessionStorage.setItem(TMP.cart, JSON.stringify(mem.cart));
      sessionStorage.setItem(TMP.wishlist, JSON.stringify(mem.wishlist));
      sessionStorage.setItem(TMP.compare, JSON.stringify(mem.compare));
      if (mem.coupon) sessionStorage.setItem(TMP.coupon, mem.coupon);
      else sessionStorage.removeItem(TMP.coupon);
    } catch (e) {}
  }
  function bootMem() {
    if (navType() === 'reload') {
      try {
        sessionStorage.removeItem(TMP.cart);
        sessionStorage.removeItem(TMP.wishlist);
        sessionStorage.removeItem(TMP.compare);
        sessionStorage.removeItem(TMP.coupon);
      } catch (e) {}
      mem = { cart: [], wishlist: [], compare: [], coupon: null };
      return;
    }
    try {
      mem.cart = JSON.parse(sessionStorage.getItem(TMP.cart) || '[]') || [];
      mem.wishlist = JSON.parse(sessionStorage.getItem(TMP.wishlist) || '[]') || [];
      mem.compare = JSON.parse(sessionStorage.getItem(TMP.compare) || '[]') || [];
      mem.coupon = sessionStorage.getItem(TMP.coupon) || null;
    } catch (e) {
      mem = { cart: [], wishlist: [], compare: [], coupon: null };
    }
  }
  bootMem();

  function read(key, fallback) { return fallback; }
  function write(key, val) {}
  function parseMoney(t) {
    return Number(String(t == null ? '' : t).replace(/[^0-9.]/g, '')) || 0;
  }
  function itemKey(id, opts) {
    opts = opts || {};
    return String(id) + '|' + (opts.color || '') + '|' + (opts.size || '');
  }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  /* ------------------------------------------------------------------
     Store object
  ------------------------------------------------------------------ */
  var Store = {
    DB: DB,
    esc: esc,
    lsKeys: LS,
    read: read,
    write: write,

    /* ---------- Formatting ---------- */
    money: function (n) {
      n = Number(n) || 0;
      return '$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },
    stars: function (rating) {
      rating = Number(rating) || 0;
      var html = '<span class="stars" aria-label="Rated ' + rating + ' out of 5">';
      for (var i = 1; i <= 5; i++) {
        if (rating >= i) html += '<i class="fa-solid fa-star"></i>';
        else if (rating >= i - 0.5) html += '<i class="fa-solid fa-star-half-stroke"></i>';
        else html += '<i class="fa-regular fa-star off"></i>';
      }
      html += '</span>';
      return html;
    },
    stockLabel: function (p) {
      if (p.stock === 'out') return '<span class="stock-out"><i class="fa-solid fa-circle-xmark"></i> Out of stock</span>';
      if (p.stock === 'low') return '<span class="stock-low"><i class="fa-solid fa-fire"></i> Only ' + p.stockQty + ' left</span>';
      return '<span class="stock-in"><i class="fa-solid fa-circle-check"></i> In stock</span>';
    },
    formatDate: function (d) {
      var dt = new Date(d + (d.length === 10 ? 'T00:00:00' : ''));
      if (isNaN(dt)) return d;
      return dt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    },
    urlParam: function (name) {
      var m = new RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
      return m ? decodeURIComponent(m[1].replace(/\+/g, ' ')) : null;
    },

    parseMoney: parseMoney,
    snapshotFrom: function (el) {
      var $el = $(el);
      var $card = $el.closest('.product-card');
      if ($card.length) {
        var now = $card.find('.pc-price .now').first().text().trim();
        var old = $card.find('.pc-price .old').first().text().trim();
        return {
          id: String($card.data('id') || ''),
          name: $card.find('.pc-title').text().trim(),
          image: $card.find('.pc-media img').attr('src') || '',
          priceText: now,
          price: parseMoney(now),
          oldPriceText: old,
          discount: parseInt($card.data('discount') || 0, 10),
          category: $card.find('.pc-cat').text().trim(),
          brand: '',
          url: $card.find('.pc-title a').attr('href') || 'product-details.html',
          short: $card.find('.pc-desc').text().trim(),
          stockQty: parseInt($card.data('stock') || 0, 10)
        };
      }
      var $pd = $el.closest('#product-details');
      if (!$pd.length && $('#product-details').length && $el.is('#pd-add-cart, #pd-buy-now, #pd-wishlist, #pd-compare')) {
        $pd = $('#product-details');
      }
      if ($pd.length) {
        var pdNow = $pd.find('.pd-price .now').first().text().trim();
        return {
          id: String($('#pd-add-cart').data('id') || 1),
          name: $pd.find('.pd-info h1').text().trim(),
          image: $('#pd-main-img').attr('src') || '',
          priceText: pdNow,
          price: parseMoney(pdNow),
          oldPriceText: $pd.find('.pd-price .old').first().text().trim(),
          discount: parseInt($pd.data('discount') || 0, 10),
          category: $pd.find('.pd-cat').text().trim(),
          brand: $pd.find('.pd-meta a').first().text().trim(),
          url: 'product-details.html',
          short: $pd.find('.pd-short').text().trim()
        };
      }
      if ($('#quickViewModal').hasClass('show') || $el.closest('#quickViewModal').length) {
        var stored = $('#quickViewModal').data('snapshot');
        if (stored) return stored;
        var qvNow = $('#qv-price').text().trim();
        return {
          id: String($('#quickViewModal').data('current-id') || ''),
          name: $('#qv-name').text().trim(),
          image: $('#qv-img').attr('src') || '',
          priceText: qvNow,
          price: parseMoney(qvNow),
          oldPriceText: $('#qv-old').text().trim(),
          category: $('#qv-cat').text().trim(),
          brand: '',
          url: $('#quickViewModal .qv-details').attr('href') || 'product-details.html',
          short: $('#qv-short').text().trim()
        };
      }
      return null;
    },
    snapshotFromId: function (id) {
      var p = DB && DB.productById ? DB.productById(id) : null;
      if (!p) return { id: String(id), name: 'Product', image: '', price: 0, priceText: '$0.00', category: '', brand: '', url: 'product-details.html?id=' + id, short: '' };
      return {
        id: String(p.id),
        name: p.name,
        image: p.image,
        price: p.price,
        priceText: this.money(p.price),
        oldPriceText: p.oldPrice > p.price ? this.money(p.oldPrice) : '',
        category: p.categoryName,
        brand: p.brand,
        url: 'product-details.html?id=' + p.id,
        short: p.short || ''
      };
    },
    asSnap: function (idOrSnap) {
      if (idOrSnap && typeof idOrSnap === 'object') return idOrSnap;
      return this.snapshotFromId(idOrSnap);
    },

    /* ==================================================================
       CART (display only this visit — cleared on refresh)
    ================================================================== */
    getCart: function () { return mem.cart.slice(); },
    saveCart: function (c) {
      mem.cart = c || [];
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('cart:changed');
    },
    cartCount: function () {
      return mem.cart.reduce(function (n, i) { return n + (Number(i.qty) || 0); }, 0);
    },

    addToCart: function (idOrSnap, qty, opts) {
      qty = qty || 1; opts = opts || {};
      var snap = this.asSnap(idOrSnap);
      if (!snap || !snap.name) return false;
      var key = itemKey(snap.id, opts);
      var found = mem.cart.filter(function (i) { return i.key === key; })[0];
      if (found) found.qty += qty;
      else {
        mem.cart.push({
          key: key,
          id: snap.id,
          qty: qty,
          color: opts.color || '',
          size: opts.size || '',
          name: snap.name,
          image: snap.image,
          price: snap.price,
          priceText: snap.priceText,
          oldPriceText: snap.oldPriceText || '',
          category: snap.category || '',
          brand: snap.brand || '',
          url: snap.url || '',
          short: snap.short || ''
        });
      }
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('cart:changed');
      this.toast(snap.name + ' added to cart', 'success');
      return true;
    },
    updateCartQty: function (key, qty) {
      qty = Math.max(1, parseInt(qty, 10) || 1);
      mem.cart.forEach(function (i) { if (i.key === key) i.qty = qty; });
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('cart:changed');
    },
    removeFromCart: function (key) {
      mem.cart = mem.cart.filter(function (i) { return i.key !== key; });
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('cart:changed');
      this.toast('Item removed from cart', 'info');
    },
    clearCart: function () { this.saveCart([]); },
    cartDetailed: function () {
      return mem.cart.map(function (i) {
        return {
          key: i.key,
          qty: i.qty,
          color: i.color,
          size: i.size,
          lineTotal: (Number(i.price) || 0) * (Number(i.qty) || 0),
          product: {
            id: i.id,
            name: i.name,
            image: i.image,
            brand: i.brand,
            categoryName: i.category,
            price: i.price,
            url: i.url
          }
        };
      });
    },
    cartSubtotal: function () {
      return mem.cart.reduce(function (s, i) { return s + (Number(i.price) || 0) * (Number(i.qty) || 0); }, 0);
    },

    /* Totals with shipping, tax and coupon. */
    calcTotals: function (shippingMethod) {
      var subtotal = this.cartSubtotal();
      var coupon = mem.coupon;
      var discount = 0;
      if (coupon && subtotal > 0) discount = this.applyCouponValue(coupon, subtotal);
      var shipping = 0;
      if (subtotal > 0 && subtotal - discount < 75) shipping = 8.99;
      if (shippingMethod === 'express') shipping = 19.99;
      if (shippingMethod === 'sameday') shipping = 29.99;
      if (subtotal === 0) shipping = 0;
      var taxable = Math.max(0, subtotal - discount);
      var tax = taxable * 0.07;
      var total = taxable + shipping + tax;
      return { subtotal: subtotal, discount: discount, coupon: coupon, shipping: shipping, tax: tax, total: total };
    },

    /* ---------- Coupons (DISABLED) ---------- */
    COUPONS: { 'WELCOME10': { type: 'percent', value: 10 }, 'SAVE20': { type: 'percent', value: 20 }, 'FLAT15': { type: 'flat', value: 15 } },
    applyCouponValue: function (code, subtotal) {
      var c = this.COUPONS[String(code).toUpperCase()];
      if (!c) return 0;
      return c.type === 'percent' ? subtotal * (c.value / 100) : Math.min(c.value, subtotal);
    },
    setCoupon: function (code) {
      code = String(code || '').trim().toUpperCase();
      if (this.COUPONS[code]) { mem.coupon = code; persistMem(); return true; }
      return false;
    },
    clearCoupon: function () { mem.coupon = null; persistMem(); },

    /* ==================================================================
       WISHLIST (display only this visit — cleared on refresh)
    ================================================================== */
    getWishlist: function () { return mem.wishlist.slice(); },
    saveWishlist: function (w) {
      mem.wishlist = w || [];
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('wishlist:changed');
    },
    isWishlisted: function (id) {
      id = String(id);
      return mem.wishlist.some(function (i) { return String(i.id) === id; });
    },
    toggleWishlist: function (idOrSnap) {
      var snap = this.asSnap(idOrSnap);
      var id = String(snap.id);
      if (this.isWishlisted(id)) {
        mem.wishlist = mem.wishlist.filter(function (i) { return String(i.id) !== id; });
        persistMem();
        this.updateHeaderCounts();
        $(window).trigger('wishlist:changed');
        this.toast((snap.name || 'Item') + ' removed from wishlist', 'info');
        return false;
      }
      mem.wishlist.push(snap);
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('wishlist:changed');
      this.toast((snap.name || 'Item') + ' added to wishlist', 'success');
      return true;
    },

    /* ==================================================================
       COMPARE (display only this visit — cleared on refresh)
    ================================================================== */
    getCompare: function () { return mem.compare.slice(); },
    saveCompare: function (c) {
      mem.compare = c || [];
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('compare:changed');
    },
    isCompared: function (id) {
      id = String(id);
      return mem.compare.some(function (i) { return String(i.id) === id; });
    },
    toggleCompare: function (idOrSnap) {
      var snap = this.asSnap(idOrSnap);
      var id = String(snap.id);
      if (this.isCompared(id)) {
        mem.compare = mem.compare.filter(function (i) { return String(i.id) !== id; });
        persistMem();
        this.updateHeaderCounts();
        $(window).trigger('compare:changed');
        this.toast((snap.name || 'Item') + ' removed from compare', 'info');
        return false;
      }
      mem.compare.push(snap);
      persistMem();
      this.updateHeaderCounts();
      $(window).trigger('compare:changed');
      this.toast((snap.name || 'Item') + ' added to compare', 'success');
      return true;
    },

    /* ==================================================================
       RECENTLY VIEWED (DISABLED)
    ================================================================== */
    getRecent: function () { return []; },
    addRecent: function (id) {
      // disabled - do not store
    },

    /* ==================================================================
       RENDERING HELPERS
    ================================================================== */
    productCard: function (p, opts) {
      opts = opts || {};
      var self = this;
      var wish = this.isWishlisted(p.id) ? ' active' : '';
      var comp = this.isCompared(p.id) ? ' compare-active' : '';
      var detailUrl = 'product-details.html?id=' + p.id;
      var badges = '';
      if (p.stock === 'out') badges += '<span class="badge-v badge-off">Sold out</span>';
      if (p.discount > 0) badges += '<span class="badge-v badge-sale">-' + p.discount + '%</span>';
      if (p.isNew && p.stock !== 'out') badges += '<span class="badge-v badge-new">New</span>';
      else if (p.isBestSeller && p.stock !== 'out') badges += '<span class="badge-v badge-hot">Hot</span>';

      var colors = '';
      if (p.colors && p.colors.length) {
        colors = '<div class="pc-colors">' + p.colors.slice(0, 4).map(function (c) {
          return '<span class="swatch" style="background:' + c.hex + '" title="' + esc(c.name) + '"></span>';
        }).join('') + '</div>';
      }

      var disabled = p.stock === 'out' ? ' disabled' : '';
      var cartLabel = p.stock === 'out' ? 'Sold out' : 'Add to Cart';

      return '' +
        '<article class="product-card fade-in-up" data-id="' + p.id + '">' +
          '<div class="pc-media">' +
            '<a href="' + detailUrl + '" aria-label="' + esc(p.name) + '">' +
              '<img src="' + p.image + '" alt="' + esc(p.name) + '" loading="lazy">' +
            '</a>' +
            '<div class="pc-badges">' + badges + '</div>' +
            '<div class="pc-actions">' +
              '<button class="wishlist-btn' + wish + '" data-id="' + p.id + '" title="Add to wishlist" aria-label="Add to wishlist"><i class="' + (wish ? 'fa-solid' : 'fa-regular') + ' fa-heart"></i></button>' +
              '<button class="compare-btn' + comp + '" data-id="' + p.id + '" title="Compare" aria-label="Compare"><i class="fa-solid fa-scale-balanced"></i></button>' +
              '<button class="quickview-btn" data-id="' + p.id + '" title="Quick view" aria-label="Quick view"><i class="fa-regular fa-eye"></i></button>' +
            '</div>' +
            '<div class="pc-quickview"><button class="btn btn-dark-v btn-sm btn-block quickview-btn" data-id="' + p.id + '"><i class="fa-regular fa-eye"></i> Quick view</button></div>' +
          '</div>' +
          '<div class="pc-body">' +
            '<div class="pc-cat">' + esc(p.categoryName) + '</div>' +
            '<h3 class="pc-title"><a href="' + detailUrl + '"><span class="clamp-2">' + esc(p.name) + '</span></a></h3>' +
            '<div class="pc-rating rating-line">' + this.stars(p.rating) + '<span class="count">(' + p.reviews + ')</span></div>' +
            '<p class="pc-desc">' + esc(p.short) + '</p>' +
            colors +
            '<div class="pc-price">' +
              '<span class="now">' + this.money(p.price) + '</span>' +
              (p.oldPrice > p.price ? '<span class="old">' + this.money(p.oldPrice) + '</span>' : '') +
              (p.discount > 0 ? '<span class="off">-' + p.discount + '%</span>' : '') +
            '</div>' +
            '<button class="btn btn-primary-v btn-block pc-addcart add-to-cart" data-id="' + p.id + '"' + disabled + '>' +
              '<i class="fa-solid fa-cart-plus"></i> ' + cartLabel +
            '</button>' +
          '</div>' +
        '</article>';
    },

    renderGrid: function ($container, products, viewMode) {
      if (!$container || !$container.length) return;
      if (!products.length) {
        $container.html(this.emptyProductsHtml());
        return;
      }
      var html = products.map(function (p) { return Store.productCard(p); }).join('');
      $container.removeClass('products-list').addClass(viewMode === 'list' ? 'products-list' : 'products-grid');
      $container.html(html);
    },
    emptyProductsHtml: function (msg) {
      return '<div class="empty-state no-results">' +
        '<img src="' + DB.IMG + 'ui/empty-search.svg" alt="No products">' +
        '<h4>' + (msg || 'No products found') + '</h4>' +
        '<p>Try adjusting your filters or search terms to find what you are looking for.</p>' +
        '<a href="shop.html" class="btn btn-primary-v">Browse all products</a></div>';
    },

    /* ==================================================================
       TOASTS
    ================================================================== */
    toast: function (message, type) {
      type = type || 'success';
      var $c = $('.toast-container-v');
      if (!$c.length) $c = $('<div class="toast-container-v"></div>').appendTo('body');
      var icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info' };
      var $t = $('<div class="v-toast ' + type + '">' +
        '<i class="fa-solid ' + (icons[type] || icons.success) + ' vt-ico"></i>' +
        '<span class="vt-msg">' + esc(message) + '</span>' +
        '<button class="vt-close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button></div>');
      $c.append($t);
      var remove = function () { $t.fadeOut(220, function () { $t.remove(); }); };
      $t.find('.vt-close').on('click', remove);
      setTimeout(remove, 3200);
    },

    /* ==================================================================
       HEADER COUNTS (DISABLED - always show 0)
    ================================================================== */
    updateHeaderCounts: function () {
      var cc = this.cartCount(), wc = this.getWishlist().length, cmp = this.getCompare().length;
      $('[data-cart-count]').text(cc).toggle(cc > 0);
      $('[data-wishlist-count]').text(wc).toggle(wc > 0);
      $('[data-compare-count]').text(cmp).toggle(cmp > 0);
      $('.cart-total-mini').text(this.money(this.cartSubtotal()));
    },

    /* ==================================================================
       COUNTDOWN
    ================================================================== */
    initCountdown: function ($el) {
      var target = $el.data('countdown');
      var end = target ? new Date(target).getTime() : (Date.now() + 2 * 24 * 3600 * 1000 + 5 * 3600 * 1000);
      function box(n, l) {
        return '<div class="cd-box"><span class="cd-num">' + String(n).padStart(2, '0') + '</span><span class="cd-lbl">' + l + '</span></div>';
      }
      function tick() {
        var diff = end - Date.now();
        if (diff <= 0) { $el.html(box(0, 'Days') + box(0, 'Hours') + box(0, 'Mins') + box(0, 'Secs')); return; }
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        $el.html(box(d, 'Days') + box(h, 'Hours') + box(m, 'Mins') + box(s, 'Secs'));
      }
      tick();
      setInterval(tick, 1000);
    },

    /* ==================================================================
       QUICK VIEW — modal HTML is static in the footer.
       JS only fills existing fields (image, title, price, etc.).
    ================================================================== */
    openQuickView: function (id, trigger) {
      var self = this;
      var $m = $('#quickViewModal');
      if (!$m.length) return;

      /* Open the modal once and reuse the same instance */
      var modalInstance = bootstrap.Modal.getInstance($m[0]) || new bootstrap.Modal($m[0]);

      /* Show modal immediately with a loading state */
      $('#qv-img').attr({ src: '', alt: '' });
      $('#qv-name').text('Loading...');
      $('#qv-cat').text('');
      $('#qv-short').text('');
      $('#qv-price').text('');
      $('#qv-old').addClass('d-none');
      $('#qv-save').addClass('d-none');
      modalInstance.show();

      /* Fetch real product data from the server */
      $.ajax({
        url: '/productQuickView/' + id,
        method: 'GET',
        success: function (p) {
          $('#qv-img').attr({ src: p.image, alt: p.name });
          $('#qv-name').text(p.name);
          $('#qv-cat').text(p.category || '');
          $('#qv-short').text(p.description || '');
          $('#qv-price').text('$' + p.price.toFixed(2));

          if (p.old_price && p.discount > 0) {
            $('#qv-old').text('$' + p.old_price.toFixed(2)).removeClass('d-none');
            $('#qv-save').text('Save ' + p.discount + '%').removeClass('d-none');
          } else {
            $('#qv-old').addClass('d-none');
            $('#qv-save').addClass('d-none');
          }

          var snap = {
            id: String(p.id),
            name: p.name,
            image: p.image,
            priceText: '$' + p.price.toFixed(2),
            price: p.price,
            oldPriceText: p.old_price ? '$' + p.old_price.toFixed(2) : '',
            discount: p.discount,
            category: p.category,
            url: p.url,
            short: p.description,
            stockQty: p.stock
          };

          $m.data('current-id', String(p.id));
          $m.data('snapshot', snap);
          $('#quickViewModal .qv-details').attr('href', p.url);
          $('#quickViewModal .qv-add').attr('product_id', p.id);
          $('#quickViewModal .qv-qty').val(1).attr('max', p.stock || '');
        },
        error: function () {
          $('#qv-name').text('Could not load product.');
        }
      });
    },

    /* ==================================================================
       INIT: header, mobile nav, back-to-top, delegated events
    ================================================================== */
    init: function () {
      var self = this;

      /* Announcement close */
      if (read(LS.announcement, false)) $('.announcement-bar').remove();
      $(document).on('click', '.abar-close', function () {
        write(LS.announcement, true);
        $('.announcement-bar').slideUp(200);
      });

      /* Sticky header */
      var $header = $('.site-header');
      if ($header.length && $header.data('sticky') !== false) {
        var hTop = $header.length ? $header.offset().top : 0;
        $(window).on('scroll', function () {
          if ($(window).scrollTop() > hTop + 120) $header.addClass('is-sticky');
          else $header.removeClass('is-sticky');
        });
      }

      /* Back to top */
      var $btt = $('.back-to-top');
      if ($btt.length) {
        $(window).on('scroll', function () {
          if ($(window).scrollTop() > 400) $btt.addClass('show'); else $btt.removeClass('show');
        });
        $btt.on('click', function () { $('html,body').animate({ scrollTop: 0 }, 400); });
      }

      /* Mobile off-canvas menu: submenu toggles */
      $(document).on('click', '.mobile-menu .has-sub > a', function (e) {
        e.preventDefault();
        var $li = $(this).closest('li');
        $li.toggleClass('open').find('> .submenu').stop(true, true).slideToggle(200);
      });

      /* Mobile nav accordion in sidebar (category dropdown) */
      $(document).on('click', '.filter-head[data-bs-toggle="collapse"]', function () {
        $(this).toggleClass('collapsed');
      });

      /* Delegated product actions */
      $(document).on('click', '.add-to-cart', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var snap = self.snapshotFrom(this) || self.snapshotFromId($btn.data('id'));
        var qty = parseInt($btn.data('qty') || 1, 10);
        if (self.addToCart(snap, qty)) {
          var orig = $btn.html();
          $btn.html('<i class="fa-solid fa-check"></i> Added').addClass('btn-success-temp');
          setTimeout(function () { $btn.html(orig).removeClass('btn-success-temp'); }, 1200);
        }
      });
      /* Wishlist click is handled by the server-side AJAX handler in footer.blade.php.
         That handler owns button state toggling and the delete-confirmation modal.
         Do NOT re-enable this block — it conflicts with server state on refresh. */
      $(document).on('click', '.compare-btn', function (e) {
        e.preventDefault();
        var snap = self.snapshotFrom(this) || self.snapshotFromId($(this).data('id'));
        var on = self.toggleCompare(snap);
        $('.compare-btn[data-id="' + snap.id + '"]').toggleClass('compare-active', on);
      });
      $(document).on('click', '.quickview-btn', function (e) {
        e.preventDefault();
        self.openQuickView($(this).data('id'), this);
      });

      /* Quick view modal footer — events bound once on the static modal */
      $(document).on('click', '#quickViewModal .qv-minus', function () {
        var $i = $('#quickViewModal .qv-qty');
        var min = parseInt($i.attr('min') || 1, 10);
        $i.val(Math.max(min, parseInt($i.val() || min, 10) - 1));
      });
      $(document).on('click', '#quickViewModal .qv-plus', function () {
        var $i = $('#quickViewModal .qv-qty');
        var min = parseInt($i.attr('min') || 1, 10);
        var max = parseInt($i.attr('max') || Infinity, 10);
        $i.val(Math.min(max, Math.max(min, parseInt($i.val() || min, 10) + 1)));
      });
      $(document).on('input change', '#quickViewModal .qv-qty', function () {
        var $i = $(this);
        var min = parseInt($i.attr('min') || 1, 10);
        var max = parseInt($i.attr('max') || Infinity, 10);
        var val = parseInt($i.val(), 10);
        if (isNaN(val) || val < min) { $i.val(min); }
        else if (val > max) { $i.val(max); }
      });
      

      /* Placeholder links: with the <base href="pages/"> tag on index.html a bare
         href="#" would reload against that base dir, so neutralise them. Real
         Bootstrap toggles (data-bs-toggle) and in-page anchors (#id) are left alone. */
      $(document).on('click', 'a[href="#"]:not([data-bs-toggle])', function (e) {
        e.preventDefault();
      });

      /* Newsletter form */
      $(document).on('submit', '.nl-form, .newsletter-form', function (e) {
        e.preventDefault();
        var email = $(this).find('input[type="email"]').val();
        if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { self.toast('Please enter a valid email address.', 'error'); return; }
        self.toast('Thanks for subscribing! Check your inbox.', 'success');
        $(this)[0].reset();
      });

      /* Header search submit */
      $(document).on('submit', '.search-box', function (e) {
        e.preventDefault();
        var q = $(this).find('input[name="q"]').val() || '';
        window.location.href = 'search.html?q=' + encodeURIComponent(q);
      });

      /* Countdown timers */
      $('.countdown[data-countdown], .countdown.auto').each(function () { self.initCountdown($(this)); });

      /* Header counts + AOS */
      this.updateHeaderCounts();
      if (window.AOS) window.AOS.init({ duration: 650, once: true, offset: 60, easing: 'ease-out-cubic' });

      /* Highlight active nav link based on current page */
      var path = window.location.pathname.split('/').pop() || 'index.html';
      $('.main-nav a, .footer-list a, .account-nav a').each(function () {
        var href = $(this).attr('href');
        if (href && href.split('?')[0] === path) $(this).addClass('active');
      });
    }
  };

  /* Expose globally */
  window.Store = Store;

  $(function () { Store.init(); });

})(window, jQuery);
