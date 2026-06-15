<?php
require_once '../kurt_dbCon.php';

$response = array();

$email         = isset($_POST['email'])         ? trim($_POST['email'])         : '';
$password      = isset($_POST['password'])      ? $_POST['password']            : '';
$deviceId      = isset($_POST['deviceId'])      ? trim($_POST['deviceId'])      : '';
$isGoogleLogin = isset($_POST['isGoogleLogin']) ? (int)$_POST['isGoogleLogin']  : 0;
$googleId      = isset($_POST['googleId'])      ? trim($_POST['googleId'])      : '';
$firstName     = isset($_POST['firstName'])     ? trim($_POST['firstName'])     : '';
$lastName      = isset($_POST['lastName'])      ? trim($_POST['lastName'])      : '';
$photoUrl      = isset($_POST['photoUrl'])      ? trim($_POST['photoUrl'])      : '';
$birthDay      = isset($_POST['birthDay'])      ? trim($_POST['birthDay'])      : '';
$gender        = isset($_POST['gender'])        ? (int)$_POST['gender']         : 2;
$mobileNum     = isset($_POST['mobileNum'])     ? trim($_POST['mobileNum'])     : '';
$fcmToken      = isset($_POST['fcmToken'])      ? trim($_POST['fcmToken'])      : '';

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

    $stmt = $db->prepare("SELECT userId, firstName, lastName, email, google_id FROM tbl_user WHERE email = ? AND signup_type = 1 LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $middleName  = '';
        $address     = '';
        $campusId    = 0;
        $userType    = 0;
        $signupType  = 1;
        $emptyPass   = '';

        if ($gender < 0 || $gender > 1) {
            $gender = 2;
        }

        $insertStmt = $db->prepare(
            "INSERT INTO tbl_user 
             (firstName, middleName, lastName, address, mobileNum, campus, userType, birthDay, gender, email, password, signup_type, google_id, photo_url, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        $insertStmt->bind_param(
            "sssssiisississ",
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
            $emptyPass,
            $signupType,
            $googleId,
            $photoUrl
        );

        if (!$insertStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to create user account: ' . $insertStmt->error;
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
        $user   = $result->fetch_assoc();
        $userId = $user['userId'];

        if (empty($user['google_id']) || $user['google_id'] !== $googleId) {
            $updateGoogleStmt = $db->prepare("UPDATE tbl_user SET google_id = ? WHERE userId = ?");
            $updateGoogleStmt->bind_param("si", $googleId, $userId);
            $updateGoogleStmt->execute();
            $updateGoogleStmt->close();
        }
    }
    $stmt->close();

    $authToken = bin2hex(random_bytes(32));

    $checkDeviceStmt = $db->prepare("SELECT deviceId FROM tbl_userdevice WHERE userId = ? AND deviceId = ?");
    $checkDeviceStmt->bind_param("is", $userId, $deviceId);
    $checkDeviceStmt->execute();
    $checkDeviceStmt->store_result();

    if ($checkDeviceStmt->num_rows > 0) {
        $updateDeviceStmt = $db->prepare("UPDATE tbl_userdevice SET lastUsed = NOW(), isActive = 1, authToken = ?, fcmToken = ? WHERE userId = ? AND deviceId = ?");
        $updateDeviceStmt->bind_param("ssis", $authToken, $fcmToken, $userId, $deviceId);
        $updateDeviceStmt->execute();
        $updateDeviceStmt->close();
    } else {
        $insertDeviceStmt = $db->prepare("INSERT INTO tbl_userdevice (userId, deviceId, lastUsed, isActive, authToken, fcmToken) VALUES (?, ?, NOW(), 1, ?, ?)");
        $insertDeviceStmt->bind_param("isss", $userId, $deviceId, $authToken, $fcmToken);
        if (!$insertDeviceStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to register device: ' . $insertDeviceStmt->error;
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
    $response['token']   = $authToken;
    $response['userId']  = $userId;

} else {
    if ($email === '' || $password === '') {
        $response['success'] = false;
        $response['message'] = 'Email and password are required.';
        header('Content-Type: application/json');
        echo json_encode($response);
        $db->close();
        exit;
    }

   $stmt = $db->prepare("SELECT userId, password, emailVerified FROM tbl_user WHERE email = ? AND signup_type = 0 LIMIT 1");
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

$user           = $result->fetch_assoc();
$userId         = $user['userId'];
$hashedPassword = $user['password'];
$emailVerified  = (int)$user['emailVerified'];
$stmt->close();

if (!password_verify($password, $hashedPassword)) {
    $response['success'] = false;
    $response['message'] = 'Invalid email or password.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}

if ($emailVerified === 0) {
    $response['success'] = false;
    $response['message'] = 'Please verify your email address before logging in. Check your inbox for the verification link.';
    header('Content-Type: application/json');
    echo json_encode($response);
    $db->close();
    exit;
}
    $authToken = bin2hex(random_bytes(32));

    $checkDeviceStmt = $db->prepare("SELECT deviceId FROM tbl_userdevice WHERE userId = ? AND deviceId = ?");
    $checkDeviceStmt->bind_param("is", $userId, $deviceId);
    $checkDeviceStmt->execute();
    $checkDeviceStmt->store_result();

    if ($checkDeviceStmt->num_rows > 0) {
        $updateDeviceStmt = $db->prepare("UPDATE tbl_userdevice SET lastUsed = NOW(), isActive = 1, authToken = ?, fcmToken = ? WHERE userId = ? AND deviceId = ?");
        $updateDeviceStmt->bind_param("ssis", $authToken, $fcmToken, $userId, $deviceId);
        $updateDeviceStmt->execute();
        $updateDeviceStmt->close();
    } else {
        $insertDeviceStmt = $db->prepare("INSERT INTO tbl_userdevice (userId, deviceId, lastUsed, isActive, authToken, fcmToken) VALUES (?, ?, NOW(), 1, ?, ?)");
        $insertDeviceStmt->bind_param("isss", $userId, $deviceId, $authToken, $fcmToken);
        if (!$insertDeviceStmt->execute()) {
            $response['success'] = false;
            $response['message'] = 'Failed to register device: ' . $insertDeviceStmt->error;
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
    $response['token']   = $authToken;
    $response['userId']  = $userId;
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>