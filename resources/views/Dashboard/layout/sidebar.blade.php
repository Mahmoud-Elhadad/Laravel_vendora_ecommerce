<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Dashboard — NovaCart e-commerce administration console." />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Dashboard · NovaCart Admin</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset("dashboard") }}/images/ui/favicon.svg" />

    <!-- Applies the saved theme + sidebar state before first paint (no flash). -->
    <script src="{{ asset("dashboard") }}/js/theme-boot.js"></script>

    <link rel="stylesheet" href="{{ asset("dashboard") }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset("dashboard") }}/vendor/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset("dashboard") }}/css/style.css" />
    <link rel="stylesheet" href="{{ asset("dashboard") }}/css/dashboard.css" />
    <link rel="stylesheet" href="{{ asset("dashboard") }}/css/responsive.css" />
  </head>

  <body data-page="index" data-base="">
    <a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>

  <!-- ============ Sidebar (desktop rail + mobile offcanvas drawer) ============ -->
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar" aria-label="Main navigation">
    <div class="sidebar-header">
      <a class="sidebar-brand" href="index">
        <img src="{{ asset("dashboard") }}/images/ui/logo.svg" alt="NovaCart" width="34" height="34" />
        <span class="brand-text">
          <strong>NovaCart</strong>
          <small>Admin Console</small>
        </span>
      </a>
      <button type="button" class="sidebar-close btn btn-icon d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close navigation">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="sidebar-body">
      <ul class="sidebar-nav" id="sidebarNav">
          <li class="nav-item"><a class="nav-link {{ Request::routeIs('vendora.index') ? 'active' : '' }}" href="{{ route('vendora.index') }}" title="Dashboard" {{ Request::routeIs('vendora.index') ? 'aria-current="page"' : '' }}>
            <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
            <span class="nav-text">Dashboard</span>

          </a></li>
          <li class="nav-item nav-group">
            <a class="nav-link nav-toggle {{ Request::routeIs('product.*') || Request::routeIs('cat.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#nav-catalog" role="button" aria-expanded="{{ Request::routeIs('product.*') || Request::routeIs('cat.*') ? 'true' : 'false' }}" aria-controls="nav-catalog" title="Catalog">
              <span class="nav-icon"><i class="fa-solid fa-box"></i></span>
              <span class="nav-text">Catalog</span>
              <span class="nav-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <div class="collapse {{ Request::routeIs('product.*') || Request::routeIs('cat.*') ? 'show' : '' }}" id="nav-catalog" data-group-label="Catalog">
              <ul class="nav-sublist">
              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('product.*') ? 'active' : '' }}" href="{{ route("product.index") }}" title="Products">
            <span class="nav-icon"><i class="fa-solid fa-box-open"></i></span>
            <span class="nav-text">Products</span>

          </a></li>
              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('cat.*') ? 'active' : '' }}" href="{{ route("cat.index") }}" title="Categories">
            <span class="nav-icon"><i class="fa-solid fa-tags"></i></span>
            <span class="nav-text">Categories</span>

          </a></li>


          </a></li>


              </ul>
            </div>
          </li>

          <li class="nav-item nav-group">
            <a class="nav-link nav-toggle {{ Request::routeIs('customer.*') || Request::routeIs('dash.show.message') ? 'active' : '' }}" data-bs-toggle="collapse" href="#nav-customers-group" role="button" aria-expanded="{{ Request::routeIs('customer.*') || Request::routeIs('dash.show.message') ? 'true' : 'false' }}" aria-controls="nav-customers-group" title="Customers">
              <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
              <span class="nav-text">Customers</span>
              <span class="nav-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <div class="collapse {{ Request::routeIs('customer.*') || Request::routeIs('dash.show.message') ? 'show' : '' }}" id="nav-customers-group" data-group-label="Customers">
              <ul class="nav-sublist">
              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('customer.index') ? 'active' : '' }}" href="{{ route("customer.index") }}" title="All Customers">
            <span class="nav-icon"><i class="fa-solid fa-user-group"></i></span>
            <span class="nav-text">All Customers</span>

          </a></li>

              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('dash.show.message') ? 'active' : '' }}" href="{{ route("dash.show.message") }}" title="Messages">
            <span class="nav-icon"><i class="fa-regular fa-comment-dots"></i></span>
            <span class="nav-text">Messages</span>
            @if($unreed_ms)

                <span class="nav-badge badge rounded-pill bg-danger-subtle text-danger-emphasis unseen_ms" data-nav-badge="messages">{{ $unreed_ms }}</span>
            @endif
          </a></li>
              </ul>
            </div>
          </li>


          <li class="nav-item nav-group">
            <a class="nav-link nav-toggle {{ Request::routeIs('admin.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#nav-administration" role="button" aria-expanded="{{ Request::routeIs('admin.*') ? 'true' : 'false' }}" aria-controls="nav-administration" title="Administration">
              <span class="nav-icon"><i class="fa-solid fa-user-shield"></i></span>
              <span class="nav-text">Administration</span>
              <span class="nav-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <div class="collapse {{ Request::routeIs('admin.*') ? 'show' : '' }}" id="nav-administration" data-group-label="Administration">
              <ul class="nav-sublist">
              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('admin.index') || Request::routeIs('admin.edit') ? 'active' : '' }}" href="{{ route("admin.index") }}" title="Staff">
            <span class="nav-icon"><i class="fa-solid fa-users-gear"></i></span>
            <span class="nav-text">Staff</span>

          </a></li>

              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('admin.notify') || Request::routeIs('admin.edit') ? 'active' : '' }}" href="{{ route("admin.notify") }}" title="Staff">
            <span class="nav-icon"><i class="fa-solid fa-users-gear"></i></span>
            <span class="nav-text">Notification</span>
            @if($unreed_ms)

                <span class="nav-badge badge rounded-pill bg-danger-subtle text-danger-emphasis unseen_ms" data-nav-badge="messages">{{ $unreed_ms }}</span>
            @endif

          </a></li>



              </ul>
            </div>
          </li>
          <li class="nav-item nav-group">
            <a class="nav-link nav-toggle {{ Request::routeIs('view.myProfile') ? 'active' : '' }}" data-bs-toggle="collapse" href="#nav-system" role="button" aria-expanded="{{ Request::routeIs('view.myProfile') ? 'true' : 'false' }}" aria-controls="nav-system" title="System">
              <span class="nav-icon"><i class="fa-solid fa-gear"></i></span>
              <span class="nav-text">System</span>
              <span class="nav-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <div class="collapse {{ Request::routeIs('view.myProfile') ? 'show' : '' }}" id="nav-system" data-group-label="System">
              <ul class="nav-sublist">

              <li class="nav-subitem"><a class="nav-link {{ Request::routeIs('view.myProfile') ? 'active' : '' }}" href="{{ route("view.myProfile") }}" title="Profile">
            <span class="nav-icon"><i class="fa-solid fa-id-badge"></i></span>
            <span class="nav-text">Profile</span>

          </a></li>
              <li class="nav-subitem"><a class="nav-link" href="#" data-action="logout" title="Logout">
              <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
              <span class="nav-text">Logout</span>
            </a></li>
              </ul>
            </div>
          </li>
      </ul>
    </div>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <span class="avatar avatar-sm avatar-brand">ME</span>
        <span class="sidebar-user-meta">
          <strong>Mahmoud Elhadad</strong>
          <small>Super Admin</small>
        </span>
        <a class="btn btn-icon btn-icon-xs sidebar-user-link" href="{{ route("view.myProfile") }}" title="My profile" aria-label="My profile">
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
      </div>
    </div>
  </aside>
  <div class="sidebar-backdrop" data-sidebar-backdrop></div>

    <div class="main-wrapper">
