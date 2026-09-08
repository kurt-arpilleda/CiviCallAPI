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

$campusId = null;
if ($adminRole === 'sub') {
    $stmt = $db->prepare("SELECT campusId FROM tbl_subadmin WHERE subId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $campusId = (int)$row['campusId'];
    }
    $stmt->close();

    if ($campusId === null) {
        $response['success'] = false;
        $response['message'] = 'Unable to determine your campus.';
        echo json_encode($response);
        $db->close();
        exit;
    }
} elseif ($adminRole === 'super') {
    $campusId = (isset($_POST['campusId']) && $_POST['campusId'] !== '') ? (int)$_POST['campusId'] : 0;
    if ($campusId <= 0) {
        $response['success'] = false;
        $response['message'] = 'Please select a campus.';
        echo json_encode($response);
        $db->close();
        exit;
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    $db->close();
    exit;
}

$rawEmails = isset($_POST['emails']) && is_array($_POST['emails']) ? $_POST['emails'] : [];
$emails = [];
foreach ($rawEmails as $rawEmail) {
    $cleanEmail = trim($rawEmail);
    if ($cleanEmail === '') {
        continue;
    }
    $emails[] = $cleanEmail;
}
$emails = array_values(array_unique($emails));

if (empty($emails)) {
    $response['success'] = false;
    $response['message'] = 'At least one email is required.';
    echo json_encode($response);
    $db->close();
    exit;
}

$defaultPassword = 'civicall@2026';
$hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

$results = [];
$successCount = 0;

$insertStmt = $db->prepare(
    "INSERT INTO tbl_user
     (firstName, middleName, lastName, address, mobileNum, campus, userType, birthDay, gender, email, password, signup_type, emailVerified, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
);

foreach ($emails as $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $results[] = ['email' => $email, 'success' => false, 'message' => 'Invalid email format.'];
        continue;
    }

    $checkStmt = $db->prepare("SELECT userId FROM tbl_user WHERE email = ? LIMIT 1");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    $alreadyExists = $checkStmt->num_rows > 0;
    $checkStmt->close();

    if ($alreadyExists) {
        $results[] = ['email' => $email, 'success' => false, 'message' => 'Email is already registered.'];
        continue;
    }

    $firstName  = '';
    $middleName = '';
    $lastName   = '';
    $address    = '';
    $mobileNum  = '';
    $userType   = 0;
    $birthDay   = '';
    $gender     = 2;
    $signupType = 0;
    $emailVerified = 1;

    $insertStmt->bind_param(
        "sssssiisissii",
        $firstName,
        $middleName,
        $lastName,
        $address,
        $mobileNum,
        $campusId,
        $userType,
        $birthDay,
        $gender,
        $email,
        $hashedPassword,
        $signupType,
        $emailVerified
    );

    if ($insertStmt->execute()) {
        $results[] = ['email' => $email, 'success' => true, 'message' => 'Registered successfully.'];
        $successCount++;
    } else {
        $results[] = ['email' => $email, 'success' => false, 'message' => 'Failed to register.'];
    }
}

$insertStmt->close();

$response['success'] = $successCount > 0;
$response['message'] = $successCount . ' of ' . count($emails) . ' user(s) registered successfully.';
$response['results'] = $results;

echo json_encode($response);
$db->close();
?>