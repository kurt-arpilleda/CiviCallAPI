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

$sql = "
    SELECT 
        u.userId, u.firstName, u.middleName, u.lastName, u.email,
        u.mobileNum, u.emergencyNum, u.address,
        u.campus AS campusId, c.campusName,
        u.department AS departmentId, d.departmentName,
        u.course AS courseId, co.courseName,
        u.userType, ut.userTypeName,
        u.birthDay, u.gender,
        u.nstp AS nstpId, n.nstpType,
        u.srCode, u.yrSection,
        u.photo_url, u.isVerified,
        u.signup_type, u.created_at
    FROM tbl_user u
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    LEFT JOIN tbl_department d ON d.departmentId = u.department
    LEFT JOIN tbl_course co ON co.courseId = u.course
    LEFT JOIN tbl_nstp n ON n.nstpId = u.nstp
    LEFT JOIN tbl_usertype ut ON ut.userTypeId = u.userType
";

if ($campusFilter !== null) {
    $sql .= " WHERE u.campus = ?";
}
$sql .= " ORDER BY u.created_at DESC";

$stmt = $db->prepare($sql);
if ($campusFilter !== null) {
    $stmt->bind_param("i", $campusFilter);
}
$stmt->execute();
$usersResult = $stmt->get_result();
$users = [];
while ($row = $usersResult->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();

$campusListResult = $db->query("SELECT campusId, campusName FROM tbl_campus ORDER BY campusName ASC");
$campusList = [];
while ($row = $campusListResult->fetch_assoc()) {
    $campusList[] = $row;
}

$userTypeListResult = $db->query("SELECT userTypeId, userTypeName FROM tbl_usertype ORDER BY userTypeName ASC");
$userTypeList = [];
while ($row = $userTypeListResult->fetch_assoc()) {
    $userTypeList[] = $row;
}

$departmentListResult = $db->query("SELECT departmentId, departmentName FROM tbl_department ORDER BY departmentName ASC");
$departmentList = [];
while ($row = $departmentListResult->fetch_assoc()) {
    $departmentList[] = $row;
}

$courseListResult = $db->query("SELECT courseId, courseName FROM tbl_course ORDER BY courseName ASC");
$courseList = [];
while ($row = $courseListResult->fetch_assoc()) {
    $courseList[] = $row;
}

$nstpListResult = $db->query("SELECT nstpId, nstpType FROM tbl_nstp ORDER BY nstpType ASC");
$nstpList = [];
while ($row = $nstpListResult->fetch_assoc()) {
    $nstpList[] = $row;
}

$isSuperAdmin = ($role === 'super');

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>CiviCall Admin — User Management</title>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="styles/usermanagement.css">
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
            <div class="skel-table-row"><div style="display:flex;align-items:center;gap:10px;width:20%;"><div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div><div><div class="skel-block" style="width:100px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:130px;height:10px;border-radius:3px;"></div></div></div><div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:80px;height:10px;border-radius:3px;"></div></div><div class="skel-block" style="width:80px;height:22px;border-radius:30px;"></div><div class="skel-block" style="width:70px;height:13px;border-radius:4px;"></div><div class="skel-block" style="width:85px;height:13px;border-radius:4px;"></div><div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div></div>
            <div class="skel-table-row"><div style="display:flex;align-items:center;gap:10px;width:20%;"><div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div><div><div class="skel-block" style="width:110px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:140px;height:10px;border-radius:3px;"></div></div></div><div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:90px;height:10px;border-radius:3px;"></div></div><div class="skel-block" style="width:70px;height:22px;border-radius:30px;"></div><div class="skel-block" style="width:65px;height:13px;border-radius:4px;"></div><div class="skel-block" style="width:80px;height:13px;border-radius:4px;"></div><div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div></div>
            <div class="skel-table-row"><div style="display:flex;align-items:center;gap:10px;width:20%;"><div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div><div><div class="skel-block" style="width:95px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:120px;height:10px;border-radius:3px;"></div></div></div><div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:85px;height:10px;border-radius:3px;"></div></div><div class="skel-block" style="width:75px;height:22px;border-radius:30px;"></div><div class="skel-block" style="width:70px;height:13px;border-radius:4px;"></div><div class="skel-block" style="width:88px;height:13px;border-radius:4px;"></div><div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div></div>
            <div class="skel-table-row"><div style="display:flex;align-items:center;gap:10px;width:20%;"><div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div><div><div class="skel-block" style="width:105px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:135px;height:10px;border-radius:3px;"></div></div></div><div><div class="skel-block" style="width:120px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:75px;height:10px;border-radius:3px;"></div></div><div class="skel-block" style="width:65px;height:22px;border-radius:30px;"></div><div class="skel-block" style="width:72px;height:13px;border-radius:4px;"></div><div class="skel-block" style="width:82px;height:13px;border-radius:4px;"></div><div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div></div>
            <div class="skel-table-row"><div style="display:flex;align-items:center;gap:10px;width:20%;"><div class="skel-circle" style="width:36px;height:36px;flex-shrink:0;"></div><div><div class="skel-block" style="width:115px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:145px;height:10px;border-radius:3px;"></div></div></div><div><div class="skel-block" style="width:108px;height:13px;margin-bottom:5px;"></div><div class="skel-block" style="width:88px;height:10px;border-radius:3px;"></div></div><div class="skel-block" style="width:78px;height:22px;border-radius:30px;"></div><div class="skel-block" style="width:68px;height:13px;border-radius:4px;"></div><div class="skel-block" style="width:84px;height:13px;border-radius:4px;"></div><div style="display:flex;gap:10px;"><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div><div class="skel-block" style="width:20px;height:20px;border-radius:4px;"></div></div></div>
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
            <li class="nav-item"><a href="?url=usermanagement" class="nav-link active"><i class="fas fa-users"></i> Users</a></li>
            <?php if ($_SESSION['admin_role'] === 'super') { ?>
            <li class="nav-item"><a href="?url=subadmin" class="nav-link"><i class="fas fa-user-shield"></i> Sub Admin</a></li>
            <?php } ?>
            <li class="nav-item"><a href="?url=verification" class="nav-link"><i class="fas fa-id-card"></i> Verifications</a></li>
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
                <h1>User Management</h1>
                <p>Manage all registered users, verification status, and activity</p>
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

        <div class="search-filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="userSearchInput" placeholder="Search by name, email, or ID...">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="campusFilter">
                    <option value="">All Campuses</option>
<?php foreach ($campusList as $c): ?>
                    <option value="<?php echo $c['campusId']; ?>"><?php echo htmlspecialchars($c['campusName']); ?></option>
<?php endforeach; ?>
                </select>
                <select class="filter-select" id="verificationFilter">
                    <option value="">All Status</option>
                    <option value="1">Verified</option>
                    <option value="0">Pending</option>
                    <option value="2">Rejected</option>
                </select>
                <select class="filter-select" id="userTypeFilter">
                    <option value="">All User Type</option>
<?php foreach ($userTypeList as $ut): ?>
                    <option value="<?php echo $ut['userTypeId']; ?>"><?php echo htmlspecialchars($ut['userTypeName']); ?></option>
<?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="user-table-container">
            <table class="user-table">
                <thead>
                    <tr><th>User</th><th>Contact / Campus</th><th>Verification</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
                </thead>
                <tbody id="userTableBody">
<?php if (empty($users)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:30px;">No users found.</td></tr>
<?php else: foreach ($users as $user):
    $fullName = trim($user['firstName'] . ' ' . ($user['middleName'] ? $user['middleName'] . ' ' : '') . $user['lastName']);
    $initials = strtoupper(substr($user['firstName'], 0, 1) . substr($user['lastName'], 0, 1));
    if (!empty($user['photo_url'])) {
        $profilePic = filter_var($user['photo_url'], FILTER_VALIDATE_URL) ? $user['photo_url'] : '../CiviCallAPI/profileImage/' . $user['photo_url'];
    } else {
        $profilePic = '';
    }
    $verificationBadge = '';
    $verificationText = '';
    if ($user['isVerified'] == 1) {
        $verificationBadge = 'badge-verified';
        $verificationText = 'Verified';
    } elseif ($user['isVerified'] == 2) {
        $verificationBadge = 'badge-rejected';
        $verificationText = 'Rejected';
    } else {
        $verificationBadge = 'badge-pending';
        $verificationText = 'Pending';
    }
    $statusDot = 'dot-active';
    $statusText = 'Active';
    $createdAt = date('M d, Y', strtotime($user['created_at']));
?>
                    <tr data-campus="<?php echo (int)($user['campusId'] ?? 0); ?>" data-verification="<?php echo (int)$user['isVerified']; ?>" data-usertype="<?php echo (int)($user['userType'] ?? 0); ?>" data-search="<?php echo htmlspecialchars(strtolower($fullName . ' ' . $user['email'] . ' ' . $user['userId'])); ?>">
                        <td>
                            <div class="user-cell">
                                <?php if ($profilePic): ?>
                                <img src="<?php echo $profilePic; ?>" alt="<?php echo $fullName; ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;background:#f0f0f0;">
                                <?php else: ?>
                                <div class="user-avatar-small"><?php echo $initials; ?></div>
                                <?php endif; ?>
                                <div class="user-info-text">
                                    <strong><?php echo htmlspecialchars($fullName); ?></strong>
                                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user['mobileNum'] ?? ''); ?><br>
                            <span style="font-size:0.7rem;"><?php echo htmlspecialchars($user['campusName'] ?? 'N/A'); ?></span>
                        </td>
                        <td><span class="<?php echo $verificationBadge; ?>"><i class="fas <?php echo ($user['isVerified'] == 1) ? 'fa-check-circle' : (($user['isVerified'] == 2) ? 'fa-times-circle' : 'fa-clock'); ?>"></i> <?php echo $verificationText; ?></span></td>
                        <td><span class="status-dot <?php echo $statusDot; ?>"></span> <?php echo $statusText; ?></td>
                        <td><?php echo $createdAt; ?></td>
                        <td class="action-buttons">
                            <button class="action-btn view" data-user-id="<?php echo $user['userId']; ?>" data-name="<?php echo htmlspecialchars($fullName); ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>" data-mobile="<?php echo htmlspecialchars($user['mobileNum'] ?? ''); ?>" data-campus="<?php echo htmlspecialchars($user['campusName'] ?? 'N/A'); ?>" data-usertype="<?php echo htmlspecialchars($user['userTypeName'] ?? 'N/A'); ?>" data-verification="<?php echo $verificationText; ?>" data-joined="<?php echo $createdAt; ?>" data-photo="<?php echo htmlspecialchars($profilePic); ?>" data-initials="<?php echo htmlspecialchars($initials); ?>"><i class="fas fa-eye"></i></button>
                                                     <button class="action-btn edit"
                                data-user-id="<?php echo $user['userId']; ?>"
                                data-first-name="<?php echo htmlspecialchars($user['firstName']); ?>"
                                data-middle-name="<?php echo htmlspecialchars($user['middleName'] ?? ''); ?>"
                                data-last-name="<?php echo htmlspecialchars($user['lastName']); ?>"
                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                data-mobile="<?php echo htmlspecialchars($user['mobileNum'] ?? ''); ?>"
                                data-emergency="<?php echo htmlspecialchars($user['emergencyNum'] ?? ''); ?>"
                                data-address="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                                data-birthday="<?php echo htmlspecialchars($user['birthDay'] ?? ''); ?>"
                                data-gender="<?php echo isset($user['gender']) ? (int)$user['gender'] : ''; ?>"
                                data-campus-id="<?php echo (int)($user['campusId'] ?? 0); ?>"
                                data-department-id="<?php echo (int)($user['departmentId'] ?? 0); ?>"
                                data-course-id="<?php echo (int)($user['courseId'] ?? 0); ?>"
                                data-usertype-id="<?php echo (int)($user['userType'] ?? 0); ?>"
                                data-nstp-id="<?php echo (int)($user['nstpId'] ?? 0); ?>"
                                data-srcode="<?php echo htmlspecialchars($user['srCode'] ?? ''); ?>"
                                data-yrsection="<?php echo htmlspecialchars($user['yrSection'] ?? ''); ?>"
                                data-photo="<?php echo htmlspecialchars($profilePic); ?>"
                                data-initials="<?php echo htmlspecialchars($initials); ?>"
                            ><i class="fas fa-edit"></i></button>
                            <button class="action-btn block"><i class="fas fa-ban"></i></button>
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

<div class="modal-overlay" id="userDetailModal">
    <div class="modal-container">
        <div class="modal-header"><h3>User Details</h3><button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="user-detail-row" style="align-items:center;">
                <div class="user-detail-label">Photo</div>
                <div class="user-detail-value">
                    <img id="detailPhoto" src="" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;background:#f0f0f0;display:none;">
                    <div id="detailPhotoInitials" class="user-avatar-small" style="width:64px;height:64px;font-size:1.4rem;display:none;"></div>
                </div>
            </div>
            <div class="divider"></div>
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

<div class="modal-overlay" id="userEditModal">
    <div class="modal-container" style="max-width:560px;">
        <div class="modal-header"><h3>Edit User</h3><button class="modal-close" id="closeEditModalBtn"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="userId">

                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                    <img id="editPhotoPreview" src="" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;background:#f0f0f0;display:none;">
                    <div id="editPhotoInitials" class="user-avatar-small" style="width:64px;height:64px;font-size:1.4rem;display:none;"></div>
                    <div>
                        <label for="editPhotoInput" style="font-weight:700;font-size:0.8rem;color:var(--gray-600);cursor:pointer;">
                            <i class="fas fa-camera"></i> Change Photo
                        </label>
                        <input type="file" id="editPhotoInput" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:block;margin-top:6px;font-size:0.75rem;">
                    </div>
                </div>

                <div class="user-detail-row">
                    <div class="user-detail-label">Email</div>
                    <div class="user-detail-value">
                        <input type="email" id="editEmail" disabled style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);background:var(--gray-100);color:var(--gray-400);">
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">First Name</div>
                    <div class="user-detail-value"><input type="text" id="editFirstName" name="firstName" required style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Middle Name</div>
                    <div class="user-detail-value"><input type="text" id="editMiddleName" name="middleName" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Last Name</div>
                    <div class="user-detail-value"><input type="text" id="editLastName" name="lastName" required style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Mobile</div>
                    <div class="user-detail-value"><input type="text" id="editMobile" name="mobileNum" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Emergency #</div>
                    <div class="user-detail-value"><input type="text" id="editEmergency" name="emergencyNum" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Address</div>
                    <div class="user-detail-value"><input type="text" id="editAddress" name="address" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Birthday</div>
                    <div class="user-detail-value"><input type="date" id="editBirthday" name="birthDay" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Gender</div>
                    <div class="user-detail-value">
                        <select id="editGender" name="gender" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Campus</div>
                    <div class="user-detail-value">
                        <select id="editCampus" name="campusId" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
