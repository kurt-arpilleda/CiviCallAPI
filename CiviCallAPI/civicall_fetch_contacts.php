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

$campusId = isset($_POST['campusId']) ? (int)$_POST['campusId'] : 0;

if ($campusId <= 0) {
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
    $campusId = (int)$userRow['campus'];
    $userStmt->close();
}

$stmt = $db->prepare("SELECT campusId, contactName, contactNumber, contactImage FROM tbl_contact WHERE campusId = ? ORDER BY contactName ASC");
$stmt->bind_param("i", $campusId);
$stmt->execute();
$result = $stmt->get_result();

$contacts = array();
while ($row = $result->fetch_assoc()) {
    $row['campusId'] = (int)$row['campusId'];
    $contacts[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['campusId'] = $campusId;
$response['contacts'] = $contacts;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>