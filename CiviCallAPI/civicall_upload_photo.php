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

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $response['success'] = false;
    $response['message'] = 'No image file uploaded or upload error.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['photo']['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    $response['success'] = false;
    $response['message'] = 'Invalid image type. Only JPEG, PNG, WEBP allowed.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$maxSize = 20 * 1024 * 1024;
if ($_FILES['photo']['size'] > $maxSize) {
    $response['success'] = false;
    $response['message'] = 'Image size must not exceed 20MB.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
if (empty($ext)) {
    $extMap = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $ext = $extMap[$mimeType] ?? 'jpg';
}

$timestamp = time();
$fileName = $userId . '_' . $timestamp . '.' . strtolower($ext);

$uploadDir = __DIR__ . '/profileImage/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        $response['success'] = false;
        $response['message'] = 'Failed to create upload folder.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
}

$oldStmt = $db->prepare("SELECT photo_url FROM tbl_user WHERE userId = ?");
$oldStmt->bind_param("i", $userId);
$oldStmt->execute();
$oldResult = $oldStmt->get_result();
if ($oldResult->num_rows > 0) {
    $oldRow = $oldResult->fetch_assoc();
    $oldPhoto = $oldRow['photo_url'];
    if (!empty($oldPhoto) && !filter_var($oldPhoto, FILTER_VALIDATE_URL)) {
        $oldFilePath = $uploadDir . $oldPhoto;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }
}
$oldStmt->close();

$destPath = $uploadDir . $fileName;
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destPath)) {
    $response['success'] = false;
    $response['message'] = 'Failed to save image. Check write permission.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$updateStmt = $db->prepare("UPDATE tbl_user SET photo_url = ? WHERE userId = ?");
$updateStmt->bind_param("si", $fileName, $userId);

if ($updateStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Profile photo updated successfully.';
    $response['photo_url'] = $fileName;
} else {
    unlink($destPath);
    $response['success'] = false;
    $response['message'] = 'Database update failed.';
}

$updateStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>