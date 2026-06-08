<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CiviCall Admin — Sign In</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles/style.css">
</head>
<body>

<div class="skeleton-overlay" id="skeletonOverlay">
  <div class="skel-left">
    <div style="display:flex;align-items:center;gap:14px;">
      <div class="skel-circle-dark" style="width:48px;height:48px;"></div>
      <div>
        <div class="skel-block-dark" style="width:100px;height:14px;margin-bottom:6px;"></div>
        <div class="skel-block-dark" style="width:60px;height:10px;border-radius:20px;"></div>
      </div>
    </div>
    <div>
      <div class="skel-block-dark" style="width:90%;height:28px;margin-bottom:12px;border-radius:6px;"></div>
      <div class="skel-block-dark" style="width:75%;height:28px;margin-bottom:18px;border-radius:6px;"></div>
      <div class="skel-block-dark" style="width:100%;height:13px;margin-bottom:8px;border-radius:4px;"></div>
      <div class="skel-block-dark" style="width:85%;height:13px;border-radius:4px;"></div>
      <div style="display:flex;gap:16px;margin-top:32px;">
        <div class="skel-block-dark" style="flex:1;height:70px;border-radius:14px;"></div>
        <div class="skel-block-dark" style="flex:1;height:70px;border-radius:14px;"></div>
        <div class="skel-block-dark" style="flex:1;height:70px;border-radius:14px;"></div>
      </div>
    </div>
    <div class="skel-block-dark" style="width:180px;height:11px;border-radius:4px;"></div>
  </div>
  <div class="skel-right">
    <div class="skel-card">
      <div class="skel-block" style="width:160px;height:36px;margin-bottom:28px;border-radius:40px;"></div>
      <div class="skel-circle" style="width:58px;height:58px;margin-bottom:20px;border-radius:16px;"></div>
      <div class="skel-block" style="width:160px;height:22px;margin-bottom:10px;border-radius:6px;"></div>
      <div class="skel-block" style="width:220px;height:14px;margin-bottom:28px;border-radius:4px;"></div>
      <div class="skel-block" style="width:70px;height:12px;margin-bottom:8px;border-radius:4px;"></div>
      <div class="skel-block" style="width:100%;height:50px;margin-bottom:20px;border-radius:12px;"></div>
      <div class="skel-block" style="width:70px;height:12px;margin-bottom:8px;border-radius:4px;"></div>
      <div class="skel-block" style="width:100%;height:50px;margin-bottom:20px;border-radius:12px;"></div>
      <div style="display:flex;justify-content:space-between;margin-bottom:22px;">
        <div class="skel-block" style="width:110px;height:14px;border-radius:4px;"></div>
        <div class="skel-block" style="width:90px;height:14px;border-radius:4px;"></div>
      </div>
      <div class="skel-block" style="width:100%;height:52px;margin-bottom:22px;border-radius:40px;"></div>
      <div style="display:flex;justify-content:center;gap:8px;">
        <div class="skel-block" style="width:140px;height:12px;border-radius:4px;"></div>
      </div>
    </div>
  </div>
</div>

<div class="left-panel">
  <div class="deco-ring ring3"></div>
  <div class="deco-ring ring1"></div>
  <div class="deco-ring ring2"></div>

  <div class="left-top">
    <div class="brand">
      <div class="brand-icon">
        <img src="assets/images/icon.png" alt="CiviCall" onerror="this.parentElement.innerHTML='<span style=\'font-size:28px;font-weight:900;color:white;\'>CC</span>'">
      </div>
      <div>
        <div class="brand-name">CiviCall</div>
        <div class="brand-badge">Admin Portal</div>
      </div>
    </div>
  </div>

  <div class="left-middle">
    <h1 class="left-headline">
      Manage your<br><span>community</span><br>with clarity.
    </h1>
    <p class="left-desc">Oversee civic engagements, user verifications, and community activity all from one secure admin dashboard.</p>
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-num">2.4k</div>
        <div class="stat-label">Active Users</div>
      </div>
      <div class="stat-card">
        <div class="stat-num">186</div>
        <div class="stat-label">Engagements</div>
      </div>
      <div class="stat-card">
        <div class="stat-num">98%</div>
        <div class="stat-label">Uptime</div>
      </div>
    </div>
  </div>

  <div class="left-bottom">
    &copy; 2025 CiviCall. All rights reserved.
  </div>
</div>

