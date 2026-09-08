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
        document.getElementById('detailMobile').textContent = this.dataset.mobile || '—';
        document.getElementById('detailEmergency').textContent = this.dataset.emergency || '—';
        document.getElementById('detailAddress').textContent = this.dataset.address || '—';
        document.getElementById('detailBirthday').textContent = this.dataset.birthday || '—';
        document.getElementById('detailGender').textContent = this.dataset.gender || '—';
        document.getElementById('detailCampus').textContent = this.dataset.campus || '—';
        document.getElementById('detailDepartment').textContent = this.dataset.department || '—';
        document.getElementById('detailCourse').textContent = this.dataset.course || '—';
        document.getElementById('detailUserType').textContent = this.dataset.usertype || '—';
        document.getElementById('detailNstp').textContent = this.dataset.nstp || '—';
        document.getElementById('detailSrCode').textContent = this.dataset.srcode || '—';
        document.getElementById('detailYrSection').textContent = this.dataset.yrsection || '—';
        document.getElementById('detailJoined').textContent = this.dataset.joined || '';

        const badge = document.getElementById('detailVerificationBadge');
        badge.textContent = this.dataset.verification || '';
        badge.className = this.dataset.verificationLevel === '1' ? 'badge-verified'
            : this.dataset.verificationLevel === '2' ? 'badge-verified badge-rejected'
            : 'badge-verified badge-pending';

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

const editModal = document.getElementById('userEditModal');
const editForm = document.getElementById('editUserForm');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const editFormMessage = document.getElementById('editFormMessage');
const editPhotoPreview = document.getElementById('editPhotoPreview');
const editPhotoInitials = document.getElementById('editPhotoInitials');
const editPhotoInput = document.getElementById('editPhotoInput');
const editCampusSelect = document.getElementById('editCampus');

function closeEditModal() {
    editModal.style.display = 'none';
    editForm.reset();
    editFormMessage.textContent = '';
}

document.querySelectorAll('.action-btn.edit').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('editUserId').value = this.dataset.userId || '';
        document.getElementById('editEmail').textContent = this.dataset.email || '';
        document.getElementById('editFirstName').value = this.dataset.firstName || '';
        document.getElementById('editMiddleName').value = this.dataset.middleName || '';
        document.getElementById('editLastName').value = this.dataset.lastName || '';
        document.getElementById('editMobile').value = this.dataset.mobile || '';
        document.getElementById('editEmergency').value = this.dataset.emergency || '';
        document.getElementById('editAddress').value = this.dataset.address || '';
        document.getElementById('editBirthday').value = this.dataset.birthday || '';
        document.getElementById('editGender').value = this.dataset.gender || '0';
        document.getElementById('editDepartment').value = this.dataset.departmentId || '';
        document.getElementById('editCourse').value = this.dataset.courseId || '';
        document.getElementById('editUserType').value = this.dataset.usertypeId || '';
        document.getElementById('editNstp').value = this.dataset.nstpId || '';
        document.getElementById('editSrCode').value = this.dataset.srcode || '';
        document.getElementById('editYrSection').value = this.dataset.yrsection || '';

        // Campus is editable for super admin only
        editCampusSelect.value = this.dataset.campusId || '';
        editCampusSelect.disabled = !IS_SUPER_ADMIN;

        editPhotoInput.value = '';
        if (this.dataset.photo) {
            editPhotoPreview.src = this.dataset.photo;
            editPhotoPreview.style.display = 'inline-block';
            editPhotoInitials.style.display = 'none';
        } else {
            editPhotoPreview.style.display = 'none';
            editPhotoInitials.textContent = this.dataset.initials || '';
            editPhotoInitials.style.display = 'flex';
        }

        editFormMessage.textContent = '';
        editModal.style.display = 'flex';
    });
});

if (editPhotoInput) {
    editPhotoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                editPhotoPreview.src = e.target.result;
                editPhotoPreview.style.display = 'inline-block';
                editPhotoInitials.style.display = 'none';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

const closeEditModalBtn = document.getElementById('closeEditModalBtn');
if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditModal);
if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
if (editModal) {
    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) closeEditModal();
    });
}

