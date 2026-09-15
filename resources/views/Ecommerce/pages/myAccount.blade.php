
<x-navbar />



  <section class="page-title-bar">
    <div class="container">
      <h1 id="page-title-text">My Account</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-v">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </nav>
    </div>
  </section>

  <main id="main-content">
      <div id="account-page">
      <section class="section-sm">
        <div class="container">
          <div class="row g-4">
            <div class="col-lg-3"><div class="account-nav">
        <div class="an-head">
          <img  src="{{ asset("storage/images/clients/".Auth::guard("ecomm")->user()->image) }}" alt="{{ Auth::guard("ecomm")->user()->first_name }} {{ Auth::guard("ecomm")->user()->last_name }}">
          <div><h6>{{ Auth::guard("ecomm")->user()->first_name }} {{ Auth::guard("ecomm")->user()->last_name }}</h6><span>{{ Auth::guard("ecomm")->user()->email }}</span></div>
        </div>
        <ul>
          <li><a href="{{ route("ecomm.show.accountPage") }}" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
          <li><a href="{{ route("ecomm.whilistPage") }}" class=""><i class="fa-solid fa-heart"></i> Wishlist</a></li>
          <li><a href="{{ route("show.cart") }}" class=""><i class="fa-solid fa-bag-shopping"></i> Cart</a></li>
          <li><a href="{{ route("ecomm.showAndEdit.profilePage") }}" class=""><i class="fa-solid fa-user"></i> Profile settings</a></li>
          <li><a href="{{ route('ecomm.logout') }}" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></li>
        </ul>
      </div></div>
            <div class="col-lg-9">
        <div class="checkout-card mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <h2 class="h5 mb-1">Welcome back 👋</h2>
              <p class="text-muted mb-0" >Member since {{ Auth::guard("ecomm")->user()->created_at }}</p>
            </div>
            <a href="{{ route("ecomm.showAndEdit.profilePage") }}" class="btn btn-outline-v btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit profile</a>
          </div>
        </div>

        <div class="row g-3 mb-4">

          <div class="col-6 col-lg-3"><div class="stat-card"><span class="sc-ico red"><i class="fa-regular fa-heart"></i></span><div><h3>{{ $num_whilists_in_account }}</h3><p>Wishlist items</p></div></div></div>
          <div class="col-6 col-lg-3"><div class="stat-card"><span class="sc-ico orange"><i class="fa-solid fa-bag-shopping"></i></span><div><h3>{{ $num_carts_in_account }}</h3><p>In cart</p></div></div></div>
          <div class="col-6 col-lg-3"><div class="stat-card"><span class="sc-ico green"><i class="fa-solid fa-wallet"></i></span><div><h3>${{ $subTotal }}</h3><p>Lifetime spend</p></div></div></div>
        </div>

      </div>
          </div>
        </div>
      </section>
      </div>
  </main>

<x-footer />
