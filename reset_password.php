<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['verified_email'])) {
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['verified_email'];
$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm_password'];

    if ($pass1 !== $pass2) {
        $error = "Passwords do not match.";
    } else {
        $hash = password_hash($pass1, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->execute([$hash, $email]);
        unset($_SESSION['verified_email']);

        // Redirect to login page after 2 seconds
        header("refresh:2;url=login.php");
        $success = "Password changed successfully. Redirecting to login...";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
<div class="card p-4 shadow-lg mx-auto mt-5" style="max-width:420px;">
<h4 class="text-center">Reset Password</h4>
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?=$success?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Change Password</button>
</form>
</div>
</div>
</body>
</html>
