<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');
$response = [];

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    $response['success'] = false;
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    exit;
}

$adminRole = $_SESSION['admin_role'];
$adminId   = $_SESSION['admin_id'];

$userId = isset($_POST['userId']) ? (int)$_POST['userId'] : 0;
if ($userId <= 0) {
    $response['success'] = false;
    $response['message'] = 'Invalid user ID.';
    echo json_encode($response);
    exit;
}

// Sub admins may only edit users belonging to their own campus, and can never change campus.
$campusId = null;
if ($adminRole === 'sub') {
    $stmt = $db->prepare("SELECT campusId FROM tbl_subadmin WHERE subId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    $subCampusId = null;
    if ($row = $res->fetch_assoc()) {
        $subCampusId = (int)$row['campusId'];
    }
    $stmt->close();

    $checkStmt = $db->prepare("SELECT campus FROM tbl_user WHERE userId = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'User not found.';
        $checkStmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }
    $checkRow = $checkStmt->fetch_assoc();
    $checkStmt->close();

    if ($subCampusId === null || (int)$checkRow['campus'] !== $subCampusId) {
        $response['success'] = false;
        $response['message'] = 'You are not authorized to edit users outside your campus.';
        echo json_encode($response);
        $db->close();
        exit;
    }
    // $campusId stays null -> COALESCE keeps the existing campus untouched.
} elseif ($adminRole === 'super') {
    $campusId = (isset($_POST['campusId']) && $_POST['campusId'] !== '') ? (int)$_POST['campusId'] : null;
}

// Note: email and isVerified are intentionally NOT read from $_POST anywhere in this file.

$firstName    = isset($_POST['firstName'])    ? trim($_POST['firstName'])    : null;
$middleName   = isset($_POST['middleName'])   ? trim($_POST['middleName'])   : null;
$lastName     = isset($_POST['lastName'])     ? trim($_POST['lastName'])     : null;
$address      = isset($_POST['address'])      ? trim($_POST['address'])      : null;
$mobileNum    = isset($_POST['mobileNum'])    ? trim($_POST['mobileNum'])    : null;
$emergencyNum = isset($_POST['emergencyNum']) ? trim($_POST['emergencyNum']) : null;
$departmentId = (isset($_POST['departmentId']) && $_POST['departmentId'] !== '') ? (int)$_POST['departmentId'] : null;
$courseId     = (isset($_POST['courseId'])     && $_POST['courseId'] !== '')     ? (int)$_POST['courseId']     : null;
$userTypeId   = (isset($_POST['userTypeId'])   && $_POST['userTypeId'] !== '')   ? (int)$_POST['userTypeId']   : null;
$birthDay     = isset($_POST['birthDay'])     ? trim($_POST['birthDay'])     : null;
$gender       = (isset($_POST['gender']) && $_POST['gender'] !== '') ? (int)$_POST['gender'] : null;
$nstpId       = (isset($_POST['nstpId'])       && $_POST['nstpId'] !== '')       ? (int)$_POST['nstpId']       : null;
$srCode       = isset($_POST['srCode'])       ? trim($_POST['srCode'])       : null;
$yrSection    = isset($_POST['yrSection'])    ? trim($_POST['yrSection'])    : null;

if ($mobileNum !== null && $mobileNum !== '') {
    $mobileCheckStmt = $db->prepare("SELECT userId FROM tbl_user WHERE mobileNum = ? AND userId != ? LIMIT 1");
    $mobileCheckStmt->bind_param("si", $mobileNum, $userId);
    $mobileCheckStmt->execute();
    $mobileCheckStmt->store_result();
    if ($mobileCheckStmt->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'Mobile number is already used by another account.';
        $mobileCheckStmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }
    $mobileCheckStmt->close();
}

// Optional photo replacement
$newPhotoFileName = null;
$destPath = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['photo']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        $response['success'] = false;
        $response['message'] = 'Invalid image type. Only JPEG, PNG, WEBP allowed.';
        echo json_encode($response);
        $db->close();
        exit;
    }

    $maxSize = 20 * 1024 * 1024;
    if ($_FILES['photo']['size'] > $maxSize) {
        $response['success'] = false;
        $response['message'] = 'Image size must not exceed 20MB.';
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
    $newPhotoFileName = $userId . '_' . $timestamp . '.' . strtolower($ext);

    // NOTE: adjust this if this file doesn't sit where adminlogin.php sits relative to CivicallAPI.
    $uploadDir = __DIR__ . '/../../CiviCallAPI/profileImage/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $response['success'] = false;
            $response['message'] = 'Failed to create upload folder.';
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

    $destPath = $uploadDir . $newPhotoFileName;
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destPath)) {
        $response['success'] = false;
        $response['message'] = 'Failed to save image. Check write permission.';
        echo json_encode($response);
        $db->close();
        exit;
    }
}

$updateStmt = $db->prepare("
    UPDATE tbl_user SET
        firstName    = COALESCE(?, firstName),
        middleName   = COALESCE(?, middleName),
        lastName     = COALESCE(?, lastName),
        address      = COALESCE(?, address),
        mobileNum    = COALESCE(?, mobileNum),
        emergencyNum = COALESCE(?, emergencyNum),
        campus       = COALESCE(?, campus),
        department   = COALESCE(?, department),
        course       = COALESCE(?, course),
        userType     = COALESCE(?, userType),
        birthDay     = COALESCE(?, birthDay),
        gender       = COALESCE(?, gender),
        nstp         = COALESCE(?, nstp),
        srCode       = COALESCE(?, srCode),
        yrSection    = COALESCE(?, yrSection),
        photo_url    = COALESCE(?, photo_url)
    WHERE userId = ?
");

$updateStmt->bind_param(
    "ssssssiiiisiisssi",
    $firstName, $middleName, $lastName, $address, $mobileNum, $emergencyNum,
    $campusId, $departmentId, $courseId, $userTypeId,
    $birthDay, $gender, $nstpId, $srCode, $yrSection,
    $newPhotoFileName, $userId
);

if ($updateStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'User updated successfully.';
    if ($newPhotoFileName) {
        $response['photo_url'] = $newPhotoFileName;
    }
} else {
    if ($destPath && file_exists($destPath)) {
        unlink($destPath);
    }
    $response['success'] = false;
    $response['message'] = 'Failed to update user.';
}

$updateStmt->close();
echo json_encode($response);
$db->close();
?>