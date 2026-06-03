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

$verifyStmt = $db->prepare("SELECT isVerified FROM tbl_user WHERE userId = ?");
$verifyStmt->bind_param("i", $userId);
$verifyStmt->execute();
$verifyResult = $verifyStmt->get_result();
$verifyRow = $verifyResult->fetch_assoc();
$verifyStmt->close();

if (!$verifyRow || (int)$verifyRow['isVerified'] !== 1) {
    $response['success'] = false;
    $response['message'] = 'not_verified';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$engagementId = isset($_POST['engagementId']) ? (int)$_POST['engagementId'] : 0;

if ($engagementId === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$engStmt = $db->prepare("SELECT engagementId FROM tbl_engagement WHERE engagementId = ? AND verificationStatus = 1");
$engStmt->bind_param("i", $engagementId);
$engStmt->execute();
$engResult = $engStmt->get_result();
$engStmt->close();

if ($engResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement not found or not verified.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$checkStmt = $db->prepare("SELECT participantId, isCancel FROM tbl_participant WHERE engagementId = ? AND userId = ?");
$checkStmt->bind_param("ii", $engagementId, $userId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $existing = $checkResult->fetch_assoc();
    $checkStmt->close();
    if ((int)$existing['isCancel'] === 0) {
        $response['success'] = false;
        $response['message'] = 'You have already joined this engagement.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    $rejoinStmt = $db->prepare("UPDATE tbl_participant SET isCancel = 0, joinStamp = NOW() WHERE participantId = ?");
    $rejoinStmt->bind_param("i", $existing['participantId']);
    $rejoinStmt->execute();
    $rejoinStmt->close();
    $response['success'] = true;
    $response['message'] = 'Successfully rejoined the engagement.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$checkStmt->close();

$insertStmt = $db->prepare("INSERT INTO tbl_participant (engagementId, userId, joinStamp, isCancel) VALUES (?, ?, NOW(), 0)");
$insertStmt->bind_param("ii", $engagementId, $userId);

if (!$insertStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to join engagement: ' . $insertStmt->error;
    $insertStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$insertStmt->close();

$response['success'] = true;
$response['message'] = 'Successfully joined the engagement.';
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>