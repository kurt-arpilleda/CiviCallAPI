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

$engagementId = isset($_POST['engagementId']) ? (int)$_POST['engagementId'] : 0;

if ($engagementId === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$countStmt = $db->prepare("SELECT COUNT(*) AS total FROM tbl_participant WHERE engagementId = ? AND isCancel = 0");
$countStmt->bind_param("i", $engagementId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$total = (int)$countRow['total'];
$countStmt->close();

$myStmt = $db->prepare("SELECT isCancel FROM tbl_participant WHERE engagementId = ? AND userId = ?");
$myStmt->bind_param("ii", $engagementId, $currentUserId);
$myStmt->execute();
$myResult = $myStmt->get_result();
$isJoined = 0;
if ($myResult->num_rows > 0) {
    $myRow = $myResult->fetch_assoc();
    $isJoined = (int)$myRow['isCancel'] === 0 ? 1 : 0;
}
$myStmt->close();

$stmt = $db->prepare("
    SELECT 
        p.userId,
        u.firstName,
        u.lastName,
        u.photo_url,
        c.campusName,
        p.joinStamp
    FROM tbl_participant p
    JOIN tbl_user u ON u.userId = p.userId
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    WHERE p.engagementId = ? AND p.isCancel = 0
    ORDER BY p.joinStamp ASC
");
$stmt->bind_param("i", $engagementId);
$stmt->execute();
$result = $stmt->get_result();

$participants = array();
while ($row = $result->fetch_assoc()) {
    $participants[] = array(
        'userId'     => (int)$row['userId'],
        'firstName'  => $row['firstName'],
        'lastName'   => $row['lastName'],
        'photo_url'  => $row['photo_url'],
        'campusName' => $row['campusName'],
        'joinStamp'  => $row['joinStamp'],
    );
}
$stmt->close();

$response['success']      = true;
$response['total']        = $total;
$response['isJoined']     = $isJoined;
$response['participants'] = $participants;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>