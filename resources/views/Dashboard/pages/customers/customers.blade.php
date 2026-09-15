@extends("Dashboard.layout.main")

@section("body")


    <main class="main-content" id="main-content">
                <div class="page-header">
                    <div class="page-header-text">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb page-breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li class="breadcrumb-item" aria-current="false">Customers</li>
                        <li class="breadcrumb-item active" aria-current="page">All Customers</li>
                        </ol>
                    </nav>
                        <h1 class="page-title mt-2">Customers</h1>
                    </div>
                    <div class="page-header-actions">
                        <button class="btn btn-subtle" type="button" data-export="customers"><i class="fa-solid fa-file-csv me-2"></i>Export</button>
                        <a href="{{ route("customer.create") }}" class="btn btn-primary"><i class="fa-solid fa-user-plus me-2"></i>Add Customer</a>
                    </div>
                </div>

                <!-- ================================================================
                    Customers — the customer book, with spend and loyalty facets.
                    Blade equivalent: resources/views/customers/index.blade.php
                    ============================================================= -->
                <div class="row g-3 mb-3">
                <div class="col-6 col-xl-6">
                    <div class="info-card">
                    <span class="info-card-icon bg-primary-subtle text-primary"><i class="fa-solid fa-users"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Customers</span>
                        <span class="info-card-value">{{ $num_customers }}</span>
                    </span>
                    </div>
                </div>
                <div class="col-6 col-xl-6">
                    <div class="info-card">
                    <span class="info-card-icon bg-success-subtle text-success"><i class="fa-solid fa-sack-dollar"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Lifetime spend</span>
                        <span class="info-card-value">${{ number_format($total_spent, 2) }}</span>
                    </span>
                    </div>
                </div>


                </div>

                <div class="row g-3 mb-3">
                <div class="col-12 col-lg-7">
                    <div class="card h-100">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">Customer Growth</h2>
                        <p class="card-subtitle mb-0">New sign-ups per month against the running total</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-box h-260">
                        <canvas data-chart="customerGrowth" role="img" aria-label="New customers per month and cumulative total"></canvas>
                        </div>
                        <div data-chart-legend="customerGrowth"></div>
                    </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card h-100">
                    <div class="card-header">
                        <div class="card-header-title">
                        <h2 class="card-title">Segments</h2>
                        <p class="card-subtitle mb-0">Value-based grouping of the customer book</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-box h-260">
                        <canvas data-chart="customerSegments" role="img" aria-label="Customers per value segment"></canvas>
                        </div>
                        <div data-chart-legend="customerSegments"></div>
                    </div>
                    </div>
                </div>
                </div>

                <div class="card">
                <div class="table-toolbar" data-table-controls="customers">
                    <div class="toolbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" class="form-control form-control-sm" placeholder="Search name, email or phone…" aria-label="Search customers" data-table-search />
                    </div>



                    <div class="toolbar-spacer"></div>

                    <div class="toolbar-end">
                        <label class="text-body-secondary small mb-0" for="customersPerPage">Rows</label>
                        <select class="form-select form-select-sm" id="customersPerPage" aria-label="Rows per page" data-table-per-page>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>



                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 table-mobile-cards" data-table="customers" data-table-options='{"perPage":10,"sort":"spent","dir":"desc","emptyTitle":"No customers match those filters","emptyText":"Try a different group or country, or reset the filters.","emptyIcon":"fa-users"}'>
                        <thead>
                            <tr>
                            <th scope="col">
                                #
                            </th>
                            <th class="sortable" data-sort="customer" scope="col">Customer</th>
                            <th class="sortable" data-sort="phone" scope="col">Phone</th>
                            <th class="sortable" data-sort="carts" scope="col">Carts</th>
                            <th class="sortable" data-sort="wishlists" scope="col">Wishlists</th>
                            <th class="sortable" data-sort="spent" scope="col">Spent</th>
                            @if(Auth::guard("dashboard")->user()->role == "super admin")

                                <th class="table-actions" scope="col"><span class="visually-hidden">Actions</span></th>

                            @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $customer)

                                <tr data-status="active"  data-name="{{ $customer->first_name }} {{ $customer->last_name }}">
                                    <td>
                                        {{ ++$key }}
                                    </td>
                                    <td data-label="Customer" class="has-entity" data-sort-value="{{ $customer->first_name }} {{ $customer->last_name }}">
                                        <span class="cell-entity">
                                            <img class="avatar avatar-md" src="{{ asset("storage/images/clients/".$customer->image) }}" alt="" loading="lazy" />
                                            <span class="cell-entity-text">
                                                <span class="cell-entity-title">
                                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                                </span>
                                                <span class="cell-entity-sub">{{ $customer->email }}</span>
                                            </span>
                                        </span>
                                    </td>
                                    <td data-label="Phone" data-sort-value="{{ $customer->phone }}">{{ $customer->phone }}</td>
                                    <td data-label="Carts" class="cell-primary" data-sort-value="{{ $customer->carts_count }}">{{ $customer->carts_count }}</td>
                                    <td data-label="Wishlists" class="cell-primary" data-sort-value="{{ $customer->wishlists_count }}">{{ $customer->wishlists_count }}</td>
                                    <td data-label="Spent" class="cell-primary" data-sort-value="{{ $customer->total_spent }}" data-export-value="{{ $customer->total_spent }}">${{ number_format($customer->total_spent, 2) }}</td>
                                    @if(Auth::guard("dashboard")->user()->role == "super admin")

                                        <td class="table-actions">

                                                 <button type="button" class="btn-action danger"  title="Delete" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal-{{ $customer->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                    <span class="visually-hidden">Delete</span>
                                                </button>

                                        </td>

                                    @endif

                                </tr>
                                @include("Dashboard.layout.modal_deleteCustomer")
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="text-body-secondary small" data-table-info="customers"></span>
                    <nav aria-label="Customers pagination" data-table-pagination="customers"></nav>
                </div>
                </div>

                <!-- ================================================================
                    Add-customer dialog (demo only — nothing is persisted)
                    ============================================================= -->

      </main>



@endsection
