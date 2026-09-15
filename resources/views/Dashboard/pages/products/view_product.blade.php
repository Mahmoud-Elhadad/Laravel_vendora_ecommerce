
 @extends("Dashboard.layout.main")

 @section('body')


        <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">Catalog</li>
        <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
      </nav>
            <h1 class="page-title">Products</h1>
          </div>
          <div class="page-header-actions">
            <button class="btn btn-subtle" type="button" data-export="products"><i class="fa-solid fa-file-csv me-2"></i>Export CSV</button>
            <a class="btn btn-primary" href="{{ route("product.create")}}"><i class="fa-solid fa-plus me-2"></i>Add Product</a>
          </div>
        </div>


        <div class="row g-3 mb-3">
          <div class="col-6 col-xl-3">
            <div class="info-card">
              <span class="info-card-icon bg-primary-subtle text-primary"><i class="fa-solid fa-box-open"></i></span>
              <span class="info-card-body">
                <span class="info-card-label">Products</span>
                <span class="info-card-value">{{ $num_products }}</span>
              </span>
            </div>
          </div>



        </div>

        <!-- ================================================================
             Product grid — rendered server-side in Blade, enhanced by
             assets/js/custom.js (AdminTable) for sort / filter / page / bulk.
             Blade equivalent: resources/views/catalog/products/index.blade.php
             ============================================================= -->
        <div class="card">
          <div class="table-toolbar" data-table-controls="products">
            <div class="toolbar-search">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="search" class="form-control form-control-sm" placeholder="Search name, category ..." aria-label="Search products" data-table-search />
            </div>

            <div class="toolbar-filters">


              <select class="form-select form-select-sm" aria-label="Filter by category" data-table-filter="category">
                <option value="all">All categories</option>
                @foreach ($cats as $cat)

                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>

                @endforeach
              </select>



              <button type="button" class="btn btn-sm btn-subtle" data-clear-filters>
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
              </button>
            </div>

            <div class="toolbar-spacer"></div>

            <div class="toolbar-end">
              <label class="text-body-secondary small mb-0" for="productsPerPage">Rows</label>
              <select class="form-select form-select-sm" id="productsPerPage" aria-label="Rows per page" data-table-per-page>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>


          <div class="table-responsive">
            <table style="text-align: center" class="table table-hover align-middle mb-0 table-mobile-cards" data-table="products" data-table-options='{"perPage":10,"sort":"name","dir":"asc","emptyTitle":"No products match those filters","emptyText":"Try a different search term, or reset the filters to see the whole catalogue.","emptyIcon":"fa-box-open"}'>
              <thead>
                <tr>
                  <th class="table-check" scope="col">
                   #
                  </th>
                  <th class="sortable" data-sort="name" scope="col">Product</th>
                  <th>Images</th>
                  <th class="sortable" data-sort="category" scope="col">Category</th>
                  <th class="sortable" data-sort="price" scope="col">Price</th>
                  <th>Sold</th>
                  <th class="sortable text-end" data-sort="count" scope="col">Count</th>
                  <th class="table-actions" scope="col"><span class="visually-hidden">Actions</span></th>
                </tr>
              </thead>
              <tbody >
                @foreach ($products as $key => $product)

                    <tr data-category="{{ $product->cat->name }}" data-name="{{ $product->name }}">
                    <td class="table-check">{{ ++$key }}</td>
                    <td data-label="Product" class="has-entity" data-sort-value="{{ $product->name }}">
                        <span class="cell-entity">
                            <span class="cell-entity-text">
                            <span class="cell-entity-title"><a href="{{ route("product.edit" , $product->id) }}">{{ $product->name }}</a></span>

                        </span>
                    </span>
                    </td>

                    <td>
                        @foreach ($product->image as $value)

                        <img class="avatar avatar-md avatar-square" src="{{ asset("storage/images/products/".$value->name) }}" alt="" loading="lazy" />
                        @endforeach


                    </td>
                    <td data-label="Category" data-sort-value="{{ $product->cat->name }}">{{ $product->cat->name }}</td>
                    <td data-label="Price" class="cell-primary" data-sort-value="{{ $product->price }}" data-export-value="{{ $product->price - ($product->price * $product->discount / 100) }}">${{ $product->price - ($product->price * $product->discount / 100) }}<span class="cell-sub"><s>${{ $product->price }}</s> <span class="text-success">−{{ $product->discount }}%</span></span></td>

                    <td data-label="Sold">{{ $product->sold_quantity }}</td>
                    <td data-label="Revenue" class="text-end" data-sort-value="{{ $product->count }}" data-export-value="{{ $product->count }}">{{ $product->count }}</td>

                    <td class="table-actions"><div class="btn-actions"><a class="btn-action" href="{{ route("product.edit" , $product->id) }}" data-bs-toggle="tooltip" title="View"><i class="fa-regular fa-eye"></i><span class="visually-hidden">View</span></a><a class="btn-action" href="{{ route("product.edit" , $product->id) }}" data-bs-toggle="tooltip" title="Edit"><i class="fa-regular fa-pen-to-square"></i><span class="visually-hidden">Edit</span></a>



                            <button type="button" class="btn-action danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal-{{ $product->id }}"><i class="fa-regular fa-trash-can"></i><span class="visually-hidden">Delete</span>
                            </button>
                       
                    </div>

                </td>
                    </tr>

                     @include("Dashboard.layout.modal_deleteProduct")
                @endforeach

              </tbody>
            </table>
          </div>

          <div class="table-footer">
            <span class="text-body-secondary small" data-table-info="products"></span>
            <nav aria-label="Products pagination" data-table-pagination="products"></nav>
          </div>
        </div>
      </main>





 @endsection
