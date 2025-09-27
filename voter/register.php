<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load DB connection
require_once __DIR__ . "/../db.php";   // this defines $pdo

// Load PHPMailer
require_once __DIR__ . "/../includes/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/../includes/PHPMailer/SMTP.php";
require_once __DIR__ . "/../includes/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send OTP Mail
 */
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'truevote404@gmail.com';   // Gmail
        $mail->Password   = 'qqqqpqzqcqjlrtng';        // Gmail App Password
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
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

$error = $success = "";

/**
 * Reset session if user clicks Start Over
 */
if (isset($_GET['reset'])) {
    unset($_SESSION['pending_email']);
    header("Location: register.php");
    exit;
}

/**
 * Step 1: Send OTP
 */
if (isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);

    if (!$email) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            // Generate OTP
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            // Insert OTP log with email only (use full DB.table to avoid conflicts)
            $stmt = $pdo->prepare("INSERT INTO truevote_db.otp_verification (email, otp_code, expiry_time, is_verified) 
                                   VALUES (?, ?, ?, 0)");
            $stmt->execute([$email, $otp, $expiry]);

            if (sendOTP($email, $otp)) {
                $_SESSION['pending_email'] = $email;
                $success = "OTP sent successfully to $email. Please check your inbox.";
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

/**
 * Step 2: Verify OTP
 */
if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $email = $_SESSION['pending_email'] ?? null;

    if (!$email) {
        $error = "No OTP request found. Please start again.";
    } else {
        // Normalize values
        $entered_otp = (string)$entered_otp;
        $email = strtolower(trim($email));

        // Fetch latest OTP for this email
        $stmt = $pdo->prepare("SELECT * FROM truevote_db.otp_verification 
                               WHERE LOWER(email) = ? 
                               AND otp_code = ? 
                               AND is_verified = 0 
                               ORDER BY otp_id DESC LIMIT 1");
        $stmt->execute([$email, $entered_otp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (strtotime($row['expiry_time']) >= time()) {
                // Mark OTP as verified
                $pdo->prepare("UPDATE truevote_db.otp_verification 
                               SET is_verified=1, verified_at=NOW() 
                               WHERE otp_id=?")
                    ->execute([$row['otp_id']]);

                $_SESSION['verified_email'] = $email;
                unset($_SESSION['pending_email']);

                // echo "<script>
                //     alert('OTP verified! Please complete your registration.');
                //     window.location.href = 'more_info.php';
                // </script>";
                exit;
            } else {
                $error = "OTP expired. Please request a new one.";
            }
        } else {
            $error = "Invalid OTP for $email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TrueVote - Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Mobile responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            border: none;
        }
        .form-control:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
            border-color: #80bdff;
        }
        .btn {
            border-radius: 30px;
            font-weight: 500;
        }
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4 shadow-lg mx-auto" style="max-width: 420px;">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3593/3593399.png" 
                 alt="TrueVote Logo" width="60" class="mb-2">
            <h4 class="fw-bold">TrueVote Registration</h4>
            <p class="text-muted small mb-0">Secure voting starts with account verification</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (empty($_SESSION['pending_email'])): ?>
            <!-- Step 1: Enter Email -->
            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                <button type="submit" name="send_otp" class="btn btn-primary w-100">📩 Send OTP</button>
            </form>
        <?php else: ?>
            <!-- Step 2: Enter OTP -->
            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Enter OTP</label>
                    <input type="text" name="otp" maxlength="6" class="form-control text-center fs-5" placeholder="" required>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-success w-100 mb-2">✅ Verify OTP</button>
            </form>
            <form method="get">
                <button type="submit" name="reset" value="1" class="btn btn-outline-secondary w-100">🔄 Start Over</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
