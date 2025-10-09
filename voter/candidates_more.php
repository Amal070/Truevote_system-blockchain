<?php
require_once "../db.php";

// Start session and restrict to logged-in voter
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

// Validate candidate ID
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details - <?= htmlspecialchars($candidate['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen font-sans">

<!-- ✅ Proper placement of header -->
<?php include "includes/header.php"; ?>

<div class="max-w-3xl mx-auto bg-gray-800 rounded-2xl shadow-lg p-8 mt-10 mb-10">
    <div class="flex flex-col items-center text-center">
        <?php if ($candidate['photo']): ?>
            <img src="../<?= htmlspecialchars($candidate['photo']) ?>" alt="Photo of <?= htmlspecialchars($candidate['name']) ?>"
                 class="w-36 h-36 rounded-full border-4 border-indigo-500 object-cover mb-4">
        <?php else: ?>
            <img src="https://via.placeholder.com/150?text=No+Photo" alt="No Photo"
                 class="w-36 h-36 rounded-full border-4 border-gray-600 object-cover mb-4">
        <?php endif; ?>

        <h2 class="text-2xl font-bold text-indigo-400"><?= htmlspecialchars($candidate['name']) ?></h2>
        <p class="text-gray-400 text-sm mb-2"><?= htmlspecialchars($candidate['party_name'] ?: 'Independent') ?></p>
    </div>

    <div class="mt-6 space-y-2 text-base leading-relaxed">
        <p><span class="font-semibold text-indigo-300">Candidate No:</span> <?= htmlspecialchars($candidate['id']) ?></p>
        <p><span class="font-semibold text-indigo-300">Gender:</span> <?= htmlspecialchars($candidate['gender']) ?></p>
        <p><span class="font-semibold text-indigo-300">Age:</span> <?= htmlspecialchars($candidate['age'] ?: 'N/A') ?></p>
        <p><span class="font-semibold text-indigo-300">Constituency:</span> <?= htmlspecialchars($candidate['constituency']) ?></p>
        <p><span class="font-semibold text-indigo-300">Election:</span> <?= htmlspecialchars($candidate['election_title']) ?></p>

        <?php if ($candidate['symbol']): ?>
        <p class="flex items-center gap-2">
            <span class="font-semibold text-indigo-300">Symbol:</span>
            <img src="../<?= htmlspecialchars($candidate['symbol']) ?>" alt="Symbol" class="h-10 inline-block">
        </p>
        <?php endif; ?>
    </div>

    <?php if ($candidate['manifesto']): ?>
    <div class="mt-8 bg-gray-900 p-4 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-indigo-400 mb-2">Manifesto</h3>
        <p class="text-gray-300 whitespace-pre-line"><?= nl2br(htmlspecialchars($candidate['manifesto'])) ?></p>
    </div>
    <?php endif; ?>

    <div class="mt-8 flex justify-center">
        <a href="view_candidate.php" 
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition">
            <i class="fas fa-arrow-left"></i> Back to Candidates
        </a>
    </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
