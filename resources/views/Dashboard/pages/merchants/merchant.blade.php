@extends("Dashboard.layout.main")

@section("body")

    <main class="main-content" id="main-content">

        <div class="page-header">
            <div class="page-header-text">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
                        <li class="breadcrumb-item" aria-current="false">Merchants</li>
                        <li class="breadcrumb-item active" aria-current="page">All Merchants</li>
                    </ol>
                </nav>
                <h1 class="page-title mt-2">Merchants</h1>
            </div>
            <div class="page-header-actions">
                <button class="btn btn-subtle" type="button" data-export="merchants"><i class="fa-solid fa-file-csv me-2"></i>Export</button>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-3">
                <div class="info-card">
                    <span class="info-card-icon bg-primary-subtle text-primary"><i class="fa-solid fa-store"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Total Merchants</span>
                        <span class="info-card-value">{{ $num_customers }}</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="info-card">
                    <span class="info-card-icon bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Approved</span>
                        <span class="info-card-value">{{ $customers->filter(fn($c) => $c->merchent?->status === 'approved')->count() }}</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="info-card">
                    <span class="info-card-icon bg-warning-subtle text-warning"><i class="fa-solid fa-clock"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Pending</span>
                        <span class="info-card-value">{{ $customers->filter(fn($c) => $c->merchent?->status === 'pending')->count() }}</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="info-card">
                    <span class="info-card-icon bg-danger-subtle text-danger"><i class="fa-solid fa-circle-xmark"></i></span>
                    <span class="info-card-body">
                        <span class="info-card-label">Rejected</span>
                        <span class="info-card-value">{{ $customers->filter(fn($c) => $c->merchent?->status === 'rejected')->count() }}</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-toolbar" data-table-controls="merchants">
                <div class="toolbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" class="form-control form-control-sm" placeholder="Search name, email or phone…" aria-label="Search merchants" data-table-search />
                </div>

                <div class="toolbar-spacer"></div>

                <div class="toolbar-end">
                    <label class="text-body-secondary small mb-0" for="merchantsPerPage">Rows</label>
                    <select class="form-select form-select-sm" id="merchantsPerPage" aria-label="Rows per page" data-table-per-page>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-mobile-cards"
                    data-table="merchants"
                    data-table-options='{"perPage":10,"sort":"status","dir":"asc","emptyTitle":"No merchants found","emptyText":"There are no merchant requests yet.","emptyIcon":"fa-store"}'>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th class="sortable" data-sort="merchant" scope="col">Merchant</th>
                            <th class="sortable" data-sort="phone" scope="col">Phone</th>
                            <th class="sortable" data-sort="status" scope="col">Status</th>
                            <th class="sortable" data-sort="request_date" scope="col">Request Date</th>
                            @if(auth("dashboard")->user()->can("edit-access"))
                                <th class="table-actions" scope="col"><span class="visually-hidden">Actions</span></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $key => $customer)
                            <tr data-name="{{ $customer->first_name }} {{ $customer->last_name }}">
                                <td>{{ ++$key }}</td>

                                <td data-label="Merchant" class="has-entity" data-sort-value="{{ $customer->first_name }} {{ $customer->last_name }}">
                                    <span class="cell-entity">
                                        <img class="avatar avatar-md" src="{{ asset('storage/images/clients/' . $customer->image) }}" alt="{{ $customer->first_name }}" loading="lazy" />
                                        <span class="cell-entity-text">
                                            <span class="cell-entity-title">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                                            <span class="cell-entity-sub">{{ $customer->email }}</span>
                                        </span>
                                    </span>
                                </td>

                                <td data-label="Phone" data-sort-value="{{ $customer->phone }}">{{ $customer->phone }}</td>

                                <td data-label="Status" data-sort-value="{{ $customer->merchent?->status }}">
                                    @php $status = $customer->merchent?->status ?? 'unknown'; @endphp
                                    @if($status === 'approved')
                                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">Approved</span>
                                    @elseif($status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">Pending</span>
                                    @elseif($status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">Unknown</span>
                                    @endif
                                </td>

                                <td data-label="Request Date" data-sort-value="{{ $customer->merchent?->created_at }}">
                                    {{ $customer->merchent?->created_at?->format('M d, Y') ?? '—' }}
                                </td>

                                @if(auth("dashboard")->user()->can("edit-access"))
                                    <td class="table-actions">
                                        <div class="btn-actions">
                                            @if($customer->merchent?->status !== 'approved')
                                                <a class="btn-action success" href="{{ route('merchant.approve', $customer->merchent->id) }}" data-bs-toggle="tooltip" title="Approve">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                    <span class="visually-hidden">Approve</span>
                                                </a>
                                            @endif

                                            @if($customer->merchent?->status !== 'rejected')
                                                <a class="btn-action danger" href="{{ route('merchant.reject', $customer->merchent->id) }}" data-bs-toggle="tooltip" title="Reject">
                                                    <i class="fa-regular fa-circle-xmark"></i>
                                                    <span class="visually-hidden">Reject</span>
                                                </a>
                                            @endif

                                             @if(auth("dashboard")->user()->can("delete-access"))
                                            <button type="button" class="btn-action danger" data-bs-toggle="modal" data-bs-target="#deleteMerchantModal-{{ $customer->merchent->id }}" title="Delete">
                                                <i class="fa-regular fa-trash-can"></i>
                                                <span class="visually-hidden">Delete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            @include("Dashboard.layout.modal_deleteMerchant")
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span class="text-body-secondary small" data-table-info="merchants"></span>
                <nav aria-label="Merchants pagination" data-table-pagination="merchants"></nav>
            </div>
        </div>

    </main>

@endsection
