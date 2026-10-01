window.addEventListener('load', function() {
    setTimeout(function() {
        var overlay = document.getElementById('skeletonOverlay');
        if (overlay) {
            overlay.style.animation = 'skeletonFadeOut 0.4s ease forwards';
            setTimeout(function() { overlay.style.display = 'none'; }, 400);
        }
    }, 800);
});

const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
menuToggle.addEventListener('click', function(e) {
    e.stopPropagation();
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(event) {
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('open');
        }
    }
});
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        sidebar.classList.remove('open');
    }
});

const modal = document.getElementById('verificationModal');
const closeModalBtn = document.getElementById('closeModalBtn');

function closeModal() {
    modal.style.display = 'none';
}
closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

const confirmDialog = document.getElementById('confirmDialog');
const confirmIcon = document.getElementById('confirmIcon');
const confirmTitle = document.getElementById('confirmTitle');
const confirmMessage = document.getElementById('confirmMessage');
const confirmOkBtn = document.getElementById('confirmOkBtn');
const confirmCancelBtn = document.getElementById('confirmCancelBtn');
let confirmResolver = null;

function showConfirm(options) {
    const type = options.type || 'approve';
    confirmTitle.textContent = options.title || 'Are you sure?';
    confirmMessage.textContent = options.message || '';
    confirmOkBtn.textContent = options.confirmText || 'Confirm';
    confirmOkBtn.className = 'confirm-btn confirm-' + type;
    confirmIcon.className = 'confirm-icon confirm-icon-' + type;
    confirmIcon.innerHTML = '<i class="fas ' + (options.icon || 'fa-question') + '"></i>';
    confirmCancelBtn.style.display = options.hideCancel ? 'none' : '';
    confirmDialog.style.display = 'flex';
    confirmOkBtn.focus();
    return new Promise(function(resolve) {
        confirmResolver = resolve;
    });
}

function closeConfirm(result) {
    confirmDialog.style.display = 'none';
    if (confirmResolver) {
        const resolve = confirmResolver;
        confirmResolver = null;
        resolve(result);
    }
}

confirmOkBtn.addEventListener('click', function() { closeConfirm(true); });
confirmCancelBtn.addEventListener('click', function() { closeConfirm(false); });
confirmDialog.addEventListener('click', function(e) {
    if (e.target === confirmDialog) closeConfirm(false);
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && confirmDialog.style.display === 'flex') closeConfirm(false);
});

function confirmReview(userId, action) {
    const approve = action === 'approve';
    const viewBtn = document.querySelector('.view-btn[data-user-id="' + userId + '"]');
    const name = viewBtn && viewBtn.dataset.name ? viewBtn.dataset.name : 'this user';
    return showConfirm({
        type: approve ? 'approve' : 'reject',
        icon: approve ? 'fa-check' : 'fa-times',
        title: approve ? 'Approve Verification' : 'Reject Verification',
        message: approve
            ? 'Approve the verification request of ' + name + '? The user will be marked as verified.'
            : 'Reject the verification request of ' + name + '? The user will be marked as rejected.',
        confirmText: approve ? 'Yes, Approve' : 'Yes, Reject'
    }).then(function(ok) {
        return ok ? reviewVerification(userId, action) : null;
    });
}

function setRowProcessing(userId, disabled) {
    document.querySelectorAll('.action-btn[data-user-id="' + userId + '"]').forEach(btn => {
        btn.disabled = disabled;
    });
}

function reviewVerification(userId, action) {
    setRowProcessing(userId, true);
    const formData = new FormData();
    formData.append('userId', userId);
    formData.append('action', action);

    return fetch('ajax/civicadmin_verify_document_api.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            showConfirm({
                type: 'reject',
                icon: 'fa-exclamation',
                title: 'Action Failed',
                message: data.message || 'Failed to update verification status.',
                confirmText: 'OK',
                hideCancel: true
            });
            setRowProcessing(userId, false);
        }
        return data;
    })
    .catch(() => {
           showConfirm({
            type: 'reject',
            icon: 'fa-exclamation',
            title: 'Something Went Wrong',
            message: 'Please try again.',
            confirmText: 'OK',
            hideCancel: true
        });
        setRowProcessing(userId, false);
    });
}

