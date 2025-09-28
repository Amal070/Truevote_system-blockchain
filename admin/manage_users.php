<?php
require_once "../db.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Load PHPMailer
require_once __DIR__ . "/../includes/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/../includes/PHPMailer/SMTP.php";
require_once __DIR__ . "/../includes/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send Notification Email
 */
function sendUserNotification($email, $status) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'truevote404@gmail.com';
        $mail->Password   = 'qqqqpqzqcqjlrtng';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('truevote404@gmail.com', 'TrueVote Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);

        if ($status === 'approved') {
            $mail->Subject = 'TrueVote Registration Approved ✅';
            $mail->Body    = "
                <h3>Congratulations!</h3>
                <p>Your TrueVote account has been <b>approved</b>.</p>
                <p>You can now <a href='http://localhost/truevote/login.php'>login here</a> and participate in elections securely.</p>
                <br>
                <p>Regards,<br>TrueVote Team</p>
            ";
        } elseif ($status === 'rejected') {
            $mail->Subject = 'TrueVote Registration Rejected ❌';
            $mail->Body    = "
                <h3>Registration Failed</h3>
                <p>We regret to inform you that your TrueVote registration was <b>rejected</b> due to fake or invalid details.</p>
                <p>If you believe this was a mistake, please contact support.</p>
                <br>
                <p>Regards,<br>TrueVote Team</p>
            ";
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Handle Approve/Reject Actions
$msg = null;
$msgType = null;

if (isset($_GET['id'], $_GET['status'])) {
    $userId = (int) $_GET['id'];
    $status = strtolower($_GET['status']);
    $allowedStatuses = ['pending','verified','approved','rejected'];

    if (in_array($status, $allowedStatuses)) {
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE user_id=?");
        $success = $stmt->execute([$status,$userId]);

        if ($success) {
            $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id=?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && in_array($status, ['approved','rejected'])) {
                sendUserNotification($user['email'], $status);
            }

            $msg = "User status updated successfully.";
            $msgType = "success";
        } else {
            $msg = "Failed to update status.";
            $msgType = "error";
        }
    } else {
        $msg = "Invalid status.";
        $msgType = "error";
    }
}

// Fetch all non-admin users
$stmt = $pdo->prepare("SELECT * FROM users WHERE role!='admin' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - TrueVote Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn { padding:6px 12px; border-radius:6px; font-size:14px; font-weight:500; }
        .btn-approve { background:#16a34a; color:white; }
        .btn-reject { background:#dc2626; color:white; }
        .btn:hover { opacity:0.9; }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">
    <?php include "includes/header.php"; ?>
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold mb-6">Manage Users</h1>

        <?php if($msg): ?>
            <div class="mb-6 p-4 rounded <?= 
                $msgType==='success' ? 'bg-green-800 border-l-4 border-green-500 text-green-200' : 'bg-red-800 border-l-4 border-red-500 text-red-200'
            ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <div class="bg-gray-800 rounded-xl shadow-lg overflow-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Voter ID</th>
                        <th class="px-6 py-3 text-left">Address</th>
                        <th class="px-6 py-3 text-left">Constituency</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['full_name']) ?> (ID: <?= $user['user_id'] ?>)</td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['phone']) ?></td>
                            <td class="px-6 py-4"><?= $user['voter_id'] ?: '<span class="text-gray-400 italic">Not Provided</span>' ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['address'] ?: 'Not Provided') ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['constituency'] ?: 'Not Provided') ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['role']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm 
                                    <?php if($user['status']==='approved'){echo 'bg-green-600';}
                                          elseif($user['status']==='rejected'){echo 'bg-red-600';}
                                          elseif($user['status']==='pending'){echo 'bg-yellow-600';}
                                          elseif($user['status']==='verified'){echo 'bg-blue-600';} ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <?php if($user['status']==='pending'||$user['status']==='verified'): ?>
                                    <a href="?id=<?= $user['user_id'] ?>&status=approved" class="btn btn-approve">Approve</a>
                                    <a href="?id=<?= $user['user_id'] ?>&status=rejected" class="btn btn-reject">Reject</a>
                                <?php elseif($user['status']==='approved'): ?>
                                    <a href="?id=<?= $user['user_id'] ?>&status=rejected" class="btn btn-reject">Reject</a>
                                <?php elseif($user['status']==='rejected'): ?>
                                    <a href="?id=<?= $user['user_id'] ?>&status=approved" class="btn btn-approve">Re-Approve</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="mt-6 text-center text-sm text-gray-500">TrueVote Admin Panel - Secure User Management System</p>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>
