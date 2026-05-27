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

$selectStmt = $db->prepare("SELECT reportId, fileName, reportText, reportType, dateTime FROM tbl_report WHERE userId = ? ORDER BY dateTime DESC");
$selectStmt->bind_param("i", $userId);
$selectStmt->execute();
$result = $selectStmt->get_result();

$reports = array();
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

$response['success'] = true;
$response['reports'] = $reports;

$selectStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>