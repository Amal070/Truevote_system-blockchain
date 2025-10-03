


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TrueVote Admin</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
body {
  font-family: 'Inter', sans-serif;
  margin:0;
  background:#0f172a;
  color:#f8fafc;
}

/* header bar */
header {
  background:#1e293b;
  border-bottom:1px solid #334155;
  padding:0.5rem 1rem;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.header-left {
  display:flex;
  align-items:center;
  gap:0.75rem;
}
.logo-box {
  width:40px;
  height:40px;
  background:linear-gradient(90deg,#ef4444,#dc2626);
  border-radius:0.5rem;
  display:flex;
  justify-content:center;
  align-items:center;
  color:#fff;
}
.header-title {
  display:flex;
  flex-direction:column;
  line-height:1.1;
}
.header-title span:first-child {
  font-weight:700;
  font-size:1.1rem;
}
.header-title span:last-child {
  font-size:0.65rem;
  color:#cbd5e1;
}
.admin-badge {
  background:linear-gradient(135deg,#f59e0b,#d97706);
  color:#fff;
  padding:0.25rem 0.5rem;
  border-radius:0.375rem;
  font-size:0.65rem;
  font-weight:700;
  display:flex;
  align-items:center;
  gap:0.25rem;
}

/* right section */
.header-right {
  display:flex;
  align-items:center;
  gap:0.75rem;
}
.blockchain-status {
  display:flex;
  align-items:center;
  gap:0.4rem;
  background:rgba(255,255,255,0.1);
  padding:0.25rem 0.5rem;
  border-radius:0.5rem;
  font-size:0.8rem;
}
.blockchain-status .dot {
  width:8px;
  height:8px;
  border-radius:50%;
  background:#10b981;
  animation:pulse 2s infinite;
}
@keyframes pulse {
  0%,100%{opacity:1}
  50%{opacity:0.7}
}
.profile {
  display:flex;
  align-items:center;
  gap:0.5rem;
}
.profile img {
  width:32px;
  height:32px;
  border-radius:50%;
  border:2px solid rgba(255,255,255,0.3);
}
.profile-info {
  display:flex;
  flex-direction:column;
  font-size:0.75rem;
}
.profile-info .name {font-weight:500;}
.profile-info .role {color:#cbd5e1;}

/* nav bar below header */
.navbar {
  background:#0f172a;
  display:flex;
  align-items:center;
  gap:1rem;
  padding:0.5rem 1rem;
  border-bottom:1px solid #334155;
}
.navbar a {
  color:#cbd5e1;
  text-decoration:none;
  font-weight:500;
  display:flex;
  align-items:center;
  gap:0.25rem;
  padding:0.25rem 0.5rem;
  border-radius:0.375rem;
  transition:all 0.2s ease;
  position:relative;
}
.navbar a:hover,
.navbar a.active {
  color:#fff;
  background:rgba(255,255,255,0.05);
}

/* dropdown */
.dropdown {
  position:relative;
}
.dropdown-content {
  display:none;
  position:absolute;
  top:100%;
  left:0;
  background:#1e293b;
  min-width:180px;
  border-radius:0.5rem;
  box-shadow:0 4px 10px rgba(0,0,0,0.4);
  z-index:50;
}
.dropdown-content a {
  display:block;
  padding:0.5rem 0.75rem;
  color:#cbd5e1;
}
.dropdown-content a:hover {
  background:#3b0764;
  color:#fff;
}
.dropdown:hover .dropdown-content {
  display:block;
}

/* responsive */
@media(max-width:768px){
  .navbar {flex-wrap:wrap;}
}
</style>
</head>
<body>

<header>
  <div class="header-left">
    <div class="logo-box"><i class="fas fa-shield-alt"></i></div>
    <div class="header-title">
      <span>TrueVote Admin</span>
      <span>Candidate Management</span>
    </div>
    <div class="admin-badge"><i class="fas fa-crown"></i>ADMIN</div>
  </div>
  <div class="header-right">
    <div class="blockchain-status">
      <div class="dot"></div>Blockchain Synced
    </div>
    <div class="profile">
      <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Crect width='32' height='32' fill='%23ef4444'/%3E%3Ccircle cx='16' cy='12' r='6' fill='white'/%3E%3Cpath d='M6 28 Q16 22 26 28' stroke='white' stroke-width='2' fill='none'/%3E%3C/svg%3E" alt="avatar">
      <div class="profile-info">
        <span class="name">Admin User</span>
        <span class="role">Super Admin</span>
      </div>
    </div>
  </div>
</header>

<div class="navbar">
  <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>

  <div class="dropdown">
    <a href="#"><i class="fas fa-vote-yea"></i>Elections<i class="fas fa-chevron-down" style="font-size:0.6rem;"></i></a>
    <div class="dropdown-content">
      <a href="add_election.php"><i class="fas fa-plus"></i> Add Election</a>
      <a href="view_elections.php"><i class="fas fa-list"></i> View Elections</a>
    </div>
  </div>

  <a href="view_candidates.php"><i class="fas fa-users"></i>Candidates</a>

  <a href="blockchain_records.php"><i class="fas fa-link"></i>Blockchain</a>
  <a href="results.php"><i class="fas fa-chart-bar"></i>Results</a>
  <a href="manage_users.php"><i class="fas fa-file-alt"></i>Logs</a>
  <a href="../logout.php"><i class="fas fa-cog"></i>Logout</a>
</div>
