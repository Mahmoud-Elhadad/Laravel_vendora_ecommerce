@extends("Dashboard.layout.main")

@section("body")
     <main class="main-content" id="main-content">
        <div class="page-header">
          <div class="page-header-text">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
        <li class="breadcrumb-item" aria-current="false">System</li>
        <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
      </nav>
            <h1 class="page-title">My Profile</h1>
          </div>
          <div class="page-header-actions">
            <button class="btn btn-primary" type="submit" form="profileForm"><i class="fa-solid fa-check me-2"></i>Save Changes</button>
          </div>
        </div>

        <!-- ================================================================
             Profile — the signed-in admin's own account, as opposed to
             staff.html which manages everyone else's. Values mirror the
             Mahmoud Elhadad record in DB.staff / the ADMIN constant in
             nav.mjs so the topbar, staff table and this page never disagree.
             The header's Save Changes button submits #profileForm; password
             and session actions live in their own small forms/controls since
             they are not part of the "save profile" intent.
             Blade equivalent: resources/views/profile/edit.blade.php
             ============================================================= -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex align-items-start gap-3 flex-wrap">
              <div class="avatar-wrap">
                 @if(auth("dashboard")->check())
                <img class="avatar avatar-xl" src="{{ asset("storage/images/admins/".Auth::guard('dashboard')->user()->img) }}" alt="" data-avatar-preview />
                @elseif(auth("ecomm")->user()?->merchent?->status === "approved")
                 <img class="avatar avatar-xl" src="{{ asset("storage/images/clients/".Auth::guard('ecomm')->user()->image) }}" alt="" data-avatar-preview />
                @endif
                <span class="presence" aria-hidden="true"></span>
              </div>

              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <h2 class="mb-0 fs-5 fw-650">
                    @if(auth("dashboard")->check())
                    {{ auth("dashboard")->user()->name }}
                    @elseif(auth("ecomm")->user()?->merchent?->status === "approved")
                    {{ auth("ecomm")->user()->first_name }} {{ auth("ecomm")->user()->last_name }}
                    @endif
                </h2>
                  <span class="badge-soft">
                    @if(auth("dashboard")->check())
                    {{ auth("dashboard")->user()->role }}
                    @endif
                  </span>
                  <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                </div>
                <p class="text-body-secondary small mb-1 text-capitalize">
                    @if(auth("dashboard")->check())
                    {{ Auth::guard('dashboard')->user()->gender }}
                    @elseif(auth("ecomm")->user()?->merchent?->status === "approved")
                    merchent
                    @endif
                </p>
                <p class="mb-0 small">
                 <a class="text-body-secondary" href="mailto:
                    @if(auth('dashboard')->check())
                        {{ auth('dashboard')->user()->email }}
                    @elseif(auth('ecomm')->user()?->merchent?->status === 'approved')
                        {{ auth('ecomm')->user()->email }}
                    @endif
                ">
                    @if(auth('dashboard')->check())
                        {{ auth('dashboard')->user()->email }}
                    @elseif(auth('ecomm')->user()?->merchent?->status === 'approved')
                        {{ auth('ecomm')->user()->email }}
                    @endif
                </a>


                  <span class="text-body-tertiary">·</span>
                  <a class="text-body-secondary" href="tel:

                    @if(auth('dashboard')->check())
                        {{ auth('dashboard')->user()->phone }}
                    @elseif(auth('ecomm')->user()?->merchent?->status === 'approved')
                        {{ auth('ecomm')->user()->phone }}
                    @endif

                  ">
                  @if(auth('dashboard')->check())
                        {{ auth('dashboard')->user()->phone }}
                    @elseif(auth('ecomm')->user()?->merchent?->status === 'approved')
                        {{ auth('ecomm')->user()->phone }}
                    @endif
                </a>
                  <span class="text-body-tertiary">·</span>
                  @if(auth('dashboard')->check())
                        {{ auth('dashboard')->user()->created_at }}
                    @elseif(auth('ecomm')->user()?->merchent?->status === 'approved')
                        {{ auth('ecomm')->user()->created_at }}
                    @endif
                </p>
              </div>

              @if(auth("dashboard")->check())

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <label class="btn btn-subtle btn-sm mb-0" for="avatarInput">
                    <i class="fa-solid fa-camera me-2"></i>Change photo
                    </label>
                    <input class="d-none" id="avatarInput" type="file" accept="image/*" data-avatar-input data-avatar-preview="[data-avatar-preview]" />
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-12 col-xl-12">

            @if(auth("dashboard")->check())

                <form id="profileForm"  action="{{ route("admin.update" , Auth::guard("dashboard")->user()->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("put")


                        <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                @error("name")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffFirstName">Name</label>
                                <input class="form-control" id="staffFirstName" value="{{ Auth::guard("dashboard")->user()->name }}" name="name" type="text" required placeholder="Enter your name!" />
                                <div class="invalid-feedback">your name is required.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                    @error("email")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffEmail">Work email</label>
                                <input class="form-control" id="staffEmail" value="{{ Auth::guard("dashboard")->user()->email }}" name="email" type="email" required placeholder="Enter your E-mail" />
                                <div class="invalid-feedback">Enter a valid email address.</div>
                            </div>


                            <div class="col-12 col-md-6">
                                    @error("role")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffRole">Role</label>
                                <select class="form-select" id="staffRole"  name="role" required>

                                    <option @selected(Auth::guard("dashboard")->user()->role == "super admin") value="super admin">Super Admin</option>
                                    <option @selected(Auth::guard("dashboard")->user()->role == "admin") value="admin">Admin</option>
                                    <option @selected(Auth::guard("dashboard")->user()->role == "manager") value="manager">Manager</option>
                                    <option @selected(Auth::guard("dashboard")->user()->role == "sales") value="sales">Sales</option>
                                    <option @selected(Auth::guard("dashboard")->user()->role == "support") value="support">Support</option>
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

                                    <option @selected(Auth::guard("dashboard")->user()->gender == "male") value="male">Male</option>
                                    <option @selected(Auth::guard("dashboard")->user()->gender == "female") value="female">Female</option>

                                </select>
                                <div class="invalid-feedback">Pick the valid gender </div>
                            </div>
                                <div class="col-12 col-md-6">
                                    @error("phone")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffPhone">Phone</label>
                                <input class="form-control" id="staffPhone" value="{{ Auth::guard("dashboard")->user()->phone }}"  name="phone" type="tel" placeholder="Enter your phone!" required />
                                <div class="invalid-feedback">Use digits, spaces and + ( ) - only.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                    @error("location")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffLocation">Location</label>
                                <input class="form-control" id="staffLocation" value="{{ Auth::guard("dashboard")->user()->location }}" name="location" type="text" placeholder="Enter your location!" required/>
                            </div>

                                <div class="col-12 col-md-6">
                                    @error("age")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffAge">Age</label>
                                <input class="form-control" id="staffAge" value="{{ Auth::guard("dashboard")->user()->age }}"  name="age" type="text" required placeholder="Enter your age!" />
                                <div class="invalid-feedback">Your age is required.</div>
                            </div>



                        </div>
                        </div>

                </form>
            @elseif(auth("ecomm")->user()?->merchent?->status === "approved")

                <form id="profileForm"  action="{{ route('merchents.update' , auth("ecomm")->user()?->merchent?->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("put")


                        <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                @error("first_name")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="merchantFirstName">First Name</label>
                                <input class="form-control" id="merchantFirstName" value="{{ Auth::guard("ecomm")->user()->first_name }}" name="first_name" type="text" required placeholder="Enter your first name!" />
                                <div class="invalid-feedback">your first name is required.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error("last_name")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="merchantlastName">Last Name</label>
                                <input class="form-control" id="merchantlastName" value="{{ Auth::guard("ecomm")->user()->last_name }}" name="last_name" type="text" required placeholder="Enter your last name!" />
                                <div class="invalid-feedback">your last name is required.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                @error("email")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="merchantEmail">Work email</label>
                                <input class="form-control" id="merchantEmail" value="{{ Auth::guard("ecomm")->user()->email }}" name="email" type="email" required placeholder="Enter your E-mail" />
                                <div class="invalid-feedback">Enter a valid email address.</div>
                            </div>



                                <div class="col-12 col-md-6">
                                    @error("phone")
                                    <p class="alert alert-danger">{{ $message }}</p>
                                @enderror
                                <label class="form-label" for="staffPhone">Phone</label>
                                <input class="form-control" id="staffPhone" value="{{ Auth::guard("ecomm")->user()->phone }}"  name="phone" type="tel" placeholder="Enter your phone!" required />
                                <div class="invalid-feedback">Use digits, spaces and + ( ) - only.</div>
                            </div>





                        </div>
                        </div>

                </form>
            @endif

            @if(auth("dashboard")->check())

                <div class="card mb-3">
                <div class="card-header">
                    <div class="card-header-title">
                    <h2 class="card-title">Change Password</h2>
                    <p class="card-subtitle mb-0">Use at least 6 characters, including a number</p>
                    </div>
                </div>
                <div class="card-body">

                    <form id="passwordForm" action="{{ route("change.password") }}" method="post">
                        @csrf
                        @method("put")

                    <div class="row g-3">
                        @if(session("password"))

                            <p class="alert alert-<?= session("password") == 'Current password is not true' ? 'danger' : 'success' ?>">{{ session("password") }}</p>

                        @endif
                        <div class="col-12 col-md-4">
                        <label class="form-label" for="currentPassword">Current password</label>
                        <input class="form-control" id="currentPassword" name="current_password" type="password" required/>
                        <div class="invalid-feedback">Enter your current password.</div>
                        </div>
                        <div class="col-12 col-md-4">
                        <label class="form-label" for="newPassword">New password</label>
                        <input class="form-control" id="newPassword" name="new_password" type="password" required minlength="6" autocomplete="new-password" />
                        <div class="invalid-feedback">At least 8 characters, including a number.</div>
                        </div>
                        <div class="col-12 col-md-4">
                        <label class="form-label" for="confirmPassword">Confirm new password</label>
                        <input class="form-control" id="confirmPassword" type="password" required minlength="6" data-match="#newPassword" autocomplete="new-password" />
                        <div class="invalid-feedback">Passwords do not match.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa-solid fa-key me-2"></i>Update Password</button>
                    </div>
                    </form>

                </div>
                </div>
            @endif



          </div>


        </div>

      </main>
@endsection
