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

$stmt = $db->prepare("SELECT userId, lastUsed, isActive FROM tbl_userdevice WHERE authToken = ?");
$stmt->bind_param("s", $authToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Invalid or expired token.';
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$row = $result->fetch_assoc();
$userId = $row['userId'];
$lastUsed = $row['lastUsed'];
$isActive = $row['isActive'];
$stmt->close();

// Check for session expiry (30 days)
$expiryDate = date('Y-m-d H:i:s', strtotime('-30 days'));
if ($isActive == 1 && strtotime($lastUsed) < strtotime($expiryDate)) {
    // Deactivate the session and clear the token
    $deactivateStmt = $db->prepare("UPDATE tbl_userdevice SET isActive = 0, authToken = NULL WHERE authToken = ?");
    $deactivateStmt->bind_param("s", $authToken);
    $deactivateStmt->execute();
    $deactivateStmt->close();

    $response['success'] = false;
    $response['message'] = 'Session expired. Please login again.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$userStmt = $db->prepare("
    SELECT 
        u.userId, u.firstName, u.middleName, u.lastName, u.email,
        u.mobileNum, u.emergencyNum, u.address,
        u.campus AS campusId, c.campusName,
        u.department AS departmentId, d.departmentName,
        u.course AS courseId, co.courseName,
        u.userCategory, u.birthDay, u.gender,
        u.nstp AS nstpId, n.nstpType,
        u.srCode, u.yrSection,
        u.photo_url, u.isVerified,
        u.signup_type, u.created_at
    FROM tbl_user u
    LEFT JOIN tbl_campus c ON c.campusId = u.campus
    LEFT JOIN tbl_department d ON d.departmentId = u.department
    LEFT JOIN tbl_course co ON co.courseId = u.course
    LEFT JOIN tbl_nstp n ON n.nstpId = u.nstp
    WHERE u.userId = ?
");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    $user['isVerified'] = (int)$user['isVerified'];
    $user['campusId'] = (int)$user['campusId'];
    $user['departmentId'] = isset($user['departmentId']) ? (int)$user['departmentId'] : null;
    $user['courseId'] = isset($user['courseId']) ? (int)$user['courseId'] : null;
    $user['nstpId'] = isset($user['nstpId']) ? (int)$user['nstpId'] : null;
    $user['userCategory'] = isset($user['userCategory']) ? (int)$user['userCategory'] : null;
    $user['gender'] = isset($user['gender']) ? (int)$user['gender'] : null;
    $user['signup_type'] = (int)$user['signup_type'];
    $response['success'] = true;
    $response['user'] = $user;
} else {
    $response['success'] = false;
    $response['message'] = 'User not found.';
}

$userStmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>