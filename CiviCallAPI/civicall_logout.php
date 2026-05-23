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

$stmt = $db->prepare("UPDATE tbl_userdevice SET isActive = 0 WHERE authToken = ?");
$stmt->bind_param("s", $authToken);
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Logged out successfully.';
} else {
    $response['success'] = false;
    $response['message'] = 'Logout failed.';
}

$stmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>