<div class="right-panel">
  <div class="login-card" id="loginCard">

    <div class="role-toggle" id="roleToggle">
      <button class="role-btn active" id="btnSuperAdmin" type="button">Super Admin</button>
      <button class="role-btn" id="btnSubAdmin" type="button">Sub Admin</button>
      <div class="role-slider" id="roleSlider"></div>
    </div>

    <div id="panelSuperAdmin" class="role-panel">
      <div class="card-icon">
        <img src="assets/images/icon.png" alt="CiviCall" onerror="this.parentElement.innerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23D53A47\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'width:38px;height:38px;\'><rect x=\'3\' y=\'11\' width=\'18\' height=\'11\' rx=\'2\' ry=\'2\'></rect><path d=\'M7 11V7a5 5 0 0 1 10 0v4\'></path></svg>'">
      </div>
      <h2 class="card-title">Super Admin</h2>
      <p class="card-sub">Restricted access. Authorized personnel only.</p>

      <div id="superErrorMsg" class="form-error-msg" style="display:none;"></div>
      <div id="superSuccessMsg" class="form-success-msg" style="display:none;"></div>

      <div class="form-group">
        <label for="superEmail">Email Address</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </span>
          <input type="email" id="superEmail" placeholder="superadmin@civicall.app" autocomplete="email">
        </div>
      </div>

      <div class="form-group">
        <label for="superPassword">Password</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          </span>
          <input type="password" id="superPassword" placeholder="••••••••" autocomplete="current-password">
          <button class="eye-btn" id="eyeBtnSuper" type="button" aria-label="Toggle password visibility">
            <svg id="eyeOpenSuper" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg id="eyeClosedSuper" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>
      </div>

      <div class="form-footer form-group">
        <label class="remember-wrap" style="text-transform:none;font-size:13px;font-weight:500;color:var(--gray-400);letter-spacing:0;">
          <input type="checkbox" id="rememberSuper">
          <span>Remember me</span>
        </label>
        <a href="#" class="forgot-link">Forgot Password?</a>
      </div>

      <div class="recaptcha-wrap">
        <div class="recaptcha-box" id="recaptchaBoxSuper">
          <div class="rc-checkbox" id="rcCheckboxSuper">
            <div class="rc-spinner"></div>
            <div class="rc-check">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
          </div>
          <span class="rc-label" id="rcLabelSuper">I'm not a robot</span>
          <div class="rc-logo">
            <span class="rc-logo-mark">🔒</span>
            <div class="rc-logo-text">reCAPTCHA<br><a href="#">Privacy</a> · <a href="#">Terms</a></div>
          </div>
        </div>
      </div>

      <button class="btn-login" id="loginBtnSuper" type="button">
        <span class="btn-text">Sign In as Super Admin</span>
        <span class="btn-loader"><span class="spinner"></span></span>
      </button>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">New Super Admin?</span>
        <div class="divider-line"></div>
      </div>

      <div class="card-footer">
        <button class="switch-to-super-btn" id="signupTriggerBtn" type="button">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
          Create a Super Admin Account
        </button>
      </div>
    </div>

    <div id="panelSubAdmin" class="role-panel" style="display:none;">
      <div class="card-icon">
        <img src="assets/images/icon.png" alt="CiviCall" onerror="this.parentElement.innerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23D53A47\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'width:38px;height:38px;\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>'">
      </div>
      <h2 class="card-title">Sub Admin</h2>
      <p class="card-sub">Campus-level access. Sign in with your credentials.</p>

      <div id="subErrorMsg" class="form-error-msg" style="display:none;"></div>
      <div id="subSuccessMsg" class="form-success-msg" style="display:none;"></div>

      <div class="form-group">
        <label for="subEmail">Email Address</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </span>
          <input type="email" id="subEmail" placeholder="subadmin@civicall.app" autocomplete="email">
        </div>
      </div>

      <div class="form-group">
        <label for="subPassword">Password</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          </span>
          <input type="password" id="subPassword" placeholder="••••••••" autocomplete="current-password">
          <button class="eye-btn" id="eyeBtnSub" type="button" aria-label="Toggle password visibility">
            <svg id="eyeOpenSub" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg id="eyeClosedSub" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>
      </div>

      <div class="form-footer form-group">
        <label class="remember-wrap" style="text-transform:none;font-size:13px;font-weight:500;color:var(--gray-400);letter-spacing:0;">
          <input type="checkbox" id="rememberSub">
          <span>Remember me</span>
        </label>
        <a href="#" class="forgot-link">Forgot Password?</a>
      </div>

      <div class="recaptcha-wrap">
        <div class="recaptcha-box" id="recaptchaBoxSub">
          <div class="rc-checkbox" id="rcCheckboxSub">
            <div class="rc-spinner"></div>
            <div class="rc-check">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
          </div>
          <span class="rc-label" id="rcLabelSub">I'm not a robot</span>
          <div class="rc-logo">
            <span class="rc-logo-mark">🔒</span>
            <div class="rc-logo-text">reCAPTCHA<br><a href="#">Privacy</a> · <a href="#">Terms</a></div>
          </div>
        </div>
      </div>

      <button class="btn-login" id="loginBtnSub" type="button">
        <span class="btn-text">Sign In as Sub Admin</span>
        <span class="btn-loader"><span class="spinner"></span></span>
      </button>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">Are you a Super Admin?</span>
        <div class="divider-line"></div>
      </div>

      <div class="card-footer">
        <button class="switch-to-super-btn" id="switchToSuperBtn" type="button">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
          Go to Super Admin Login
        </button>
        <p class="card-footer-text" style="margin-top:14px;">
          Having trouble? Contact <a href="#">appcivicall@gmail.com</a><br>
          This portal is restricted to authorized sub-admins only.
        </p>
      </div>
    </div>

  </div>
