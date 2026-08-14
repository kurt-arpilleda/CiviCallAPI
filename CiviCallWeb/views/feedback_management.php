<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Feedback Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/feedback.css">
</head>
<body>

<div class="skeleton-overlay" id="skeletonOverlay">
    <div class="skel-sidebar">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="skel-block-dark" style="width:44px;height:44px;border-radius:14px;flex-shrink:0;"></div>
            <div>
                <div class="skel-block-dark" style="width:90px;height:14px;margin-bottom:6px;"></div>
                <div class="skel-block-dark" style="width:60px;height:9px;border-radius:20px;"></div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px;">
            <div class="skel-block-dark skel-nav-item"></div>
            <div class="skel-block-dark skel-nav-item"></div>
            <div class="skel-block-dark skel-nav-item"></div>
            <div class="skel-block-dark skel-nav-item"></div>
            <div class="skel-block-dark skel-nav-item"></div>
            <div class="skel-block-dark skel-nav-item"></div>
        </div>
    </div>
    <div class="skel-main">
        <div class="skel-topbar">
            <div>
                <div class="skel-block" style="width:200px;height:28px;margin-bottom:8px;"></div>
                <div class="skel-block" style="width:240px;height:13px;border-radius:4px;"></div>
            </div>
            <div class="skel-block" style="width:220px;height:52px;border-radius:50px;"></div>
        </div>
        <div class="skel-filter">
            <div class="skel-block" style="width:260px;height:44px;border-radius:50px;"></div>
            <div class="skel-block" style="width:130px;height:40px;border-radius:30px;"></div>
            <div class="skel-block" style="width:160px;height:40px;border-radius:30px;"></div>
        </div>
        <div class="skel-table-card">
            <div class="skel-table-header">
                <div class="skel-block" style="width:80px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:120px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:12px;border-radius:4px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="skel-circle" style="width:36px;height:36px;"></div>
                    <div>
                        <div class="skel-block" style="width:120px;height:13px;margin-bottom:6px;border-radius:4px;"></div>
                        <div class="skel-block" style="width:160px;height:10px;border-radius:4px;"></div>
                    </div>
                </div>
                <div class="skel-block" style="width:90px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:200px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:80px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:60px;height:28px;border-radius:8px;margin-left:20px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="skel-circle" style="width:36px;height:36px;"></div>
                    <div>
                        <div class="skel-block" style="width:110px;height:13px;margin-bottom:6px;border-radius:4px;"></div>
                        <div class="skel-block" style="width:150px;height:10px;border-radius:4px;"></div>
                    </div>
                </div>
                <div class="skel-block" style="width:90px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:220px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:80px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:60px;height:28px;border-radius:8px;margin-left:20px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="skel-circle" style="width:36px;height:36px;"></div>
                    <div>
                        <div class="skel-block" style="width:130px;height:13px;margin-bottom:6px;border-radius:4px;"></div>
                        <div class="skel-block" style="width:170px;height:10px;border-radius:4px;"></div>
                    </div>
                </div>
                <div class="skel-block" style="width:90px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:180px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:80px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:60px;height:28px;border-radius:8px;margin-left:20px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="skel-circle" style="width:36px;height:36px;"></div>
                    <div>
                        <div class="skel-block" style="width:100px;height:13px;margin-bottom:6px;border-radius:4px;"></div>
                        <div class="skel-block" style="width:140px;height:10px;border-radius:4px;"></div>
                    </div>
                </div>
                <div class="skel-block" style="width:90px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:210px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:80px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:60px;height:28px;border-radius:8px;margin-left:20px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="skel-circle" style="width:36px;height:36px;"></div>
                    <div>
                        <div class="skel-block" style="width:115px;height:13px;margin-bottom:6px;border-radius:4px;"></div>
                        <div class="skel-block" style="width:155px;height:10px;border-radius:4px;"></div>
                    </div>
                </div>
                <div class="skel-block" style="width:90px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:190px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:80px;height:14px;border-radius:4px;margin-left:20px;"></div>
                <div class="skel-block" style="width:60px;height:28px;border-radius:8px;margin-left:20px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="admin-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">CC</div>
                <div class="logo-text">
                    <h2>CiviCall</h2>
                    <p>Admin Portal</p>
                </div>
            </div>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="civicalladmin_dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a href="civicadmin_engagementmanagement.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Engagements</a></li>
            <li class="nav-item"><a href="civicadmin_usermanagement.php" class="nav-link"><i class="fas fa-users"></i> Users</a></li>
            <li class="nav-item"><a href="civicadmin_verificationmanagement.php" class="nav-link"><i class="fas fa-id-card"></i> Verifications</a></li>
            <li class="nav-item"><a href="civicadmin_reportmanagement.php" class="nav-link"><i class="fas fa-flag-checkered"></i> Reports</a></li>
            <li class="nav-item"><a href="civicadmin_feedbackmanagement.php" class="nav-link active"><i class="fas fa-star"></i> Feedback</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="civicadmin_login.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i> <span>Menu</span></button>
            <div class="page-title">
                <h1>Feedback Management</h1>
                <p>Review user ratings and comments about the app</p>
            </div>
            <div class="user-profile">
                <div class="notify-icon">
                    <i class="far fa-bell"></i>
                    <span class="notify-badge">3</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar">SA</div>
                    <div class="user-name">Admin CiviCall</div>
                </div>
            </div>
        </div>

        <div class="filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search by user or feedback...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option>All Ratings</option>
                    <option>5 Stars</option>
                    <option>4 Stars</option>
                    <option>3 Stars</option>
                    <option>2 Stars</option>
                    <option>1 Star</option>
                </select>
                <select class="filter-select">
                    <option>Date: Newest First</option>
                    <option>Date: Oldest First</option>
                </select>
            </div>
        </div>

        <div class="feedback-table-container">
            <table class="feedback-table">
                <thead>
                    <tr><th>User</th><th>Rating</th><th>Feedback</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">JD</div><div class="user-info-text"><strong>Juan Dela Cruz</strong><span>juan.cruz@civicall.com</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></td><td class="feedback-text-preview">Excellent app! Very helpful for community engagement. Love the UI...</td><td>Mar 28, 2025</td><td class="action-icons"><button class="action-icon view" data-id="1"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">MS</div><div class="user-info-text"><strong>Maria Santos</strong><span>maria.santos@example.com</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div></td><td class="feedback-text-preview">Great platform but sometimes the map loading is slow. Otherwise good...</td><td>Mar 27, 2025</td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">CR</div><div class="user-info-text"><strong>Carlos Reyes</strong><span>carlos.reyes@gmail.com</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></div></td><td class="feedback-text-preview">Needs dark mode and better notification system. Overall decent...</td><td>Mar 26, 2025</td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">AR</div><div class="user-info-text"><strong>Anna Rivera</strong><span>anna.rivera@student.edu</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div></td><td class="feedback-text-preview">Very helpful for finding civic activities near me. Thank you devs!</td><td>Mar 25, 2025</td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">ML</div><div class="user-info-text"><strong>Michael Lee</strong><span>michael.lee@civicall.app</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></td><td class="feedback-text-preview">Absolutely love this initiative! Keep up the great work.</td><td>Mar 24, 2025</td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td><div class="user-cell"><div class="user-avatar-small">JT</div><div class="user-info-text"><strong>James Tan</strong><span>james.tan@yahoo.com</span></div></div></td><td><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></div></td><td class="feedback-text-preview">Had some issues with login but support helped quickly.</td><td>Mar 23, 2025</td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button><i class="fas fa-chevron-left"></i> Prev</button>
            <button class="active-page">1</button>
            <button>2</button>
            <button>3</button>
            <button>Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </main>
</div>

<div class="modal-overlay" id="feedbackModal">
    <div class="modal-container">
        <div class="modal-header"><h3>Feedback Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="detail-row"><div class="detail-label">User</div><div class="detail-value" id="modalUser"></div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value" id="modalEmail"></div></div>
            <div class="detail-row"><div class="detail-label">Rating</div><div class="detail-value" id="modalRating"></div></div>
            <div class="detail-row"><div class="detail-label">Date</div><div class="detail-value" id="modalDate"></div></div>
            <div class="divider"></div>
            <div class="detail-row"><div class="detail-label">Feedback</div><div class="detail-value"><div class="feedback-full-text" id="modalText"></div></div></div>
        </div>
    </div>
</div>

<script src="../js/feedback.js"></script>
</body>
</html>