<?php
session_start();
require_once __DIR__ . "/../db.php";

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $user_id = $_SESSION['user_id'];

    // Fetch user from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "User not found.";
    } elseif (!password_verify($current_password, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash new password and update
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $updateStmt->execute([$hashedPassword, $user_id]);

        $_SESSION['success'] = "Password updated successfully.";
        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
<?php include "includes/header.php"; ?>

<main class="flex-grow">
    <div class="container mx-auto px-4 py-8">

        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 mb-8 shadow-lg">
            <h1 class="text-3xl font-bold flex items-center gap-2">
                <i class="fas fa-lock"></i> Change Password
            </h1>
            <p class="text-gray-200 mt-1">Keep your account secure by updating your password regularly.</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6 shadow-md flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error); ?></span>
            </div>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6 shadow-md flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="max-w-lg mx-auto bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-700">
            <form method="POST" class="space-y-6">

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" 
                            class="w-full px-4 py-2 pr-10 rounded-lg bg-gray-700 border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                            required>
                        <i class="fas fa-key absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" 
                            class="w-full px-4 py-2 pr-10 rounded-lg bg-gray-700 border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                            required>
                        <i class="fas fa-lock absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" 
                            class="w-full px-4 py-2 pr-10 rounded-lg bg-gray-700 border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                            required>
                        <i class="fas fa-check absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 py-3 rounded-lg font-semibold shadow-md transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</main>

<?php include "includes/footer.php"; ?>
</body>
</html>
