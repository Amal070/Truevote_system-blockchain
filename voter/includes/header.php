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

// fallback values
$name       = !empty($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Voter';
$voterId    = !empty($_SESSION['voter_id']) ? $_SESSION['voter_id'] : '';
$profileImg = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'uploads/profile/default.png';
?>
<header class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 md:px-8 md:py-4 text-white shadow-lg relative z-50">
  <div class="flex justify-between items-center">
    
    <!-- Branding -->
    <div class="flex items-center gap-3">
      <div class="w-11 h-11 bg-blue-500 rounded-xl flex items-center justify-center text-white border-2 border-white/30 shadow">
        <i class="fas fa-vote-yea text-lg"></i>
      </div>
      <div>
        <div class="text-2xl font-bold tracking-wide">TrueVote</div>
        <div class="text-xs text-gray-200">Secure Digital Voting</div>
      </div>
    </div>

    <!-- Desktop Navigation -->
    <nav id="nav-menu" class="hidden md:flex items-center gap-6 text-sm font-medium">
      <a href="dashboard.php" class="relative group">
        Dashboard
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-300 transition-all group-hover:w-full"></span>
      </a>
      <a href="elections.php" class="relative group">
        Elections
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-300 transition-all group-hover:w-full"></span>
      </a>
      <a href="history.php" class="relative group">
        History
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-300 transition-all group-hover:w-full"></span>
      </a>
      <a href="results.php" class="relative group">
        Results
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-300 transition-all group-hover:w-full"></span>
      </a>
      <a href="profile.php" class="relative group">
        Profile
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-yellow-300 transition-all group-hover:w-full"></span>
      </a>
      <a href="../logout.php" class="relative group text-red-200">
        Logout
        <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-red-300 transition-all group-hover:w-full"></span>
      </a>
    </nav>

    <!-- Mobile Menu Button -->
    <button class="md:hidden text-2xl focus:outline-none" onclick="toggleNav()">☰</button>

    <!-- User Info -->
    <div class="hidden md:flex items-center gap-3 bg-white/10 px-3 py-2 rounded-lg shadow-sm">
      <img src="../<?= htmlspecialchars($profileImg) ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/40">
      <div class="leading-tight">
        <div class="font-medium"><?= htmlspecialchars($name) ?></div>
        <?php if ($voterId !== ''): ?>
        <div class="text-xs text-gray-200">Voter ID: <?= htmlspecialchars($voterId) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobile-menu" class="hidden flex-col mt-3 space-y-2 bg-indigo-700 rounded-lg p-3 md:hidden animate-slide-down">
    <a href="dashboard.php" class="block hover:text-yellow-300">Dashboard</a>
    <a href="elections.php" class="block hover:text-yellow-300">Elections</a>
    <a href="history.php" class="block hover:text-yellow-300">History</a>
    <a href="results.php" class="block hover:text-yellow-300">Results</a>
    <a href="profile.php" class="block hover:text-yellow-300">Profile</a>
    <a href="../logout.php" class="block hover:text-red-300">Logout</a>
    <div class="flex items-center gap-3 border-t border-white/20 pt-3 mt-2">
      <img src="../<?= htmlspecialchars($profileImg) ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/40">
      <div>
        <div class="font-medium text-sm"><?= htmlspecialchars($name) ?></div>
        <?php if ($voterId !== ''): ?>
        <div class="text-xs text-gray-200">Voter ID: <?= htmlspecialchars($voterId) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<script>
function toggleNav() {
  const menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden');
}
</script>

<style>
@keyframes slide-down {
  0% {opacity: 0; transform: translateY(-10px);}
  100% {opacity: 1; transform: translateY(0);}
}
.animate-slide-down { animation: slide-down 0.3s ease-out; }
</style>