<?php
require_once '../kurt_dbCon.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$response = array();

$emailOrPhone = isset($_POST['emailOrPhone']) ? trim($_POST['emailOrPhone']) : '';

if ($emailOrPhone === '') {
    $response['success'] = false;
    $response['message'] = 'Email or mobile number is required.';
    echo json_encode($response);
    $db->close();
    exit;
}

$stmt = $db->prepare("SELECT userId, email, signup_type FROM tbl_user WHERE (email = ? OR mobileNum = ?) AND signup_type = 0 LIMIT 1");
$stmt->bind_param("ss", $emailOrPhone, $emailOrPhone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['success'] = false;
    $response['message'] = 'No manual account found with that email or mobile number.';
    $stmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}

$user = $result->fetch_assoc();
$userEmail = $user['email'];
$stmt->close();

$resetToken = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', strtotime('+20 minutes'));

$insertStmt = $db->prepare("INSERT INTO tbl_passwordreset (email, resetToken, expiresAt, createdAt) VALUES (?, ?, ?, NOW())");
$insertStmt->bind_param("sss", $userEmail, $resetToken, $expiresAt);
if (!$insertStmt->execute()) {
    $response['success'] = false;
    $response['message'] = 'Database error: could not create reset request.';
    $insertStmt->close();
    echo json_encode($response);
    $db->close();
    exit;
}
$insertStmt->close();

$resetLink = "http://192.168.1.56/CiviCall/CiviCallAPI/civicall_resetPortal.php?token=" . $resetToken;
$iconUrl = "http://192.168.1.56/CiviCall/CiviCallAPI/images/icon.png";

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
    $mail->addAddress($userEmail);
    $mail->isHTML(true);
    $mail->Subject = 'Reset Your CiviCall Password';
$mail->Body    = "
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

            <div style='font-size:32px; font-weight:bold; color:#D53A47; margin-bottom:20px;'>
                CiviCall App
            </div>

            <h2>Password Reset Request</h2>

            <p>We received a request to reset your CiviCall account password.</p>

            <p>
                Click the button below to set a new password.
                This link is valid for <strong>20 minutes</strong>.
            </p>

            <a href='{$resetLink}' class='btn' style='color:#ffffff;'>
                Reset Password
            </a>

            <p>If you did not request this, please ignore this email.</p>

            <div class='footer'>
                CiviCall – Connecting Communities
            </div>
        </div>
    </body>
    </html>
";
    $mail->AltBody = "Reset your CiviCall password by visiting this link: $resetLink. Valid for 20 minutes.";

    $mail->send();
    $response['success'] = true;
    $response['message'] = 'Password reset link has been sent to your email address.';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
}

echo json_encode($response);
$db->close();
?>