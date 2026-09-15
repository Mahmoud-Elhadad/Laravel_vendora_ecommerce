<x-navbar />


      <main id="main-content">
      <section class="section-gray section-sm">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
              <div class="auth-card">
                <div class="row g-0">
                  <div class="col-lg-5 d-none d-lg-flex">
                    <div class="auth-side">
                      <div class="as-inner">
                        <h2>Sign in to Vendora</h2>
                        <p>Access your orders, wishlist and personalised recommendations in one place.</p>
                        <ul><li><i class="fa-solid fa-truck-fast"></i> Track your orders in real time</li><li><i class="fa-solid fa-heart"></i> Sync your wishlist across devices</li><li><i class="fa-solid fa-bolt"></i> Faster checkout with saved details</li></ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="auth-body">
                      <a class="auth-logo d-inline-block" href="/"><img src="{{ asset("ecomm") }}/images/ui/logo.svg" alt="Vendora"></a>
                      <h1>Welcome back</h1>
                      <p class="auth-sub">Sign in to continue shopping with Vendora.</p>

                      <form action="{{ route("ecomm.check.login") }}" method="post">
                        @csrf

                        @if(session("unvalid"))
                            <p class="alert alert-danger">{{ session("unvalid") }}</p>
                        @endif

                                <div class="col-12 mb-3">
                                    @error("email")
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                        <label class="form-label" for="signin-email">Email address</label>
                        <input type="email" class="form-control" id="signin-email" name="email" placeholder="you@example.com" >
                        <div class="invalid-feedback"></div>
                        </div>
                                <div class="col-12 mb-3">
                                    @error("password")
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                        <label class="form-label" for="signin-password">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="signin-password" name="password" placeholder="••••••••" >

                            <div class="invalid-feedback"></div>
                        </div>
                        </div>
                                <button type="submit" class="btn btn-primary-v btn-lg btn-block"><i class="fa-solid fa-right-to-bracket"></i> Sign in</button>
                      </form>

                      <p class="text-center mb-0 mt-3">Don't have an account? <a href="{{ route("ecomm.show.registrPage") }}" class="fw-semibold">Create one free</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  </main>

<x-footer />
