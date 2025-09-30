<?php
// start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// block non-logged in users
if (empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// fallback values to avoid warnings
$name       = !empty($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Voter';
$voterId    = !empty($_SESSION['voter_id']) ? $_SESSION['voter_id'] : '';
$profileImg = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'uploads/profile/default.png';
?>
<header class="bg-gradient-to-r from-blue-600 to-indigo-600 p-3 md:p-4 text-white flex flex-col md:flex-row justify-between items-center shadow-lg">
  <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4 w-full md:w-auto">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white border-2 border-white/20">
        <i class="fas fa-vote-yea"></i>
      </div>
      <div>
        <div class="text-lg md:text-xl font-bold">TrueVote</div>
        <div class="text-xs md:text-sm text-gray-200">Secure Digital Voting</div>
      </div>
    </div>
    <nav id="nav-menu" class="hidden md:flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 mt-4 md:mt-0">
      <a href="dashboard.php" class="transition duration-300 hover:underline">Dashboard</a>
      <a href="elections.php" class="transition duration-300 hover:underline">Elections</a>
      <a href="history.php" class="transition duration-300 hover:underline">History</a>
      <a href="results.php" class="transition duration-300 hover:underline">Results</a>
      <a href="profile.php" class="transition duration-300 hover:underline">Profile</a>
      <a href="../logout.php" class="transition duration-300 hover:underline">Logout</a>
    </nav>
    <button class="md:hidden self-end" onclick="toggleNav()">☰</button>
  </div>

  <div class="flex items-center gap-2 mt-4 md:mt-0">
    <img src="../<?= htmlspecialchars($profileImg) ?>" alt="Profile" class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover ring-2 ring-white/50">
    <div>
      <div class="text-sm md:text-base"><?= htmlspecialchars($name) ?></div>
      <?php if ($voterId !== ''): ?>
      <div class="text-xs text-gray-300">Voter ID: <?= htmlspecialchars($voterId) ?></div>
      <?php endif; ?>
    </div>
  </div>
</header>

<script>
function toggleNav() {
  const nav = document.getElementById('nav-menu');
  nav.classList.toggle('hidden');
}
</script>
