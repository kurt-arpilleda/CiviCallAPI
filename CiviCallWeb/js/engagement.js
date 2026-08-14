window.addEventListener('load', function() {
    setTimeout(function() {
        var overlay = document.getElementById('skeletonOverlay');
        if (overlay) {
            overlay.style.animation = 'skeletonFadeOut 0.4s ease forwards';
            setTimeout(function() { overlay.style.display = 'none'; }, 400);
        }
    }, 1900);
});

var menuToggle = document.getElementById('menuToggle');
var sidebar = document.getElementById('sidebar');

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

var modal = document.getElementById('engagementDetailModal');
var closeModalBtn = document.getElementById('closeModalBtn');
var viewButtons = document.querySelectorAll('.action-icon.view');

function openModal() { modal.style.display = 'flex'; }
function closeModal() { modal.style.display = 'none'; }

viewButtons.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        var row = btn.closest('tr');
        var title = row.cells[0].innerText;
        var category = row.cells[1].innerText.trim();
        var schedule = row.cells[2].innerText;
        var campus = row.cells[3].innerText;
        var participants = row.cells[4].innerText;
        var statusSpan = row.cells[5].querySelector('.status-badge');
        var statusText = statusSpan.innerText.trim();

        document.getElementById('detailTitle').innerText = title;
        document.getElementById('detailCategory').innerText = category.replace(/[^\w\s]/g, '').trim();
        document.getElementById('detailDesc').innerText = 'This is a sample description for ' + title;
        document.getElementById('detailSchedule').innerText = schedule;
        document.getElementById('detailLocation').innerText = 'Sample Location Address';
        document.getElementById('detailCampus').innerText = campus;
        document.getElementById('detailFacilitator').innerText = 'Admin Facilitator · +639999888777';
        document.getElementById('detailParticipants').innerText = participants;
        document.getElementById('detailPoints').innerText = '20 pts';

        var badgeClass = 'status-active';
        if (statusText === 'Pending') badgeClass = 'status-pending';
        else if (statusText === 'Rejected') badgeClass = 'status-rejected';
        else if (statusText === 'Completed') badgeClass = 'status-completed';

        document.getElementById('detailStatus').innerHTML = '<span class="status-badge ' + badgeClass + '">' + statusText + '</span>';
        openModal();
    });
});

closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });