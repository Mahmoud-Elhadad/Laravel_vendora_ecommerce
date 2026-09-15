@extends("Dashboard.layout.main")


@section("body")



     <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">Catalog</li>
        <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
      </nav>
            <h1 class="page-title">Categories</h1>
          </div>
          <div class="page-header-actions">
            <a href="{{ route("cat.create") }}" class="btn btn-primary" ><i class="fa-solid fa-plus me-2"></i>Add Category</a>
          </div>
        </div>



        <div data-view="table">
          <div class="card">
            <div class="table-toolbar" data-table-controls="categories">
              <div class="toolbar-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" class="form-control form-control-sm" placeholder="Search categories…" aria-label="Search categories" data-table-search />
              </div>

              <div class="toolbar-filters">
                <select class="form-select form-select-sm" aria-label="Filter by status" data-table-filter="status">
                  <option value="all">All statuses</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>


                <button type="button" class="btn btn-sm btn-subtle" data-clear-filters>
                  <i class="fa-solid fa-rotate-left me-2"></i>Reset
                </button>
              </div>

              <div class="toolbar-spacer"></div>

              <div class="toolbar-end">
                <label class="text-body-secondary small mb-0" for="categoriesPerPage">Rows</label>
                <select class="form-select form-select-sm" id="categoriesPerPage" aria-label="Rows per page" data-table-per-page>
                  <option value="10">10</option>
                  <option value="25" selected>25</option>
                  <option value="50">50</option>
                </select>
              </div>
            </div>


            <div class="table-responsive">

              <table class="table table-hover align-middle mb-0 table-mobile-cards" data-table="categories" data-table-options='{"perPage":25,"perPageOptions":[10,25,50],"sort":"name","dir":"asc","emptyTitle":"No categories found","emptyText":"Try a different search term, or reset the filters.","emptyIcon":"fa-tags"}'>
                <thead>
                  <tr>
                    <th scope="col">
                      #
                    </th>
                    <th class="sortable" data-sort="name" scope="col">Category</th>
                    <th scope="col">Description</th>
                    <th class="sortable" data-sort="products" scope="col">Num of products</th>
                    <th class="sortable" data-sort="status" scope="col">Status</th>
                    <th class="sortable" data-sort="created" scope="col">Created</th>
                    <th class="table-actions" scope="col"><span class="visually-hidden">Actions</span></th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($cats as $key => $cat)

                        <tr data-status="{{ $cat->status }}" data-name="{{ strtolower($cat->name) }}">
                        <td class="table-check">
                           {{ ++$key }}
                        </td>
                        <td data-label="Category" class="has-entity" data-sort-value="{{ strtolower($cat->name) }}">
                            <span class="cell-entity">
                                <img class="avatar avatar-md avatar-square" src="{{ asset("storage/images/cats/".$cat->img) }}" alt="" loading="lazy" /><span class="cell-entity-text"><span class="cell-entity-title"> {{ $cat->name }} <i class="fa-solid fa-star text-warning" data-bs-toggle="tooltip" title="Featured"></i></span></span></span>
                        </td>
                        <td data-label="Description">{{ $cat->description }}</td>
                        <td data-label="Products" class="cell-primary" data-sort-value="{{ $cat->num_products }}">{{ $cat->num_products }}</td>

                        <td data-label="Status" data-sort-value="{{ $cat->status }}" data-status-cell><span class="status-badge <?= $cat->status == 'active' ? 'status-success' : 'status-neutral' ?> " title="{{ $cat->status }}"><i class="<?= $cat->status == 'active' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-pause' ?>"></i> {{ $cat->status }}</span></td>
                        <td data-label="Created" data-sort-value="{{ $cat->created_at->format('Y-m-d') }}">{{ $cat->created_at->format('M d, Y') }}</td>
                        <td class="table-actions">
                            <div class="btn-actions">
                                

                                <form action="{{ route("cat.destroy" , $cat->id) }}" method = "post">
                                    @csrf
                                    @method('delete')
                                     <button type="submit" class="btn-action danger"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>


            </div>

            <div class="table-footer">
              <span class="text-body-secondary small" data-table-info="categories"></span>
              <nav aria-label="Categories pagination" data-table-pagination="categories"></nav>
            </div>
          </div>
        </div>




      </main>


@endsection
