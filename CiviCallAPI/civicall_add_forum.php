<?php
// civicall_add_forum.php
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

$userStmt = $db->prepare("SELECT isVerified FROM tbl_user WHERE userId = ?");
$userStmt->bind_param("i", $userId);
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
$isVerified = (int)$userRow['isVerified'];
$userStmt->close();

if ($isVerified !== 1) {
    $response['success'] = false;
    $response['message'] = 'Only verified users can create forum posts.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
if ($message === '') {
    $response['success'] = false;
    $response['message'] = 'Forum message is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$insertStmt = $db->prepare("INSERT INTO tbl_forum (userId, message, upCount, downCount, isRemove, createdAt) VALUES (?, ?, 0, 0, 0, NOW())");
$insertStmt->bind_param("is", $userId, $message);

if (!$insertStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to create forum post: ' . $insertStmt->error;
    $insertStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$forumId = $insertStmt->insert_id;
$insertStmt->close();

$photoFileName = null;

if (isset($_FILES['forumImage']) && $_FILES['forumImage']['error'] === UPLOAD_ERR_OK) {
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['forumImage']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMime)) {
        $response['success'] = true;
        $response['message'] = 'Forum post created but image type was invalid.';
        $response['forumId'] = $forumId;
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $maxSize = 20 * 1024 * 1024;
    if ($_FILES['forumImage']['size'] > $maxSize) {
        $response['success'] = true;
        $response['message'] = 'Forum post created but image exceeds 20MB.';
        $response['forumId'] = $forumId;
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $ext = pathinfo($_FILES['forumImage']['name'], PATHINFO_EXTENSION);
    if (empty($ext)) {
        $extMap = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $ext = $extMap[$mimeType] ?? 'jpg';
    }

    $timestamp = time();
    $photoFileName = $userId . '_' . $timestamp . '_' . $forumId . '.' . strtolower($ext);

    $uploadDir = __DIR__ . '/forumImages/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destPath = $uploadDir . $photoFileName;
    if (move_uploaded_file($_FILES['forumImage']['tmp_name'], $destPath)) {
        $imgStmt = $db->prepare("UPDATE tbl_forum SET image = ? WHERE forumId = ?");
        $imgStmt->bind_param("si", $photoFileName, $forumId);
        $imgStmt->execute();
        $imgStmt->close();
    } else {
        $photoFileName = null;
    }
}

$response['success'] = true;
$response['message'] = 'Forum post created successfully.';
$response['forumId'] = $forumId;
if ($photoFileName !== null) {
    $response['forumImage'] = $photoFileName;
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>