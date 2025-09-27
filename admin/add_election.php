<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_code = $_POST['election_code'];
    $title = $_POST['title'];
    $election_type = $_POST['election_type'];
    $constituency = $_POST['constituency'];
    $description = $_POST['description'];

    $announcement_date = $_POST['announcement_date'];
    $nomination_start_date = $_POST['nomination_start_date'];
    $nomination_end_date = $_POST['nomination_end_date'];
    $scrutiny_date = $_POST['scrutiny_date'];
    $withdrawal_date = $_POST['withdrawal_date'];
    $polling_start_date = $_POST['polling_start_date'];
    $polling_end_date = $_POST['polling_end_date'];
    $counting_date = $_POST['counting_date'];

    if (
        !empty($election_code) && !empty($title) && !empty($election_type) && 
        !empty($constituency) && !empty($announcement_date) &&
        !empty($nomination_start_date) && !empty($nomination_end_date) &&
        !empty($scrutiny_date) && !empty($withdrawal_date) &&
        !empty($polling_start_date) && !empty($polling_end_date) && !empty($counting_date)
    ) {
        $stmt = $pdo->prepare("
            INSERT INTO elections (
                election_code, title, election_type, constituency, description,
                announcement_date, nomination_start_date, nomination_end_date,
                scrutiny_date, withdrawal_date, polling_start_date, polling_end_date, counting_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt->execute([
            $election_code, $title, $election_type, $constituency, $description,
            $announcement_date, $nomination_start_date, $nomination_end_date,
            $scrutiny_date, $withdrawal_date, $polling_start_date, $polling_end_date, $counting_date
        ])) {
            header("Location: view_elections.php?success=Election added successfully");
            exit;
        } else {
            $error = "Failed to add election.";
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Election - TrueVote Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background:#0f172a; color:#cbd5e1; margin:0; padding:0; }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        h2 { text-align:center; font-weight:600; margin-bottom:2rem; color:#f8fafc; }

        /* Card */
        .card { background:#1e293b; padding:2rem; border-radius:0.75rem; box-shadow:0 10px 20px rgba(0,0,0,0.3); }

        /* Scoped Form Styles */
        .form-card .form-label { display:block; font-weight:500; margin-bottom:0.5rem; color:#f8fafc; }
        .form-card input[type="text"],
        .form-card input[type="date"],
        .form-card textarea,
        .form-card select {
            width:100%;
            padding:0.5rem 0.75rem;
            border:1px solid #334155;
            border-radius:0.5rem;
            background:#0f172a;
            color:#f8fafc;
            margin-bottom:1rem;
        }
        .form-card input:focus,
        .form-card textarea:focus,
        .form-card select:focus {
            outline:none;
            border-color:#3b82f6;
            box-shadow:0 0 0 3px rgba(59,130,246,0.3);
        }
        .form-card textarea { resize: vertical; min-height: 80px; }

        .btn-primary {
            background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color:#fff; padding:0.6rem 1.2rem; border:none;
            border-radius:0.5rem; cursor:pointer; font-weight:600;
        }
        .btn-primary:hover { transform: scale(1.05); box-shadow:0 5px 15px rgba(59,130,246,0.5); }

        .alert { background:#ef4444; color:#fff; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem; text-align:center; }
    </style>
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="container">
        <h2>Add Election</h2>

        <?php if (isset($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="card form-card">
            <div>
                <label class="form-label">Election Code *</label>
                <input type="text" name="election_code" required>
            </div>
            <div>
                <label class="form-label">Election Title *</label>
                <input type="text" name="title" required>
            </div>
            <div>
                <label class="form-label">Election Type *</label>
                <select name="election_type" required>
                    <option value="">-- Select --</option>
                    <option value="National">National</option>
                    <option value="State">State</option>
                    <option value="Local">Local</option>
                    <option value="Municipal">Municipal</option>
                    <option value="Organizational">Organizational</option>
                </select>
            </div>
            <div>
                <label class="form-label">Constituency / Region *</label>
                <input type="text" name="constituency" required>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description"></textarea>
            </div>

            <h3 style="color:#93c5fd; margin:1.5rem 0 1rem;">Election Schedule</h3>
            <div>
                <label class="form-label">Announcement Date *</label>
                <input type="date" name="announcement_date" required>
            </div>
            <div>
                <label class="form-label">Nomination Start Date *</label>
                <input type="date" name="nomination_start_date" required>
            </div>
            <div>
                <label class="form-label">Nomination End Date *</label>
                <input type="date" name="nomination_end_date" required>
            </div>
            <div>
                <label class="form-label">Scrutiny Date *</label>
                <input type="date" name="scrutiny_date" required>
            </div>
            <div>
                <label class="form-label">Withdrawal Date *</label>
                <input type="date" name="withdrawal_date" required>
            </div>
            <div>
                <label class="form-label">Polling Start Date *</label>
                <input type="date" name="polling_start_date" required>
            </div>
            <div>
                <label class="form-label">Polling End Date *</label>
                <input type="date" name="polling_end_date" required>
            </div>
            <div>
                <label class="form-label">Counting Date *</label>
                <input type="date" name="counting_date" required>
            </div>

            <button type="submit" class="btn-primary">Add Election</button>
        </form>
    </div>

    <?php include "includes/footer.php"; ?>
</body>
</html>
