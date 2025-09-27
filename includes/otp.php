<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db.php";


require_once __DIR__ . "/../includes/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/../includes/PHPMailer/SMTP.php";
require_once __DIR__ . "/../includes/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send OTP email
 */
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'truevote404@gmail.com';      // your Gmail
        $mail->Password   = 'qqqqpqzqcqjlrtng';           // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('truevote404@gmail.com', 'TrueVote OTP');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP is <b>$otp</b>. It will expire in 5 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("OTP Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Generate and store OTP (for a given user)
 */
function generateAndStoreOTP($user_id, $email) {
    global $pdo;
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    // Insert OTP into otp_verification
    $stmt = $pdo->prepare("INSERT INTO otp_verification (user_id, otp_code, expiry_time, is_verified) 
                           VALUES (?, ?, ?, 0)");
    $stmt->execute([$user_id, $otp, $expiry]);

    if (sendOTPEmail($email, $otp)) {
        $_SESSION['pending_user'] = $user_id;
        $_SESSION['pending_email'] = $email;
        return true;
    }
    return false;
}

/**
 * Verify OTP
 */
function verifyOTP($user_id, $entered_otp) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM otp_verification 
                           WHERE user_id=? AND otp_code=? 
                           AND is_verified=0 
                           AND expiry_time >= NOW()
                           ORDER BY otp_id DESC LIMIT 1");
    $stmt->execute([$user_id, $entered_otp]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Mark OTP as verified
        $pdo->prepare("UPDATE otp_verification 
                       SET is_verified=1 
                       WHERE otp_id=?")->execute([$row['otp_id']]);

        $_SESSION['verified_email'] = $_SESSION['pending_email'] ?? null;
        unset($_SESSION['pending_user'], $_SESSION['pending_email']);
        return true;
    }
    return false;
}
