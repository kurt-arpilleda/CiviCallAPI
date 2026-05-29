<?php
require_once '../kurt_dbCon.php';

$response = array();

$firstName    = isset($_POST['firstName'])    ? trim($_POST['firstName'])    : '';
$middleName   = isset($_POST['middleName'])   ? trim($_POST['middleName'])   : '';
$lastName     = isset($_POST['lastName'])     ? trim($_POST['lastName'])     : '';
$address      = isset($_POST['address'])      ? trim($_POST['address'])      : '';
$mobileNum    = isset($_POST['mobileNum'])    ? trim($_POST['mobileNum'])    : '';
$campusId     = isset($_POST['campusId'])     ? (int)$_POST['campusId']      : 0;
$userTypeId   = isset($_POST['userTypeId'])   ? (int)$_POST['userTypeId']    : 0;
$birthDay     = isset($_POST['birthDay'])     ? trim($_POST['birthDay'])     : '';
$gender       = isset($_POST['gender'])       ? (int)$_POST['gender']        : -1;
$email        = isset($_POST['email'])        ? trim($_POST['email'])        : '';
$password     = isset($_POST['password'])     ? $_POST['password']           : '';

if (
    $firstName === '' || $lastName === '' || $address === '' ||
    $mobileNum === '' || $campusId === 0  || $userTypeId === 0 ||
    $birthDay  === '' || $gender   === -1 || $email === '' || $password === ''
) {
    $response['success'] = false;
    $response['message'] = 'All fields are required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

$mobileCheckStmt = $db->prepare("SELECT userId FROM tbl_user WHERE mobileNum = ? LIMIT 1");
$mobileCheckStmt->bind_param("s", $mobileNum);
$mobileCheckStmt->execute();
$mobileCheckStmt->store_result();

if ($mobileCheckStmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Mobile number is already registered.';
    $mobileCheckStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$mobileCheckStmt->close();

$emailCheckStmt = $db->prepare("SELECT userId FROM tbl_user WHERE email = ? AND signup_type = 0 LIMIT 1");
$emailCheckStmt->bind_param("s", $email);
$emailCheckStmt->execute();
$emailCheckStmt->store_result();

if ($emailCheckStmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Email is already registered with a manual account.';
    $emailCheckStmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
$emailCheckStmt->close();

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $db->prepare(
    "INSERT INTO tbl_user 
     (firstName, middleName, lastName, address, mobileNum, campus, userType, birthDay, gender, email, password, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
);

$stmt->bind_param(
    "sssssiisiss",
    $firstName,
    $middleName,
    $lastName,
    $address,
    $mobileNum,
    $campusId,
    $userTypeId,
    $birthDay,
    $gender,
    $email,
    $hashedPassword
);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Registration successful.';
} else {
    $response['success'] = false;
    $response['message'] = 'Registration failed. Please try again.';
}

$stmt->close();
header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>