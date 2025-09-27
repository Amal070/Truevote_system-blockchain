<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Fetch all elections
$elections = $pdo->query("SELECT * FROM elections ORDER BY announcement_date DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all candidates grouped by election_id
$candidatesByElection = [];
$stmt = $pdo->query("SELECT * FROM candidates ORDER BY name ASC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $candidatesByElection[$row['election_id']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Candidates - TrueVote Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; background:#0f172a; margin:0; color:#f8fafc; }
    h2 { text-align:center; font-weight:600; margin:2rem 0; color:#e2e8f0; }

    .container { max-width:1200px; margin:auto; padding:1rem; }

    .election-card {
      background:#1e293b;
      padding:1.5rem;
      margin-bottom:2rem;
      border-radius:12px;
      box-shadow:0 6px 18px rgba(0,0,0,0.4);
    }
    .election-card h3 {
      margin:0 0 1rem;
      color:#93c5fd;
      font-size:1.4rem;
    }
    .election-info {
      color:#cbd5e1;
      margin-bottom:1rem;
      font-size:0.95rem;
    }

    .candidates-grid {
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap:1rem;
    }
    .candidate-card {
      background:#0f172a;
      border:1px solid #334155;
      border-radius:10px;
      padding:1rem;
      text-align:center;
      transition:0.3s;
    }
    .candidate-card:hover {
      transform:translateY(-3px);
      box-shadow:0 8px 16px rgba(0,0,0,0.5);
    }
    .candidate-photo {
      width:100px;
      height:100px;
      border-radius:50%;
      object-fit:cover;
      margin-bottom:0.75rem;
      border:2px solid #3b82f6;
    }
    .candidate-logo {
      width:50px;
      height:50px;
      object-fit:cover;
      margin-top:0.5rem;
    }
    .candidate-name {
      font-weight:600;
      font-size:1.1rem;
      margin:0.25rem 0;
      color:#f1f5f9;
    }
    .candidate-party { color:#38bdf8; font-size:0.9rem; }
    .candidate-constituency { font-size:0.9rem; color:#eab308; }
    .candidate-manifesto {
      margin-top:0.5rem;
      font-size:0.85rem;
      color:#94a3b8;
    }

    .no-candidates {
      font-style:italic;
      color:#94a3b8;
      text-align:center;
      margin:1rem 0;
    }
  </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="container">
  <h2>All Elections & Candidates</h2>

  <?php if (!empty($elections)): ?>
    <?php foreach ($elections as $election): ?>
      <div class="election-card">
        <h3><?= htmlspecialchars($election['title']) ?> 
          <small style="color:#cbd5e1;">(<?= htmlspecialchars($election['election_type']) ?>)</small>
        </h3>
        <div class="election-info">
          <strong>Code:</strong> <?= htmlspecialchars($election['election_code']) ?> | 
          <strong>Constituency:</strong> <?= htmlspecialchars($election['constituency']) ?> | 
          <strong>Announcement:</strong> <?= htmlspecialchars($election['announcement_date']) ?>
        </div>

        <?php if (!empty($candidatesByElection[$election['id']])): ?>
          <div class="candidates-grid">
            <?php foreach ($candidatesByElection[$election['id']] as $cand): ?>
              <div class="candidate-card">
                <?php if ($cand['photo']): ?>
                  <img src="../<?= htmlspecialchars($cand['photo']) ?>" class="candidate-photo" alt="Photo">
                <?php else: ?>
                  <img src="https://via.placeholder.com/100" class="candidate-photo" alt="No Photo">
                <?php endif; ?>

                <div class="candidate-name"><?= htmlspecialchars($cand['name']) ?></div>
                <div class="candidate-party"><?= htmlspecialchars($cand['party_name']) ?></div>
                <!-- <div class="candidate-constituency">Constituency: <?= htmlspecialchars($cand['constituency']) ?></div> -->

                <?php if ($cand['logo']): ?>
                  <div><img src="../<?= htmlspecialchars($cand['logo']) ?>" class="candidate-logo" alt="Logo"></div>
                <?php endif; ?>

                <?php if ($cand['manifesto']): ?>
                  <div class="candidate-manifesto"><?= htmlspecialchars($cand['manifesto']) ?></div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-candidates">No candidates added yet for this election.</div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No elections found.</p>
  <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
