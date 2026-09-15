/* ==========================================================================
   Vendora E-commerce — Custom / Page Logic (custom.js)
   Home rendering, compare, blog, about, contact, account, profile,
   addresses and misc page behaviour.
   Depends on: jQuery, Bootstrap, Swiper, main.js (Store), data.js (VendoraDB)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store, DB = window.VendoraDB;

  var CAROUSEL_BREAKPOINTS = {
    0: { slidesPerView: 1.3, spaceBetween: 14 },
    480: { slidesPerView: 2, spaceBetween: 16 },
    768: { slidesPerView: 3, spaceBetween: 20 },
    1024: { slidesPerView: 4, spaceBetween: 24 },
    1400: { slidesPerView: 5, spaceBetween: 24 }
  };

  /* ==================================================================
     HOME PAGE
  ================================================================== */
  function initHome() {
    if (!$('#home-page').length) return;

    // Product carousels - already static HTML, just initialize Swiper
    $('.product-carousel').each(function () {
      var $sw = $(this);
      var $wrap = $sw.closest('.carousel-wrap');
      new Swiper(this, {
        slidesPerView: 4, spaceBetween: 24, loop: false, grabCursor: true,
        breakpoints: CAROUSEL_BREAKPOINTS,
        navigation: { nextEl: $wrap.find('.carousel-next')[0], prevEl: $wrap.find('.carousel-prev')[0] }
      });
    });

    // Hero slider
    if ($('.hero-slider').length) {
      new Swiper('.hero-slider', {
        loop: true, effect: 'fade', fadeEffect: { crossFade: true }, speed: 700,
        autoplay: { delay: 5200, disableOnInteraction: false },
        pagination: { el: '.hero-slider .swiper-pagination', clickable: true },
        navigation: { nextEl: '.hero-slider .swiper-button-next', prevEl: '.hero-slider .swiper-button-prev' }
      });
    }

    // Brands carousel - already static HTML, just initialize Swiper
    var $brands = $('#home-brands');
    if ($brands.length) {
      new Swiper($brands[0], {
        slidesPerView: 5, spaceBetween: 20, loop: true, autoplay: { delay: 2600, disableOnInteraction: false },
        breakpoints: { 0: { slidesPerView: 2 }, 480: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 5 } }
      });
    }

    // Testimonials carousel - already static HTML, just initialize Swiper
    var $testi = $('#home-testimonials');
    if ($testi.length) {
      new Swiper($testi[0], {
        slidesPerView: 3, spaceBetween: 24, loop: true, grabCursor: true,
        autoplay: { delay: 4200, disableOnInteraction: false },
        breakpoints: { 0: { slidesPerView: 1 }, 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
        pagination: { el: $testi.find('.swiper-pagination')[0], clickable: true }
      });
    }

    // Countdown (flash sale) handled by Store.init if .countdown.auto present
  }

  function blogCard(post) {
    return '<div class="col-md-6 col-lg-4"><article class="blog-card">' +
      '<div class="bc-media"><a href="blog-details.html?id=' + post.id + '"><img src="' + post.image + '" alt="' + Store.esc(post.title) + '" loading="lazy"></a>' +
      '<span class="badge-v badge-soft bc-cat">' + Store.esc(post.category) + '</span></div>' +
      '<div class="bc-body">' +
        '<div class="bc-meta"><span><i class="fa-regular fa-calendar"></i> ' + Store.formatDate(post.date) + '</span>' +
        '<span><i class="fa-regular fa-clock"></i> ' + Store.esc(post.readTime) + '</span></div>' +
        '<h5><a href="blog-details.html?id=' + post.id + '">' + Store.esc(post.title) + '</a></h5>' +
        '<p>' + Store.esc(post.excerpt) + '</p>' +
        '<a href="blog-details.html?id=' + post.id + '" class="bc-link">Read more <i class="fa-solid fa-arrow-right"></i></a>' +
      '</div></article></div>';
  }

  /* ==================================================================
     COMPARE PAGE
  ================================================================== */
  function initCompare() {
    var $root = $('#compare-page');
    if (!$root.length) return;

    function render() {
      var ids = Store.getCompare();
      var products = ids.map(function (id) { return DB.productById(id); }).filter(Boolean);
      $('#compare-count').text(products.length);
      if (!products.length) {
        $root.html('<div class="empty-state"><img src="' + DB.IMG + 'ui/empty-search.svg" alt="Nothing to compare">' +
          '<h4>Your comparison list is empty</h4><p>Add products using the compare button to see them side by side here.</p>' +
          '<a href="shop.html" class="btn btn-primary-v btn-lg">Browse products</a></div>');
        return;
      }
      var cols = products.length;
      var colW = Math.max(200, Math.floor(860 / cols));
      function row(label, cellFn) {
        return '<tr><th class="row-label">' + label + '</th>' + products.map(cellFn).join('') + '</tr>';
      }
      var specKeys = [];
      products.forEach(function (p) { Object.keys(p.specs).forEach(function (k) { if (specKeys.indexOf(k) === -1) specKeys.push(k); }); });

      var html = '<div class="compare-scroll"><table class="compare-table"><tbody>';
      html += row('', function (p) {
        return '<td><img src="' + p.image + '" alt="' + Store.esc(p.name) + '">' +
          '<a class="cmp-name" href="product-details.html?id=' + p.id + '">' + Store.esc(p.name) + '</a>' +
          '<button class="cmp-remove" data-id="' + p.id + '" title="Remove"><i class="fa-regular fa-trash-can"></i> Remove</button></td>';
      });
      html += row('Price', function (p) { return '<td><strong style="font-size:18px;color:var(--v-heading)">' + Store.money(p.price) + '</strong>' + (p.oldPrice > p.price ? '<br><span class="text-muted" style="text-decoration:line-through">' + Store.money(p.oldPrice) + '</span>' : '') + '</td>'; });
      html += row('Rating', function (p) { return '<td>' + Store.stars(p.rating) + '<div class="text-muted small">(' + p.reviews + ')</div></td>'; });
      html += row('Availability', function (p) { return '<td>' + Store.stockLabel(p) + '</td>'; });
      html += row('Brand', function (p) { return '<td>' + Store.esc(p.brand) + '</td>'; });
      html += row('Category', function (p) { return '<td>' + Store.esc(p.categoryName) + '</td>'; });
      specKeys.forEach(function (k) {
        html += row(k, function (p) { return '<td>' + Store.esc(p.specs[k] || '—') + '</td>'; });
      });
      html += row('Colors', function (p) {
        if (!p.colors.length) return '<td class="text-muted">—</td>';
        return '<td><div class="d-flex gap-1 justify-content-center">' + p.colors.map(function (c) { return '<span class="swatch" style="background:' + c.hex + ';width:18px;height:18px;border-radius:50%;display:inline-block"></span>'; }).join('') + '</div></td>';
      });
      html += row('', function (p) {
        return '<td><button class="btn btn-primary-v btn-sm add-to-cart" data-id="' + p.id + '"' + (p.stock === 'out' ? ' disabled' : '') + '><i class="fa-solid fa-cart-plus"></i> Add to cart</button></td>';
      });
      html += '</tbody></table></div>';
      html += '<div class="text-center mt-4"><button class="btn btn-outline-v" id="clear-compare"><i class="fa-regular fa-trash-can"></i> Clear comparison</button></div>';
      $root.html(html);
    }

    render();
    $(window).on('compare:changed', render);
    $root.on('click', '.cmp-remove', function () { Store.toggleCompare($(this).data('id')); });
    $root.on('click', '#clear-compare', function () { Store.saveCompare([]); });
  }

  /* ==================================================================
     BLOG
  ================================================================== */
  function initBlog() {
    var $grid = $('#blog-grid');
    if (!$grid.length) return;
    var activeCat = 'all', query = '';

    // Sidebar
    $('#blog-categories').html(DB.blog.reduce(function (acc, p) {
      acc[p.category] = (acc[p.category] || 0) + 1; return acc;
    }, {}) && (function () {
      var counts = {};
      DB.blog.forEach(function (p) { counts[p.category] = (counts[p.category] || 0) + 1; });
      var html = '<li><a href="#" class="d-flex justify-content-between" data-cat="all">All<span class="cnt">' + DB.blog.length + '</span></a></li>';
      Object.keys(counts).forEach(function (c) { html += '<li><a href="#" class="d-flex justify-content-between" data-cat="' + Store.esc(c) + '">' + Store.esc(c) + '<span class="cnt">' + counts[c] + '</span></a></li>'; });
      return html;
    })());

    function render() {
      var list = DB.blog.filter(function (p) {
        if (activeCat !== 'all' && p.category !== activeCat) return false;
        if (query && (p.title + ' ' + p.excerpt + ' ' + p.tags.join(' ')).toLowerCase().indexOf(query) === -1) return false;
        return true;
      });
      if (!list.length) { $grid.html('<div class="col-12"><div class="empty-state"><h4>No articles found</h4><p>Try a different category or search term.</p></div></div>'); return; }
      $grid.html(list.map(blogCard).join(''));
    }
    // Featured post
    var feat = DB.blog.filter(function (p) { return p.featured; })[0] || DB.blog[0];
    $('#blog-featured').html('<a href="blog-details.html?id=' + feat.id + '" class="featured-post d-block">' +
      '<img class="fp-bg" src="' + feat.image + '" alt="' + Store.esc(feat.title) + '">' +
      '<div class="fp-body"><span class="badge-v badge-soft mb-2">' + Store.esc(feat.category) + '</span>' +
      '<h2>' + Store.esc(feat.title) + '</h2>' +
      '<div class="bc-meta"><span><i class="fa-regular fa-user"></i> ' + Store.esc(feat.author) + '</span><span><i class="fa-regular fa-calendar"></i> ' + Store.formatDate(feat.date) + '</span><span><i class="fa-regular fa-clock"></i> ' + Store.esc(feat.readTime) + '</span></div>' +
      '</div></a>');

    // Recent & popular widgets
    $('#blog-recent').html(DB.blog.slice(0, 4).map(function (p) {
      return '<li><img src="' + p.image + '" alt=""><div><h6><a href="blog-details.html?id=' + p.id + '">' + Store.esc(p.title) + '</a></h6><span class="wp-date">' + Store.formatDate(p.date) + '</span></div></li>';
    }).join(''));
    var tags = []; DB.blog.forEach(function (p) { p.tags.forEach(function (t) { if (tags.indexOf(t) === -1) tags.push(t); }); });
    $('#blog-tags').html(tags.map(function (t) { return '<a href="#" class="tag-pill">' + Store.esc(t) + '</a>'; }).join(''));

    render();
    $('#blog-categories').on('click', 'a[data-cat]', function (e) { e.preventDefault(); activeCat = $(this).data('cat'); $('#blog-categories a').removeClass('active'); $(this).addClass('active'); render(); });
    $('#blog-search').on('input', function () { query = $(this).val().toLowerCase().trim(); render(); });
  }

  function initBlogDetails() {
    var $root = $('#blog-article');
    if (!$root.length) return;
    var id = Store.urlParam('id') || 1;
    var post = DB.blogById(id) || DB.blog[0];
    document.title = post.title + ' — Vendora Blog';
    var paras = post.content.map(function (c) { return '<p>' + Store.esc(c) + '</p>'; }).join('');
    $root.html('' +
      '<span class="badge-v badge-soft mb-3">' + Store.esc(post.category) + '</span>' +
      '<h1 class="mb-3">' + Store.esc(post.title) + '</h1>' +
      '<div class="article-meta">' +
        '<div class="am-author"><img src="' + DB.IMG + 'users/user-' + ((post.id % 6) + 1) + '.svg" alt="' + Store.esc(post.author) + '"><div><strong>' + Store.esc(post.author) + '</strong><div class="text-muted small">Author</div></div></div>' +
        '<span class="text-muted"><i class="fa-regular fa-calendar"></i> ' + Store.formatDate(post.date) + '</span>' +
        '<span class="text-muted"><i class="fa-regular fa-clock"></i> ' + Store.esc(post.readTime) + '</span>' +
      '</div>' +
      '<div class="article-hero"><img src="' + post.image + '" alt="' + Store.esc(post.title) + '"></div>' +
      '<div class="article-content">' + paras +
        '<blockquote>"Quality is not an act, it is a habit. We curate every product so you can shop with total confidence."</blockquote>' +
        '<p>Thanks for reading. Explore our catalog to find the products mentioned in this article, and follow us on social media for more guides and inspiration.</p>' +
      '</div>' +
      '<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-4 border-top">' +
        '<div class="tags-row">' + post.tags.map(function (t) { return '<a href="blog.html" class="tag-pill">#' + Store.esc(t) + '</a>'; }).join('') + '</div>' +
        '<div class="share-row"><a href="#" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>' +
        '<a href="#" aria-label="Share on Twitter"><i class="fa-brands fa-x-twitter"></i></a>' +
        '<a href="#" aria-label="Share on Pinterest"><i class="fa-brands fa-pinterest-p"></i></a>' +
        '<a href="#" aria-label="Copy link"><i class="fa-solid fa-link"></i></a></div>' +
      '</div>');

    // Related posts
    var related = DB.blog.filter(function (p) { return p.id !== post.id; }).slice(0, 3);
    $('#blog-related').html(related.map(blogCard).join(''));

    // Comments (demo)
    $('#blog-comments').html([
      { n: 'Sarah Mitchell', d: '2 days ago', t: 'Such a helpful read! I picked up two of these recommendations already.', a: 'user-2' },
      { n: 'Daniel Kim', d: '4 days ago', t: 'Great tips. Would love a follow-up article comparing budget options.', a: 'user-4' }
    ].map(function (c) {
      return '<div class="comment-item"><img src="' + DB.IMG + 'users/' + c.a + '.svg" alt="' + Store.esc(c.n) + '">' +
        '<div class="flex-grow-1"><div class="cm-head"><h6>' + Store.esc(c.n) + '</h6><span class="cm-date">' + Store.esc(c.d) + '</span>' +
        '<a href="#" class="cm-reply">Reply</a></div><p class="mb-0 text-muted">' + Store.esc(c.t) + '</p></div></div>';
    }).join(''));

    $('#comment-form').on('submit', function (e) {
      e.preventDefault();
      var name = $('#comment-name').val().trim() || 'Guest';
      var text = $('#comment-text').val().trim();
      if (!text) { Store.toast('Please write a comment.', 'error'); return; }
      $('#blog-comments').append('<div class="comment-item fade-in-up"><img src="' + DB.IMG + 'users/user-12.svg" alt="' + Store.esc(name) + '">' +
        '<div class="flex-grow-1"><div class="cm-head"><h6>' + Store.esc(name) + '</h6><span class="cm-date">Just now</span></div>' +
        '<p class="mb-0 text-muted">' + Store.esc(text) + '</p></div></div>');
      this.reset();
      Store.toast('Comment posted!', 'success');
    });
  }

  /* ==================================================================
     ABOUT PAGE (animated counters + team + testimonials)
  ================================================================== */
  function initAbout() {
    if (!$('#about-page').length) return;

    // Team
    var $team = $('#about-team');
    if ($team.length) {
      $team.html(DB.team.map(function (m) {
        return '<div class="col-6 col-lg-3"><div class="team-card"><img src="' + m.avatar + '" alt="' + Store.esc(m.name) + '">' +
          '<div class="tc-body"><h6>' + Store.esc(m.name) + '</h6><span>' + Store.esc(m.role) + '</span>' +
          '<div class="tc-social"><a href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>' +
          '<a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>' +
          '<a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></div></div></div></div>';
      }).join(''));
    }
    // Testimonials
    var $testi = $('#about-testimonials');
    if ($testi.length) {
      $testi.html(DB.testimonials.slice(0, 3).map(function (t) {
        return '<div class="col-md-4"><div class="testimonial-card h-100"><i class="fa-solid fa-quote-right quote-ico"></i>' + Store.stars(t.rating) +
          '<p class="mt-3">"' + Store.esc(t.text) + '"</p><div class="tc-author"><img src="' + t.avatar + '" alt=""><div><h6>' + Store.esc(t.name) + '</h6><span>' + Store.esc(t.role) + '</span></div></div></div></div>';
      }).join(''));
    }
    // Counters
    animateCounters();
  }

  function animateCounters() {
    var $counters = $('[data-count]');
    if (!$counters.length) return;
    var done = false;
    function run() {
      if (done) return; done = true;
      $counters.each(function () {
        var $el = $(this), target = Number($el.data('count')) || 0, suffix = $el.data('suffix') || '';
        $({ v: 0 }).animate({ v: target }, {
          duration: 1600, easing: 'swing',
          step: function (now) { $el.text(Math.floor(now).toLocaleString() + suffix); },
          complete: function () { $el.text(target.toLocaleString() + suffix); }
        });
      });
    }
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { run(); io.disconnect(); } });
      }, { threshold: 0.3 });
      io.observe($counters[0]);
    } else run();
  }

  /* ==================================================================
     CONTACT PAGE
  ================================================================== */
  function initContact() {
    var $form = $('#contact-form');
    if (!$form.length) return;
    $form.on('submit', function (e) {
      e.preventDefault();
      var name = $('#contact-name').val().trim();
      var email = $('#contact-email').val().trim();
      var message = $('#contact-message').val().trim();
      var subject = $("#contact-subject").val().trim()
      var _token = $('meta[name="csrf-token"]').attr("content")
      var ok = true;
      [['#contact-name', name.length >= 2], ['#contact-email', /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)], ['#contact-message', message.length >= 10]].forEach(function (f) {
        $(f[0]).toggleClass('is-invalid', !f[1]); if (!f[1]) ok = false;
      });
      if (!ok) { Store.toast('Please fix the highlighted fields.', 'error'); return; }

      let all_subjects = [
            "general enquiry"
            ,"order support"
            ,"returns & refunds"
            ,"product question"
            ,"partnership"
      ];
      if(!all_subjects.includes(subject)){Store.toast('Please fix the highlighted fields.', 'error'); return;}
      $.ajax({
        url : '/storeMs' ,
        method : "post" ,
        data : {
            name , email , message , subject , _token
        },success:function(data){
            

        },error:function(error){
            console.log(error);

        }
      });



      var $btn = $form.find('button[type="submit"]');
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Sending...');
      setTimeout(function () {
        $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Send Message');
        $form[0].reset();
        $('#contact-success').removeClass('d-none');
        Store.toast('Message sent! We will get back to you shortly.', 'success');
      }, 1200);



    });

  }

  /* ==================================================================
     MY ACCOUNT
  ================================================================== */
  function initAccount() {
    if (!$('#account-page').length) return;
    var c = DB.customer;
    var orders = placedOrdersAll();
    $('#acct-name').text(c.firstName + ' ' + c.lastName);
    $('#acct-email').text(c.email);
    $('#acct-avatar').attr('src', c.avatar);
    $('#acct-member').text('Member since ' + c.memberSince);
    $('#stat-orders').text(orders.length);
    $('#stat-wishlist').text(Store.getWishlist().length);
    $('#stat-cart').text(Store.cartCount());
    $('#stat-total').text(Store.money(orders.reduce(function (s, o) { return s + o.total; }, 0)));

    var recent = orders.slice(0, 4);
    $('#recent-orders').html(recent.length ? recent.map(function (o) {
      return '<tr><td><span class="order-num">' + Store.esc(o.id) + '</span></td><td>' + Store.formatDate(o.date) + '</td>' +
        '<td><span class="status-badge status-' + o.status + '">' + Store.esc(o.statusLabel) + '</span></td>' +
        '<td><strong>' + Store.money(o.total) + '</strong></td>' +
        '<td class="text-end"><a href="order-details.html?id=' + encodeURIComponent(o.id) + '" class="btn btn-sm btn-outline-v">View</a></td></tr>';
    }).join('') : '<tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr>');
  }
  function placedOrdersAll() { return Store.read('vendora_orders', []).concat(DB.orders); }

  /* ==================================================================
     PROFILE
  ================================================================== */
  function initProfile() {
    var $form = $('#profile-form');
    if (!$form.length) return;
    var c = DB.customer;
    $('#profile-avatar').attr('src', c.avatar);
    $('#p-first').val(c.firstName); $('#p-last').val(c.lastName);
    $('#p-email').val(c.email); $('#p-phone').val(c.phone); $('#p-dob').val(c.dob);

    $form.on('submit', function (e) {
      e.preventDefault();
      var ok = true;
      [['#p-first', $('#p-first').val().trim().length >= 2], ['#p-last', $('#p-last').val().trim().length >= 2], ['#p-email', /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test($('#p-email').val().trim())]].forEach(function (f) {
        $(f[0]).toggleClass('is-invalid', !f[1]); if (!f[1]) ok = false;
      });
      if (!ok) { Store.toast('Please fix the highlighted fields.', 'error'); return; }
      Store.toast('Profile updated successfully!', 'success');
    });
    // Change password section
    $('#password-form').on('submit', function (e) {
      e.preventDefault();
      var cur = $('#cur-password').val(), nw = $('#np-password').val(), cf = $('#np-confirm').val();
      if (!cur) { $('#cur-password').addClass('is-invalid'); return; } else $('#cur-password').removeClass('is-invalid');
      if (nw.length < 8) { $('#np-password').addClass('is-invalid'); return; } else $('#np-password').removeClass('is-invalid');
      if (nw !== cf) { $('#np-confirm').addClass('is-invalid'); return; } else $('#np-confirm').removeClass('is-invalid');
      this.reset();
      Store.toast('Password changed successfully!', 'success');
    });
  }

  /* ==================================================================
     ADDRESSES
  ================================================================== */
  var ADDR_KEY = 'vendora_addresses';
  function getAddresses() { return Store.read(ADDR_KEY, DB.addresses); }
  function saveAddresses(a) { Store.write(ADDR_KEY, a); }

  function initAddresses() {
    if (!$('#addresses-page').length) return;
    var editingId = null;

    function render() {
      var list = getAddresses();
      $('#addresses-grid').html(list.map(function (a) {
        return '<div class="col-md-6"><div class="address-card' + (a.isDefault ? ' default' : '') + '">' +
          (a.isDefault ? '<span class="badge-v badge-soft default-tag">Default</span>' : '') +
          '<div class="ac-type"><i class="fa-solid ' + (a.type === 'billing' ? 'fa-file-invoice-dollar' : 'fa-truck-fast') + '"></i> ' + Store.esc(a.type) + ' · ' + Store.esc(a.label) + '</div>' +
          '<h6>' + Store.esc(a.name) + '</h6>' +
          '<p>' + Store.esc(a.line1) + (a.line2 ? '<br>' + Store.esc(a.line2) : '') + '</p>' +
          '<p>' + Store.esc(a.city) + ', ' + Store.esc(a.state) + ' ' + Store.esc(a.zip) + '</p>' +
          '<p>' + Store.esc(a.country) + '</p>' +
          '<p><i class="fa-solid fa-phone text-muted"></i> ' + Store.esc(a.phone) + '</p>' +
          '<div class="ac-actions">' +
            '<button class="btn btn-sm btn-outline-v edit-addr" data-id="' + a.id + '"><i class="fa-regular fa-pen-to-square"></i> Edit</button>' +
            (a.isDefault ? '' : '<button class="btn btn-sm btn-soft-v set-default" data-id="' + a.id + '" data-type="' + a.type + '"><i class="fa-solid fa-check"></i> Set default</button>') +
            '<button class="btn btn-sm btn-outline-danger del-addr" data-id="' + a.id + '"><i class="fa-regular fa-trash-can"></i></button>' +
          '</div></div></div>';
      }).join('') +
      '<div class="col-md-6"><div class="address-add" id="add-address-btn"><i class="fa-solid fa-plus"></i><div><strong>Add new address</strong><div class="small">Shipping or billing</div></div></div></div>');
    }

    function openModal(addr) {
      editingId = addr ? addr.id : null;
      $('#addr-modal-title').text(addr ? 'Edit address' : 'Add new address');
      $('#a-label').val(addr ? addr.label : 'Home');
      $('#a-type').val(addr ? addr.type : 'shipping');
      $('#a-name').val(addr ? addr.name : DB.customer.firstName + ' ' + DB.customer.lastName);
      $('#a-phone').val(addr ? addr.phone : DB.customer.phone);
      $('#a-line1').val(addr ? addr.line1 : '');
      $('#a-line2').val(addr ? addr.line2 : '');
      $('#a-city').val(addr ? addr.city : '');
      $('#a-state').val(addr ? addr.state : '');
      $('#a-zip').val(addr ? addr.zip : '');
      $('#a-country').val(addr ? addr.country : 'United States');
      $('#a-default').prop('checked', addr ? !!addr.isDefault : false);
      new bootstrap.Modal(document.getElementById('addressModal')).show();
    }

    render();
    $('#addresses-page').on('click', '#add-address-btn', function () { openModal(null); });
    $('#addresses-page').on('click', '.edit-addr', function () {
      var id = Number($(this).data('id'));
      openModal(getAddresses().filter(function (a) { return a.id === id; })[0]);
    });
    $('#addresses-page').on('click', '.del-addr', function () {
      var id = Number($(this).data('id'));
      if (!confirm('Delete this address?')) return;
      saveAddresses(getAddresses().filter(function (a) { return a.id !== id; }));
      Store.toast('Address deleted', 'info'); render();
    });
    $('#addresses-page').on('click', '.set-default', function () {
      var id = Number($(this).data('id')), type = $(this).data('type');
      var list = getAddresses().map(function (a) {
        if (a.type === type) a.isDefault = (a.id === id);
        return a;
      });
      saveAddresses(list); Store.toast('Default address updated', 'success'); render();
    });

    $('#address-form').on('submit', function (e) {
      e.preventDefault();
      var required = ['#a-name', '#a-phone', '#a-line1', '#a-city', '#a-zip'];
      var ok = true;
      required.forEach(function (sel) { var $f = $(sel); if (!$f.val().trim()) { $f.addClass('is-invalid'); ok = false; } else $f.removeClass('is-invalid'); });
      if (!ok) return;
      var list = getAddresses();
      var data = {
        type: $('#a-type').val(), label: $('#a-label').val(), name: $('#a-name').val(), phone: $('#a-phone').val(),
        line1: $('#a-line1').val(), line2: $('#a-line2').val(), city: $('#a-city').val(), state: $('#a-state').val(),
        zip: $('#a-zip').val(), country: $('#a-country').val(), isDefault: $('#a-default').is(':checked')
      };
      if (data.isDefault) list.forEach(function (a) { if (a.type === data.type) a.isDefault = false; });
      if (editingId) {
        list = list.map(function (a) { if (a.id === editingId) return $.extend(a, data); return a; });
      } else {
        data.id = list.length ? Math.max.apply(null, list.map(function (a) { return a.id; })) + 1 : 1;
        if (!data.isDefault && !list.some(function (a) { return a.type === data.type; })) data.isDefault = true;
        list.push(data);
      }
      saveAddresses(list);
      bootstrap.Modal.getInstance(document.getElementById('addressModal')).hide();
      Store.toast(editingId ? 'Address updated' : 'Address added', 'success');
      render();
    });
  }

  /* ==================================================================
     MISC (404 search, FAQ filter)
  ================================================================== */
  function initMisc() {
    // 404 / error page search
    $(document).on('submit', '#error-search', function (e) {
      e.preventDefault();
      var q = $('#error-search input').val();
      window.location.href = 'search.html?q=' + encodeURIComponent(q);
    });

    // FAQ category filter
    $('#faq-cat-nav').on('click', 'a[data-faq]', function (e) {
      e.preventDefault();
      var cat = $(this).data('faq');
      $('#faq-cat-nav a').removeClass('active'); $(this).addClass('active');
      $('.faq-group').each(function () {
        if (cat === 'all' || $(this).data('group') === cat) $(this).removeClass('d-none');
        else $(this).addClass('d-none');
      });
    });
  }

  $(function () {
    initHome();
    initCompare();
    initBlog();
    initBlogDetails();
    initAbout();
    initContact();
    initAccount();
    initProfile();
    initAddresses();
    initMisc();
  });

})(window, jQuery);
