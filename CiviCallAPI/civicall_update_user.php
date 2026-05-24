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

$firstName    = isset($_POST['firstName'])    ? trim($_POST['firstName'])    : null;
$middleName   = isset($_POST['middleName'])   ? trim($_POST['middleName'])   : null;
$lastName     = isset($_POST['lastName'])     ? trim($_POST['lastName'])     : null;
$address      = isset($_POST['address'])      ? trim($_POST['address'])      : null;
$mobileNum    = isset($_POST['mobileNum'])    ? trim($_POST['mobileNum'])    : null;
$emergencyNum = isset($_POST['emergencyNum']) ? trim($_POST['emergencyNum']) : null;
$campusId     = isset($_POST['campusId'])     ? (int)$_POST['campusId']      : null;
$departmentId = isset($_POST['departmentId']) ? (int)$_POST['departmentId'] : null;
$courseId     = isset($_POST['courseId'])     ? (int)$_POST['courseId']      : null;
$userCategory = isset($_POST['userCategory']) ? (int)$_POST['userCategory'] : null;
$birthDay     = isset($_POST['birthDay'])     ? trim($_POST['birthDay'])     : null;
$gender       = isset($_POST['gender'])       ? (int)$_POST['gender']        : null;
$nstpId       = isset($_POST['nstpId'])       ? (int)$_POST['nstpId']        : null;
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
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    $mobileCheckStmt->close();
}

$updateStmt = $db->prepare("
    UPDATE tbl_user SET
        firstName = COALESCE(?, firstName),
        middleName = COALESCE(?, middleName),
        lastName = COALESCE(?, lastName),
        address = COALESCE(?, address),
        mobileNum = COALESCE(?, mobileNum),
        emergencyNum = COALESCE(?, emergencyNum),
        campus = COALESCE(?, campus),
        department = COALESCE(?, department),
        course = COALESCE(?, course),
        userCategory = COALESCE(?, userCategory),
        birthDay = COALESCE(?, birthDay),
        gender = COALESCE(?, gender),
        nstp = COALESCE(?, nstp),
        srCode = COALESCE(?, srCode),
        yrSection = COALESCE(?, yrSection)
    WHERE userId = ?
");

$updateStmt->bind_param(
    "ssssssiiiisiissi",
    $firstName, $middleName, $lastName, $address, $mobileNum, $emergencyNum,
    $campusId, $departmentId, $courseId, $userCategory,
    $birthDay, $gender, $nstpId, $srCode, $yrSection,
    $userId
);

if ($updateStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Profile updated successfully.';
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to update profile.';
}

$updateStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>