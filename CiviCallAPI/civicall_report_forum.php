<?php
require_once '../kurt_dbCon.php';

$response = array();

$authToken = isset($_POST['authToken']) ? trim($_POST['authToken']) : '';
if ($authToken === '') {
    $response['success'] = false;
    $response['message'] = 'Auth token is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$tokenStmt = $db->prepare("SELECT userId FROM tbl_userdevice WHERE authToken = ? AND isActive = 1");
$tokenStmt->bind_param("s", $authToken);
$tokenStmt->execute();
$tokenResult = $tokenStmt->get_result();

if ($tokenResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Invalid or expired token.';
    $tokenStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$tokenRow = $tokenResult->fetch_assoc();
$userId = $tokenRow['userId'];
$tokenStmt->close();

$targetType = isset($_POST['targetType']) ? trim($_POST['targetType']) : '';
$targetId = isset($_POST['targetId']) ? (int)$_POST['targetId'] : 0;
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
$details = isset($_POST['details']) ? trim($_POST['details']) : '';

if ($targetType !== 'post' && $targetType !== 'comment') {
    $response['success'] = false;
    $response['message'] = 'Invalid target type.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($targetId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Target ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($reason === '') {
    $response['success'] = false;
    $response['message'] = 'Reason is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($targetType === 'post') {
    $checkStmt = $db->prepare("SELECT forumId FROM tbl_forum WHERE forumId = ? AND isRemove = 0");
    $checkStmt->bind_param("i", $targetId);
} else {
    $checkStmt = $db->prepare("SELECT commentId FROM tbl_forumcomment WHERE commentId = ? AND isRemove = 0");
    $checkStmt->bind_param("i", $targetId);
}
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = $targetType === 'post' ? 'Forum post not found.' : 'Comment not found.';
    $checkStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$checkStmt->close();

$dupStmt = $db->prepare("SELECT reportId FROM tbl_forumreport WHERE targetType = ? AND targetId = ? AND reporterId = ?");
$dupStmt->bind_param("sii", $targetType, $targetId, $userId);
$dupStmt->execute();
$dupResult = $dupStmt->get_result();

if ($dupResult->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'You have already reported this ' . $targetType . '.';
    $dupStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$dupStmt->close();

$insertStmt = $db->prepare("INSERT INTO tbl_forumreport (targetType, targetId, reporterId, reason, details, status, createdAt) VALUES (?, ?, ?, ?, ?, 0, NOW())");
$insertStmt->bind_param("siiss", $targetType, $targetId, $userId, $reason, $details);

if (!$insertStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to submit report: ' . $insertStmt->error;
    $insertStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$reportId = $insertStmt->insert_id;
$insertStmt->close();

$response['success'] = true;
$response['message'] = 'Report submitted successfully.';
$response['reportId'] = $reportId;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>