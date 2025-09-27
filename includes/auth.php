<?php
// includes/auth.php
require_once __DIR__ . "/../db.php";

// Login User
function loginUser($email, $password, $role = "voter") {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND role=? AND status='approved'");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

// Check Login
function checkLogin($role = null) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    if ($role && $_SESSION['role'] !== $role) {
        return false;
    }
    return true;
}

// Logout
function logoutUser() {
    session_unset();
    session_destroy();
}
