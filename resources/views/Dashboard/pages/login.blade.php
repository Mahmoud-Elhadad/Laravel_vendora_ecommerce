<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
   <link rel="stylesheet" href="{{ asset("dashboard") }}/css/bootstrap.min.css" />
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    font-family: 'Poppins', sans-serif;
    background:#05040d;
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
  }

  .page {
    width:100%;
    min-height:100vh;
    display:flex;
    background:#0b0a1a;
    position:relative;
    overflow:hidden;
  }

  /* LEFT SIDE - Space Illustration */
  .left-panel {
    flex:1.1;
    position:relative;
    background: radial-gradient(circle at 30% 90%, #1a1440 0%, #0b0a1a 55%),
                #0b0a1a;
    overflow:hidden;
    min-height:100vh;
  }

  .logo {
    position:absolute;
    top:40px;
    left:40px;
    display:flex;
    align-items:center;
    gap:10px;
    z-index:5;
    color:#f5f4fb;
  }
  .logo-icon {
    width:34px;
    height:34px;
    border:2px solid #f5f4fb;
    border-radius:6px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:16px;
    transform: skew(-6deg);
  }
  .logo-text {
    line-height:1.1;
  }
  .logo-text .name {
    font-weight:700;
    font-size:18px;
    letter-spacing:0.5px;
  }
  .logo-text .sub {
    font-size:9px;
    letter-spacing:3px;
    color:#a9a6c9;
  }

  /* Stars */
  .stars {
    position:absolute;
    inset:0;
    background-image:
      radial-gradient(1.5px 1.5px at 20% 15%, #fff, transparent),
      radial-gradient(1.5px 1.5px at 45% 8%, #fff, transparent),
      radial-gradient(1px 1px at 70% 20%, #fff, transparent),
      radial-gradient(1.5px 1.5px at 85% 35%, #fff, transparent),
      radial-gradient(1px 1px at 60% 55%, #fff, transparent),
      radial-gradient(1.5px 1.5px at 30% 70%, #fff, transparent),
      radial-gradient(1px 1px at 15% 85%, #fff, transparent),
      radial-gradient(1.5px 1.5px at 90% 75%, #fff, transparent),
      radial-gradient(1px 1px at 50% 90%, #fff, transparent),
      radial-gradient(1px 1px at 75% 92%, #fff, transparent);
    background-repeat:no-repeat;
  }

  .big-star {
    position:absolute;
    top:22%;
    right:20%;
    width:22px;
    height:22px;
    color:#fff;
    filter:drop-shadow(0 0 6px #fff);
    z-index:2;
  }

  .shooting-star {
    position:absolute;
    width:2px;
    height:150px;
    background:linear-gradient(to bottom, rgba(255,255,255,0.9), transparent);
    transform:rotate(35deg);
    z-index:2;
  }
  .shooting-star.s1 { top:8%; right:28%; height:220px; }
  .shooting-star.s2 { top:55%; left:8%; height:130px; }
  .shooting-star.s3 { top:60%; left:38%; height:170px; opacity:0.7; }

  .planet-blue {
    position:absolute;
    top:-15%;
    left:-18%;
    width:70%;
    aspect-ratio:1/1;
    border-radius:50%;
    background: radial-gradient(circle at 35% 30%, #7fe3e0 0%, #3fb6c9 25%, #2a7fb0 55%, #1a3a6b 80%);
    box-shadow: inset -30px -30px 80px rgba(0,0,0,0.5), inset 20px 20px 60px rgba(255,255,255,0.15);
    overflow:hidden;
    z-index:1;
  }
  .planet-blue::before {
    content:'';
    position:absolute;
    inset:0;
    background:
      radial-gradient(circle at 60% 20%, rgba(20,60,90,0.5) 0%, transparent 30%),
      radial-gradient(circle at 20% 60%, rgba(15,50,80,0.6) 0%, transparent 35%),
      radial-gradient(circle at 75% 70%, rgba(10,40,70,0.5) 0%, transparent 30%);
  }

  .ring {
    position:absolute;
    top:38%;
    left:8%;
    width:55%;
    height:110px;
    border:1px solid rgba(200,190,255,0.25);
    border-radius:50%;
    transform:rotate(-8deg);
    z-index:2;
  }

  .planet-moon {
    position:absolute;
    top:42%;
    left:32%;
    width:22%;
    aspect-ratio:1/1;
    border-radius:50%;
    background: radial-gradient(circle at 35% 30%, #f2e9ff 0%, #cbb8e8 35%, #8f76b8 65%, #4a3766 90%);
    z-index:3;
    box-shadow: 0 0 40px rgba(180,150,230,0.25);
  }
  .planet-moon::after {
    content:'';
    position:absolute;
    inset:0;
    border-radius:50%;
    background:
      linear-gradient(90deg, transparent 60%, rgba(120,90,60,0.35) 65%, rgba(120,90,60,0.15) 75%, transparent 85%);
  }

  .planet-small {
    position:absolute;
    bottom:8%;
    left:14%;
    width:14%;
    aspect-ratio:1/1;
    border-radius:50%;
    background: radial-gradient(circle at 35% 30%, #b79fd4 0%, #6b4f8f 50%, #2d1f47 85%);
    z-index:2;
  }

  .glow-warm {
    position:absolute;
    bottom:20%;
    left:40%;
    width:180px;
    height:180px;
    background: radial-gradient(circle, rgba(255,180,120,0.35) 0%, transparent 70%);
    border-radius:50%;
    z-index:1;
  }

  .tagline {
    position:absolute;
    bottom:60px;
    left:40px;
    z-index:5;
    line-height:1.25;
  }
  .tagline .l1 {
    font-size:32px;
    font-weight:800;
    color:#f5f4fb;
    letter-spacing:0.5px;
  }
  .tagline .l2 {
    font-size:32px;
    font-weight:800;
    color:#8b7fd6;
    letter-spacing:0.5px;
  }

  /* RIGHT SIDE - Form */
  .right-panel {
    flex:0.85;
    background:#0e0c22;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:60px 70px;
    position:relative;
  }

  .right-panel h1 {
    font-size:44px;
    font-weight:800;
    color:#f5f4fb;
    letter-spacing:2px;
    margin-bottom:30px;
  }

  .field-label {
    font-size:13px;
    font-weight:600;
    color:#e6e3f5;
    margin-bottom:12px;
  }

  .input-wrap {
    display:flex;
    align-items:center;
    gap:10px;
    background:#1b1836;
    border:1px solid #2c2856;
    border-radius:10px;
    padding:15px 18px;
    margin-bottom:20px;
  }
  .input-wrap svg { flex-shrink:0; color:#8b86ad; }
  .input-wrap input {
    background:transparent;
    border:none;
    outline:none;
    color:#e6e3f5;
    font-family:'Poppins',sans-serif;
    font-size:14px;
    width:100%;
  }
  .input-wrap input::placeholder { color:#7d7898; }

  .toggle-pass {
    background:transparent;
    border:none;
    outline:none;
    color:#8b86ad;
    cursor:pointer;
    display:flex;
    align-items:center;
    flex-shrink:0;
  }
  .toggle-pass:hover { color:#e6e3f5; }

  .btn-signup {
    width:100%;
    padding:16px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg, #8b7fd6 0%, #5aa8d8 100%);
    color:#fff;
    font-family:'Poppins',sans-serif;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    margin-bottom:26px;
    transition:opacity 0.2s ease;
  }
  .btn-signup:hover { opacity:0.9; }

  .divider {
    border:none;
    border-top:1px solid #24214a;
    margin-bottom:26px;
  }

  .continue-label {
    font-size:13px;
    font-weight:600;
    color:#e6e3f5;
    margin-bottom:16px;
  }

  .social-row {
    display:flex;
    gap:16px;
    margin-bottom:22px;
  }
  .social-btn {
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    background:#1b1836;
    border:1px solid #2c2856;
    border-radius:10px;
    padding:13px;
    color:#e6e3f5;
    font-family:'Poppins',sans-serif;
    font-weight:600;
    font-size:14px;
    cursor:pointer;
    transition:background 0.2s ease;
  }
  .social-btn:hover { background:#221d47; }

  .terms {
    font-size:12px;
    color:#8b86ad;
  }
  .terms a {
    color:#8b7fd6;
    text-decoration:none;
  }
  .terms a:hover { text-decoration:underline; }

  @media (max-width:900px) {
    .page { flex-direction:column; }
    .left-panel { min-height:340px; }
    .right-panel { padding:40px 30px; }
  }
</style>
</head>
<body>
<div class="page">

  <div class="left-panel">
    <div class="stars"></div>


    <div class="planet-blue"></div>
    <div class="ring"></div>
    <div class="planet-moon"></div>
    <div class="planet-small"></div>
    <div class="glow-warm"></div>

    <svg class="big-star" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z"/>
    </svg>

    <div class="shooting-star s1"></div>
    <div class="shooting-star s2"></div>
    <div class="shooting-star s3"></div>

    <div class="tagline">
      <div class="l1">SIGN IN TO YOUR</div>
      <div class="l2">ADVENTURE!</div>
    </div>
  </div>

  <div class="right-panel">
    <h1>SIGN IN</h1>

    <form id="signin-form" action="{{ route("dash.check") }}" method="POST">

        @csrf

        @if(session('error'))
            <p class="alert alert-danger">{{ session("error") }}</p>
        @endif


      <div class="field-label">E-mail address</div>
        @error("email")
            <p class="alert alert-danger">{{ $message }}</p>
        @enderror
      <div class="input-wrap">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <path d="M2 6l10 7 10-7"/>
        </svg>
        <input type="text" id="email" name="email" placeholder="Yourname@gmail.com" >
      </div>

      <div class="field-label">Password</div>
        @error("password")
            <p class="alert alert-danger">{{ $message }}</p>
        @enderror
      <div class="input-wrap">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="4" y="10" width="16" height="10" rx="2"/>
          <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
        </svg>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <button type="button" class="toggle-pass" id="togglePass" aria-label="Show password">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>

      <button type="submit" class="btn-signup">Sign in</button>
    </form>

    <hr class="divider">




    <div class="terms">By registering you with our <a href="#">Terms and Conditions</a></div>
  </div>

</div>
<script>
  const toggleBtn = document.getElementById('togglePass');
  const passInput = document.getElementById('password');

  toggleBtn.addEventListener('click', () => {
    const isHidden = passInput.type === 'password';
    passInput.type = isHidden ? 'text' : 'password';
    toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
  });


</script>
</body>
</html>
