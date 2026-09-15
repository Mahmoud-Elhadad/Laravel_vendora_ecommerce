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
                        <h2>Become a member</h2>
                        <p>It takes less than a minute. Enjoy exclusive perks from day one.</p>
                        <ul><li><i class="fa-solid fa-tag"></i> Member-only discounts & early access</li><li><i class="fa-solid fa-bell"></i> Order updates and restock alerts</li><li><i class="fa-solid fa-gift"></i> Birthday surprises and rewards</li></ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="auth-body">
                      <a class="auth-logo d-inline-block" href="/"><img src="{{ asset("ecomm") }}/images/ui/logo.svg" alt="Vendora"></a>
                      <h1>Create your account</h1>
                      <p class="auth-sub">Join Vendora and unlock member-only deals.</p>

                      <form action="{{ route("ecomm.store.user") }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                @error("first_name")
                                <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="signup-first">First name</label>
                                <input type="text" class="form-control" id="signup-first" name="first_name" placeholder="first name">

                            </div>
                            <div class="col-md-6 mb-3">
                                @error("last_name")
                                <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                    <label class="form-label" for="signup-last">Last name</label>
                                    <input type="text" class="form-control" id="signup-last" name="last_name" placeholder="last name">

                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            @error("email")
                                <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="signup-email">Email address</label>
                                <input type="email" class="form-control" id="signup-email" name="email" placeholder="you@example.com">

                        </div>
                        <div class="col-12 mb-3">
                            @error("phone")
                                <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="signup-phone">Phone number </label>
                                <input type="tel" class="form-control" id="signup-phone" name="phone" placeholder="phone number">

                        </div>
                         <div class="col-12 mb-3">

                                <label class="form-label" for="clientImg">Your Image</label>
                                <input class="form-control" id="clientImg"  name="img" type="file" />

                        </div>
                        <div class="col-12 mb-3">
                            @error("password")
                                <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="signup-password">Password</label>
                                <div class="password-field">
                                    <input type="password" class="form-control" id="signup-password" name="password" placeholder="••••••••">


                                </div>
                        </div>
                                <div class="strength-meter"><div class="sm-fill"></div></div>
                                <div class="strength-text mb-3"></div>
                                <div class="col-12 mb-3">
                        <label class="form-label" for="signup-confirm">Confirm password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="signup-confirm" name="confirm" placeholder="••••••••">


                        </div>
                        </div>

                                <button type="submit" class="btn btn-primary-v btn-lg btn-block"><i class="fa-solid fa-user-plus"></i> Create account</button>
                      </form>


                      <p class="text-center mb-0 mt-3">Already have an account? <a href="{{ route("ecomm.show.loginPage") }}" class="fw-semibold">Sign in</a></p>
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
