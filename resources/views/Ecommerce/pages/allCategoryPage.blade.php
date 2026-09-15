<x-navbar />

<section class="page-title-bar">
    <div class="container">
        <h1 id="page-title-text">All Categories</h1>
        <p class="mb-0 mt-2 text-white-50">Browse and filter products across all categories.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-v">
                <li class="breadcrumb-item"><a href="{{ route('display.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
    </div>
</section>

<main id="main-content">
    <div class="container section-sm">
        <div class="row g-4">

            {{-- ── Sidebar filters ── --}}
            <aside class="col-lg-3">
                <button id="mobile-filter-toggle"
                        class="btn btn-outline-v w-100 mb-3 d-lg-none">
                    <i class="fa-solid fa-sliders"></i> Filters &amp; sorting
                </button>

                <div id="shop-filters">
                    <form method="GET" action="{{ route('ecomm.category.page') }}" id="filter-form">

                        {{-- Search --}}
                        <div class="filter-card">
                            <div class="filter-head">Search</div>
                            <div class="filter-body">
                                <div class="input-group input-group-sm">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Product name…"
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="filter-card">
                            <div class="filter-head">Category</div>
                            <div class="filter-body">
                                <ul class="filter-list">
                                    <li>
                                        <label class="filter-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="cat"
                                                   value=""
                                                   onchange="this.form.submit()"
                                                   {{ ! request('cat') ? 'checked' : '' }}>
                                            All categories
                                            <span class="fc-count">{{ $products->total() }}</span>
                                        </label>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li>
                                            <label class="filter-check">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="cat"
                                                       value="{{ $category->id }}"
                                                       onchange="this.form.submit()"
                                                       {{ request('cat') == $category->id ? 'checked' : '' }}>
                                                {{ $category->name }}
                                                <span class="fc-count">{{ $category->num_products }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Keep sort value when filter changes --}}
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <button type="button"
                                class="btn btn-outline-v btn-block mt-2"
                                onclick="window.location='{{ route('ecomm.category.page') }}'">
                            <i class="fa-solid fa-rotate-left"></i> Clear filters
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ── Product grid ── --}}
            <div class="col-lg-9">

                {{-- Toolbar --}}
                <div class="shop-toolbar mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <p class="mb-0 text-muted small">
                        Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $products->total() }}</strong> products
                    </p>

                    <form method="GET" action="{{ route('ecomm.category.page') }}" id="sort-form" class="d-flex align-items-center gap-2">
                        {{-- Preserve active filters --}}
                        @if (request('cat'))
                            <input type="hidden" name="cat" value="{{ request('cat') }}">
                        @endif
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <label for="sort-select" class="mb-0 small text-muted text-nowrap">Sort by</label>
                        <select id="sort-select"
                                name="sort"
                                class="form-select form-select-sm w-auto"
                                onchange="this.form.submit()">
                            <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>Newest</option>
                            <option value="popular"    {{ $sort === 'popular'    ? 'selected' : '' }}>Most popular</option>
                            <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Price: low → high</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: high → low</option>
                        </select>
                    </form>
                </div>

                {{-- Active filter chips --}}
                @if (request('cat') || request('search'))
                    <div class="mb-3 d-flex flex-wrap gap-2" id="active-filters">
                        @if (request('cat'))
                            @php $activeCat = $categories->firstWhere('id', request('cat')); @endphp
                            @if ($activeCat)
                                <a href="{{ route('ecomm.category.page', array_merge(request()->except('cat'), ['sort' => $sort])) }}"
                                   class="badge bg-primary-v text-decoration-none">
                                    {{ $activeCat->name }} <i class="fa-solid fa-xmark ms-1"></i>
                                </a>
                            @endif
                        @endif
                        @if (request('search'))
                            <a href="{{ route('ecomm.category.page', array_merge(request()->except('search'), ['sort' => $sort])) }}"
                               class="badge bg-primary-v text-decoration-none">
                                "{{ request('search') }}" <i class="fa-solid fa-xmark ms-1"></i>
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Products grid --}}
                @if ($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No products found</h5>
                        <p class="text-muted">Try adjusting your filters or search term.</p>
                        <a href="{{ route('ecomm.category.page') }}" class="btn btn-outline-v">Clear filters</a>
                    </div>
                @else
                    <div class="row g-3 g-md-4" id="product-grid">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-xl-4">
                                @include('Ecommerce.layout.shop-product-card')
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($products->hasPages())
                        <div class="mt-4 d-flex justify-content-center" id="shop-pagination">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</main>

<x-footer />
