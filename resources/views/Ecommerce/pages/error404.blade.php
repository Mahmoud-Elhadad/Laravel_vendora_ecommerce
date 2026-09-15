<x-navbar />


 <main id="main-content">
      <section class="error-page">
        <div class="container text-center">
          <img class="error-illus" src="{{ asset("ecomm") }}/images/ui/404.svg" alt="Page not found illustration" data-aos="zoom-in">
          <h1>Oops — this page took a detour</h1>
          <p class="text-muted mx-auto" style="max-width:520px">The page you are looking for was moved, removed, renamed or might never have existed. Let's get you back to something useful.</p>
          <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
            <a href="/" class="btn btn-primary-v btn-lg"><i class="fa-solid fa-house"></i> Back to home</a>
            <a href="{{ route('ecomm.shop.page') }}" class="btn btn-outline-v btn-lg"><i class="fa-solid fa-bag-shopping"></i> Browse shop</a>
          </div>
          <div class="mt-5 pt-4 border-top">
            <p class="text-muted mb-3">Popular destinations</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="{{ route("ecomm.FAQ.page") }}" class="btn btn-sm btn-soft-v">Help center</a>
              <a href="{{ route("show.message") }}" class="btn btn-sm btn-soft-v">Contact us</a>
            </div>
          </div>
        </div>
      </section>
  </main>


<x-footer />
