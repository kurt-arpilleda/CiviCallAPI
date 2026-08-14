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

const modal = document.getElementById('userDetailModal');
const closeModalBtn = document.getElementById('closeModalBtn');
const tbody = document.getElementById('userTableBody');
const rowTemplate = document.getElementById('userRowTemplate');
const actualRows = Array.from(rowTemplate.content.children);
const skeletonRowsCount = actualRows.length;

function generateSkeletonRows() {
    let html = '';
    for (let i = 0; i < skeletonRowsCount; i++) {
        html += `<tr class="skeleton-row">
            <td><div class="skeleton-avatar"></div><div class="skeleton-text" style="width:120px"></div></td>
            <td><div class="skeleton-text" style="width:150px"></div><div class="skeleton-text" style="width:80px; margin-top:4px"></div></td>
            <td><div class="skeleton-text" style="width:100px"></div></td>
            <td><div class="skeleton-text" style="width:80px"></div></td>
            <td><div class="skeleton-text" style="width:90px"></div></td>
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
    document.querySelectorAll('.action-btn.view').forEach(btn => {
        btn.removeEventListener('click', handleViewClick);
        btn.addEventListener('click', handleViewClick);
    });
    document.querySelectorAll('.action-btn.edit').forEach(btn => {
        btn.removeEventListener('click', () => alert('Edit functionality would be implemented here.'));
        btn.addEventListener('click', () => alert('Edit functionality would be implemented here.'));
    });
    document.querySelectorAll('.action-btn.block').forEach(btn => {
        btn.removeEventListener('click', () => alert('Block functionality would be implemented here.'));
        btn.addEventListener('click', () => alert('Block functionality would be implemented here.'));
    });
}

function handleViewClick(e) {
    const btn = e.currentTarget;
    const row = btn.closest('tr');
    const nameCell = row.querySelector('.user-info-text strong').innerText;
    const emailCell = row.querySelector('.user-info-text span').innerText;
    const contactCell = row.cells[1].innerHTML.split('<br>')[0];
    const campusCell = row.cells[1].querySelector('span') ? row.cells[1].querySelector('span').innerText : 'N/A';
    const verificationCell = row.cells[2].innerText.trim();
    const joinedCell = row.cells[4].innerText;
    document.getElementById('detailName').innerText = nameCell;
    document.getElementById('detailEmail').innerText = emailCell;
    document.getElementById('detailMobile').innerText = contactCell;
    document.getElementById('detailCampus').innerText = campusCell;
    document.getElementById('detailUserType').innerText = 'Student';
    document.getElementById('detailVerification').innerText = verificationCell;
    document.getElementById('detailJoined').innerText = joinedCell;
    modal.style.display = 'flex';
}

function openModal() { modal.style.display = 'flex'; }
function closeModal() { modal.style.display = 'none'; }

closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

generateSkeletonRows();
setTimeout(() => {
    loadActualRows();
}, 800);