

  @extends("Dashboard.layout.main")

  @section("body")

    @if(auth("dashboard")->check())

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

    @elseif (auth("ecomm")->user()?->merchent?->status === "approved")

        <!-- Main Content -->
        <main class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-left">
                    <h1>Dashboard Overview</h1>
                </div>
                <div class="navbar-right">

                    <div class="navbar-user">
                        <div class="navbar-user-avatar" style="overflow: hidden"><img style="width: 50px; height: 50px;" src="{{ asset("storage/images/clients/".auth("ecomm")->user()->image) }}" alt=""></div>
                        <div class="navbar-user-info">
                            <h4>{{ auth("ecomm")->user()->first_name }} {{ auth("ecomm")->user()->last_name }} </h4>
                            <p>{{ auth("ecomm")->user()->email }} </p>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>Welcome back, {{ auth("ecomm")->user()->first_name }}! 👋</h2>
                <p>Here's what's happening with your store today.</p>
             
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-icon blue">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Total Products</h3>
                    <div class="value">156</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8 this week</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon green">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Total Orders</h3>
                    <div class="value">892</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+24 this week</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon orange">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Total Sales</h3>
                    <div class="value">$45,230</div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+18.5% this month</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon red">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Pending Orders</h3>
                    <div class="value">8</div>
                    <div class="trend down">
                        <i class="fas fa-arrow-down"></i>
                        <span>-3 from yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="merchant-add-product.html" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="quick-action-text">
                        <h4>Add Product</h4>
                        <p>List a new product</p>
                    </div>
                </a>
                <a href="merchant-products.html" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="quick-action-text">
                        <h4>View Products</h4>
                        <p>Manage inventory</p>
                    </div>
                </a>
                <a href="merchant-orders.html" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="quick-action-text">
                        <h4>View Orders</h4>
                        <p>Process orders</p>
                    </div>
                </a>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-area"></i> Sales Overview</h3>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-right: 12px;"></i>
                        Sales chart visualization
                    </div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-chart-pie"></i> Orders by Status</h3>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-pie" style="font-size: 48px; margin-right: 12px;"></i>
                        Orders distribution chart
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-trophy"></i> Top Selling Products</h2>
                </div>
                <div class="top-products">
                    <div class="top-product-card">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop" alt="Product" class="top-product-image">
                        <div class="top-product-info">
                            <h4>Wireless Headphones</h4>
                            <p>Electronics</p>
                            <div class="sales">$12,450</div>
                        </div>
                    </div>
                    <div class="top-product-card">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop" alt="Product" class="top-product-image">
                        <div class="top-product-info">
                            <h4>Smart Watch Pro</h4>
                            <p>Electronics</p>
                            <div class="sales">$8,920</div>
                        </div>
                    </div>
                    <div class="top-product-card">
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=100&h=100&fit=crop" alt="Product" class="top-product-image">
                        <div class="top-product-info">
                            <h4>Smart Watch</h4>
                            <p>Electronics</p>
                            <div class="sales">$7,340</div>
                        </div>
                    </div>
                    <div class="top-product-card">
                        <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=100&h=100&fit=crop" alt="Product" class="top-product-image">
                        <div class="top-product-info">
                            <h4>Laptop Backpack</h4>
                            <p>Accessories</p>
                            <div class="sales">$5,680</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-clock"></i> Recent Orders</h2>
                    <a href="merchant-orders.html" class="btn btn-primary"><i class="fas fa-eye"></i> View All Orders</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-001</td>
                            <td>Alice Johnson</td>
                            <td>Wireless Headphones</td>
                            <td>2</td>
                            <td>$159.98</td>
                            <td><span class="status completed">Completed</span></td>
                            <td>2026-10-04</td>
                        </tr>
                        <tr>
                            <td>#ORD-002</td>
                            <td>Bob Smith</td>
                            <td>Smart Watch</td>
                            <td>1</td>
                            <td>$299.99</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>2026-10-04</td>
                        </tr>
                        <tr>
                            <td>#ORD-003</td>
                            <td>Carol Davis</td>
                            <td>Laptop Backpack</td>
                            <td>1</td>
                            <td>$49.99</td>
                            <td><span class="status completed">Completed</span></td>
                            <td>2026-10-03</td>
                        </tr>
                        <tr>
                            <td>#ORD-004</td>
                            <td>David Wilson</td>
                            <td>USB-C Hub</td>
                            <td>3</td>
                            <td>$89.97</td>
                            <td><span class="status processing">Processing</span></td>
                            <td>2026-10-03</td>
                        </tr>
                        <tr>
                            <td>#ORD-005</td>
                            <td>Eva Martinez</td>
                            <td>Wireless Mouse</td>
                            <td>1</td>
                            <td>$29.99</td>
                            <td><span class="status cancelled">Cancelled</span></td>
                            <td>2026-10-02</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <h3><i class="fas fa-history"></i> Recent Activity</h3>
                <div class="activity-item">
                    <div class="activity-icon success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-info">
                        <h4>New order received</h4>
                        <p>Order #ORD-006 from Frank Brown</p>
                    </div>
                    <div class="activity-time">2 min ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon info">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="activity-info">
                        <h4>Product updated</h4>
                        <p>Wireless Headphones stock updated to 45</p>
                    </div>
                    <div class="activity-time">15 min ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-info">
                        <h4>Low stock alert</h4>
                        <p>USB-C Hub is running low on stock</p>
                    </div>
                    <div class="activity-time">1 hour ago</div>
                </div>
            </div>
        </main>

    @endif


  @endsection

