<?php
session_start();
require_once __DIR__ . "/../db.php";

// Check login for voter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.php");
    exit;
}

// Fetch only published elections (publish_result = 1)
$query = "SELECT * FROM elections 
          WHERE publish_result = '1' 
          ORDER BY polling_end_date DESC";
$stmt = $pdo->query($query);
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Election Results</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.results-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}
.election-card {
    background: #1e293b;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #334155;
}
.election-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #f8fafc;
    margin-bottom: 0.5rem;
}
.candidate-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}
.candidate-card {
    background: #334155;
    border-radius: 0.5rem;
    padding: 1rem;
    border: 1px solid #475569;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
}
.candidate-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}
.candidate-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 0.5rem;
}
.candidate-name {
    font-weight: 600;
    color: #f8fafc;
    margin-bottom: 0.25rem;
}
.candidate-party {
    color: #cbd5e1;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}
.candidate-symbol {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-bottom: 0.5rem;
}
.candidate-votes {
    color: #f8fafc;
    font-weight: 500;
    margin-bottom: 0.5rem;
}
.progress-container {
    width: 30px;
    height: 150px;
    background: #475569;
    border-radius: 0.25rem;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.progress-bar {
    width: 100%;
    background: linear-gradient(180deg, #10b981, #059669);
    border-radius: 0.25rem 0.25rem 0 0;
    transition: height 0.3s ease;
}
.percentage {
    color: #cbd5e1;
    font-size: 0.9rem;
}
</style>
</head>
<body class="bg-gray-900 text-white">

<?php include "includes/header.php"; ?>

<div class="results-container">
    <h1 class="text-3xl font-bold mb-6">Published Election Results</h1>

    <?php if (empty($elections)): ?>
        <p class="text-gray-300">No results published yet. Please check back later.</p>
    <?php endif; ?>

    <?php foreach ($elections as $election): ?>
        <div class="election-card">
            <div class="election-title"><?php echo htmlspecialchars($election['title']); ?></div>

            <?php
            // Fetch candidates for this election
            $cand_query = "SELECT * FROM candidates WHERE election_id = ?";
            $cand_stmt = $pdo->prepare($cand_query);
            $cand_stmt->execute([$election['id']]);
            $candidates = $cand_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Count votes
            $total_votes = 0;
            $candidate_votes = [];
            foreach ($candidates as $candidate) {
                $vote_query = "SELECT COUNT(*) as votes 
                               FROM votes 
                               WHERE candidate_id = ? AND election_id = ? AND vote_count = '1'";
                $vote_stmt = $pdo->prepare($vote_query);
                $vote_stmt->execute([$candidate['id'], $election['id']]);
                $votes = $vote_stmt->fetch()['votes'];
                $candidate_votes[$candidate['id']] = $votes;
                $total_votes += $votes;
            }

            // Prepare candidates with percentages
            $candidates_with_votes = [];
            foreach ($candidates as $candidate) {
                $votes = $candidate_votes[$candidate['id']];
                $percentage = $total_votes > 0 ? round(($votes / $total_votes) * 100, 2) : 0;
                $candidate['votes'] = $votes;
                $candidate['percentage'] = $percentage;
                $candidates_with_votes[] = $candidate;
            }

            // Sort by percentage descending
            usort($candidates_with_votes, function($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });
            ?>

            <div class="candidate-grid">
                <?php foreach ($candidates_with_votes as $candidate): ?>
                    <div class="candidate-card">
                        <div class="candidate-content">
                            <?php if (strtolower($candidate['name']) == 'none of the above'): ?>
                                <div class="candidate-name" style="font-size: 1.5rem; font-weight: bold;"><?php echo htmlspecialchars($candidate['name']); ?></div>
                            <?php else: ?>
                                <img src="<?php echo '../' . htmlspecialchars($candidate['photo']); ?>" alt="Photo" class="candidate-photo">
                                <div class="candidate-name"><?php echo htmlspecialchars($candidate['name']); ?></div>
                                <div class="candidate-party"><?php echo htmlspecialchars($candidate['party_name']); ?></div>
                                <img src="<?php echo '../' . htmlspecialchars($candidate['symbol']); ?>" alt="Symbol" class="candidate-symbol">
                            <?php endif; ?>
                            <div class="candidate-votes" data-votes="<?php echo $candidate['votes']; ?>">Votes: 0</div>
                            <div class="percentage" data-percentage="<?php echo $candidate['percentage']; ?>">0%</div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" data-height="<?php echo $candidate['percentage']; ?>" style="height: 0%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    const voteElements = document.querySelectorAll('.candidate-votes');
    const percentageElements = document.querySelectorAll('.percentage');

    progressBars.forEach(bar => {
        const targetHeight = parseFloat(bar.getAttribute('data-height'));
        let currentHeight = 0;
        const increment = targetHeight / 100;
        const interval = setInterval(() => {
            currentHeight += increment;
            if (currentHeight >= targetHeight) {
                currentHeight = targetHeight;
                clearInterval(interval);
            }
            bar.style.height = currentHeight + '%';
        }, 20);
    });

    voteElements.forEach(el => {
        const targetVotes = parseInt(el.getAttribute('data-votes'));
        let currentVotes = 0;
        const increment = Math.ceil(targetVotes / 100);
        const interval = setInterval(() => {
            currentVotes += increment;
            if (currentVotes >= targetVotes) {
                currentVotes = targetVotes;
                clearInterval(interval);
            }
            el.textContent = 'Votes: ' + currentVotes;
        }, 20);
    });

    percentageElements.forEach(el => {
        const targetPercentage = parseFloat(el.getAttribute('data-percentage'));
        let currentPercentage = 0;
        const increment = targetPercentage / 100;
        const interval = setInterval(() => {
            currentPercentage += increment;
            if (currentPercentage >= targetPercentage) {
                currentPercentage = targetPercentage;
                clearInterval(interval);
            }
            el.textContent = Math.round(currentPercentage) + '%';
        }, 20);
    });
});
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>
