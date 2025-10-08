<?php
require_once "../db.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch voting history
    $stmt = $pdo->prepare("
        SELECT v.*, e.title as election_title, c.name as candidate_name, c.party_name
        FROM votes v
        JOIN elections e ON v.election_id = e.id
        JOIN candidates c ON v.candidate_id = c.id
        WHERE v.voter_id = ?
        ORDER BY v.vote_id DESC
    ");
    $stmt->execute([$user_id]);
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $votes = [];
    $error = "Error fetching voting history: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voting History - TrueVote</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #111827;
      color: white;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .container {padding: 20px; flex: 1;}
    .section {background: #1f2937; border-radius: 16px; padding: 20px; margin-top: 20px;}
    .table {width: 100%; border-collapse: collapse; margin-top: 20px;}
    .table th, .table td {padding: 12px; text-align: left; border-bottom: 1px solid #374151;}
    .table th {
      background: #374151;
      color: #9ca3af;
      text-transform: uppercase;
      font-size: 12px;
      font-weight: 600;
    }
    .table td {color: white;}
    .table tr:hover {background: #374151;}
    .btn {
      padding: 10px 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      margin-right: 10px;
      text-decoration: none;
      display: inline-block;
    }
    .btn-primary {background: #2563eb; color: white;}
    .btn-primary:hover {background: #1d4ed8;}
    .tag {
      display: inline-block;
      background: #1d4ed8;
      padding: 5px 12px;
      border-radius: 999px;
      font-size: 14px;
      margin-right: 8px;
      color: white;
    }
    .gray {color: #9ca3af;}
    .green {color: #22c55e;}
    .blue {color: #3b82f6;}
    .purple {color: #a855f7;}
    .center {text-align: center;}
    .py-12 {padding-top: 3rem; padding-bottom: 3rem;}
    .mb-4 {margin-bottom: 1rem;}
    .text-4xl {font-size: 2.25rem;}
    .text-xl {font-size: 1.25rem;}
    .font-semibold {font-weight: 600;}
    .text-3xl {font-size: 1.875rem;}
    .font-bold {font-weight: 700;}
    .mb-6 {margin-bottom: 1.5rem;}
    .mb-2 {margin-bottom: 0.5rem;}
    .mt-4 {margin-top: 1rem;}
    .overflow-x-auto {overflow-x: auto;}
    .whitespace-nowrap {white-space: nowrap;}
    .font-mono {font-family: monospace;}
    .px-2 {padding-left: 0.5rem; padding-right: 0.5rem;}
    .py-1 {padding-top: 0.25rem; padding-bottom: 0.25rem;}
    .rounded {border-radius: 0.25rem;}
    /* Fixed color contrast for transaction hash */
    .bg-gray-100 {
      background: #1f2937;       /* dark gray to match theme */
      color: #93c5fd;            /* light blue text for visibility */
      border: 1px solid #374151; /* subtle border */
      font-weight: 500;
    }
    .inline-flex {display: inline-flex;}
    .leading-5 {line-height: 1.25rem;}
    .rounded-full {border-radius: 9999px;}
    .bg-green-100 {background: #065f46;}
    .text-green-800 {color: #a7f3d0;}
    .mr-1 {margin-right: 0.25rem;}
  </style>
</head>
<body>

<?php include "includes/header.php"; ?>

<div class="container">
  <h1 class="text-3xl font-bold mb-6">
    <i class="fas fa-history blue"></i> Voting History
  </h1>

  <?php if (isset($error)): ?>
    <div class="section" style="background:#dc2626;color:white;">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($votes)): ?>
    <div class="section center py-12">
      <i class="fas fa-vote-yea text-4xl gray mb-4"></i>
      <h2 class="text-xl font-semibold mb-2">No Voting History</h2>
      <p class="gray">You haven't cast any votes yet.</p>
      <a href="elections.php" class="btn btn-primary mt-4">
        <i class="fas fa-plus"></i> Vote Now
      </a>
    </div>
  <?php else: ?>
    <div class="section">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>Election</th>
              <th>Candidate</th>
              <th>Party</th>
              <th>Transaction Hash</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($votes as $vote): ?>
              <tr>
                <td><?php echo htmlspecialchars($vote['election_title']); ?></td>
                <td><?php echo htmlspecialchars($vote['candidate_name']); ?></td>
                <td class="gray"><?php echo htmlspecialchars($vote['party_name'] ?? 'N/A'); ?></td>
                <td class="font-mono">
                  <?php if (!empty($vote['transaction_hash'])): ?>
                    <span class="bg-gray-100 px-2 py-1 rounded" title="<?php echo htmlspecialchars($vote['transaction_hash']); ?>">
                      <?php echo htmlspecialchars(substr($vote['transaction_hash'], 0, 10)) . '...'; ?>
                    </span>
                  <?php else: ?>
                    <span class="gray">N/A</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="tag green">
                    <i class="fas fa-check-circle mr-1"></i> Verified
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>

