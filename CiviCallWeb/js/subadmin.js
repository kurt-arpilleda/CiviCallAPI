var modalBackdrop = document.getElementById('modalBackdrop');
var modalRegister = document.getElementById('modalRegister');
var registerTriggerBtn = document.getElementById('registerTriggerBtn');
var closeRegister = document.getElementById('closeRegister');
var registerSubmit = document.getElementById('registerSubmit');

function openModal() {
    modalBackdrop.classList.add('open');
}

function closeModal() {
    modalBackdrop.classList.remove('open');
}

registerTriggerBtn.addEventListener('click', openModal);
closeRegister.addEventListener('click', closeModal);

modalBackdrop.addEventListener('click', function(e) {
    if (e.target === modalBackdrop) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

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

registerSubmit.addEventListener('click', function() {
    var name = document.getElementById('registerName').value.trim();
    var email = document.getElementById('registerEmail').value.trim();
    var password = document.getElementById('registerPassword').value;
    var campusId = document.getElementById('registerCampus').value;

    hideMsg('registerErrorMsg');
    hideMsg('registerSuccessMsg');

    if (!name || !email || !password || !campusId) {
        showMsg('registerErrorMsg', 'All fields are required.', true);
        return;
    }

    setLoading(registerSubmit, true);

    var formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('campusId', campusId);

    fetch('ajax/subadminSignup.php', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        setLoading(registerSubmit, false);
        if (data.success) {
            showMsg('registerSuccessMsg', data.message, false);
            setTimeout(function() {
                window.location.reload();
            }, 1200);
        } else {
            showMsg('registerErrorMsg', data.message, true);
        }
    })
    .catch(function() {
        setLoading(registerSubmit, false);
        showMsg('registerErrorMsg', 'Network error. Please try again.', true);
    });
});