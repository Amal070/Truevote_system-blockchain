<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Get candidate id from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid candidate ID.");
}
$candidate_id = (int)$_GET['id'];

// Fetch candidate details
$stmt = $pdo->prepare("
    SELECT c.*, e.title AS election_title, e.constituency 
    FROM candidates c
    JOIN elections e ON c.election_id = e.id
    WHERE c.id = ?
");
$stmt->execute([$candidate_id]);
$candidate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$candidate) {
    die("Candidate not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Candidate Details - <?= htmlspecialchars($candidate['name']) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#0f172a; font-family:'Inter',sans-serif; color:#f8fafc; margin:0; padding:0; }
        .container { max-width:800px; margin:2rem auto; padding:2rem; background:#1e293b; border-radius:0.75rem; box-shadow:0 4px 15px rgba(0,0,0,0.3); }
        h2 { color:#93c5fd; margin-bottom:1rem; }
        .candidate-photo { text-align:center; margin-bottom:1rem; }
        .candidate-photo img { width:150px; height:150px; border-radius:50%; object-fit:cover; border:4px solid #6366f1; }
        .details { line-height:1.8; font-size:1rem; }
        .details strong { color:#93c5fd; }
        .manifesto { margin-top:1.5rem; padding:1rem; background:#0f172a; border-radius:0.5rem; }
        .back-btn {
            display:inline-block; margin-top:1.5rem; padding:8px 16px; 
            background:#6366f1; color:#fff; border-radius:6px; 
            text-decoration:none; font-size:0.95rem; transition:0.3s;
        }
        .back-btn:hover { background:#4f46e5; }
    </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="container">
    <div class="candidate-photo">
        <?php if ($candidate['photo']): ?>
            <img src="../<?= htmlspecialchars($candidate['photo']) ?>" alt="Photo of <?= htmlspecialchars($candidate['name']) ?>">
        <?php else: ?>
            <img src="https://via.placeholder.com/150?text=No+Photo" alt="No Photo">
        <?php endif; ?>
    </div>

    <h2><?= htmlspecialchars($candidate['name']) ?></h2>

    <div class="details">
        <p><strong>Candidate No:</strong> <?= htmlspecialchars($candidate['id']) ?></p>
        <p><strong>Party:</strong> <?= htmlspecialchars($candidate['party_name'] ?: 'Independent') ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($candidate['gender']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($candidate['age'] ?: 'N/A') ?></p>
        <p><strong>Constituency:</strong> <?= htmlspecialchars($candidate['constituency']) ?></p>
        <p><strong>Election:</strong> <?= htmlspecialchars($candidate['election_title']) ?></p>
        <?php if ($candidate['symbol']): ?>
            <p><strong>Symbol:</strong> 
                <img src="../<?= htmlspecialchars($candidate['symbol']) ?>" alt="Symbol" style="height:40px; vertical-align:middle;">
            </p>
        <?php endif; ?>
    </div>

    <?php if ($candidate['manifesto']): ?>
        <div class="manifesto">
            <h3 style="color:#93c5fd;">Manifesto</h3>
            <p><?= nl2br(htmlspecialchars($candidate['manifesto'])) ?></p>
        </div>
    <?php endif; ?>

    <!-- Bottom Buttons -->
    <div style="display:flex; justify-content:space-between; margin-top:1.5rem;">
        <a href="view_candidates.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Candidates
        </a>
        <a href="edit_candidate.php?id=<?= $candidate['id'] ?>" class="back-btn" style="background:#10b981;">
            <i class="fas fa-edit"></i> Edit Candidate
        </a>
    </div>
</div>


<?php include "includes/footer.php"; ?>
</body>
</html>
