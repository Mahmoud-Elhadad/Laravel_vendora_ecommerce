@extends("Dashboard.layout.main")

@section("body")


     <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">Catalog</li>
        <li class="breadcrumb-item"><a href="{{ route("product.index") }}">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
        </ol>
      </nav>
            <h1 class="page-title">Edit Product</h1>
          </div>
          <div class="page-header-actions">
            <a href="{{ route("product.index") }}" class="btn btn-subtle" >Cancel</a>
            <button class="btn btn-primary" type="submit" form="productForm"><i class="fa-solid fa-check me-2"></i>Update Product</button>
          </div>
        </div>


        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex align-items-start gap-3 flex-wrap">
              <span class="thumb thumb-xl">
                <img src="{{asset("storage/images/products/".$single_product[0]->image[0]['name']) }}" alt="" data-product-slot="image" data-product-format="url" data-product-attr="src" />
              </span>

              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <h2 class="mb-0 fs-5 fw-650" data-product-slot="name">{{ $single_product[0]->name}}</h2>

                </div>

                <p class="mb-0 small">
                  <span class="badge-soft" data-product-slot="categoryName">{{ $single_product[0]->cat->name }}</span>

                </p>
              </div>

              <div class="d-flex align-items-center gap-2 flex-wrap">
                <a class="btn btn-subtle btn-sm" href="{{ route("product.index") }}"><i class="fa-solid fa-arrow-left me-2"></i>All products</a>

              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <!-- Performance -->
          <div class="col-12 col-xl-6">
            <div class="card h-100">
              <div class="card-header">
                <div class="card-header-title">
                  <h2 class="card-title">Performance</h2>
                  <p class="card-subtitle mb-0">Lifetime figures for this product in the demo dataset</p>
                </div>
              </div>
              <div class="card-body">
                <ul class="kpi-list">
                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-cart-shopping text-body-tertiary"></i>Units sold</span>
                    <span class="kpi-value" data-product-slot="sold" data-product-format="int">{{ $single_product[0]->sold_quantity }}</span>
                  </li>
                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-sack-dollar text-body-tertiary"></i>Count</span>
                    <span class="kpi-value" data-product-slot="count" data-product-format="money">{{ $single_product[0]->count }}</span>
                  </li>
                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-chart-line text-body-tertiary"></i>Price</span>
                    <span class="kpi-value" data-product-slot="price" data-product-format="money">${{ $single_product[0]->price - ($single_product[0]->price * $single_product[0]->discount / 100) }}</span>
                  </li>

                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-tag text-body-tertiary"></i>Discount depth</span>
                    <span class="kpi-value" data-product-slot="discountPct">-{{ $single_product[0]->discount }}%</span>
                  </li>

                </ul>
              </div>
            </div>
          </div>

          <!-- Record -->
          <div class="col-12 col-xl-6">
            <div class="card h-100">
              <div class="card-header">
                <div class="card-header-title">
                  <h2 class="card-title">Record</h2>
                  <p class="card-subtitle mb-0">What a Laravel <code>products</code> row would hold</p>
                </div>
              </div>
              <div class="card-body">
                <ul class="kpi-list">
                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-fingerprint text-body-tertiary"></i>Product ID</span>
                    <span class="kpi-value" data-product-slot="id">{{ $single_product[0]->id }}</span>
                  </li>
                  <li>
                    <span class="kpi-label"><i class="fa-solid fa-folder-tree text-body-tertiary"></i>Category</span>
                    <span class="kpi-value" data-product-slot="categoryName">{{ $single_product[0]->cat->name }}</span>
                  </li>

                  <li>
                    <span class="kpi-label"><i class="fa-regular fa-calendar-plus text-body-tertiary"></i>Created</span>
                    <span class="kpi-value" data-product-slot="createdAt" data-product-format="date">{{ $single_product[0]->created_at }}</span>
                  </li>
                  <li>
                    <span class="kpi-label"><i class="fa-regular fa-clock text-body-tertiary"></i>Last updated</span>
                    <span class="kpi-value" data-product-slot="updatedAt" data-product-format="dateTime">{{ $single_product[0]->updated_at }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>


        <form id="productForm" action="{{ route("product.update" , $single_product[0]->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method("put")

          <div class="row g-3">
            <div class="col-12 col-xl-8">
              <div class="card h-100">
                <!-- Basics -->
                <div class="form-section">
                  <h2 class="form-section-title"><i class="fa-solid fa-circle-info text-body-tertiary"></i>Product information</h2>
                  <br>

                  <div class="row g-3">
                    <div class="col-12">
                        @error("name")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                      <label class="form-label" for="productName">Product name</label>
                      <input class="form-control" id="productName" name="name" type="text" maxlength="120" value="{{ $single_product[0]->name }}" placeholder="Product Name" data-slug-target="#productSlug" />
                      <div class="invalid-feedback">Between 3 and 120 characters.</div>
                    </div>





                    <div class="col-12 col-md-6">
                          @error("cat_id")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                      <label class="form-label" for="productCategory">Category</label>
                      <select class="form-select" id="productCategory" name="cat_id" >
                          <option value="">Select a category…</option>
                        @foreach ($cats as $cat)
                             <option @selected($single_product[0]->cat_id  == $cat->id) value="{{ $cat->id }}">{{ $cat->name}}</option>
                        @endforeach

                      </select>
                      <div class="invalid-feedback">A category is required.</div>
                      <div class="form-text">Counts show how many products already sit in each.</div>
                    </div>


                        <!-- Media -->
                <div class="form-section">
                  <h2 class="form-section-title"><i class="fa-regular fa-images text-body-tertiary"></i>Media</h2>
                  <p class="form-section-desc">The first image is the catalogue thumbnail. PNG, square, up to 2048px.</p>

                    @error("img")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                   <input class="form-control" id="productImg"  name="img[]" multiple type="file"  />


                </div>

                    <div class="col-12">

                      <label class="form-label" for="productShortDescription">Short description</label>
                      <textarea  class="form-control" id="productShortDescription" name="description" rows="2"  minlength="20" maxlength="160" data-counter="160" placeholder="One or two sentences shown on the catalogue card.">{{$single_product[0]->description}} </textarea>
                      <div class="invalid-feedback">Between 20 and 160 characters.</div>
                      <div class="form-text text-end"><span data-counter-for="productShortDescription">0 / 160</span></div>
                    </div>

                  </div>
                </div>






              </div>
            </div>

            <div class="col-12 col-xl-4">


              <!-- Pricing -->
              <div class="card mb-3">
                <div class="card-header">
                  <div class="card-header-title">
                    <h2 class="card-title">Pricing</h2>
                  </div>

                </div>
                <div class="card-body">
                  <div class="mb-3">
                      @error("price")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="productPrice">Price</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input class="form-control" id="productPrice" name="price" type="number" step="0.01"  value="{{ $single_product[0]->price }}" placeholder="0.00" data-price />
                    </div>
                    <div class="invalid-feedback">A price is required.</div>
                  </div>

                  <div class="mb-3">
                      @error("discount")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="productOldDiscount">Discount</label>
                    <div class="input-group">
                      <span class="input-group-text">%</span>
                      <input class="form-control" id="productOldDiscount" name="discount" type="number" step="0.01" value="{{ $single_product[0]->discount }}" placeholder="Discount"  />
                    </div>

                  </div>

                  <div class="mb-3">
                      @error("count")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="productCount">Count</label>
                    <div class="input-group">
                      <span class="input-group-text">#</span>
                      <input class="form-control" id="productCount" name="count" type="number" step="1"  value="{{ $single_product[0]->count }}" placeholder="Count"  />
                    </div>

                  </div>


                </div>
              </div>


            </div>
          </div>
        </form>
      </main>

@endsection
