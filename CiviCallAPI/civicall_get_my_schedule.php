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

$stmt = $db->prepare("
    SELECT
        p.participantId,
        p.engagementId,
        p.isCancel,
        p.isAttend,
        p.joinStamp,
        e.titleEngagement,
        e.description,
        e.locationAddress,
        e.startSchedule,
        e.endSchedule,
        e.activityPoints,
        e.facilitatorName,
        e.facilitatorContact,
        e.engagementImage,
        ec.categoryName
    FROM tbl_participant p
    JOIN tbl_engagement e ON e.engagementId = p.engagementId
    LEFT JOIN tbl_engagementcategory ec ON ec.categoryId = e.category
    WHERE p.userId = ?
    ORDER BY e.startSchedule ASC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$schedules = array();
while ($row = $result->fetch_assoc()) {
    $schedules[] = array(
        'participantId'    => (int)$row['participantId'],
        'engagementId'     => (int)$row['engagementId'],
        'isCancel'         => (int)$row['isCancel'],
        'isAttend'         => (int)$row['isAttend'],
        'joinStamp'        => $row['joinStamp'],
        'titleEngagement'  => $row['titleEngagement'],
        'description'      => $row['description'],
        'locationAddress'  => $row['locationAddress'],
        'startSchedule'    => $row['startSchedule'],
        'endSchedule'      => $row['endSchedule'],
        'activityPoints'   => (int)$row['activityPoints'],
        'facilitatorName'  => $row['facilitatorName'],
        'facilitatorContact' => $row['facilitatorContact'],
        'engagementImage'  => $row['engagementImage'],
        'categoryName'     => $row['categoryName'],
    );
}
$stmt->close();

$response['success']   = true;
$response['schedules'] = $schedules;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>