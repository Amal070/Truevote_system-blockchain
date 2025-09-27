<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM elections WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: view_elections.php?success=Election deleted successfully");
    exit;
}

// Fetch elections
$stmt = $pdo->query("SELECT * FROM elections ORDER BY announcement_date DESC");
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Elections - TrueVote Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family:'Inter',sans-serif; margin:0; padding:0; background:#0f172a; color:#cbd5e1; }

        .container { max-width: 1100px; margin:2rem auto; padding:0 1rem; }
        h2 { text-align:center; font-weight:600; margin-bottom:2rem; color:#f8fafc; }

        .card {
            background:#1e293b;
            padding:1.5rem;
            border-radius:0.75rem;
            box-shadow:0 10px 20px rgba(0,0,0,0.3);
            transition:transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover { transform:translateY(-2px); box-shadow:0 15px 25px rgba(0,0,0,0.5); }

        .btn-primary {
            background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color:#fff; padding:0.6rem 1.2rem;
            border:none; border-radius:0.5rem;
            cursor:pointer; font-weight:600;
            text-decoration:none;
            display:inline-block;
            margin-bottom:1rem;
        }
        .btn-primary:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(59,130,246,0.5); }

        .btn-warning { background:#f59e0b; border:none; color:#fff; padding:0.4rem 0.8rem; border-radius:0.3rem; font-weight:600; }
        .btn-danger { background:#ef4444; border:none; color:#fff; padding:0.4rem 0.8rem; border-radius:0.3rem; font-weight:600; }

        .alert { background:#22c55e; color:#fff; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem; text-align:center; }

        /* Responsive table */
        .table-wrapper {
            overflow-x:auto;
            -webkit-overflow-scrolling: touch;
            border-radius:0.5rem;
        }
        table { width:100%; border-collapse:collapse; min-width:950px; }
        th, td { padding:0.75rem; border-bottom:1px solid #334155; text-align:center; white-space:nowrap; }
        th { background:#334155; color:#f8fafc; }
        tr:hover { background:#0f172a; }

        td.actions { white-space:nowrap; }
    </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="container">
    <h2>Manage Elections</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="card">
        <a href="add_election.php" class="btn-primary"><i class="fas fa-plus"></i> Add Election</a>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Constituency</th>
                        <th>Announcement</th>
                        <th>Nomination</th>
                        <th>Polling</th>
                        <th>Counting</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($elections)): ?>
                        <?php foreach ($elections as $election): ?>
                            <tr>
                                <td><?= $election['id'] ?></td>
                                <td><?= htmlspecialchars($election['election_code']) ?></td>
                                <td><?= htmlspecialchars($election['title']) ?></td>
                                <td><?= htmlspecialchars($election['election_type']) ?></td>
                                <td><?= htmlspecialchars($election['constituency']) ?></td>
                                <td><?= htmlspecialchars($election['announcement_date']) ?></td>
                                <td>
                                    <?= htmlspecialchars($election['nomination_start_date']) ?> → 
                                    <?= htmlspecialchars($election['nomination_end_date']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($election['polling_start_date']) ?> → 
                                    <?= htmlspecialchars($election['polling_end_date']) ?>
                                </td>
                                <td><?= htmlspecialchars($election['counting_date']) ?></td>
                                <td class="actions">
                                    <a href="edit_election.php?id=<?= $election['id'] ?>" class="btn-warning">Edit</a>
                                    <a href="view_elections.php?delete=<?= $election['id'] ?>"
                                       class="btn-danger"
                                       onclick="return confirm('Delete this election? This will also remove its candidates.');">
                                       Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="10">No elections found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