<?php foreach ($campusList as $c): ?>
                            <option value="<?php echo $c['campusId']; ?>"><?php echo htmlspecialchars($c['campusName']); ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Department</div>
                    <div class="user-detail-value">
                        <select id="editDepartment" name="departmentId" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
                            <option value="">-- None --</option>
<?php foreach ($departmentList as $d): ?>
                            <option value="<?php echo $d['departmentId']; ?>"><?php echo htmlspecialchars($d['departmentName']); ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Course</div>
                    <div class="user-detail-value">
                        <select id="editCourse" name="courseId" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
                            <option value="">-- None --</option>
<?php foreach ($courseList as $co): ?>
                            <option value="<?php echo $co['courseId']; ?>"><?php echo htmlspecialchars($co['courseName']); ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">User Type</div>
                    <div class="user-detail-value">
                        <select id="editUserType" name="userTypeId" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
<?php foreach ($userTypeList as $ut): ?>
                            <option value="<?php echo $ut['userTypeId']; ?>"><?php echo htmlspecialchars($ut['userTypeName']); ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">NSTP</div>
                    <div class="user-detail-value">
                        <select id="editNstp" name="nstpId" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);">
                            <option value="">-- None --</option>
<?php foreach ($nstpList as $n): ?>
                            <option value="<?php echo $n['nstpId']; ?>"><?php echo htmlspecialchars($n['nstpType']); ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">SR Code</div>
                    <div class="user-detail-value"><input type="text" id="editSrCode" name="srCode" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>
                <div class="user-detail-row">
                    <div class="user-detail-label">Yr & Section</div>
                    <div class="user-detail-value"><input type="text" id="editYrSection" name="yrSection" style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--gray-200);"></div>
                </div>

                <div class="divider"></div>
                <div id="editFormMessage" style="font-size:0.8rem;font-weight:700;margin-bottom:12px;"></div>
                <div style="display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" id="cancelEditBtn" style="background:var(--white);border:1px solid var(--gray-200);border-radius:8px;padding:10px 18px;font-weight:700;cursor:pointer;">Cancel</button>
                    <button type="submit" style="background:var(--red);color:#fff;border:none;border-radius:8px;padding:10px 22px;font-weight:700;cursor:pointer;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const IS_SUPER_ADMIN = <?php echo $isSuperAdmin ? 'true' : 'false'; ?>;
</script>
<script src="js/usermanagement.js"></script>
</body>
</html>