document.querySelectorAll('.action-btn.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalName').innerText = this.dataset.name || '';
        document.getElementById('modalEmail').innerText = this.dataset.email || '';
        document.getElementById('modalDocType').innerText = this.dataset.doctype || '';
        document.getElementById('modalDate').innerText = this.dataset.date || '';
        document.getElementById('modalFileLink').innerHTML = '<i class="fas fa-download"></i> ' + (this.dataset.filename || '');
        document.getElementById('modalFileLink').href = this.dataset.file || '#';
        document.getElementById('modalCampus').innerText = this.dataset.campus || '';
        document.getElementById('modalMobile').innerText = this.dataset.mobile || '';

        const modalPhoto = document.getElementById('modalPhoto');
        const modalPhotoInitials = document.getElementById('modalPhotoInitials');
        modalPhotoInitials.textContent = this.dataset.initials || '';
        if (this.dataset.photo) {
            modalPhoto.onerror = function() {
                modalPhoto.style.display = 'none';
                modalPhotoInitials.style.display = 'flex';
            };
            modalPhoto.src = this.dataset.photo;
            modalPhoto.style.display = 'block';
            modalPhotoInitials.style.display = 'none';
        } else {
            modalPhoto.style.display = 'none';
            modalPhotoInitials.style.display = 'flex';
        }

        modal.dataset.userId = this.dataset.userId;
        const approveBtn = document.getElementById('modalApproveBtn');
        const rejectBtn = document.getElementById('modalRejectBtn');
        approveBtn.disabled = this.dataset.status === '1';
        approveBtn.style.opacity = approveBtn.disabled ? '0.5' : '1';
        rejectBtn.disabled = this.dataset.status === '2';
        rejectBtn.style.opacity = rejectBtn.disabled ? '0.5' : '1';

        modal.style.display = 'flex';
    });
});

document.querySelectorAll('.action-btn.approve-btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
        confirmReview(this.dataset.userId, 'approve');
    });
});

document.querySelectorAll('.action-btn.reject-btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
        confirmReview(this.dataset.userId, 'reject');
    });
});

document.getElementById('modalApproveBtn').addEventListener('click', function() {
    if (this.disabled) return;
    confirmReview(modal.dataset.userId, 'approve').then(function(data) {
        if (data && data.success) closeModal();
    });
});

document.getElementById('modalRejectBtn').addEventListener('click', function() {
    if (this.disabled) return;
    confirmReview(modal.dataset.userId, 'reject').then(function(data) {
        if (data && data.success) closeModal();
    });
});

const searchInput = document.getElementById('verificationSearchInput');
const statusFilter = document.getElementById('statusFilter');
const docTypeFilter = document.getElementById('docTypeFilter');
const campusFilter = document.getElementById('campusFilter');
const verificationRows = document.querySelectorAll('#verificationTableBody tr[data-search]');

function applyVerificationFilters() {
    const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const statusVal = statusFilter ? statusFilter.value : '';
    const docTypeVal = docTypeFilter ? docTypeFilter.value : '';
    const campusVal = campusFilter ? campusFilter.value : '';

    verificationRows.forEach(row => {
        const matchesSearch = !searchTerm || row.dataset.search.includes(searchTerm);
        const matchesStatus = statusVal === '' || row.dataset.status === statusVal;
        const matchesDocType = !docTypeVal || row.dataset.doctype === docTypeVal;
        const matchesCampus = !campusVal || row.dataset.campus === campusVal;
        row.style.display = (matchesSearch && matchesStatus && matchesDocType && matchesCampus) ? '' : 'none';
    });
}

if (searchInput) searchInput.addEventListener('input', applyVerificationFilters);
if (statusFilter) statusFilter.addEventListener('change', applyVerificationFilters);
if (docTypeFilter) docTypeFilter.addEventListener('change', applyVerificationFilters);
if (campusFilter) campusFilter.addEventListener('change', applyVerificationFilters);

const photoLightbox = document.getElementById('photoLightbox');
const photoLightboxImg = document.getElementById('photoLightboxImg');
const photoLightboxClose = document.getElementById('photoLightboxClose');

function openPhotoLightbox(src) {
    if (!src) return;
    photoLightboxImg.src = src;
    photoLightbox.style.display = 'flex';
}

function closePhotoLightbox() {
    photoLightbox.style.display = 'none';
    photoLightboxImg.src = '';
}

document.addEventListener('click', function(e) {
    const img = e.target.closest('.photo-zoomable');
    if (img && img.src && img.style.display !== 'none') {
        openPhotoLightbox(img.src);
    }
});

photoLightboxClose.addEventListener('click', closePhotoLightbox);
photoLightbox.addEventListener('click', function(e) {
    if (e.target === photoLightbox) closePhotoLightbox();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && photoLightbox.style.display === 'flex') closePhotoLightbox();
});

function replaceBrokenPhoto(img) {
    const fallback = document.createElement('div');
    fallback.className = 'user-avatar-small';
    fallback.textContent = img.dataset.initials || '';
    img.replaceWith(fallback);
}

document.querySelectorAll('img.user-photo').forEach(function(img) {
    if (img.complete && img.naturalWidth === 0) {
        replaceBrokenPhoto(img);
    } else {
        img.addEventListener('error', function() { replaceBrokenPhoto(img); });
    }
});

const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to logout?')) return;
        fetch('ajax/adminLogout.php', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                window.location.href = data.redirect ? data.redirect : 'index.php?url=login';
            })
            .catch(() => {
                window.location.href = 'index.php?url=login';
            });
    });
}