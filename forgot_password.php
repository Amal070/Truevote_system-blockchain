<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/includes/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/includes/PHPMailer/SMTP.php";
require_once __DIR__ . "/includes/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'truevote404@gmail.com';
        $mail->Password   = 'qqqqpqzqcqjlrtng'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('truevote404@gmail.com', 'TrueVote OTP');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'TrueVote Password Reset OTP';
        $mail->Body    = "Your password reset OTP is <b>$otp</b>. It expires in 5 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

$error = $success = "";

/** Step 1: Send OTP */
if (isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if user exists (voter or admin)
        $check = $pdo->prepare("SELECT user_id FROM users WHERE email=?");
        $check->execute([$email]);
        $user = $check->fetch();
        if (!$user) {
            $error = "No user found with this email.";
        } else {
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));
            $stmt = $pdo->prepare("INSERT INTO otp_verification (email, otp_code, expiry_time, is_verified)
                                   VALUES (?, ?, ?, 0)");
            $stmt->execute([$email, $otp, $expiry]);

            if (sendOTP($email, $otp)) {
                $_SESSION['pending_email'] = $email;
                $success = "OTP sent successfully to $email.";
            } else {
                $error = "Failed to send OTP.";
            }
        }
    }
}

/** Step 2: Verify OTP */
if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $email = $_SESSION['pending_email'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM otp_verification
                           WHERE email=? AND otp_code=? AND is_verified=0
                           ORDER BY otp_id DESC LIMIT 1");
    $stmt->execute([$email, $entered_otp]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && strtotime($row['expiry_time']) >= time()) {
        $pdo->prepare("UPDATE otp_verification SET is_verified=1, verified_at=NOW() WHERE otp_id=?")
            ->execute([$row['otp_id']]);
        $_SESSION['verified_email'] = $email;
        unset($_SESSION['pending_email']);
        header("Location: reset_password.php");
        exit;
    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TrueVote - Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
<div class="card p-4 shadow-lg mx-auto mt-5" style="max-width:420px;">
<h4 class="text-center">Forgot Password</h4>

<?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?=htmlspecialchars($success)?></div><?php endif; ?>

<?php if (empty($_SESSION['pending_email'])): ?>
<form method="post">
    <div class="mb-3">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <button type="submit" name="send_otp" class="btn btn-primary w-100">Send OTP</button>
</form>
<?php else: ?>
<form method="post">
    <div class="mb-3">
        <label>Enter OTP</label>
        <input type="text" name="otp" maxlength="6" class="form-control" required>
    </div>
    <button type="submit" name="verify_otp" class="btn btn-success w-100">Verify OTP</button>
</form>
<?php endif; ?>

</div>
</div>
</body>
</html>
