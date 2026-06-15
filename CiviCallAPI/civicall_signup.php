<?php
require_once '../kurt_dbCon.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
$response = array();

$firstName  = isset($_POST['firstName'])  ? trim($_POST['firstName'])  : '';
$middleName = isset($_POST['middleName']) ? trim($_POST['middleName']) : '';
$lastName   = isset($_POST['lastName'])   ? trim($_POST['lastName'])   : '';
$address    = isset($_POST['address'])    ? trim($_POST['address'])    : '';
$mobileNum  = isset($_POST['mobileNum'])  ? trim($_POST['mobileNum'])  : '';
$campusId   = isset($_POST['campusId'])   ? (int)$_POST['campusId']    : 0;
$userTypeId = isset($_POST['userTypeId']) ? (int)$_POST['userTypeId']  : 0;
$birthDay   = isset($_POST['birthDay'])   ? trim($_POST['birthDay'])   : '';
$gender     = isset($_POST['gender'])     ? (int)$_POST['gender']      : -1;
$email      = isset($_POST['email'])      ? trim($_POST['email'])      : '';
$password   = isset($_POST['password'])   ? $_POST['password']         : '';

if (
    $firstName === '' || $lastName === '' || $address === '' ||
    $mobileNum === '' || $campusId === 0  || $userTypeId === 0 ||
    $birthDay  === '' || $gender   === -1 || $email === '' || $password === ''
) {
    $response['success'] = false;
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    $db->close();
    exit;
}

$mobileCheckStmt = $db->prepare("SELECT userId, emailVerified FROM tbl_user WHERE mobileNum = ? LIMIT 1");
$mobileCheckStmt->bind_param("s", $mobileNum);
$mobileCheckStmt->execute();
$mobileCheckStmt->store_result();
if ($mobileCheckStmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Mobile number is already registered.';
    $mobileCheckStmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}
$mobileCheckStmt->close();

$emailCheckStmt = $db->prepare("SELECT userId, emailVerified FROM tbl_user WHERE email = ? AND signup_type = 0 LIMIT 1");
$emailCheckStmt->bind_param("s", $email);
$emailCheckStmt->execute();
$emailResult = $emailCheckStmt->get_result();

if ($emailResult->num_rows > 0) {
    $existingUser = $emailResult->fetch_assoc();
    $emailCheckStmt->close();

    if ($existingUser['emailVerified'] == 1) {
        $response['success'] = false;
        $response['message'] = 'Email is already registered with a manual account.';
        echo json_encode($response);
        $db->close();
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $updateStmt = $db->prepare(
        "UPDATE tbl_user SET firstName=?, middleName=?, lastName=?, address=?, mobileNum=?, campus=?, userType=?, birthDay=?, gender=?, password=? WHERE userId=?"
    );
    $userId = $existingUser['userId'];
    $updateStmt->bind_param("sssssiisisi",
        $firstName, $middleName, $lastName, $address, $mobileNum,
        $campusId, $userTypeId, $birthDay, $gender, $hashedPassword, $userId
    );
    if (!$updateStmt->execute()) {
        $response['success'] = false;
        $response['message'] = 'Update failed. Please try again.';
        $updateStmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }
    $updateStmt->close();
} else {
    $emailCheckStmt->close();
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare(
        "INSERT INTO tbl_user 
         (firstName, middleName, lastName, address, mobileNum, campus, userType, birthDay, gender, email, password, emailVerified, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())"
    );
    $stmt->bind_param("sssssiisiss",
        $firstName, $middleName, $lastName, $address, $mobileNum,
        $campusId, $userTypeId, $birthDay, $gender, $email, $hashedPassword
    );
    if (!$stmt->execute()) {
        $response['success'] = false;
        $response['message'] = 'Registration failed. Please try again.';
        $stmt->close();
        echo json_encode($response);
        $db->close();
        exit;
    }
    $stmt->close();
}

$db->query("DELETE FROM tbl_emailverification WHERE email = '$email'");

$verifyToken = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

$tokenStmt = $db->prepare("INSERT INTO tbl_emailverification (email, verifyToken, expiresAt, createdAt) VALUES (?, ?, ?, NOW())");
$tokenStmt->bind_param("sss", $email, $verifyToken, $expiresAt);
if (!$tokenStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Failed to generate verification token.';
    $tokenStmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}
$tokenStmt->close();

$verifyLink = "http://192.168.1.56/CiviCall/CiviCallAPI/civicall_verifyEmail.php?token=" . $verifyToken;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'appcivicall@gmail.com';
    $mail->Password   = 'gvye nrmd euqb wjnr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('appcivicall@gmail.com', 'CiviCall Support');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your CiviCall Account';
    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 500px; margin: auto; background: #ffffff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; }
            h2 { color: #D53A47; }
            .btn { display: inline-block; background-color: #D53A47; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
            .footer { font-size: 12px; color: #777; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div style='font-size:32px; font-weight:bold; color:#D53A47; margin-bottom:20px;'>CiviCall App</div>
            <h2>Verify Your Email</h2>
            <p>Thank you for registering! Please verify your email address to activate your account.</p>
            <p>This link is valid for <strong>24 hours</strong>.</p>
            <a href='{$verifyLink}' class='btn' style='color:#ffffff;'>Verify Email</a>
            <p>If you did not create an account, please ignore this email.</p>
            <div class='footer'>CiviCall – Connecting Communities</div>
        </div>
    </body>
    </html>";
    $mail->AltBody = "Verify your CiviCall account by visiting: $verifyLink. Valid for 24 hours.";
    $mail->send();

    $response['success'] = true;
    $response['message'] = 'Registration successful. Please check your email to verify your account.';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Registration saved but failed to send verification email: ' . $mail->ErrorInfo;
}

echo json_encode($response);
$db->close();
?>