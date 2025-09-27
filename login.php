<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/db.php";  // DB connection

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'] ?? 'voter';

    try {
        // Fetch user by email and role
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $isPasswordValid = false;

            // Case 1: If password is hashed (normal flow)
            if (password_verify($password, $user['password'])) {
                $isPasswordValid = true;
            }
            // Case 2: If password is plain text in DB (e.g., admin inserted manually)
            elseif ($user['password'] === $password) {
                $isPasswordValid = true;

                // 🔒 Upgrade security: re-hash plain text password
                $newHash = password_hash($password, PASSWORD_BCRYPT);
                $update = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $update->execute([$newHash, $user['user_id']]);
            }

            if ($isPasswordValid) {
                if ($user['status'] !== 'approved') {
                    $error = "Your account is not approved yet.";
                } else {
                    // Set session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role']    = $user['role'];
                    $_SESSION['name']    = $user['full_name'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: admin/dashboard.php");
                    } else {
                        header("Location: voter/dashboard.php");
                    }
                    exit;
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid email, password, or role.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TrueVote Login</title>
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
    .login-container {
      max-width: 420px;
      width: 100%;
      padding: 30px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      animation: fadeIn 0.6s ease-in-out;
    }
    .role-btns button {
      flex: 1;
      border-radius: 30px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .role-btns button.active {
      background-color: #0d6efd;
      color: #fff;
      border-color: #0d6efd;
      box-shadow: 0 0 8px rgba(13, 110, 253, 0.4);
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 8px rgba(13, 110, 253, 0.3);
    }
    .btn-primary {
      border-radius: 30px;
      font-weight: 600;
    }
    .alert {
      border-radius: 10px;
      font-size: 0.9rem;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="text-center mb-4">
      <!-- <img src="assets/logo.png" alt="Logo" width="70" class="mb-2"> -->
      <h3 class="fw-bold">TrueVote Login</h3>
      <p class="text-muted small">Sign in to your secure voting account</p>
    </div>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Role Selection -->
      <div class="mb-3 role-btns d-flex gap-2">
        <input type="hidden" name="role" id="roleInput" value="voter">
        <button type="button" id="voterBtn" class="btn btn-outline-primary active">Voter</button>
        <button type="button" id="adminBtn" class="btn btn-outline-secondary">Admin</button>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <!-- Remember + Forgot -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input type="checkbox" id="remember" class="form-check-input">
          <label for="remember" class="form-check-label">Remember me</label>
        </div>
        <a href="forgot_password.php" class="text-decoration-none small">Forgot password?</a>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
    </form>

    <!-- Register link -->
    <p class="mt-4 text-center small">New user? <a href="voter/register.php" class="fw-semibold">Register here</a></p>
  </div>

  <script>
    const voterBtn = document.getElementById("voterBtn");
    const adminBtn = document.getElementById("adminBtn");
    const roleInput = document.getElementById("roleInput");

    voterBtn.addEventListener("click", () => {
      voterBtn.classList.add("active");
      adminBtn.classList.remove("active");
      roleInput.value = "voter";
    });

    adminBtn.addEventListener("click", () => {
      adminBtn.classList.add("active");
      voterBtn.classList.remove("active");
      roleInput.value = "admin";
    });
  </script>
</body>
</html>

