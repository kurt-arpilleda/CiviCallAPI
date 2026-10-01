<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ?url=login');
    exit;
}
require_once __DIR__ . '/../../kurt_dbCon.php';

$role = $_SESSION['admin_role'];
$adminId = $_SESSION['admin_id'];
$campusFilter = null;

if ($role === 'sub') {
    $stmt = $db->prepare("SELECT campusId FROM tbl_subadmin WHERE subId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $campusFilter = $row['campusId'];
    }
    $stmt->close();
}

$docTypeLabels = [1 => 'Certificate of Registration', 2 => 'Certificate of Graduation', 3 => 'School ID', 4 => 'Valid ID'];

$sql = "
    SELECT
        v.userId, v.fileName, v.fileType, v.dateTime,
        u.firstName, u.middleName, u.lastName, u.email, u.mobileNum,
          u.campus AS campusId, u.isVerified, u.photo_url, c.campusName
    FROM tbl_userverification v
    INNER JOIN tbl_user u ON u.userId = v.userId
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
";

if ($campusFilter !== null) {
    $sql .= " WHERE u.campus = ?";
}
$sql .= " ORDER BY v.dateTime DESC";

$stmt = $db->prepare($sql);
if ($campusFilter !== null) {
    $stmt->bind_param("i", $campusFilter);
}
$stmt->execute();
$verifResult = $stmt->get_result();
$verifications = [];
while ($row = $verifResult->fetch_assoc()) {
    $verifications[] = $row;
}
$stmt->close();

$campusListResult = $db->query("SELECT campusId, campusName FROM tbl_campus ORDER BY campusName ASC");
$campusList = [];
while ($row = $campusListResult->fetch_assoc()) {
    $campusList[] = $row;
}

$isSuperAdmin = ($role === 'super');

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — Verification Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="styles/verification.css">
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
            <li class="nav-item"><a href="?url=dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a href="?url=engagement" class="nav-link"><i class="fas fa-calendar-alt"></i> Engagements</a></li>
            <li class="nav-item"><a href="?url=usermanagement" class="nav-link"><i class="fas fa-users"></i> Users</a></li>
            <?php if ($_SESSION['admin_role'] === 'super') { ?>
            <li class="nav-item"><a href="?url=subadmin" class="nav-link"><i class="fas fa-user-shield"></i> Sub Admin</a></li>
            <?php } ?>
            <li class="nav-item"><a href="?url=verification" class="nav-link active"><i class="fas fa-id-card"></i> Verifications</a></li>
            <li class="nav-item"><a href="?url=reports" class="nav-link"><i class="fas fa-flag-checkered"></i> Reports</a></li>
            <li class="nav-item"><a href="?url=feedback" class="nav-link"><i class="fas fa-star"></i> Feedback</a></li>
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
                <h1>Verification Management</h1>
                <p>Review and approve user identity documents</p>
            </div>
            <div class="user-profile">
                <div class="notify-icon">
                    <i class="far fa-bell"></i>
                    <span class="notify-badge">3</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><?php echo substr(htmlspecialchars($_SESSION['admin_name']), 0, 2); ?></div>
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></div>
                </div>
            </div>
        </div>

        <div class="filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="verificationSearchInput" placeholder="Search by name or email...">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="2">Rejected</option>
                </select>
                <select class="filter-select" id="docTypeFilter">
                    <option value="">All Document Types</option>
<?php foreach ($docTypeLabels as $typeId => $typeLabel): ?>
                    <option value="<?php echo $typeId; ?>"><?php echo htmlspecialchars($typeLabel); ?></option>
<?php endforeach; ?>
                </select>
<?php if ($isSuperAdmin): ?>
                <select class="filter-select" id="campusFilter">
                    <option value="">Campus</option>
<?php foreach ($campusList as $c): ?>
                    <option value="<?php echo $c['campusId']; ?>"><?php echo htmlspecialchars($c['campusName']); ?></option>
<?php endforeach; ?>
                </select>
<?php endif; ?>
            </div>
        </div>

        <div class="verification-table-container">
            <table class="verification-table">
                <thead>
                    <tr><th>User</th><th>Document Type</th><th>Submitted</th><th>Document</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody id="verificationTableBody">
