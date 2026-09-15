
<x-navbar />

  <main id="main-content">
      <div id="home-page">
      <!-- Hero slider -->
      <div class="swiper hero-slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="hero-slide">
              <div class="hero-bg"><img src="{{ asset("ecomm") }}/images/banners/hero-1.svg" alt="Headphones deal"></div>
              <div class="hero-overlay"></div>
              <div class="container">
                <div class="hero-content" data-aos="fade-right">
                  <span class="hero-eyebrow"><i class="fa-solid fa-bolt"></i> Limited-time offer</span>
                  <h1>Sound that moves you</h1>
                  <p>Premium audio, wearables and tech essentials — up to 40% off this week only.</p>

                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="hero-slide">
              <div class="hero-bg"><img src="{{ asset("ecomm") }}/images/banners/hero-2.svg" alt="Watches collection"></div>
              <div class="hero-overlay"></div>
              <div class="container">
                <div class="hero-content" data-aos="fade-right">
                  <span class="hero-eyebrow"><i class="fa-solid fa-bolt"></i> New season, new style</span>
                  <h1>Timepieces & fashion</h1>
                  <p>Discover curated watches, apparel and accessories from top-rated brands.</p>

                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="hero-slide">
              <div class="hero-bg"><img src="{{ asset("ecomm") }}/images/banners/hero-3.svg" alt="Sneakers"></div>
              <div class="hero-overlay"></div>
              <div class="container">
                <div class="hero-content" data-aos="fade-right">
                  <span class="hero-eyebrow"><i class="fa-solid fa-bolt"></i> Trending now</span>
                  <h1>Step up your sneaker game</h1>
                  <p>Performance footwear and streetwear staples, delivered fast to your door.</p>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>

      <section class="feature-strip border-bottom">
        <div class="container">
          <div class="row g-3">
            <div class="col-6 col-lg-3">
              <div class="feature-item" data-aos="fade-up">
                <span class="fi-ico"><i class="fa-solid fa-truck-fast"></i></span>
                <div><h6>Free shipping</h6><p>On all orders over $75</p></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="feature-item" data-aos="fade-up">
                <span class="fi-ico"><i class="fa-solid fa-shield-halved"></i></span>
                <div><h6>Secure payment</h6><p>100% protected checkout</p></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="feature-item" data-aos="fade-up">
                <span class="fi-ico"><i class="fa-solid fa-rotate-left"></i></span>
                <div><h6>30-day returns</h6><p>Hassle-free refunds</p></div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="feature-item" data-aos="fade-up">
                <span class="fi-ico"><i class="fa-solid fa-headset"></i></span>
                <div><h6>24/7 support</h6><p>Real humans, always on</p></div>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Categories -->
      <section class="section">
        <div class="container">
          <div class="section-head">
          <div class="row align-items-end g-3">
            <div class="col">
              <span class="eyebrow">Browse the catalog</span>
              <h2>Shop by category</h2>
              <p>Find exactly what you are looking for across nine curated departments.</p>
            </div>
            <div class="col-auto d-none d-sm-block"><a class="head-link" href="{{ route('ecomm.category.page') }}">All categories <i class="fa-solid fa-arrow-right"></i></a></div>
          </div>
        </div>
          <div class="row g-3 g-md-4" id="home-categories">

            @foreach ($all_categories as $cat)

                <div class="col-6 col-md-4 col-lg-2">
                <a class="cat-card" href="{{ route('ecomm.category.page', ['cat' => $cat->id]) }}">
                    <div class="cc-img"><img src="{{ asset('storage/images/cats/'.$cat->img) }}" alt="{{ $cat->name }}" loading="lazy"></div>
                    <h6>{{ $cat->name }}</h6>
                    <div class="cc-count">{{ $cat->num_products }} {{ Str::plural('item', $cat->num_products) }}</div>
                </a>
                </div>
            @endforeach

          </div>
        </div>
      </section>

      <section class="section section-gray">
            <div class="container carousel-wrap">
            <div class="section-head">
            <div class="row align-items-end g-3">
                <div class="col">
                <span class="eyebrow">Just landed</span>
                <h2>New arrivals</h2>

                </div>
                <div class="col-auto d-none d-sm-block"><div class="carousel-nav">
                <button type="button" class="carousel-prev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
                <button type="button" class="carousel-next" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
                </div></div>
            </div>
            </div>
            <div class="swiper product-carousel" data-source="new" data-count="10">
                <div class="swiper-wrapper">
                    @foreach ($products as $product)
                        @include("Ecommerce.layout.products")
                    @endforeach
                </div>
            </div>
            </div>
      </section>

      >

      <!-- Deal of the day -->
      <section class="section section-gray">
        <div class="container">
          <div class="deal-split" data-aos="fade-up">
            <div class="ds-media"><img src="{{ asset("ecomm") }}/images/banners/deal.svg" alt="Deal of the day"></div>
            <div class="ds-body">
              <span class="eyebrow"><i class="fa-solid fa-fire text-danger"></i> Deal of the day</span>
              <h3>Mega tech sale ends soon</h3>
              <p class="text-muted">Grab best-selling electronics and accessories at their lowest price of the season. Quantities are limited.</p>
              <div class="countdown auto my-4" aria-label="Deal countdown"><!-- rendered by main.js --></div>
              <div><a href="{{ route("ecomm.shop.page") }}" class="btn btn-primary-v btn-lg"><i class="fa-solid fa-bolt"></i> Shop the deal</a></div>
            </div>
          </div>
        </div>
      </section>

    <section class="section">
            <div class="container carousel-wrap">
            <div class="section-head">
            <div class="row align-items-end g-3">
                <div class="col">
                <span class="eyebrow">Customer favourites</span>
                <h2>Best sellers</h2>
                <p>The products our shoppers keep coming back for.</p>
                </div>
                <div class="col-auto d-none d-sm-block"><div class="carousel-nav">
                <button type="button" class="carousel-prev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
                <button type="button" class="carousel-next" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
                </div></div>
            </div>
            </div>
            <div class="swiper product-carousel" data-source="best" data-count="10">
                <div class="swiper-wrapper">

                    @foreach($best_products_sold as $product)

                        @include("Ecommerce.layout.best_seller_products")
                    @endforeach

                </div>
            </div>
            </div>
    </section>




      <!-- Testimonials -->
      <section class="section section-gray">
        <div class="container">
          <div class="section-head">
          <div class="row align-items-end g-3">
            <div class="col">
              <span class="eyebrow">Social proof</span>
              <h2>What our customers say</h2>
              <p>Thousands of five-star reviews from happy shoppers.</p>
            </div>
            <div class="col-auto d-none d-sm-block"></div>
          </div>
        </div>
          <div class="swiper" id="home-testimonials">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 5 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
                  <p class="mt-3">"The quality blew me away. My headphones arrived in two days, perfectly packaged, and the sound is incredible. Vendora is now my go-to store."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-1.svg" alt="Emily Carter"><div><h6>Emily Carter</h6><span>Verified Buyer</span></div></div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 5 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
                  <p class="mt-3">"I have ordered over a dozen times and every experience has been smooth. Easy returns, fair prices and genuinely helpful support."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-2.svg" alt="James Osei"><div><h6>James Osei</h6><span>Loyal Customer</span></div></div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 4 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star off"></i></span>
                  <p class="mt-3">"Great selection and fast shipping. The website makes it easy to compare products, and checkout takes seconds. Highly recommend."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-3.svg" alt="Maria Lopez"><div><h6>Maria Lopez</h6><span>Verified Buyer</span></div></div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 5 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
                  <p class="mt-3">"Bought a laptop and a smartwatch here. Both authentic, both at better prices than anywhere else I checked. Five stars."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-4.svg" alt="Liam Novak"><div><h6>Liam Novak</h6><span>Tech Enthusiast</span></div></div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 5 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
                  <p class="mt-3">"The clothing fits true to size and the fabric feels premium. Returns were painless when I needed to swap a size."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-5.svg" alt="Aisha Bello"><div><h6>Aisha Bello</h6><span>Verified Buyer</span></div></div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <i class="fa-solid fa-quote-right quote-ico"></i>
                  <span class="stars" aria-label="Rated 4 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star off"></i></span>
                  <p class="mt-3">"Solid sports gear at honest prices. My dumbbell set is rugged and space-saving — exactly what my home gym needed."</p>
                  <div class="tc-author"><img src="{{ asset("ecomm") }}/images/users/user-6.svg" alt="Tom Fischer"><div><h6>Tom Fischer</h6><span>Fitness Coach</span></div></div>
                </div>
              </div>
            </div>
            <div class="swiper-pagination mt-4"></div>
          </div>
        </div>
      </section>


      </div>
  </main>

<x-footer />
