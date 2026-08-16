<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');

$response = [];

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'super') {
    $response['success'] = false;
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    exit;
}

$name     = isset($_POST['name'])     ? trim($_POST['name'])     : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? $_POST['password']       : '';
$campusId = isset($_POST['campusId']) ? trim($_POST['campusId']) : '';

if ($name === '' || $email === '' || $password === '' || $campusId === '') {
    $response['success'] = false;
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    exit;
}

$checkStmt = $db->prepare("SELECT subId FROM tbl_subadmin WHERE email = ? LIMIT 1");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Email is already registered.';
    $checkStmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}
$checkStmt->close();

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $db->prepare("INSERT INTO tbl_subadmin (name, email, password, campusId, createdAt, status, isActive) VALUES (?, ?, ?, ?, NOW(), 1, 0)");
$stmt->bind_param("sssi", $name, $email, $hashedPassword, $campusId);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Sub Admin account created successfully.';
} else {
    $response['success'] = false;
    $response['message'] = 'Registration failed. Please try again.';
}

$stmt->close();
echo json_encode($response);
$db->close();