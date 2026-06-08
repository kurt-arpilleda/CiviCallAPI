<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — User Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/usermanagement.css">
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
                <div class="skel-block" style="width:180px;height:28px;margin-bottom:8px;"></div>
                <div class="skel-block" style="width:300px;height:13px;border-radius:4px;"></div>
            </div>
            <div class="skel-block" style="width:220px;height:52px;border-radius:50px;"></div>
        </div>
        <div class="skel-filter-bar">
            <div class="skel-block" style="width:300px;height:44px;border-radius:50px;"></div>
            <div style="display:flex;gap:12px;">
                <div class="skel-block" style="width:130px;height:44px;border-radius:30px;"></div>
                <div class="skel-block" style="width:160px;height:44px;border-radius:30px;"></div>
                <div class="skel-block" style="width:110px;height:44px;border-radius:30px;"></div>
            </div>
        </div>
        <div class="skel-table-card">
            <div class="skel-table-header">
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:14%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:12%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:100px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:130px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:80px;height:10px;border-radius:3px;"></div></div>
                <div class="skel-block" style="width:80px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:70px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:140px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:90px;height:10px;border-radius:3px;"></div></div>
                <div class="skel-block" style="width:70px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:65px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:95px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:120px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:85px;height:10px;border-radius:3px;"></div></div>
                <div class="skel-block" style="width:75px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:70px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:88px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:135px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div><div class="skel-block" style="width:120px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:75px;height:10px;border-radius:3px;"></div></div>
                <div class="skel-block" style="width:65px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:72px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:82px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:145px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div><div class="skel-block" style="width:108px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:88px;height:10px;border-radius:3px;"></div></div>
                <div class="skel-block" style="width:78px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:68px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:84px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
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
            <li class="nav-item"><a href="civicadmin_usermanagement.php" class="nav-link active"><i class="fas fa-users"></i> Users</a></li>
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
                <h1>User Management</h1>
                <p>Manage all registered users, verification status, and activity</p>
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

        <div class="search-filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search by name, email, or ID...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option>All Campuses</option>
                    <option>Manila Campus</option>
                    <option>Quezon City</option>
                    <option>Makati</option>
                </select>
                <select class="filter-select">
                    <option>Verification Status</option>
                    <option>Verified</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
                <select class="filter-select">
                    <option>User Type</option>
                    <option>Student</option>
                    <option>Faculty</option>
                    <option>Staff</option>
                </select>
            </div>
        </div>

        <div class="user-table-container">
            <table class="user-table">
                <thead>
                    <tr><th>User</th><th>Contact / Campus</th><th>Verification</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
                </thead>
                <tbody id="userTableBody"></tbody>
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

<template id="userRowTemplate">
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">JD</div><div class="user-info-text"><strong>Juan Dela Cruz</strong><span>juan.cruz@civicall.com</span></div></div></td>
        <td>+639123456789<br><span style="font-size:0.7rem;">Manila Campus</span></td>
        <td><span class="badge-verified"><i class="fas fa-check-circle"></i> Verified</span></td>
        <td><span class="status-dot dot-active"></span> Active</td>
        <td>Jan 15, 2025</td>
        <td class="action-buttons"><button class="action-btn view" data-user-id="1"><i class="fas fa-eye"></i></button><button class="action-btn edit"><i class="fas fa-edit"></i></button><button class="action-btn block"><i class="fas fa-ban"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">MS</div><div class="user-info-text"><strong>Maria Santos</strong><span>maria.santos@example.com</span></div></div></td>
        <td>+639987654321<br><span style="font-size:0.7rem;">Quezon City</span></td>
        <td><span class="badge-pending"><i class="fas fa-clock"></i> Pending</span></td>
        <td><span class="status-dot dot-active"></span> Active</td>
        <td>Feb 02, 2025</td>
        <td class="action-buttons"><button class="action-btn view"><i class="fas fa-eye"></i></button><button class="action-btn edit"><i class="fas fa-edit"></i></button><button class="action-btn block"><i class="fas fa-ban"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">CR</div><div class="user-info-text"><strong>Carlos Reyes</strong><span>carlos.reyes@gmail.com</span></div></div></td>
        <td>+639451237890<br><span style="font-size:0.7rem;">Makati Campus</span></td>
        <td><span class="badge-verified"><i class="fas fa-check-circle"></i> Verified</span></td>
        <td><span class="status-dot dot-active"></span> Active</td>
        <td>Jan 28, 2025</td>
        <td class="action-buttons"><button class="action-btn view"><i class="fas fa-eye"></i></button><button class="action-btn edit"><i class="fas fa-edit"></i></button><button class="action-btn block"><i class="fas fa-ban"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">AR</div><div class="user-info-text"><strong>Anna Rivera</strong><span>anna.rivera@student.edu</span></div></div></td>
        <td>+639112233445<br><span style="font-size:0.7rem;">Manila Campus</span></td>
        <td><span class="badge-rejected"><i class="fas fa-times-circle"></i> Rejected</span></td>
        <td><span class="status-dot dot-inactive"></span> Inactive</td>
        <td>Mar 01, 2025</td>
        <td class="action-buttons"><button class="action-btn view"><i class="fas fa-eye"></i></button><button class="action-btn edit"><i class="fas fa-edit"></i></button><button class="action-btn block"><i class="fas fa-ban"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">ML</div><div class="user-info-text"><strong>Michael Lee</strong><span>michael.lee@civicall.app</span></div></div></td>
        <td>+639998887777<br><span style="font-size:0.7rem;">Quezon City</span></td>
        <td><span class="badge-verified"><i class="fas fa-check-circle"></i> Verified</span></td>
        <td><span class="status-dot dot-active"></span> Active</td>
        <td>Feb 18, 2025</td>
        <td class="action-buttons"><button class="action-btn view"><i class="fas fa-eye"></i></button><button class="action-btn edit"><i class="fas fa-edit"></i></button><button class="action-btn block"><i class="fas fa-ban"></i></button></td>
    </tr>
</template>

<div class="modal-overlay" id="userDetailModal">
    <div class="modal-container">
        <div class="modal-header"><h3>User Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="user-detail-row"><div class="user-detail-label">Full Name</div><div class="user-detail-value" id="detailName"></div></div>
            <div class="user-detail-row"><div class="user-detail-label">Email</div><div class="user-detail-value" id="detailEmail"></div></div>
            <div class="user-detail-row"><div class="user-detail-label">Mobile</div><div class="user-detail-value" id="detailMobile"></div></div>
            <div class="user-detail-row"><div class="user-detail-label">Campus</div><div class="user-detail-value" id="detailCampus"></div></div>
            <div class="user-detail-row"><div class="user-detail-label">User Type</div><div class="user-detail-value" id="detailUserType"></div></div>
            <div class="divider"></div>
            <div class="user-detail-row"><div class="user-detail-label">Verification</div><div class="user-detail-value" id="detailVerification"></div></div>
            <div class="user-detail-row"><div class="user-detail-label">Joined Date</div><div class="user-detail-value" id="detailJoined"></div></div>
        </div>
    </div>
</div>

<script src="../js/usermanagement.js"></script>
</body>
</html>