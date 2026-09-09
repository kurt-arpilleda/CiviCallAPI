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
            alert(data.message || 'Failed to update verification status.');
            setRowProcessing(userId, false);
        }
        return data;
    })
    .catch(() => {
        alert('Something went wrong. Please try again.');
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
        if (!confirm('Approve this verification request?')) return;
        reviewVerification(this.dataset.userId, 'approve');
    });
});

document.querySelectorAll('.action-btn.reject-btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Reject this verification request?')) return;
        reviewVerification(this.dataset.userId, 'reject');
    });
});

document.getElementById('modalApproveBtn').addEventListener('click', function() {
    if (this.disabled) return;
    if (!confirm('Approve this verification request?')) return;
    reviewVerification(modal.dataset.userId, 'approve').then(() => closeModal());
});

document.getElementById('modalRejectBtn').addEventListener('click', function() {
    if (this.disabled) return;
    if (!confirm('Reject this verification request?')) return;
    reviewVerification(modal.dataset.userId, 'reject').then(() => closeModal());
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