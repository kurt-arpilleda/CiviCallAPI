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

$userStmt = $db->prepare("SELECT userId, firstName, middleName, lastName, email, mobileNum, photo_url, isVerified FROM tbl_user WHERE userId = ?");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    $user['isVerified'] = (int)$user['isVerified'];
    $response['success'] = true;
    $response['user'] = $user;
} else {
    $response['success'] = false;
    $response['message'] = 'User not found.';
}

$userStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>