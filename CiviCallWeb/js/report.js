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

const modal = document.getElementById('reportModal');
const closeModalBtn = document.getElementById('closeModalBtn');
const tbody = document.getElementById('reportTableBody');
const rowTemplate = document.getElementById('reportRowTemplate');
const actualRows = Array.from(rowTemplate.content.children);
const skeletonRowsCount = actualRows.length;

function generateSkeletonRows() {
    let html = '';
    for (let i = 0; i < skeletonRowsCount; i++) {
        html += `<tr class="skeleton-row">
            <td><div class="skeleton-avatar"></div><div class="skeleton-text" style="width:120px"></div></td>
            <td><div class="skeleton-text" style="width:100px"></div></td>
            <td><div class="skeleton-text" style="width:200px"></div></td>
            <td><div class="skeleton-text" style="width:80px"></div></td>
            <td><div class="skeleton-text" style="width:90px"></div></td>
            <td><div class="skeleton-icon"></div><div class="skeleton-icon"></div></td>
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
    document.querySelectorAll('.action-icon.view').forEach(btn => {
        btn.removeEventListener('click', handleViewClick);
        btn.addEventListener('click', handleViewClick);
    });
    document.querySelectorAll('.action-icon.delete').forEach(btn => {
        btn.removeEventListener('click', handleDeleteClick);
        btn.addEventListener('click', handleDeleteClick);
    });
}

function handleViewClick(e) {
    const btn = e.currentTarget;
    const row = btn.closest('tr');
    const nameCell = row.querySelector('.user-info-text strong').innerText;
    const emailCell = row.querySelector('.user-info-text span').innerText;
    const typeSpan = row.querySelector('.type-badge');
    const typeText = typeSpan.innerText.trim();
    const reportText = row.cells[2].innerText;
    const date = row.cells[4].innerText;
    const attachmentLink = row.cells[3].querySelector('.attachment-link');
    const hasAttachment = attachmentLink && attachmentLink.innerText !== 'No file';
    document.getElementById('modalReporter').innerText = nameCell;
    document.getElementById('modalEmail').innerText = emailCell;
    document.getElementById('modalType').innerText = typeText;
    document.getElementById('modalDate').innerText = date;
    document.getElementById('modalText').innerText = reportText;
    if (hasAttachment) {
        document.getElementById('modalAttachment').innerHTML = '<i class="fas fa-download"></i> ' + attachmentLink.innerText;
        document.getElementById('modalAttachment').style.display = 'inline-flex';
    } else {
        document.getElementById('modalAttachment').innerHTML = 'No attachment';
        document.getElementById('modalAttachment').style.display = 'inline-flex';
        document.getElementById('modalAttachment').href = '#';
    }
    modal.style.display = 'flex';
}

function handleDeleteClick(e) {
    alert('Delete functionality would be implemented here.');
}

function openModal() { modal.style.display = 'flex'; }
function closeModal() { modal.style.display = 'none'; }

closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

generateSkeletonRows();
setTimeout(() => {
    loadActualRows();
}, 800);