var SUPER_ADMIN_REG_CODE = 'CIVIC2025';
var verifiedCode = '';

window.addEventListener('load', function() {
  setTimeout(function() {
    var overlay = document.getElementById('skeletonOverlay');
    if (overlay) overlay.style.display = 'none';
  }, 1900);
});

var btnSuperAdmin = document.getElementById('btnSuperAdmin');
var btnSubAdmin   = document.getElementById('btnSubAdmin');
var roleSlider    = document.getElementById('roleSlider');
var panelSuperAdmin = document.getElementById('panelSuperAdmin');
var panelSubAdmin   = document.getElementById('panelSubAdmin');
var currentRole = 'super';

function switchToSuper() {
  if (currentRole === 'super') return;
  currentRole = 'super';
  btnSuperAdmin.classList.add('active');
  btnSubAdmin.classList.remove('active');
  roleSlider.classList.remove('slide-right');
  panelSubAdmin.style.display = 'none';
  panelSuperAdmin.style.display = 'block';
  panelSuperAdmin.style.animation = 'none';
  void panelSuperAdmin.offsetWidth;
  panelSuperAdmin.style.animation = '';
}

function switchToSub() {
  if (currentRole === 'sub') return;
  currentRole = 'sub';
  btnSubAdmin.classList.add('active');
  btnSuperAdmin.classList.remove('active');
  roleSlider.classList.add('slide-right');
  panelSuperAdmin.style.display = 'none';
  panelSubAdmin.style.display = 'block';
  panelSubAdmin.style.animation = 'none';
  void panelSubAdmin.offsetWidth;
  panelSubAdmin.style.animation = '';
}

btnSuperAdmin.addEventListener('click', switchToSuper);
btnSubAdmin.addEventListener('click', switchToSub);

document.getElementById('switchToSuperBtn').addEventListener('click', function() {
  switchToSuper();
});

function makeEyeToggle(eyeBtn, passInput, eyeOpen, eyeClosed) {
  var show = false;
  eyeBtn.addEventListener('click', function() {
    show = !show;
    passInput.type = show ? 'text' : 'password';
    eyeOpen.style.display  = show ? 'none' : 'block';
    eyeClosed.style.display = show ? 'block' : 'none';
  });
}

makeEyeToggle(
  document.getElementById('eyeBtnSuper'),
  document.getElementById('superPassword'),
  document.getElementById('eyeOpenSuper'),
  document.getElementById('eyeClosedSuper')
);

makeEyeToggle(
  document.getElementById('eyeBtnSub'),
  document.getElementById('subPassword'),
  document.getElementById('eyeOpenSub'),
  document.getElementById('eyeClosedSub')
);

makeEyeToggle(
  document.getElementById('eyeBtnSignup'),
  document.getElementById('signupPassword'),
  document.getElementById('eyeOpenSignup'),
  document.getElementById('eyeClosedSignup')
);

function makeRecaptcha(boxId, checkboxId, labelId) {
  var box      = document.getElementById(boxId);
  var checkbox = document.getElementById(checkboxId);
  var label    = document.getElementById(labelId);
  var done = false;
  var busy = false;

  box.addEventListener('click', function() {
    if (done || busy) return;
    busy = true;
    checkbox.classList.add('loading');
    label.textContent = 'Verifying…';

    setTimeout(function() {
      checkbox.classList.remove('loading');
      checkbox.classList.add('done');
      label.textContent = 'Verified';
      box.classList.add('checked');
      done = true;
      busy = false;
    }, 1400);
  });

  return function() { return done; };
}

var isSuperCaptchaDone = makeRecaptcha('recaptchaBoxSuper', 'rcCheckboxSuper', 'rcLabelSuper');
var isSubCaptchaDone   = makeRecaptcha('recaptchaBoxSub',   'rcCheckboxSub',   'rcLabelSub');

function shake(el) {
  el.style.animation = 'none';
  el.style.transition = 'transform 0.06s ease';
  var moves = [6, -6, 4, -4, 2, -2, 0];
  var i = 0;
  function step() {
    if (i < moves.length) {
      el.style.transform = 'translateX(' + moves[i] + 'px)';
      i++;
      setTimeout(step, 55);
    } else {
      el.style.transform = '';
      el.style.transition = '';
    }
  }
  step();
}

function showMsg(elId, text, isError) {
  var el = document.getElementById(elId);
  if (!el) return;
  el.textContent = text;
  el.style.display = 'block';
  if (isError) {
    el.className = 'form-error-msg';
  } else {
    el.className = 'form-success-msg';
  }
}

function hideMsg(elId) {
  var el = document.getElementById(elId);
  if (el) el.style.display = 'none';
}

function setLoading(btn, loading) {
  if (loading) {
    btn.classList.add('loading');
    btn.disabled = true;
  } else {
    btn.classList.remove('loading');
    btn.disabled = false;
  }
}

