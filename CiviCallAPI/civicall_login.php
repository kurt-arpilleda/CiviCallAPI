<?php
require_once '../kurt_dbCon.php';

$response = array();

$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? $_POST['password']       : '';
$deviceId = isset($_POST['deviceId']) ? trim($_POST['deviceId']) : '';
$isGoogleLogin = isset($_POST['isGoogleLogin']) ? (int)$_POST['isGoogleLogin'] : 0;
$googleId = isset($_POST['googleId']) ? trim($_POST['googleId']) : '';
$firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
$photoUrl = isset($_POST['photoUrl']) ? trim($_POST['photoUrl']) : '';
$birthDay = isset($_POST['birthDay']) ? trim($_POST['birthDay']) : '';
$gender = isset($_POST['gender']) ? (int)$_POST['gender'] : 2;
$mobileNum = isset($_POST['mobileNum']) ? trim($_POST['mobileNum']) : '';

if ($deviceId === '') {
    $response['success'] = false;
    $response['message'] = 'Device ID is required.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($isGoogleLogin == 1) {
    if ($email === '' || $googleId === '') {
        $response['success'] = false;
        $response['message'] = 'Email and Google ID are required for Google login.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    
    $stmt = $db->prepare("SELECT userId, firstName, lastName, email, signup_type FROM tbl_user WHERE email = ? AND signup_type = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $middleName = '';
        $address = '';
        $campusId = 1;
        $userCategory = 0;
        $createdAt = date('Y-m-d H:i:s');
        
        if ($gender < 0 || $gender > 1) {
            $gender = 2;
        }
        
        $insertStmt = $db->prepare("INSERT INTO tbl_user (firstName, middleName, lastName, address, mobileNum, campus, userCategory, birthDay, gender, email, password, signup_type, google_id, photo_url, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $emptyPassword = '';
        $signupType = 1;
        $insertStmt->bind_param("sssssiisissiiss", $firstName, $middleName, $lastName, $address, $mobileNum, $campusId, $userCategory, $birthDay, $gender, $email, $emptyPassword, $signupType, $googleId, $photoUrl, $createdAt);
        
        if (!$insertStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to create user account.';
            $insertStmt->close();
            $stmt->close();
            header('Content-Type: application/json');
            echo json_encode($response);
            $db->close();
            exit;
        }
        $userId = $insertStmt->insert_id;
        $insertStmt->close();
    } else {
        $user = $result->fetch_assoc();
        $userId = $user['userId'];
        
        if (empty($user['google_id']) || $user['google_id'] !== $googleId) {
            $updateStmt = $db->prepare("UPDATE tbl_user SET google_id = ? WHERE userId = ?");
            $updateStmt->bind_param("si", $googleId, $userId);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }
    $stmt->close();
    
    $authToken = bin2hex(random_bytes(32));
    
    $checkDeviceStmt = $db->prepare("SELECT deviceId FROM tbl_userdevice WHERE userId = ? AND deviceId = ?");
    $checkDeviceStmt->bind_param("is", $userId, $deviceId);
    $checkDeviceStmt->execute();
    $checkDeviceStmt->store_result();
    
    if ($checkDeviceStmt->num_rows > 0) {
        $updateDeviceStmt = $db->prepare("UPDATE tbl_userdevice SET lastUsed = NOW(), isActive = 1, authToken = ? WHERE userId = ? AND deviceId = ?");
        $updateDeviceStmt->bind_param("sis", $authToken, $userId, $deviceId);
        $updateDeviceStmt->execute();
        $updateDeviceStmt->close();
    } else {
        $deactivateStmt = $db->prepare("UPDATE tbl_userdevice SET isActive = 0 WHERE userId = ?");
        $deactivateStmt->bind_param("i", $userId);
        $deactivateStmt->execute();
        $deactivateStmt->close();
        
        $insertDeviceStmt = $db->prepare("INSERT INTO tbl_userdevice (userId, deviceId, lastUsed, isActive, authToken) VALUES (?, ?, NOW(), 1, ?)");
        $insertDeviceStmt->bind_param("iss", $userId, $deviceId, $authToken);
        if (!$insertDeviceStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to register device.';
            $insertDeviceStmt->close();
            $checkDeviceStmt->close();
            header('Content-Type: application/json');
            echo json_encode($response);
            $db->close();
            exit;
        }
        $insertDeviceStmt->close();
    }
    $checkDeviceStmt->close();
    
    $response['success'] = true;
    $response['message'] = 'Google login successful.';
    $response['token'] = $authToken;
    $response['userId'] = $userId;
    
} else {
    if ($email === '' || $password === '') {
        $response['success'] = false;
        $response['message'] = 'Email and password are required.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    
    $stmt = $db->prepare("SELECT userId, password, signup_type FROM tbl_user WHERE email = ? AND signup_type = 0 LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        $stmt->close();
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    
    $user = $result->fetch_assoc();
    $userId = $user['userId'];
    $hashedPassword = $user['password'];
    
    if (!password_verify($password, $hashedPassword)) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password.';
        $stmt->close();
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }
    $stmt->close();
    
    $authToken = bin2hex(random_bytes(32));
    
    $checkDeviceStmt = $db->prepare("SELECT deviceId FROM tbl_userdevice WHERE userId = ? AND deviceId = ?");
    $checkDeviceStmt->bind_param("is", $userId, $deviceId);
    $checkDeviceStmt->execute();
    $checkDeviceStmt->store_result();
    
    if ($checkDeviceStmt->num_rows > 0) {
        $updateDeviceStmt = $db->prepare("UPDATE tbl_userdevice SET lastUsed = NOW(), isActive = 1, authToken = ? WHERE userId = ? AND deviceId = ?");
        $updateDeviceStmt->bind_param("sis", $authToken, $userId, $deviceId);
        $updateDeviceStmt->execute();
        $updateDeviceStmt->close();
    } else {
        $deactivateStmt = $db->prepare("UPDATE tbl_userdevice SET isActive = 0 WHERE userId = ?");
        $deactivateStmt->bind_param("i", $userId);
        $deactivateStmt->execute();
        $deactivateStmt->close();
        
        $insertDeviceStmt = $db->prepare("INSERT INTO tbl_userdevice (userId, deviceId, lastUsed, isActive, authToken) VALUES (?, ?, NOW(), 1, ?)");
        $insertDeviceStmt->bind_param("iss", $userId, $deviceId, $authToken);
        if (!$insertDeviceStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to register device.';
            $insertDeviceStmt->close();
            $checkDeviceStmt->close();
            header('Content-Type: application/json');
            echo json_encode($response);
            $db->close();
            exit;
        }
        $insertDeviceStmt->close();
    }
    $checkDeviceStmt->close();
    
    $response['success'] = true;
    $response['message'] = 'Login successful.';
    $response['token'] = $authToken;
    $response['userId'] = $userId;
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>