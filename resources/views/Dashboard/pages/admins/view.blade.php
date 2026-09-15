@extends("Dashboard.layout.main")

@section("body")




    <main class="main-content" id="main-content">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="page-header">
                <div class="page-header-text">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb page-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index"><i class="fa-solid fa-house-chimney"></i></a></li>
                <li class="breadcrumb-item" aria-current="false">Administration</li>
                <li class="breadcrumb-item active" aria-current="page">Staff</li>
                </ol>
            </nav>
                    <h1 class="page-title">Staff & Permissions</h1>
                </div>
                @if(Auth::guard("dashboard")->user()->role == "super admin")
                    <div class="page-header-actions">
                        <a href="{{ route("admin.create") }}" class="btn btn-primary" ><i class="fa-solid fa-user-plus me-2"></i>Add Staff</a>
                    </div>
                @endif
                </div>

                <!-- ================================================================
                    Staff — admin accounts, roles and permissions.
                    Blade equivalent: resources/views/staff/index.blade.php
                    ============================================================= -->


                <div class="card">
                <div class="table-toolbar" data-table-controls="staff">
                    <div class="toolbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" class="form-control form-control-sm" placeholder="Search name, email, role or location…" aria-label="Search staff" data-table-search />
                    </div>






                </div>

                <div class="bulk-bar" data-table-bulk="staff" hidden>
                    <strong><span data-bulk-count>0</span> selected</strong>
                    <button type="button" class="btn btn-sm btn-subtle" data-bulk-action="activate"><i class="fa-solid fa-user-check me-2"></i>Activate</button>
                    <button type="button" class="btn btn-sm btn-subtle" data-bulk-action="deactivate"><i class="fa-solid fa-user-slash me-2"></i>Suspend</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bulk-action="delete"><i class="fa-regular fa-trash-can me-2"></i>Remove</button>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 table-mobile-cards" data-table="staff" data-table-options='{"perPage":10,"sort":"member","dir":"asc","emptyTitle":"No staff match those filters","emptyText":"Try a different role, or invite a new team member.","emptyIcon":"fa-user-shield"}'>
                            <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">Member</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Age</th>
                                     @if(Auth::guard("dashboard")->user()->role == "super admin")

                                        <th scope="col">Edit</th>
                                        <th class="table-actions" scope="col">Delete</th>

                                     @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $key => $admin)

                                    <tr data-status="active" data-role="Super Admin">
                                        <td class="table-check">{{ ++$key }}</td>
                                        <td data-label="Member" class="has-entity" data-sort-value="{{ $admin->name }}"><span class="cell-entity"><img class="avatar avatar-md" src="{{ asset("storage/images/admins/".$admin->img) }}" alt="" loading="lazy" /><span class="cell-entity-text"><span class="cell-entity-title text-capitalize">{{ $admin->name }}</span><span class="cell-entity-sub">{{ $admin->email }}</span></span></span></td>
                                        <td data-label="Role" data-sort-value="{{ $admin->role }}"><span class="badge-soft text-capitalize">{{ $admin->role }}</span></td>
                                        <td data-label="gender" class="has-entity text-capitalize" data-sort-value="{{ $admin->gender }}">{{ $admin->gender }}</td>
                                        <td data-label="Phone" data-sort-value="{{ $admin->phone }}">{{ $admin->phone }}</td>
                                        <td class="text-capitalize" data-label="Location" data-sort-value="{{ $admin->location }}">{{ $admin->location }}</td>
                                        <td data-label="age" data-sort-value="{{ $admin->age }}">{{ $admin->age }}</td>


                                        @if(Auth::guard("dashboard")->user()->role == "super admin")

                                            <td>
                                             <a href="{{ route("admin.edit" , $admin->id) }}" class="btn btn-primary">Edit</a>
                                            </td>

                                             <td>
                                                 <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAdminModal-{{ $admin->id }}">
                                                     <i class="fa-regular fa-trash-can me-1"></i>
                                                 </button>
                                             </td>

                                        @endif

                                    </tr>

                                @endforeach


                            </tbody>
                    </table>

                </div>

                <div class="table-footer">
                    <span class="text-body-secondary small" data-table-info="staff"></span>
                    <nav aria-label="Staff pagination" data-table-pagination="staff"></nav>
                </div>
                </div>

                <!-- Delete Modals for all admins -->
                @foreach ($admins as $admin)
                    @include("Dashboard.layout.modal_deleteAdmin")
                @endforeach

                <!-- ================================================================
                    Invite-staff dialog (demo only — nothing is persisted)

      </main>


@endsection
