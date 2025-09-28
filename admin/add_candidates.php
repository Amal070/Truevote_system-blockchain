<?php
require_once "../db.php";
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = $_POST['name'];
    $gender       = $_POST['gender'];
    $party_name   = $_POST['party_name'];
    $age          = !empty($_POST['age']) ? (int)$_POST['age'] : null;
    $manifesto    = $_POST['manifesto'];
    $election_id  = (int)$_POST['election_id'];

    // Validate election_id
    if ($election_id <= 0) {
        header("Location: view_candidates.php?error=Invalid election ID");
        exit;
    }

    // Check if election exists
    $stmt_check = $pdo->prepare("SELECT id FROM elections WHERE id = ?");
    $stmt_check->execute([$election_id]);
    if (!$stmt_check->fetch()) {
        header("Location: view_candidates.php?error=Election not found");
        exit;
    }

    // File upload paths
    $photoPath = null;
    $symbolPath = null;

    // Candidate Photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = time() . "_" . basename($_FILES['photo']['name']);
        $photoPathAbs = "../uploads/candidates/photos/" . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPathAbs);
        $photoPath = "uploads/candidates/photos/" . $photoName; // save relative path
    }

    // Party Symbol upload
    if (isset($_FILES['symbol']) && $_FILES['symbol']['error'] === UPLOAD_ERR_OK) {
        $symbolName = time() . "_" . basename($_FILES['symbol']['name']);
        $symbolPathAbs = "../uploads/candidates/symbols/" . $symbolName;
        move_uploaded_file($_FILES['symbol']['tmp_name'], $symbolPathAbs);
        $symbolPath = "uploads/candidates/symbols/" . $symbolName;
    }

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO candidates 
        (election_id, candidate_code, name, gender, party_name, symbol, age, photo, manifesto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $election_id,
        uniqid("CAND_"),
        $name,
        $gender,
        $party_name,
        $symbolPath,
        $age,
        $photoPath,
        $manifesto
    ]);

    header("Location: view_candidates.php?success=Candidate added successfully");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Candidate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0f172a;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #f8fafc;
    }
    h3 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
      color: #e2e8f0;
    }
    .card {
      background-color: #1e293b;
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      padding: 2rem;
      max-width: 700px;
      margin: 2rem auto;
      width: 95%;
    }
    .form-label {
      font-weight: 500;
      color: #e2e8f0;
    }
    .form-control, .form-select, textarea {
      background-color: #0f172a;
      border: 1px solid #334155;
      color: #f8fafc;
      border-radius: 10px;
    }
    .form-control:focus, .form-select:focus, textarea:focus {
      border-color: #6366f1;
      box-shadow: none;
    }
    .btn-primary {
      background-color: #6366f1;
      border: none;
      font-weight: 600;
      border-radius: 10px;
      padding: 0.75rem;
    }
    .btn-primary:hover {
      background-color: #4f46e5;
    }
    .upload-box {
      border: 2px dashed #6366f1;
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      cursor: pointer;
      transition: 0.3s;
      background: #0f172a;
      color: #e2e8f0;
    }
    .upload-box:hover {
      background: #1e293b;
      border-color: #4f46e5;
    }
    .upload-box input {display:none;}
    .upload-preview {
      margin-top: 15px;
      max-height: 150px;
    }
    .upload-preview img {
      max-height: 150px;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="card">
  <h3>Add Candidate</h3>
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="election_id" value="<?= htmlspecialchars($_GET['election_id'] ?? '') ?>">

    <div class="mb-3">
      <label class="form-label">Candidate Name *</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Gender *</label>
      <select name="gender" class="form-select" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Party Name</label>
      <input type="text" name="party_name" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Age</label>
      <input type="number" name="age" class="form-control" min="18" max="120">
    </div>

    <div class="mb-3">
      <label class="form-label">Manifesto</label>
      <textarea name="manifesto" class="form-control" rows="3"></textarea>
    </div>

    <!-- Candidate Photo -->
    <div class="mb-3">
      <label class="form-label">Candidate Photo</label>
      <div class="upload-box" onclick="document.getElementById('photo').click()">
        <p class="mb-1"><strong>Click to upload</strong> or drag & drop</p>
        <small class="text-muted">PNG, JPG up to 2MB</small>
        <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event, 'photoPreview')">
      </div>
      <div id="photoPreview" class="upload-preview"></div>
    </div>

    <!-- Party Symbol -->
    <div class="mb-3">
      <label class="form-label">Party Symbol</label>
      <div class="upload-box" onclick="document.getElementById('symbol').click()">
        <p class="mb-1"><strong>Click to upload</strong> or drag & drop</p>
        <small class="text-muted">PNG, JPG up to 2MB</small>
        <input type="file" name="symbol" id="symbol" accept="image/*" onchange="previewImage(event, 'symbolPreview')">
      </div>
      <div id="symbolPreview" class="upload-preview"></div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Add Candidate</button>
  </form>
</div>

<script>
  function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = "";
    const file = event.target.files[0];
    if (file) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      preview.appendChild(img);
    }
  }
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>



