/* ==========================================================================
   Vendora E-commerce — Products (product.js)
   Two engines:
     1. Listing engine  -> powers shop.html, category.html, search.html
        (filters, sorting, pagination, grid/list, active chips)
     2. Details engine  -> powers product-details.html
        (gallery, zoom, options, tabs, related & recently viewed)
   Depends on: jQuery, Bootstrap, main.js (Store), data.js (VendoraDB)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store, DB = window.VendoraDB;

  /* ==================================================================
     1. LISTING ENGINE
  ================================================================== */
  function Listing($root) {
    this.$root = $root;
    this.mode = $root.data('mode') || 'all';
    this.category = Store.urlParam('cat') || $root.data('category') || null;
    this.query = Store.urlParam('q') || $root.data('query') || '';
    this.state = {
      categories: this.category ? [this.category] : [],
      brands: [], colors: [], sizes: [], availability: [],
      minPrice: 0, maxPrice: 0, rating: 0,
      sort: 'featured', perPage: 12, page: 1, view: 'grid'
    };
    this.base = this.computeBase();
    this.priceBounds = this.computePriceBounds();
    this.state.maxPrice = this.priceBounds.max;
  }

  Listing.prototype.computeBase = function () {
    if (this.mode === 'category' && this.category) return DB.byCategory(this.category);
    if (this.mode === 'search') return DB.search(this.query);
    return DB.products.slice();
  };

  Listing.prototype.computePriceBounds = function () {
    var prices = this.base.map(function (p) { return p.price; });
    return { min: prices.length ? Math.floor(Math.min.apply(null, prices)) : 0, max: prices.length ? Math.ceil(Math.max.apply(null, prices)) : 1000 };
  };

  Listing.prototype.unique = function (field) {
    var seen = {}, out = [];
    this.base.forEach(function (p) {
      var arr = p[field] || [];
      arr.forEach(function (item) {
        var key = typeof item === 'object' ? item.name : item;
        var hex = typeof item === 'object' ? item.hex : null;
        if (!seen[key]) { seen[key] = true; out.push({ name: key, hex: hex, count: 0 }); }
      });
    });
    // counts
    var self = this;
    out.forEach(function (o) {
      o.count = self.base.filter(function (p) {
        return (p[field] || []).some(function (it) { return (typeof it === 'object' ? it.name : it) === o.name; });
      }).length;
    });
    return out.sort(function (a, b) { return a.name.localeCompare(b.name); });
  };

  Listing.prototype.filtered = function () {
    var s = this.state, self = this;
    var list = this.base.filter(function (p) {
      if (s.categories.length && s.categories.indexOf(p.category) === -1) return false;
      if (s.brands.length && s.brands.indexOf(p.brand) === -1) return false;
      if (p.price < s.minPrice || p.price > s.maxPrice) return false;
      if (s.rating && p.rating < s.rating) return false;
      if (s.colors.length && !(p.colors || []).some(function (c) { return s.colors.indexOf(c.name) !== -1; })) return false;
      if (s.sizes.length && !(p.sizes || []).some(function (z) { return s.sizes.indexOf(z) !== -1; })) return false;
      if (s.availability.length && s.availability.indexOf(p.stock) === -1) return false;
      return true;
    });
    // Sort
    var sorters = {
      'featured': function (a, b) { return (b.isFeatured - a.isFeatured) || (b.rating - a.rating); },
      'newest': function (a, b) { return (b.isNew - a.isNew) || (b.id - a.id); },
      'price-asc': function (a, b) { return a.price - b.price; },
      'price-desc': function (a, b) { return b.price - a.price; },
      'rating': function (a, b) { return b.rating - a.rating; },
      'discount': function (a, b) { return b.discount - a.discount; },
      'name': function (a, b) { return a.name.localeCompare(b.name); }
    };
    list.sort(sorters[s.sort] || sorters.featured);
    return list;
  };

  Listing.prototype.renderSidebar = function () {
    var $sb = $('#shop-filters');
    if (!$sb.length) return;
    var s = this.state, self = this;
    var html = '';

    // Category filter (only in 'all' / 'search' modes)
    if (this.mode !== 'category') {
      html += '<div class="filter-card"><div class="filter-head">Category</div><div class="filter-body"><ul class="filter-list">';
      DB.categories.forEach(function (c) {
        var checked = s.categories.indexOf(c.key) !== -1 ? ' checked' : '';
        html += '<li><label class="filter-check"><input class="form-check-input f-category" type="checkbox" value="' + c.key + '"' + checked + '> ' + Store.esc(c.name) + ' <span class="fc-count">' + c.count + '</span></label></li>';
      });
      html += '</ul></div></div>';
    }

    // Price
    var pb = this.priceBounds;
    html += '<div class="filter-card"><div class="filter-head">Price</div><div class="filter-body">' +
      '<div class="price-range-vals"><span>' + Store.money(s.minPrice) + '</span><span>' + Store.money(s.maxPrice) + '</span></div>' +
      '<input type="range" class="v-range f-price-max" min="' + pb.min + '" max="' + pb.max + '" step="1" value="' + s.maxPrice + '">' +
      '<div class="price-inputs mt-2">' +
        '<input type="number" class="form-control form-control-sm f-price-min" value="' + s.minPrice + '" min="0" placeholder="Min">' +
        '<span>—</span>' +
        '<input type="number" class="form-control form-control-sm f-price-max-in" value="' + s.maxPrice + '" min="0" placeholder="Max">' +
      '</div></div></div>';

    // Brand
    var brands = {};
    this.base.forEach(function (p) { brands[p.brand] = (brands[p.brand] || 0) + 1; });
    html += '<div class="filter-card"><div class="filter-head">Brand</div><div class="filter-body"><ul class="filter-list">';
    Object.keys(brands).sort().forEach(function (b) {
      var checked = s.brands.indexOf(b) !== -1 ? ' checked' : '';
      html += '<li><label class="filter-check"><input class="form-check-input f-brand" type="checkbox" value="' + Store.esc(b) + '"' + checked + '> ' + Store.esc(b) + ' <span class="fc-count">' + brands[b] + '</span></label></li>';
    });
    html += '</ul></div></div>';

    // Color
    var colors = this.unique('colors');
    if (colors.length) {
      html += '<div class="filter-card"><div class="filter-head">Color</div><div class="filter-body"><div class="color-swatches">';
      colors.forEach(function (c) {
        var checked = s.colors.indexOf(c.name) !== -1 ? ' checked' : '';
        html += '<label title="' + Store.esc(c.name) + '"><input class="f-color" type="checkbox" value="' + Store.esc(c.name) + '"' + checked + '><span class="sw" style="background:' + c.hex + '"></span></label>';
      });
      html += '</div></div></div>';
    }

    // Size
    var sizes = this.unique('sizes');
    if (sizes.length) {
      html += '<div class="filter-card"><div class="filter-head">Size</div><div class="filter-body"><div class="size-options">';
      sizes.forEach(function (z) {
        var checked = s.sizes.indexOf(z.name) !== -1 ? ' checked' : '';
        html += '<label><input class="f-size" type="checkbox" value="' + Store.esc(z.name) + '"' + checked + '><span class="sz">' + Store.esc(z.name) + '</span></label>';
      });
      html += '</div></div></div>';
    }

    // Rating
    html += '<div class="filter-card"><div class="filter-head">Rating</div><div class="filter-body"><ul class="filter-list rating-filter">';
    [4, 3, 2, 1].forEach(function (r) {
      var checked = s.rating === r ? ' checked' : '';
      html += '<li><label class="filter-check"><input class="form-check-input f-rating" type="radio" name="rating" value="' + r + '"' + checked + '> ' + Store.stars(r) + ' <span class="fc-count">& up</span></label></li>';
    });
    html += '<li><label class="filter-check"><input class="form-check-input f-rating" type="radio" name="rating" value="0"' + (s.rating === 0 ? ' checked' : '') + '> Any rating</label></li>';
    html += '</ul></div></div>';

    // Availability
    var stockOpts = [['in', 'In stock'], ['low', 'Low stock'], ['out', 'Out of stock']];
    html += '<div class="filter-card"><div class="filter-head">Availability</div><div class="filter-body"><ul class="filter-list">';
    stockOpts.forEach(function (o) {
      var checked = s.availability.indexOf(o[0]) !== -1 ? ' checked' : '';
      html += '<li><label class="filter-check"><input class="form-check-input f-avail" type="checkbox" value="' + o[0] + '"' + checked + '> ' + o[1] + '</label></li>';
    });
    html += '</ul></div></div>';

    html += '<button class="btn btn-outline-v btn-block" id="clear-filters"><i class="fa-solid fa-rotate-left"></i> Clear all filters</button>';
    $sb.html(html);
  };

  Listing.prototype.renderToolbar = function (total) {
    var $tb = $('#shop-toolbar');
    if (!$tb.length) return;
    var s = this.state;
    $tb.html('' +
      '<div class="st-count">Showing <strong>' + (total ? ((s.page - 1) * s.perPage + 1) : 0) + '–' + Math.min(s.page * s.perPage, total) + '</strong> of <strong>' + total + '</strong> products</div>' +
      '<div class="st-controls">' +
        '<div class="view-toggles d-none d-sm-flex">' +
          '<button data-view="grid" class="' + (s.view === 'grid' ? 'active' : '') + '" title="Grid view"><i class="fa-solid fa-grip"></i></button>' +
          '<button data-view="list" class="' + (s.view === 'list' ? 'active' : '') + '" title="List view"><i class="fa-solid fa-list"></i></button>' +
        '</div>' +
        '<select class="form-select form-select-sm f-sort" aria-label="Sort by">' +
          '<option value="featured"' + (s.sort === 'featured' ? ' selected' : '') + '>Featured</option>' +
          '<option value="newest"' + (s.sort === 'newest' ? ' selected' : '') + '>Newest arrivals</option>' +
          '<option value="price-asc"' + (s.sort === 'price-asc' ? ' selected' : '') + '>Price: low to high</option>' +
          '<option value="price-desc"' + (s.sort === 'price-desc' ? ' selected' : '') + '>Price: high to low</option>' +
          '<option value="rating"' + (s.sort === 'rating' ? ' selected' : '') + '>Top rated</option>' +
          '<option value="discount"' + (s.sort === 'discount' ? ' selected' : '') + '>Biggest discount</option>' +
          '<option value="name"' + (s.sort === 'name' ? ' selected' : '') + '>Name: A to Z</option>' +
        '</select>' +
        '<select class="form-select form-select-sm f-perpage d-none d-md-block" aria-label="Products per page">' +
          [12, 16, 24, 48].map(function (n) { return '<option value="' + n + '"' + (s.perPage === n ? ' selected' : '') + '>' + n + ' per page</option>'; }).join('') +
        '</select>' +
      '</div>');
  };

  Listing.prototype.renderChips = function () {
    var $ch = $('#active-filters');
    if (!$ch.length) return;
    var s = this.state, chips = [];
    var self = this;
    s.categories.forEach(function (c) { var cat = DB.categoryByKey(c); chips.push({ type: 'categories', val: c, label: 'Category: ' + (cat ? cat.name : c) }); });
    s.brands.forEach(function (b) { chips.push({ type: 'brands', val: b, label: 'Brand: ' + b }); });
    s.colors.forEach(function (c) { chips.push({ type: 'colors', val: c, label: 'Color: ' + c }); });
    s.sizes.forEach(function (z) { chips.push({ type: 'sizes', val: z, label: 'Size: ' + z }); });
    s.availability.forEach(function (a) { chips.push({ type: 'availability', val: a, label: 'Availability: ' + a }); });
    if (s.rating) chips.push({ type: 'rating', val: s.rating, label: s.rating + '★ & up' });
    if (s.minPrice > this.priceBounds.min || s.maxPrice < this.priceBounds.max) chips.push({ type: 'price', val: '', label: 'Price: ' + Store.money(s.minPrice) + ' – ' + Store.money(s.maxPrice) });

    if (!chips.length) { $ch.html(''); return; }
    $ch.html(chips.map(function (c) {
      return '<span class="filter-chip">' + Store.esc(c.label) + '<button data-chip-type="' + c.type + '" data-chip-val="' + Store.esc(c.val) + '" aria-label="Remove"><i class="fa-solid fa-xmark"></i></button></span>';
    }).join('') + '<button class="btn btn-sm btn-link text-danger px-2" id="clear-chips">Clear all</button>');
  };

  Listing.prototype.renderPagination = function (total) {
    var $pg = $('#shop-pagination');
    if (!$pg.length) return;
    var s = this.state;
    var pages = Math.ceil(total / s.perPage);
    if (pages <= 1) { $pg.html(''); return; }
    var html = '<nav aria-label="Pagination"><ul class="pagination-v">';
    html += '<li class="page-item ' + (s.page === 1 ? 'disabled' : '') + '"><a class="page-link" href="#" data-page="' + (s.page - 1) + '"><i class="fa-solid fa-chevron-left"></i></a></li>';
    var start = Math.max(1, s.page - 2), end = Math.min(pages, start + 4);
    start = Math.max(1, end - 4);
    if (start > 1) html += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>' + (start > 2 ? '<li class="page-item disabled"><span class="page-link">…</span></li>' : '');
    for (var i = start; i <= end; i++) html += '<li class="page-item ' + (i === s.page ? 'active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
    if (end < pages) html += (end < pages - 1 ? '<li class="page-item disabled"><span class="page-link">…</span></li>' : '') + '<li class="page-item"><a class="page-link" href="#" data-page="' + pages + '">' + pages + '</a></li>';
    html += '<li class="page-item ' + (s.page === pages ? 'disabled' : '') + '"><a class="page-link" href="#" data-page="' + (s.page + 1) + '"><i class="fa-solid fa-chevron-right"></i></a></li>';
    html += '</ul></nav>';
    $pg.html(html);
  };

  Listing.prototype.render = function () {
    var list = this.filtered();
    var s = this.state;
    var total = list.length;
    var pages = Math.max(1, Math.ceil(total / s.perPage));
    if (s.page > pages) s.page = pages;
    var start = (s.page - 1) * s.perPage;
    var pageItems = list.slice(start, start + s.perPage);

    this.renderSidebar();
    this.renderToolbar(total);
    this.renderChips();

    var $grid = $('#product-grid');
    if (!pageItems.length) {
      $grid.removeClass('products-grid products-list').html(Store.emptyProductsHtml(this.mode === 'search' ? 'No results for "' + Store.esc(this.query) + '"' : 'No products match your filters'));
    } else {
      var html = pageItems.map(function (p) { return Store.productCard(p); }).join('');
      $grid.removeClass('products-grid products-list').addClass(s.view === 'list' ? 'products-list' : 'products-grid').html(html);
    }
    this.renderPagination(total);
  };

  Listing.prototype.reset = function () { this.state.page = 1; this.render(); };

  Listing.prototype.bind = function () {
    var self = this, s = this.state;

    $('#shop-filters')
      .on('change', '.f-category', function () { self.toggleArr('categories', $(this).val()); })
      .on('change', '.f-brand', function () { self.toggleArr('brands', $(this).val()); })
      .on('change', '.f-color', function () { self.toggleArr('colors', $(this).val()); })
      .on('change', '.f-size', function () { self.toggleArr('sizes', $(this).val()); })
      .on('change', '.f-avail', function () { self.toggleArr('availability', $(this).val()); })
      .on('change', '.f-rating', function () { s.rating = Number($(this).val()); self.reset(); })
      .on('input change', '.f-price-max', function () { s.maxPrice = Number($(this).val()); self.reset(); })
      .on('change', '.f-price-min', function () { s.minPrice = Number($(this).val()) || 0; self.reset(); })
      .on('change', '.f-price-max-in', function () { s.maxPrice = Number($(this).val()) || self.priceBounds.max; self.reset(); })
      .on('click', '#clear-filters', function () { self.clearAll(); });

    $('#shop-toolbar')
      .on('change', '.f-sort', function () { s.sort = $(this).val(); self.reset(); })
      .on('change', '.f-perpage', function () { s.perPage = Number($(this).val()); self.reset(); })
      .on('click', '.view-toggles button', function () { s.view = $(this).data('view'); self.render(); });

    $('#active-filters')
      .on('click', '[data-chip-type]', function () {
        var type = $(this).data('chip-type'), val = $(this).data('chip-val');
        if (type === 'rating') s.rating = 0;
        else if (type === 'price') { s.minPrice = self.priceBounds.min; s.maxPrice = self.priceBounds.max; }
        else { s[type] = s[type].filter(function (x) { return String(x) !== String(val); }); }
        self.reset();
      })
      .on('click', '#clear-chips', function () { self.clearAll(); });

    $('#shop-pagination').on('click', '.page-link[data-page]', function (e) {
      e.preventDefault();
      var pg = Number($(this).data('page'));
      if (pg >= 1) { s.page = pg; self.render(); $('html,body').animate({ scrollTop: $('#product-listing').offset().top - 90 }, 300); }
    });
  };

  Listing.prototype.toggleArr = function (key, val) {
    var arr = this.state[key], i = arr.indexOf(val);
    if (i === -1) arr.push(val); else arr.splice(i, 1);
    this.reset();
  };

  Listing.prototype.clearAll = function () {
    var pb = this.priceBounds;
    this.state.categories = this.mode === 'category' && this.category ? [this.category] : [];
    this.state.brands = []; this.state.colors = []; this.state.sizes = []; this.state.availability = [];
    this.state.minPrice = pb.min; this.state.maxPrice = pb.max; this.state.rating = 0;
    this.reset();
  };

  /* ==================================================================
     2. DETAILS ENGINE
  ================================================================== */
  function galleryHtml(p) {
    var imgs = p.images && p.images.length ? p.images : [p.image];
    var thumbs = imgs.map(function (src, i) {
      return '<div class="thumb' + (i === 0 ? ' active' : '') + '" data-src="' + src + '"><img src="' + src + '" alt="' + Store.esc(p.name) + ' view ' + (i + 1) + '"></div>';
    }).join('');
    return '' +
      '<div class="pd-main-image" id="pd-zoom"><img src="' + imgs[0] + '" alt="' + Store.esc(p.name) + '" id="pd-main-img"></div>' +
      '<div class="pd-thumbs">' + thumbs + '</div>';
  }

  function infoHtml(p) {
    var colors = '';
    if (p.colors && p.colors.length) {
      colors = '<div class="pd-option"><div class="opt-label">Color: <span class="selected-color">' + Store.esc(p.colors[0].name) + '</span></div><div class="color-choices">' +
        p.colors.map(function (c, i) { return '<label><input type="radio" name="pd-color" value="' + Store.esc(c.name) + '"' + (i === 0 ? ' checked' : '') + '><span class="cc" style="background:' + c.hex + '" title="' + Store.esc(c.name) + '"></span></label>'; }).join('') +
        '</div></div>';
    }
    var sizes = '';
    if (p.sizes && p.sizes.length) {
      sizes = '<div class="pd-option"><div class="opt-label">Size: <span class="selected-size">' + Store.esc(p.sizes[0]) + '</span> &nbsp; <a href="#" class="text-muted small" onclick="return false;">Size guide</a></div><div class="size-choices">' +
        p.sizes.map(function (z, i) { return '<label><input type="radio" name="pd-size" value="' + Store.esc(z) + '"' + (i === 0 ? ' checked' : '') + '><span class="sc">' + Store.esc(z) + '</span></label>'; }).join('') +
        '</div></div>';
    }
    var soldOut = p.stock === 'out';
    return '' +
      '<div class="pd-cat">' + Store.esc(p.categoryName) + '</div>' +
      '<h1>' + Store.esc(p.name) + '</h1>' +
      '<div class="pd-meta">' +
        '<span class="rating-line">' + Store.stars(p.rating) + '<span class="count">' + p.rating.toFixed(1) + ' (' + p.reviews + ' reviews)</span></span>' +
        '<span class="m-item"><i class="fa-solid fa-barcode"></i> SKU: <strong>' + Store.esc(p.sku) + '</strong></span>' +
        '<span class="m-item"><i class="fa-solid fa-tag"></i> Brand: <a href="shop.html">' + Store.esc(p.brand) + '</a></span>' +
      '</div>' +
      '<div class="pd-price">' +
        '<span class="now">' + Store.money(p.price) + '</span>' +
        (p.oldPrice > p.price ? '<span class="old">' + Store.money(p.oldPrice) + '</span><span class="save">Save ' + p.discount + '%</span>' : '') +
      '</div>' +
      '<div class="mb-3">' + Store.stockLabel(p) + '</div>' +
      '<p class="pd-short">' + Store.esc(p.short) + '</p>' +
      colors + sizes +
      '<div class="pd-option"><div class="opt-label">Quantity</div>' +
        '<div class="qty-selector" id="pd-qty"><button type="button" class="qty-minus">−</button><input type="number" value="1" min="1" id="pd-qty-input" aria-label="Quantity"><button type="button" class="qty-plus">+</button></div>' +
      '</div>' +
      '<div class="pd-actions">' +
        '<button class="btn btn-primary-v btn-lg" id="pd-add-cart"' + (soldOut ? ' disabled' : '') + '><i class="fa-solid fa-cart-plus"></i> ' + (soldOut ? 'Sold out' : 'Add to Cart') + '</button>' +
        '<button class="btn btn-dark-v btn-lg" id="pd-buy-now"' + (soldOut ? ' disabled' : '') + '><i class="fa-solid fa-bolt"></i> Buy Now</button>' +
      '</div>' +
      '<div class="pd-extras">' +
        '<button id="pd-wishlist" class="' + (Store.isWishlisted(p.id) ? 'active' : '') + '"><i class="' + (Store.isWishlisted(p.id) ? 'fa-solid' : 'fa-regular') + ' fa-heart"></i> Add to Wishlist</button>' +
        '<button id="pd-compare"><i class="fa-solid fa-scale-balanced"></i> Add to Compare</button>' +
      '</div>' +
      '<div class="pd-guarantee">' +
        '<div class="g-item"><i class="fa-solid fa-truck-fast"></i> Free shipping on orders over $75</div>' +
        '<div class="g-item"><i class="fa-solid fa-rotate-left"></i> 30-day hassle-free returns</div>' +
        '<div class="g-item"><i class="fa-solid fa-shield-halved"></i> 2-year warranty & secure checkout</div>' +
      '</div>';
  }

  function specRows(p) {
    return Object.keys(p.specs).map(function (k) {
      return '<tr><th>' + Store.esc(k) + '</th><td>' + Store.esc(p.specs[k]) + '</td></tr>';
    }).join('');
  }

  function reviewsHtml(p) {
    var dist = [
      { star: 5, pct: 78 }, { star: 4, pct: 15 }, { star: 3, pct: 4 }, { star: 2, pct: 2 }, { star: 1, pct: 1 }
    ];
    var names = ['Emily Carter', 'James Osei', 'Maria Lopez', 'Liam Novak'];
    var texts = [
      'Exceeded my expectations — great quality and fast delivery. Would definitely buy again!',
      'Exactly as described. Very happy with this purchase and the packaging was excellent.',
      'Good value for the price. Does what it says and arrived earlier than expected.',
      'Solid product overall. Shipping was quick and customer service was responsive.'
    ];
    var bars = dist.map(function (d) {
      return '<div class="rs-bar-row"><span class="lbl">' + d.star + ' <i class="fa-solid fa-star" style="color:#f7b32b"></i></span>' +
        '<div class="rs-bar-track"><div class="rs-bar-fill" style="width:' + d.pct + '%"></div></div><span class="num">' + Math.round(p.reviews * d.pct / 100) + '</span></div>';
    }).join('');
    var items = '';
    for (var i = 0; i < 4; i++) {
      items += '<div class="review-item">' +
        '<div class="ri-head"><img src="' + DB.IMG + 'users/user-' + (i + 1) + '.svg" alt="' + names[i] + '">' +
        '<div><h6>' + names[i] + '</h6><span class="date">' + Store.formatDate('2026-0' + (8 - i) + '-1' + i) + '</span></div>' +
        '<span class="stars">' + Store.stars(5 - (i === 3 ? 1 : 0)) + '</span></div>' +
        '<p>' + texts[i] + '</p>' +
        '<span class="ri-verify"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>' +
        '</div>';
    }
    return '<div class="review-summary">' +
        '<div class="rs-score"><div class="big">' + p.rating.toFixed(1) + '</div>' + Store.stars(p.rating) + '<div class="text-muted small mt-1">' + p.reviews + ' global ratings</div></div>' +
        '<div class="rs-bars">' + bars + '</div>' +
      '</div>' +
      '<div id="reviews-list">' + items + '</div>' +
      '<div class="card border-0 bg-light p-4 mt-4" id="review-form-wrap">' +
        '<h5 class="mb-3">Write a review</h5>' +
        '<form id="review-form">' +
          '<div class="mb-3"><label class="form-label-v">Your rating</label><div class="star-input" id="star-input">' +
            [1, 2, 3, 4, 5].map(function (n) { return '<i class="fa-regular fa-star" data-val="' + n + '" role="button" aria-label="' + n + ' star"></i>'; }).join('') +
          '</div></div>' +
          '<div class="mb-3"><label class="form-label-v" for="rv-name">Name</label><input class="form-control" id="rv-name" required></div>' +
          '<div class="mb-3"><label class="form-label-v" for="rv-text">Your review</label><textarea class="form-control" id="rv-text" rows="4" required></textarea></div>' +
          '<button class="btn btn-primary-v" type="submit">Submit Review</button>' +
        '</form>' +
      '</div>';
  }

  function tabsHtml(p) {
    var features = (p.features || []).map(function (f) { return '<li>' + Store.esc(f) + '</li>'; }).join('');
    return '' +
      '<ul class="nav nav-tabs-v" role="tablist">' +
        '<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc" type="button">Description</button></li>' +
        '<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-specs" type="button">Specifications</button></li>' +
        '<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ship" type="button">Shipping & Returns</button></li>' +
        '<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button">Reviews (' + p.reviews + ')</button></li>' +
      '</ul>' +
      '<div class="tab-content tab-content-v">' +
        '<div class="tab-pane fade show active" id="tab-desc">' +
          '<p class="lead">' + Store.esc(p.short) + '</p>' +
          '<p>' + Store.esc(p.description) + '</p>' +
          '<h5 class="mt-4 mb-3">Key Features</h5><ul class="info-list">' +
            (p.features || []).map(function (f) { return '<li><i class="fa-solid fa-circle-check"></i> ' + Store.esc(f) + '</li>'; }).join('') +
          '</ul>' +
        '</div>' +
        '<div class="tab-pane fade" id="tab-specs"><table class="spec-table">' + specRows(p) + '</table></div>' +
        '<div class="tab-pane fade" id="tab-ship">' +
          '<div class="row g-4">' +
            '<div class="col-md-6"><h6><i class="fa-solid fa-truck-fast text-primary-v"></i> Shipping Information</h6>' +
              '<ul class="info-list"><li><i class="fa-solid fa-check"></i> Free standard shipping on orders over $75.</li>' +
              '<li><i class="fa-solid fa-check"></i> Standard delivery: 3–5 business days.</li>' +
              '<li><i class="fa-solid fa-check"></i> Express delivery: 1–2 business days ($19.99).</li>' +
              '<li><i class="fa-solid fa-check"></i> Orders placed before 2 PM ship the same day.</li></ul></div>' +
            '<div class="col-md-6"><h6><i class="fa-solid fa-rotate-left text-primary-v"></i> Return Information</h6>' +
              '<ul class="info-list"><li><i class="fa-solid fa-check"></i> 30-day hassle-free returns.</li>' +
              '<li><i class="fa-solid fa-check"></i> Items must be unused and in original packaging.</li>' +
              '<li><i class="fa-solid fa-check"></i> Refunds processed within 5–7 business days.</li>' +
              '<li><i class="fa-solid fa-check"></i> Free return shipping label included.</li></ul></div>' +
          '</div>' +
        '</div>' +
        '<div class="tab-pane fade" id="tab-reviews">' + reviewsHtml(p) + '</div>' +
      '</div>';
  }

  function renderDetails() {
    var $root = $('#product-details');
    if (!$root.length) return;
    // Static HTML already present - just bind events
    var id = Store.urlParam('id') || 1;
    var p = DB.productById(id) || DB.products[0];
    document.title = p.name + ' — Vendora';
    bindDetails(p);
  }

  function renderRecentlyViewed(excludeId) {
    // Disabled - localStorage is disabled
    var $rv = $('#recently-viewed');
    if (!$rv.length) return;
    $rv.closest('.section, .recently-viewed-section').addClass('d-none');
  }

  function bindDetails(p) {
    var $root = $('#product-details');

    // Thumbnail switch
    $root.on('click', '.pd-thumbs .thumb', function () {
      $('.pd-thumbs .thumb').removeClass('active');
      $(this).addClass('active');
      $('#pd-main-img').attr('src', $(this).data('src'));
    });

    // Zoom
    var $zoom = $('#pd-zoom');
    $zoom.on('mousemove', function (e) {
      var rect = this.getBoundingClientRect();
      var x = ((e.clientX - rect.left) / rect.width) * 100;
      var y = ((e.clientY - rect.top) / rect.height) * 100;
      $(this).addClass('zooming');
      $('#pd-main-img').css('transform-origin', x + '% ' + y + '%');
    }).on('mouseleave', function () {
      $(this).removeClass('zooming');
      $('#pd-main-img').css('transform-origin', 'center center');
    });

    // Color / size selected label
    $root.on('change', 'input[name="pd-color"]', function () { $('.selected-color').text($(this).val()); });
    $root.on('change', 'input[name="pd-size"]', function () { $('.selected-size').text($(this).val()); });

    // Qty
    $root.on('click', '#pd-qty .qty-minus', function () {
      var $i = $('#pd-qty-input');
      var min = parseInt($i.attr('min') || 1, 10);
      $i.val(Math.max(min, parseInt($i.val() || min, 10) - 1));
    }).on('click', '#pd-qty .qty-plus', function () {
      var $i = $('#pd-qty-input');
      var max = parseInt($i.attr('max') || Infinity, 10);
      var min = parseInt($i.attr('min') || 1, 10);
      $i.val(Math.min(max, Math.max(min, parseInt($i.val() || min, 10) + 1)));
    }).on('input change', '#pd-qty-input', function () {
      var $i = $(this);
      var min = parseInt($i.attr('min') || 1, 10);
      var max = parseInt($i.attr('max') || Infinity, 10);
      var val = parseInt($i.val(), 10);
      if (isNaN(val) || val < min) { $i.val(min); }
      else if (val > max) { $i.val(max); }
    });

    // Add to cart — handled via AJAX in footer.blade.php
    // Buy now
    $root.on('click', '#pd-buy-now', function () {
      var qty = parseInt($('#pd-qty-input').val() || 1, 10);
      var color = $('input[name="pd-color"]:checked').val() || '';
      var size = $('input[name="pd-size"]:checked').val() || '';
      if (Store.addToCart(p.id, qty, { color: color, size: size })) window.location.href = 'checkout.html';
    });
    // Wishlist handled by footer.blade.php delegated handler (server-side persistence).
    $root.on('click', '#pd-compare', function () {
      var on = Store.toggleCompare(p.id);
      $(this).toggleClass('active', on);
      if (on) $(this).html('<i class="fa-solid fa-scale-balanced"></i> Added to Compare');
      else $(this).html('<i class="fa-solid fa-scale-balanced"></i> Add to Compare');
    });

    // Star rating input for review
    $root.on('mouseover', '#star-input i', function () {
      var v = $(this).data('val');
      $('#star-input i').each(function () { $(this).attr('class', ($(this).data('val') <= v ? 'fa-solid' : 'fa-regular') + ' fa-star'); });
    }).on('click', '#star-input i', function () {
      $('#star-input').data('value', $(this).data('val'));
    });
    $root.on('submit', '#review-form', function (e) {
      e.preventDefault();
      var rating = $('#star-input').data('value') || 5;
      var name = $('#rv-name').val().trim() || 'Anonymous';
      var text = $('#rv-text').val().trim();
      if (!text) { Store.toast('Please write your review.', 'error'); return; }
      var html = '<div class="review-item fade-in-up"><div class="ri-head"><img src="' + DB.IMG + 'users/user-12.svg" alt="' + Store.esc(name) + '">' +
        '<div><h6>' + Store.esc(name) + '</h6><span class="date">Just now</span></div>' + Store.stars(rating) + '</div><p>' + Store.esc(text) + '</p></div>';
      $('#reviews-list').prepend(html);
      this.reset();
      $('#star-input i').attr('class', 'fa-regular fa-star');
      Store.toast('Thanks! Your review has been submitted.', 'success');
    });
  }

  /* ==================================================================
     INIT
  ================================================================== */
  $(function () {
    var $listing = $('#product-listing');
    if ($listing.length) {
      var engine = new Listing($listing);
      engine.render();
      engine.bind();
      window.__listing = engine;

      // Mobile filter toggle
      $(document).on('click', '#mobile-filter-toggle', function () {
        $('html,body').animate({ scrollTop: $('#shop-filters').offset().top - 90 }, 350);
      });
      // Category page header info
      if (engine.mode === 'category' && engine.category) {
        var c = DB.categoryByKey(engine.category);
        if (c) {
          $('#cat-hero-title').text(c.name);
          $('#cat-hero-desc').text(c.description);
          $('#cat-hero-img').attr('src', c.image);
          $('.page-title-bar h1, #page-title-text').text(c.name);
        }
      }
      if (engine.mode === 'search') {
        var q = engine.query;
        $('#search-query-label').text(q ? '"' + q + '"' : 'all products');
        $('#search-input').val(q);
        $('#results-count').text(engine.base.length);
      }
    }
    if ($('#product-details').length) renderDetails();
  });

})(window, jQuery);
