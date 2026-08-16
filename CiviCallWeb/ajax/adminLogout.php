<?php
session_start();
require_once '../../kurt_dbCon.php';
require_once 'adminLog.php';

header('Content-Type: application/json');

$response = [];

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    $response['success'] = false;
    $response['message'] = 'Not logged in.';
    echo json_encode($response);
    exit;
}

$adminId = $_SESSION['admin_id'];
$role    = $_SESSION['admin_role'];

if ($role === 'super') {
    $stmt = $db->prepare("UPDATE tbl_superadmin SET isActive = 0 WHERE supId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->close();
    logAdminAction($db, 0, 1);
} elseif ($role === 'sub') {
    $stmt = $db->prepare("UPDATE tbl_subadmin SET isActive = 0 WHERE subId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->close();
    logAdminAction($db, 1, 1);
}

$_SESSION = [];
session_destroy();

$response['success']  = true;
$response['message']  = 'Logged out successfully.';
$response['redirect'] = 'index.php?url=login';

echo json_encode($response);
$db->close();