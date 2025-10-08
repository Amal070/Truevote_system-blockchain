<?php
require_once "../db.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Load Voter CSV Data
$csvVoters = loadVoterCSV();

// Load PHPMailer
require_once __DIR__ . "/../includes/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/../includes/PHPMailer/SMTP.php";
require_once __DIR__ . "/../includes/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Load Voter CSV Data
 */
function loadVoterCSV() {
    $csvFile = __DIR__ . "/../includes/voter_details/voter.csv";
    $voters = [];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $voters[] = array_combine($header, $data);
        }
        fclose($handle);
    }
    return $voters;
}

/**
 * Validate User Against CSV
 */
function validateUserAgainstCSV($user, $csvVoters) {
    foreach ($csvVoters as $csvVoter) {
        if (
            strtoupper(trim($user['voter_id'] ?? '')) === strtoupper(trim($csvVoter['voter_id'])) &&
            strtoupper(trim($user['full_name'] ?? '')) === strtoupper(trim($csvVoter['name'])) &&
            strtoupper(trim($user['father_name'] ?? '')) === strtoupper(trim($csvVoter['father_name'])) &&
            strtoupper(trim($user['gender'] ?? '')) === strtoupper(trim($csvVoter['gender'])) &&
            trim($user['dob'] ?? '') === trim($csvVoter['dob']) &&
            strtoupper(trim($user['address'] ?? '')) === strtoupper(trim($csvVoter['address'])) &&
            strtoupper(trim($user['state'] ?? '')) === strtoupper(trim($csvVoter['state'])) &&
            strtoupper(trim($user['district'] ?? '')) === strtoupper(trim($csvVoter['district'])) &&
            strtoupper(trim($user['constituency'] ?? '')) === strtoupper(trim($csvVoter['constituency']))
        ) {
            return true;
        }
    }
    return false;
}

/**
 * Get Mismatched Fields
 */
function getMismatchedFields($user, $csvVoters) {
    $mismatches = [];
    foreach ($csvVoters as $csvVoter) {
        if (strtoupper(trim($user['voter_id'] ?? '')) === strtoupper(trim($csvVoter['voter_id']))) {
            // Found matching voter_id, check other fields
            if (strtoupper(trim($user['full_name'] ?? '')) !== strtoupper(trim($csvVoter['name']))) {
                $mismatches['full_name'] = true;
            }
            if (strtoupper(trim($user['father_name'] ?? '')) !== strtoupper(trim($csvVoter['father_name']))) {
                $mismatches['father_name'] = true;
            }
            if (strtoupper(trim($user['gender'] ?? '')) !== strtoupper(trim($csvVoter['gender']))) {
                $mismatches['gender'] = true;
            }
            if (trim($user['dob'] ?? '') !== trim($csvVoter['dob'])) {
                $mismatches['dob'] = true;
            }
            if (strtoupper(trim($user['address'] ?? '')) !== strtoupper(trim($csvVoter['address']))) {
                $mismatches['address'] = true;
            }
            if (strtoupper(trim($user['state'] ?? '')) !== strtoupper(trim($csvVoter['state']))) {
                $mismatches['state'] = true;
            }
            if (strtoupper(trim($user['district'] ?? '')) !== strtoupper(trim($csvVoter['district']))) {
                $mismatches['district'] = true;
            }
            if (strtoupper(trim($user['constituency'] ?? '')) !== strtoupper(trim($csvVoter['constituency']))) {
                $mismatches['constituency'] = true;
            }
            return $mismatches;
        }
    }
    // If no matching voter_id, all fields mismatch
    return ['voter_id' => true, 'full_name' => true, 'father_name' => true, 'gender' => true, 'dob' => true, 'address' => true, 'state' => true, 'district' => true, 'constituency' => true];
}

/**
 * Send Notification Email
 */
function sendUserNotification($email, $status, $reasons = []) {
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
            $reasonsText = !empty($reasons) ? '<p>Reasons for rejection: ' . implode(', ', $reasons) . '</p>' : '';
            $mail->Body    = "
                <h3>Registration Failed</h3>
                <p>We regret to inform you that your TrueVote registration was <b>rejected</b> due to fake or invalid details.</p>
                $reasonsText
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

// Handle Reject Actions
$msg = null;
$msgType = null;

if (isset($_POST['reject_user'])) {
    $userId = (int) $_POST['user_id'];
    $reasons = isset($_POST['reasons']) ? $_POST['reasons'] : [];

    $stmt = $pdo->prepare("UPDATE users SET status='rejected' WHERE user_id=?");
    $success = $stmt->execute([$userId]);

    if ($success) {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id=?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            sendUserNotification($user['email'], 'rejected', $reasons);
        }

        $msg = "User rejected successfully.";
        $msgType = "success";
    } else {
        $msg = "Failed to reject user.";
        $msgType = "error";
    }
}

