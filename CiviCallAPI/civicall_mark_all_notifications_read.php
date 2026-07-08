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
$currentUserId = $tokenRow['userId'];
$tokenStmt->close();

$userStmt = $db->prepare("SELECT campus FROM tbl_user WHERE userId = ?");
$userStmt->bind_param("i", $currentUserId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'User not found.';
    $userStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$userRow = $userResult->fetch_assoc();
$userCampus = (int)$userRow['campus'];
$userStmt->close();

$updateStmt = $db->prepare("
    UPDATE tbl_notification
    SET notificationStatus = 1
    WHERE notificationStatus = 0
    AND (
        (targetType = 0 AND notificationTarget = ?)
        OR
        (targetType = 1 AND notificationTarget = ?)
    )
");
$updateStmt->bind_param("ii", $userCampus, $currentUserId);
$updateStmt->execute();

$response['success'] = true;
$response['message'] = 'All notifications marked as read.';
$response['affectedRows'] = $updateStmt->affected_rows;

$updateStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>