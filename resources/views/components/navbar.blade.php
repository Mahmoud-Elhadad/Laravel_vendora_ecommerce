<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Shop premium electronics, fashion, home essentials, beauty and sports gear at Vendora. Fast delivery, secure checkout, 30-day returns and deals every day.">
  <meta name="author" content="Vendora">
  <meta name="theme-color" content="#4f46e5">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Vendora — Premium Online Store for Electronics, Fashion & Home</title>
  <link rel="canonical" href="/">
  <base href="pages/">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="{{ asset("ecomm") }}/images/ui/favicon.svg">

  <!-- Vendor styles -->
  <link rel="stylesheet" href="{{ asset("ecomm") }}/vendor/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset("ecomm") }}/vendor/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="{{ asset("ecomm") }}/vendor/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="{{ asset("ecomm") }}/vendor/aos/aos.css">

  <!-- Theme styles -->
  <link rel="stylesheet" href="{{ asset("ecomm") }}/css/style.css">
  <link rel="stylesheet" href="{{ asset("ecomm") }}/css/responsive.css">
  <link rel="stylesheet" href="{{ asset("ecomm") }}/css/custom.css">
</head>
<body class="home">
  <a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>

  <div class="announcement-bar">
    <div class="container">
      <div class="abar-inner">
        <div class="abar-left">
          <span class="abar-item"><i class="fa-solid fa-truck-fast"></i> Free shipping on orders over $75</span>
          <span class="abar-item d-none d-md-inline-flex"><i class="fa-solid fa-rotate-left"></i> 30-day easy returns</span>
        </div>
        <div class="abar-right">
          <span class="abar-item d-none d-lg-inline-flex"><i class="fa-regular fa-envelope"></i> support@vendora.com</span>
          <span class="abar-item"><i class="fa-solid fa-tag"></i> Summer sale — up to <strong>40% off</strong>. <a href="{{ route('ecomm.shop.page') }}">Shop now</a></span>
          <button class="abar-close" aria-label="Dismiss announcement"><i class="fa-solid fa-xmark"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================ HEADER ============================ -->
  <header class="site-header" data-sticky="true">
    <div class="header-main">
      <div class="container">
        <div class="hm-inner">
          <button class="mobile-nav-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Open menu">
            <i class="fa-solid fa-bars"></i>
          </button>

          <a class="header-logo" href="/" aria-label="Vendora home">
            <img src="{{ asset("ecomm") }}/images/ui/logo.svg" alt="Vendora">
          </a>


          <div class="header-actions">
            <div class="dropdown-v d-none d-sm-block">
              <a class="h-action" href="{{ route("ecomm.show.accountPage") }}" aria-label="My account">
                <span class="ha-ico"><i class="fa-regular fa-user"></i></span>
                <span class="ha-text d-none d-xl-flex">
                  <span class="ha-label">
                    Hello, @if(Auth::guard("ecomm")->check())
                        {{ Auth::guard("ecomm")->user()->first_name }}
                    @else
                    Sign in
                    @endif
                </span>
                  <span class="ha-value">Account</span>
                </span>
              </a>
              <div class="dropdown-panel">
                  <div class="dp-head"><strong>Welcome to Vendora</strong><br>
                    @if(!Auth::guard("ecomm")->check())
                     <small class="text-muted">Sign in for a personalised experience</small>
                    @endif
                    </div>
                @if(!Auth::guard("ecomm")->check())

                    <a href="{{ route("ecomm.show.loginPage") }}"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
                    <a href="{{ route("ecomm.show.registrPage") }}"><i class="fa-solid fa-user-plus"></i> Create account</a>
                @endif
                <a href="{{ route("ecomm.show.accountPage") }}"><i class="fa-regular fa-circle-user"></i> My account</a>
                <a href="{{ route("ecomm.whilistPage") }}"><i class="fa-regular fa-heart"></i> Wishlist</a>
                <a href="{{ route("show.cart") }}"><i class="fa-solid fa-bag-shopping"></i> Cart</a>
              </div>
            </div>

            <a class="h-action d-none d-md-flex navbar-wishlist-info" href="{{ route("ecomm.whilistPage") }}" aria-label="Wishlist">
              <span class="ha-ico"><i class="fa-regular fa-heart"></i><span class="count-badge" <?= $num_whilists == 0 ? "data-wishlist-count" : ""?> >{{ $num_whilists }}</span></span>
              <span class="ha-text d-none d-xl-flex"><span class="ha-label">Your</span><span class="ha-value">Wishlist</span></span>
            </a>



            <a class="h-action navbar-cart-info" href="{{ route("show.cart") }}" aria-label="Cart">
              <span class="ha-ico"><i class="fa-solid fa-bag-shopping"></i><span class="count-badge primary" <?= $num_carts == 0 ? "data-cart-count" : ""?> >{{ $num_carts }}</span></span>
              <span class="ha-text d-none d-xl-flex"><span class="ha-label">Your cart</span><span >$<?= $sum_all_carts ? $sum_all_carts : "0.00" ?></span></span>
            </a>
          </div>
        </div>

        <!-- Mobile search (below the bar on small screens) -->
        <div class="header-search d-lg-none mt-3">
          <form class="search-box" role="search" action="search.html" method="get">
            <input type="search" name="q" placeholder="Search products..." aria-label="Search products" autocomplete="off">
            <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>
      </div>
    </div>

    <div class="header-nav d-none d-lg-block">
      <div class="container">
        <div class="hn-inner">
          <ul class="main-nav">
            <li><a class="nav-toggle-cat" href="{{ route('ecomm.shop.page') }}"><i class="fa-solid fa-layer-group"></i> Browse Categories</a></li>
            <li><a href="/" class="{{ Request::routeIs('display.home') ? 'active' : '' }}">Home</a></li>
            <li class="has-mega">
              <a href="{{ route('ecomm.shop.page') }}" class="{{ Request::routeIs('ecomm.shop.page') ? 'active' : '' }}">Shop <i class="fa-solid fa-chevron-down fa-xs"></i></a>
              <div class="mega-menu">
                <div class="container">
                  <div class="mega-grid">
                    <div class="mega-col">
                      <h6>Shop</h6>
                      <ul>
                        <li><a href="{{ route('ecomm.shop.page') }}">All products</a></li>
                        <li><a href="{{ route('ecomm.shop.page', ['sort' => 'newest']) }}">New arrivals</a></li>
                        <li><a href="{{ route('ecomm.shop.page', ['sort' => 'popular']) }}">Best sellers</a></li>
                        <li><a href="{{ route('ecomm.shop.page', ['sort' => 'price_asc']) }}">Price: low → high</a></li>
                      </ul>
                    </div>
                    <div class="mega-col">
                      <h6>Categories</h6>
                      <ul id="mega-categories">
                        @foreach ($navbar_categories ?? [] as $navCat)
                            <li><a href="{{ route('ecomm.shop.page', ['cat' => $navCat->id]) }}">{{ $navCat->name }}</a></li>
                        @endforeach
                      </ul>
                    </div>
                    <div class="mega-col">
                      <h6>Account</h6>
                      <ul>
                        <li><a href="{{ route("ecomm.show.accountPage") }}">My account</a></li>
                        <li><a href="{{ route("ecomm.whilistPage") }}">Wishlist</a></li>
                        @if(!Auth::guard("ecomm")->check())

                         <li><a href="{{ route("ecomm.show.loginPage") }}">Sign in</a></li>
                        @endif
                      </ul>
                    </div>
                    <div class="mega-col">
                      <h6>Utility</h6>
                      <ul>
                        <li><a href="{{ route("show.cart") }}">Shopping cart</a></li>
                        <li><a href="{{ route("ecomm.FAQ.page") }}">Help &amp; FAQ</a></li>
                      </ul>
                    </div>
                    <div class="mega-col mega-promo">
                      <a href="{{ route('ecomm.shop.page') }}"><img src="{{ asset("ecomm") }}/images/banners/promo-1.svg" alt="Featured promotion"></a>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <li class="dropdown-v dropdown-center">
              <a href="#" class="">Pages <i class="fa-solid fa-chevron-down fa-xs"></i></a>
              <div class="dropdown-panel">
                <a href="{{ route('ecomm.about.page') }}">About us</a>
                <a href="{{ route("show.message") }}">Contact</a>
                <a href="{{ route("ecomm.FAQ.page") }}">FAQ</a>
                <a href="{{ route("ecomm.privacy.page") }}">Privacy policy</a>
                <a href="{{ route("ecomm.terms.page") }}">Terms of service</a>
              </div>
            </li>
            <li><a href="{{ route('ecomm.about.page') }}" class="{{ Request::routeIs('ecomm.about.page') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route("show.message") }}" class="{{ Request::routeIs('show.message') ? 'active' : '' }}">Contact</a></li>
          </ul>

          @if(Auth::guard("ecomm")->check())

            <div class="nav-right">
                <span class="nr-item"><i class="fa-solid fa-headset"></i><span><strong> {{ Auth::guard("ecomm")->user()->phone }} </strong></span></span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </header>

  <!-- ========================= MOBILE MENU ========================= -->
  <div class="offcanvas offcanvas-start offcanvas-menu" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="oc-header">
      <img src="{{ asset("ecomm") }}/images/ui/logo.svg" alt="Vendora" id="mobileMenuLabel">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form class="search-box mb-3" role="search" action="search.html" method="get">
        <input type="search" name="q" placeholder="Search products..." aria-label="Search">
        <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>

      <ul class="mobile-menu">
        <li><a href="/"><i class="fa-solid fa-house me-2"></i>Home</a></li>
        <li class="has-sub">
          <a href="{{ route('ecomm.shop.page') }}"><i class="fa-solid fa-bag-shopping me-2"></i>Shop <i class="fa-solid fa-chevron-down float-end mt-1"></i></a>
          <ul class="submenu">
            <li><a href="{{ route('ecomm.shop.page') }}">All products</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['sort' => 'newest']) }}">New arrivals</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['sort' => 'popular']) }}">Best sellers</a></li>
            <li><a href="{{ route('ecomm.shop.page', ['sort' => 'price_asc']) }}">Price: low → high</a></li>
          </ul>
        </li>
        <li class="has-sub">
          <a href="{{ route('ecomm.shop.page') }}"><i class="fa-solid fa-layer-group me-2"></i>Categories <i class="fa-solid fa-chevron-down float-end mt-1"></i></a>
          <ul class="submenu" id="mobile-categories">
            @foreach ($navbar_categories ?? [] as $navCat)
                <li><a href="{{ route('ecomm.shop.page', ['cat' => $navCat->id]) }}">{{ $navCat->name }}</a></li>
            @endforeach
          </ul>
        </li>
        <li><a href="{{ route('ecomm.about.page') }}"><i class="fa-regular fa-circle-info me-2"></i>About</a></li>
        <li><a href="{{ route("show.message") }}"><i class="fa-regular fa-address-book me-2"></i>Contact</a></li>
        <li class="has-sub">
          <a href="{{ route("ecomm.show.accountPage") }}"><i class="fa-regular fa-circle-user me-2"></i>Account <i class="fa-solid fa-chevron-down float-end mt-1"></i></a>
          <ul class="submenu">
            <li><a href="{{ route("ecomm.show.accountPage") }}">Dashboard</a></li>
            <li><a href="{{ route("ecomm.whilistPage") }}">Wishlist</a></li>
            <li><a href="{{ route("ecomm.show.accountPage") }}">Profile</a></li>
            @if(!Auth::guard("ecomm")->check())

               <li><a href="{{ route("ecomm.show.loginPage") }}">Sign in</a></li>
            @endif
          </ul>
        </li>
      </ul>

      @if(!Auth::guard("ecomm")->check())

        <div class="mt-4 pt-3 border-top">
            <a href="{{ route("ecomm.show.loginPage") }}" class="btn btn-primary-v btn-block mb-2"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
            <a href="{{ route("ecomm.show.registrPage") }}" class="btn btn-outline-v btn-block">Create account</a>
        </div>
      @endif
      <div class="mt-4 text-muted small">
        <p class="mb-1"><i class="fa-solid fa-phone me-2 text-primary-v"></i>+1 (800) 555-0199</p>
        <p class="mb-0"><i class="fa-regular fa-envelope me-2 text-primary-v"></i>support@vendora.com</p>
      </div>
    </div>
  </div>
