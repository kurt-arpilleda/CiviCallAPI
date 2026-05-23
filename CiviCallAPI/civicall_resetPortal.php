<?php
require_once '../kurt_dbCon.php';

$message = '';
$showForm = true;
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($token)) {
    $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">Invalid or missing reset token.</div>';
    $showForm = false;
} else {
    $stmt = $db->prepare("SELECT email, expiresAt FROM tbl_passwordreset WHERE resetToken = ? ORDER BY createdAt DESC LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">Invalid reset token. Please request a new password reset.</div>';
        $showForm = false;
    } else {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expiresAt = $row['expiresAt'];
        $now = new DateTime();
        $expiry = new DateTime($expiresAt);
        if ($now > $expiry) {
            $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">This reset link has expired (20 minutes). Please request a new one.</div>';
            $showForm = false;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
                $newPassword = $_POST['new_password'];
                $confirm = $_POST['confirm_password'];
                if (strlen($newPassword) < 8) {
                    $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">Password must be at least 8 characters.</div>';
                } elseif ($newPassword !== $confirm) {
                    $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">Passwords do not match.</div>';
                } else {
                    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
                    $updateStmt = $db->prepare("UPDATE tbl_user SET password = ? WHERE email = ? AND signup_type = 0");
                    $updateStmt->bind_param("ss", $hashed, $email);
                    if ($updateStmt->execute()) {
                        $deleteStmt = $db->prepare("DELETE FROM tbl_passwordreset WHERE resetToken = ?");
                        $deleteStmt->bind_param("s", $token);
                        $deleteStmt->execute();
                        $deleteStmt->close();
                        $message = '<div style="color: #2e7d32; background:#e8f5e9; padding:12px; border-radius:8px;">Password reset successful! You can now close this page and sign in to the app.</div>';
                        $showForm = false;
                    } else {
                        $message = '<div style="color: #D53A47; background:#ffe6e6; padding:12px; border-radius:8px;">Database error. Please try again.</div>';
                    }
                    $updateStmt->close();
                }
            }
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Reset Password - CiviCall</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(145deg, #f8f9fc 0%, #f1f3f7 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            max-width: 480px;
            width: 100%;
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .header {
            background-color: #D53A47;
            padding: 30px 24px 24px;
            text-align: center;
            color: white;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
  .logo-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    margin: 0 auto 12px auto;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
}
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            padding: 32px 28px 40px;
        }
        .form-group {
            margin-bottom: 22px;
        }
        label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333333;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            border: 1.5px solid #e2e4e8;
            border-radius: 16px;
            transition: 0.2s;
            background-color: #ffffff;
            outline: none;
        }
        input:focus {
            border-color: #D53A47;
            box-shadow: 0 0 0 3px rgba(213,58,71,0.1);
        }
        button {
            width: 100%;
            background-color: #D53A47;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }
        button:hover {
            background-color: #b12e3a;
        }
        .message {
            margin-bottom: 24px;
            font-size: 14px;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            margin-top: 28px;
            border-top: 1px solid #edf2f7;
            padding-top: 24px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div class="logo-icon">
            <img src="/CiviCall/CiviCallAPI/images/icon.png" alt="CiviCall">
        </div>
        <h1>CiviCall</h1>
        <p>Reset your password</p>
    </div>
    <div class="content">
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($showForm): ?>
            <form method="POST">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required placeholder="••••••••" minlength="8">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="••••••••">
                </div>
                <button type="submit">Change Password</button>
            </form>
        <?php else: ?>
            <div style="text-align: center; margin-top: 12px;">
                <a href="https://civicall.app" style="color: #D53A47; font-weight: 600; text-decoration: none;">← Back to CiviCall App</a>
            </div>
        <?php endif; ?>
        <div class="footer">
            Secure password reset — link expires in 20 minutes
        </div>
    </div>
</div>
</body>
</html>