if (editForm) {
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(editForm);
        if (!IS_SUPER_ADMIN) {
            formData.delete('campusId'); // extra safety; server ignores it anyway for sub admins
        }

        editFormMessage.style.color = 'var(--gray-600)';
        editFormMessage.textContent = 'Saving changes...';

        fetch('ajax/civicadmin_edit_user_api.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                editFormMessage.style.color = '#2e7d32';
                editFormMessage.textContent = data.message || 'User updated successfully.';
                setTimeout(() => window.location.reload(), 800);
            } else {
                editFormMessage.style.color = '#d32f2f';
                editFormMessage.textContent = data.message || 'Failed to update user.';
            }
        })
        .catch(() => {
            editFormMessage.style.color = '#d32f2f';
            editFormMessage.textContent = 'Something went wrong. Please try again.';
        });
    });
}

document.querySelectorAll('.action-btn.block').forEach(btn => {
    btn.addEventListener('click', () => alert('Block functionality would be implemented here.'));
});

// Logout button (if present)
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

const registerModal = document.getElementById('userRegisterModal');
const openRegisterModalBtn = document.getElementById('openRegisterModalBtn');
const closeRegisterModalBtn = document.getElementById('closeRegisterModalBtn');
const cancelRegisterBtn = document.getElementById('cancelRegisterBtn');
const registerForm = document.getElementById('registerUserForm');
const registerEmailList = document.getElementById('registerEmailList');
const addRegisterRowBtn = document.getElementById('addRegisterRowBtn');
const registerFormMessage = document.getElementById('registerFormMessage');
const registerResults = document.getElementById('registerResults');

function openRegisterModal() {
    registerResults.innerHTML = '';
    registerFormMessage.textContent = '';
    registerModal.style.display = 'flex';
}

function closeRegisterModal() {
    registerModal.style.display = 'none';
    registerForm.reset();
    registerResults.innerHTML = '';
    registerFormMessage.textContent = '';
    while (registerEmailList.children.length > 1) {
        registerEmailList.removeChild(registerEmailList.lastElementChild);
    }
    updateRegisterRemoveButtons();
}

function updateRegisterRemoveButtons() {
    const rows = registerEmailList.querySelectorAll('.register-email-row');
    rows.forEach(row => {
        row.querySelector('.btn-remove-row').disabled = rows.length <= 1;
    });
}

function addRegisterRow() {
    const row = document.createElement('div');
    row.className = 'register-email-row';
    row.innerHTML = '<input type="email" name="emails[]" placeholder="user@example.com" required><button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>';
    registerEmailList.appendChild(row);
    row.querySelector('.btn-remove-row').addEventListener('click', function() {
        row.remove();
        updateRegisterRemoveButtons();
    });
    updateRegisterRemoveButtons();
}

if (openRegisterModalBtn) openRegisterModalBtn.addEventListener('click', openRegisterModal);
if (closeRegisterModalBtn) closeRegisterModalBtn.addEventListener('click', closeRegisterModal);
if (cancelRegisterBtn) cancelRegisterBtn.addEventListener('click', closeRegisterModal);
if (registerModal) {
    registerModal.addEventListener('click', (e) => {
        if (e.target === registerModal) closeRegisterModal();
    });
    registerModal.querySelectorAll('.btn-remove-row').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.closest('.register-email-row').remove();
            updateRegisterRemoveButtons();
        });
    });
}
if (addRegisterRowBtn) addRegisterRowBtn.addEventListener('click', addRegisterRow);

if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(registerForm);
        registerResults.innerHTML = '';
        registerFormMessage.style.color = 'var(--gray-600)';
        registerFormMessage.textContent = 'Registering users...';

        fetch('ajax/civicadmin_register_user_api.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.results && Array.isArray(data.results)) {
                data.results.forEach(r => {
                    const item = document.createElement('div');
                    item.className = 'register-result-item ' + (r.success ? 'ok' : 'fail');
                    item.textContent = r.email + ' — ' + r.message;
                    registerResults.appendChild(item);
                });
            }
            if (data.success) {
                registerFormMessage.style.color = '#2e7d32';
                registerFormMessage.textContent = data.message || 'Users registered successfully.';
                setTimeout(() => window.location.reload(), 1200);
            } else {
                registerFormMessage.style.color = '#d32f2f';
                registerFormMessage.textContent = data.message || 'Failed to register users.';
            }
        })
        .catch(() => {
            registerFormMessage.style.color = '#d32f2f';
            registerFormMessage.textContent = 'Something went wrong. Please try again.';
        });
    });
}

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