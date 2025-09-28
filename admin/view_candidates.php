<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Fetch all elections
$electionsStmt = $pdo->query("SELECT * FROM elections ORDER BY announcement_date DESC");
$elections = $electionsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Candidates by Election</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#0f172a; font-family:'Inter',sans-serif; color:#f8fafc; margin:0; padding:0; }
        .container { max-width:1200px; margin:2rem auto; padding:0 1rem; }
        h2 { margin-bottom:1rem; color:#93c5fd; text-align:left; }
        .election-section { margin-bottom:3rem; }
        .candidates-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:1rem; }
        .candidate-card {
            background:#1e293b; padding:1rem; border-radius:0.75rem; box-shadow:0 4px 15px rgba(0,0,0,0.3);
            display:flex; flex-direction:column; align-items:center; text-align:center;
        }
        .candidate-card img { width:100px; height:100px; object-fit:cover; border-radius:50%; margin-bottom:0.5rem; }
        .candidate-name { font-weight:600; margin-bottom:0.25rem; font-size:1.1rem; }
        .candidate-party { font-size:0.9rem; margin-bottom:0.25rem; color:#d1d5db; }
        .view-btn {
            display:inline-block; margin-top:10px; padding:6px 14px; 
            background:#6366f1; color:#fff; border-radius:6px; 
            text-decoration:none; font-size:0.9rem; transition:0.3s;
        }
        .view-btn:hover { background:#4f46e5; }
        .add-candidate {
            display:flex; align-items:center; justify-content:center;
            background:#6366f1; color:#fff; font-size:2rem; border-radius:50%;
            width:60px; height:60px; cursor:pointer; margin:1rem auto; text-decoration:none;
            transition:0.3s;
        }
        .add-candidate:hover { background:#4f46e5; transform:scale(1.1); }
        .no-candidates { color:#94a3b8; font-style:italic; }
    </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="container">
    <?php if (!empty($elections)): ?>
        <?php foreach ($elections as $election): ?>
            <div class="election-section">
                <h2><?= htmlspecialchars($election['title']) ?> (<?= htmlspecialchars($election['constituency']) ?>)</h2>

                <?php
                // Fetch candidates for this election
                $stmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id=? ORDER BY name ASC");
                $stmt->execute([$election['id']]);
                $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (!empty($candidates)): ?>
                    <div class="candidates-grid">
                        <?php foreach ($candidates as $c): ?>
                            <div class="candidate-card">
                                <?php if($c['photo']): ?>
                                 <img src="../uploads/candidates/photos/<?= htmlspecialchars($c['photo']) ?>" 
                                 alt="Photo of <?= htmlspecialchars($c['name']) ?>">
                                <?php else: ?>
                                 <img src="https://via.placeholder.com/100?text=No+Photo" alt="No Photo">
                                <?php endif; ?>


                                <div class="candidate-name"><?= htmlspecialchars($c['name']) ?></div>
                                <div class="candidate-party">Party: <?= htmlspecialchars($c['party_name'] ?: 'Independent') ?></div>
                                <div class="candidate-party">Constituency: <?= htmlspecialchars($election['constituency']) ?></div>

                                <!-- View More Button -->
                                <a href="view_candidate.php?id=<?= $c['id'] ?>" class="view-btn">View More</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-candidates">No candidates yet.</p>
                <?php endif; ?>

                <!-- Add Candidate Button -->
                <a href="add_candidates.php?election_id=<?= $election['id'] ?>" class="add-candidate">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-candidates">No elections found.</p>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
