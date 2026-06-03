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

$engagementId = isset($_POST['engagementId']) ? (int)$_POST['engagementId'] : 0;

if ($engagementId === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$updateStmt = $db->prepare("UPDATE tbl_participant SET isCancel = 1 WHERE engagementId = ? AND userId = ? AND isCancel = 0");
$updateStmt->bind_param("ii", $engagementId, $userId);
$updateStmt->execute();
$affected = $updateStmt->affected_rows;
$updateStmt->close();

if ($affected === 0) {
    $response['success'] = false;
    $response['message'] = 'No active participation found to cancel.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$response['success'] = true;
$response['message'] = 'Participation cancelled successfully.';
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>