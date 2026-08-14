var SUPER_ADMIN_REG_CODE = 'CIVIC2025';

window.addEventListener('load', function() {
  setTimeout(function() {
    var overlay = document.getElementById('skeletonOverlay');
    if (overlay) overlay.style.display = 'none';
  }, 1900);
});

var btnSuperAdmin = document.getElementById('btnSuperAdmin');
var btnSubAdmin = document.getElementById('btnSubAdmin');
var roleSlider = document.getElementById('roleSlider');
var panelSuperAdmin = document.getElementById('panelSuperAdmin');
var panelSubAdmin = document.getElementById('panelSubAdmin');
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

var switchToSuperBtn = document.getElementById('switchToSuperBtn');
switchToSuperBtn.addEventListener('click', function() {
  switchToSuper();
});

function makeEyeToggle(eyeBtn, passInput, eyeOpen, eyeClosed) {
  var show = false;
  eyeBtn.addEventListener('click', function() {
    show = !show;
    passInput.type = show ? 'text' : 'password';
    eyeOpen.style.display = show ? 'none' : 'block';
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
  var box = document.getElementById(boxId);
  var checkbox = document.getElementById(checkboxId);
  var label = document.getElementById(labelId);
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
var isSubCaptchaDone = makeRecaptcha('recaptchaBoxSub', 'rcCheckboxSub', 'rcLabelSub');

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

var loginBtnSuper = document.getElementById('loginBtnSuper');
loginBtnSuper.addEventListener('click', function() {
  var email = document.getElementById('superEmail').value.trim();
  var password = document.getElementById('superPassword').value;

  if (!email || !password) {
    shake(document.querySelector('.login-card'));
    return;
  }

  if (!isSuperCaptchaDone()) {
    shake(document.getElementById('recaptchaBoxSuper'));
    return;
  }

  loginBtnSuper.classList.add('loading');
  loginBtnSuper.disabled = true;

  setTimeout(function() {
    loginBtnSuper.classList.remove('loading');
    loginBtnSuper.disabled = false;
  }, 2500);
});

document.getElementById('superPassword').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') loginBtnSuper.click();
});

var loginBtnSub = document.getElementById('loginBtnSub');
loginBtnSub.addEventListener('click', function() {
  var email = document.getElementById('subEmail').value.trim();
  var password = document.getElementById('subPassword').value;

  if (!email || !password) {
    shake(document.querySelector('.login-card'));
    return;
  }

  if (!isSubCaptchaDone()) {
    shake(document.getElementById('recaptchaBoxSub'));
    return;
  }

  loginBtnSub.classList.add('loading');
  loginBtnSub.disabled = true;

  setTimeout(function() {
    loginBtnSub.classList.remove('loading');
    loginBtnSub.disabled = false;
  }, 2500);
});

document.getElementById('subPassword').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') loginBtnSub.click();
});

var modalBackdrop = document.getElementById('modalBackdrop');
var modalAccessCode = document.getElementById('modalAccessCode');
var modalSignup = document.getElementById('modalSignup');
var accessCodeInput = document.getElementById('accessCodeInput');
var accessCodeError = document.getElementById('accessCodeError');
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

  accessCodeSubmit.classList.add('loading');
  accessCodeSubmit.disabled = true;

  setTimeout(function() {
    accessCodeSubmit.classList.remove('loading');
    accessCodeSubmit.disabled = false;

    if (code === SUPER_ADMIN_REG_CODE) {
      accessCodeError.classList.remove('visible');
      modalAccessCode.style.display = 'none';
      modalSignup.style.display = 'block';
      modalSignup.style.animation = 'none';
      void modalSignup.offsetWidth;
      modalSignup.style.animation = 'modalIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) both';
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
  var name = document.getElementById('signupName').value.trim();
  var email = document.getElementById('signupEmail').value.trim();
  var password = document.getElementById('signupPassword').value;

  if (!name || !email || !password) {
    shake(modalSignup);
    return;
  }

  signupSubmit.classList.add('loading');
  signupSubmit.disabled = true;

  setTimeout(function() {
    signupSubmit.classList.remove('loading');
    signupSubmit.disabled = false;
  }, 2500);
});

document.getElementById('backToLoginLink').addEventListener('click', function(e) {
  e.preventDefault();
  closeModal();
});