// Fetch all non-admin users
$stmt = $pdo->prepare("SELECT * FROM users WHERE role!='admin' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process users for automatic approval
foreach ($users as &$user) {
    if (($user['status'] === 'pending' || $user['status'] === 'rejected') && validateUserAgainstCSV($user, $csvVoters)) {
        $stmt = $pdo->prepare("UPDATE users SET status='approved' WHERE user_id=?");
        $stmt->execute([$user['user_id']]);
        $user['status'] = 'approved';
        sendUserNotification($user['email'], 'approved');
    }
}
unset($user);

// Sort users to show not matched (with mismatches) at the top
usort($users, function($a, $b) use ($csvVoters) {
    $mismatchesA = getMismatchedFields($a, $csvVoters);
    $mismatchesB = getMismatchedFields($b, $csvVoters);
    $hasMismatchA = !empty($mismatchesA);
    $hasMismatchB = !empty($mismatchesB);
    if ($hasMismatchA && !$hasMismatchB) return -1;
    if (!$hasMismatchA && $hasMismatchB) return 1;
    return 0;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voters - TrueVote Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn { padding:6px 12px; border-radius:6px; font-size:14px; font-weight:500; }
        .btn-reject { background:#dc2626; color:white; }
        .btn:hover { opacity:0.9; }
        .mismatch { color: red; }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">
    <?php include "includes/header.php"; ?>
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold mb-6">Manage Voters</h1>

        <?php if($msg): ?>
            <div class="mb-6 p-4 rounded <?= 
                $msgType==='success' ? 'bg-green-800 border-l-4 border-green-500 text-green-200' : 'bg-red-800 border-l-4 border-red-500 text-red-200'
            ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <div class="bg-gray-800 rounded-xl shadow-lg overflow-auto">
            <table class="w-full min-w-[1200px]">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">User ID</th>
                        <th class="px-6 py-3 text-left">Voter ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Father Name</th>
                        <th class="px-6 py-3 text-left">Gender</th>
                        <th class="px-6 py-3 text-left">DOB</th>
                        <th class="px-6 py-3 text-left">Address</th>
                        <th class="px-6 py-3 text-left">State</th>
                        <th class="px-6 py-3 text-left">District</th>
                        <th class="px-6 py-3 text-left">Constituency</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach($users as $user): ?>
                        <?php $mismatches = getMismatchedFields($user, $csvVoters); ?>
                        <tr>
                            <td class="px-6 py-4"><?= $user['user_id'] ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['voter_id']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['voter_id']) ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['full_name']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['full_name']) ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['father_name']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['father_name'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['gender']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['gender'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['dob']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['dob'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['address']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['address'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['state']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['state'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['district']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['district'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4 <?= isset($mismatches['constituency']) ? 'mismatch' : '' ?>"><?= htmlspecialchars($user['constituency'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm 
                                    <?php if($user['status']==='approved'){echo 'bg-green-600';}
                                          elseif($user['status']==='rejected'){echo 'bg-red-600';}
                                          elseif($user['status']==='pending'){echo 'bg-yellow-600';}
                                          elseif($user['status']==='verified'){echo 'bg-blue-600';} ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($mismatches)): ?>
                                    <button onclick="openRejectModal(<?= $user['user_id'] ?>, <?= htmlspecialchars(json_encode($mismatches)) ?>, <?= htmlspecialchars(json_encode($user)) ?>)" class="btn btn-reject">Reject</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Reject Voter</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Select the mismatched fields as reasons for rejection:</p>
                    <form id="rejectForm" method="post">
                        <input type="hidden" name="user_id" id="rejectUserId">
                        <div id="reasonsContainer" class="mt-4 text-left max-h-32 overflow-y-auto"></div>
                        <div class="flex items-center px-4 py-3">
                            <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 mr-2">Cancel</button>
                            <button type="submit" name="reject_user" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(userId, mismatches, user) {
            document.getElementById('rejectUserId').value = userId;
            const container = document.getElementById('reasonsContainer');
            container.innerHTML = '';

            const fields = ['voter_id', 'full_name', 'father_name', 'gender', 'dob', 'address', 'state', 'district', 'constituency'];
            const fieldLabels = {
                'voter_id': 'Voter ID',
                'full_name': 'Name',
                'father_name': 'Father Name',
                'gender': 'Gender',
                'dob': 'Date of Birth',
                'address': 'Address',
                'state': 'State',
                'district': 'District',
                'constituency': 'Constituency'
            };

            fields.forEach(field => {
                if (mismatches[field]) {
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML = `
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="reasons[]" value="${fieldLabels[field]}" class="form-checkbox h-5 w-5 text-red-600">
                            <span class="ml-2 text-gray-700">${fieldLabels[field]}</span>
                        </label>
                    `;
                    container.appendChild(div);
                }
            });

            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>

    <?php include "includes/footer.php"; ?>
</body>
</html>