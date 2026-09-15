/*!
 * custom.js — NovaCart Admin shared runtime.
 *
 * Frontend only. There is no backend: every mutation below changes the DOM
 * (and `window.DB` where noted) so the interface can be demoed. When this is
 * ported to Laravel, each marked "DEMO" block becomes an AJAX call and the
 * table re-renders from the server response.
 *
 * Exposes three globals:
 *   window.AdminUI    — theme, sidebar, toasts, confirm dialog, formatters, search
 *   window.AdminTable — declarative sort / filter / paginate / bulk-select engine
 *   window.AdminPages — per-page module registry, keyed by <body data-page="...">
 *
 * ---------------------------------------------------------------------------
 * HTML contract (used by every page fragment in build/pages/*.html)
 * ---------------------------------------------------------------------------
 * Theme        [data-theme-toggle]
 * Sidebar      [data-sidebar-toggle]  .sidebar-backdrop[data-sidebar-backdrop]
 * Global search form[data-global-search] > [data-search-input] + [data-search-results]
 * Confirm      any element with [data-confirm]  →  #confirmModal
 * Toast        #toastStack
 * Table        <table data-table="KEY"> with:
 *                th.sortable            → clickable sort
 *                td[data-label="…"]     → mobile card label
 *                tr[data-<attr>="…"]    → filterable facet
 *                .table-check inputs    → bulk selection
 *              controls live anywhere on the page as [data-table-controls="KEY"]:
 *                input[data-table-search]
 *                select[data-table-filter="<attr>"]   ("all" = no filter)
 *                select[data-table-per-page]
 *                button[data-export]
 *              footer chrome: [data-table-info] [data-table-pagination] [data-table-bulk]
 * Row actions  [data-row-delete] [data-row-action]
 * Forms        <form data-validate> uses Bootstrap .is-valid/.is-invalid
 * Misc         [data-print] [data-form-cancel] [data-mark-all-read]
 */
