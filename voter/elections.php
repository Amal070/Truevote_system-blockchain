<?php
require_once "../db.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // logged in voter id
$today = date("Y-m-d");

$message = "";

// Handle registration
if (isset($_POST['register_election'])) {
    $election_id = intval($_POST['election_id']);
    try {
        // 👇 changed user_id → voter_id
        $stmt = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id=? AND voter_id=?");
        $stmt->execute([$election_id, $user_id]);

        if ($stmt->rowCount() == 0) {
            $insert = $pdo->prepare("INSERT INTO election_registrations (election_id, voter_id, registered_at) VALUES (?, ?, NOW())");
            $insert->execute([$election_id, $user_id]);
            $message = "✅ Successfully registered for election!";
        } else {
            $message = "⚠️ Already registered!";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch all elections
$stmt = $pdo->query("SELECT * FROM elections ORDER BY announcement_date DESC");
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getElectionStatus($election, $today) {
    if ($today < $election['announcement_date']) return "upcoming"; // Before announcement
    if ($today >= $election['announcement_date'] && $today <= $election['withdrawal_date']) return "registration"; // Registration period
    if ($today >= $election['polling_start_date'] && $today <= $election['polling_end_date']) return "voting"; // Voting period
    if ($today > $election['polling_end_date']) return "past"; // Counting/Results
    return "unknown";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elections - TrueVote</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827;
            color: #d1d5db;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-300 antialiased">
<?php include "includes/header.php"; ?>

<div class="container mx-auto mt-8 p-4">
    <h1 class="text-3xl font-bold mb-8 text-gray-200">Elections</h1>

    <?php if (!empty($message)): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Upcoming Elections -->
    <h2 class="text-2xl font-semibold mt-6 mb-4 text-gray-400">Upcoming Elections</h2>
    <div class="grid md:grid-cols-2 gap-8">
        <?php foreach ($elections as $election): ?>
            <?php if (getElectionStatus($election, $today) == "upcoming"): ?>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-1 text-gray-200"><?= htmlspecialchars($election['title']) ?></h3>
                    <p class="text-sm text-gray-400 mb-2"><?= htmlspecialchars($election['description']) ?></p>
                    <p class="text-gray-300"><strong>Announcement Date:</strong> <?= $election['announcement_date'] ?></p>
                    <span class="inline-block mt-4 text-orange-500 font-semibold text-sm">Coming Soon</span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Registration Period -->
    <h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-400">Registration Open</h2>
    <div class="grid md:grid-cols-2 gap-8">
        <?php foreach ($elections as $election): ?>
            <?php if (getElectionStatus($election, $today) == "registration"): ?>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-1 text-gray-200"><?= htmlspecialchars($election['title']) ?></h3>
                    <p class="text-sm text-gray-400 mb-2"><?= htmlspecialchars($election['description']) ?></p>
                    <p class="text-gray-300"><strong>Registration:</strong> <?= $election['announcement_date'] ?> → <?= $election['withdrawal_date'] ?></p>

                    <?php
                        $check = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id=? AND voter_id=?");
                        $check->execute([$election['id'], $user_id]);
                    ?>
                    <?php if ($check->rowCount() == 0): ?>
                        <form method="post" class="mt-4">
                            <input type="hidden" name="election_id" value="<?= $election['id'] ?>">
                            <button type="submit" name="register_election" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">Register</button>
                        </form>
                    <?php else: ?>
                        <span class="inline-block mt-4 text-green-500 font-semibold text-sm">✅ Registered</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Voting Period -->
    <h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-400">Ongoing Voting</h2>
    <div class="grid md:grid-cols-2 gap-8">
        <?php foreach ($elections as $election): ?>
            <?php if (getElectionStatus($election, $today) == "voting"): ?>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-1 text-gray-200"><?= htmlspecialchars($election['title']) ?></h3>
                    <p class="text-sm text-gray-400 mb-2"><?= htmlspecialchars($election['description']) ?></p>
                    <p class="text-gray-300"><strong>Polling:</strong> <?= $election['polling_start_date'] ?> → <?= $election['polling_end_date'] ?></p>

                    <?php
                        $check = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id=? AND voter_id=?");
                        $check->execute([$election['id'], $user_id]);
                    ?>
                    <?php if ($check->rowCount() > 0): ?>
                        <a href="vote_now.php?election_id=<?= $election['id'] ?>" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">Vote Now</a>
                    <?php else: ?>
                        <span class="inline-block mt-4 text-red-500 font-semibold text-sm">❌ Not Registered</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Past Elections -->
    <h2 class="text-2xl font-semibold mt-10 mb-4 text-gray-400">Past Elections</h2>
    <div class="grid md:grid-cols-2 gap-8">
        <?php foreach ($elections as $election): ?>
            <?php if (getElectionStatus($election, $today) == "past"): ?>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-1 text-gray-200"><?= htmlspecialchars($election['title']) ?></h3>
                    <p class="text-sm text-gray-400 mb-3"><?= htmlspecialchars($election['description']) ?></p>
                    <a href="results.php?election_id=<?= $election['id'] ?>" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-block text-sm font-semibold transition-colors duration-200">View Results</a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>



<?php include "includes/footer.php"; ?>
</body>
</html>
