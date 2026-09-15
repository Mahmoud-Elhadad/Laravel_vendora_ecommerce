

  @extends("Dashboard.layout.main")

  @section("body")

        <main class="main-content" id="main-content">
                <div class="page-header">
                        <div class="page-header-text">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb page-breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route("vendora.index")}}"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                            <h1 class="page-title">Dashboard</h1>
                        </div>
                        <div class="page-header-actions">
                            <button class="btn btn-subtle" type="button" data-export="recentOrders"><i class="fa-solid fa-download me-2"></i>Export</button>
                            <button class="btn btn-primary" type="button" data-date-range-picker><i class="fa-regular fa-calendar me-2"></i><span data-range-label>Last 30 days</span></button>
                        </div>
                </div>

                <!-- ================================================================
                    KPI strip — one card per headline metric.
                    Blade equivalent:
                    ============================================================= -->
                <div class="row g-3 mb-3">


                <div class="col-12 col-sm-6 col-xl-4">
                    <a class="stat-card accent-warning" href="{{ route("customer.index") }}" data-stat-link>
                    <div class="stat-head">
                        <span class="stat-label">Customers</span>
                        <span class="stat-icon"><i class="fa-solid fa-users"></i></span>
                    </div>
                    <div class="stat-value">{{ $all_clients }}</div>

                    </a>
                </div>


                <div class="col-12 col-sm-6 col-xl-4">
                    <a class="stat-card accent-primary" href="{{ route("product.index") }}" data-stat-link>
                    <div class="stat-head">
                        <span class="stat-label">Products</span>
                        <span class="stat-icon"><i class="fa-solid fa-box"></i></span>
                    </div>
                    <div class="stat-value">{{ $num_all }}</div>

                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a class="stat-card accent-primary" href="{{ route("cat.index") }}" data-stat-link>
                    <div class="stat-head">
                        <span class="stat-label">Categories</span>
                        <span class="stat-icon"><i class="fa-solid fa-tags"></i></span>
                    </div>
                    <div class="stat-value">{{ $all_cats }}</div>

                    </a>
                </div>

                </div>

                <!-- ================================================================
                    Revenue trend + category mix
                    ============================================================= -->
                <div class="row g-3 mb-3">
                <div class="col-12 col-xl-8">
                    <div class="card h-100">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">Revenue &amp; Orders</h2>
                        <p class="card-subtitle mb-0">Gross revenue against order volume for the selected period</p>
                        </div>
                        <div class="segmented" role="group" aria-label="Reporting period" data-range-group>
                        <button type="button" data-range="today" data-range-label="Today">Today</button>
                        <button type="button" data-range="7d" data-range-label="Last 7 Days">7D</button>
                        <button type="button" class="is-active" data-range="30d" data-range-label="Last 30 Days" aria-pressed="true">30D</button>
                        <button type="button" data-range="6m" data-range-label="Last 6 Months">6M</button>
                        <button type="button" data-range="year" data-range-label="This Year">YTD</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-box h-340">
                        <canvas data-chart="revenueTrend" role="img" aria-label="Revenue and orders over time"></canvas>
                        </div>

                    </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">Sales by Category</h2>
                        <p class="card-subtitle mb-0">Share of lifetime catalogue revenue</p>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="chart-box h-300">
                        <canvas data-chart="categoryShare" role="img" aria-label="Revenue share by category"></canvas>
                        </div>

                    </div>
                    </div>
                </div>
                </div>

                <!-- ================================================================
                    Order pipeline + demand heatmap
                    ============================================================= -->
                <div class="row g-3 mb-3">


                <div class="col-12 col-lg-7 col-xxl-12">
                    <div class="card h-100">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">When Customers Order</h2>
                        <p class="card-subtitle mb-0">Order volume by weekday and hour — plan staffing and campaigns around the peaks</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="heatmap-scroll" data-heatmap></div>
                    </div>
                    </div>
                </div>
                </div>

                <!-- ================================================================
                    Recent orders + right-hand widgets
                    ============================================================= -->
                <div class="row g-3">


                <div class="col-12 col-xxl-12">
                    <div class="card mb-3">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">Top Products</h2>

                        </div>

                    </div>
                    <div class="card-body pt-1 pb-2">
                        @foreach ($eight_products as $key => $product)

                            <a class="product-row" href="{{ route("product.edit" , $product->id) }}">
                                <span class="rank-index">{{ ++$key }}</span>
                                <img class="product-row-thumb" src="{{ asset("storage/images/products/".$product->image[0]['name']) }}" alt="" loading="lazy" />
                                <span class="product-row-body">
                                    <span class="product-row-title">{{ $product->name }}</span>
                                    <span class="product-row-sub">{{ $product->cat->name }} · {{ $product->sold_quantity }} sold</span>
                                </span>

                            </a>
                        @endforeach


                    </div>
                    </div>


                </div>
                </div>

        </main>


  @endsection

