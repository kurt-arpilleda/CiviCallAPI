<?php
require_once '../kurt_dbCon.php';

$response = array();

$authToken = isset($_POST['authToken']) ? trim($_POST['authToken']) : '';
$reportText = isset($_POST['reportText']) ? trim($_POST['reportText']) : '';
$reportType = isset($_POST['reportType']) ? (int)$_POST['reportType'] : 0;

if ($authToken === '') {
    $response['success'] = false;
    $response['message'] = 'Auth token is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($reportText === '') {
    $response['success'] = false;
    $response['message'] = 'Report text is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($reportType < 1 || $reportType > 3) {
    $response['success'] = false;
    $response['message'] = 'Invalid report type.';
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

$fileName = null;

if (isset($_FILES['reportImage']) && $_FILES['reportImage']['error'] === UPLOAD_ERR_OK) {
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['reportImage']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMime)) {
        $response['success'] = false;
        $response['message'] = 'Invalid image type. Only JPEG, PNG, WEBP allowed.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $maxSize = 10 * 1024 * 1024;
    if ($_FILES['reportImage']['size'] > $maxSize) {
        $response['success'] = false;
        $response['message'] = 'Image size must not exceed 10MB.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

    $ext = pathinfo($_FILES['reportImage']['name'], PATHINFO_EXTENSION);
    if (empty($ext)) {
        $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $ext = $extMap[$mimeType] ?? 'jpg';
    }

    $timestamp = time();
    $fileName = $userId . '_' . $timestamp . '_' . $reportType . '.' . strtolower($ext);

    $uploadDir = __DIR__ . '/reportImage/';
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

    $destPath = $uploadDir . $fileName;
    if (!move_uploaded_file($_FILES['reportImage']['tmp_name'], $destPath)) {
        $response['success'] = false;
        $response['message'] = 'Failed to save image.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
}

$insertStmt = $db->prepare("INSERT INTO tbl_report (userId, fileName, reportText, reportType, dateTime) VALUES (?, ?, ?, ?, NOW())");
$insertStmt->bind_param("issi", $userId, $fileName, $reportText, $reportType);

if ($insertStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Report submitted successfully.';
} else {
    if ($fileName !== null && file_exists($uploadDir . $fileName)) {
        unlink($uploadDir . $fileName);
    }
    $response['success'] = false;
    $response['message'] = 'Failed to save report.';
}

$insertStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>