</div>

<div class="modal-backdrop" id="modalBackdrop">

  <div class="modal-box" id="modalAccessCode">
    <button class="modal-close-btn" id="closeAccessCode" type="button" aria-label="Close">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <div class="modal-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D53A47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
    </div>
    <h3 class="modal-title">Access Required</h3>
    <p class="modal-sub">Enter the Super Admin registration code to proceed.</p>
    <div class="form-group" style="margin-bottom:10px;">
      <label for="accessCodeInput">Registration Code</label>
      <div class="input-wrap">
        <span class="input-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
        </span>
        <input type="password" id="accessCodeInput" placeholder="Enter registration code" autocomplete="off">
      </div>
    </div>
    <p class="access-code-error" id="accessCodeError">Incorrect code. Please try again.</p>
    <button class="btn-login" id="accessCodeSubmit" type="button" style="margin-top:6px;">
      <span class="btn-text">Verify Code</span>
      <span class="btn-loader"><span class="spinner"></span></span>
    </button>
  </div>

  <div class="modal-box" id="modalSignup" style="display:none;">
    <button class="modal-close-btn" id="closeSignup" type="button" aria-label="Close">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <div class="modal-icon signup-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D53A47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    </div>
    <h3 class="modal-title">Create Super Admin</h3>
    <p class="modal-sub">Fill in your details to register a new super admin account.</p>

    <div id="signupErrorMsg" class="form-error-msg" style="display:none;"></div>
    <div id="signupSuccessMsg" class="form-success-msg" style="display:none;"></div>

    <div class="form-group">
      <label for="signupName">Full Name</label>
      <div class="input-wrap">
        <span class="input-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </span>
        <input type="text" id="signupName" placeholder="Juan dela Cruz" autocomplete="name">
      </div>
    </div>

    <div class="form-group">
      <label for="signupEmail">Email Address</label>
      <div class="input-wrap">
        <span class="input-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </span>
        <input type="email" id="signupEmail" placeholder="superadmin@civicall.app" autocomplete="email">
      </div>
    </div>

    <div class="form-group" style="margin-bottom:6px;">
      <label for="signupPassword">Password</label>
      <div class="input-wrap">
        <span class="input-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </span>
        <input type="password" id="signupPassword" placeholder="••••••••" autocomplete="new-password">
        <button class="eye-btn" id="eyeBtnSignup" type="button" aria-label="Toggle password visibility">
          <svg id="eyeOpenSignup" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          <svg id="eyeClosedSignup" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
        </button>
      </div>
    </div>

    <button class="btn-login" id="signupSubmit" type="button" style="margin-top:18px;">
      <span class="btn-text">Create Account</span>
      <span class="btn-loader"><span class="spinner"></span></span>
    </button>

    <p class="card-footer-text" style="text-align:center;margin-top:14px;">
      Already have an account? <a href="#" id="backToLoginLink" style="color:var(--red);font-weight:700;text-decoration:none;">Sign In</a>
    </p>
  </div>

</div>

<script src="js/script.js"></script>
</body>
</html>