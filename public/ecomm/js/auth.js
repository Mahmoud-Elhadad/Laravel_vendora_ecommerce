/* ==========================================================================
   Vendora E-commerce — Auth (auth.js)
   Frontend-only auth pages: sign in, sign up, forgot & reset password.
   Includes password visibility toggle, strength meter and validation.
   NOTE: No real authentication — forms are simulated for demo purposes.
   Depends on: jQuery, Bootstrap, main.js (Store)
   ========================================================================== */
(function (window, $) {
  'use strict';
  var Store = window.Store;

  /* ---------- Password visibility toggle ---------- */
  $(document).on('click', '.toggle-pass', function () {
    var $btn = $(this);
    var $input = $btn.closest('.password-field').find('input');
    var isText = $input.attr('type') === 'text';
    $input.attr('type', isText ? 'password' : 'text');
    $btn.find('i').attr('class', isText ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye');
  });

  /* ---------- Password strength ---------- */
  function strengthScore(pw) {
    var score = 0;
    if (!pw) return 0;
    if (pw.length >= 8) score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    return Math.min(score, 5);
  }
  function updateStrength(pw) {
    var score = strengthScore(pw);
    var pct = [0, 20, 40, 60, 80, 100][score];
    var labels = ['', 'Very weak', 'Weak', 'Fair', 'Good', 'Strong'];
    var colors = ['#ef4444', '#ef4444', '#f59e0b', '#f59e0b', '#10b981', '#10b981'];
    $('.strength-meter .sm-fill').css({ width: pct + '%', background: colors[score] });
    $('.strength-text').text(pw ? labels[score] : '').css('color', colors[score]);
    return score;
  }
  $(document).on('input', '#password, #new-password, #signup-password', function () { updateStrength($(this).val()); });

  /* ---------- Validators ---------- */
  function isEmail(v) { return /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(v); }
  function markInvalid($input, invalid) {
    $input.toggleClass('is-invalid', !!invalid).toggleClass('is-valid', !invalid && $input.val().length > 0);
    var $fb = $input.siblings('.invalid-feedback');
    if (invalid && typeof invalid === 'string' && $fb.length) $fb.text(invalid);
  }

  /* ---------- Sign in ---------- */
  function initSignIn() {
    var $form = $('#signin-form');
    if (!$form.length) return;
    $form.on('submit', function (e) {
      e.preventDefault();
      var email = $('#signin-email').val().trim();
      var pw = $('#signin-password').val();
      var ok = true;
      if (!isEmail(email)) { markInvalid($('#signin-email'), 'Enter a valid email address.'); ok = false; } else markInvalid($('#signin-email'), false);
      if (pw.length < 6) { markInvalid($('#signin-password'), 'Password must be at least 6 characters.'); ok = false; } else markInvalid($('#signin-password'), false);
      if (!ok) return;
      var $btn = $form.find('button[type="submit"]');
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Signing in...');
      setTimeout(function () {
        Store.toast('Welcome back! Redirecting to your account...', 'success');
        setTimeout(function () { window.location.href = 'my-account.html'; }, 900);
      }, 900);
    });
  }

  /* ---------- Sign up ---------- */
  function initSignUp() {
    var $form = $('#signup-form');
    if (!$form.length) return;
    $form.on('submit', function (e) {
      e.preventDefault();
      var fields = {
        first: $('#signup-first').val().trim(),
        last: $('#signup-last').val().trim(),
        email: $('#signup-email').val().trim(),
        phone: $('#signup-phone').val().trim(),
        pw: $('#signup-password').val(),
        confirm: $('#signup-confirm').val()
      };
      var ok = true;
      if (fields.first.length < 2) { markInvalid($('#signup-first'), 'First name is required.'); ok = false; } else markInvalid($('#signup-first'), false);
      if (fields.last.length < 2) { markInvalid($('#signup-last'), 'Last name is required.'); ok = false; } else markInvalid($('#signup-last'), false);
      if (!isEmail(fields.email)) { markInvalid($('#signup-email'), 'Enter a valid email address.'); ok = false; } else markInvalid($('#signup-email'), false);
      if (fields.phone && !/^[0-9+\-()\s]{7,}$/.test(fields.phone)) { markInvalid($('#signup-phone'), 'Enter a valid phone number.'); ok = false; } else markInvalid($('#signup-phone'), false);
      if (strengthScore(fields.pw) < 3) { markInvalid($('#signup-password'), 'Use 8+ characters with a mix of letters, numbers & symbols.'); ok = false; } else markInvalid($('#signup-password'), false);
      if (fields.confirm !== fields.pw || !fields.confirm) { markInvalid($('#signup-confirm'), 'Passwords do not match.'); ok = false; } else markInvalid($('#signup-confirm'), false);
      if (!$('#signup-terms').is(':checked')) { $('#terms-error').removeClass('d-none'); ok = false; } else $('#terms-error').addClass('d-none');
      if (!ok) return;
      var $btn = $form.find('button[type="submit"]');
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Creating account...');
      setTimeout(function () {
        Store.toast('Account created successfully! Welcome to Vendora.', 'success');
        setTimeout(function () { window.location.href = 'my-account.html'; }, 900);
      }, 1000);
    });
    // Live confirm-password check
    $('#signup-confirm').on('input', function () {
      if ($(this).val() && $(this).val() !== $('#signup-password').val()) markInvalid($(this), 'Passwords do not match.');
      else markInvalid($(this), false);
    });
  }

  /* ---------- Forgot password ---------- */
  function initForgot() {
    var $form = $('#forgot-form');
    if (!$form.length) return;
    $form.on('submit', function (e) {
      e.preventDefault();
      var email = $('#forgot-email').val().trim();
      if (!isEmail(email)) {
        markInvalid($('#forgot-email'), 'Enter a valid email address.');
        $('#forgot-success').addClass('d-none');
        return;
      }
      markInvalid($('#forgot-email'), false);
      var $btn = $form.find('button[type="submit"]');
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Sending...');
      setTimeout(function () {
        $form.addClass('d-none');
        $('#forgot-success').removeClass('d-none').find('.sent-email').text(email);
      }, 1000);
    });
  }

  /* ---------- Reset password ---------- */
  function initReset() {
    var $form = $('#reset-form');
    if (!$form.length) return;
    $form.on('submit', function (e) {
      e.preventDefault();
      var pw = $('#new-password').val(), confirm = $('#confirm-password').val();
      var ok = true;
      if (strengthScore(pw) < 3) { markInvalid($('#new-password'), 'Use 8+ characters with a mix of letters, numbers & symbols.'); ok = false; } else markInvalid($('#new-password'), false);
      if (confirm !== pw || !confirm) { markInvalid($('#confirm-password'), 'Passwords do not match.'); ok = false; } else markInvalid($('#confirm-password'), false);
      if (!ok) return;
      var $btn = $form.find('button[type="submit"]');
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Resetting...');
      setTimeout(function () {
        Store.toast('Password reset successfully. Redirecting to sign in...', 'success');
        setTimeout(function () { window.location.href = 'signin.html'; }, 1200);
      }, 1000);
    });
    $('#confirm-password').on('input', function () {
      if ($(this).val() && $(this).val() !== $('#new-password').val()) markInvalid($(this), 'Passwords do not match.');
      else markInvalid($(this), false);
    });
  }

  $(function () {
    initSignIn();
    initSignUp();
    initForgot();
    initReset();
    // Initialize strength meter if present
    if ($('.strength-meter').length) updateStrength($('#password, #new-password').val() || '');
  });

})(window, jQuery);
