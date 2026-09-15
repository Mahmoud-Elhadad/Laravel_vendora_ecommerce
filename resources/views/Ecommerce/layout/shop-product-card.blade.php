@php
    $discountedPrice = $product->price - ($product->price * $product->discount / 100);
    $isWishlisted    = in_array($product->id, $wishlistedIds ?? []);
@endphp

<article class="product-card fade-in-up" data-id="{{ $product->id }}" data-stock="{{ $product->count }}" data-discount="{{ $product->discount }}">
    <div class="pc-media">
        <a href="{{ route('product.details', $product->id) }}" aria-label="{{ $product->name }}">
            <img src="{{ asset('storage/images/products/' . $product->image[0]['name']) }}"
                 alt="{{ $product->name }}"
                 loading="lazy">
        </a>

        @if ($product->discount > 0)
            <div class="pc-badges">
                <span class="badge-v badge-sale">-{{ $product->discount }}%</span>
            </div>
        @endif

        <div class="pc-actions">
            @if (Auth::guard('ecomm')->check())
                <button product_id="{{ $product->id }}"
                        class="wishlist-btn{{ $isWishlisted ? ' active' : '' }}"
                        data-id="{{ $product->id }}"
                        title="Add to wishlist"
                        aria-label="Add to wishlist">
                    <i class="{{ $isWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                </button>
            @else
                <a href="{{ route('ecomm.show.loginPage') }}" title="Add to wishlist" aria-label="Add to wishlist">
                    <i class="fa-regular fa-heart"></i>
                </a>
            @endif

            <button class="quickview-btn"
                    data-id="{{ $product->id }}"
                    data-stock="{{ $product->count }}"
                    title="Quick view"
                    aria-label="Quick view">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>

        <div class="pc-quickview">
            <button class="btn btn-dark-v btn-sm btn-block quickview-btn"
                    data-id="{{ $product->id }}"
                    data-stock="{{ $product->count }}">
                <i class="fa-regular fa-eye"></i> Quick view
            </button>
        </div>
    </div>

    <div class="pc-body">
        <div class="pc-cat">{{ $product->cat->name }}</div>

        <h3 class="pc-title">
            <a href="{{ route('product.details', $product->id) }}">
                <span class="clamp-2">{{ $product->name }}</span>
            </a>
        </h3>

        <div class="pc-rating rating-line">
            <span class="stars" aria-label="Rated 5 out of 5">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </span>
        </div>

        <p class="pc-desc">{{ $product->description }}</p>

        <div class="pc-price">
            <span class="now">${{ number_format($discountedPrice, 2) }}</span>
            @if ($product->discount > 0)
                <span class="old">${{ number_format($product->price, 2) }}</span>
                <span class="off">-{{ $product->discount }}%</span>
            @endif
        </div>

        @if ($product->count > 0)
            @if (Auth::guard('ecomm')->check())
                <button product_id="{{ $product->id }}"
                        data-stock="{{ $product->count }}"
                        data-id="{{ $product->id }}"
                        class="btn btn-primary-v btn-block pc-addcart qv-add">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
            @else
                <a href="{{ route('ecomm.show.loginPage') }}" class="btn btn-primary-v btn-block">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </a>
            @endif
        @else
            <button class="btn btn-secondary btn-block" disabled>
                <i class="fa-solid fa-ban"></i> Out of Stock
            </button>
        @endif
    </div>
</article>
