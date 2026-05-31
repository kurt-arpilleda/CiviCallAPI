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

$engagementId = isset($_POST['engagementId']) ? (int)$_POST['engagementId'] : 0;

if ($engagementId === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$checkStmt = $db->prepare("SELECT uploaderId, verificationStatus FROM tbl_engagement WHERE engagementId = ?");
$checkStmt->bind_param("i", $engagementId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Engagement not found.';
    $checkStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$engRow = $checkResult->fetch_assoc();
$checkStmt->close();

if ((int)$engRow['uploaderId'] !== $userId) {
    $response['success'] = false;
    $response['message'] = 'You are not authorized to edit this engagement.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ((int)$engRow['verificationStatus'] === 1) {
    $response['success'] = false;
    $response['message'] = 'Verified engagements cannot be edited.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$categoryId      = isset($_POST['categoryId'])      ? (int)$_POST['categoryId']           : 0;
$title           = isset($_POST['title'])           ? trim($_POST['title'])               : '';
$description     = isset($_POST['description'])     ? trim($_POST['description'])         : '';
$objective       = isset($_POST['objective'])       ? trim($_POST['objective'])           : '';
$instruction     = isset($_POST['instruction'])     ? trim($_POST['instruction'])         : '';
$locationAddress = isset($_POST['locationAddress']) ? trim($_POST['locationAddress'])     : '';
$latitude        = isset($_POST['latitude'])        ? (float)$_POST['latitude']           : 0.0;
$longitude       = isset($_POST['longitude'])       ? (float)$_POST['longitude']          : 0.0;
$startSchedule   = isset($_POST['startSchedule'])   ? trim($_POST['startSchedule'])       : '';
$endSchedule     = isset($_POST['endSchedule'])     ? trim($_POST['endSchedule'])         : '';
$campus          = isset($_POST['campus'])          ? trim($_POST['campus'])              : '';
$targetParty     = isset($_POST['targetParty'])     ? (int)$_POST['targetParty']          : 0;
$activityPoints  = isset($_POST['activityPoints'])  ? (int)$_POST['activityPoints']       : 0;
$facilitatorName = isset($_POST['facilitatorName']) ? trim($_POST['facilitatorName'])     : '';
$facilitatorContact = isset($_POST['facilitatorContact']) ? trim($_POST['facilitatorContact']) : '';

if ($title === '') {
    $response['success'] = false;
    $response['message'] = 'Engagement title is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$updateStmt = $db->prepare("
    UPDATE tbl_engagement SET
        category = ?,
        titleEngagement = ?,
        description = ?,
        objective = ?,
        instruction = ?,
        locationAddress = ?,
        latitude = ?,
        longitude = ?,
        startSchedule = ?,
        endSchedule = ?,
        campus = ?,
        targetParty = ?,
        activityPoints = ?,
        facilitatorName = ?,
        facilitatorContact = ?
    WHERE engagementId = ? AND uploaderId = ?
");

$updateStmt->bind_param(
    "isssssddsssiissii",
    $categoryId,
    $title,
    $description,
    $objective,
    $instruction,
    $locationAddress,
    $latitude,
    $longitude,
    $startSchedule,
    $endSchedule,
    $campus,
    $targetParty,
    $activityPoints,
    $facilitatorName,
    $facilitatorContact,
    $engagementId,
    $userId
);

if (!$updateStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to update engagement: ' . $updateStmt->error;
    $updateStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$updateStmt->close();

$photoFileName = null;

if (isset($_FILES['engagementImage']) && $_FILES['engagementImage']['error'] === UPLOAD_ERR_OK) {
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['engagementImage']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMime)) {
        $response['success'] = true;
        $response['message'] = 'Engagement updated but image type was invalid.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $maxSize = 20 * 1024 * 1024;
    if ($_FILES['engagementImage']['size'] > $maxSize) {
        $response['success'] = true;
        $response['message'] = 'Engagement updated but image exceeds 20MB.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $ext = pathinfo($_FILES['engagementImage']['name'], PATHINFO_EXTENSION);
    if (empty($ext)) {
        $extMap = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $ext = $extMap[$mimeType] ?? 'jpg';
    }

    $timestamp = time();
    $photoFileName = $userId . '_' . $timestamp . '_' . $engagementId . '.' . strtolower($ext);

    $uploadDir = __DIR__ . '/engagementImage/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $oldImgStmt = $db->prepare("SELECT engagementImage FROM tbl_engagement WHERE engagementId = ?");
    $oldImgStmt->bind_param("i", $engagementId);
    $oldImgStmt->execute();
    $oldImgResult = $oldImgStmt->get_result();
    if ($oldImgResult->num_rows > 0) {
        $oldImgRow = $oldImgResult->fetch_assoc();
        $oldImg = $oldImgRow['engagementImage'];
        if (!empty($oldImg)) {
            $oldPath = $uploadDir . $oldImg;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }
    $oldImgStmt->close();

    $destPath = $uploadDir . $photoFileName;
    if (move_uploaded_file($_FILES['engagementImage']['tmp_name'], $destPath)) {
        $imgStmt = $db->prepare("UPDATE tbl_engagement SET engagementImage = ? WHERE engagementId = ?");
        $imgStmt->bind_param("si", $photoFileName, $engagementId);
        $imgStmt->execute();
        $imgStmt->close();
    } else {
        $photoFileName = null;
    }
}

$response['success'] = true;
$response['message'] = 'Engagement updated successfully.';
if ($photoFileName !== null) {
    $response['engagementImage'] = $photoFileName;
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>