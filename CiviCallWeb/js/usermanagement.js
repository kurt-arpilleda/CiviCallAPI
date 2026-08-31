window.addEventListener('load', function() {
    var overlay = document.getElementById('skeletonOverlay');
    if (overlay) overlay.style.display = 'none';
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

const modal = document.getElementById('userDetailModal');
const closeModalBtn = document.getElementById('closeModalBtn');

function closeModal() {
    modal.style.display = 'none';
}
closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
});

document.querySelectorAll('.action-btn.view').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('detailName').textContent = this.dataset.name || '';
        document.getElementById('detailEmail').textContent = this.dataset.email || '';
        document.getElementById('detailMobile').textContent = this.dataset.mobile || '';
        document.getElementById('detailCampus').textContent = this.dataset.campus || '';
        document.getElementById('detailUserType').textContent = this.dataset.usertype || '';
        document.getElementById('detailVerification').textContent = this.dataset.verification || '';
        document.getElementById('detailJoined').textContent = this.dataset.joined || '';

        const photoImg = document.getElementById('detailPhoto');
        const photoInitials = document.getElementById('detailPhotoInitials');
        if (this.dataset.photo) {
            photoImg.src = this.dataset.photo;
            photoImg.style.display = 'inline-block';
            photoInitials.style.display = 'none';
        } else {
            photoImg.style.display = 'none';
            photoInitials.textContent = this.dataset.initials || '';
            photoInitials.style.display = 'flex';
        }

        modal.style.display = 'flex';
    });
});

const userSearchInput = document.getElementById('userSearchInput');
const campusFilter = document.getElementById('campusFilter');
const verificationFilter = document.getElementById('verificationFilter');
const userTypeFilter = document.getElementById('userTypeFilter');
const userRows = document.querySelectorAll('#userTableBody tr[data-search]');

function applyUserFilters() {
    const searchTerm = userSearchInput ? userSearchInput.value.trim().toLowerCase() : '';
    const campusVal = campusFilter ? campusFilter.value : '';
    const verificationVal = verificationFilter ? verificationFilter.value : '';
    const userTypeVal = userTypeFilter ? userTypeFilter.value : '';

    userRows.forEach(row => {
        const matchesSearch = !searchTerm || row.dataset.search.includes(searchTerm);
        const matchesCampus = !campusVal || row.dataset.campus === campusVal;
        const matchesVerification = verificationVal === '' || row.dataset.verification === verificationVal;
        const matchesUserType = !userTypeVal || row.dataset.usertype === userTypeVal;
        row.style.display = (matchesSearch && matchesCampus && matchesVerification && matchesUserType) ? '' : 'none';
    });
}

if (userSearchInput) userSearchInput.addEventListener('input', applyUserFilters);
if (campusFilter) campusFilter.addEventListener('change', applyUserFilters);
if (verificationFilter) verificationFilter.addEventListener('change', applyUserFilters);
if (userTypeFilter) userTypeFilter.addEventListener('change', applyUserFilters);

document.querySelectorAll('.action-btn.edit').forEach(btn => {
    btn.addEventListener('click', () => alert('Edit functionality would be implemented here.'));
});
document.querySelectorAll('.action-btn.block').forEach(btn => {
    btn.addEventListener('click', () => alert('Block functionality would be implemented here.'));
});

five// Logout button (if present)
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