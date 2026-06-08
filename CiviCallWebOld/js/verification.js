window.addEventListener('load', function() {
    setTimeout(function() {
        var overlay = document.getElementById('skeletonOverlay');
        if (overlay) {
            overlay.style.animation = 'skeletonFadeOut 0.4s ease forwards';
            setTimeout(function() { overlay.style.display = 'none'; }, 400);
        }
    }, 1900);
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
const tbody = document.getElementById('verificationTableBody');
const rowTemplate = document.getElementById('verificationRowTemplate');
const actualRows = Array.from(rowTemplate.content.children);
const skeletonRowsCount = actualRows.length;

function generateSkeletonRows() {
    let html = '';
    for (let i = 0; i < skeletonRowsCount; i++) {
        html += `<tr class="skeleton-row">
            <td><div class="skeleton-avatar"></div><div class="skeleton-text" style="width:120px"></div></td>
            <td><div class="skeleton-text" style="width:100px"></div></td>
            <td><div class="skeleton-text" style="width:90px"></div></td>
            <td><div class="skeleton-text" style="width:80px"></div></td>
            <td><div class="skeleton-text" style="width:80px"></div></td>
            <td><div class="skeleton-icon"></div><div class="skeleton-icon"></div><div class="skeleton-icon"></div></td>
        </tr>`;
    }
    tbody.innerHTML = html;
}

function loadActualRows() {
    tbody.innerHTML = '';
    actualRows.forEach(row => {
        tbody.appendChild(row.cloneNode(true));
    });
    attachEventDelegation();
}

function attachEventDelegation() {
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.removeEventListener('click', handleViewClick);
        btn.addEventListener('click', handleViewClick);
    });
    document.querySelectorAll('.approve-btn:not([disabled])').forEach(btn => {
        btn.removeEventListener('click', () => alert('Approved!'));
        btn.addEventListener('click', () => alert('Approved!'));
    });
    document.querySelectorAll('.reject-btn:not([disabled])').forEach(btn => {
        btn.removeEventListener('click', () => alert('Rejected!'));
        btn.addEventListener('click', () => alert('Rejected!'));
    });
}

function handleViewClick(e) {
    const btn = e.currentTarget;
    const row = btn.closest('tr');
    const nameCell = row.querySelector('.user-info-text strong').innerText;
    const emailCell = row.querySelector('.user-info-text span').innerText;
    const docType = row.cells[1].innerText;
    const date = row.cells[2].innerText;
    const fileName = row.cells[3].querySelector('.doc-link')?.innerText || 'document.pdf';
    document.getElementById('modalName').innerText = nameCell;
    document.getElementById('modalEmail').innerText = emailCell;
    document.getElementById('modalDocType').innerText = docType;
    document.getElementById('modalDate').innerText = date;
    document.getElementById('modalFileLink').innerHTML = '<i class="fas fa-download"></i> ' + fileName;
    document.getElementById('modalCampus').innerText = 'Manila Campus';
    document.getElementById('modalMobile').innerText = '+639123456789';
    modal.style.display = 'flex';
}

function openModal() { modal.style.display = 'flex'; }
function closeModal() { modal.style.display = 'none'; }

closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
document.getElementById('modalApproveBtn')?.addEventListener('click', () => { alert('This would approve the verification request.'); closeModal(); });
document.getElementById('modalRejectBtn')?.addEventListener('click', () => { alert('This would reject the verification request.'); closeModal(); });

generateSkeletonRows();
setTimeout(() => {
    loadActualRows();
}, 800);