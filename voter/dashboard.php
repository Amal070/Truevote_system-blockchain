<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db.php';

// ✅ Check login and role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

// ✅ Fetch voter info
$stmt = $pdo->prepare("SELECT full_name, voter_id, profile_image FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$voter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voter) {
    header("Location: ../logout.php");
    exit;
}

$name       = $voter['full_name'] ?? 'Voter';
$voterId    = $voter['voter_id'] ?? 'N/A';
$imageFile  = $voter['profile_image'] ?? '';
$profileImg = "../uploads/profile/default.png";
if (!empty($imageFile) && file_exists(__DIR__ . "/../uploads/profile/" . $imageFile)) {
    $profileImg = "../uploads/profile/" . $imageFile;
}

// Set session variables for header
$_SESSION['full_name'] = $name;
$_SESSION['voter_id'] = $voterId;
$_SESSION['profile_pic'] = $imageFile;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voter Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {margin:0;font-family:Arial,sans-serif;background-color:#111827;color:white;display:flex;flex-direction:column;min-height:100vh;}
    .container {padding:20px;flex:1;}

    .btn {padding:10px 16px;border-radius:8px;border:none;cursor:pointer;font-weight:bold;margin-right:10px;}
    .btn-primary {background:white;color:#2563eb;}
    .btn-secondary {background:#1f2937;color:white;}
    .tag {display:inline-block;background:#1d4ed8;padding:5px 12px;border-radius:999px;font-size:14px;margin-right:8px;}
    .grid {display:grid;gap:20px;}
    .grid-4 {grid-template-columns:repeat(auto-fit,minmax(150px,1fr));}
    .grid-2 {grid-template-columns:repeat(auto-fit,minmax(250px,1fr));}
    .card {background:#1f2937;border-radius:12px;padding:20px;text-align:center;}
    .card h2 {font-size:28px;margin:10px 0;}
    .section {background:#1f2937;border-radius:16px;padding:20px;margin-top:20px;}
    .gray {color:#9ca3af;}
    .green {color:#22c55e;}
    .blue {color:#3b82f6;}
    .purple {color:#a855f7;}
    .recent-item {background:#374151;border-radius:12px;padding:15px;margin-bottom:12px;}

    /* ✅ Footer styles */
    footer {
      background:#1f2937;
      color:#9ca3af;
      text-align:center;
      padding:15px;
      font-size:14px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    footer .social a {
      color:#9ca3af;
      margin-left:12px;
      font-size:18px;
      text-decoration:none;
    }
    footer .social a:hover {color:white;}
  </style>
</head>
<body>

<?php include "includes/header.php"; ?>

<div class="container">
  <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
  <p>Your secure voting dashboard - every vote counts and is protected by blockchain technology</p>
  <button class="btn btn-primary">Vote Now</button>
  <button class="btn btn-secondary">View Results</button>
  <div style="margin-top:15px;">
    <span class="tag">Verified Voter</span>
    <span class="tag">3 Elections Available</span>
    <span class="tag">2 Votes Cast</span>
  </div>

  <div class="grid grid-4" style="margin-top:20px;">
    <div class="card"><h2>3</h2><p class="gray">Available Elections</p></div>
    <div class="card"><h2>2</h2><p class="gray">Votes Cast</p></div>
    <div class="card"><h2>1</h2><p class="gray">Pending Results</p></div>
    <div class="card"><h2>100%</h2><p class="gray">Security Score</p></div>
  </div>

  <div class="section">
    <h2>Active Elections</h2>
    <div class="grid grid-2">
      <div class="card" style="text-align:left;background:#374151;">
        <h3>Presidential Election 2024</h3>
        <p>Choose the next President</p>
        <p class="gray">Ends: March 31, 2024 • 3 candidates</p>
        <button class="btn btn-primary" style="margin-top:10px;">Vote Now</button>
      </div>
      <div class="card" style="text-align:left;background:#374151;">
        <h3>Senate Election 2024</h3>
        <p>Select your state senator</p>
        <p class="gray">Ends: March 25, 2024 • 2 candidates</p>
        <button class="btn btn-secondary" style="margin-top:10px;" disabled>Vote Cast</button>
      </div>
    </div>
  </div>

  <div class="section">
    <h2>Recent Activity</h2>
    <div class="recent-item">
      <p class="green">Vote Cast - Senate Election 2024</p>
      <p>Your vote for Maria Rodriguez has been recorded</p>
      <p class="gray">March 10, 2024 at 08:00 PM GMT+5:30</p>
    </div>
    <div class="recent-item">
      <p class="blue">Registered for Presidential Election 2024</p>
      <p>You are now eligible to vote in this election</p>
      <p class="gray">March 1, 2024 at 02:45 PM GMT+5:30</p>
    </div>
    <div class="recent-item">
      <p class="purple">Blockchain Verification Complete</p>
      <p>Your vote in Governor Election 2023 has been verified</p>
      <p class="gray">November 8, 2023 at 03:50 PM GMT+5:30</p>
    </div>
  </div>

  <div class="section">
    <h2>Security Settings</h2>
    <!-- Security settings content -->
  </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
