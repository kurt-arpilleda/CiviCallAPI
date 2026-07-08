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

$query = "
    SELECT
        n.notifId,
        n.notifDetailId,
        n.notificationTarget,
        n.notificationStatus,
        n.targetType,
        n.dateTime,
        nd.notificationDetail,
        nt.typeId,
        nt.notifName
    FROM tbl_notification n
    JOIN tbl_notificationdetail nd ON nd.notifDetailId = n.notifDetailId
    JOIN tbl_notificationtype nt ON nt.typeId = nd.notificationType
    WHERE n.notificationStatus != 2
    AND nt.status = 1
    AND (
        (n.targetType = 0 AND n.notificationTarget = ?)
        OR
        (n.targetType = 1 AND n.notificationTarget = ?)
    )
    ORDER BY n.dateTime DESC
";

$stmt = $db->prepare($query);
$stmt->bind_param("ii", $userCampus, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = array();
$unreadCount = 0;
while ($row = $result->fetch_assoc()) {
    $row['notifId'] = (int)$row['notifId'];
    $row['notifDetailId'] = (int)$row['notifDetailId'];
    $row['notificationTarget'] = (int)$row['notificationTarget'];
    $row['notificationStatus'] = (int)$row['notificationStatus'];
    $row['targetType'] = (int)$row['targetType'];
    $row['typeId'] = (int)$row['typeId'];
    if ($row['notificationStatus'] === 0) {
        $unreadCount++;
    }
    $notifications[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['unreadCount'] = $unreadCount;
$response['notifications'] = $notifications;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>