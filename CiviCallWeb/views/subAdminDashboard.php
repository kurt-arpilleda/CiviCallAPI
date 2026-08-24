<?php
require_once __DIR__ . '/../../kurt_dbCon.php';

$campusList = [];
$campusResult = $db->query("SELECT campusId, campusName FROM tbl_campus ORDER BY campusName ASC");
if ($campusResult) {
    while ($row = $campusResult->fetch_assoc()) {
        $campusList[] = $row;
    }
}

$subAdmins = [];
$subAdminResult = $db->query("SELECT s.subId, s.name, s.email, s.createdAt, c.campusName FROM tbl_subadmin s LEFT JOIN tbl_campus c ON s.campusId = c.campusId ORDER BY s.createdAt DESC");
if ($subAdminResult) {
    while ($row = $subAdminResult->fetch_assoc()) {
        $subAdmins[] = $row;
    }
}

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Sub Admin Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="styles/dashboard.css">
<link rel="stylesheet" href="styles/subadmin.css">
</head>
<body>

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
            <li class="nav-item"><a href="?url=dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a href="civicadmin_engagementmanagement.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Engagements</a></li>
         <li class="nav-item"><a href="?url=usermanagement" class="nav-link"><i class="fas fa-users"></i> Users</a></li>
            <li class="nav-item"><a href="?url=subadmin" class="nav-link active"><i class="fas fa-user-shield"></i> Sub Admin</a></li>
            <li class="nav-item"><a href="civicadmin_verificationmanagement.php" class="nav-link"><i class="fas fa-id-card"></i> Verifications</a></li>
            <li class="nav-item"><a href="civicadmin_reportmanagement.php" class="nav-link"><i class="fas fa-flag-checkered"></i> Reports</a></li>
            <li class="nav-item"><a href="civicadmin_feedbackmanagement.php" class="nav-link"><i class="fas fa-star"></i> Feedback</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
<div class="sidebar-footer">
            <a href="#" class="nav-link" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i> <span>Menu</span></button>
            <div class="page-title">
                <h1>Sub Admin Management</h1>
                <p>Manage sub admin accounts</p>
            </div>
            <div class="user-profile">
                <div class="notify-icon">
                    <i class="far fa-bell"></i>
                    <span class="notify-badge">3</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar">SA</div>
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-shield" style="margin-right: 8px; color: var(--red);"></i> Sub Admins</h3>
                <button class="btn-register" id="registerTriggerBtn" type="button">Register Sub Admin</button>
            </div>
            <div class="engagement-table">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Campus</th><th>Date Registered</th></tr>
                    </thead>
                    <tbody id="subAdminTableBody">
                        <?php if (count($subAdmins) === 0) { ?>
                        <tr><td colspan="4" style="text-align:center;">No sub admins registered yet.</td></tr>
                        <?php } else { foreach ($subAdmins as $subAdmin) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subAdmin['name']); ?></td>
                            <td><?php echo htmlspecialchars($subAdmin['email']); ?></td>
                            <td><?php echo htmlspecialchars($subAdmin['campusName']); ?></td>
                            <td><?php echo htmlspecialchars($subAdmin['createdAt']); ?></td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal-backdrop" id="modalBackdrop">
    <div class="modal-box" id="modalRegister">
        <button class="modal-close-btn" id="closeRegister" type="button" aria-label="Close">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <div class="modal-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D53A47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
        <h3 class="modal-title">Register Sub Admin</h3>
        <p class="modal-sub">Fill in the details to create a new sub admin account.</p>

        <div id="registerErrorMsg" class="form-error-msg" style="display:none;"></div>
        <div id="registerSuccessMsg" class="form-success-msg" style="display:none;"></div>

        <div class="form-group">
            <label for="registerName">Full Name</label>
            <div class="input-wrap">
                <input type="text" id="registerName" placeholder="Juan dela Cruz" autocomplete="name">
            </div>
        </div>

        <div class="form-group">
            <label for="registerEmail">Email Address</label>
            <div class="input-wrap">
                <input type="email" id="registerEmail" placeholder="subadmin@civicall.app" autocomplete="email">
            </div>
        </div>

        <div class="form-group">
            <label for="registerPassword">Password</label>
            <div class="input-wrap">
                <input type="password" id="registerPassword" placeholder="••••••••" autocomplete="new-password">
            </div>
        </div>

        <div class="form-group">
            <label for="registerCampus">Campus</label>
            <div class="input-wrap">
                <select id="registerCampus">
                    <option value="">Select campus</option>
                    <?php foreach ($campusList as $campus) { ?>
                    <option value="<?php echo htmlspecialchars($campus['campusId']); ?>"><?php echo htmlspecialchars($campus['campusName']); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <button class="btn-login" id="registerSubmit" type="button" style="margin-top:18px;">
            <span class="btn-text">Register Sub Admin</span>
            <span class="btn-loader"><span class="spinner"></span></span>
        </button>
    </div>
</div>

<script src="js/dashboard.js"></script>
<script src="js/subadmin.js"></script>
</body>
</html>