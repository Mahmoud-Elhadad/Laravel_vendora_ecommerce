@extends("Dashboard.layout.main")

@section("body")



    <div class="modal-header">
        <h2 class="modal-title" id="customerModalLabel">Add customer</h2>
        <a href="{{ route("customer.index") }}" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
    </div>

    <form action="{{ route("customer.store") }}" method="post">
        @csrf

        <div class="modal-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                     @error("first_name")
                            <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="customerFirstName">First name</label>
                    <input class="form-control" id="customerFirstName" value="{{ old('first_name') }}" name="first_name" type="text"  placeholder="First name" />
                    <div class="invalid-feedback">A first name is required.</div>
                </div>
                <div class="col-12 col-md-6">
                     @error("last_name")
                            <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="customerLastName">Last name</label>
                    <input class="form-control" id="customerLastName" value="{{ old('last_name') }}" name="last_name" type="text"  placeholder="Last name" />
                    <div class="invalid-feedback">A last name is required.</div>
                </div>
                <div class="col-12 col-md-6">
                     @error("email")
                            <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="customerEmail">Email</label>
                    <input class="form-control" id="customerEmail" value="{{ old('email') }}" name="email" type="email"  placeholder="customer@example.com" />
                    <div class="invalid-feedback">Enter a valid email address.</div>
                </div>
                <div class="col-12 col-md-6">
                     @error("phone")
                            <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    <label class="form-label" for="customerPhone">Phone</label>
                    <input class="form-control" id="customerPhone" value="{{ old('phone') }}" name="phone" type="text"  placeholder="EGY phone" />
                    <div class="invalid-feedback">Use digits, spaces and + ( ) - only.</div>
                </div>
                <div class="col-12 col-md-12">
                    @error("password")
                        <p class="alert alert-danger">{{ $message }}</p>
                    @enderror
                    <div class="col-12 col-md-12 d-flex  align-items-center gap-3">
                       <div class="col-12 col-md-6">

                           <label class="form-label" for="customerPassword"> Password</label>
                           <input class="form-control" id="customerPassword"   name="password" type="password"  placeholder="Enter a strong password!" />
                           <div class="invalid-feedback">Enter a valid password.</div>
                       </div>
                       <div class="col-12 col-md-6">

                           <label class="form-label" for="customerConfirm">Confirm password</label>
                           <input class="form-control" id="customerConfirm"  name="confirm" type="password"  placeholder="Enter a confirm password!" />
                           <div class="invalid-feedback">Enter a valid confirm password.</div>
                       </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
        <a href="{{ route("customer.index") }}" class="btn btn-subtle">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Save customer</button>
        </div>
    </form>


@endsection
