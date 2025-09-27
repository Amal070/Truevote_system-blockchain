<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db.php"; // DB connection

$error = "";

// Check if email from OTP step exists
if (!isset($_SESSION['verified_email'])) {
    die("Access denied. Please verify your email first.");
}
$email = $_SESSION['verified_email'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voter_id  = trim($_POST['voter_id']);
    $full_name = trim($_POST['full_name']);
    $phone     = trim($_POST['phone']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // Validation
    if (!$voter_id || !$full_name || !$phone || !$password || !$confirm) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Phone number must be exactly 10 digits.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must have at least 1 capital letter, 1 number, 1 symbol, and be at least 8 characters long.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        try {
            // Check if Email OR Voter ID already exists
            $check = $pdo->prepare("SELECT email, voter_id FROM users WHERE email = ? OR voter_id = ?");
            $check->execute([$email, $voter_id]);
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                if ($existing['email'] === $email) {
                    $error = "Email already registered.";
                } elseif ($existing['voter_id'] === $voter_id) {
                    $error = "Voter ID already registered.";
                }
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO users 
                    (voter_id, full_name, email, phone, password, role, status) 
                    VALUES (?, ?, ?, ?, ?, 'voter', 'pending')");
                $stmt->execute([$voter_id, $full_name, $email, $phone, $hashedPassword]);

                unset($_SESSION['verified_email']); // Clear session

                echo "<script>
                    window.location.href = '../login.php';
                </script>";
                exit;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Registration - TrueVote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease-in-out;
        }
        h4 {
            font-weight: 700;
            color: #0d6efd;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.3);
        }
        .btn-primary {
            border-radius: 30px;
            font-weight: 600;
            padding: 10px;
        }
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
        .text-danger, .text-success {
            font-size: 0.85rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="card p-4 mx-auto" style="max-width: 500px;">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3593/3593399.png" alt="TrueVote Logo" width="60" class="mb-2">
            <h4>Complete Your Registration</h4>
            <p class="text-muted small mb-0">Please provide your details to finish setting up your account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" id="regForm">
            <div class="mb-3">
                <label class="form-label">Email (readonly)</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Voter ID</label>
                <input type="text" name="voter_id" maxlength="20" class="form-control" placeholder="Enter your Voter ID" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" pattern="[0-9]{10}" maxlength="10" class="form-control" placeholder="Enter 10-digit phone number" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Create Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Min 8 chars, 1 capital, 1 number, 1 symbol"
                    required>
                <small id="passwordMessage" class="text-danger d-block mt-1"></small>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter password" required>
                <small id="confirmMessage" class="text-danger d-block mt-1"></small>
            </div>

            <button type="submit" class="btn btn-primary w-100">✅ Complete Registration</button>
        </form>
    </div>
</div>

<script>
const passwordField = document.getElementById('password');
const confirmField  = document.getElementById('confirm_password');
const passwordMsg   = document.getElementById('passwordMessage');
const confirmMsg    = document.getElementById('confirmMessage');

passwordField.addEventListener('input', () => {
    const value = passwordField.value;
    const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!pattern.test(value)) {
        passwordMsg.textContent = "⚠️ Must have 1 capital, 1 number, 1 symbol & min 8 characters.";
        passwordMsg.classList.remove("text-success");
        passwordMsg.classList.add("text-danger");
    } else {
        passwordMsg.textContent = "✅ Password looks good!";
        passwordMsg.classList.remove("text-danger");
        passwordMsg.classList.add("text-success");
    }
});

confirmField.addEventListener('input', () => {
    if (confirmField.value !== passwordField.value) {
        confirmMsg.textContent = "❌ Passwords do not match.";
        confirmMsg.classList.remove("text-success");
        confirmMsg.classList.add("text-danger");
    } else {
        confirmMsg.textContent = "✅ Passwords match!";
        confirmMsg.classList.remove("text-danger");
        confirmMsg.classList.add("text-success");
    }
});

document.getElementById('regForm').addEventListener('submit', (e) => {
    if (passwordField.value !== confirmField.value) {
        e.preventDefault();
        alert("Passwords do not match!");
    }
});
</script>
</body>
</html>
