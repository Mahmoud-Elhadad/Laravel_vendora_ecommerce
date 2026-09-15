@extends("Dashboard.layout.main")

@section('body')


     <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-3">
          <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">Catalog</li>
        <li class="breadcrumb-item"><a href="products.html">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
        </ol>
      </nav>
            <h1 class="page-title">Add Product</h1>
          </div>
          <div class="page-header-actions">
            <a href="{{ route("product.index") }}" class="btn btn-subtle" >Cancel</a>

            <button class="btn btn-primary" type="submit" form="productForm"><i class="fa-solid fa-check me-2"></i>Publish Product</button>
          </div>
        </div>




        <form id="productForm" action="{{ route("product.store") }}" method="post" enctype="multipart/form-data">
            @csrf

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
                      <input class="form-control" id="productName" name="name" type="text" maxlength="120" value="{{ old("name") }}" placeholder="Product Name" data-slug-target="#productSlug" />
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
                             <option @selected(old('cat_id') == $cat->id) value="{{ $cat->id }}">{{ $cat->name}}</option>
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
                      <textarea  class="form-control" id="productShortDescription" name="description" rows="2"  minlength="20" maxlength="160" data-counter="160" placeholder="One or two sentences shown on the catalogue card.">{{ old("description") }}</textarea>
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
                      <input class="form-control" id="productPrice" name="price" type="number" step="0.01"  value="{{ old('price') }}" placeholder="0.00" data-price />
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
                      <input class="form-control" id="productOldDiscount" name="discount" type="number" step="0.01" value="{{ old('discount') }}" placeholder="Discount"  />
                    </div>

                  </div>

                  <div class="mb-3">
                      @error("count")
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="productCount">Count</label>
                    <div class="input-group">
                      <span class="input-group-text">#</span>
                      <input class="form-control" id="productCount" name="count" type="number" step="1"  value="{{ old("count") }}" placeholder="Count"  />
                    </div>

                  </div>


                </div>
              </div>


            </div>
          </div>
        </form>
      </main>


@endsection
