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
$stmt = $pdo->prepare("SELECT full_name, voter_id, profile_image, constituency FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$voter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voter) {
    header("Location: ../logout.php");
    exit;
}

$name         = $voter['full_name'] ?? 'Voter';
$voterId      = $voter['voter_id'] ?? 'N/A';
$constituency = $voter['constituency'] ?? '';
$imageFile    = $voter['profile_image'] ?? '';
$profileImg   = "../uploads/profile/default.png";
if (!empty($imageFile) && file_exists(__DIR__ . "/../uploads/profile/" . $imageFile)) {
    $profileImg = "../uploads/profile/" . $imageFile;
}

// ✅ Calculate stats
$user_id = $_SESSION['user_id'];

// Available Elections: active elections in constituency not voted
$available_stmt = $pdo->prepare("
    SELECT COUNT(*) FROM elections e
    WHERE e.constituency = ? 
    AND e.announcement_date <= CURDATE() 
    AND e.polling_end_date >= CURDATE()
    AND NOT EXISTS (
        SELECT 1 FROM votes v WHERE v.election_id = e.id AND v.voter_id = ?
    )
");
$available_stmt->execute([$constituency, $user_id]);
$available_elections = $available_stmt->fetchColumn();

// Votes Cast
$votes_cast_stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE voter_id = ?");
$votes_cast_stmt->execute([$user_id]);
$votes_cast = $votes_cast_stmt->fetchColumn();

// Pending Results: elections voted but results not published
$pending_results_stmt = $pdo->prepare("
    SELECT COUNT(*) FROM votes v
    JOIN elections e ON v.election_id = e.id
    WHERE v.voter_id = ? AND e.publish_result = 0
");
$pending_results_stmt->execute([$user_id]);
$pending_results = $pending_results_stmt->fetchColumn();

// Security Score (always 100% for now)
$security_score = 100;

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
    <span class="tag"><?= $available_elections ?> Elections Available</span>
    <span class="tag"><?= $votes_cast ?> Votes Cast</span>
  </div>

  <div class="grid grid-4" style="margin-top:20px;">
    <div class="card"><h2><?= $available_elections ?></h2><p class="gray">Available Elections</p></div>
    <div class="card"><h2><?= $votes_cast ?></h2><p class="gray">Votes Cast</p></div>
    <div class="card"><h2><?= $pending_results ?></h2><p class="gray">Pending Results</p></div>
    <div class="card"><h2><?= $security_score ?>%</h2><p class="gray">Security Score</p></div>
  </div>

  <div class="section">
    <h2>Active Elections</h2>
    <div class="grid grid-2">
      <?php
      // Fetch active elections in voter's constituency
      $active_elections_stmt = $pdo->prepare("
          SELECT * FROM elections
          WHERE constituency = ?
          AND announcement_date <= CURDATE()
          AND polling_end_date >= CURDATE()
          ORDER BY polling_end_date ASC
          LIMIT 4
      ");
      $active_elections_stmt->execute([$constituency]);
      $active_elections = $active_elections_stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($active_elections)) {
          echo '<p class="gray">No active elections in your constituency at the moment.</p>';
      } else {
          foreach ($active_elections as $election) {
              // Check if registered
              $reg_check = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id = ? AND voter_id = ?");
              $reg_check->execute([$election['id'], $user_id]);
              $registered = $reg_check->rowCount() > 0;

              // Check if voted
              $vote_check = $pdo->prepare("SELECT * FROM votes WHERE election_id = ? AND voter_id = ?");
              $vote_check->execute([$election['id'], $user_id]);
              $voted = $vote_check->rowCount() > 0;

              // Count candidates
              $cand_count_stmt = $pdo->prepare("SELECT COUNT(*) FROM candidates WHERE election_id = ?");
              $cand_count_stmt->execute([$election['id']]);
              $cand_count = $cand_count_stmt->fetchColumn();

              $button_text = 'Register';
              $button_class = 'btn-primary';
              $disabled = '';
              if ($registered && !$voted) {
                  $button_text = 'Vote Now';
              } elseif ($voted) {
                  $button_text = 'Vote Cast';
                  $button_class = 'btn-secondary';
                  $disabled = 'disabled';
              }
              ?>
              <div class="card" style="text-align:left;background:#374151;">
                <h3><?= htmlspecialchars($election['title']) ?></h3>
                <p><?= htmlspecialchars($election['description']) ?></p>
                <p class="gray">Ends: <?= date('M j, Y', strtotime($election['polling_end_date'])) ?> • <?= $cand_count ?> candidates</p>
                <button class="btn <?= $button_class ?>" style="margin-top:10px;" <?= $disabled ?> onclick="window.location.href='elections.php'"><?= $button_text ?></button>
              </div>
              <?php
          }
      }
      ?>
    </div>
  </div>

  <div class="section">
    <h2>Recent Activity</h2>
    <?php
    // Fetch recent votes
    $recent_votes_stmt = $pdo->prepare("
        SELECT v.*, e.title, c.name as candidate_name
        FROM votes v
        JOIN elections e ON v.election_id = e.id
        LEFT JOIN candidates c ON v.candidate_id = c.id
        WHERE v.voter_id = ?
        ORDER BY v.created_at DESC
        LIMIT 5
    ");
    $recent_votes_stmt->execute([$user_id]);
    $recent_votes = $recent_votes_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch recent registrations
    $recent_regs_stmt = $pdo->prepare("
        SELECT r.*, e.title
        FROM election_registrations r
        JOIN elections e ON r.election_id = e.id
        WHERE r.voter_id = ?
        ORDER BY r.registered_at DESC
        LIMIT 5
    ");
    $recent_regs_stmt->execute([$user_id]);
    $recent_regs = $recent_regs_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge and sort by date
    $activities = [];
    foreach ($recent_votes as $vote) {
        $activities[] = [
            'type' => 'vote',
            'title' => $vote['title'],
            'candidate' => $vote['candidate_name'] ?? 'NOTA',
            'date' => $vote['created_at']
        ];
    }
    foreach ($recent_regs as $reg) {
        $activities[] = [
            'type' => 'register',
            'title' => $reg['title'],
            'date' => $reg['registered_at']
        ];
    }
    // Sort by date desc
    usort($activities, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    $activities = array_slice($activities, 0, 5); // Limit to 5

    if (empty($activities)) {
        echo '<p class="gray">No recent activity.</p>';
    } else {
        foreach ($activities as $activity) {
            if ($activity['type'] == 'vote') {
                echo '<div class="recent-item">';
                echo '<p class="green">Vote Cast - ' . htmlspecialchars($activity['title']) . '</p>';
                echo '<p>Your vote for ' . htmlspecialchars($activity['candidate']) . ' has been recorded</p>';
                echo '<p class="gray">' . date('M j, Y at g:i A T', strtotime($activity['date'])) . '</p>';
                echo '</div>';
            } elseif ($activity['type'] == 'register') {
                echo '<div class="recent-item">';
                echo '<p class="blue">Registered for ' . htmlspecialchars($activity['title']) . '</p>';
                echo '<p>You are now eligible to vote in this election</p>';
                echo '<p class="gray">' . date('M j, Y at g:i A T', strtotime($activity['date'])) . '</p>';
                echo '</div>';
            }
        }
    }
    ?>
  </div>

  <div class="section">
    <h2>Security Settings</h2>
    <!-- Security settings content -->
  </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
