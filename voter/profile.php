<?php
session_start();
require_once __DIR__ . "/../db.php";

// directory on the server
$targetDir = __DIR__ . "/../uploads/profile/";

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch logged-in user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

// ---- fix profile image path ----
if (!empty($user['profile_image'])) {
    // prepend ../ so it points to /uploads/profile/...
    $profile_image = "../" . ltrim($user['profile_image'], "/");
} else {
    $profile_image = "../uploads/profile/default.png";
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    // store in DB as relative to project root
    $new_image = ltrim($user['profile_image'], "/");

    // Upload profile image if provided
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            // what we store in DB
            $new_image = "uploads/profile/" . $fileName;
            $profile_image = "../" . $new_image; // for immediate display
        } else {
            $error = "Failed to upload profile image.";
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Phone number must be 10 digits.";
    } else {
        if (!isset($error)) {
            $update = $pdo->prepare("UPDATE users SET email=?, phone=?, profile_image=? WHERE user_id=?");
            if ($update->execute([$email, $phone, $new_image, $user_id])) {
                $_SESSION['success'] = "Profile updated successfully.";
                header("Location: profile.php");
                exit;
            } else {
                $error = "Error updating profile.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Voter Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
<?php include "includes/header.php"; ?>

<div class="container mx-auto px-4 py-6">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-600 text-white p-3 rounded mb-4"><?= htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 mb-8">
        <h1 class="text-3xl font-bold"><?= htmlspecialchars($user['full_name']); ?>'s Profile</h1>
        <p>Manage your voter information and security settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Information -->
        <div class="bg-gray-800 rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6"><i class="fas fa-user-circle mr-2"></i>Personal Information</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <!-- Clickable profile image -->
                <div class="flex justify-center mb-4">
                    <label for="profile_image_input" class="cursor-pointer">
                        <img src="<?= htmlspecialchars($profile_image); ?>" 
                             alt="Profile Image" 
                             class="w-32 h-32 rounded-full border-4 border-blue-500 hover:opacity-80 transition">
                    </label>
                    <input type="file" name="profile_image" id="profile_image_input" class="hidden" onchange="this.form.submit()">
                </div>

                <div>
                    <label class="block mb-1">Full Name</label>
                    <input type="text" value="<?= htmlspecialchars($user['full_name']); ?>" class="w-full px-4 py-2 rounded bg-gray-700" readonly>
                </div>

                <div>
                    <label class="block mb-1">Voter ID</label>
                    <input type="text" value="<?= htmlspecialchars($user['voter_id']); ?>" class="w-full px-4 py-2 rounded bg-gray-700" readonly>
                </div>

                <div>
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 rounded bg-gray-700">
                </div>

                <div>
                    <label class="block mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2 rounded bg-gray-700">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded mt-4">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </form>
        </div>

        <!-- Security Settings -->
        <div class="bg-gray-800 rounded-xl p-6">
            <h2 class="text-xl font-bold mb-6"><i class="fas fa-shield-alt mr-2"></i>Security Settings</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                    <div>
                        <h3>Two-Factor Authentication</h3>
                        <p class="text-sm text-gray-300">Extra layer of security</p>
                    </div>
                    <span class="text-green-400"><i class="fas fa-check-circle mr-1"></i>Enabled</span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                    <div>
                        <h3>Email Notifications</h3>
                        <p class="text-sm text-gray-300">Receive election updates</p>
                    </div>
                    <input type="checkbox" checked class="h-5 w-10">
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                    <div>
                        <h3>Blockchain Verification</h3>
                        <p class="text-sm text-gray-300">Votes secured on blockchain</p>
                    </div>
                    <span class="text-green-400"><i class="fas fa-link mr-1"></i>Connected</span>
                </div>

                <button class="w-full border border-gray-500 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-key mr-2"></i>Change Password
                </button>
            </div>
        </div>
    </div>
</div>
<?php include "includes/footer.php"; ?>
</body>
</html>
