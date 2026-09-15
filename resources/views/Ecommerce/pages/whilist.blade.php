<x-navbar />

<section class="page-title-bar">
    <div class="container">
        <h1 id="page-title-text">My Wishlist</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-v">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ecomm.show.accountPage') }}">Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
            </ol>
        </nav>
    </div>
</section>

<main id="main-content">
    <section class="section-sm">
        <div class="container">

            {{-- Empty state --}}
            <div id="wishlist-empty" class="empty-state{{ $whilists->isNotEmpty() ? ' d-none' : '' }}">
                <img src="{{ asset('ecomm') }}/images/ui/empty-wishlist.svg" alt="Empty wishlist">
                <h4>Your wishlist is empty</h4>
                <p>Save the items you love by tapping the heart icon on any product, and they will show up here.</p>
                <a href="{{ route("ecomm.shop.page") }}" class="btn btn-primary-v btn-lg"><i class="fa-solid fa-bag-shopping"></i> Explore Products</a>
            </div>

            @if(session('whilist'))
                <p class="alert alert-success">{{ session('whilist') }}</p>
            @endif

            {{-- Wishlist products --}}
            <div id="wishlist-filled"{{ $whilists->isEmpty() ? ' style="display:none"' : '' }}>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                    <h2 class="h4 mb-0"> Your wishlists
                            @if ($whilists->isNotEmpty())
                                <span class="text-muted fw-normal">{{ $whilists->count() }} item{{ $whilists->count() === 1 ? '' : 's' }}</span>
                            @endif
                    </h2>
                    <div id="wishlist-actions" class="d-flex gap-2">
                        <form action="{{ route("ecomm.store.AllWhilist.cart") }}" method="post">
                            @csrf
                            <button type="submit" id="add-all-to-cart" class="btn btn-primary-v btn-sm"><i class="fa-solid fa-cart-plus"></i> Add all to cart</button>
                        </form>

                        <button type="button" id="clear-wishlist" class="btn btn-outline-v btn-sm" data-bs-toggle="modal" data-bs-target="#deleteWhilistModal"><i class="fa-regular fa-trash-can"></i> Clear wishlist</button>

                        @include("Ecommerce.layout.modal_deleteAllWhilists")

                    </div>
                </div>

                <div class="products-grid" id="wishlist-grid">
                    @foreach($whilists as $whilist)

                        <article class="product-card fade-in-up" data-id="{{ $whilist->product->id }}" data-stock="{{ $whilist->product->count }}" data-discount="{{ $whilist->product->discount }}">
                            <div class="pc-media">
                                <a class="pc-link" href="{{ route('product.details', $whilist->product_id) }}" aria-label="{{ $whilist->product->name }}">
                                    <img class="pc-img" src="{{ asset('storage/images/products/' . $whilist->product->image[0]['name']) }}" alt="{{ $whilist->product->name }}" loading="lazy">
                                </a>
                                <div class="pc-actions">
                                    <button type="button" class="wishlist-btn active" product_id="{{ $whilist->product->id }}" data-id="{{ $whilist->product->id }}" title="Remove from wishlist" aria-label="Remove from wishlist">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                    <button type="button" class="quickview-btn" data-id="{{ $whilist->product->id }}" data-stock="{{ $whilist->product->count }}" title="Quick view" aria-label="Quick view"><i class="fa-regular fa-eye"></i></button>
                                </div>
                                <div class="pc-quickview">
                                    <button type="button" class="btn btn-dark-v btn-sm btn-block quickview-btn" data-id="{{ $whilist->product->id }}" data-stock="{{ $whilist->product->count }}"><i class="fa-regular fa-eye"></i> Quick view</button>
                                </div>
                            </div>
                             <div class="pc-body">
                                <div class="pc-cat">{{ $whilist->product->cat->name }}</div>
                                <h3 class="pc-title"><a href="{{ route("product.details" , $whilist->product_id) }}"><span class="clamp-2">{{ $whilist->product->name }}</span></a></h3>
                                <div class="pc-rating rating-line">
                                    <span class="stars" aria-label="Rated 4.8 out of 5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span><span class="count">(96)</span>
                                </div>
                                <p class="pc-desc">{{ $whilist->product->description }}</p>

                                <div class="pc-price">
                                    <span class="now">${{ $whilist->product->price - ($whilist->product->price * $whilist->product->discount / 100) }}</span><span class="old">${{ $whilist->product->price }}</span><span class="off">-{{ $whilist->product->discount }}%</span>
                                </div>
                                @if(Auth::guard("ecomm")->check())

                                    <button product_id="{{ $whilist->product->id }}" data-stock="{{ $whilist->product->count }}" data-id="{{ $whilist->product->id }}" class="btn btn-primary-v btn-block pc-addcart qv-add"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                                @else
                                    <a href="{{ route("ecomm.show.loginPage") }}" class="btn btn-primary-v btn-block"><i class="fa-solid fa-cart-plus"></i> Add to Cart</a>

                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
</main>

<x-footer />
