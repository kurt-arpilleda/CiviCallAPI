<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');
$response = [];

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    $response['success'] = false;
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    exit;
}

$adminRole = $_SESSION['admin_role'];
$adminId   = $_SESSION['admin_id'];

$userId = isset($_POST['userId']) ? (int)$_POST['userId'] : 0;
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

if ($userId <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    $response['success'] = false;
    $response['message'] = 'Invalid request.';
    echo json_encode($response);
    exit;
}

if ($adminRole === 'sub') {
    $stmt = $db->prepare("SELECT campusId FROM tbl_subadmin WHERE subId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    $subCampusId = null;
    if ($row = $res->fetch_assoc()) {
        $subCampusId = (int)$row['campusId'];
    }
    $stmt->close();

    $checkStmt = $db->prepare("SELECT campus FROM tbl_user WHERE userId = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'User not found.';
        $checkStmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }
    $checkRow = $checkStmt->fetch_assoc();
    $checkStmt->close();

    if ($subCampusId === null || (int)$checkRow['campus'] !== $subCampusId) {
        $response['success'] = false;
        $response['message'] = 'You are not authorized to review verifications outside your campus.';
        echo json_encode($response);
        $db->close();
        exit;
    }
} elseif ($adminRole !== 'super') {
    $response['success'] = false;
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    $db->close();
    exit;
}

$verifStmt = $db->prepare("SELECT userId FROM tbl_userverification WHERE userId = ?");
$verifStmt->bind_param("i", $userId);
$verifStmt->execute();
$verifResult = $verifStmt->get_result();
if ($verifResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'No verification document found for this user.';
    $verifStmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}
$verifStmt->close();

$newStatus = ($action === 'approve') ? 1 : 2;

$updateStmt = $db->prepare("UPDATE tbl_user SET isVerified = ? WHERE userId = ?");
$updateStmt->bind_param("ii", $newStatus, $userId);

if ($updateStmt->execute()) {
    $response['success'] = true;
    $response['message'] = $action === 'approve' ? 'Verification approved.' : 'Verification rejected.';
    $response['status'] = $newStatus;
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to update verification status.';
}

$updateStmt->close();
echo json_encode($response);
$db->close();
?>