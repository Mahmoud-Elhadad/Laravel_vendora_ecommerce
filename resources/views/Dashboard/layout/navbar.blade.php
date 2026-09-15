
  <!-- ============ Top navbar ============ -->
  <header class="topbar">
    <div class="topbar-start">
      <button class="btn btn-icon sidebar-toggle" type="button" data-sidebar-toggle aria-label="Toggle navigation" title="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
      </button>


    </div>

    <div class="topbar-end">
      <button class="btn btn-icon" type="button" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">
        <i class="fa-solid fa-moon theme-icon-dark"></i>
        <i class="fa-solid fa-sun theme-icon-light"></i>
      </button>

      <div class="dropdown">
        <button class="btn btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Quick actions" title="Quick actions">
          <i class="fa-solid fa-bolt"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-2 quick-actions">
          <p class="dropdown-header px-2">Quick actions</p>
          <div class="row g-2">
            <div class="col-6">
              <a class="quick-action" href="{{ route("product.create") }}">
                <span class="quick-action-icon bg-primary-subtle text-primary"><i class="fa-solid fa-box-open"></i></span>
                <span>New product</span>
              </a>
            </div>

            <div class="col-6">
              <a class="quick-action" href="{{ route("cat.create") }}">
                <span class="quick-action-icon bg-info-subtle text-info"><i class="fa-solid fa-tags"></i></span>
                <span>New Category</span>
              </a>
            </div>

            <div class="col-6">
              <a class="quick-action" href="{{ route("customer.create") }}">
                <span class="quick-action-icon bg-info-subtle text-info"><i class="fa-solid fa-user-group"></i></span>
                <span>New customer</span>
              </a>
            </div>

            <div class="col-6">
              <a class="quick-action" href="{{ route("admin.create") }}">
                <span class="quick-action-icon bg-info-subtle text-info"><i class="fa-solid fa-users-gear"></i></span>
                <span>New Staff</span>
              </a>
            </div>


          </div>
        </div>
      </div>


        <a href="{{ route("dash.show.message") }}" class="btn btn-icon has-dot">
          <i class="fa-regular fa-comment-dots"></i>
        </a>




      <div class="vr mx-1 d-none d-sm-block"></div>

      @if(Auth::guard("dashboard")->check())

        <div class="dropdown">
            <button class="btn btn-profile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar avatar-sm avatar-brand">{{ Auth::guard("dashboard")->user()->name ? collect(explode(' ', Auth::guard("dashboard")->user()->name))->map(fn($w) => $w[0])->implode('') : '' }}</span>
            <span class="btn-profile-meta d-none d-md-flex">
                <strong>{{ Auth::guard("dashboard")->user()->name }}</strong>
                <small class="text-capitalize">{{ Auth::guard("dashboard")->user()->role }}</small>
            </span>
            <i class="fa-solid fa-chevron-down ms-1 small opacity-50"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
            <li class="dropdown-user">
                <span class="avatar avatar-sm avatar-brand">{{ Auth::guard("dashboard")->user()->name ? collect(explode(' ', Auth::guard("dashboard")->user()->name))->map(fn($w) => $w[0])->implode('') : '' }}</span>
                <span>
                <strong>{{ Auth::guard("dashboard")->user()->name }}</strong>
                <small class="d-block text-body-secondary">{{ Auth::guard("dashboard")->user()->email }}</small>
                </span>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item rounded-2" href="{{ route("view.myProfile") }}"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>


            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item rounded-2 text-danger" href="#" data-action="logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
            </ul>
        </div>

      @endif

    </div>
  </header>

  @yield('body')
