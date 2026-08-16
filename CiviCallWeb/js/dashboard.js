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

var logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to logout?')) return;

        fetch('ajax/adminLogout.php', {
            method: 'POST'
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            window.location.href = data.redirect ? data.redirect : 'index.php?url=login';
        })
        .catch(function() {
            window.location.href = 'index.php?url=login';
        });
    });
}