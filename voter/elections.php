<?php
require_once "../db.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // logged in voter id
$today = date("Y-m-d");

// Fetch voter's data
$voter_stmt = $pdo->prepare("SELECT full_name, father_name, gender, dob, address, state, district, constituency, email, phone FROM users WHERE user_id = ?");
$voter_stmt->execute([$user_id]);
$voter = $voter_stmt->fetch(PDO::FETCH_ASSOC);

if (!$voter) {
    header("Location: profile.php");
    exit;
}

if (!$voter['constituency']) {
    die("Please update your profile with constituency information to view elections.");
}

$voter_constituency = $voter['constituency'];

$message = "";
$vote_message = $_SESSION['vote_message'] ?? "";
unset($_SESSION['vote_message']);

// Handle registration
if (isset($_POST['register_election'])) {
    $election_id = intval($_POST['election_id']);
    try {
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

// Fetch voter's elections by constituency
$stmt = $pdo->prepare("SELECT * FROM elections WHERE constituency = ? ORDER BY announcement_date DESC");
$stmt->execute([$voter_constituency]);
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getElectionStatus($election, $today) {
    if ($today < $election['registration_start_date']) return "upcoming";
    if ($today >= $election['registration_start_date'] && $today <= $election['registration_end_date']) return "registration";
    if ($today >= $election['polling_start_date'] && $today <= $election['polling_end_date']) return "voting";
    if ($today > $election['polling_end_date']) return "past";
    return "unknown";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elections - TrueVote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

    <?php if (!empty($vote_message)): ?>
        <div class="bg-blue-100 text-blue-800 p-3 rounded mb-4">
            <?= htmlspecialchars($vote_message) ?>
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
                    <p class="text-gray-300"><strong>Registration Starts:</strong> <?= $election['registration_start_date'] ?></p>
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
                    <p class="text-gray-300"><strong>Registration:</strong> <?= $election['registration_start_date'] ?> → <?= $election['registration_end_date'] ?></p>

                    <?php
                        $check = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id=? AND voter_id=?");
                        $check->execute([$election['id'], $user_id]);
                    ?>
                    <?php if ($check->rowCount() > 0): ?>
                        <span class="inline-block mt-4 text-green-500 font-semibold text-sm">✅ Registered</span>
                    <?php elseif ($today > $election['registration_end_date']): ?>
                        <span class="inline-block mt-4 text-red-500 font-semibold text-sm">Registration Ended</span>
                    <?php else: ?>
                        <button type="button" onclick="openModal(<?= $election['id'] ?>, '<?= addslashes(htmlspecialchars($election['title'])) ?>')" class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">Register</button>
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
                        <?php
                        // Fetch candidates and ensure NOTA (id=1) comes last
                        $cand_stmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id = ? ORDER BY (id = 1) ASC, id ASC");
                        $cand_stmt->execute([$election['id']]);
                        $candidates = $cand_stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div id="candidates-<?= $election['id'] ?>" class="hidden flex flex-col items-center">
                            <?php foreach ($candidates as $candidate): ?>
                                <div class="w-3/4 bg-gray-700 p-4 rounded-lg flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-gray-200 font-semibold"><?= htmlspecialchars($candidate['name']) ?></p>
                                            <!-- <p class="text-gray-400 text-sm">Party: <?= htmlspecialchars($candidate['party_name'] ?? 'Independent') ?></p> -->
                                        </div>
                                        <?php if (!empty($candidate['symbol'])): ?>
                                            <img src="../<?= htmlspecialchars($candidate['symbol']) ?>" alt="Symbol" class="w-12 h-12 ml-6">
                                        <?php endif; ?>
                                    </div>
                                    <form method="post" action="cast_vote.php" class="ml-6">
                                        <input type="hidden" name="election_id" value="<?= $election['id'] ?>">
                                        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
                                        <?php if ($candidate['id'] == 1): ?>
                                            <!-- NOTA Button -->
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                NOTA
                                            </button>
                                        <?php else: ?>
                                            <!-- Normal Candidate -->
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                Vote
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" onclick="openVoteModal(<?= $election['id'] ?>)" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">Vote Now</button>
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

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4 text-gray-200">Confirm Registration</h3>
        <p class="mb-4 text-gray-300">You want to register for this election: <span id="modal-title" class="font-semibold"></span></p>
        <h4 class="text-lg font-semibold mb-2 text-gray-200">Your Details:</h4>
        <ul class="mb-4 text-sm text-gray-300">
            <li><strong>Full Name:</strong> <?= htmlspecialchars($voter['full_name']) ?></li>
            <li><strong>Father's Name:</strong> <?= htmlspecialchars($voter['father_name'] ?? 'N/A') ?></li>
            <li><strong>Gender:</strong> <?= htmlspecialchars($voter['gender'] ?? 'N/A') ?></li>
            <li><strong>Date of Birth:</strong> <?= htmlspecialchars($voter['dob'] ?? 'N/A') ?></li>
            <li><strong>Address:</strong> <?= htmlspecialchars($voter['address'] ?? 'N/A') ?></li>
            <li><strong>State:</strong> <?= htmlspecialchars($voter['state'] ?? 'N/A') ?></li>
            <li><strong>District:</strong> <?= htmlspecialchars($voter['district'] ?? 'N/A') ?></li>
            <li><strong>Constituency:</strong> <?= htmlspecialchars($voter['constituency']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($voter['email']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($voter['phone'] ?? 'N/A') ?></li>
        </ul>
        <form method="post" class="flex space-x-4">
            <input type="hidden" name="election_id" id="modal-election-id">
            <button type="submit" name="register_election" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">Register</button>
            <button type="button" onclick="closeModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">Cancel</button>
        </form>
    </div>
</div>

<!-- Vote Modal -->
<div id="vote-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-6xl w-full mx-4 max-h-screen overflow-y-auto">
        <h3 class="text-xl font-bold mb-4 text-gray-200">Vote for Candidates</h3>
        <div id="vote-modal-content" class="flex flex-col items-center"></div>
        <button type="button" onclick="closeVoteModal()" class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">Close</button>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4 text-gray-200">Confirm Vote</h3>
        <p id="confirm-message" class="mb-4 text-gray-300"></p>
        <div class="flex space-x-4">
            <button id="confirm-yes" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">Yes</button>
            <button id="confirm-no" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">No</button>
        </div>
    </div>
</div>

<script>
function openModal(electionId, title) {
    document.getElementById('modal-election-id').value = electionId;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function openVoteModal(electionId) {
    const candidatesHtml = document.getElementById('candidates-' + electionId).innerHTML;
    document.getElementById('vote-modal-content').innerHTML = candidatesHtml;
    document.getElementById('vote-modal').classList.remove('hidden');

    // Add event listeners to vote buttons for confirmation
    const voteButtons = document.querySelectorAll('#vote-modal-content button[type="submit"]');
    voteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const candidateId = form.querySelector('input[name="candidate_id"]').value;
            let message = '';
            if (candidateId == 1) {
                message = 'You want to make vote NOTA?';
            } else {
                const candidateName = form.previousElementSibling.querySelector('p.font-semibold').textContent;
                message = `You want to vote for ${candidateName}?`;
            }
            openConfirmModal(message, form);
        });
    });
}

function closeVoteModal() {
    document.getElementById('vote-modal').classList.add('hidden');
}

function openConfirmModal(message, form) {
    document.getElementById('confirm-message').textContent = message;
    document.getElementById('confirm-modal').classList.remove('hidden');
    document.getElementById('confirm-yes').onclick = function() {
        form.submit();
    };
    document.getElementById('confirm-no').onclick = function() {
        closeConfirmModal();
    };
</script>

</body>
</html>
