@extends("Dashboard.layout.main")

@section("body")

      <form action="{{ route("admin.update" , $admin->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("put")

                        <div class="modal-header">
                        <h2 class="modal-title" id="staffModalLabel">Edit staff member</h2>
                        <a href="{{ route("admin.index") }}" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                @error("name")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffFirstName">Name</label>
                                <input class="form-control" id="staffFirstName" value="{{ $admin->name }}" name="name" type="text" required placeholder="Enter your name!" />
                                <div class="invalid-feedback">your name is required.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                 @error("email")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffEmail">Work email</label>
                                <input class="form-control" id="staffEmail" value="{{ $admin->email }}" name="email" type="email" required placeholder="Enter your E-mail" />
                                <div class="invalid-feedback">Enter a valid email address.</div>
                            </div>


                            <div class="col-12 col-md-6">
                                 @error("role")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffRole">Role</label>
                                <select class="form-select" id="staffRole"  name="role" required>

                                    <option @selected($admin->role == "super admin") value="super admin">Super Admin</option>
                                    <option @selected($admin->role == "admin") value="admin">Admin</option>
                                    <option @selected($admin->role == "manager") value="manager">Manager</option>
                                    <option @selected($admin->role == "sales") value="sales">Sales</option>
                                    <option @selected($admin->role == "support") value="support">Support</option>
                                </select>
                                <div class="invalid-feedback">Pick the role that matches their duties.</div>
                                <div class="form-text">The role pre-fills the permission set below.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                 @error("gender")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffGender">Gender</label>
                                <select class="form-select" id="staffGender"  name="gender" required>

                                    <option @selected($admin->gender == "male") value="male">Male</option>
                                    <option @selected($admin->gender == "female") value="female">Female</option>

                                </select>
                                <div class="invalid-feedback">Pick the valid gender </div>
                            </div>
                             <div class="col-12 col-md-6">
                                 @error("phone")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffPhone">Phone</label>
                                <input class="form-control" id="staffPhone" value="{{ $admin->phone }}" name="phone" type="tel" placeholder="Enter your phone!" required />
                                <div class="invalid-feedback">Use digits, spaces and + ( ) - only.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                 @error("location")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffLocation">Location</label>
                                <input class="form-control" id="staffLocation" value="{{ $admin->location }}" name="location" type="text" placeholder="Enter your location!" required/>
                            </div>

                             <div class="col-12 col-md-6">
                                 @error("age")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffAge">Age</label>
                                <input class="form-control" id="staffAge" value="{{ $admin->age }}" name="age" type="text" required placeholder="Enter your age!" />
                                <div class="invalid-feedback">Your age is required.</div>
                            </div>

                             <div class="col-12 col-md-6">
                                 @error("img")
                                   <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffImg">Your Img</label>
                                <input class="form-control" id="staffImg"  name="img" type="file" />
                                <div class="invalid-feedback">Your img is required.</div>
                            </div>


                        </div>
                        </div>
                        <div class="modal-footer">
                        <a href="{{ route("admin.index") }}" class="btn btn-subtle">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane me-2"></i>Send invite</button>
                        </div>
      </form>


@endsection
