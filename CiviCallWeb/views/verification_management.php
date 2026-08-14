<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Verification Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../styles/verification.css">
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
                <div class="skel-block" style="width:260px;height:13px;border-radius:4px;"></div>
            </div>
            <div class="skel-block" style="width:220px;height:52px;border-radius:50px;"></div>
        </div>
        <div class="skel-filter-bar">
            <div class="skel-block" style="width:260px;height:44px;border-radius:50px;"></div>
            <div style="display:flex;gap:12px;">
                <div class="skel-block" style="width:110px;height:44px;border-radius:30px;"></div>
                <div class="skel-block" style="width:160px;height:44px;border-radius:30px;"></div>
                <div class="skel-block" style="width:100px;height:44px;border-radius:30px;"></div>
            </div>
        </div>
        <div class="skel-table-card">
            <div class="skel-table-header">
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:13%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:8%;height:11px;border-radius:4px;"></div>
                <div class="skel-block" style="width:10%;height:11px;border-radius:4px;"></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:100px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:130px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:100px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:85px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:100px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:75px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:140px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:110px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:95px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:70px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:95px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:120px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:120px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:82px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:90px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:80px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:135px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:105px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:78px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:98px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:72px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:145px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:90px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:86px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:102px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:78px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
            </div>
            <div class="skel-table-row">
                <div style="display:flex;align-items:center;gap:10px;width:20%;">
                    <div class="skel-circle" style="width:38px;height:38px;flex-shrink:0;"></div>
                    <div><div class="skel-block" style="width:88px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:118px;height:10px;border-radius:3px;"></div></div>
                </div>
                <div class="skel-block" style="width:115px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:76px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:88px;height:13px;border-radius:4px;"></div>
                <div class="skel-block" style="width:68px;height:22px;border-radius:30px;"></div>
                <div style="display:flex;gap:8px;"><div class="skel-block" style="width:50px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:65px;height:28px;border-radius:20px;"></div><div class="skel-block" style="width:55px;height:28px;border-radius:20px;"></div></div>
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
            <li class="nav-item"><a href="civicadmin_verificationmanagement.php" class="nav-link active"><i class="fas fa-id-card"></i> Verifications</a></li>
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
                <h1>Verification Management</h1>
                <p>Review and approve user identity documents</p>
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
                <input type="text" placeholder="Search by name or email...">
            </div>
            <div class="filter-group">
                <select class="filter-select">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
                <select class="filter-select">
                    <option>All Document Types</option>
                    <option>Student ID</option>
                    <option>Government ID</option>
                    <option>School Certificate</option>
                    <option>Barangay Clearance</option>
                </select>
                <select class="filter-select">
                    <option>Campus</option>
                    <option>Manila</option>
                    <option>Quezon City</option>
                    <option>Makati</option>
                </select>
            </div>
        </div>

        <div class="verification-table-container">
            <table class="verification-table">
                <thead>
                    <tr><th>User</th><th>Document Type</th><th>Submitted</th><th>Document</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody id="verificationTableBody"></tbody>
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

<template id="verificationRowTemplate">
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">JD</div><div class="user-info-text"><strong>Juan Dela Cruz</strong><span>juan.cruz@civicall.com</span></div></div></td>
        <td>Student ID</td>
        <td>Mar 28, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-file-pdf"></i> view_id.pdf</a></td>
        <td><span class="status-badge status-pending">Pending</span></td>
        <td class="action-buttons"><button class="action-btn view-btn" data-id="1">View</button><button class="action-btn approve-btn">Approve</button><button class="action-btn reject-btn">Reject</button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">MS</div><div class="user-info-text"><strong>Maria Santos</strong><span>maria.santos@example.com</span></div></div></td>
        <td>Government ID</td>
        <td>Mar 27, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-image"></i> gov_id.png</a></td>
        <td><span class="status-badge status-pending">Pending</span></td>
        <td class="action-buttons"><button class="action-btn view-btn">View</button><button class="action-btn approve-btn">Approve</button><button class="action-btn reject-btn">Reject</button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">CR</div><div class="user-info-text"><strong>Carlos Reyes</strong><span>carlos.reyes@gmail.com</span></div></div></td>
        <td>School Certificate</td>
        <td>Mar 26, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-file-alt"></i> cert.pdf</a></td>
        <td><span class="status-badge status-pending">Pending</span></td>
        <td class="action-buttons"><button class="action-btn view-btn">View</button><button class="action-btn approve-btn">Approve</button><button class="action-btn reject-btn">Reject</button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">AR</div><div class="user-info-text"><strong>Anna Rivera</strong><span>anna.rivera@student.edu</span></div></div></td>
        <td>Barangay Clearance</td>
        <td>Mar 25, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-file-pdf"></i> brgy_clear.pdf</a></td>
        <td><span class="status-badge status-pending">Pending</span></td>
        <td class="action-buttons"><button class="action-btn view-btn">View</button><button class="action-btn approve-btn">Approve</button><button class="action-btn reject-btn">Reject</button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">ML</div><div class="user-info-text"><strong>Michael Lee</strong><span>michael.lee@civicall.app</span></div></div></td>
        <td>Student ID</td>
        <td>Mar 24, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-image"></i> student_id.jpg</a></td>
        <td><span class="status-badge status-approved">Approved</span></td>
        <td class="action-buttons"><button class="action-btn view-btn">View</button><button class="action-btn approve-btn" disabled style="opacity:0.5;">Approve</button><button class="action-btn reject-btn">Reject</button></td>
    </tr>
    <tr>
        <td><div class="user-cell"><div class="user-avatar-small">JT</div><div class="user-info-text"><strong>James Tan</strong><span>james.tan@yahoo.com</span></div></div></td>
        <td>Government ID</td>
        <td>Mar 22, 2025</td>
        <td><a href="#" class="doc-link"><i class="fas fa-file-pdf"></i> national_id.pdf</a></td>
        <td><span class="status-badge status-rejected">Rejected</span></td>
        <td class="action-buttons"><button class="action-btn view-btn">View</button><button class="action-btn approve-btn">Approve</button><button class="action-btn reject-btn" disabled style="opacity:0.5;">Reject</button></td>
    </tr>
</template>

<div class="modal-overlay" id="verificationModal">
    <div class="modal-container">
        <div class="modal-header"><h3>Verification Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="detail-row"><div class="detail-label">Full Name</div><div class="detail-value" id="modalName"></div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value" id="modalEmail"></div></div>
            <div class="detail-row"><div class="detail-label">Document Type</div><div class="detail-value" id="modalDocType"></div></div>
            <div class="detail-row"><div class="detail-label">Submitted</div><div class="detail-value" id="modalDate"></div></div>
            <div class="detail-row"><div class="detail-label">File</div><div class="detail-value"><a href="#" id="modalFileLink" class="doc-link"><i class="fas fa-download"></i> view_document.pdf</a></div></div>
            <div class="divider"></div>
            <div class="detail-row"><div class="detail-label">Campus</div><div class="detail-value" id="modalCampus"></div></div>
            <div class="detail-row"><div class="detail-label">Mobile</div><div class="detail-value" id="modalMobile"></div></div>
            <div class="modal-actions">
                <button class="action-btn reject-btn" id="modalRejectBtn">Reject</button>
                <button class="action-btn approve-btn" id="modalApproveBtn">Approve</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/verification.js"></script>
</body>
</html>