function doAjaxLogin(role, email, password, btn, errorId) {
  hideMsg(errorId);
  setLoading(btn, true);

  var formData = new FormData();
  formData.append('role',     role);
  formData.append('email',    email);
  formData.append('password', password);

  fetch('ajax/adminlogin.php', {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    setLoading(btn, false);
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      showMsg(errorId, data.message, true);
      shake(document.querySelector('.login-card'));
    }
  })
  .catch(function() {
    setLoading(btn, false);
    showMsg(errorId, 'Network error. Please try again.', true);
  });
}

var loginBtnSuper = document.getElementById('loginBtnSuper');
loginBtnSuper.addEventListener('click', function() {
  var email    = document.getElementById('superEmail').value.trim();
  var password = document.getElementById('superPassword').value;

  if (!email || !password) {
    shake(document.querySelector('.login-card'));
    return;
  }

  if (!isSuperCaptchaDone()) {
    shake(document.getElementById('recaptchaBoxSuper'));
    return;
  }

  doAjaxLogin('super', email, password, loginBtnSuper, 'superErrorMsg');
});

document.getElementById('superPassword').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') loginBtnSuper.click();
});

var loginBtnSub = document.getElementById('loginBtnSub');
loginBtnSub.addEventListener('click', function() {
  var email    = document.getElementById('subEmail').value.trim();
  var password = document.getElementById('subPassword').value;

  if (!email || !password) {
    shake(document.querySelector('.login-card'));
    return;
  }

  if (!isSubCaptchaDone()) {
    shake(document.getElementById('recaptchaBoxSub'));
    return;
  }

  doAjaxLogin('sub', email, password, loginBtnSub, 'subErrorMsg');
});

document.getElementById('subPassword').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') loginBtnSub.click();
});

var modalBackdrop    = document.getElementById('modalBackdrop');
var modalAccessCode  = document.getElementById('modalAccessCode');
var modalSignup      = document.getElementById('modalSignup');
var accessCodeInput  = document.getElementById('accessCodeInput');
var accessCodeError  = document.getElementById('accessCodeError');
var accessCodeSubmit = document.getElementById('accessCodeSubmit');

function openModal() {
  modalBackdrop.classList.add('open');
  modalAccessCode.style.display = 'block';
  modalSignup.style.display = 'none';
  accessCodeInput.value = '';
  accessCodeError.classList.remove('visible');
  accessCodeInput.focus();
}

function closeModal() {
  modalBackdrop.classList.remove('open');
}

document.getElementById('signupTriggerBtn').addEventListener('click', openModal);
document.getElementById('closeAccessCode').addEventListener('click', closeModal);
document.getElementById('closeSignup').addEventListener('click', closeModal);

modalBackdrop.addEventListener('click', function(e) {
  if (e.target === modalBackdrop) closeModal();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal();
});

accessCodeSubmit.addEventListener('click', function() {
  var code = accessCodeInput.value.trim();

  if (!code) {
    shake(modalAccessCode);
    return;
  }

  setLoading(accessCodeSubmit, true);

  setTimeout(function() {
    setLoading(accessCodeSubmit, false);

    if (code === SUPER_ADMIN_REG_CODE) {
      verifiedCode = code;
      accessCodeError.classList.remove('visible');
      modalAccessCode.style.display = 'none';
      modalSignup.style.display = 'block';
      modalSignup.style.animation = 'none';
      void modalSignup.offsetWidth;
      modalSignup.style.animation = 'modalIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) both';
      hideMsg('signupErrorMsg');
      hideMsg('signupSuccessMsg');
      document.getElementById('signupName').focus();
    } else {
      accessCodeError.classList.add('visible');
      shake(modalAccessCode);
      accessCodeInput.value = '';
      accessCodeInput.focus();
    }
  }, 900);
});

accessCodeInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') accessCodeSubmit.click();
});

var signupSubmit = document.getElementById('signupSubmit');
signupSubmit.addEventListener('click', function() {
  var name     = document.getElementById('signupName').value.trim();
  var email    = document.getElementById('signupEmail').value.trim();
  var password = document.getElementById('signupPassword').value;

  hideMsg('signupErrorMsg');
  hideMsg('signupSuccessMsg');

  if (!name || !email || !password) {
    shake(modalSignup);
    return;
  }

  setLoading(signupSubmit, true);

  var formData = new FormData();
  formData.append('name',     name);
  formData.append('email',    email);
  formData.append('password', password);
  formData.append('regCode',  verifiedCode);

  fetch('ajax/superadminSignup.php', {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    setLoading(signupSubmit, false);
    if (data.success) {
      showMsg('signupSuccessMsg', data.message, false);
      document.getElementById('signupName').value = '';
      document.getElementById('signupEmail').value = '';
      document.getElementById('signupPassword').value = '';
      setTimeout(function() {
        closeModal();
      }, 2000);
    } else {
      showMsg('signupErrorMsg', data.message, true);
      shake(modalSignup);
    }
  })
  .catch(function() {
    setLoading(signupSubmit, false);
    showMsg('signupErrorMsg', 'Network error. Please try again.', true);
  });
});

document.getElementById('signupPassword').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') signupSubmit.click();
});

document.getElementById('backToLoginLink').addEventListener('click', function(e) {
  e.preventDefault();
  closeModal();
});