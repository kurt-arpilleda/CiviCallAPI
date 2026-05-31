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
        e.engagementId,
        e.uploaderId,
        e.category AS categoryId,
        ec.categoryName,
        e.titleEngagement,
        e.description,
        e.objective,
        e.instruction,
        e.locationAddress,
        e.latitude,
        e.longitude,
        e.startSchedule,
        e.endSchedule,
        e.campus,
        e.targetParty,
        e.activityPoints,
        e.facilitatorName,
        e.facilitatorContact,
        e.engagementImage,
        e.verificationStatus,
        e.createdAt
    FROM tbl_engagement e
    LEFT JOIN tbl_engagementcategory ec ON ec.categoryId = e.category
    WHERE (
        (e.verificationStatus = 1 AND FIND_IN_SET(?, e.campus) > 0)
        OR
        (e.verificationStatus = 0 AND e.uploaderId = ?)
    )
    ORDER BY e.createdAt DESC
";

$stmt = $db->prepare($query);
$stmt->bind_param("ii", $userCampus, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$engagements = array();
while ($row = $result->fetch_assoc()) {
    $row['engagementId']      = (int)$row['engagementId'];
    $row['uploaderId']        = (int)$row['uploaderId'];
    $row['categoryId']        = (int)$row['categoryId'];
    $row['targetParty']       = (int)$row['targetParty'];
    $row['activityPoints']    = (int)$row['activityPoints'];
    $row['verificationStatus']= (int)$row['verificationStatus'];
    $row['latitude']          = (float)$row['latitude'];
    $row['longitude']         = (float)$row['longitude'];
    $row['isOwner']           = ($row['uploaderId'] === $currentUserId) ? 1 : 0;
    $engagements[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['currentUserId'] = $currentUserId;
$response['engagements'] = $engagements;

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>