<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Get election ID
if (!isset($_GET['id'])) {
    header("Location: view_elections.php?error=Election not found");
    exit;
}

$id = (int) $_GET['id'];

// Fetch election details
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ?");
$stmt->execute([$id]);
$election = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$election) {
    header("Location: view_elections.php?error=Election not found");
    exit;
}

// Update election
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE elections SET 
        election_code = ?, 
        title = ?, 
        election_type = ?, 
        constituency = ?, 
        announcement_date = ?, 
        nomination_start_date = ?, 
        nomination_end_date = ?, 
        scrutiny_date = ?, 
        withdrawal_date = ?, 
        polling_start_date = ?, 
        polling_end_date = ?, 
        counting_date = ? 
        WHERE id = ?");

    $stmt->execute([
        $_POST['election_code'],
        $_POST['title'],
        $_POST['election_type'],
        $_POST['constituency'],
        $_POST['announcement_date'],
        $_POST['nomination_start_date'],
        $_POST['nomination_end_date'],
        $_POST['scrutiny_date'],
        $_POST['withdrawal_date'],
        $_POST['polling_start_date'],
        $_POST['polling_end_date'],
        $_POST['counting_date'],
        $id
    ]);

    header("Location: view_elections.php?success=Election updated successfully");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Election</title>
    <style>
        body { font-family:'Inter',sans-serif; background:#0f172a; color:#cbd5e1; }
        .container { max-width:800px; margin:2rem auto; padding:1rem; }
        h2 { text-align:center; color:#f8fafc; }
        .card { background:#1e293b; padding:1.5rem; border-radius:0.75rem; box-shadow:0 8px 20px rgba(0,0,0,0.4); }
        label { font-weight:600; display:block; margin-top:1rem; }
        input, select { width:100%; padding:0.6rem; margin-top:0.4rem; border-radius:0.5rem; border:none; background:#334155; color:#f8fafc; }
        input:focus { outline:2px solid #3b82f6; }
        .btn-primary { margin-top:1.5rem; background:linear-gradient(135deg, #3b82f6, #1d4ed8); color:#fff; padding:0.7rem 1.2rem; border:none; border-radius:0.5rem; font-weight:600; cursor:pointer; }
        .btn-primary:hover { transform:scale(1.05); }
    </style>
</head>
<body>
<?php include "includes/header.php"; ?>
<div class="container">
    <h2>Edit Election</h2>
    <div class="card">
        <form method="POST">
            <label>Election Code</label>
            <input type="text" name="election_code" value="<?= htmlspecialchars($election['election_code']) ?>" required>

            <label>Election Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($election['title']) ?>" required>

            <label>Election Type</label>
            <select name="election_type" required>
                <option value="National" <?= $election['election_type']=="National"?"selected":"" ?>>National</option>
                <option value="State" <?= $election['election_type']=="State"?"selected":"" ?>>State</option>
                <option value="Local" <?= $election['election_type']=="Local"?"selected":"" ?>>Local</option>
                <option value="Municipal" <?= $election['election_type']=="Municipal"?"selected":"" ?>>Municipal</option>
                <option value="Organizational" <?= $election['election_type']=="Organizational"?"selected":"" ?>>Organizational</option>
            </select>

            <label>Constituency / Region</label>
            <input type="text" name="constituency" value="<?= htmlspecialchars($election['constituency']) ?>" required>

            <label>Announcement Date</label>
            <input type="date" name="announcement_date" value="<?= $election['announcement_date'] ?>" required>

            <label>Nomination Start Date</label>
            <input type="date" name="nomination_start_date" value="<?= $election['nomination_start_date'] ?>" required>

            <label>Nomination End Date</label>
            <input type="date" name="nomination_end_date" value="<?= $election['nomination_end_date'] ?>" required>

            <label>Scrutiny Date</label>
            <input type="date" name="scrutiny_date" value="<?= $election['scrutiny_date'] ?>" required>

            <label>Withdrawal Date</label>
            <input type="date" name="withdrawal_date" value="<?= $election['withdrawal_date'] ?>" required>

            <label>Polling Start Date</label>
            <input type="date" name="polling_start_date" value="<?= $election['polling_start_date'] ?>" required>

            <label>Polling End Date</label>
            <input type="date" name="polling_end_date" value="<?= $election['polling_end_date'] ?>" required>

            <label>Counting Date</label>
            <input type="date" name="counting_date" value="<?= $election['counting_date'] ?>" required>

            <button type="submit" class="btn-primary">Update Election</button>
        </form>
    </div>
</div>
<?php include "includes/footer.php"; ?>
</body>
</html>
