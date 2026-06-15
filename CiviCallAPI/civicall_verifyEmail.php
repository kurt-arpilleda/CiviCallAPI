<?php
require_once '../kurt_dbCon.php';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$message = '';
$success = false;

if (empty($token)) {
    $message = '<div style="color:#D53A47;background:#ffe6e6;padding:12px;border-radius:8px;">Invalid or missing verification token.</div>';
} else {
    $stmt = $db->prepare("SELECT email, expiresAt FROM tbl_emailverification WHERE verifyToken = ? ORDER BY createdAt DESC LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = '<div style="color:#D53A47;background:#ffe6e6;padding:12px;border-radius:8px;">Invalid verification link. Please register again.</div>';
    } else {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expiresAt = $row['expiresAt'];
        $now = new DateTime();
        $expiry = new DateTime($expiresAt);

        if ($now > $expiry) {
            $deleteStmt = $db->prepare("DELETE FROM tbl_emailverification WHERE verifyToken = ?");
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();
            $deleteStmt->close();
            $message = '<div style="color:#D53A47;background:#ffe6e6;padding:12px;border-radius:8px;">This verification link has expired (24 hours). Please register again.</div>';
        } else {
            $updateStmt = $db->prepare("UPDATE tbl_user SET emailVerified = 1 WHERE email = ? AND signup_type = 0");
            $updateStmt->bind_param("s", $email);
            if ($updateStmt->execute() && $updateStmt->affected_rows > 0) {
                $deleteStmt = $db->prepare("DELETE FROM tbl_emailverification WHERE verifyToken = ?");
                $deleteStmt->bind_param("s", $token);
                $deleteStmt->execute();
                $deleteStmt->close();
                $message = '<div style="color:#2e7d32;background:#e8f5e9;padding:12px;border-radius:8px;">Email verified successfully! You can now sign in to CiviCall.</div>';
                $success = true;
            } else {
                $message = '<div style="color:#D53A47;background:#ffe6e6;padding:12px;border-radius:8px;">Verification failed. Please try again.</div>';
            }
            $updateStmt->close();
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - CiviCall</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Roboto,'Helvetica Neue',sans-serif; background:linear-gradient(145deg,#f8f9fc,#f1f3f7); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px; }
        .card { max-width:480px; width:100%; background:#fff; border-radius:32px; box-shadow:0 20px 35px -12px rgba(0,0,0,0.1); overflow:hidden; }
        .header { background-color:#D53A47; padding:30px 24px 24px; text-align:center; color:white; }
        .header h1 { font-size:28px; font-weight:700; margin-bottom:6px; }
        .header p { font-size:14px; opacity:0.9; }
        .content { padding:32px 28px 40px; }
        .message { margin-bottom:24px; font-size:14px; border-radius:12px; padding:12px; text-align:center; }
        .footer { text-align:center; font-size:13px; color:#6c757d; margin-top:28px; border-top:1px solid #edf2f7; padding-top:24px; }
        a { color:#D53A47; font-weight:600; text-decoration:none; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div style="font-size:32px;font-weight:bold;margin-bottom:12px;">CiviCall</div>
        <h1>Email Verification</h1>
        <p>Account activation</p>
    </div>
    <div class="content">
        <div class="message"><?php echo $message; ?></div>
        <div class="footer">CiviCall – Connecting Communities</div>
    </div>
</div>
</body>
</html>