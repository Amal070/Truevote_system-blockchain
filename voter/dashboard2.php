<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueVote - Voter Dashboard</title>
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
        
        /* Gradient backgrounds */
        .voter-gradient { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
        }
        .voting-gradient { 
            background: linear-gradient(135deg, var(--accent-blue) 0%, #1d4ed8 100%); 
        }
        
        /* Status indicators */
        .status-active { 
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%); 
        }
        .status-upcoming { 
            background: linear-gradient(135deg, var(--accent-blue) 0%, #1d4ed8 100%); 
        }
        .status-completed { 
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); 
        }
        .status-voted { 
            background: linear-gradient(135deg, var(--accent-purple) 0%, #7c3aed 100%); 
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
        
        /* Vote button styles */
        .vote-btn {
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        .vote-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }
        .vote-btn:disabled {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* Election card styles */
        .election-card {
            background: var(--bg-secondary);
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }
        .election-card:hover {
            border-color: var(--accent-blue);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);
        }
        
        /* Candidate card styles */
        .candidate-card {
            background: var(--bg-secondary);
            border: 2px solid #374151;
            transition: all 0.3s ease;
        }
        .candidate-card:hover {
            border-color: var(--accent-blue);
            transform: translateY(-2px);
        }
        .candidate-card.selected {
            border-color: var(--accent-green);
            background: rgba(16, 185, 129, 0.1);
        }
        
        /* Party badge styles */
        .party-democrat { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .party-republican { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .party-independent { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .party-libertarian { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .party-green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
        
        /* Modal styles */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }
        
        /* Progress bar styles */
        .progress-bar {
            background: linear-gradient(90deg, var(--accent-blue) 0%, var(--accent-purple) 100%);
            animation: progress-glow 2s ease-in-out infinite alternate;
        }
        @keyframes progress-glow {
            from { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
            to { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); }
        }
        
        /* Notification styles */
        .notification-success {
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%);
        }
        .notification-warning {
            background: linear-gradient(135deg, var(--accent-yellow) 0%, #d97706 100%);
        }
        .notification-error {
            background: linear-gradient(135deg, var(--accent-red) 0%, #dc2626 100%);
        }
        
        /* Vote confirmation styles */
        .vote-confirmation {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            border: 2px solid var(--accent-green);
        }
        
        /* Blockchain hash styles */
        .blockchain-hash {
            font-family: 'Courier New', monospace;
            background: var(--bg-tertiary);
            border: 1px solid #475569;
            word-break: break-all;
        }
        
        /* Loading spinner */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .mobile-stack {
                flex-direction: column;
            }
            .mobile-full {
                width: 100%;
            }
        }
        
        /* Focus styles for accessibility */
        .focus-ring:focus {
            outline: 2px solid var(--accent-blue);
            outline-offset: 2px;
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
        
        /* Vote timer styles */
        .vote-timer {
            background: linear-gradient(135deg, var(--accent-red) 0%, #dc2626 100%);
            animation: timer-pulse 1s ease-in-out infinite alternate;
        }
        @keyframes timer-pulse {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }
        
        /* Security badge styles */
        .security-badge {
            background: linear-gradient(135deg, var(--accent-purple) 0%, #7c3aed 100%);
        }
        
        /* Election results styles */
        .results-bar {
            background: var(--bg-tertiary);
            overflow: hidden;
        }
        .results-fill {
            background: linear-gradient(90deg, var(--accent-blue) 0%, var(--accent-purple) 100%);
            transition: width 1s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: var(--bg-primary); color: var(--text-primary);">
    <!-- Voter Header -->
    

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Dashboard Section -->
        <div id="dashboardSection" class="section-content">
            <!-- Welcome Header -->
            <div class="voting-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="text-center lg:text-left mb-6 lg:mb-0">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                            <i class="fas fa-vote-yea mr-3"></i>Welcome, John!
                        </h1>
                        <p class="text-blue-100 text-base sm:text-lg mb-4">Your secure voting dashboard - every vote counts and is protected by blockchain technology</p>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                            <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-shield-alt mr-1"></i>Verified Voter
                            </span>
                            <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i>3 Elections Available
                            </span>
                            <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-history mr-1"></i>2 Votes Cast
                            </span>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="showSection('elections')" class="election-card px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all card-hover focus-ring" style="color: var(--text-primary);">
                            <i class="fas fa-vote-yea mr-2"></i>Vote Now
                        </button>
                        <button onclick="showSection('results')" class="election-card px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all card-hover focus-ring" style="color: var(--text-primary);">
                            <i class="fas fa-chart-bar mr-2"></i>View Results
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Available Elections -->
                <div class="election-card rounded-xl shadow-sm p-6 card-hover scale-in">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-vote-yea text-blue-400 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold" style="color: var(--text-primary);">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Available Elections</h3>
                    <div class="text-sm text-blue-400 font-medium">
                        <i class="fas fa-clock mr-1"></i>2 ending soon
                    </div>
                </div>

                <!-- Votes Cast -->
                <div class="election-card rounded-xl shadow-sm p-6 card-hover scale-in">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold" style="color: var(--text-primary);">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Votes Cast</h3>
                    <div class="text-sm text-green-400 font-medium">
                        <i class="fas fa-shield-alt mr-1"></i>Blockchain verified
                    </div>
                </div>

                <!-- Pending Results -->
                <div class="election-card rounded-xl shadow-sm p-6 card-hover scale-in">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-400 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold" style="color: var(--text-primary);">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Pending Results</h3>
                    <div class="text-sm text-yellow-400 font-medium">
                        <i class="fas fa-clock mr-1"></i>Counting in progress
                    </div>
                </div>

                <!-- Security Score -->
                <div class="election-card rounded-xl shadow-sm p-6 card-hover scale-in">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-purple-400 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-green-400">100%</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">Security Score</h3>
                    <div class="text-sm text-purple-400 font-medium">
                        <i class="fas fa-lock mr-1"></i>Fully secured
                    </div>
                </div>
            </div>

            <!-- Active Elections Preview -->
            <div class="election-card rounded-xl shadow-sm p-6 mb-8 fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-vote-yea text-blue-500 mr-2"></i>Active Elections
                    </h2>
                    <button onclick="showSection('elections')" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="activeElectionsPreview">
                    <!-- Elections will be populated by JavaScript -->
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="election-card rounded-xl shadow-sm p-6 fade-in">
                <h2 class="text-xl font-bold mb-6" style="color: var(--text-primary);">
                    <i class="fas fa-history text-green-500 mr-2"></i>Recent Activity
                </h2>
                
                <div class="space-y-4" id="recentActivity">
                    <!-- Activity items will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Elections Section -->
        <div id="electionsSection" class="section-content hidden">
            <div class="voting-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                    <i class="fas fa-vote-yea mr-3"></i>Available Elections
                </h1>
                <p class="text-blue-100 text-base sm:text-lg">Cast your vote securely with blockchain verification</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="electionsGrid">
                <!-- Elections will be populated by JavaScript -->
            </div>
        </div>

        <!-- Voting History Section -->
        <div id="votingHistorySection" class="section-content hidden">
            <div class="voting-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                    <i class="fas fa-history mr-3"></i>Voting History
                </h1>
                <p class="text-blue-100 text-base sm:text-lg">Track all your votes with blockchain verification</p>
            </div>

            <div class="election-card rounded-xl shadow-sm overflow-hidden fade-in">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold" style="color: var(--text-primary);">Your Vote Records</h2>
                        <button onclick="exportVotingHistory()" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                    </div>
                    
                    <div class="space-y-4" id="votingHistoryList">
                        <!-- Voting history will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="section-content hidden">
            <div class="voting-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                    <i class="fas fa-chart-bar mr-3"></i>Election Results
                </h1>
                <p class="text-blue-100 text-base sm:text-lg">View official results and statistics</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="resultsGrid">
                <!-- Results will be populated by JavaScript -->
            </div>
        </div>

        <!-- Profile Section -->
        <!-- <div id="profileSection" class="section-content hidden">
            <div class="voting-gradient rounded-2xl p-6 sm:p-8 mb-8 text-white fade-in">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                    <i class="fas fa-user mr-3"></i>Voter Profile
                </h1>
                <p class="text-blue-100 text-base sm:text-lg">Manage your voter information and security settings</p>
            </div> -->

            <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6"> -->
                <!-- Profile Information -->
                <!-- <div class="election-card rounded-xl shadow-sm p-6 fade-in">
                    <h2 class="text-xl font-bold mb-6" style="color: var(--text-primary);">
                        <i class="fas fa-user-circle text-blue-500 mr-2"></i>Personal Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Full Name</label>
                            <input type="text" value="" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-primary);" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Voter ID</label>
                            <input type="text" value="VTR789456" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-primary);" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email Address</label>
                            <input type="email" value="john.doe@email.com" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Phone Number</label>
                            <input type="tel" value="+1 (555) 123-4567" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Registration Date</label>
                            <input type="text" value="January 15, 2024" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-secondary); border-color: #374151; color: var(--text-primary);" readonly>
                        </div>
                    </div>
                    
                    <button class="w-full mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus-ring">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div> -->

                <!-- Security Settings -->
                <!-- <div class="election-card rounded-xl shadow-sm p-6 fade-in"> -->
                    <h2 class="text-xl font-bold mb-6" style="color: var(--text-primary);">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>Security Settings
                    </h2>
                    
                    <div class="space-y-6">
                        <!-- Two-Factor Authentication -->
                        <!-- <div class="flex items-center justify-between p-4 border rounded-lg" style="border-color: #374151;">
                            <div>
                                <h3 class="font-medium" style="color: var(--text-primary);">Two-Factor Authentication</h3>
                                <p class="text-sm" style="color: var(--text-muted);">Add an extra layer of security</p>
                            </div>
                            <div class="flex items-center">
                                <span class="text-green-400 text-sm font-medium mr-3">
                                    <i class="fas fa-check-circle mr-1"></i>Enabled
                                </span>
                                <button class="text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div> -->

                        <!-- Email Notifications -->
                        <!-- <div class="flex items-center justify-between p-4 border rounded-lg" style="border-color: #374151;">
                            <div>
                                <h3 class="font-medium" style="color: var(--text-primary);">Email Notifications</h3>
                                <p class="text-sm" style="color: var(--text-muted);">Get notified about elections and results</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div> -->

                        <!-- Blockchain Verification -->
                        <!-- <div class="flex items-center justify-between p-4 border rounded-lg" style="border-color: #374151;">
                            <div>
                                <h3 class="font-medium" style="color: var(--text-primary);">Blockchain Verification</h3>
                                <p class="text-sm" style="color: var(--text-muted);">Your votes are secured on the blockchain</p>
                            </div>
                            <div class="flex items-center">
                                <span class="text-green-400 text-sm font-medium">
                                    <i class="fas fa-link mr-1"></i>Connected
                                </span>
                            </div>
                        </div> -->

                        <!-- Change Password -->
                        <!-- <button class="w-full px-6 py-3 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors focus-ring">
                            <i class="fas fa-key mr-2"></i>Change Password
                        </button> -->
                    <!-- </div>
                </div>
            </div>
        </div> -->
    </main>

    <!-- Vote Confirmation Modal -->
    <div id="voteConfirmationModal" class="hidden fixed inset-0 z-50 modal-backdrop flex items-center justify-center p-4">
        <div class="vote-confirmation rounded-2xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto scale-in p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Vote Confirmed!</h3>
                <p class="text-lg" style="color: var(--text-secondary);">Your vote has been successfully recorded on the blockchain</p>
            </div>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center p-4 border rounded-lg" style="border-color: #374151; background-color: var(--bg-tertiary);">
                    <span style="color: var(--text-secondary);">Election:</span>
                    <span class="font-medium" style="color: var(--text-primary);" id="confirmElection">Presidential 2024</span>
                </div>
                <div class="flex justify-between items-center p-4 border rounded-lg" style="border-color: #374151; background-color: var(--bg-tertiary);">
                    <span style="color: var(--text-secondary);">Candidate:</span>
                    <span class="font-medium" style="color: var(--text-primary);" id="confirmCandidate">Sarah Mitchell</span>
                </div>
                <div class="flex justify-between items-center p-4 border rounded-lg" style="border-color: #374151; background-color: var(--bg-tertiary);">
                    <span style="color: var(--text-secondary);">Vote Time:</span>
                    <span class="font-medium" style="color: var(--text-primary);" id="confirmTime">2024-03-15 14:30:25 UTC</span>
                </div>
                <div class="p-4 border rounded-lg" style="border-color: #374151; background-color: var(--bg-tertiary);">
                    <div class="flex justify-between items-center mb-2">
                        <span style="color: var(--text-secondary);">Blockchain Hash:</span>
                        <button onclick="copyToClipboard(document.getElementById('blockchainHash').textContent)" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="blockchain-hash p-3 rounded text-sm" id="blockchainHash" style="color: var(--text-primary);">
                        0x7d4a8f2e9c1b6a5d3e8f7c2a9b4e6d1c8f5a2e7b9c4d6a1e8f3c7b2a5d9e4f6c1a8
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="closeVoteConfirmation()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus-ring">
                    <i class="fas fa-check mr-2"></i>Continue Voting
                </button>
                <button onclick="viewVotingHistory()" class="flex-1 px-6 py-3 border border-gray-500 rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="color: var(--text-secondary);">
                    <i class="fas fa-history mr-2"></i>View History
                </button>
            </div>
        </div>
    </div>

    <!-- Voting Modal -->
    <div id="votingModal" class="hidden fixed inset-0 z-50 modal-backdrop flex items-center justify-center p-4">
        <div class="rounded-2xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto scale-in" style="background-color: var(--bg-secondary);">
            <div class="p-6 border-b border-gray-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-vote-yea text-blue-500 mr-2"></i>
                        Cast Your Vote
                    </h3>
                    <button onclick="closeVotingModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <h4 class="text-lg font-medium" style="color: var(--text-secondary);" id="votingElectionTitle">Presidential Election 2024</h4>
                    <p class="text-sm" style="color: var(--text-muted);" id="votingElectionDescription">Select your preferred candidate for President of the United States</p>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="candidatesList">
                    <!-- Candidates will be populated by JavaScript -->
                </div>
                
                <div class="border-t border-gray-600 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-medium" style="color: var(--text-primary);">Selected Candidate:</h4>
                            <p class="text-sm" style="color: var(--text-muted);" id="selectedCandidateInfo">No candidate selected</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm" style="color: var(--text-muted);">Time Remaining:</div>
                            <div class="vote-timer text-white px-3 py-1 rounded-full text-sm font-bold" id="votingTimer">
                                <i class="fas fa-clock mr-1"></i>29:45
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="closeVotingModal()" class="flex-1 px-6 py-3 border border-gray-500 rounded-lg hover:bg-gray-600 transition-colors focus-ring" style="color: var(--text-secondary);">
                            Cancel
                        </button>
                        <button onclick="submitVote()" id="submitVoteBtn" disabled class="flex-1 vote-btn text-white px-6 py-3 rounded-lg font-semibold focus-ring">
                            <i class="fas fa-vote-yea mr-2"></i>Submit Vote
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-gray-700 mt-12" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="text-sm mb-4 sm:mb-0" style="color: var(--text-muted);">
                    &copy; 2024 TrueVote Digital Voting Platform. All rights reserved.
                </div>
                <div class="flex items-center space-x-6 text-sm" style="color: var(--text-muted);">
                    <span>Secure • Transparent • Verified</span>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-400 transition-colors">Help Center</a>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a>
                    <span>•</span>
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-shield-alt text-green-400"></i>
                        <span class="text-green-400">Blockchain Secured</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Sample data
        const voterData = {
            id: 'VTR789456',
            name: 'John Doe',
            email: 'john.doe@email.com',
            phone: '+1 (555) 123-4567',
            registrationDate: '2024-01-15',
            votingHistory: []
        };

        const electionsData = [
            {
                id: 'presidential-2024',
                title: 'Presidential Election 2024',
                description: 'Choose the next President of the United States',
                status: 'active',
                startDate: '2024-03-01',
                endDate: '2024-03-31',
                hasVoted: false,
                candidates: [
                    {
                        id: 'sarah-mitchell',
                        name: 'Sarah Mitchell',
                        party: 'democrat',
                        platform: 'Healthcare reform, climate action, economic equality',
                        votes: 15847
                    },
                    {
                        id: 'robert-johnson',
                        name: 'Robert Johnson',
                        party: 'republican',
                        platform: 'Economic growth, border security, traditional values',
                        votes: 14523
                    },
                    {
                        id: 'jennifer-chen',
                        name: 'Jennifer Chen',
                        party: 'independent',
                        platform: 'Government transparency, fiscal responsibility, social progress',
                        votes: 8934
                    }
                ]
            },
            {
                id: 'senate-2024',
                title: 'Senate Election 2024',
                description: 'Select your state senator',
                status: 'active',
                startDate: '2024-03-01',
                endDate: '2024-03-25',
                hasVoted: true,
                votedFor: 'maria-rodriguez',
                voteTime: '2024-03-10T14:30:25Z',
                blockchainHash: '0x7d4a8f2e9c1b6a5d3e8f7c2a9b4e6d1c8f5a2e7b9c4d6a1e8f3c7b2a5d9e4f6c1a8',
                candidates: [
                    {
                        id: 'maria-rodriguez',
                        name: 'Maria Rodriguez',
                        party: 'democrat',
                        platform: 'Education funding, infrastructure investment, healthcare access',
                        votes: 8934
                    },
                    {
                        id: 'david-thompson',
                        name: 'David Thompson',
                        party: 'republican',
                        platform: 'State fiscal responsibility, business development, law and order',
                        votes: 7621
                    }
                ]
            },
            {
                id: 'mayor-2024',
                title: 'Mayoral Election 2024',
                description: 'Choose your city mayor',
                status: 'upcoming',
                startDate: '2024-04-01',
                endDate: '2024-04-15',
                hasVoted: false,
                candidates: [
                    {
                        id: 'jennifer-chen-mayor',
                        name: 'Jennifer Chen',
                        party: 'independent',
                        platform: 'Urban development, public transportation, community engagement',
                        votes: 0
                    },
                    {
                        id: 'michael-brown',
                        name: 'Michael Brown',
                        party: 'democrat',
                        platform: 'Affordable housing, environmental sustainability, public safety',
                        votes: 0
                    }
                ]
            },
            {
                id: 'governor-2023',
                title: 'Governor Election 2023',
                description: 'State governor election',
                status: 'completed',
                startDate: '2023-11-01',
                endDate: '2023-11-15',
                hasVoted: true,
                votedFor: 'lisa-anderson',
                voteTime: '2023-11-08T10:15:30Z',
                blockchainHash: '0x9f3e7c1a8d5b2e6f4c9a7e3b8d1f5c2a9e6b4d7f1c8a5e2b9f6c3d8a1e4f7c2b5',
                candidates: [
                    {
                        id: 'lisa-anderson',
                        name: 'Lisa Anderson',
                        party: 'green',
                        platform: 'Environmental protection, sustainable development, social justice',
                        votes: 234567,
                        winner: true
                    },
                    {
                        id: 'christopher-davis',
                        name: 'Christopher Davis',
                        party: 'republican',
                        platform: 'Economic development, infrastructure, public safety',
                        votes: 198432
                    }
                ]
            }
        ];

        let currentSection = 'dashboard';
        let selectedCandidate = null;
        let currentElection = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🗳️ TrueVote Voter Dashboard initialized');
            renderDashboard();
            renderElections();
            renderVotingHistory();
            renderResults();
            startTimers();
        });

        // Section navigation
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected section
            document.getElementById(sectionName + 'Section').classList.remove('hidden');
            
            // Update navigation
            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('text-white', 'font-semibold', 'border-b-2', 'border-blue-400');
                link.classList.add('text-gray-300', 'hover:text-white', 'font-medium');
            });
            
            const activeLink = document.querySelector(`a[href="#${sectionName}"]`);
            if (activeLink) {
                activeLink.classList.remove('text-gray-300', 'hover:text-white', 'font-medium');
                activeLink.classList.add('text-white', 'font-semibold', 'border-b-2', 'border-blue-400');
            }
            
            currentSection = sectionName;
        }

        // Render dashboard
        function renderDashboard() {
            renderActiveElectionsPreview();
            renderRecentActivity();
        }

        function renderActiveElectionsPreview() {
            const container = document.getElementById('activeElectionsPreview');
            const activeElections = electionsData.filter(e => e.status === 'active').slice(0, 3);
            
            container.innerHTML = activeElections.map(election => `
                <div class="election-card rounded-xl p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold" style="color: var(--text-primary);">${election.title}</h3>
                        <span class="status-${election.status} text-white px-3 py-1 rounded-full text-xs font-medium">
                            ${election.hasVoted ? 'Voted' : 'Active'}
                        </span>
                    </div>
                    
                    <p class="text-sm mb-4" style="color: var(--text-muted);">${election.description}</p>
                    
                    <div class="flex items-center justify-between text-sm mb-4" style="color: var(--text-secondary);">
                        <span><i class="fas fa-calendar mr-1"></i>Ends: ${formatDate(election.endDate)}</span>
                        <span><i class="fas fa-users mr-1"></i>${election.candidates.length} candidates</span>
                    </div>
                    
                    ${election.hasVoted ? `
                        <button class="w-full px-4 py-2 bg-gray-600 text-gray-300 rounded-lg cursor-not-allowed" disabled>
                            <i class="fas fa-check-circle mr-2"></i>Vote Cast
                        </button>
                    ` : `
                        <button onclick="openVotingModal('${election.id}')" class="w-full vote-btn text-white px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-vote-yea mr-2"></i>Vote Now
                        </button>
                    `}
                </div>
            `).join('');
        }

        function renderRecentActivity() {
            const container = document.getElementById('recentActivity');
            const activities = [
                {
                    type: 'vote',
                    title: 'Vote Cast - Senate Election 2024',
                    description: 'Your vote for Maria Rodriguez has been recorded',
                    time: '2024-03-10T14:30:25Z',
                    icon: 'fas fa-vote-yea',
                    color: 'text-green-400'
                },
                {
                    type: 'registration',
                    title: 'Registered for Presidential Election 2024',
                    description: 'You are now eligible to vote in this election',
                    time: '2024-03-01T09:15:00Z',
                    icon: 'fas fa-user-check',
                    color: 'text-blue-400'
                },
                {
                    type: 'verification',
                    title: 'Blockchain Verification Complete',
                    description: 'Your vote in Governor Election 2023 has been verified',
                    time: '2023-11-08T10:20:00Z',
                    icon: 'fas fa-shield-alt',
                    color: 'text-purple-400'
                }
            ];
            
            container.innerHTML = activities.map(activity => `
                <div class="flex items-start space-x-4 p-4 border rounded-lg hover:bg-gray-700 hover:bg-opacity-30 transition-colors" style="border-color: #374151;">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                        <i class="${activity.icon} ${activity.color}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium" style="color: var(--text-primary);">${activity.title}</h4>
                        <p class="text-sm" style="color: var(--text-muted);">${activity.description}</p>
                        <p class="text-xs mt-1" style="color: var(--text-muted);">${formatDateTime(activity.time)}</p>
                    </div>
                </div>
            `).join('');
        }

        // Render elections
        function renderElections() {
            const container = document.getElementById('electionsGrid');
            
            container.innerHTML = electionsData.map(election => `
                <div class="election-card rounded-xl p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold" style="color: var(--text-primary);">${election.title}</h3>
                        <span class="status-${election.hasVoted ? 'voted' : election.status} text-white px-3 py-1 rounded-full text-xs font-medium">
                            ${election.hasVoted ? 'Voted' : getStatusText(election.status)}
                        </span>
                    </div>
                    
                    <p class="text-sm mb-6" style="color: var(--text-muted);">${election.description}</p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                            <span><i class="fas fa-calendar-alt mr-2"></i>Start Date:</span>
                            <span>${formatDate(election.startDate)}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                            <span><i class="fas fa-calendar-check mr-2"></i>End Date:</span>
                            <span>${formatDate(election.endDate)}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                            <span><i class="fas fa-users mr-2"></i>Candidates:</span>
                            <span>${election.candidates.length}</span>
                        </div>
                        ${election.hasVoted ? `
                            <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                                <span><i class="fas fa-check-circle mr-2"></i>Voted For:</span>
                                <span class="font-medium text-green-400">${getCandidateName(election.votedFor, election.candidates)}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="space-y-3">
                        ${election.status === 'active' && !election.hasVoted ? `
                            <button onclick="openVotingModal('${election.id}')" class="w-full vote-btn text-white px-4 py-3 rounded-lg font-semibold">
                                <i class="fas fa-vote-yea mr-2"></i>Cast Vote
                            </button>
                        ` : election.hasVoted ? `
                            <button class="w-full px-4 py-3 bg-gray-600 text-gray-300 rounded-lg cursor-not-allowed" disabled>
                                <i class="fas fa-check-circle mr-2"></i>Vote Already Cast
                            </button>
                        ` : election.status === 'upcoming' ? `
                            <button class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg cursor-not-allowed" disabled>
                                <i class="fas fa-clock mr-2"></i>Voting Opens ${formatDate(election.startDate)}
                            </button>
                        ` : `
                            <button onclick="viewResults('${election.id}')" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-chart-bar mr-2"></i>View Results
                            </button>
                        `}
                        
                        <button onclick="viewCandidates('${election.id}')" class="w-full px-4 py-3 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-users mr-2"></i>View Candidates
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Render voting history
        function renderVotingHistory() {
            const container = document.getElementById('votingHistoryList');
            const votedElections = electionsData.filter(e => e.hasVoted);
            
            container.innerHTML = votedElections.map(election => `
                <div class="border rounded-xl p-6 hover:bg-gray-700 hover:bg-opacity-30 transition-colors" style="border-color: #374151;">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">${election.title}</h3>
                            <div class="flex items-center space-x-4 text-sm" style="color: var(--text-secondary);">
                                <span><i class="fas fa-user mr-1"></i>Voted for: <strong class="text-green-400">${getCandidateName(election.votedFor, election.candidates)}</strong></span>
                                <span><i class="fas fa-calendar mr-1"></i>${formatDateTime(election.voteTime)}</span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0">
                            <span class="status-voted text-white px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-shield-alt mr-1"></i>Blockchain Verified
                            </span>
                        </div>
                    </div>
                    
                    <div class="border rounded-lg p-4 mb-4" style="border-color: #374151; background-color: var(--bg-tertiary);">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium" style="color: var(--text-secondary);">Blockchain Hash:</span>
                            <button onclick="copyToClipboard('${election.blockchainHash}')" class="text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="blockchain-hash p-2 rounded text-xs break-all" style="color: var(--text-primary);">
                            ${election.blockchainHash}
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button onclick="verifyVote('${election.id}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-search mr-1"></i>Verify on Blockchain
                        </button>
                        <button onclick="downloadVoteCertificate('${election.id}')" class="px-4 py-2 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors text-sm">
                            <i class="fas fa-download mr-1"></i>Download Certificate
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Render results
        function renderResults() {
            const container = document.getElementById('resultsGrid');
            const completedElections = electionsData.filter(e => e.status === 'completed');
            
            container.innerHTML = completedElections.map(election => {
                const totalVotes = election.candidates.reduce((sum, candidate) => sum + candidate.votes, 0);
                
                return `
                    <div class="election-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold" style="color: var(--text-primary);">${election.title}</h3>
                            <span class="status-completed text-white px-3 py-1 rounded-full text-xs font-medium">
                                Completed
                            </span>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            ${election.candidates.sort((a, b) => b.votes - a.votes).map((candidate, index) => {
                                const percentage = totalVotes > 0 ? (candidate.votes / totalVotes * 100).toFixed(1) : 0;
                                return `
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                ${candidate.winner ? '<i class="fas fa-crown text-yellow-400"></i>' : `<span class="text-gray-400">${index + 1}.</span>`}
                                                <span class="font-medium" style="color: var(--text-primary);">${candidate.name}</span>
                                                <span class="party-${candidate.party} text-white px-2 py-1 rounded-full text-xs">
                                                    ${getPartyText(candidate.party)}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold" style="color: var(--text-primary);">${candidate.votes.toLocaleString()}</div>
                                                <div class="text-sm" style="color: var(--text-muted);">${percentage}%</div>
                                            </div>
                                        </div>
                                        <div class="results-bar h-2 rounded-full">
                                            <div class="results-fill h-full rounded-full" style="width: ${percentage}%"></div>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        
                        <div class="border-t border-gray-600 pt-4">
                            <div class="flex items-center justify-between text-sm" style="color: var(--text-secondary);">
                                <span>Total Votes: <strong>${totalVotes.toLocaleString()}</strong></span>
                                <span>Completed: ${formatDate(election.endDate)}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Voting modal functions
        function openVotingModal(electionId) {
            const election = electionsData.find(e => e.id === electionId);
            if (!election || election.hasVoted) return;
            
            currentElection = election;
            selectedCandidate = null;
            
            document.getElementById('votingElectionTitle').textContent = election.title;
            document.getElementById('votingElectionDescription').textContent = election.description;
            
            renderCandidatesList(election.candidates);
            updateSelectedCandidateInfo();
            
            document.getElementById('votingModal').classList.remove('hidden');
            startVotingTimer();
        }

        function closeVotingModal() {
            document.getElementById('votingModal').classList.add('hidden');
            selectedCandidate = null;
            currentElection = null;
        }

        function renderCandidatesList(candidates) {
            const container = document.getElementById('candidatesList');
            
            container.innerHTML = candidates.map(candidate => `
                <div class="candidate-card rounded-xl p-4 cursor-pointer" onclick="selectCandidate('${candidate.id}')">
                    <div class="flex items-center space-x-4 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            ${candidate.name.split(' ').map(n => n[0]).join('')}
                        </div>
                        <div>
                            <h4 class="font-bold" style="color: var(--text-primary);">${candidate.name}</h4>
                            <span class="party-${candidate.party} text-white px-2 py-1 rounded-full text-xs">
                                ${getPartyText(candidate.party)}
                            </span>
                        </div>
                    </div>
                    <p class="text-sm" style="color: var(--text-muted);">${candidate.platform}</p>
                </div>
            `).join('');
        }

        function selectCandidate(candidateId) {
            selectedCandidate = candidateId;
            
            // Update visual selection
            document.querySelectorAll('.candidate-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            event.currentTarget.classList.add('selected');
            
            updateSelectedCandidateInfo();
        }

        function updateSelectedCandidateInfo() {
            const infoElement = document.getElementById('selectedCandidateInfo');
            const submitBtn = document.getElementById('submitVoteBtn');
            
            if (selectedCandidate && currentElection) {
                const candidate = currentElection.candidates.find(c => c.id === selectedCandidate);
                if (candidate) {
                    infoElement.textContent = `${candidate.name} (${getPartyText(candidate.party)})`;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    infoElement.textContent = 'No candidate selected';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                infoElement.textContent = 'No candidate selected';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function submitVote() {
            if (!selectedCandidate || !currentElection) return;
            
            const candidate = currentElection.candidates.find(c => c.id === selectedCandidate);
            if (!candidate) return;
            
            // Simulate vote submission
            const voteTime = new Date().toISOString();
            const blockchainHash = generateBlockchainHash();
            
            // Update election data
            const electionIndex = electionsData.findIndex(e => e.id === currentElection.id);
            if (electionIndex !== -1) {
                electionsData[electionIndex].hasVoted = true;
                electionsData[electionIndex].votedFor = selectedCandidate;
                electionsData[electionIndex].voteTime = voteTime;
                electionsData[electionIndex].blockchainHash = blockchainHash;
            }
            
            // Show confirmation modal
            document.getElementById('confirmElection').textContent = currentElection.title;
            document.getElementById('confirmCandidate').textContent = candidate.name;
            document.getElementById('confirmTime').textContent = formatDateTime(voteTime);
            document.getElementById('blockchainHash').textContent = blockchainHash;
            
            closeVotingModal();
            document.getElementById('voteConfirmationModal').classList.remove('hidden');
            
            // Update displays
            renderDashboard();
            renderElections();
            renderVotingHistory();
            
            showNotification('Vote successfully cast and recorded on blockchain!', 'success');
        }

        function closeVoteConfirmation() {
            document.getElementById('voteConfirmationModal').classList.add('hidden');
        }

        function viewVotingHistory() {
            closeVoteConfirmation();
            showSection('voting-history');
        }

        // Utility functions
        function getStatusText(status) {
            const statusMap = {
                'active': 'Active',
                'upcoming': 'Upcoming',
                'completed': 'Completed'
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

        function getCandidateName(candidateId, candidates) {
            const candidate = candidates.find(c => c.id === candidateId);
            return candidate ? candidate.name : 'Unknown';
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function formatDateTime(dateString) {
            return new Date(dateString).toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZoneName: 'short'
            });
        }

        function generateBlockchainHash() {
            const chars = '0123456789abcdef';
            let hash = '0x';
            for (let i = 0; i < 64; i++) {
                hash += chars[Math.floor(Math.random() * chars.length)];
            }
            return hash;
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Blockchain hash copied to clipboard!', 'success');
            }).catch(() => {
                showNotification('Failed to copy to clipboard', 'error');
            });
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300 notification-${type}`;
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
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Timer functions
        function startTimers() {
            // Update voting timers every second
            setInterval(updateVotingTimers, 1000);
        }

        function startVotingTimer() {
            let timeLeft = 30 * 60; // 30 minutes in seconds
            
            const timer = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('votingTimer').innerHTML = `
                    <i class="fas fa-clock mr-1"></i>${minutes}:${seconds.toString().padStart(2, '0')}
                `;
                
                timeLeft--;
                
                if (timeLeft < 0) {
                    clearInterval(timer);
                    closeVotingModal();
                    showNotification('Voting session expired. Please try again.', 'warning');
                }
            }, 1000);
        }

        function updateVotingTimers() {
            // Update election end times, etc.
            // This would be implemented based on actual election end times
        }

        // Action functions
        function viewCandidates(electionId) {
            const election = electionsData.find(e => e.id === electionId);
            if (election) {
                alert(`Candidates for ${election.title}:\n\n${election.candidates.map(c => `• ${c.name} (${getPartyText(c.party)})\n  Platform: ${c.platform}`).join('\n\n')}`);
            }
        }

        function viewResults(electionId) {
            showSection('results');
        }

        function verifyVote(electionId) {
            const election = electionsData.find(e => e.id === electionId);
            if (election && election.blockchainHash) {
                alert(`Blockchain Verification\n\nElection: ${election.title}\nVote Hash: ${election.blockchainHash}\n\nStatus: ✅ VERIFIED\nBlock Number: #${Math.floor(Math.random() * 1000000)}\nConfirmations: ${Math.floor(Math.random() * 100) + 50}\n\nYour vote has been successfully verified on the blockchain and cannot be altered or deleted.`);
            }
        }

        function downloadVoteCertificate(electionId) {
            const election = electionsData.find(e => e.id === electionId);
            if (election) {
                showNotification('Vote certificate download started', 'success');
                // In a real implementation, this would generate and download a PDF certificate
            }
        }

        function exportVotingHistory() {
            const votedElections = electionsData.filter(e => e.hasVoted);
            const csvContent = generateVotingHistoryCSV(votedElections);
            downloadCSV(csvContent, `voting_history_${voterData.id}_${new Date().toISOString().split('T')[0]}.csv`);
            showNotification('Voting history exported successfully', 'success');
        }

        function generateVotingHistoryCSV(elections) {
            const headers = ['Election', 'Candidate', 'Vote Time', 'Blockchain Hash', 'Status'];
            const rows = elections.map(election => [
                election.title,
                getCandidateName(election.votedFor, election.candidates),
                formatDateTime(election.voteTime),
                election.blockchainHash,
                'Verified'
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
            alert('Notification panel would show:\n\n• New elections available\n• Voting reminders\n• Election results published\n• Blockchain verification updates\n• Security alerts');
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showNotification('Logging out securely...', 'info');
                setTimeout(() => {
                    alert('You have been securely logged out. Thank you for using TrueVote!');
                    // In a real implementation, this would redirect to login page
                }, 1500);
            }
        }

        console.log('✅ TrueVote Voter Dashboard ready');
        console.log(`👤 Logged in as: ${voterData.name} (${voterData.id})`);
        console.log(`🗳️ ${electionsData.length} elections available`);
        console.log('🔒 Blockchain security enabled');
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'97b5798692901192',t:'MTc1NzI0MDQzOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
