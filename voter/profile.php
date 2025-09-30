<?php 
session_start();
require_once __DIR__ . "/../db.php";

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

// Profile image path
if (!empty($user['profile_image'])) {
    $profile_image = "../" . ltrim($user['profile_image'], "/");
} else {
    $profile_image = "../uploads/profile/default.png";
}

// Load state.json for dropdowns
$state_json = json_decode(
    file_get_contents(__DIR__ . "/../includes/constituencies/state.json"),
    true
);

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $father_name = trim($_POST['father_name'] ?? '');
    $gender      = $_POST['gender'] ?? '';
    $address     = trim($_POST['address'] ?? '');
    $state       = trim($_POST['state'] ?? '');
    $district    = trim($_POST['district'] ?? '');
    $constituency= trim($_POST['constituency'] ?? '');

    $new_image = ltrim($user['profile_image'], "/");

    // Upload new profile image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $new_image = "uploads/profile/" . $fileName;
            $profile_image = "../" . $new_image;
        } else {
            $error = "Failed to upload profile image.";
        }
    }

    // Validation & update
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Phone number must be 10 digits.";
    } else {
        if (!isset($error)) {
            $update = $pdo->prepare("UPDATE users 
                SET email=?, phone=?, father_name=?, gender=?, address=?, state=?, district=?, constituency=?, profile_image=? 
                WHERE user_id=?");
            if ($update->execute([$email, $phone, $father_name, $gender, $address, $state, $district, $constituency, $new_image, $user_id])) {
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

    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 mb-8">
        <h1 class="text-3xl font-bold"><?= htmlspecialchars($user['full_name']); ?>'s Profile</h1>
        <p>Manage your voter information and security settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Form -->
        <div class="bg-gray-800 rounded-xl p-6">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <!-- Image -->
                <div class="flex justify-center mb-4 relative w-32 mx-auto">
                    <img src="<?= htmlspecialchars($profile_image); ?>" 
                         alt="Profile Image" 
                         class="w-32 h-32 rounded-full border-4 border-blue-500 object-cover">
                    <label for="profile_image_input" class="absolute bottom-2 right-2 bg-blue-600 p-2 rounded-full cursor-pointer hover:bg-blue-700 transition">
                        <i class="fas fa-pencil-alt text-white"></i>
                    </label>
                    <input type="file" name="profile_image" id="profile_image_input" class="hidden" onchange="this.form.submit()">
                </div>

                <!-- Readonly fields -->
                <input type="text" value="<?= htmlspecialchars($user['full_name']); ?>" class="w-full px-4 py-2 rounded bg-gray-700" readonly>
                <input type="text" value="<?= htmlspecialchars($user['voter_id']); ?>" class="w-full px-4 py-2 rounded bg-gray-700" readonly>
                <input type="date" value="<?= htmlspecialchars($user['dob']); ?>" class="w-full px-4 py-2 rounded bg-gray-700" readonly>

                <!-- Editable fields -->
                <input type="text" name="father_name" value="<?= htmlspecialchars($user['father_name']); ?>" placeholder="Father's Name" class="w-full px-4 py-2 rounded bg-gray-700">
                <select name="gender" class="w-full px-4 py-2 rounded bg-gray-700">
                    <option value="">Select Gender</option>
                    <option value="Male" <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
                    <option value="Other" <?= $user['gender']=='Other'?'selected':'' ?>>Other</option>
                </select>
                <textarea name="address" class="w-full px-4 py-2 rounded bg-gray-700" rows="2"><?= htmlspecialchars($user['address']); ?></textarea>

                <!-- State / District / Constituency -->
                <select name="state" id="stateSelect" class="w-full px-4 py-2 rounded bg-gray-700">
                    <option value="">Select State</option>
                    <?php foreach ($state_json as $stateName => $districts): ?>
                        <option value="<?= htmlspecialchars($stateName); ?>" <?= $user['state']==$stateName?'selected':'' ?>><?= htmlspecialchars($stateName); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="district" id="districtSelect" class="w-full px-4 py-2 rounded bg-gray-700">
                    <option value="">Select District</option>
                    <?php 
                    if (!empty($user['state']) && isset($state_json[$user['state']])) {
                        foreach ($state_json[$user['state']] as $districtName => $constituencies) {
                            echo '<option value="'.htmlspecialchars($districtName).'" '.($user['district']==$districtName?'selected':'').'>'.htmlspecialchars($districtName).'</option>';
                        }
                    }
                    ?>
                </select>

                <select name="constituency" id="constituencySelect" class="w-full px-4 py-2 rounded bg-gray-700">
                    <option value="">Select Constituency</option>
                    <?php 
                    if (!empty($user['state']) && !empty($user['district']) && isset($state_json[$user['state']][$user['district']])) {
                        foreach ($state_json[$user['state']][$user['district']] as $c) {
                            echo '<option value="'.htmlspecialchars($c).'" '.($user['constituency']==$c?'selected':'').'>'.htmlspecialchars($c).'</option>';
                        }
                    }
                    ?>
                </select>

                <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 rounded bg-gray-700">
                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2 rounded bg-gray-700">

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded mt-4">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </form>
        </div>

        <!-- Security Settings Card -->
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

<script>
const stateData = <?= json_encode($state_json); ?>;
const stateSelect = document.getElementById('stateSelect');
const districtSelect = document.getElementById('districtSelect');
const constituencySelect = document.getElementById('constituencySelect');

stateSelect.addEventListener('change', function() {
    const state = this.value;
    districtSelect.innerHTML = '<option value="">Select District</option>';
    constituencySelect.innerHTML = '<option value="">Select Constituency</option>';
    if(state && stateData[state]) {
        for(const district in stateData[state]) {
            const opt = document.createElement('option');
            opt.value = district;
            opt.textContent = district;
            districtSelect.appendChild(opt);
        }
    }
});

districtSelect.addEventListener('change', function() {
    const state = stateSelect.value;
    const district = this.value;
    constituencySelect.innerHTML = '<option value="">Select Constituency</option>';
    if(state && district && stateData[state] && stateData[state][district]) {
        stateData[state][district].forEach(c=>{
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            constituencySelect.appendChild(opt);
        });
    }
});
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>


