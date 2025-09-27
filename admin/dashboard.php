<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates - TrueVote Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Dark theme configuration */
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --accent-blue: #3b82f6;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
            --accent-purple: #8b5cf6;
        }
        
        /* Enhanced animations */
        .fade-in { 
            animation: fadeIn 0.6s ease-in-out; 
        }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .slide-in { 
            animation: slideIn 0.4s ease-out; 
        }
        @keyframes slideIn { 
            from { transform: translateX(-20px); opacity: 0; } 
            to { transform: translateX(0); opacity: 1; } 
        }
        
        .scale-in { 
            animation: scaleIn 0.5s ease-out; 
        }
        @keyframes scaleIn { 
            from { transform: scale(0.95); opacity: 0; } 
            to { transform: scale(1); opacity: 1; } 
        }
        
        /* Card hover effects */
        .card-hover { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .card-hover:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3); 
        }
        
        /* Status indicators */
        .status-active { 
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%); 
        }
        .status-pending { 
            background: linear-gradient(135deg, var(--accent-yellow) 0%, #d97706 100%); 
        }
        .status-rejected { 
            background: linear-gradient(135deg, var(--accent-red) 0%, #dc2626 100%); 
        }
        .status-withdrawn { 
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); 
        }
        
        /* Gradient backgrounds */
        .admin-gradient { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
        }
        .candidates-gradient { 
            background: linear-gradient(135deg, var(--accent-purple) 0%, #7c3aed 100%); 
        }
        
        /* Table styles */
        .data-table {
            border-collapse: separate;
            border-spacing: 0;
            background: var(--bg-secondary);
        }
        .data-table th,
        .data-table td {
            border-bottom: 1px solid #374151;
        }
        .data-table th:first-child,
        .data-table td:first-child {
            border-left: 1px solid #374151;
        }
        .data-table th:last-child,
        .data-table td:last-child {
            border-right: 1px solid #374151;
        }
        .data-table thead th:first-child {
            border-top-left-radius: 0.5rem;
            border-top: 1px solid #374151;
        }
        .data-table thead th:last-child {
            border-top-right-radius: 0.5rem;
            border-top: 1px solid #374151;
        }
        .data-table thead th {
            border-top: 1px solid #374151;
            background: var(--bg-tertiary);
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--bg-tertiary);
            border-radius: 3px;
        }
        
        /* Admin badge */
        .admin-badge {
            background: linear-gradient(135deg, var(--accent-red) 0%, #dc2626 100%);
        }
        
        /* Focus styles for accessibility */
        .focus-ring:focus {
            outline: 2px solid var(--accent-blue);
            outline-offset: 2px;
        }
        
        /* Modal styles */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }
        
        /* Search and filter styles */
        .search-input {
            background: var(--bg-secondary);
            border-color: #374151;
            color: var(--text-primary);
        }
        .search-input::placeholder {
            color: var(--text-muted);
        }
        
        /* Action button styles */
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        /* Bulk action styles */
        .bulk-actions {
            background: linear-gradient(135deg, var(--accent-blue) 0%, #1d4ed8 100%);
        }
        
        /* Candidate avatar styles */
        .candidate-avatar {
            background: linear-gradient(135deg, var(--accent-purple) 0%, #7c3aed 100%);
        }
        
        /* Stats card styles */
        .stats-card {
            background: var(--bg-secondary);
            border: 1px solid #374151;
        }
        
        /* Party badge styles */
        .party-democrat { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .party-republican { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .party-independent { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .party-libertarian { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .party-green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
        
        /* Election status styles */
        .election-upcoming { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .election-active { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .election-completed { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
        
        /* Responsive table */
        @media (max-width: 768px) {
            .mobile-card {
                display: block;
            }
            .desktop-table {
                display: none;
            }
        }
        @media (min-width: 769px) {
            .mobile-card {
                display: none;
            }
            .desktop-table {
                display: table;
            }
        }
        
        /* Loading animation */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Notification styles */
        .notification-badge {
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-3px); }
            60% { transform: translateY(-2px); }
        }
        
        /* Vote count animation */
        .vote-counter {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid #475569;
        }
        
        /* Blockchain verification badge */
        .blockchain-verified {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: var(--bg-primary); color: var(--text-primary);">
    <!-- Admin Header -->
    <header class="admin-gradient shadow-lg border-b border-gray-700 sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Admin Badge -->
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white">TrueVote Admin</span>
                        <div class="text-xs text-gray-300 hidden sm:block">Candidate Management</div>
                    </div>
                    <span class="admin-badge text-white px-2 py-1 rounded-full text-xs font-bold">
                        <i class="fas fa-crown mr-1"></i>ADMIN
                    </span>
                </div>

                <!-- System Status & Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Blockchain Status -->
                    <div class="hidden md:flex items-center space-x-2 bg-white bg-opacity-10 rounded-lg px-3 py-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full blockchain-verified"></div>
                        <span class="text-white text-sm font-medium">Blockchain Synced</span>
                    </div>
                    
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors focus-ring" onclick="toggleNotifications()">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center notification-badge">3</span>
                    </button>
                    
                    <!-- Admin Profile -->
                    <div class="flex items-center space-x-3">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23ef4444'/%3E%3Ccircle cx='16' cy='12' r='6' fill='white'/%3E%3Cpath d='M6 28 Q16 22 26 28' stroke='white' stroke-width='2' fill='none'/%3E%3C/svg%3E" 
                             alt="Admin" 
                             class="w-8 h-8 rounded-full border-2 border-white border-opacity-30">
                        <div class="hidden sm:block text-white">
                            <div class="text-sm font-medium">Admin User</div>
                            <div class="text-xs text-gray-300">Super Admin</div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-300 hover:text-white p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors focus-ring" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl" id="mobileMenuIcon"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Admin Navigation -->
        <div class="border-t border-gray-600">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="hidden md:flex space-x-8 py-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <!-- Elections Menu with Dropdown -->
<div class="relative group">
    <a href="#" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1 flex items-center">
        <i class="fas fa-vote-yea mr-2"></i>Elections
        <i class="fas fa-chevron-down ml-1 text-xs"></i>
    </a>

    <!-- Dropdown -->
    <div class="absolute left-0 mt-2 w-44 bg-gray-800 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition duration-200 z-50">
        <a href="add_election.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-purple-600 hover:text-white rounded-t-lg">
            <i class="fas fa-plus mr-2"></i> Add Election
        </a>
        <a href="view_elections.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-purple-600 hover:text-white rounded-b-lg">
            <i class="fas fa-list mr-2"></i> View Elections
        </a>
    </div>
</div>

                    <!-- Candidates Menu with Dropdown -->
<div class="relative group">
    <a href="#" class="text-white font-semibold border-b-2 border-purple-400 pb-1 flex items-center">
        <i class="fas fa-users mr-2"></i>Candidates
        <i class="fas fa-chevron-down ml-1 text-xs"></i>
    </a>

    <!-- Dropdown -->
    <div class="absolute left-0 mt-2 w-44 bg-gray-800 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition duration-200 z-50">
        <a href="add_candidates.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-purple-600 hover:text-white rounded-t-lg">
            <i class="fas fa-user-plus mr-2"></i> Add Candidate
        </a>
        <a href="view_candidates.php" class="block px-4 py-2 text-sm text-gray-200 hover:bg-purple-600 hover:text-white rounded-b-lg">
            <i class="fas fa-list mr-2"></i> View Candidates
        </a>
    </div>
</div>

                    <a href="manage_voters.php" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-user-check mr-2"></i>Voters
                    </a>
                    <a href="blockchain_records.php" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-link mr-2"></i>Blockchain
                    </a>
                    <a href="publish_results.php" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-chart-bar mr-2"></i>Results
                    </a>
                    <a href="manage_users.php" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-file-alt mr-2"></i>Logs
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white font-medium transition-colors focus-ring rounded px-2 py-1">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-600" style="background-color: var(--bg-secondary);">
            <div class="px-4 py-6 space-y-4">
                <a href="dashboard.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="manage_elections.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-vote-yea mr-2"></i>Elections
                </a>
                <a href="manage_candidates.php" class="block text-white font-semibold py-2 px-2 rounded-lg bg-purple-600">
                    <i class="fas fa-users mr-2"></i>Candidates
                </a>
                <a href="manage_voters.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-user-check mr-2"></i>Voters
                </a>
                <a href="blockchain_records.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-link mr-2"></i>Blockchain
                </a>
                <a href="publish_results.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Results
                </a>
                <a href="system_logs.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Logs
                </a>
                <a href="settings.php" class="block text-gray-300 hover:text-white font-medium py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Header -->
        <div class="candidates-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="text-center lg:text-left mb-6 lg:mb-0">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                        <i class="fas fa-users mr-3"></i>Manage Candidates
                    </h1>
                    <p class="text-purple-100 text-base sm:text-lg mb-4">Comprehensive candidate registration and election management</p>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-user-tie mr-1"></i>47 Total Candidates
                        </span>
                        <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>38 Active
                        </span>
                        <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-vote-yea mr-1"></i>5 Elections
                        </span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="addCandidate()" class="stats-card px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all card-hover focus-ring" style="color: var(--text-primary);">
                        <i class="fas fa-user-plus mr-2"></i>Add Candidate
                    </button>
                    <button onclick="bulkImport()" class="stats-card px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all card-hover focus-ring" style="color: var(--text-primary);">
                        <i class="fas fa-upload mr-2"></i>Bulk Import
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Candidates -->
            <div class="stats-card rounded-xl shadow-sm p-6 card-hover scale-in">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-400 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--text-primary);">47</span>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Total Candidates</h3>
                <div class="text-sm text-green-400 font-medium">
                    <i class="fas fa-arrow-up mr-1"></i>+8 this month
                </div>
            </div>

            <!-- Active Candidates -->
            <div class="stats-card rounded-xl shadow-sm p-6 card-hover scale-in">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-400 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--text-primary);">38</span>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Active</h3>
                <div class="w-full bg-gray-600 rounded-full h-2 mt-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 81%"></div>
                </div>
                <div class="text-xs text-gray-400 mt-1">81% approval rate</div>
            </div>

            <!-- Pending Approval -->
            <div class="stats-card rounded-xl shadow-sm p-6 card-hover scale-in">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--text-primary);">6</span>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Pending</h3>
                <div class="text-sm text-yellow-400 font-medium">
                    <i class="fas fa-hourglass-half mr-1"></i>Awaiting review
                </div>
            </div>

            <!-- Elections Running -->
            <div class="stats-card rounded-xl shadow-sm p-6 card-hover scale-in">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-vote-yea text-blue-400 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--text-primary);">5</span>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Active Elections</h3>
                <div class="text-sm text-blue-400 font-medium">
                    <i class="fas fa-chart-line mr-1"></i>2 ending soon
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="stats-card rounded-xl shadow-sm p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search candidates by name, party, or position..." 
                               class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input"
                               onkeyup="searchCandidates()">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <select id="statusFilter" onchange="filterCandidates()" class="px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                        <option value="withdrawn">Withdrawn</option>
                    </select>

                    <select id="electionFilter" onchange="filterCandidates()" class="px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                        <option value="">All Elections</option>
                        <option value="presidential-2024">Presidential 2024</option>
                        <option value="senate-2024">Senate 2024</option>
                        <option value="governor-2024">Governor 2024</option>
                        <option value="mayor-2024">Mayor 2024</option>
                        <option value="council-2024">City Council 2024</option>
                    </select>

                    <select id="partyFilter" onchange="filterCandidates()" class="px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                        <option value="">All Parties</option>
                        <option value="democrat">Democrat</option>
                        <option value="republican">Republican</option>
                        <option value="independent">Independent</option>
                        <option value="libertarian">Libertarian</option>
                        <option value="green">Green Party</option>
                    </select>

                    <button onclick="resetFilters()" class="px-4 py-3 bg-gray-600 text-gray-200 rounded-lg hover:bg-gray-500 transition-colors focus-ring">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div id="bulkActions" class="hidden mt-4 p-4 bulk-actions rounded-lg text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span id="selectedCount" class="font-medium">0 candidates selected</span>
                        <button onclick="selectAll()" class="text-blue-200 hover:text-white transition-colors">
                            Select All
                        </button>
                        <button onclick="clearSelection()" class="text-blue-200 hover:text-white transition-colors">
                            Clear Selection
                        </button>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="bulkApprove()" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-check mr-2"></i>Approve Selected
                        </button>
                        <button onclick="bulkReject()" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Reject Selected
                        </button>
                        <button onclick="bulkExport()" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i>Export Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidates Table -->
        <div class="stats-card rounded-xl shadow-sm overflow-hidden fade-in">
            <!-- Desktop Table -->
            <div class="desktop-table overflow-x-auto custom-scrollbar">
                <table class="data-table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-4 text-left">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="rounded border-gray-500 text-purple-600 focus:ring-purple-500 bg-gray-700">
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
                                <button onclick="sortTable('name')" class="flex items-center space-x-1 hover:text-white">
                                    <span>Candidate</span>
                                    <i class="fas fa-sort text-gray-400"></i>
                                </button>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
                                <button onclick="sortTable('election')" class="flex items-center space-x-1 hover:text-white">
                                    <span>Election</span>
                                    <i class="fas fa-sort text-gray-400"></i>
                                </button>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
                                <button onclick="sortTable('party')" class="flex items-center space-x-1 hover:text-white">
                                    <span>Party</span>
                                    <i class="fas fa-sort text-gray-400"></i>
                                </button>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
                                <button onclick="sortTable('status')" class="flex items-center space-x-1 hover:text-white">
                                    <span>Status</span>
                                    <i class="fas fa-sort text-gray-400"></i>
                                </button>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Votes</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
                                <button onclick="sortTable('registered')" class="flex items-center space-x-1 hover:text-white">
                                    <span>Registered</span>
                                    <i class="fas fa-sort text-gray-400"></i>
                                </button>
                            </th>
                            <th class="px-4 py-4 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="candidatesTableBody" class="divide-y divide-gray-600">
                        <!-- Candidate rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-card space-y-4 p-4">
                <div id="candidatesMobileCards">
                    <!-- Mobile cards will be populated by JavaScript -->
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-600" style="background-color: var(--bg-tertiary);">
                <div class="flex items-center justify-between">
                    <div class="text-sm" style="color: var(--text-secondary);">
                        Showing <span id="showingStart">1</span> to <span id="showingEnd">25</span> of <span id="totalCandidates">47</span> candidates
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="previousPage()" id="prevBtn" class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-secondary);">
                            <i class="fas fa-chevron-left mr-1"></i>Previous
                        </button>
                        <div class="flex space-x-1" id="pageNumbers">
                            <!-- Page numbers will be populated by JavaScript -->
                        </div>
                        <button onclick="nextPage()" id="nextBtn" class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-secondary);">
                            Next<i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-700 mt-12" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="text-sm mb-4 sm:mb-0" style="color: var(--text-muted);">
                    &copy; 2024 TrueVote Admin Panel. All rights reserved.
                </div>
                <div class="flex items-center space-x-6 text-sm" style="color: var(--text-muted);">
                    <span>Version 2.1.0</span>
                    <span>•</span>
                    <a href="#" class="hover:text-purple-400 transition-colors">Documentation</a>
                    <span>•</span>
                    <a href="#" class="hover:text-purple-400 transition-colors">Support</a>
                    <span>•</span>
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-link text-green-400"></i>
                        <span class="text-green-400">Blockchain Connected</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Edit Candidate Modal -->
    <div id="editCandidateModal" class="hidden fixed inset-0 z-50 modal-backdrop flex items-center justify-center p-4">
        <div class="rounded-2xl shadow-2xl max-w-3xl w-full max-h-screen overflow-y-auto scale-in" style="background-color: var(--bg-secondary);">
            <div class="p-6 border-b border-gray-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-user-edit text-purple-500 mr-2"></i>
                        Edit Candidate Information
                    </h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="editCandidateForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Full Name</label>
                        <input type="text" id="editName" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Candidate ID</label>
                        <input type="text" id="editCandidateID" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email Address</label>
                        <input type="email" id="editEmail" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Phone Number</label>
                        <input type="tel" id="editPhone" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Election</label>
                        <select id="editElection" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="presidential-2024">Presidential 2024</option>
                            <option value="senate-2024">Senate 2024</option>
                            <option value="governor-2024">Governor 2024</option>
                            <option value="mayor-2024">Mayor 2024</option>
                            <option value="council-2024">City Council 2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Political Party</label>
                        <select id="editParty" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="democrat">Democrat</option>
                            <option value="republican">Republican</option>
                            <option value="independent">Independent</option>
                            <option value="libertarian">Libertarian</option>
                            <option value="green">Green Party</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Status</label>
                        <select id="editStatus" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                            <option value="withdrawn">Withdrawn</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Position</label>
                        <input type="text" id="editPosition" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Campaign Platform</label>
                    <textarea id="editPlatform" rows="4" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Admin Notes</label>
                    <textarea id="editNotes" rows="3" placeholder="Internal notes about this candidate..." class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input"></textarea>
                </div>
                
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-600">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 border border-gray-500 rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="color: var(--text-secondary);">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors focus-ring">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Candidate Modal -->
    <div id="addCandidateModal" class="hidden fixed inset-0 z-50 modal-backdrop flex items-center justify-center p-4">
        <div class="rounded-2xl shadow-2xl max-w-3xl w-full max-h-screen overflow-y-auto scale-in" style="background-color: var(--bg-secondary);">
            <div class="p-6 border-b border-gray-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-user-plus text-green-500 mr-2"></i>
                        Add New Candidate
                    </h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="addCandidateForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Full Name *</label>
                        <input type="text" id="addName" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email Address *</label>
                        <input type="email" id="addEmail" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Phone Number *</label>
                        <input type="tel" id="addPhone" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Date of Birth *</label>
                        <input type="date" id="addDOB" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Election *</label>
                        <select id="addElection" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="">Select Election</option>
                            <option value="presidential-2024">Presidential 2024</option>
                            <option value="senate-2024">Senate 2024</option>
                            <option value="governor-2024">Governor 2024</option>
                            <option value="mayor-2024">Mayor 2024</option>
                            <option value="council-2024">City Council 2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Political Party *</label>
                        <select id="addParty" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="">Select Party</option>
                            <option value="democrat">Democrat</option>
                            <option value="republican">Republican</option>
                            <option value="independent">Independent</option>
                            <option value="libertarian">Libertarian</option>
                            <option value="green">Green Party</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Position *</label>
                        <input type="text" id="addPosition" required placeholder="e.g., President, Senator, Governor" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Initial Status</label>
                        <select id="addStatus" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input">
                            <option value="pending">Pending Approval</option>
                            <option value="active">Active</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Campaign Platform *</label>
                    <textarea id="addPlatform" rows="4" required placeholder="Describe the candidate's key policy positions and campaign promises..." class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent search-input"></textarea>
                </div>
                
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-600">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 border border-gray-500 rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="color: var(--text-secondary);">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors focus-ring">
                        <i class="fas fa-user-plus mr-2"></i>Add Candidate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sample candidate data
        let candidatesData = [
            {
                id: 'CND001234',
                name: 'Sarah Mitchell',
                email: 'sarah.mitchell@campaign.com',
                phone: '+1 (555) 123-4567',
                dob: '1975-08-15',
                election: 'presidential-2024',
                electionTitle: 'Presidential 2024',
                party: 'democrat',
                position: 'President',
                status: 'active',
                votes: 15847,
                registered: '2024-01-15',
                platform: 'Healthcare reform, climate action, economic equality',
                notes: 'Strong polling numbers, experienced candidate'
            },
            {
                id: 'CND001235',
                name: 'Robert Johnson',
                email: 'robert.johnson@campaign.com',
                phone: '+1 (555) 234-5678',
                dob: '1968-03-22',
                election: 'presidential-2024',
                electionTitle: 'Presidential 2024',
                party: 'republican',
                position: 'President',
                status: 'active',
                votes: 14523,
                registered: '2024-01-20',
                platform: 'Economic growth, border security, traditional values',
                notes: 'Incumbent advantage, strong fundraising'
            },
            {
                id: 'CND001236',
                name: 'Maria Rodriguez',
                email: 'maria.rodriguez@senate.com',
                phone: '+1 (555) 345-6789',
                dob: '1982-11-08',
                election: 'senate-2024',
                electionTitle: 'Senate 2024',
                party: 'democrat',
                position: 'Senator',
                status: 'active',
                votes: 8934,
                registered: '2024-02-10',
                platform: 'Education funding, infrastructure investment, healthcare access',
                notes: 'Rising star, strong grassroots support'
            },
            {
                id: 'CND001237',
                name: 'David Thompson',
                email: 'david.thompson@gov.com',
                phone: '+1 (555) 456-7890',
                dob: '1971-05-30',
                election: 'governor-2024',
                electionTitle: 'Governor 2024',
                party: 'republican',
                position: 'Governor',
                status: 'pending',
                votes: 0,
                registered: '2024-03-05',
                platform: 'State fiscal responsibility, business development, law and order',
                notes: 'Awaiting final document verification'
            },
            {
                id: 'CND001238',
                name: 'Jennifer Chen',
                email: 'jennifer.chen@independent.com',
                phone: '+1 (555) 567-8901',
                dob: '1979-09-12',
                election: 'mayor-2024',
                electionTitle: 'Mayor 2024',
                party: 'independent',
                position: 'Mayor',
                status: 'active',
                votes: 5672,
                registered: '2024-02-28',
                platform: 'Urban development, public transportation, community engagement',
                notes: 'Strong local support, innovative policies'
            },
            {
                id: 'CND001239',
                name: 'Michael Brown',
                email: 'michael.brown@libertarian.com',
                phone: '+1 (555) 678-9012',
                dob: '1985-12-03',
                election: 'senate-2024',
                electionTitle: 'Senate 2024',
                party: 'libertarian',
                position: 'Senator',
                status: 'rejected',
                votes: 0,
                registered: '2024-03-01',
                platform: 'Limited government, individual liberty, fiscal conservatism',
                notes: 'Rejected - incomplete filing requirements'
            },
            {
                id: 'CND001240',
                name: 'Lisa Anderson',
                email: 'lisa.anderson@green.com',
                phone: '+1 (555) 789-0123',
                dob: '1977-04-18',
                election: 'council-2024',
                electionTitle: 'City Council 2024',
                party: 'green',
                position: 'City Council Member',
                status: 'active',
                votes: 2341,
                registered: '2024-03-10',
                platform: 'Environmental protection, sustainable development, social justice',
                notes: 'Environmental advocate, community organizer'
            },
            {
                id: 'CND001241',
                name: 'Christopher Davis',
                email: 'christopher.davis@campaign.com',
                phone: '+1 (555) 890-1234',
                dob: '1973-08-25',
                election: 'governor-2024',
                electionTitle: 'Governor 2024',
                party: 'democrat',
                position: 'Governor',
                status: 'withdrawn',
                votes: 0,
                registered: '2024-01-30',
                platform: 'Healthcare expansion, education reform, economic development',
                notes: 'Withdrew due to personal reasons'
            }
        ];

        let filteredCandidates = [...candidatesData];
        let selectedCandidates = new Set();
        let currentPage = 1;
        let itemsPerPage = 25;
        let sortColumn = '';
        let sortDirection = 'asc';

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Candidate Management System initialized');
            renderCandidates();
            updatePagination();
        });

        // Render candidates table
        function renderCandidates() {
            const tableBody = document.getElementById('candidatesTableBody');
            const mobileCards = document.getElementById('candidatesMobileCards');
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentCandidates = filteredCandidates.slice(startIndex, endIndex);

            // Desktop table
            tableBody.innerHTML = currentCandidates.map(candidate => `
                <tr class="hover:bg-gray-700 hover:bg-opacity-50 transition-colors">
                    <td class="px-4 py-4">
                        <input type="checkbox" 
                               class="candidate-checkbox rounded border-gray-500 text-purple-600 focus:ring-purple-500 bg-gray-700" 
                               value="${candidate.id}" 
                               onchange="toggleCandidateSelection('${candidate.id}')">
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="candidate-avatar w-10 h-10 rounded-full flex items-center justify-center text-white font-bold">
                                ${candidate.name.split(' ').map(n => n[0]).join('')}
                            </div>
                            <div>
                                <div class="font-medium" style="color: var(--text-primary);">${candidate.name}</div>
                                <div class="text-sm" style="color: var(--text-muted);">${candidate.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm font-medium" style="color: var(--text-primary);">${candidate.electionTitle}</div>
                        <div class="text-xs" style="color: var(--text-muted);">${candidate.position}</div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="party-${candidate.party} text-white px-3 py-1 rounded-full text-xs font-medium">
                            ${getPartyText(candidate.party)}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="status-${candidate.status} text-white px-3 py-1 rounded-full text-xs font-medium">
                            ${getStatusText(candidate.status)}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="vote-counter px-3 py-2 rounded-lg text-center">
                            <div class="text-lg font-bold" style="color: var(--text-primary);">${candidate.votes.toLocaleString()}</div>
                            <div class="text-xs" style="color: var(--text-muted);">votes</div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm" style="color: var(--text-primary);">${formatDate(candidate.registered)}</div>
                        <div class="text-xs" style="color: var(--text-muted);">${candidate.phone}</div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="viewCandidate('${candidate.id}')" 
                                    class="action-btn text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-blue-600 hover:bg-opacity-20 transition-all" 
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editCandidate('${candidate.id}')" 
                                    class="action-btn text-green-400 hover:text-green-300 p-2 rounded-lg hover:bg-green-600 hover:bg-opacity-20 transition-all" 
                                    title="Edit Candidate">
                                <i class="fas fa-edit"></i>
                            </button>
                            ${candidate.status !== 'active' || !isElectionActive(candidate.election) ? `
                            <button onclick="deleteCandidate('${candidate.id}')" 
                                    class="action-btn text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-600 hover:bg-opacity-20 transition-all" 
                                    title="Delete Candidate">
                                <i class="fas fa-trash"></i>
                            </button>
                            ` : `
                            <button class="action-btn text-gray-500 p-2 rounded-lg cursor-not-allowed" 
                                    title="Cannot delete - Election is active" disabled>
                                <i class="fas fa-lock"></i>
                            </button>
                            `}
                        </div>
                    </td>
                </tr>
            `).join('');

            // Mobile cards
            mobileCards.innerHTML = currentCandidates.map(candidate => `
                <div class="border border-gray-600 rounded-xl p-4 card-hover" style="background-color: var(--bg-secondary);">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   class="candidate-checkbox rounded border-gray-500 text-purple-600 focus:ring-purple-500 bg-gray-700" 
                                   value="${candidate.id}" 
                                   onchange="toggleCandidateSelection('${candidate.id}')">
                            <div class="candidate-avatar w-12 h-12 rounded-full flex items-center justify-center text-white font-bold">
                                ${candidate.name.split(' ').map(n => n[0]).join('')}
                            </div>
                            <div>
                                <div class="font-medium" style="color: var(--text-primary);">${candidate.name}</div>
                                <div class="text-sm font-mono" style="color: var(--text-muted);">${candidate.id}</div>
                            </div>
                        </div>
                        <span class="status-${candidate.status} text-white px-2 py-1 rounded-full text-xs font-medium">
                            ${getStatusText(candidate.status)}
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-sm mb-4" style="color: var(--text-muted);">
                        <div><i class="fas fa-vote-yea w-4 text-purple-400"></i> ${candidate.electionTitle}</div>
                        <div><i class="fas fa-flag w-4 text-purple-400"></i> ${getPartyText(candidate.party)}</div>
                        <div><i class="fas fa-envelope w-4 text-purple-400"></i> ${candidate.email}</div>
                        <div><i class="fas fa-chart-bar w-4 text-purple-400"></i> ${candidate.votes.toLocaleString()} votes</div>
                        <div><i class="fas fa-calendar w-4 text-purple-400"></i> Registered: ${formatDate(candidate.registered)}</div>
                    </div>
                    
                    <div class="flex justify-end space-x-2">
                        <button onclick="viewCandidate('${candidate.id}')" class="action-btn text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-blue-600 hover:bg-opacity-20 transition-all">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editCandidate('${candidate.id}')" class="action-btn text-green-400 hover:text-green-300 p-2 rounded-lg hover:bg-green-600 hover:bg-opacity-20 transition-all">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${candidate.status !== 'active' || !isElectionActive(candidate.election) ? `
                        <button onclick="deleteCandidate('${candidate.id}')" class="action-btn text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-600 hover:bg-opacity-20 transition-all">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : `
                        <button class="action-btn text-gray-500 p-2 rounded-lg cursor-not-allowed" title="Cannot delete - Election is active" disabled>
                            <i class="fas fa-lock"></i>
                        </button>
                        `}
                    </div>
                </div>
            `).join('');

            updateSelectionUI();
        }

        // Helper functions
        function getStatusText(status) {
            const statusMap = {
                'active': 'Active',
                'pending': 'Pending',
                'rejected': 'Rejected',
                'withdrawn': 'Withdrawn'
            };
            return statusMap[status] || status;
        }

        function getPartyText(party) {
            const partyMap = {
                'democrat': 'Democrat',
                'republican': 'Republican',
                'independent': 'Independent',
                'libertarian': 'Libertarian',
                'green': 'Green Party'
            };
            return partyMap[party] || party;
        }

        function isElectionActive(electionId) {
            // In a real system, this would check the election status
            const activeElections = ['presidential-2024', 'senate-2024'];
            return activeElections.includes(electionId);
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Search and filter functions
        function searchCandidates() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const electionFilter = document.getElementById('electionFilter').value;
            const partyFilter = document.getElementById('partyFilter').value;

            filteredCandidates = candidatesData.filter(candidate => {
                const matchesSearch = !searchTerm || 
                    candidate.name.toLowerCase().includes(searchTerm) ||
                    candidate.id.toLowerCase().includes(searchTerm) ||
                    candidate.email.toLowerCase().includes(searchTerm) ||
                    candidate.position.toLowerCase().includes(searchTerm) ||
                    getPartyText(candidate.party).toLowerCase().includes(searchTerm);
                
                const matchesStatus = !statusFilter || candidate.status === statusFilter;
                const matchesElection = !electionFilter || candidate.election === electionFilter;
                const matchesParty = !partyFilter || candidate.party === partyFilter;

                return matchesSearch && matchesStatus && matchesElection && matchesParty;
            });

            currentPage = 1;
            renderCandidates();
            updatePagination();
        }

        function filterCandidates() {
            searchCandidates();
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('electionFilter').value = '';
            document.getElementById('partyFilter').value = '';
            filteredCandidates = [...candidatesData];
            currentPage = 1;
            renderCandidates();
            updatePagination();
        }

        // Sort table
        function sortTable(column) {
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }

            filteredCandidates.sort((a, b) => {
                let aVal = a[column];
                let bVal = b[column];

                if (column === 'registered') {
                    aVal = new Date(aVal);
                    bVal = new Date(bVal);
                } else if (column === 'votes') {
                    aVal = parseInt(aVal);
                    bVal = parseInt(bVal);
                }

                if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
                return 0;
            });

            renderCandidates();
        }

        // Selection functions
        function toggleCandidateSelection(candidateId) {
            if (selectedCandidates.has(candidateId)) {
                selectedCandidates.delete(candidateId);
            } else {
                selectedCandidates.add(candidateId);
            }
            updateSelectionUI();
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
            
            if (selectAllCheckbox.checked) {
                candidateCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    selectedCandidates.add(checkbox.value);
                });
            } else {
                candidateCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    selectedCandidates.delete(checkbox.value);
                });
            }
            updateSelectionUI();
        }

        function selectAll() {
            const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
            candidateCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                selectedCandidates.add(checkbox.value);
            });
            document.getElementById('selectAllCheckbox').checked = true;
            updateSelectionUI();
        }

        function clearSelection() {
            const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
            candidateCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAllCheckbox').checked = false;
            selectedCandidates.clear();
            updateSelectionUI();
        }

        function updateSelectionUI() {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedCandidates.size > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = `${selectedCandidates.size} candidate${selectedCandidates.size !== 1 ? 's' : ''} selected`;
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        // Pagination functions
        function updatePagination() {
            const totalPages = Math.ceil(filteredCandidates.length / itemsPerPage);
            const startItem = (currentPage - 1) * itemsPerPage + 1;
            const endItem = Math.min(currentPage * itemsPerPage, filteredCandidates.length);

            document.getElementById('showingStart').textContent = startItem;
            document.getElementById('showingEnd').textContent = endItem;
            document.getElementById('totalCandidates').textContent = filteredCandidates.length;

            // Update page numbers
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = `px-3 py-2 text-sm rounded-lg transition-colors focus-ring ${
                        i === currentPage 
                            ? 'bg-purple-600 text-white' 
                            : 'border border-gray-500 hover:bg-gray-600'
                    }`;
                    button.style.backgroundColor = i === currentPage ? 'var(--accent-purple)' : 'var(--bg-secondary)';
                    button.style.borderColor = '#374151';
                    button.style.color = i === currentPage ? 'white' : 'var(--text-secondary)';
                    button.onclick = () => goToPage(i);
                    pageNumbers.appendChild(button);
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 py-2';
                    ellipsis.style.color = 'var(--text-muted)';
                    pageNumbers.appendChild(ellipsis);
                }
            }

            // Update prev/next buttons
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;
        }

        function goToPage(page) {
            currentPage = page;
            renderCandidates();
            updatePagination();
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                renderCandidates();
                updatePagination();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredCandidates.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderCandidates();
                updatePagination();
            }
        }

        // Candidate management functions
        function viewCandidate(candidateId) {
            const candidate = candidatesData.find(c => c.id === candidateId);
            if (candidate) {
                alert(`Viewing candidate details for ${candidate.name}\n\nID: ${candidate.id}\nElection: ${candidate.electionTitle}\nParty: ${getPartyText(candidate.party)}\nStatus: ${candidate.status}\nVotes: ${candidate.votes.toLocaleString()}\nPlatform: ${candidate.platform}\n\nIn a full implementation, this would open a detailed view modal with blockchain verification status.`);
            }
        }

        function editCandidate(candidateId) {
            const candidate = candidatesData.find(c => c.id === candidateId);
            if (candidate) {
                // Populate edit form
                document.getElementById('editName').value = candidate.name;
                document.getElementById('editCandidateID').value = candidate.id;
                document.getElementById('editEmail').value = candidate.email;
                document.getElementById('editPhone').value = candidate.phone;
                document.getElementById('editElection').value = candidate.election;
                document.getElementById('editParty').value = candidate.party;
                document.getElementById('editStatus').value = candidate.status;
                document.getElementById('editPosition').value = candidate.position;
                document.getElementById('editPlatform').value = candidate.platform;
                document.getElementById('editNotes').value = candidate.notes || '';
                
                // Show modal
                document.getElementById('editCandidateModal').classList.remove('hidden');
            }
        }

        function deleteCandidate(candidateId) {
            const candidate = candidatesData.find(c => c.id === candidateId);
            if (candidate) {
                if (candidate.status === 'active' && isElectionActive(candidate.election)) {
                    alert('Cannot delete active candidate from ongoing election. Please withdraw the candidate first.');
                    return;
                }
                
                if (confirm(`Are you sure you want to delete candidate ${candidate.name} (${candidate.id})?\n\nThis action cannot be undone and will remove all associated voting records.`)) {
                    candidatesData = candidatesData.filter(c => c.id !== candidateId);
                    filteredCandidates = filteredCandidates.filter(c => c.id !== candidateId);
                    selectedCandidates.delete(candidateId);
                    renderCandidates();
                    updatePagination();
                    updateSelectionUI();
                    
                    showNotification('Candidate deleted successfully', 'success');
                }
            }
        }

        // Modal functions
        function closeEditModal() {
            document.getElementById('editCandidateModal').classList.add('hidden');
        }

        function closeAddModal() {
            document.getElementById('addCandidateModal').classList.add('hidden');
        }

        function addCandidate() {
            document.getElementById('addCandidateModal').classList.remove('hidden');
        }

        // Form submissions
        document.getElementById('editCandidateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const candidateId = document.getElementById('editCandidateID').value;
            const candidateIndex = candidatesData.findIndex(c => c.id === candidateId);
            
            if (candidateIndex !== -1) {
                candidatesData[candidateIndex] = {
                    ...candidatesData[candidateIndex],
                    name: document.getElementById('editName').value,
                    email: document.getElementById('editEmail').value,
                    phone: document.getElementById('editPhone').value,
                    election: document.getElementById('editElection').value,
                    electionTitle: getElectionTitle(document.getElementById('editElection').value),
                    party: document.getElementById('editParty').value,
                    status: document.getElementById('editStatus').value,
                    position: document.getElementById('editPosition').value,
                    platform: document.getElementById('editPlatform').value,
                    notes: document.getElementById('editNotes').value
                };
                
                // Update filtered candidates if needed
                const filteredIndex = filteredCandidates.findIndex(c => c.id === candidateId);
                if (filteredIndex !== -1) {
                    filteredCandidates[filteredIndex] = candidatesData[candidateIndex];
                }
                
                renderCandidates();
                closeEditModal();
                showNotification('Candidate updated successfully', 'success');
            }
        });

        document.getElementById('addCandidateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newCandidate = {
                id: 'CND' + String(Date.now()).slice(-6),
                name: document.getElementById('addName').value,
                email: document.getElementById('addEmail').value,
                phone: document.getElementById('addPhone').value,
                dob: document.getElementById('addDOB').value,
                election: document.getElementById('addElection').value,
                electionTitle: getElectionTitle(document.getElementById('addElection').value),
                party: document.getElementById('addParty').value,
                position: document.getElementById('addPosition').value,
                status: document.getElementById('addStatus').value,
                votes: 0,
                registered: new Date().toISOString().split('T')[0],
                platform: document.getElementById('addPlatform').value,
                notes: 'Added by admin'
            };
            
            candidatesData.unshift(newCandidate);
            filteredCandidates = [...candidatesData];
            renderCandidates();
            updatePagination();
            closeAddModal();
            
            // Reset form
            document.getElementById('addCandidateForm').reset();
            
            showNotification('New candidate added successfully', 'success');
        });

        function getElectionTitle(electionId) {
            const electionTitles = {
                'presidential-2024': 'Presidential 2024',
                'senate-2024': 'Senate 2024',
                'governor-2024': 'Governor 2024',
                'mayor-2024': 'Mayor 2024',
                'council-2024': 'City Council 2024'
            };
            return electionTitles[electionId] || electionId;
        }

        // Bulk actions
        function bulkApprove() {
            if (selectedCandidates.size === 0) return;
            
            if (confirm(`Are you sure you want to approve ${selectedCandidates.size} selected candidate(s)?`)) {
                selectedCandidates.forEach(candidateId => {
                    const candidateIndex = candidatesData.findIndex(c => c.id === candidateId);
                    if (candidateIndex !== -1) {
                        candidatesData[candidateIndex].status = 'active';
                    }
                    
                    const filteredIndex = filteredCandidates.findIndex(c => c.id === candidateId);
                    if (filteredIndex !== -1) {
                        filteredCandidates[filteredIndex].status = 'active';
                    }
                });
                
                renderCandidates();
                clearSelection();
                showNotification(`${selectedCandidates.size} candidates approved successfully`, 'success');
            }
        }

        function bulkReject() {
            if (selectedCandidates.size === 0) return;
            
            if (confirm(`Are you sure you want to reject ${selectedCandidates.size} selected candidate(s)?`)) {
                selectedCandidates.forEach(candidateId => {
                    const candidateIndex = candidatesData.findIndex(c => c.id === candidateId);
                    if (candidateIndex !== -1) {
                        candidatesData[candidateIndex].status = 'rejected';
                    }
                    
                    const filteredIndex = filteredCandidates.findIndex(c => c.id === candidateId);
                    if (filteredIndex !== -1) {
                        filteredCandidates[filteredIndex].status = 'rejected';
                    }
                });
                
                renderCandidates();
                clearSelection();
                showNotification(`${selectedCandidates.size} candidates rejected`, 'warning');
            }
        }

        function bulkExport() {
            if (selectedCandidates.size === 0) return;
            
            const selectedCandidateData = candidatesData.filter(c => selectedCandidates.has(c.id));
            const csvContent = generateCSV(selectedCandidateData);
            downloadCSV(csvContent, `candidates_export_${new Date().toISOString().split('T')[0]}.csv`);
            
            showNotification(`${selectedCandidates.size} candidates exported successfully`, 'success');
        }

        function bulkImport() {
            alert('Bulk import functionality would open a file upload dialog.\n\nSupported formats:\n• CSV files with candidate data\n• Excel spreadsheets\n• JSON format\n\nThe system would validate candidate eligibility, check for duplicates, and import records in batch with blockchain verification.');
        }

        // Utility functions
        function generateCSV(data) {
            const headers = ['ID', 'Name', 'Email', 'Phone', 'Election', 'Party', 'Position', 'Status', 'Votes', 'Registered', 'Platform'];
            const rows = data.map(candidate => [
                candidate.id,
                candidate.name,
                candidate.email,
                candidate.phone,
                candidate.electionTitle,
                getPartyText(candidate.party),
                candidate.position,
                candidate.status,
                candidate.votes,
                candidate.registered,
                candidate.platform
            ]);
            
            return [headers, ...rows].map(row => row.map(field => `"${field}"`).join(',')).join('\n');
        }

        function downloadCSV(content, filename) {
            const blob = new Blob([content], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-600' : 
                type === 'warning' ? 'bg-yellow-600' : 
                type === 'error' ? 'bg-red-600' : 'bg-blue-600'
            }`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(full)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('mobileMenuIcon');
            
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                menuIcon.className = 'fas fa-times text-xl';
            } else {
                mobileMenu.classList.add('hidden');
                menuIcon.className = 'fas fa-bars text-xl';
            }
        }

        // Toggle notifications
        function toggleNotifications() {
            alert('Notification panel would show recent candidate-related alerts:\n\n• New candidate registrations\n• Document verification updates\n• Election deadline reminders\n• Blockchain verification status\n• Vote count updates');
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey || event.metaKey) {
                switch(event.key) {
                    case 'f':
                        event.preventDefault();
                        document.getElementById('searchInput').focus();
                        break;
                    case 'n':
                        event.preventDefault();
                        addCandidate();
                        break;
                    case 'a':
                        event.preventDefault();
                        selectAll();
                        break;
                }
            }
            
            if (event.key === 'Escape') {
                closeEditModal();
                closeAddModal();
                document.getElementById('mobileMenu').classList.add('hidden');
            }
        });

        console.log('✅ Candidate Management System ready');
        console.log(`📊 Managing ${candidatesData.length} candidates across ${new Set(candidatesData.map(c => c.election)).size} elections`);
        console.log('🔍 Search, filter, and bulk operations available');
        console.log('🔗 Blockchain integration ready for vote verification');
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'97b56d98822b1192',t:'MTc1NzIzOTk1MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
