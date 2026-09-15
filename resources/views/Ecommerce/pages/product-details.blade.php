<x-navbar />



  <main id="main-content">
      <div class="breadcrumb-light">
        <div class="container">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item"><a href="shop.html">Shop</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product</li>
            </ol>
          </nav>
        </div>
      </div>
      <section class="section-sm">
        <div class="container">
          <div id="product-details" data-discount="{{ $product->discount }}">
            <div class="row g-4 g-lg-5">
              <div class="col-lg-6">
                <div class="pd-gallery">
                  <div class="pd-main-image" id="pd-zoom">
                    <img src="{{ asset('storage/images/products/' . $product->image[0]['name']) }}" alt="{{ $product->name }}" id="pd-main-img">
                  </div>
                  <div class="pd-thumbs">
                    @foreach ($product->image as $img)

                        <div class="thumb active" data-src="{{ asset("storage/images/products/" . $img->name) }}">
                        <img src="{{ asset("storage/images/products/" . $img->name) }}" alt="{{ $product->name }} view 1">
                        </div>
                    @endforeach

                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="pd-info">
                  <div class="pd-cat">{{ $product->cat->name }}</div>
                  <h1>{{ $product->name }}</h1>
                  <div class="pd-meta">
                    <span class="rating-line">
                      <span class="stars" aria-label="Rated 4.7 out of 5">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                      </span>

                    </span>
                    <span class="m-item"><i class="fa-solid fa-barcode"></i> SKU: <strong>ELE-{{ $product->id }}</strong></span>

                  </div>
                  <div class="pd-price">
                    <span class="now">${{ $product->price - ($product->price * $product->discount / 100) }}</span>
                    <span class="old">${{ $product->price }}</span>
                    <span class="save">Save {{ $product->discount }}%</span>
                  </div>

                  <p class="pd-short">{{ $product->description }}</p>

                  <div class="pd-option">
                    <div class="opt-label">Quantity</div>
                    <div class="qty-selector" id="pd-qty">
                      <button type="button" class="qty-minus">−</button>
                      <input type="number" value="1" min="1" max="{{ $product->count }}" id="pd-qty-input" aria-label="Quantity">
                      <button type="button" class="qty-plus">+</button>
                    </div>
                  </div>
                  <div class="pd-actions">
                    @if (Auth::guard("ecomm")->check())

                    <button data-id="{{ $product->id }}" product_id="{{ $product->id }}" class="btn btn-primary-v btn-lg qv-add" id="pd-add-cart"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                    @else

                    <a href="{{ route("ecomm.show.loginPage") }}" class="btn btn-primary-v btn-lg"><i class="fa-solid fa-cart-plus"></i> Add to Cart</a>
                    @endif

                  </div>
                  <div class="pd-extras">
                    @if (Auth::guard("ecomm")->check())
                    @php $isWishlisted = in_array($product->id, $wishlistedIds ?? []); @endphp
                    <button class="wishlist-btn{{ $isWishlisted ? ' active' : '' }}" product_id="{{ $product->id }}" data-id="{{ $product->id }}" id="pd-wishlist">
                        <i class="{{ $isWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        {{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                    </button>
                    @else
                    <a href="{{ route("ecomm.show.loginPage") }}"><i class="fa-regular fa-heart"></i> Add to Wishlist</a>
                    @endif
                  </div>
                  <div class="pd-guarantee">
                    <div class="g-item"><i class="fa-solid fa-truck-fast"></i> Free shipping on orders over $75</div>
                    <div class="g-item"><i class="fa-solid fa-rotate-left"></i> 30-day hassle-free returns</div>
                    <div class="g-item"><i class="fa-solid fa-shield-halved"></i> 2-year warranty & secure checkout</div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      @if(count($relatedProducts) != 0)



        <section class="section-sm pt-0">
            <div class="container">
            <div class="section-head"><h2>Related products</h2></div>
            <div id="related-products" class="products-grid">
                @foreach ($relatedProducts as $product)

                    <article class="product-card fade-in-up">
                    <div class="pc-media">
                        <a href="{{ route("product.details" , $product->id) }}" aria-label="{{ $product->name }}">
                        <img src="{{ asset("storage/images/products/" . $product->image[0]['name']) }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        <div class="pc-badges"><span class="badge-v badge-hot">HOT</span></div>
                        <div class="pc-actions">
                        @if (Auth::guard("ecomm")->check())
                            @php $isRelatedWishlisted = in_array($product->id, $wishlistedIds ?? []); @endphp
                            <button class="wishlist-btn{{ $isRelatedWishlisted ? ' active' : '' }}" product_id="{{ $product->id }}" data-id="{{ $product->id }}" title="Add to wishlist" aria-label="Add to wishlist">
                                <i class="{{ $isRelatedWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                            </button>
                        @else
                            <a href="{{ route("ecomm.show.loginPage") }}" title="Add to wishlist"><i class="fa-regular fa-heart"></i></a>
                        @endif

                        <button class="quickview-btn" title="Quick view" aria-label="Quick view"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        <div class="pc-quickview"><button class="btn btn-dark-v btn-sm btn-block quickview-btn"><i class="fa-regular fa-eye"></i> Quick view</button></div>
                    </div>
                    <div class="pc-body">
                        <div class="pc-cat">{{ $product->cat->name }}</div>
                        <h3 class="pc-title"><a href="{{ route("product.details" , $product->id) }}"><span class="clamp-2">{{ $product->name }}</span></a></h3>
                        <div class="pc-rating rating-line"><span class="stars" aria-label="Rated 4.4 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star off"></i></span></div>
                        <p class="pc-desc">Waterproof 360° speaker with deep bass and 20 hours of playtime.</p>

                        <div class="pc-price"><span class="now">${{ $product->price - ($product->price  * $product->discount/ 100) }}</span><span class="old">${{ $product->price }}</span><span class="off">-{{ $product->discount }}%</span></div>
                        @if (Auth::guard("ecomm")->check())

                        <button class="btn btn-primary-v btn-block pc-addcart add-to-cart qv-add"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                        @else

                        <a href="{{ route("dash.loginForm") }}" class="btn btn-primary-v btn-block"><i class="fa-solid fa-cart-plus"></i> Add to Cart</a>
                        @endif
                    </div>
                    </article>

                @endforeach

            </div>
            </div>
        </section>

      @endif


  </main>


<x-footer />
