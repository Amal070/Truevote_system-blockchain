<?php
require_once "../db.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$message = "";

// Handle vote submission
if (isset($_POST['election_id']) && isset($_POST['candidate_id'])) {
    $election_id = intval($_POST['election_id']);
    $candidate_id = intval($_POST['candidate_id']);

    try {
        // Check if voter is registered for this election
        $reg_check = $pdo->prepare("SELECT * FROM election_registrations WHERE election_id = ? AND voter_id = ?");
        $reg_check->execute([$election_id, $user_id]);
        if ($reg_check->rowCount() == 0) {
            $message = "❌ You are not registered for this election.";
        } else {
            // Check if already voted
            $vote_check = $pdo->prepare("SELECT * FROM votes WHERE election_id = ? AND voter_id = ?");
            $vote_check->execute([$election_id, $user_id]);
            if ($vote_check->rowCount() > 0) {
                $message = "⚠️ You have already voted in this election.";
            } else {
                // Insert vote
                $insert = $pdo->prepare("INSERT INTO votes (election_id, voter_id, candidate_id, voted_at) VALUES (?, ?, ?, NOW())");
                $insert->execute([$election_id, $user_id, $candidate_id]);
                $message = "✅ Your vote has been cast successfully!";
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
} else {
    $message = "Invalid request.";
}

// Redirect back to elections with message
$_SESSION['vote_message'] = $message;
header("Location: elections.php");
exit;
?>
