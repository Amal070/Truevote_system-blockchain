<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    $admin_id = $_SESSION['user_id']; // logged-in admin

    // Validate status
    $allowed = ['pending', 'varified', 'approved', 'rejected'];
    if (in_array($status, $allowed)) {
        // Update status
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE user_id=?");
        $stmt->execute([$status, $id]);

        // Log action
        $user_stmt = $pdo->prepare("SELECT full_name FROM users WHERE user_id=?");
        $user_stmt->execute([$id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        $log_stmt = $pdo->prepare("INSERT INTO logs (admin_id, action, details) VALUES (?, ?, ?)");
        $log_stmt->execute([
            $admin_id,
            "User Status Updated",
            "Set status of {$user['full_name']} to $status"
        ]);
    }
}

header("Location: manage_users.php");
exit;
