<?php
require_once '../kurt_dbCon.php';

$response = array();

$authToken = isset($_POST['authToken']) ? trim($_POST['authToken']) : '';
$fileType = isset($_POST['fileType']) ? (int)$_POST['fileType'] : 0;

if ($authToken === '') {
    $response['success'] = false;
    $response['message'] = 'Auth token is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($fileType < 1 || $fileType > 4) {
    $response['success'] = false;
    $response['message'] = 'Invalid document type.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if (!isset($_FILES['verificationFile']) || $_FILES['verificationFile']['error'] !== UPLOAD_ERR_OK) {
    $response['success'] = false;
    $response['message'] = 'No file uploaded or upload error.';
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

$allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['verificationFile']['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMime)) {
    $response['success'] = false;
    $response['message'] = 'Invalid file type. Only JPEG, PNG, WEBP, PDF allowed.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$maxSize = 10 * 1024 * 1024;
if ($_FILES['verificationFile']['size'] > $maxSize) {
    $response['success'] = false;
    $response['message'] = 'File size must not exceed 10MB.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$ext = pathinfo($_FILES['verificationFile']['name'], PATHINFO_EXTENSION);
if (empty($ext)) {
    $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];
    $ext = $extMap[$mimeType] ?? 'bin';
}

$timestamp = time();
$fileName = $userId . '_' . $timestamp . '_' . $fileType . '.' . strtolower($ext);

$uploadDir = __DIR__ . '/fileVerification/';
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

$oldStmt = $db->prepare("SELECT fileName FROM tbl_userverification WHERE userId = ?");
$oldStmt->bind_param("i", $userId);
$oldStmt->execute();
$oldResult = $oldStmt->get_result();
if ($oldResult->num_rows > 0) {
    $oldRow = $oldResult->fetch_assoc();
    $oldFile = $oldRow['fileName'];
    if (!empty($oldFile)) {
        $oldPath = $uploadDir . $oldFile;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
}
$oldStmt->close();

$destPath = $uploadDir . $fileName;
if (!move_uploaded_file($_FILES['verificationFile']['tmp_name'], $destPath)) {
    $response['success'] = false;
    $response['message'] = 'Failed to save file.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$insertStmt = $db->prepare("REPLACE INTO tbl_userverification (userId, fileName, fileType, dateTime) VALUES (?, ?, ?, NOW())");
$insertStmt->bind_param("isi", $userId, $fileName, $fileType);

if ($insertStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Verification document uploaded successfully.';
} else {
    unlink($destPath);
    $response['success'] = false;
    $response['message'] = 'Database update failed.';
}

$insertStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>