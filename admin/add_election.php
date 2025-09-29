<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
require_once "../db.php";

// Load JSON data
$national_json = json_decode(file_get_contents(__DIR__ . "/../includes/constituencies/national.json"), true);
$state_json    = json_decode(file_get_contents(__DIR__ . "/../includes/constituencies/state.json"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_code           = $_POST['election_code'];
    $title                   = $_POST['title'];
    $election_type           = $_POST['election_type'];
    $district                = isset($_POST['district']) ? $_POST['district'] : null;
    $constituency            = isset($_POST['constituency']) ? $_POST['constituency'] : null;
    $description             = $_POST['description'];
    $announcement_date       = $_POST['announcement_date'];
    $registration_start_date = $_POST['registration_start_date'];
    $registration_end_date   = $_POST['registration_end_date'];
    $polling_start_date      = $_POST['polling_start_date'];
    $polling_end_date        = $_POST['polling_end_date'];

    $valid = !empty($election_code) && !empty($title) && !empty($election_type) &&
             !empty($announcement_date) && !empty($registration_start_date) && 
             !empty($registration_end_date) && !empty($polling_start_date) && !empty($polling_end_date);

    // For Lok Sabha: constituency required
    // For State: district + constituency required
    if ($election_type === "Lok Sabha Elections (National)") {
        $valid = $valid && !empty($constituency);
    } elseif ($election_type === "State Legislative Assembly Elections (Vidhan Sabha)") {
        $valid = $valid && !empty($district) && !empty($constituency);
    }

    if ($valid) {
        $stmt = $pdo->prepare("
            INSERT INTO elections (
                election_code, title, election_type, constituency, description,
                announcement_date, registration_start_date, registration_end_date,
                polling_start_date, polling_end_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([
            $election_code, $title, $election_type, $constituency, $description,
            $announcement_date, $registration_start_date, $registration_end_date,
            $polling_start_date, $polling_end_date
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
        body { font-family: 'Inter', sans-serif; background:#0f172a; color:#cbd5e1; margin:0; padding:0; }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        h2 { text-align:center; font-weight:600; margin-bottom:2rem; color:#f8fafc; }
        .card { background:#1e293b; padding:2rem; border-radius:0.75rem; box-shadow:0 10px 20px rgba(0,0,0,0.3); }
        .form-label { display:block; font-weight:500; margin-bottom:0.5rem; color:#f8fafc; }
        input, select, textarea {
            width:100%; padding:0.5rem 0.75rem; border:1px solid #334155;
            border-radius:0.5rem; background:#0f172a; color:#f8fafc; margin-bottom:1rem;
        }
        input:focus, textarea:focus, select:focus {
            outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.3);
        }
        .btn-primary { background:linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%);
            color:#fff; padding:0.6rem 1.2rem; border:none; border-radius:0.5rem;
            cursor:pointer; font-weight:600; }
        .btn-primary:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(59,130,246,0.5); }
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

    <form method="POST" class="card">
        <label class="form-label">Election Code *</label>
        <input type="text" name="election_code" required>

        <label class="form-label">Election Title *</label>
        <input type="text" name="title" required>

        <label class="form-label">Election Type *</label>
        <select name="election_type" id="election_type" required>
            <option value="">-- Select --</option>
            <option value="Lok Sabha Elections (National)">Lok Sabha Elections (National)</option>
            <option value="State Legislative Assembly Elections (Vidhan Sabha)">State Legislative Assembly Elections (Vidhan Sabha)</option>
        </select>

        <!-- District field (only for state elections) -->
        <div id="district-container"></div>

        <!-- Constituency field -->
        <div id="constituency-container"></div>

        <label class="form-label">Description</label>
        <textarea name="description"></textarea>

        <h3 style="color:#93c5fd; margin:1.5rem 0 1rem;">Election Schedule</h3>
        <label class="form-label">Announcement Date *</label>
        <input type="date" name="announcement_date" required>

        <label class="form-label">Registration Start Date *</label>
        <input type="date" name="registration_start_date" required>

        <label class="form-label">Registration End Date *</label>
        <input type="date" name="registration_end_date" required>

        <label class="form-label">Polling Start Date *</label>
        <input type="date" name="polling_start_date" required>

        <label class="form-label">Polling End Date *</label>
        <input type="date" name="polling_end_date" required>

        <button type="submit" class="btn-primary">Add Election</button>
    </form>
</div>

<script>
const nationalData = <?php echo json_encode($national_json); ?>;
const stateData    = <?php echo json_encode($state_json); ?>;

const electionTypeSelect = document.getElementById("election_type");
const districtContainer  = document.getElementById("district-container");
const constituencyContainer = document.getElementById("constituency-container");

electionTypeSelect.addEventListener("change", function() {
    const type = this.value;
    districtContainer.innerHTML = "";
    constituencyContainer.innerHTML = "";

    if (type === "Lok Sabha Elections (National)") {
        // Only constituency dropdown from national.json
        let consLabel = document.createElement("label");
        consLabel.className = "form-label";
        consLabel.textContent = "Constituency *";
        constituencyContainer.appendChild(consLabel);

        let consSelect = document.createElement("select");
        consSelect.name = "constituency";
        consSelect.required = true;
        consSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
        for (let stateName in nationalData) {
            nationalData[stateName].forEach(c => {
                consSelect.innerHTML += `<option value="${c}">${c}</option>`;
            });
        }
        constituencyContainer.appendChild(consSelect);

    } else if (type === "State Legislative Assembly Elections (Vidhan Sabha)") {
        // District dropdown from state.json keys
        let distLabel = document.createElement("label");
        distLabel.className = "form-label";
        distLabel.textContent = "District *";
        districtContainer.appendChild(distLabel);

        let districtSelect = document.createElement("select");
        districtSelect.name = "district";
        districtSelect.required = true;
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        for (let district in stateData["Kerala"]) {
            districtSelect.innerHTML += `<option value="${district}">${district}</option>`;
        }
        districtContainer.appendChild(districtSelect);

        let consLabel = document.createElement("label");
        consLabel.className = "form-label";
        consLabel.textContent = "Constituency *";
        constituencyContainer.appendChild(consLabel);

        let consSelect = document.createElement("select");
        consSelect.name = "constituency";
        consSelect.required = true;
        consSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
        constituencyContainer.appendChild(consSelect);

        districtSelect.addEventListener("change", function() {
            const district = this.value;
            consSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
            if (district && stateData["Kerala"][district]) {
                stateData["Kerala"][district].forEach(c => {
                    consSelect.innerHTML += `<option value="${c}">${c}</option>`;
                });
            }
        });
    }
});
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>
