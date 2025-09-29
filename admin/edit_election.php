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

// Validate ID
if (!isset($_GET['id'])) {
    header("Location: view_elections.php?error=Election not found");
    exit;
}
$id = (int) $_GET['id'];

// Fetch election
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ?");
$stmt->execute([$id]);
$election = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$election) {
    header("Location: view_elections.php?error=Election not found");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_code           = $_POST['election_code'];
    $title                   = $_POST['title'];
    $election_type           = $_POST['election_type'];
    // constituency name only
    $constituency            = $_POST['constituency']; 
    $description             = $_POST['description'];
    $announcement_date       = $_POST['announcement_date'];
    $registration_start_date = $_POST['registration_start_date'];
    $registration_end_date   = $_POST['registration_end_date'];
    $polling_start_date      = $_POST['polling_start_date'];
    $polling_end_date        = $_POST['polling_end_date'];

    $stmt = $pdo->prepare("
        UPDATE elections SET
            election_code = ?, title = ?, election_type = ?, constituency = ?, description = ?,
            announcement_date = ?, registration_start_date = ?, registration_end_date = ?,
            polling_start_date = ?, polling_end_date = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $election_code, $title, $election_type, $constituency, $description,
        $announcement_date, $registration_start_date, $registration_end_date,
        $polling_start_date, $polling_end_date, $id
    ]);

    header("Location: view_elections.php?success=Election updated successfully");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Election - TrueVote Admin Panel</title>
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
</style>
</head>
<body>
<?php include "includes/header.php"; ?>
<div class="container">
<h2>Edit Election</h2>

<form method="POST" class="card">
    <label class="form-label">Election Code *</label>
    <input type="text" name="election_code" value="<?= htmlspecialchars($election['election_code']) ?>" required>

    <label class="form-label">Election Title *</label>
    <input type="text" name="title" value="<?= htmlspecialchars($election['title']) ?>" required>

    <label class="form-label">Election Type *</label>
    <select name="election_type" id="election_type" required>
        <option value="">-- Select --</option>
        <option value="Lok Sabha Elections (National)" <?= $election['election_type']=="Lok Sabha Elections (National)"?"selected":"" ?>>Lok Sabha Elections (National)</option>
        <option value="State Legislative Assembly Elections (Vidhan Sabha)" <?= $election['election_type']=="State Legislative Assembly Elections (Vidhan Sabha)"?"selected":"" ?>>State Legislative Assembly Elections (Vidhan Sabha)</option>
    </select>

    <div id="constituency-wrapper"></div>

    <label class="form-label">Description</label>
    <textarea name="description"><?= htmlspecialchars($election['description']) ?></textarea>

    <h3 style="color:#93c5fd; margin:1.5rem 0 1rem;">Election Schedule</h3>
    <label class="form-label">Announcement Date *</label>
    <input type="date" name="announcement_date" value="<?= $election['announcement_date'] ?>" required>

    <label class="form-label">Registration Start Date *</label>
    <input type="date" name="registration_start_date" value="<?= $election['registration_start_date'] ?>" required>

    <label class="form-label">Registration End Date *</label>
    <input type="date" name="registration_end_date" value="<?= $election['registration_end_date'] ?>" required>

    <label class="form-label">Polling Start Date *</label>
    <input type="date" name="polling_start_date" value="<?= $election['polling_start_date'] ?>" required>

    <label class="form-label">Polling End Date *</label>
    <input type="date" name="polling_end_date" value="<?= $election['polling_end_date'] ?>" required>

    <button type="submit" class="btn-primary">Update Election</button>
</form>
</div>

<script>
const nationalData = <?php echo json_encode($national_json); ?>;
const stateData    = <?php echo json_encode($state_json); ?>;
const existingType = <?= json_encode($election['election_type']); ?>;
const existingConstituency = <?= json_encode($election['constituency']); ?>;

function renderFields(type) {
    const wrapper = document.getElementById("constituency-wrapper");
    wrapper.innerHTML = "";

    if (type === "Lok Sabha Elections (National)") {
        // Only constituency dropdown
        let label = document.createElement("label");
        label.className = "form-label";
        label.textContent = "Constituency *";
        wrapper.appendChild(label);

        let select = document.createElement("select");
        select.name = "constituency"; select.required = true;
        select.innerHTML = '<option value="">-- Select Constituency --</option>';
        for (let stateName in nationalData) {
            nationalData[stateName].forEach(c => {
                select.innerHTML += `<option value="${c}" ${c===existingConstituency?'selected':''}>${c}</option>`;
            });
        }
        wrapper.appendChild(select);

    } else if (type === "State Legislative Assembly Elections (Vidhan Sabha)") {
        // State select
        let stateLabel = document.createElement("label");
        stateLabel.className = "form-label";
        stateLabel.textContent = "State *";
        wrapper.appendChild(stateLabel);

        let stateSelect = document.createElement("select");
        stateSelect.innerHTML = '<option value="">-- Select State --</option>';
        for (let st in stateData) {
            stateSelect.innerHTML += `<option value="${st}">${st}</option>`;
        }
        wrapper.appendChild(stateSelect);

        // District select
        let distLabel = document.createElement("label");
        distLabel.className = "form-label";
        distLabel.textContent = "District *";
        wrapper.appendChild(distLabel);

        let districtSelect = document.createElement("select");
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        wrapper.appendChild(districtSelect);

        // Constituency select
        let consLabel = document.createElement("label");
        consLabel.className = "form-label";
        consLabel.textContent = "Constituency *";
        wrapper.appendChild(consLabel);

        let constSelect = document.createElement("select");
        constSelect.name = "constituency"; constSelect.required = true;
        constSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
        wrapper.appendChild(constSelect);

        // Prefill if existing
        // we only stored constituency name, so can't pre-select state/district automatically 
        // (admin must reselect) but we can still set constituency name in dropdown when chosen.

        stateSelect.addEventListener("change", function() {
            const st = this.value;
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            constSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
            if (st && stateData[st]) {
                for (let dist in stateData[st]) {
                    districtSelect.innerHTML += `<option value="${dist}">${dist}</option>`;
                }
            }
        });

        districtSelect.addEventListener("change", function() {
            const dist = this.value;
            const st = stateSelect.value;
            constSelect.innerHTML = '<option value="">-- Select Constituency --</option>';
            if (st && dist && stateData[st][dist]) {
                stateData[st][dist].forEach(c => {
                    constSelect.innerHTML += `<option value="${c}" ${c===existingConstituency?'selected':''}>${c}</option>`;
                });
            }
        });
    }
}

document.getElementById("election_type").addEventListener("change", function() {
    renderFields(this.value);
});
renderFields(existingType);
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>