(function (window, document, $) {
  'use strict';

  /* =======================================================================
   * 1. Formatters
   * ===================================================================== */

  var money0 = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    maximumFractionDigits: 0,
  });
  var money2 = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
  var intFmt = new Intl.NumberFormat('en-US');
  var dateFmt = new Intl.DateTimeFormat('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
  var dateTimeFmt = new Intl.DateTimeFormat('en-US', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });

  var Fmt = {
    esc: function (value) {
      return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    },

    /** $1,234 for large figures, $1,234.56 where cents matter. */
    money: function (value, cents) {
      var n = Number(value) || 0;
      return cents || Math.abs(n) < 1000 ? money2.format(n) : money0.format(n);
    },

    int: function (value) {
      return intFmt.format(Math.round(Number(value) || 0));
    },

    compact: function (value) {
      var n = Number(value) || 0;
      if (Math.abs(n) >= 1e6) return (n / 1e6).toFixed(n % 1e6 === 0 ? 0 : 1) + 'M';
      if (Math.abs(n) >= 1e3) return (n / 1e3).toFixed(n % 1e3 === 0 ? 0 : 1) + 'K';
      return intFmt.format(n);
    },

    pct: function (value, digits) {
      return (Number(value) || 0).toFixed(digits == null ? 1 : digits) + '%';
    },

    /** Signed delta with a direction icon, for KPI comparisons. */
    delta: function (value) {
      var n = Number(value) || 0;
      var up = n >= 0;
      return (
        '<span class="stat-compare ' +
        (up ? 'text-success' : 'text-danger') +
        '"><i class="fa-solid fa-arrow-trend-' +
        (up ? 'up' : 'down') +
        '"></i> ' +
        Math.abs(n).toFixed(1) +
        '%</span>'
      );
    },

    date: function (value) {
      var d = new Date(value);
      return isNaN(d) ? '—' : dateFmt.format(d);
    },

    dateTime: function (value) {
      var d = new Date(value);
      return isNaN(d) ? '—' : dateTimeFmt.format(d);
    },

    /** "3 hours ago" — used in feeds, timelines and notification lists. */
    ago: function (value) {
      var d = new Date(value);
      if (isNaN(d)) return '—';
      var secs = Math.round((Date.now() - d.getTime()) / 1000);
      if (secs < 45) return 'just now';
      var steps = [
        ['minute', 60],
        ['hour', 60],
        ['day', 24],
        ['week', 7],
        ['month', 4.345],
        ['year', 12],
      ];
      var unit = 'second';
      var size = secs;
      for (var i = 0; i < steps.length; i++) {
        if (size < steps[i][1]) break;
        unit = steps[i][0];
        size = size / steps[i][1];
      }
      size = Math.round(size);
      return size + ' ' + unit + (size === 1 ? '' : 's') + ' ago';
    },

    /** Truncate for table cells so long product names never blow out a column. */
    clip: function (value, max) {
      var s = String(value == null ? '' : value);
      var limit = max || 60;
      return s.length > limit ? s.slice(0, limit - 1).trimEnd() + '…' : s;
    },
  };

  /* =======================================================================
   * 2. Status vocabulary
   * One map, reused by every table/badge so wording and colour never drift.
   * ===================================================================== */

  /** Whitelist for tone strings interpolated into Bootstrap utility classes. */
  var TONES = { primary: 1, success: 1, warning: 1, danger: 1, info: 1 };

  var STATUS = {
    /* order status */
    pending: { label: 'Pending', tone: 'warning', icon: 'fa-clock' },
    processing: { label: 'Processing', tone: 'info', icon: 'fa-gear' },
    shipped: { label: 'Shipped', tone: 'primary', icon: 'fa-truck-fast' },
    delivered: { label: 'Delivered', tone: 'success', icon: 'fa-circle-check' },
    cancelled: { label: 'Cancelled', tone: 'danger', icon: 'fa-circle-xmark' },
    refunded: { label: 'Refunded', tone: 'neutral', icon: 'fa-rotate-left' },
    /* payment */
    paid: { label: 'Paid', tone: 'success', icon: 'fa-circle-check' },
    unpaid: { label: 'Unpaid', tone: 'danger', icon: 'fa-circle-exclamation' },
    'partially refunded': { label: 'Partially refunded', tone: 'warning', icon: 'fa-rotate-left' },
    'payment pending': { label: 'Payment pending', tone: 'warning', icon: 'fa-clock' },
    failed: { label: 'Failed', tone: 'danger', icon: 'fa-triangle-exclamation' },
    /* shipping */
    'not shipped': { label: 'Not shipped', tone: 'neutral', icon: 'fa-box' },
    preparing: { label: 'Preparing', tone: 'info', icon: 'fa-box' },
    unfulfilled: { label: 'Unfulfilled', tone: 'neutral', icon: 'fa-box' },
    'in transit': { label: 'In transit', tone: 'info', icon: 'fa-truck-fast' },
    out: { label: 'Out for delivery', tone: 'primary', icon: 'fa-truck' },
    returned: { label: 'Returned', tone: 'warning', icon: 'fa-undo' },
    /* catalogue */
    active: { label: 'Active', tone: 'success', icon: 'fa-circle-check' },
    inactive: { label: 'Inactive', tone: 'neutral', icon: 'fa-circle-pause' },
    blocked: { label: 'Blocked', tone: 'danger', icon: 'fa-ban' },
    draft: { label: 'Draft', tone: 'neutral', icon: 'fa-pen-ruler' },
    archived: { label: 'Archived', tone: 'neutral', icon: 'fa-box-archive' },
    /* stock */
    'in stock': { label: 'In stock', tone: 'success', icon: 'fa-boxes-stacked' },
    medium: { label: 'Medium stock', tone: 'info', icon: 'fa-boxes-stacked' },
    'low stock': { label: 'Low stock', tone: 'warning', icon: 'fa-triangle-exclamation' },
    'out of stock': { label: 'Out of stock', tone: 'danger', icon: 'fa-box-open' },
    /* generic people / records */
    enabled: { label: 'Enabled', tone: 'success', icon: 'fa-toggle-on' },
    disabled: { label: 'Disabled', tone: 'neutral', icon: 'fa-toggle-off' },
    suspended: { label: 'Suspended', tone: 'danger', icon: 'fa-ban' },
    verified: { label: 'Verified', tone: 'success', icon: 'fa-circle-check' },
    /* reviews */
    approved: { label: 'Approved', tone: 'success', icon: 'fa-check' },
    rejected: { label: 'Rejected', tone: 'danger', icon: 'fa-thumbs-down' },
    'on hold': { label: 'On hold', tone: 'warning', icon: 'fa-pause' },
    spam: { label: 'Spam', tone: 'danger', icon: 'fa-shield-halved' },
    /* coupons */
    scheduled: { label: 'Scheduled', tone: 'info', icon: 'fa-calendar' },
    expired: { label: 'Expired', tone: 'neutral', icon: 'fa-calendar-xmark' },
    /* staff */
    online: { label: 'Online', tone: 'success', icon: 'fa-circle' },
    away: { label: 'Away', tone: 'warning', icon: 'fa-circle' },
    offline: { label: 'Offline', tone: 'neutral', icon: 'fa-circle' },
    /* notifications / activity */
    read: { label: 'Read', tone: 'neutral', icon: 'fa-envelope-open' },
    unread: { label: 'Unread', tone: 'primary', icon: 'fa-envelope' },
  };

  /** `badge('paid')` → `<span class="status-badge status-success">…</span>` */
  function badge(status, opts) {
    // Data stores multi-word statuses hyphenated ("low-stock"); keys use spaces.
    var key = String(status == null ? '' : status)
      .toLowerCase()
      .replace(/[-_]+/g, ' ')
      .trim();
    var o = opts || {};
    var meta = STATUS[key] || {
      label: String(status || 'Unknown').replace(/^\w/, function (c) {
        return c.toUpperCase();
      }),
      tone: 'neutral',
      icon: 'fa-circle-question',
    };
    var label = o.label || meta.label;
    var icon = o.icon === false ? '' : '<i class="fa-solid ' + (o.icon || meta.icon) + '"></i> ';
    return (
      '<span class="status-badge status-' +
      (o.tone || meta.tone) +
      '" title="' +
      Fmt.esc(label) +
      '">' +
      icon +
      Fmt.esc(label) +
      '</span>'
    );
  }

  /** 4.6 → five star glyphs with the partial star approximated by a half. */
  function stars(rating, opts) {
    var o = opts || {};
    var value = Number(rating) || 0;
    var html = '<span class="rating' + (o.size === 'lg' ? ' rating-lg' : '') + '" role="img" aria-label="' + value.toFixed(1) + ' out of 5">';
    for (var i = 1; i <= 5; i++) {
      if (value >= i) html += '<i class="fa-solid fa-star"></i>';
      else if (value >= i - 0.5) html += '<i class="fa-solid fa-star-half-stroke"></i>';
      else html += '<i class="fa-regular fa-star empty"></i>';
    }
    html += '</span>';
    if (o.value !== false) html += '<span class="rating-value">' + value.toFixed(1) + '</span>';
    return html;
  }

  /* =======================================================================
   * 3. Toasts
   * ===================================================================== */

  var TOAST_ICONS = {
    success: 'fa-circle-check',
    danger: 'fa-circle-xmark',
    warning: 'fa-triangle-exclamation',
    info: 'fa-circle-info',
  };

  function toast(message, options) {
    var o = options || {};
    var type = TOAST_ICONS[o.type] ? o.type : 'info';
    var stack = document.getElementById('toastStack');
    if (!stack) return null;

    var el = document.createElement('div');
    el.className = 'toast toast-' + type;
    el.setAttribute('role', 'status');
    el.innerHTML =
      '<div class="toast-header">' +
      '<i class="fa-solid ' +
      TOAST_ICONS[type] +
      ' toast-icon"></i>' +
      '<strong class="toast-title">' +
      Fmt.esc(o.title || type.charAt(0).toUpperCase() + type.slice(1)) +
      '</strong>' +
      '<button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
      '</div>' +
      '<div class="toast-body"><span class="toast-text">' +
      Fmt.esc(message) +
      '</span></div>';

    stack.appendChild(el);
    var instance = new bootstrap.Toast(el, { delay: o.delay || 3800 });
    instance.show();
    el.addEventListener('hidden.bs.toast', function () {
      instance.dispose();
      el.remove();
    });
    return instance;
  }

  /* =======================================================================
   * 4. Confirm dialog
   * One Bootstrap modal is reused by every destructive action on every page.
   * ===================================================================== */

  var confirmState = { resolve: null, instance: null };

  function confirmDialog(options) {
    var o = options || {};
    var el = document.getElementById('confirmModal');
    if (!el) return Promise.resolve(true);

    if (!confirmState.instance) confirmState.instance = new bootstrap.Modal(el);

    var iconWrap = el.querySelector('[data-confirm-icon]');
    var accept = el.querySelector('[data-confirm-accept]');
    var tone = o.danger === false ? 'primary' : 'danger';

    if (iconWrap) {
      iconWrap.className = 'confirm-icon ' + (tone === 'danger' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary');
      iconWrap.innerHTML = '<i class="fa-solid ' + (o.icon || 'fa-triangle-exclamation') + '"></i>';
    }
    el.querySelector('#confirmModalLabel').textContent = o.title || 'Are you sure?';
    el.querySelector('[data-confirm-text]').textContent = o.text || 'This action cannot be undone.';

    accept.className = 'btn btn-' + tone;
    accept.innerHTML = '<i class="fa-solid ' + (o.icon || 'fa-triangle-exclamation') + ' me-1"></i>' + Fmt.esc(o.accept || 'Confirm');

    return new Promise(function (resolve) {
      confirmState.resolve = resolve;
      confirmState.instance.show();
      $(el).one('hidden.bs.modal', function () {
        resolve(false);
        confirmState.resolve = null;
      });
      $(accept).off('click.confirm').on('click.confirm', function () {
        resolve(true);
        confirmState.resolve = null;
        confirmState.instance.hide();
      });
    });
  }

  /* =======================================================================
   * 5. Theme
   * ===================================================================== */

  var THEME_KEY = 'nc-theme';

  function currentTheme() {
    return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
  }

  function applyTheme(theme, persist) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    if (persist !== false) {
      try {
        localStorage.setItem(THEME_KEY, theme);
      } catch (e) {
        /* private mode — theme simply won't persist */
      }
    }
    var meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', theme === 'dark' ? '#0b1017' : '#f3f5f9');
    // Charts and any theme-sensitive widget re-read tokens on this event.
    window.dispatchEvent(new CustomEvent('nc:themechange', { detail: { theme: theme } }));
  }

  function initTheme() {
    $(document).on('click', '[data-theme-toggle]', function () {
      var next = currentTheme() === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      toast(next === 'dark' ? 'Dark mode enabled' : 'Light mode enabled', {
        type: 'info',
        title: 'Appearance',
        delay: 1800,
      });
    });
  }

  /* =======================================================================
   * 6. Sidebar
   * Desktop  → collapse to the icon rail (persisted).
   * < lg     → drive Bootstrap's offcanvas instead.
   * ===================================================================== */

  var SIDEBAR_KEY = 'nc-sidebar-collapsed';
  var offcanvasInstance = null;

  function isDesktop() {
    return window.matchMedia('(min-width: 992px)').matches;
  }

  function sidebarOffcanvas() {
    var el = document.getElementById('sidebar');
    if (!el) return null;
    if (!offcanvasInstance) offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(el);
    return offcanvasInstance;
  }

  function setCollapsed(collapsed, persist) {
    document.documentElement.classList.toggle('sidebar-collapsed', !!collapsed);
    if (persist !== false) {
      try {
        localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
      } catch (e) {
        /* ignore */
      }
    }
    // Chart canvases must resize when the content column changes width.
    window.dispatchEvent(new Event('nc:layoutchange'));
  }

  function closeMobileSidebar() {
    var oc = sidebarOffcanvas();
    if (oc) oc.hide();
    $('[data-sidebar-backdrop]').removeClass('is-visible');
  }

  function initSidebar() {
    var saved = null;
    try {
      saved = localStorage.getItem(SIDEBAR_KEY);
    } catch (e) {
      /* ignore */
    }
    setCollapsed(saved === '1' && isDesktop(), false);

    $(document).on('click', '[data-sidebar-toggle]', function (event) {
      event.preventDefault();
      if (isDesktop()) {
        setCollapsed(!document.documentElement.classList.contains('sidebar-collapsed'));
      } else {
        var oc = sidebarOffcanvas();
        if (oc) oc.toggle();
      }
    });

    var sidebarEl = document.getElementById('sidebar');
    if (sidebarEl) {
      // Mirror Bootstrap's offcanvas state onto the custom backdrop.
      sidebarEl.addEventListener('shown.bs.offcanvas', function () {
        $('[data-sidebar-backdrop]').addClass('is-visible');
      });
      sidebarEl.addEventListener('hidden.bs.offcanvas', closeMobileSidebar);
    }
    $(document).on('click', '[data-sidebar-backdrop]', closeMobileSidebar);

    // Navigating from the drawer should close it.
    $('#sidebarNav').on('click', 'a.nav-link', function () {
      if (!isDesktop()) closeMobileSidebar();
    });

    // Breakpoint crossing: never leave the desktop collapse applied on mobile.
    var mq = window.matchMedia('(min-width: 992px)');
    var onCross = function () {
      if (!mq.matches) {
        document.documentElement.classList.remove('sidebar-collapsed');
        closeMobileSidebar();
      } else if (saved === '1') {
        document.documentElement.classList.add('sidebar-collapsed');
      }
      window.dispatchEvent(new Event('nc:layoutchange'));
    };
    if (mq.addEventListener) mq.addEventListener('change', onCross);
    else if (mq.addListener) mq.addListener(onCross);
  }

  /* =======================================================================
   * 7. Global search (Ctrl+K)
   * Searches the in-memory DB — a Laravel port swaps this for a
   * debounced GET to /admin/search?q=… returning the same shape.
   * ===================================================================== */

  function searchIndex() {
    if (typeof window.DB === 'undefined') return [];
    var DB = window.DB;
    var rows = [];
    (DB.products || []).forEach(function (p) {
      rows.push({
        type: 'Product',
        icon: 'fa-box',
        title: p.name,
        subtitle: p.sku + ' · ' + p.brandName + ' · ' + Fmt.money(p.price, true),
        href: DB.page('edit-product', 'id=' + p.id),
        haystack: (p.name + ' ' + p.sku + ' ' + p.brandName + ' ' + p.categoryName).toLowerCase(),
      });
    });
    (DB.orders || []).forEach(function (o) {
      rows.push({
        type: 'Order',
        icon: 'fa-bag-shopping',
        title: o.number,
        subtitle: o.customerName + ' · ' + Fmt.money(o.total, true) + ' · ' + o.status,
        href: DB.page('order-details', 'id=' + o.id),
        haystack: (o.number + ' ' + o.customerName + ' ' + o.customerEmail + ' ' + o.trackingNumber).toLowerCase(),
      });
    });
    (DB.customers || []).forEach(function (c) {
      rows.push({
        type: 'Customer',
        icon: 'fa-user',
        title: c.name,
        subtitle: c.email + ' · ' + (c.orders || 0) + ' orders',
        href: DB.page('customer-details', 'id=' + c.id),
        haystack: (c.name + ' ' + c.email + ' ' + c.phone + ' ' + (c.city || '')).toLowerCase(),
      });
    });
    (DB.categories || []).forEach(function (c) {
      rows.push({
        type: 'Category',
        icon: 'fa-folder-tree',
        title: c.name,
        subtitle: (c.productCount || 0) + ' products',
        href: DB.page('categories'),
        haystack: c.name.toLowerCase(),
      });
    });
    (DB.brands || []).forEach(function (b) {
      rows.push({
        type: 'Brand',
        icon: 'fa-copyright',
        title: b.name,
        subtitle: b.tagline || '',
        href: DB.page('brands'),
        haystack: b.name.toLowerCase(),
      });
    });
    (DB.coupons || []).forEach(function (c) {
      rows.push({
        type: 'Coupon',
        icon: 'fa-ticket',
        title: c.code,
        subtitle: c.description || '',
        href: DB.page('coupons'),
        haystack: (c.code + ' ' + (c.description || '')).toLowerCase(),
      });
    });
    return rows;
  }

  var searchCache = null;

  function runSearch(term) {
    var q = String(term || '').trim().toLowerCase();
    if (q.length < 2) return [];
    if (!searchCache) searchCache = searchIndex();
    var scored = [];
    for (var i = 0; i < searchCache.length; i++) {
      var row = searchCache[i];
      var idx = row.haystack.indexOf(q);
      if (idx === -1) continue;
      // Prefix hits outrank mid-string hits, which outrank description hits.
      var score = idx === 0 ? 0 : idx < 12 ? 1 : 2;
      scored.push({ row: row, score: score });
    }
    scored.sort(function (a, b) {
      return a.score - b.score || a.row.title.localeCompare(b.row.title);
    });
    return scored.slice(0, 8).map(function (s) {
      return s.row;
    });
  }

  /** Wrap the matched substring in <mark> — both sides escaped first. */
  function highlight(text, term) {
    var safe = Fmt.esc(text);
    var needle = Fmt.esc(term);
    if (!needle) return safe;
    var at = safe.toLowerCase().indexOf(needle.toLowerCase());
    if (at === -1) return safe;
    return safe.slice(0, at) + '<mark>' + safe.slice(at, at + needle.length) + '</mark>' + safe.slice(at + needle.length);
  }

  function renderSearchResults(results, term) {
    var box = $('[data-search-results]');
    if (!box.length) return;
    var query = String(term || '').trim();

    if (query.length < 2) {
      box.empty().prop('hidden', true);
      return;
    }

    if (!results.length) {
      box
        .html(
          '<div class="search-empty"><i class="fa-regular fa-face-frown me-1"></i>' +
            'No matches for &ldquo;' +
            Fmt.esc(query) +
            '&rdquo;<br /><small class="text-body-tertiary">Try a product name, order number or email.</small></div>'
        )
        .prop('hidden', false);
      return;
    }

    // Group by record type, preserving the relevance order within each group.
    var groups = [];
    var byType = {};
    results.forEach(function (r) {
      if (!byType[r.type]) {
        byType[r.type] = [];
        groups.push(r.type);
      }
      byType[r.type].push(r);
    });

    var html = groups
      .map(function (type) {
        var rows = byType[type]
          .map(function (r) {
            var index = results.indexOf(r);
            return (
              '<a class="search-result" href="' +
              Fmt.esc(r.href) +
              '" data-index="' +
              index +
              '">' +
              '<span class="search-result-icon"><i class="fa-solid ' +
              r.icon +
              '"></i></span>' +
              '<span class="search-result-body">' +
              '<span class="search-result-title">' +
              highlight(r.title, query) +
              '</span>' +
              '<span class="search-result-sub">' +
              Fmt.esc(Fmt.clip(r.subtitle, 60)) +
              '</span>' +
              '</span>' +
              '<span class="search-result-type">' +
              Fmt.esc(type) +
              '</span>' +
              '</a>'
            );
          })
          .join('');
        return '<div class="search-group-label">' + Fmt.esc(type) + '</div>' + rows;
      })
      .join('');

    box.html(html).prop('hidden', false);
    box.find('.search-result').first().addClass('is-active');
  }

  function initGlobalSearch() {
    var form = $('[data-global-search]');
    if (!form.length) return;
    var input = form.find('[data-search-input]');
    var box = form.find('[data-search-results]');
    var debounce = null;

    input.on('input', function () {
      var term = input.val();
      window.clearTimeout(debounce);
      debounce = window.setTimeout(function () {
        renderSearchResults(runSearch(term), term);
      }, 110);
    });

    input.on('focus', function () {
      if (input.val().trim().length >= 2) renderSearchResults(runSearch(input.val()), input.val());
    });

    // Keyboard: arrows move, Enter opens, Escape closes.
    input.on('keydown', function (event) {
      var items = box.find('.search-result');
      if (!items.length) return;
      var active = items.filter('.is-active');
      var index = items.index(active);

      if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        event.preventDefault();
        var next = event.key === 'ArrowDown' ? index + 1 : index - 1;
        if (next < 0) next = items.length - 1;
        if (next >= items.length) next = 0;
        items.removeClass('is-active');
        items.eq(next).addClass('is-active');
      } else if (event.key === 'Enter') {
        event.preventDefault();
        var href = (active.length ? active : items.first()).attr('href');
        if (href) window.location.href = href;
      } else if (event.key === 'Escape') {
        box.prop('hidden', true);
        input.blur();
      }
    });

    $(document).on('click', function (event) {
      if (!$(event.target).closest('[data-global-search]').length) box.prop('hidden', true);
    });

    $(document).on('keydown', function (event) {
      var isK = event.key === 'k' || event.key === 'K';
      if (isK && (event.ctrlKey || event.metaKey)) {
        event.preventDefault();
        input.trigger('focus').trigger('select');
      }
      if (event.key === '/' && !$(event.target).is('input, textarea, select')) {
        event.preventDefault();
        input.trigger('focus');
      }
      if (event.key === 'Escape' && document.activeElement === input[0]) box.prop('hidden', true);
    });
  }

  /* =======================================================================
   * 8. Topbar dropdowns: notifications + messages
   * ===================================================================== */

  function initNotifications() {
    var DB = window.DB;
    if (!DB) return;

    var unreadNotes = (DB.notifications || []).filter(function (n) {
      return !n.read;
    }).length;
    var unreadMsgs = (DB.messages || []).filter(function (m) {
      return !m.read;
    }).length;

    var noteDot = $('[data-notify-list]').closest('.dropdown').find('.dot-indicator');
    var msgDot = $('[data-messages-list]').closest('.dropdown').find('.dot-indicator');
    if (noteDot.length) noteDot.toggleClass('d-none', unreadNotes === 0);
    if (msgDot.length) msgDot.toggleClass('d-none', unreadMsgs === 0);
    $('[data-notify-count]').text(unreadNotes);
    $('[data-messages-count]').text(unreadMsgs);

    var list = $('[data-notify-list]');
    if (list.length && DB.notifications) {
      list.html(
        DB.notifications
          .slice(0, 6)
          .map(function (n) {
            var tone = TONES[n.color] ? n.color : 'primary';
            return (
              '<a class="notify-item' +
              (n.read ? '' : ' unread') +
              '" href="' +
              Fmt.esc(DB.link(n.link)) +
              '">' +
              '<span class="notify-icon bg-' +
              tone +
              '-subtle text-' +
              tone +
              '"><i class="fa-solid ' +
              Fmt.esc(n.icon || 'fa-bell') +
              '"></i></span>' +
              '<span class="notify-body">' +
              '<span class="notify-title">' +
              Fmt.esc(n.title) +
              '</span>' +
              '<span class="notify-text">' +
              Fmt.esc(n.text) +
              '</span>' +
              '<span class="notify-time">' +
              Fmt.ago(n.at) +
              '</span>' +
              '</span></a>'
            );
          })
          .join('')
      );
    }

    var messages = $('[data-messages-list]');
    if (messages.length && DB.messages) {
      messages.html(
        DB.messages
          .slice(0, 5)
          .map(function (m) {
            return (
              '<a class="notify-item' +
              (m.read ? '' : ' unread') +
              '" href="' +
              Fmt.esc(DB.link(m.link)) +
              '">' +
              '<img class="avatar avatar-sm" src="' +
              Fmt.esc(DB.url(m.avatar)) +
              '" alt="" />' +
              '<span class="notify-body">' +
              '<span class="notify-title">' +
              Fmt.esc(m.from) +
              ' <span class="text-body-tertiary fw-450">· ' +
              Fmt.esc(m.subject) +
              '</span></span>' +
              '<span class="notify-text">' +
              Fmt.esc(Fmt.clip(m.text, 58)) +
              '</span>' +
              '<span class="notify-time">' +
              Fmt.ago(m.at) +
              '</span>' +
              '</span></a>'
            );
          })
          .join('')
      );
    }

    // DEMO: marking as read is local only.
    $(document).on('click', '[data-mark-all-read]', function (event) {
      event.preventDefault();
      var messages = $(this).attr('data-mark-all-read') === 'messages';
      var records = (messages ? DB.messages : DB.notifications) || [];

      records.forEach(function (record) {
        record.read = true;
      });

      var list = $(messages ? '[data-messages-list]' : '[data-notify-list]');
      list.find('.notify-item, .notify-row').removeClass('unread');
      list.closest('.dropdown').find('.dot-indicator').addClass('d-none');
      $(messages ? '[data-messages-count]' : '[data-notify-count]').text('0');

      var label = messages ? 'messages' : 'notifications';
      toast('All ' + label + ' marked as read', {
        type: 'success',
        title: messages ? 'Messages' : 'Notifications',
      });
    });
  }

  /* =======================================================================
   * 9. AdminTable — declarative table engine
   *
   * Everything a data table needs (sort, search, faceted filters, pagination,
   * bulk selection, CSV export, mobile card labels, empty state) is driven by
   * the markup contract documented at the top of this file, so a new page
   * only writes HTML and calls AdminTable.mount().
   * ===================================================================== */

  var TABLE_DEFAULTS = {
    perPage: 10,
    perPageOptions: [10, 25, 50, 100],
    sort: null,
    dir: 'asc',
    searchColumns: null,
    emptyTitle: 'No records found',
    emptyText: 'Try adjusting your search or filters.',
    emptyIcon: 'fa-magnifying-glass',
    emptyHref: null,
    emptyAction: null,
  };

  function cellText(tr, index) {
    var td = tr.children[index];
    if (!td) return '';
    var explicit = td.getAttribute('data-sort-value');
    if (explicit != null) return explicit;
    return (td.textContent || '').trim();
  }

  function sortValue(raw) {
    if (raw === '' || raw == null) return null;
    // Currency / percent / thousands separators are all sortable as numbers.
    var numeric = String(raw).replace(/[$,\s%]/g, '');
    if (numeric !== '' && !isNaN(Number(numeric))) return Number(numeric);
    var asDate = Date.parse(raw);
    if (!isNaN(asDate) && /\d{4}|[A-Za-z]{3}/.test(raw) && /[/-]/.test(raw)) return asDate;
    return String(raw).toLowerCase();
  }

  function compare(a, b) {
    if (a === null && b === null) return 0;
    if (a === null) return 1; // blanks always sink to the bottom
    if (b === null) return -1;
    if (typeof a === 'number' && typeof b === 'number') return a - b;
    if (typeof a === 'number' || typeof b === 'number') return Number(a) - Number(b) || 0;
    return String(a).localeCompare(String(b), 'en', { numeric: true, sensitivity: 'base' });
  }

  function AdminTable(root, options) {
    this.$root = $(root);
    this.options = $.extend({}, TABLE_DEFAULTS, options || {});
    this.$table = this.$root.is('table') ? this.$root : this.$root.find('table[data-table]');
    this.key = this.$table.attr('data-table') || 'table';
    this.$controls = $('[data-table-controls="' + this.key + '"]');
    this.$body = this.$table.children('tbody').first();
    this.$allRows = this.$body.children('tr').not('[data-table-empty]').get();
    this.rows = this.$allRows.slice();
    this.state = {
      page: 1,
      perPage: this.options.perPage,
      sort: this.options.sort,
      dir: this.options.dir,
      search: '',
      filters: {},
    };
    this.$empty = null;
    this.init();
  }

  AdminTable.prototype.init = function () {
    var self = this;

    // Snapshot each row's original markup so filters can be re-applied cheaply.
    this.$allRows.forEach(function (tr) {
      tr.__ncRow = { text: (tr.textContent || '').toLowerCase(), selected: false };
    });

    this.buildSortHeaders();
    this.buildEmptyRow();
    this.bindControls();
    this.bindSelection();
    this.bindRowActions();

    // A URL facet (?status=pending) pre-applies the matching filter.
    var params = typeof window.DB !== 'undefined' && window.DB.query ? window.DB.query() : {};
    Object.keys(params).forEach(function (name) {
      var select = self.$controls.find('[data-table-filter="' + name + '"]');
      if (select.length && select.find('option[value="' + params[name] + '"]').length) {
        select.val(params[name]);
        self.state.filters[name] = params[name];
      }
    });

    this.apply();
  };

  AdminTable.prototype.buildSortHeaders = function () {
    var self = this;
    this.$table.find('thead th.sortable').each(function () {
      var th = $(this);
      var key = th.attr('data-sort') || String(th.index());
      th.attr('data-sort-key', key).attr('role', 'columnheader').attr('tabindex', '0');
      if (!th.attr('aria-sort')) th.attr('aria-sort', 'none');
    });

    this.$table.on('click', 'thead th.sortable', function () {
      self.sortBy($(this).attr('data-sort-key'), $(this).index());
    });
    this.$table.on('keydown', 'thead th.sortable', function (event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        self.sortBy($(this).attr('data-sort-key'), $(this).index());
      }
    });
  };

  AdminTable.prototype.sortBy = function (key, index) {
    if (this.state.sort === key) {
      this.state.dir = this.state.dir === 'asc' ? 'desc' : 'asc';
    } else {
      this.state.sort = key;
      // Text columns read better descending-last; numbers descending-first.
      this.state.dir = 'asc';
    }
    this.state.page = 1;
    this.apply();
    var th = this.$table.find('thead th[data-sort-key="' + key + '"]');
    th.attr('aria-sort', this.state.dir === 'asc' ? 'ascending' : 'descending');
    window.dispatchEvent(new CustomEvent('nc:tablesort', { detail: { table: this.key, key: key, dir: this.state.dir } }));
  };

  AdminTable.prototype.buildEmptyRow = function () {
    var cols = this.$table.find('thead tr').first().children().length || 1;
    var tr = document.createElement('tr');
    tr.setAttribute('data-table-empty', '');
    tr.hidden = true;
    tr.innerHTML =
      '<td colspan="' +
      cols +
      '">' +
      '<div class="state state-empty">' +
      '<span class="state-icon"><i class="fa-solid ' +
      this.options.emptyIcon +
      '"></i></span>' +
      '<h3 class="state-title">' +
      Fmt.esc(this.options.emptyTitle) +
      '</h3>' +
      '<p class="state-text">' +
      Fmt.esc(this.options.emptyText) +
      '</p>' +
      (this.options.emptyAction ? '<div class="state-actions">' + this.options.emptyAction + '</div>' : '') +
      '</div></td>';
    this.$empty = $(tr);
    this.$body.append(tr);
  };

  AdminTable.prototype.bindControls = function () {
    var self = this;
    var controls = this.$controls;
    if (!controls.length) return;

    var searchInput = controls.find('[data-table-search]');
    var debounce = null;
    searchInput.on('input', function () {
      var value = searchInput.val();
      window.clearTimeout(debounce);
      debounce = window.setTimeout(function () {
        self.state.search = value.trim().toLowerCase();
        self.state.page = 1;
        self.apply();
      }, 140);
    });

    controls.find('[data-table-filter]').each(function () {
      var select = $(this);
      var name = select.attr('data-table-filter');
      select.on('change', function () {
        var value = select.val();
        if (!value || value === 'all') delete self.state.filters[name];
        else self.state.filters[name] = value;
        self.state.page = 1;
        self.apply();
      });
    });

    var perPage = controls.find('[data-table-per-page]');
    if (perPage.length) {
      perPage.on('change', function () {
        self.state.perPage = parseInt(perPage.val(), 10) || TABLE_DEFAULTS.perPage;
        self.state.page = 1;
        self.apply();
      });
    }

    controls.find('[data-export]').on('click', function (event) {
      event.preventDefault();
      self.exportCsv($(this).attr('data-export'));
    });

    $(document).on('click', '[data-table-controls="' + self.key + '"] [data-clear-filters]', function (event) {
      event.preventDefault();
      self.reset();
    });
  };

  AdminTable.prototype.reset = function () {
    this.state = { page: 1, perPage: this.options.perPage, sort: this.options.sort, dir: this.options.dir, search: '', filters: {} };
    this.$controls.find('[data-table-search]').val('');
    this.$controls.find('[data-table-filter]').val('all');
    this.$table.find('thead th.sortable').removeAttr('data-sort-dir').attr('aria-sort', 'none');
    this.apply();
    toast('Filters cleared', { type: 'info', title: 'Table', delay: 1800 });
  };

  AdminTable.prototype.bindSelection = function () {
    var self = this;
    var table = this.$table;

    table.on('change', 'thead [data-check-all]', function () {
      var checked = this.checked;
      self.visibleRows().forEach(function (tr) {
        var box = tr.querySelector('[data-check-row]');
        if (box && !box.disabled) {
          box.checked = checked;
          $(tr).toggleClass('is-selected', checked);
          tr.__ncRow.selected = checked;
        }
      });
      self.updateBulkBar();
    });

    table.on('change', 'tbody [data-check-row]', function () {
      var tr = this.closest('tr');
      tr.__ncRow.selected = this.checked;
      $(tr).toggleClass('is-selected', this.checked);
      var boxes = self.visibleRows().map(function (r) {
        return r.querySelector('[data-check-row]');
      });
      var all = table.find('thead [data-check-all]');
      if (all.length) {
        all.prop('checked', boxes.length > 0 && boxes.every(function (b) {
          return b && b.checked;
        }));
        all.prop('indeterminate', boxes.some(function (b) {
          return b && b.checked;
        }) && !all.prop('checked'));
      }
      self.updateBulkBar();
    });
  };

  AdminTable.prototype.selectedRows = function () {
    return this.rows.filter(function (tr) {
      return tr.__ncRow && tr.__ncRow.selected;
    });
  };

  AdminTable.prototype.updateBulkBar = function () {
    var bar = $('[data-table-bulk="' + this.key + '"], [data-table-controls="' + this.key + '"] [data-table-bulk]');
    if (!bar.length) return;
    var count = this.selectedRows().length;
    bar.prop('hidden', !count);
    bar.find('[data-bulk-count]').text(count);
  };

  AdminTable.prototype.bindRowActions = function () {
    var self = this;

    this.$table.on('click', '[data-row-delete]', function (event) {
      event.preventDefault();
      event.stopPropagation();
      var tr = this.closest('tr');
      var label = $(this).attr('data-row-delete') || Fmt.clip(tr.getAttribute('data-name') || 'this record', 40);

      confirmDialog({
        title: 'Delete ' + label + '?',
        text: 'This removes the record from the demo dataset. It cannot be undone.',
        accept: 'Delete',
        icon: 'fa-trash-can',
      }).then(function (ok) {
        if (!ok) return;
        // DEMO: real port → DELETE /admin/{resource}/{id} then reload the table.
        tr.remove();
        self.rows = self.rows.filter(function (r) {
          return r !== tr;
        });
        self.$allRows = self.rows;
        self.apply();
        toast(label + ' deleted', { type: 'success', title: 'Removed' });
      });
    });

    this.$table.on('click', '[data-row-action]', function (event) {
      var action = $(this).attr('data-row-action');
      if (action === 'toggle-status' || action === 'archive' || action === 'restore') {
        event.preventDefault();
        self.cycleRowStatus(this.closest('tr'), action);
      }
    });

    // Bulk buttons declared in the toolbar.
    $(document).on('click', '[data-bulk-action]', function (event) {
      event.preventDefault();
      var action = $(this).attr('data-bulk-action');
      var selected = self.selectedRows();
      if (!selected.length) return;
      self.bulkAction(action, selected, $(this));
    });
  };

  /** DEMO: flips a row's status badge in place. */
  AdminTable.prototype.cycleRowStatus = function (tr, action) {
    var cell = tr.querySelector('[data-status-cell]');
    var current = tr.getAttribute('data-status');
    var next = action === 'restore' ? 'active' : current === 'active' ? 'archived' : 'active';
    if (cell) cell.innerHTML = badge(next);
    tr.setAttribute('data-status', next);
    toast('Status changed to ' + next, { type: 'success', title: 'Updated', delay: 2000 });
    this.apply();
  };

  AdminTable.prototype.bulkAction = function (action, rows, $button) {
    var self = this;
    var label = rows.length + ' record' + (rows.length === 1 ? '' : 's');

    var run = function () {
      rows.forEach(function (tr) {
        if (action === 'delete') {
          tr.remove();
        } else if (action === 'activate' || action === 'deactivate' || action === 'archive') {
          var next = action === 'activate' ? 'active' : action === 'deactivate' ? 'disabled' : 'archived';
          var cell = tr.querySelector('[data-status-cell]');
          if (cell) cell.innerHTML = badge(next);
          tr.setAttribute('data-status', next);
        }
      });
      if (action === 'delete') {
        self.rows = self.rows.filter(function (r) {
          return rows.indexOf(r) === -1;
        });
        self.$allRows = self.rows;
      }
      self.$table.find('thead [data-check-all]').prop('checked', false).prop('indeterminate', false);
      self.apply();
      toast(label + ' — ' + action + ' applied', { type: action === 'delete' ? 'danger' : 'success', title: 'Bulk update' });
    };

    if (action === 'delete') {
      confirmDialog({
        title: 'Delete ' + label + '?',
        text: 'This removes them from the demo dataset. It cannot be undone.',
        accept: 'Delete all',
        icon: 'fa-trash-can',
      }).then(function (ok) {
        if (ok) run();
      });
    } else {
      run();
    }
  };

  /* -- filtering / sorting / paging ------------------------------------- */

  AdminTable.prototype.matches = function (tr) {
    var state = this.state;

    if (state.search) {
      var columns = this.options.searchColumns;
      var hay = tr.__ncRow.text;
      if (columns && columns.length) {
        hay = columns
          .map(function (i) {
            return cellText(tr, i);
          })
          .join(' ')
          .toLowerCase();
      }
      // Every whitespace-separated token must appear (AND semantics).
      var tokens = state.search.split(/\s+/);
      for (var t = 0; t < tokens.length; t++) {
        if (hay.indexOf(tokens[t]) === -1) return false;
      }
    }

    var keys = Object.keys(state.filters);
    for (var i = 0; i < keys.length; i++) {
      var want = String(state.filters[keys[i]]).toLowerCase();
      var have = String(tr.getAttribute('data-' + keys[i]) || '').toLowerCase();
      if (want === 'all' || want === '') continue;
      if (want.charAt(0) === '!') {
        if (have === want.slice(1)) return false;
      } else if (have !== want) {
        return false;
      }
    }
    return true;
  };

  AdminTable.prototype.visibleRows = function () {
    return this.rows.filter(function (tr) {
      return !tr.hidden;
    });
  };

  AdminTable.prototype.apply = function () {
    var self = this;
    var state = this.state;

    var filtered = this.rows.filter(function (tr) {
      return self.matches(tr);
    });

    if (state.sort != null) {
      var th = this.$table.find('thead th[data-sort-key="' + state.sort + '"]');
      var index = th.length ? th.index() : parseInt(state.sort, 10);
      if (!isNaN(index)) {
        var factor = state.dir === 'desc' ? -1 : 1;
        filtered.sort(function (a, b) {
          return compare(sortValue(cellText(a, index)), sortValue(cellText(b, index))) * factor;
        });
      }
    }

    var total = filtered.length;
    var pages = Math.max(1, Math.ceil(total / state.perPage));
    if (state.page > pages) state.page = pages;
    var start = (state.page - 1) * state.perPage;
    var end = start + state.perPage;
    var shown = filtered.slice(start, end);

    var shownSet = new Set(shown);
    this.rows.forEach(function (tr) {
      tr.hidden = !shownSet.has(tr);
    });

    if (this.$empty) this.$empty.prop('hidden', total !== 0);

    this.renderInfo(total, start, shown.length);
    this.renderPagination(pages);
    this.updateBulkBar();

    window.dispatchEvent(
      new CustomEvent('nc:tablechange', {
        detail: { table: this.key, total: total, page: state.page, pages: pages },
      })
    );
  };

  AdminTable.prototype.renderInfo = function (total, start, count) {
    var info = $('[data-table-info="' + this.key + '"]');
    if (!info.length) return;
    if (!total) {
      info.html('No results');
      return;
    }
    info.html(
      'Showing <strong>' + (start + 1) + '–' + (start + count) + '</strong> of <strong>' + total + '</strong>'
    );
  };

  AdminTable.prototype.renderPagination = function (pages) {
    var self = this;
    var nav = $('[data-table-pagination="' + this.key + '"]');
    if (!nav.length) return;
    if (pages <= 1) {
      nav.empty();
      return;
    }

    var current = this.state.page;
    var window_ = [];
    window_.push(1);
    for (var p = current - 1; p <= current + 1; p++) {
      if (p > 1 && p < pages) window_.push(p);
    }
    if (pages > 1) window_.push(pages);
    window_ = window_.filter(function (v, i, arr) {
      return arr.indexOf(v) === i;
    });

    var item = function (page, label, disabled, active) {
      return (
        '<li class="page-item' +
        (disabled ? ' disabled' : '') +
        (active ? ' active' : '') +
        '"><button type="button" class="page-link" data-goto="' +
        page +
        '"' +
        (disabled ? ' tabindex="-1" aria-disabled="true"' : '') +
        (active ? ' aria-current="page"' : '') +
        '>' +
        label +
        '</button></li>'
      );
    };

    var html = '<ul class="pagination pagination-sm mb-0">';
    html += item(current - 1, '<i class="fa-solid fa-chevron-left"></i><span class="visually-hidden">Previous</span>', current === 1, false);
    var last = 0;
    window_.forEach(function (p) {
      if (last && p - last > 1) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
      html += item(p, String(p), false, p === current);
      last = p;
    });
    html += item(current + 1, '<i class="fa-solid fa-chevron-right"></i><span class="visually-hidden">Next</span>', current === pages, false);
    html += '</ul>';

    nav.html(html);
    nav.off('click.pager').on('click.pager', '[data-goto]', function () {
      var page = parseInt($(this).attr('data-goto'), 10);
      if (isNaN(page) || page < 1 || page > pages || page === self.state.page) return;
      self.state.page = page;
      self.apply();
      // Keep the table head in view after a long jump.
      var top = self.$table.offset().top - 90;
      window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    });
  };

  /* -- CSV export (frontend only, no server round trip) ----------------- */

  AdminTable.prototype.exportCsv = function (filename) {
    var self = this;
    var headCells = this.$table.find('thead tr').first().children();
    var skipIndexes = [];
    headCells.each(function (i) {
      if ($(this).is('.table-check, .table-actions')) skipIndexes.push(i);
    });

    var headers = [];
    headCells.each(function (i) {
      if (skipIndexes.indexOf(i) !== -1) return;
      headers.push((this.textContent || '').trim());
    });

    var filtered = this.rows.filter(function (tr) {
      return self.matches(tr);
    });

    var lines = [headers.map(csvCell).join(',')];
    filtered.forEach(function (tr) {
      var cells = [];
      $(tr)
        .children()
        .each(function (i) {
          if (skipIndexes.indexOf(i) !== -1) return;
          var explicit = this.getAttribute('data-export-value');
          cells.push(explicit != null ? explicit : (this.textContent || '').trim().replace(/\s+/g, ' '));
        });
      lines.push(cells.map(csvCell).join(','));
    });

    var blob = new Blob(['\ufeff' + lines.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = (filename || this.key) + '-' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    setTimeout(function () {
      URL.revokeObjectURL(url);
    }, 1000);

    toast(filtered.length + ' rows exported to CSV', { type: 'success', title: 'Export complete' });
  };

  function csvCell(value) {
    var s = String(value == null ? '' : value);
    return /[",\r\n]/.test(s) ? '"' + s.replace(/"/g, '""') + '"' : s;
  }

  /** Registry of mounted tables, keyed by `data-table`. */
  AdminTable.instances = {};

  /** Mount every table on the page; returns the instances keyed by table name. */
  AdminTable.mountAll = function () {
    var map = AdminTable.instances;
    $('table[data-table]').each(function () {
      var key = this.getAttribute('data-table');
      if (map[key]) return;
      var opts = {};
      var raw = this.getAttribute('data-table-options');
      if (raw) {
        try {
          opts = JSON.parse(raw);
        } catch (err) {
          if (window.console) console.warn('[AdminTable:' + key + '] bad data-table-options JSON', err);
        }
      }
      map[key] = new AdminTable(this, opts);
    });
    return map;
  };

  /* =======================================================================
   * 10. Forms
   * ===================================================================== */

  function initForms() {
    /* HTML cannot compare two inputs, so [data-match="#otherId"] does it here.
     * The confirmation control reports its own mismatch through custom
     * validity, which feeds the same :invalid path the rest of the form uses. */
    function syncMatchedFields(form) {
      $(form)
        .find('[data-match]')
        .each(function () {
          var other = $($(this).attr('data-match'))[0];
          if (!other) return;
          this.setCustomValidity(other.value === this.value ? '' : 'mismatch');
        });
    }

    $(document).on('input change', '[data-match]', function () {
      if (this.form) syncMatchedFields(this.form);
    });

    $(document).on('submit', 'form[data-validate]', function (event) {
      var form = this;
      syncMatchedFields(form);
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        var firstBad = $(form).find(':invalid').first();
        if (firstBad.length) {
          // Tabbed forms hide most of their fields; open the pane that holds the
          // offending control or the highlight lands somewhere the user cannot see.
          var pane = firstBad.closest('.tab-pane')[0];
          if (pane && pane.id && !pane.classList.contains('show')) {
            var trigger = form.querySelector('[data-bs-target="#' + pane.id + '"]');
            if (trigger) bootstrap.Tab.getOrCreateInstance(trigger).show();
          }
          firstBad.trigger('focus');
          var offset = firstBad.offset();
          if (offset) window.scrollTo({ top: Math.max(0, offset.top - 120), behavior: 'smooth' });
        }
        toast('Please fix the highlighted fields', { type: 'warning', title: 'Validation' });
      } else {
        // DEMO: there is no endpoint. Show what a submit would have sent.
        event.preventDefault();
        var formName = $(form).attr('data-form-name') || 'Record';
        toast(formName + ' saved successfully', { type: 'success', title: 'Saved' });

        var modalEl = form.closest('.modal');
        if (modalEl) {
          var modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
          form.reset();
        }

        var redirect = $(form).attr('data-redirect');
        if (redirect && window.DB) {
          setTimeout(function () {
            window.location.href = window.DB.page(redirect);
          }, 900);
        }
      }
      $(form).addClass('was-validated');
    });

    $(document).on('click', '[data-form-cancel]', function (event) {
      event.preventDefault();
      var target = $(this).attr('data-form-cancel');
      if (target && window.DB) window.location.href = window.DB.page(target);
      else window.history.length > 1 ? window.history.back() : window.close();
    });

    // Header buttons reach their form through form="<id>", so there is no
    // enclosing <form> to walk up to.
    $(document).on('click', '[data-save-draft]', function (event) {
      event.preventDefault();
      var form = $(this).attr('form') ? $('#' + $(this).attr('form')) : $(this).closest('form');
      if (!form.length) return;
      form.find('[name="status"]').val('draft');
      toast('Saved as a draft — not visible on the storefront', { type: 'info', title: 'Draft saved' });
    });

    // Live character counters: <textarea data-counter="300"> + [data-counter-for]
    $(document).on('input', '[data-counter]', function () {
      var max = parseInt(this.getAttribute('data-counter'), 10) || 0;
      var out = $('[data-counter-for="' + this.id + '"]');
      if (out.length) out.text(this.value.length + ' / ' + max);
    });

    // Slug auto-fill: <input name="name" data-slug-target="#slug">
    $(document).on('input', '[data-slug-target]', function () {
      var target = $(this.getAttribute('data-slug-target'));
      if (!target.length || target.data('touched')) return;
      target.val(slugify(this.value));
    });
    // Once an editor rewrites the slug by hand, stop syncing it.
    $(document).on('input', '[data-slug-field]', function () {
      $(this).data('touched', true);
    });

    // Price / discount maths kept in sync on the product form.
    $(document).on('input', '[data-price], [data-old-price], [data-cost]', function () {
      var form = this.closest('form');
      if (!form) return;
      var price = parseFloat($(form).find('[data-price]').val()) || 0;
      var old = parseFloat($(form).find('[data-old-price]').val()) || 0;
      var cost = parseFloat($(form).find('[data-cost]').val()) || 0;

      var discount = $(form).find('[data-discount-preview]');
      if (discount.length) {
        var off = old > price && old > 0 ? Math.round(((old - price) / old) * 100) : 0;
        discount.text(off > 0 ? '−' + off + '% off' : 'No discount');
        discount.toggleClass('text-success', off > 0).toggleClass('text-body-secondary', off === 0);
      }

      var margin = $(form).find('[data-margin-preview]');
      if (margin.length) {
        var pctValue = price > 0 ? Math.round(((price - cost) / price) * 100) : 0;
        margin.text(price > 0 ? pctValue + '% margin' : 'No margin');
        margin
          .toggleClass('text-success', price > 0 && pctValue >= 30)
          .toggleClass('text-warning', price > 0 && pctValue >= 15 && pctValue < 30)
          .toggleClass('text-danger', price > 0 && pctValue < 15)
          .toggleClass('text-body-secondary', price === 0);
      }
    });

    // Stock maths: available = stock − reserved.
    $(document).on('input', '[data-stock], [data-reserved]', function () {
      var form = this.closest('form');
      if (!form) return;
      var stock = parseInt($(form).find('[data-stock]').val(), 10) || 0;
      var reserved = parseInt($(form).find('[data-reserved]').val(), 10) || 0;
      var out = $(form).find('[data-available-preview]');
      if (out.length) out.text(Fmt.int(Math.max(0, stock - reserved)));
    });

    // Conditional field groups: <select data-show-when="NAME"> reveals the
    // [data-show-group="NAME"] blocks whose data-show-values allow-list contains
    // the current value. A hidden required control would make the form
    // impossible to submit, so `required` parks on data-was-required instead.
    function applyShowWhen(select) {
      var group = select.getAttribute('data-show-when');
      var value = select.value;
      $('[data-show-group="' + group + '"]').each(function () {
        var allowed = (this.getAttribute('data-show-values') || '').split(/[\s,]+/).filter(Boolean);
        var show = !allowed.length || allowed.indexOf(value) !== -1;
        this.classList.toggle('d-none', !show);
        $(this).find('[required], [data-was-required]').each(function () {
          if (show) {
            if (this.hasAttribute('data-was-required')) {
              this.setAttribute('required', '');
              this.removeAttribute('data-was-required');
            }
          } else if (this.hasAttribute('required')) {
            this.removeAttribute('required');
            this.setAttribute('data-was-required', '');
          }
        });
      });
    }

    $(document).on('change', '[data-show-when]', function () {
      applyShowWhen(this);
    });
    $('[data-show-when]').each(function () {
      applyShowWhen(this);
    });
  }

  function slugify(value) {
    return String(value || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '')
      .slice(0, 80);
  }

  /* =======================================================================
   * 11. Image picker / dropzone (product form)
   * ===================================================================== */

  function initDropzones() {
    $(document).on('click', '.dropzone', function (event) {
      if ($(event.target).closest('input, button, a').length) return;
      var input = $($(this).attr('data-dropzone-for') || '#' + $(this).attr('data-dropzone'));
      if (input.length) input.trigger('click');
    });

    $(document).on('change', '[data-image-input]', function () {
      var input = this;
      var preview = $($(this).attr('data-image-preview') || '[data-image-preview]');
      if (!preview.length || !input.files || !input.files.length) return;

      Array.prototype.slice.call(input.files).forEach(function (file, i) {
        if (!/^image\//.test(file.type)) return;
        var reader = new FileReader();
        reader.onload = function (e) {
          var tile = $(
            '<figure class="image-tile">' +
              '<img src="' +
              e.target.result +
              '" alt="' +
              Fmt.esc(file.name) +
              '" />' +
              '<figcaption>' +
              Fmt.esc(Fmt.clip(file.name, 22)) +
              '</figcaption>' +
              (i === 0 ? '<span class="image-tile-badge">Main</span>' : '') +
              '<button type="button" class="image-tile-remove" aria-label="Remove image"><i class="fa-solid fa-xmark"></i></button>' +
              '</figure>'
          );
          preview.find('.image-tile-add').before(tile);
        };
        reader.readAsDataURL(file);
      });
    });

    /* The gallery handler builds tiles; a single round avatar just needs its
     * src swapped, so it gets its own much smaller hook. */
    $(document).on('change', '[data-avatar-input]', function () {
      if (!this.files || !this.files.length) return;
      var file = this.files[0];
      if (!/^image\//.test(file.type)) return;
      var preview = $($(this).attr('data-avatar-preview') || '[data-avatar-preview]').first();
      if (!preview.length) return;
      
      // عرض الصورة مباشرة في المعاينة
      var reader = new FileReader();
      reader.onload = function (e) {
        preview.attr('src', e.target.result);
      };
      reader.readAsDataURL(file);

      // إرسال الصورة إلى السيرفر
      var formData = new FormData();
      formData.append('img', file);
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

      $.ajax({
        url: 'changeImage',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            console.log('Image uploaded successfully');
            // يمكنك إضافة رسالة نجاح للمستخدم هنا
            if (typeof toastr !== 'undefined') {
              toastr.success(response.message);
            }
          }
        },
        error: function(xhr) {
          console.error('Error uploading image:', xhr);
          // استرجاع الصورة القديمة في حالة الفشل
          if (typeof toastr !== 'undefined') {
            toastr.error('Failed to upload image');
          }
        }
      });
    });

    $(document).on('dragover dragenter', '.dropzone', function (event) {
      event.preventDefault();
      $(this).addClass('dragover');
    });
    $(document).on('dragleave dragend drop', '.dropzone', function (event) {
      event.preventDefault();
      $(this).removeClass('dragover');
    });
    $(document).on('drop', '.dropzone', function (event) {
      var original = event.originalEvent;
      if (!original || !original.dataTransfer) return;
      var selector = $(this).attr('data-dropzone-for') || '#' + $(this).attr('data-dropzone');
      var input = $(selector)[0];
      if (input) {
        input.files = original.dataTransfer.files;
        $(input).trigger('change');
      }
    });

    $(document).on('click', '.image-tile-remove', function (event) {
      event.preventDefault();
      event.stopPropagation();
      $(this).closest('.image-tile').remove();
    });

    $(document).on('click', '.image-tile', function () {
      $(this).addClass('is-main').siblings().removeClass('is-main');
    });
  }

  /* =======================================================================
   * 12. Misc global behaviours
   * ===================================================================== */

  function initMisc() {
    // Print buttons.
    $(document).on('click', '[data-print]', function (event) {
      event.preventDefault();
      window.print();
    });

    // Profile page: "sign out" a listed session. Demo only — fades the row
    // and drops it, there is no real session store to revoke.
    $(document).on('click', '[data-session-signout]', function () {
      var row = $(this).closest('.setting-row');
      var label = row.find('.setting-row-label').text().trim() || 'Session';
      row.css('transition', 'opacity .2s ease').css('opacity', 0);
      setTimeout(function () {
        row.slideUp(150, function () {
          row.remove();
        });
      }, 200);
      toast(label + ' signed out', { type: 'success', title: 'Session' });
    });

    // Page-header export buttons sit outside any table toolbar, so bindControls()
    // never sees them; resolve the table from the button's own filename instead.
    $(document).on('click', '[data-export]', function (event) {
      if ($(this).closest('[data-table-controls]').length) return;
      event.preventDefault();

      var name = $(this).attr('data-export');
      var table = AdminTable.instances[name];
      if (table) {
        table.exportCsv(name);
        return;
      }

      var keys = Object.keys(AdminTable.instances);
      if (keys.length === 1) {
        AdminTable.instances[keys[0]].exportCsv(name);
        return;
      }

      toast('No table on this page matches “' + name + '”', { type: 'warning', title: 'Export' });
    });

    // Logout modal → DEMO: there is no session, so just leave a trace.
    $(document).on('click', '[data-logout-confirm]', function () {
      var modal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
      if (modal) modal.hide();
      toast('Signed out (demo — no backend is connected)', { type: 'info', title: 'Logout' });
    });
    $(document).on('click', '[data-action="logout"]', function (event) {
      event.preventDefault();
      var el = document.getElementById('logoutModal');
      if (el) bootstrap.Modal.getOrCreateInstance(el).show();
    });

    // Segmented controls behave like radio groups.
    $(document).on('click', '.segmented [data-segment]', function (event) {
      event.preventDefault();
      var group = $(this).closest('.segmented');
      group.find('[data-segment]').removeClass('is-active').attr('aria-pressed', 'false');
      $(this).addClass('is-active').attr('aria-pressed', 'true');
      group.trigger('nc:segment', [$(this).attr('data-segment')]);
    });

    // Tabs that lazy-render a chart on first show.
    $(document).on('shown.bs.tab', '[data-bs-toggle="tab"], [data-bs-toggle="pill"]', function (event) {
      window.dispatchEvent(
        new CustomEvent('nc:tabshown', { detail: { target: $(event.target).attr('data-bs-target') } })
      );
    });

    // Anything with a title inside the app chrome gets a Bootstrap tooltip.
    initTooltips();

    // Copy-to-clipboard for tracking numbers, SKUs, coupon codes.
    $(document).on('click', '[data-copy]', function (event) {
      event.preventDefault();
      var text = $(this).attr('data-copy');
      var done = function () {
        toast('Copied “' + text + '” to clipboard', { type: 'success', title: 'Copied', delay: 1800 });
      };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(done, fallbackCopy);
      } else {
        fallbackCopy();
      }
      function fallbackCopy() {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
          document.execCommand('copy');
          done();
        } catch (e) {
          toast('Copy failed — select the text manually', { type: 'warning', title: 'Clipboard' });
        }
        ta.remove();
      }
    });

    // Reveal-on-scroll for dashboard widgets (skipped under reduced motion).
    initReveal();

    // Keep chart canvases sized when the shell changes.
    var resizeTimer = null;
    $(window).on('resize nc:layoutchange', function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () {
        window.dispatchEvent(new Event('nc:resizecharts'));
      }, 160);
    });

    // Back-to-top.
    var top = $('[data-scroll-top]');
    if (top.length) {
      $(window).on('scroll', function () {
        top.toggleClass('is-visible', window.scrollY > 480);
      });
      top.on('click', function (event) {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  }

  function initTooltips(root) {
    var scope = root ? $(root) : $(document.body);
    scope.find('[data-bs-toggle="tooltip"]').each(function () {
      if (this.__ncTooltip) return;
      this.__ncTooltip = new bootstrap.Tooltip(this, { trigger: 'hover focus', delay: { show: 220, hide: 60 } });
    });
  }

  function initPopovers(root) {
    var scope = root ? $(root) : $(document.body);
    scope.find('[data-bs-toggle="popover"]').each(function () {
      if (this.__ncPopover) return;
      this.__ncPopover = new bootstrap.Popover(this);
    });
  }

  function initReveal() {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var targets = document.querySelectorAll('.stagger > *, [data-reveal]');
    if (reduce || !targets.length || !('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('nc-fade-up');
          observer.unobserve(entry.target);
        });
      },
      { rootMargin: '0px 0px -40px 0px', threshold: 0.02 }
    );
    Array.prototype.forEach.call(targets, function (el) {
      observer.observe(el);
    });
  }

  /* =======================================================================
   * 13. AdminPages — per-page module registry
   * ===================================================================== */

  var AdminPages = {
    registry: {},

    /** AdminPages.register('products', function (ctx) { … }) */
    register: function (name, fn) {
      this.registry[name] = fn;
      return this;
    },

    /** Shared modules that run on every page. */
    always: function (fn) {
      return this.register('*', fn);
    },

    run: function () {
      var page = document.body.getAttribute('data-page') || '';
      var ctx = {
        page: page,
        params: typeof window.DB !== 'undefined' && window.DB.query ? window.DB.query() : {},
        DB: window.DB,
        ui: AdminUI,
        fmt: Fmt,
        tables: AdminTable.instances,
      };

      var steps = [
        ['*', this.registry['*']],
        ['tables', function () {
          ctx.tables = AdminTable.mountAll();
        }],
        [page, this.registry[page]],
      ];

      steps.forEach(function (step) {
        if (typeof step[1] !== 'function') return;
        try {
          step[1](ctx);
        } catch (err) {
          if (window.console) console.error('[AdminPages:' + step[0] + ']', err);
        }
      });

      window.AdminContext = ctx;
      window.dispatchEvent(new CustomEvent('nc:pageready', { detail: ctx }));
      return ctx;
    },
  };

  /* =======================================================================
   * 14. Public surface + boot
   * ===================================================================== */

  var AdminUI = {
    fmt: Fmt,
    badge: badge,
    stars: stars,
    status: STATUS,
    toast: toast,
    confirm: confirmDialog,
    slugify: slugify,
    theme: { get: currentTheme, set: applyTheme },
    sidebar: {
      collapse: function (value) {
        setCollapsed(value == null ? !document.documentElement.classList.contains('sidebar-collapsed') : value);
      },
      isCollapsed: function () {
        return document.documentElement.classList.contains('sidebar-collapsed');
      },
      closeMobile: closeMobileSidebar,
    },
    tooltips: initTooltips,
    popovers: initPopovers,
    search: runSearch,
    table: function (key) {
      return AdminTable.instances[key];
    },
  };

  window.AdminUI = AdminUI;
  window.AdminTable = AdminTable;
  window.AdminPages = AdminPages;

  function boot() {
    applyTheme(currentTheme(), false);
    initTheme();
    initSidebar();
    initGlobalSearch();
    initNotifications();
    initForms();
    initDropzones();
    initMisc();
    AdminPages.run();
    document.body.classList.add('nc-ready');
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})(window, document, window.jQuery);
