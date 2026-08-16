<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');

$response = [];

$code = isset($_POST['code']) ? trim($_POST['code']) : '';

if ($code === '') {
    $response['success'] = false;
    $response['message'] = 'Access code is required.';
    echo json_encode($response);
    exit;
}

$stmt = $db->prepare("SELECT passCode FROM tbl_superadmincode LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'Access code not configured.';
    $stmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

if ($code === $row['passCode']) {
    $response['success'] = true;
    $response['message'] = 'Access code verified.';
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid access code.';
}

echo json_encode($response);
$db->close();