<?php if (empty($verifications)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:30px;">No verification requests found.</td></tr>
<?php else: foreach ($verifications as $v):
    $fullName = trim($v['firstName'] . ' ' . ($v['middleName'] ? $v['middleName'] . ' ' : '') . $v['lastName']);
    $initials = strtoupper(substr($v['firstName'], 0, 1) . substr($v['lastName'], 0, 1));
       $docTypeLabel = $docTypeLabels[(int)$v['fileType']] ?? 'Document';
    if (!empty($v['photo_url'])) {
        $profilePic = filter_var($v['photo_url'], FILTER_VALIDATE_URL) ? $v['photo_url'] : '../CiviCallAPI/profileImage/' . $v['photo_url'];
    } else {
        $profilePic = '';
    }
    $ext = strtolower(pathinfo($v['fileName'], PATHINFO_EXTENSION));
    $fileIcon = ($ext === 'pdf') ? 'fa-file-pdf' : (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'fa-image' : 'fa-file-alt');
    $fileUrl = '../CiviCallAPI/fileVerification/' . $v['fileName'];
    $submittedDate = date('M d, Y', strtotime($v['dateTime']));
    if ((int)$v['isVerified'] === 1) {
        $statusClass = 'status-approved';
        $statusText = 'Approved';
    } elseif ((int)$v['isVerified'] === 2) {
        $statusClass = 'status-rejected';
        $statusText = 'Rejected';
    } else {
        $statusClass = 'status-pending';
        $statusText = 'Pending';
    }
?>
                    <tr data-search="<?php echo htmlspecialchars(strtolower($fullName . ' ' . $v['email'])); ?>" data-status="<?php echo (int)$v['isVerified']; ?>" data-doctype="<?php echo (int)$v['fileType']; ?>" data-campus="<?php echo (int)($v['campusId'] ?? 0); ?>">
                        <td><div class="user-cell"><?php if ($profilePic): ?><img src="<?php echo htmlspecialchars($profilePic); ?>" alt="<?php echo htmlspecialchars($fullName); ?>" class="user-photo photo-zoomable" data-initials="<?php echo $initials; ?>"><?php else: ?><div class="user-avatar-small"><?php echo $initials; ?></div><?php endif; ?><div class="user-info-text">
                            <strong><?php echo htmlspecialchars($fullName); ?></strong><span><?php echo htmlspecialchars($v['email']); ?></span></div></div></td>
                        <td><?php echo htmlspecialchars($docTypeLabel); ?></td>
                        <td><?php echo $submittedDate; ?></td>
                        <td><a href="<?php echo htmlspecialchars($fileUrl); ?>" target="_blank" class="doc-link"><i class="fas <?php echo $fileIcon; ?>"></i> <?php echo htmlspecialchars($v['fileName']); ?></a></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                        <td class="action-buttons">
                            <button class="action-btn view-btn"
                                data-user-id="<?php echo $v['userId']; ?>"
                                data-name="<?php echo htmlspecialchars($fullName); ?>"
                                data-email="<?php echo htmlspecialchars($v['email']); ?>"
                                data-doctype="<?php echo htmlspecialchars($docTypeLabel); ?>"
                                data-date="<?php echo $submittedDate; ?>"
                                data-file="<?php echo htmlspecialchars($fileUrl); ?>"
                                data-filename="<?php echo htmlspecialchars($v['fileName']); ?>"
                                data-campus="<?php echo htmlspecialchars($v['campusName'] ?? 'N/A'); ?>"
                                                               data-mobile="<?php echo htmlspecialchars($v['mobileNum'] ?? ''); ?>"
                                data-photo="<?php echo htmlspecialchars($profilePic); ?>"
                                data-initials="<?php echo $initials; ?>"
                                data-status="<?php echo (int)$v['isVerified']; ?>"
                            >View</button>
                            <button class="action-btn approve-btn" data-user-id="<?php echo $v['userId']; ?>" <?php echo ((int)$v['isVerified'] === 1) ? 'disabled style="opacity:0.5;"' : ''; ?>>Approve</button>
                            <button class="action-btn reject-btn" data-user-id="<?php echo $v['userId']; ?>" <?php echo ((int)$v['isVerified'] === 2) ? 'disabled style="opacity:0.5;"' : ''; ?>>Reject</button>
                        </td>
                    </tr>
<?php endforeach; endif; ?>
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

<div class="modal-overlay" id="verificationModal">
    <div class="modal-container">
        <div class="modal-header"><h3>Verification Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
                      <div class="modal-photo-wrap">
              <img id="modalPhoto" class="modal-photo photo-zoomable" src="" alt="" style="display:none;">
                <div id="modalPhotoInitials" class="modal-photo modal-photo-initials" style="display:none;"></div>
            </div>
            <div class="detail-row"><div class="detail-label">Full Name</div><div class="detail-value" id="modalName"></div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value" id="modalEmail"></div></div>
            <div class="detail-row"><div class="detail-label">Document Type</div><div class="detail-value" id="modalDocType"></div></div>
            <div class="detail-row"><div class="detail-label">Submitted</div><div class="detail-value" id="modalDate"></div></div>
            <div class="detail-row"><div class="detail-label">File</div><div class="detail-value"><a href="#" id="modalFileLink" class="doc-link" target="_blank"><i class="fas fa-download"></i> view_document.pdf</a></div></div>
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

<script>
    const IS_SUPER_ADMIN = <?php echo $isSuperAdmin ? 'true' : 'false'; ?>;
</script>
<div class="photo-lightbox" id="photoLightbox">
    <button type="button" class="photo-lightbox-close" id="photoLightboxClose"><i class="fas fa-times"></i></button>
    <img id="photoLightboxImg" src="" alt="">
</div>

<div class="confirm-overlay" id="confirmDialog">
    <div class="confirm-box">
        <div class="confirm-icon confirm-icon-approve" id="confirmIcon"><i class="fas fa-check"></i></div>
        <h3 id="confirmTitle"></h3>
        <p id="confirmMessage"></p>
        <div class="confirm-actions">
            <button type="button" class="confirm-btn confirm-cancel" id="confirmCancelBtn">Cancel</button>
            <button type="button" class="confirm-btn confirm-approve" id="confirmOkBtn">Confirm</button>
        </div>
    </div>
</div>

<script src="js/verification.js"></script>
</body>
</html>