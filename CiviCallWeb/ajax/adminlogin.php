<?php
session_start();
require_once '../../kurt_dbCon.php';

header('Content-Type: application/json');

$response = [];

$role     = isset($_POST['role'])     ? trim($_POST['role'])     : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? $_POST['password']       : '';

if ($role === '' || $email === '' || $password === '') {
    $response['success'] = false;
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    exit;
}

if ($role === 'super') {
    $stmt = $db->prepare("SELECT supId, name, email, password FROM tbl_superadmin WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        $stmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }

    $admin = $result->fetch_assoc();
    $stmt->close();

    if (!password_verify($password, $admin['password'])) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        echo json_encode($response);
        $db->close();
        exit;
    }

    $_SESSION['admin_id']   = $admin['supId'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email']= $admin['email'];
    $_SESSION['admin_role'] = 'super';

    $response['success']  = true;
    $response['message']  = 'Login successful.';
    $response['redirect'] = '?url=dashboard';

} elseif ($role === 'sub') {
    $stmt = $db->prepare("SELECT subId, email, password FROM tbl_subadmin WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        $stmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }

    $admin = $result->fetch_assoc();
    $stmt->close();

    if (!password_verify($password, $admin['password'])) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        echo json_encode($response);
        $db->close();
        exit;
    }

    $_SESSION['admin_id']   = $admin['subId'];
    $_SESSION['admin_email']= $admin['email'];
    $_SESSION['admin_role'] = 'sub';

    $response['success']  = true;
    $response['message']  = 'Login successful.';
    $response['redirect'] = '?url=dashboard';

} else {
    $response['success'] = false;
    $response['message'] = 'Invalid role specified.';
}

echo json_encode($response);
$db->close();