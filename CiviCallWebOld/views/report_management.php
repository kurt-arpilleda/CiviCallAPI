<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Report Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/report.css">
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
        <div class="skel-filter-bar">
            <div class="skel-block" style="width:280px;height:44px;border-radius:50px;"></div>
            <div style="display:flex;gap:12px;">
                <div class="skel-block" style="width:120px;height:44px;border-radius:30px;"></div>
                <div class="skel-block" style="width:150px;height:44px;border-radius:30px;"></div>
            </div>
        </div>
        <div class="skel-table-card">
            <div class="skel-table-header">
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:22%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:12%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:18%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:100px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:130px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:80px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:200px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:18%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:140px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:90px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:230px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:18%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:95px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:120px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:70px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:210px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:95px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:88px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:18%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:135px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:85px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:190px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:75px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:18%;">
                    <div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:145px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:75px;height:22px;border-radius:30px;"></div>
                <div class="skel-block" style="width:220px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:82px;height:13px;border-radius:4px;"></div>
                <div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div>
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
            <li class="nav-item"><a href="civicadmin_reportmanagement.php" class="nav-link active"><i class="fas fa-flag-checkered"></i> Reports</a></li>
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
                <h1>Report Management</h1>
                <p>View and moderate user-submitted reports</p>
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
                <input type="text" placeholder="Search by user or report text...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option>All Types</option>
                    <option>Bug Report</option>
                    <option>Feedback</option>
                    <option>Other</option>
                </select>
                <select class="filter-select">
                    <option>Date: Newest First</option>
                    <option>Date: Oldest First</option>
                </select>
            </div>
        </div>

        <div class="report-table-container">
            <table class="report-table">
                <thead>
                    <tr><th>Reporter</th><th>Type</th><th>Report Content</th><th>Attachment</th><th>Date Submitted</th><th>Actions</th></tr>
                </thead>
                <tbody id="reportTableBody"></tbody>
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

<template id="reportRowTemplate">
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">JD</div><div class="user-info-text"><strong>Juan Dela Cruz</strong><span>juan.cruz@civicall.com</span></div></div></td>
        <td><span class="type-badge type-bug"><i class="fas fa-bug"></i> Bug Report</span></td>
        <td class="report-text-preview">App crashes when I try to upload an image for engagement...</td>
        <td><a href="#" class="attachment-link"><i class="fas fa-image"></i> screenshot.png</a></td>
        <td>Mar 28, 2025</td>
        <td class="action-icons"><button class="action-icon view" data-id="1"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">MS</div><div class="user-info-text"><strong>Maria Santos</strong><span>maria.santos@example.com</span></div></div></td>
        <td><span class="type-badge type-feedback"><i class="fas fa-comment"></i> Feedback</span></td>
        <td class="report-text-preview">The app is great but I would love to see a dark mode feature...</td>
        <td><a href="#" class="attachment-link"><i class="fas fa-file-alt"></i> feedback.txt</a></td>
        <td>Mar 27, 2025</td>
        <td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">CR</div><div class="user-info-text"><strong>Carlos Reyes</strong><span>carlos.reyes@gmail.com</span></div></div></td>
        <td><span class="type-badge type-other"><i class="fas fa-question-circle"></i> Other</span></td>
        <td class="report-text-preview">I found inappropriate content in the engagement section. Please review...</td>
        <td><a href="#" class="attachment-link"><i class="fas fa-image"></i> evidence.jpg</a></td>
        <td>Mar 26, 2025</td>
        <td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">AR</div><div class="user-info-text"><strong>Anna Rivera</strong><span>anna.rivera@student.edu</span></div></div></td>
        <td><span class="type-badge type-bug"><i class="fas fa-bug"></i> Bug Report</span></td>
        <td class="report-text-preview">Location services not working properly on Android device...</td>
        <td><span class="attachment-link">No file</span></td>
        <td>Mar 25, 2025</td>
        <td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">ML</div><div class="user-info-text"><strong>Michael Lee</strong><span>michael.lee@civicall.app</span></div></div></td>
        <td><span class="type-badge type-feedback"><i class="fas fa-comment"></i> Feedback</span></td>
        <td class="report-text-preview">Excellent platform! Very helpful for community engagement. Keep it up...</td>
        <td><span class="attachment-link">No file</span></td>
        <td>Mar 24, 2025</td>
        <td class="action-icons"><button class="action-icon view"><i class="fas fa-eye"></i></button><button class="action-icon delete"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
</template>

<div class="modal-overlay" id="reportModal">
    <div class="modal-container">
        <div class="modal-header"><h3>Report Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="detail-row"><div class="detail-label">Reported By</div><div class="detail-value" id="modalReporter"></div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value" id="modalEmail"></div></div>
            <div class="detail-row"><div class="detail-label">Report Type</div><div class="detail-value" id="modalType"></div></div>
            <div class="detail-row"><div class="detail-label">Date</div><div class="detail-value" id="modalDate"></div></div>
            <div class="divider"></div>
            <div class="detail-row"><div class="detail-label">Report Text</div><div class="detail-value"><div class="report-full-text" id="modalText"></div></div></div>
            <div class="detail-row"><div class="detail-label">Attachment</div><div class="detail-value"><a href="#" id="modalAttachment" class="attachment-link"><i class="fas fa-download"></i> screenshot.png</a></div></div>
        </div>
    </div>
</div>

<script src="../js/report.js"></script>
</body>
</html>