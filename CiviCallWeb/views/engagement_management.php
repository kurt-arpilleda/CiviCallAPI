<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Engagement Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/engagement.css">
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
                <div class="skel-block" style="width:220px;height:28px;margin-bottom:8px;"></div>
                <div class="skel-block" style="width:160px;height:13px;border-radius:4px;"></div>
            </div>
            <div class="skel-block" style="width:220px;height:52px;border-radius:50px;"></div>
        </div>
        <div class="skel-filter">
            <div class="skel-block" style="width:260px;height:44px;border-radius:50px;"></div>
            <div class="skel-block" style="width:130px;height:40px;border-radius:30px;"></div>
            <div class="skel-block" style="width:130px;height:40px;border-radius:30px;"></div>
            <div class="skel-block" style="width:130px;height:40px;border-radius:30px;"></div>
            <div class="skel-block" style="width:140px;height:40px;border-radius:40px;margin-left:auto;"></div>
        </div>
        <div class="skel-table-card">
            <div class="skel-table-header">
                <div class="skel-block" style="width:80px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:12px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:12px;border-radius:4px;"></div>
            </div>
            <div class="skel-table-row">
                <div class="skel-block" style="width:160px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:24px;border-radius:30px;"></div>
                <div class="skel-block" style="width:130px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:24px;border-radius:30px;"></div>
            </div>
            <div class="skel-table-row">
                <div class="skel-block" style="width:180px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:24px;border-radius:30px;"></div>
                <div class="skel-block" style="width:130px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:24px;border-radius:30px;"></div>
            </div>
            <div class="skel-table-row">
                <div class="skel-block" style="width:140px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:24px;border-radius:30px;"></div>
                <div class="skel-block" style="width:130px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:24px;border-radius:30px;"></div>
            </div>
            <div class="skel-table-row">
                <div class="skel-block" style="width:170px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:24px;border-radius:30px;"></div>
                <div class="skel-block" style="width:130px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:24px;border-radius:30px;"></div>
            </div>
            <div class="skel-table-row">
                <div class="skel-block" style="width:150px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:24px;border-radius:30px;"></div>
                <div class="skel-block" style="width:130px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:60px;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:24px;border-radius:30px;"></div>
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
            <li class="nav-item"><a href="civicadmin_engagementmanagement.php" class="nav-link active"><i class="fas fa-calendar-alt"></i> Engagements</a></li>
            <li class="nav-item"><a href="civicadmin_usermanagement.php" class="nav-link"><i class="fas fa-users"></i> Users</a></li>
            <li class="nav-item"><a href="civicadmin_verificationmanagement.php" class="nav-link"><i class="fas fa-id-card"></i> Verifications</a></li>
            <li class="nav-item"><a href="civicadmin_reportmanagement.php" class="nav-link"><i class="fas fa-flag-checkered"></i> Reports</a></li>
            <li class="nav-item"><a href="civicadmin_feedbackmanagement.php" class="nav-link"><i class="fas fa-star"></i> Feedback</a></li>
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
                <h1>Engagement Management</h1>
                <p>Create, monitor, and moderate civic engagements</p>
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
                <input type="text" placeholder="Search by title, facilitator...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option>All Categories</option>
                    <option>Environment</option>
                    <option>Education</option>
                    <option>Health</option>
                    <option>Community</option>
                </select>
                <select class="filter-select">
                    <option>Verification Status</option>
                    <option>Verified</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
                <select class="filter-select">
                    <option>Campus</option>
                    <option>Manila</option>
                    <option>Quezon City</option>
                    <option>Makati</option>
                </select>
                <button class="add-btn"><i class="fas fa-plus"></i> New Engagement</button>
            </div>
        </div>

        <div class="engagement-table-container">
            <table class="engagement-table">
                <thead>
                    <tr><th>Title</th><th>Category</th><th>Date & Time</th><th>Campus</th><th>Participants</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <tr><td class="title-cell">Coastal Cleanup Drive</td><td><span class="category-badge"><i class="fas fa-tree"></i> Environment</span></td><td>Mar 15, 2025 · 8:00 AM</td><td>Manila</td><td>47 / 100</td><td><span class="status-badge status-active"><i class="fas fa-check-circle"></i> Verified</span></td><td class="action-icons"><button class="action-icon view" data-id="1"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Leadership Summit 2025</td><td><span class="category-badge"><i class="fas fa-chalkboard-user"></i> Education</span></td><td>Mar 20, 2025 · 9:30 AM</td><td>Quezon City</td><td>112 / 200</td><td><span class="status-badge status-active"><i class="fas fa-check-circle"></i> Verified</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Blood Donation Campaign</td><td><span class="category-badge"><i class="fas fa-heartbeat"></i> Health</span></td><td>Mar 10, 2025 · 7:00 AM</td><td>Makati</td><td>28 / 50</td><td><span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Community Tree Planting</td><td><span class="category-badge"><i class="fas fa-leaf"></i> Environment</span></td><td>Mar 25, 2025 · 6:30 AM</td><td>Manila</td><td>63 / 80</td><td><span class="status-badge status-active"><i class="fas fa-check-circle"></i> Verified</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Digital Literacy Workshop</td><td><span class="category-badge"><i class="fas fa-laptop-code"></i> Education</span></td><td>Mar 28, 2025 · 1:00 PM</td><td>Quezon City</td><td>34 / 60</td><td><span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Feeding Program</td><td><span class="category-badge"><i class="fas fa-utensils"></i> Community</span></td><td>Apr 02, 2025 · 10:00 AM</td><td>Makati</td><td>19 / 40</td><td><span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
                    <tr><td class="title-cell">Mental Health Awareness</td><td><span class="category-badge"><i class="fas fa-brain"></i> Health</span></td><td>Apr 05, 2025 · 2:00 PM</td><td>Manila</td><td>89 / 120</td><td><span class="status-badge status-completed"><i class="fas fa-flag-checkered"></i> Completed</span></td><td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon edit"><i class="fas fa-edit"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td></tr>
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

<div class="modal-overlay" id="engagementDetailModal">
    <div class="modal-container">
        <div class="modal-header"><h3>Engagement Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="detail-row"><div class="detail-label">Title</div><div class="detail-value" id="detailTitle"></div></div>
            <div class="detail-row"><div class="detail-label">Category</div><div class="detail-value" id="detailCategory"></div></div>
            <div class="detail-row"><div class="detail-label">Description</div><div class="detail-value" id="detailDesc"></div></div>
            <div class="detail-row"><div class="detail-label">Schedule</div><div class="detail-value" id="detailSchedule"></div></div>
            <div class="detail-row"><div class="detail-label">Location</div><div class="detail-value" id="detailLocation"></div></div>
            <div class="detail-row"><div class="detail-label">Campus</div><div class="detail-value" id="detailCampus"></div></div>
            <div class="detail-row"><div class="detail-label">Facilitator</div><div class="detail-value" id="detailFacilitator"></div></div>
            <div class="divider"></div>
            <div class="detail-row"><div class="detail-label">Participants</div><div class="detail-value" id="detailParticipants"></div></div>
            <div class="detail-row"><div class="detail-label">Points</div><div class="detail-value" id="detailPoints"></div></div>
            <div class="detail-row"><div class="detail-label">Verification</div><div class="detail-value" id="detailStatus"></div></div>
        </div>
    </div>
</div>

<script src="../js/engagement.js"></script>
</body>
</html>