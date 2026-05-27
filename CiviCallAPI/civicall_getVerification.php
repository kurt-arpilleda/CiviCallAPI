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

$stmt = $db->prepare("SELECT userId FROM tbl_userdevice WHERE authToken = ? AND isActive = 1");
$stmt->bind_param("s", $authToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Invalid or expired token.';
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$row = $result->fetch_assoc();
$userId = $row['userId'];
$stmt->close();

$verifStmt = $db->prepare("SELECT fileName, fileType, dateTime FROM tbl_userverification WHERE userId = ? ORDER BY dateTime DESC LIMIT 1");
$verifStmt->bind_param("i", $userId);
$verifStmt->execute();
$verifResult = $verifStmt->get_result();

if ($verifResult->num_rows > 0) {
    $data = $verifResult->fetch_assoc();
    $response['success'] = true;
    $response['data'] = $data;
} else {
    $response['success'] = true;
    $response['data'] = null;
}

$verifStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>