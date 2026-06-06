<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');

$response = [];

$name     = isset($_POST['name'])     ? trim($_POST['name'])     : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? $_POST['password']       : '';
$regCode  = isset($_POST['regCode'])  ? trim($_POST['regCode'])  : '';

define('SUPER_ADMIN_REG_CODE', 'CIVIC2025');

if ($name === '' || $email === '' || $password === '' || $regCode === '') {
    $response['success'] = false;
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    exit;
}

if ($regCode !== SUPER_ADMIN_REG_CODE) {
    $response['success'] = false;
    $response['message'] = 'Invalid registration code.';
    echo json_encode($response);
    exit;
}

$checkStmt = $db->prepare("SELECT supId FROM tbl_superadmin WHERE email = ? LIMIT 1");
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

$stmt = $db->prepare("INSERT INTO tbl_superadmin (name, email, password, createdAt) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Super Admin account created successfully.';
} else {
    $response['success'] = false;
    $response['message'] = 'Registration failed. Please try again.';
}

$stmt->close();
echo json_encode($response);
$db->close();