<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/dashboard.css">
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
                <div class="skel-block" style="width:160px;height:28px;margin-bottom:8px;"></div>
                <div class="skel-block" style="width:110px;height:13px;border-radius:4px;"></div>
            </div>
            <div class="skel-block" style="width:220px;height:52px;border-radius:50px;"></div>
        </div>
        <div class="skel-stats">
            <div class="skel-stat-card">
                <div>
                    <div class="skel-block" style="width:70px;height:32px;margin-bottom:8px;"></div>
                    <div class="skel-block" style="width:110px;height:11px;border-radius:4px;"></div>
                </div>
                <div class="skel-circle" style="width:54px;height:54px;border-radius:28px;"></div>
            </div>
            <div class="skel-stat-card">
                <div>
                    <div class="skel-block" style="width:70px;height:32px;margin-bottom:8px;"></div>
                    <div class="skel-block" style="width:110px;height:11px;border-radius:4px;"></div>
                </div>
                <div class="skel-circle" style="width:54px;height:54px;border-radius:28px;"></div>
            </div>
            <div class="skel-stat-card">
                <div>
                    <div class="skel-block" style="width:70px;height:32px;margin-bottom:8px;"></div>
                    <div class="skel-block" style="width:110px;height:11px;border-radius:4px;"></div>
                </div>
                <div class="skel-circle" style="width:54px;height:54px;border-radius:28px;"></div>
            </div>
            <div class="skel-stat-card">
                <div>
                    <div class="skel-block" style="width:70px;height:32px;margin-bottom:8px;"></div>
                    <div class="skel-block" style="width:110px;height:11px;border-radius:4px;"></div>
                </div>
                <div class="skel-circle" style="width:54px;height:54px;border-radius:28px;"></div>
            </div>
        </div>
        <div class="skel-row">
            <div class="skel-card">
                <div class="skel-block" style="width:180px;height:20px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
            </div>
            <div class="skel-card">
                <div class="skel-block" style="width:180px;height:20px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85%;height:14px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100%;height:14px;border-radius:4px;"></div>
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
            <li class="nav-item"><a href="civicalladmin_dashboard.php" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a href="civicadmin_engagementmanagement.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Engagements</a></li>
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
                <h1>Dashboard</h1>
                <p>Welcome back, Super Admin</p>
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

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-left">
                    <h3>147</h3>
                    <p>Total Engagements</p>
                </div>
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <h3>2,342</h3>
                    <p>Active Users</p>
                </div>
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <h3>28</h3>
                    <p>Pending Verifications</p>
                </div>
                <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <h3>94%</h3>
                    <p>Engagement Rate</p>
                </div>
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar-week" style="margin-right: 8px; color: var(--red);"></i> Recent Engagements</h3>
                    <a href="civicadmin_engagementmanagement.php">View all <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="engagement-table">
                    <table>
                        <thead>
                            <tr><th>Title</th><th>Category</th><th>Date</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Coastal Cleanup Drive</td><td>Environment</td><td>2025-03-15</td><td><span class="status-badge">Active</span></td></tr>
                            <tr><td>Leadership Summit</td><td>Education</td><td>2025-03-20</td><td><span class="status-badge">Active</span></td></tr>
                            <tr><td>Blood Donation</td><td>Health</td><td>2025-03-10</td><td><span class="status-badge pending">Pending</span></td></tr>
                            <tr><td>Community Tree Planting</td><td>Environment</td><td>2025-03-25</td><td><span class="status-badge">Active</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-check" style="margin-right: 8px; color: var(--red);"></i> Verification Requests</h3>
                    <a href="civicadmin_verificationmanagement.php">Review all</a>
                </div>
                <div class="verification-list">
                    <div class="verif-item"><div class="verif-info"><h4>Juan Dela Cruz</h4><p>Student ID · March 28, 2025</p></div><div class="verif-status">Pending</div></div>
                    <div class="verif-item"><div class="verif-info"><h4>Maria Santos</h4><p>Government ID · March 27, 2025</p></div><div class="verif-status">Pending</div></div>
                    <div class="verif-item"><div class="verif-info"><h4>Carlos Reyes</h4><p>School Certificate · March 26, 2025</p></div><div class="verif-status">Pending</div></div>
                    <div class="verif-item"><div class="verif-info"><h4>Anna Rivera</h4><p>Barangay Clearance · March 25, 2025</p></div><div class="verif-status">Pending</div></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-simple" style="margin-right: 8px; color: var(--red);"></i> Weekly Activity (Engagements Created)</h3>
                <a href="#">Details</a>
            </div>
            <div class="chart-placeholder">
                <div class="bar-group">
                    <div><div class="bar" style="height: 92px;"></div><div class="bar-label">Mon</div></div>
                    <div><div class="bar" style="height: 68px;"></div><div class="bar-label">Tue</div></div>
                    <div><div class="bar" style="height: 110px;"></div><div class="bar-label">Wed</div></div>
                    <div><div class="bar" style="height: 84px;"></div><div class="bar-label">Thu</div></div>
                    <div><div class="bar" style="height: 126px;"></div><div class="bar-label">Fri</div></div>
                    <div><div class="bar" style="height: 73px;"></div><div class="bar-label">Sat</div></div>
                    <div><div class="bar" style="height: 45px;"></div><div class="bar-label">Sun</div></div>
                </div>
                <p style="color: var(--gray-400); font-size: 0.75rem; margin-top: 8px;">+18% compared to last week</p>
            </div>
        </div>
    </main>
</div>
<script src="../js/dashboard.js"></script>
</body>
</html>