<?php
// start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// block non-logged in users
if (empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// fallback values to avoid warnings
$name       = !empty($_SESSION['name']) ? $_SESSION['name'] : 'Voter';
$voterId    = !empty($_SESSION['voter_id']) ? $_SESSION['voter_id'] : '';
$profileImg = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'default.jpg';
?>
<style>
  body {margin:0;font-family:Arial,sans-serif;background-color:#111827;color:white;}
  .container {padding:20px;}
  header.voter-gradient {
    background:linear-gradient(to right,#2563eb,#4f46e5);
    padding:15px 20px;
    color:white;
    display:flex;justify-content:space-between;align-items:center;
  }
  header .left {display:flex;align-items:center;gap:10px;}
  header .left .logo {
    width:40px;height:40px;background:#3b82f6;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
  }
  header .left .title {font-size:20px;font-weight:bold;}
  header nav a {color:white;text-decoration:none;margin-left:15px;}
  header nav a:hover {text-decoration:underline;}
  .profile {display:flex;align-items:center;gap:10px;}
  .profile img {width:40px;height:40px;border-radius:50%;object-fit:cover;}
</style>

<header class="voter-gradient">
  <div class="left">
    <div class="logo"><i class="fas fa-vote-yea text-white"></i></div>
    <div>
      <div class="title">TrueVote</div>
      <div style="font-size:12px;color:#e0e0e0;">Secure Digital Voting</div>
    </div>
    <nav class="desktop-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="elections.php">Elections</a>
      <a href="history.php">History</a>
      <a href="results.php">Results</a>
      <a href="profile.php">Profile</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>

  <div class="profile">
    <img src="../uploads/profile/<?= htmlspecialchars($profileImg) ?>" alt="Profile">
    <div>
      <div><?= htmlspecialchars($name) ?></div>
      <?php if ($voterId !== ''): ?>
      <div style="font-size:12px;color:#ddd;">Voter ID: <?= htmlspecialchars($voterId) ?></div>
      <?php endif; ?>
    </div>
  </div>
</header>
