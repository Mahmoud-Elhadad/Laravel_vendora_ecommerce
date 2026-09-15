
 @extends("Dashboard.layout.main")

 @section("body")



  <!-- ================================================================
             Add-category dialog (demo only — nothing is persisted)
  ======================================================================= -->




              <form class="m-2" action="{{ route("cat.store") }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                  <h2 class="modal-title" id="categoryModalLabel">Add category</h2>
                  <a href="{{ route("cat.index") }}"  class="btn-close"  aria-label="Close"></a>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-12 col-md-4">
                         @error("name")
                                <p class="alert alert-danger">{{ $message }}</p>
                         @enderror
                      <label class="form-label" for="categoryName">Name</label>
                      <input class="form-control" id="categoryName" name="name" value="{{ old('name') }}" type="text" required minlength="2" placeholder="category name" data-slug-target="#categorySlug" />
                      <div class="invalid-feedback">Enter a category name.</div>
                    </div>

                     <div class="col-12 col-md-4">
                             @error("img")
                                <p class="alert alert-danger">{{ $message }}</p>
                            @enderror
                                <label class="form-label" for="categoryImg">Category Image</label>
                                <input class="form-control" id="categoryImg"  name="img" type="file" required />
                                <div class="invalid-feedback">category img is required.</div>
                            </div>

                    <div class="col-12 col-md-4">
                         @error("status")
                                <p class="alert alert-danger">{{ $message }}</p>
                            @enderror
                      <label class="form-label" for="categoryStatus">Status</label>
                      <select class="form-select" id="categoryStatus" name="status">
                        <option @selected(old("status") == "active") value="active">Active</option>
                        <option @selected(old("status") == "inactive") value="inactive">Inactive</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label" for="categoryDescription">Description</label>
                      <textarea class="form-control" id="categoryDescription" name="description"  rows="3" maxlength="160" data-counter="160" placeholder="Shown on the category landing page."></textarea>
                      <div class="form-text text-end"><span data-counter-for="categoryDescription">0 / 160</span></div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-subtle" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Save category</button>
                </div>
              </form>





 @endsection
