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

var modal = document.getElementById('feedbackModal');
var closeModalBtn = document.getElementById('closeModalBtn');
var viewButtons = document.querySelectorAll('.action-icon.view');

function openModal() { modal.style.display = 'flex'; }
function closeModal() { modal.style.display = 'none'; }

viewButtons.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        var row = btn.closest('tr');
        var nameCell = row.querySelector('.user-info-text strong').innerText;
        var emailCell = row.querySelector('.user-info-text span').innerText;
        var ratingStars = row.cells[1].innerHTML;
        var feedbackText = row.cells[2].innerText;
        var date = row.cells[3].innerText;

        document.getElementById('modalUser').innerText = nameCell;
        document.getElementById('modalEmail').innerText = emailCell;
        document.getElementById('modalRating').innerHTML = ratingStars;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalText').innerText = feedbackText;
        openModal();
    });
});

closeModalBtn.addEventListener('click', closeModal);
modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });