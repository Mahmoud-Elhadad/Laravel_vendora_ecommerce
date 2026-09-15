<x-navbar />

{{-- CSS --}}
        <style>
        .profile-photo-card{
            --v-accent:#6C5CE7;
            --v-accent-soft:#EFEBFC;
            --ink:#1F2430;
            --muted:#6B7280;
            --line:#E4E4E7;
            display:flex;
            align-items:center;
            gap:20px;
        }
        .profile-photo-card .avatar-wrap{
            position:relative;
            flex-shrink:0;
        }
        .profile-photo-card .avatar{
            width:88px;
            height:88px;
            border-radius:50%;
            background:var(--v-accent-soft);
            display:flex;
            align-items:center;
            justify-content:center;
            color:var(--v-accent);
            font-size:32px;
            border:1px solid var(--line);
            overflow:hidden;
        }
        .profile-photo-card .avatar img{width:100%;height:100%;object-fit:cover;}
        .profile-photo-card .badge{
            position:absolute;
            bottom:0;
            right:0;
            width:30px;
            height:30px;
            border-radius:50%;
            background:var(--v-accent);
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            border:3px solid #fff;
            cursor:pointer;
        }
        .profile-photo-card .info{flex:1; min-width:0;}
        .profile-photo-card .info h6{
            margin:0 0 4px;
            font-size:15px;
            font-weight:700;
            color:var(--ink);
        }
        .profile-photo-card .info p{
            margin:0 0 14px;
            font-size:13px;
            color:var(--muted);
        }
        .profile-photo-card .upload-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            border:1px solid var(--v-accent);
            color:var(--v-accent);
            background:transparent;
            padding:8px 16px;
            border-radius:10px;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
            transition:background .15s ease, color .15s ease;
        }
        .profile-photo-card .upload-btn:hover{
            background:var(--v-accent);
            color:#fff;
        }
        .profile-photo-card input[type=file]{display:none;}

        @media (max-width:420px){
            .profile-photo-card{flex-direction:column; text-align:center;}
        }
        </style>

 <section class="page-title-bar">
    <div class="container">
      <h1 id="page-title-text">Profile Settings</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-v">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("ecomm.show.accountPage") }}">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
      </nav>
    </div>
  </section>

  <main id="main-content">
<section class="section-sm">
        <div class="container">
          <div class="row g-4">
             <div class="col-lg-3"><div class="account-nav">
        <div class="an-head">
          <img  src="{{ asset("storage/images/clients/".$client->image) }}" alt="{{ $client->first_name }} {{ $client->last_name }}">
          <div><h6>{{ $client->first_name }} {{ $client->last_name }}</h6><span>{{ $client->email }}</span></div>
        </div>
        <ul>
          <li><a href="{{ route("ecomm.show.accountPage") }}" class=""><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
          <li><a href="{{ route("show.cart") }}" class=""><i class="fa-solid fa-heart"></i> Wishlist</a></li>
          <li><a href="{{ route("show.cart") }}" class=""><i class="fa-solid fa-bag-shopping"></i> Cart</a></li>
          <li><a href="{{ route("ecomm.showAndEdit.profilePage") }}" class="active"><i class="fa-solid fa-user"></i> Profile settings</a></li>
          <li><a href="{{ route('ecomm.logout') }}" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></li>
        </ul>
      </div></div>
            <div class="col-lg-9">
        <div class="checkout-card mb-4">
          <div class="cc-title"><h5>Personal information</h5></div>
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="profile-avatar">
              <img  src="{{ asset("storage/images/clients/".$client->image) }}" alt="Profile photo">

            </div>

          </div>

          <form action="{{ route("ecomm.update.ecommUser") }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
              <div class="col-md-6 mb-3">
                @error("first_name")
                    <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <label class="form-label" for="first_name">First name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="first_name"  value="{{ $client->first_name }}" name="first_name">
              </div>
              <div class="col-md-6 mb-3">
                @error("last_name")
                    <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <label class="form-label" for="last_name">Last name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="last_name" value="{{ $client->last_name }}" name="last_name">
              </div>
              <div class="col-md-6 mb-3">
                @error("email")
                    <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <label class="form-label" for="email">Email address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" value="{{ $client->email }}" name="email">
              </div>
              <div class="col-md-6 mb-3">
                @error("phone")
                    <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <label class="form-label" for="phone">Phone number</label>
                <input type="tel" class="form-control" id="phone" value="{{ $client->phone }}" name="phone">
              </div>
              <div class="col-md-12 mb-3">
                @error("image")
                    <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                    <div class="profile-photo-card">
                        <div class="avatar-wrap">
                        <div class="avatar" id="avatarPreview">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <label class="badge" for="photoInput">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                        </div>

                        <div class="info">
                        <h6>Update profile photo</h6>
                        <p>JPG or PNG, at least 200×200px</p>
                        <label class="upload-btn" for="photoInput">
                            <i class="fa-solid fa-upload"></i>
                            Upload new
                        </label>
                        <input type="file" name="image" id="photoInput" accept="image/png, image/jpeg">
                        </div>
                     </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary-v"><i class="fa-regular fa-floppy-disk"></i> Save changes</button>
          </form>

        </div>

        <div class="checkout-card">
          <div class="cc-title"><h5>Change password</h5></div>

          <form action="{{ route("ecomm.change.userPassword") }}" method="post">
            @csrf

            @if(session("password"))
                <p class="alert alert-<?= session('password') == 'your password changed successfully' ? 'success' : 'danger' ?>">{{ session('password') }}</p>
            @endif

            <div class="row">
                @error("current_password")
                    <div class="col-md-12 mb-3">
                        <p class="alert alert-danger">{{ $message }}</p>
                    </div>
                @enderror
              <div class="col-md-4 mb-3">

                <label class="form-label" for="current-password">Current password</label>
                <div class="password-field">
                  <input type="password" class="form-control" id="current-password" name="current_password" placeholder="••••••••">
                </div>
              </div>
              @error("new_password")
                    <div class="col-md-12 mb-3">
                        <p class="alert alert-danger">{{ $message }}</p>
                    </div>
                @enderror
              <div class="col-md-4 mb-3">

                <label class="form-label" for="new-password">New password</label>
                <div class="password-field">
                  <input type="password" class="form-control" name="new_password" id="new-password" placeholder="Min. 6 characters">

                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label" for="password-confirm">Confirm new password</label>
                <input type="password" class="form-control" id="password-confirm" name="confirm" placeholder="Re-enter password">
              </div>
            </div>
            <button type="submit" class="btn btn-primary-v"><i class="fa-solid fa-key"></i> Update password</button>
          </form>

        </div></div>
          </div>
        </div>
      </section>
  </main>


  {{-- Script --}}
    <script>
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('avatarPreview');
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            preview.innerHTML = `<img src="${url}" alt="Profile photo">`;
        });
    </script>
<x-footer />
