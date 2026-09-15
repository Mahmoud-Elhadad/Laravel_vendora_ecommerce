
<x-navbar />


  <section class="page-title-bar">
    <div class="container">
      <h1 id="page-title-text">Shopping Cart</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-v">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cart</li>
        </ol>
      </nav>
    </div>
  </section>

<main id="main-content">
    <section class="section-sm">
        <div class="container">
            <div class="row g-4" id="cart-page">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h4 mb-0">
                            Your cart
                            @if ($cart_products->isNotEmpty())
                                <span class="text-muted fw-normal">{{ $cart_products->count() }} item{{ $cart_products->count() === 1 ? '' : 's' }}</span>
                            @endif
                        </h2>
                    </div>

                    @if ($cart_products->isEmpty())
                        {{-- Empty cart state --}}
                        <div class="empty-state">
                            <img src="{{ asset('ecomm') }}/images/ui/empty-cart.svg" alt="Empty cart">
                            <h4>Your shopping cart is empty</h4>
                            <p>Looks like you have not added anything yet. Explore our catalog and find something you love.</p>
                            <a href="{{ route('ecomm.shop.page') }}" class="btn btn-primary-v btn-lg">
                                <i class="fa-solid fa-bag-shopping"></i> Start Shopping
                            </a>
                        </div>
                    @else
                        {{-- Cart filled state --}}
                        <div class="table-responsive">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart_products as $cart_product)
                                        @php
                                            $product = $cart_product->product;
                                            $image   = $product->image->first();
                                            $subtotal = $product->price * $cart_product->count;
                                        @endphp
                                        <tr class="cart-row" data-cart-id="{{ $cart_product->id }}">
                                            <td class="cart-product-cell" data-label="Product">
                                                <div class="cart-item">
                                                    <a class="ci-link" href="{{ route('product.details', $product->id) }}">
                                                        <img class="ci-img"
                                                             src="{{ $image ? asset('storage/images/products/' . $image->name) : asset('ecomm/images/ui/empty-cart.svg') }}"
                                                             alt="{{ $product->name }}">
                                                    </a>
                                                    <div>
                                                        <div class="ci-name">
                                                            <a class="ci-name-link" href="{{ route('product.details', $product->id) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </div>
                                                        @if ($product->cat)
                                                            <div class="ci-meta">{{ $product->cat->name }}</div>
                                                        @endif
                                                        <div class="ci-meta d-md-none mt-1">
                                                            <strong>${{ number_format($product->price, 2) }}</strong> each
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Price">
                                                <span class="cart-price d-none d-md-inline">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            </td>
                                            <td class="text-center h6">
                                                {{ $cart_product->count }}
                                            </td>
                                            <td data-label="Subtotal">
                                                <span class="cart-subtotal">${{ number_format($subtotal, 2) }}</span>
                                            </td>
                                            <td data-label="Actions">
                                                <div class="d-flex gap-2 justify-content-md-end">
                                                    <button type="button" class="icon-btn cart-remove" title="Remove"
                                                            data-cart-id="{{ $cart_product->id }}">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
                            <a href="{{ route('ecomm.shop.page') }}" class="btn btn-outline-v">
                                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                            </a>


                            <button type="button" class="btn btn-outline-v" data-bs-toggle="modal" data-bs-target="#deleteCartModal"><i class="fa-regular fa-trash-can me-1"></i>Clear Cart
                            </button>
                        </div>
                        @include("Ecommerce.layout.modal_deleteAllCart")
                    @endif
                </div>

                {{-- Order summary --}}
                <div class="col-lg-4">
                    <div class="summary-box  all_total_cart">
                        @php
                            $total = $cart_products->sum(fn($item) => $item->product->price * $item->count);
                        @endphp
                        <div class="summary-line total">
                            <span class="lbl">Total</span>
                            <span class="val">${{ number_format($total, 2) }}</span>
                        </div>

                        <a href="{{ route('ecomm.shop.page') }}" class="btn btn-outline-v btn-block mt-2">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />
