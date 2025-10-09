<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueVote - Secure Digital Voting Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
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
            --accent-purple: #8b5cf6;
        }
        
        /* Hero gradient background */
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .scale-in {
            animation: scaleIn 0.6s ease-out;
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-blue) 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }
        
        .btn-outline {
            border: 2px solid var(--accent-blue);
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background: var(--accent-blue);
            transform: translateY(-2px);
        }
        
        /* Card styles */
        .feature-card {
            background: var(--bg-secondary);
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-blue);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        /* Security badge animation */
        .security-pulse {
            animation: securityPulse 2s ease-in-out infinite;
        }
        
        @keyframes securityPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Blockchain visualization */
        .blockchain-visual {
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            animation: blockchainGlow 3s ease-in-out infinite alternate;
        }
        
        @keyframes blockchainGlow {
            from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            to { box-shadow: 0 0 40px rgba(139, 92, 246, 0.8); }
        }
        
        /* Statistics counter animation */
        .stat-number {
            background: linear-gradient(135deg, var(--accent-green) 0%, var(--accent-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Navigation styles */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-blue);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.125rem;
            }
        }
        
        /* Focus styles for accessibility */
        .focus-ring:focus {
            outline: 2px solid var(--accent-blue);
            outline-offset: 2px;
        }
        
        /* Loading animation for buttons */
        .loading {
            position: relative;
            overflow: hidden;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Testimonial styles */
        .testimonial-card {
            background: var(--bg-secondary);
            border-left: 4px solid var(--accent-blue);
        }
        
        /* Footer gradient */
        .footer-gradient {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: var(--bg-primary); color: var(--text-primary);">
    <!-- Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md border-b border-gray-700" style="background-color: rgba(15, 23, 42, 0.9);">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-vote-yea text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white">TrueVote</span>
                        <div class="text-xs text-gray-300 hidden sm:block">Secure Digital Voting</div>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="nav-link text-gray-300 hover:text-white font-medium">Features</a>
                    <a href="#security" class="nav-link text-gray-300 hover:text-white font-medium">Security</a>
                    <a href="#about" class="nav-link text-gray-300 hover:text-white font-medium">About</a>
                    <a href="#contact" class="nav-link text-gray-300 hover:text-white font-medium">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3"> 
    <!-- Login Button -->
    <a href="login.php" class="btn-outline text-blue-400 px-4 py-2 rounded-lg font-medium focus-ring">
        <i class="fas fa-sign-in-alt mr-2"></i>Login
    </a>

    <!-- Register Button -->
    <a href="voter/register.php" class="btn-primary text-white px-4 py-2 rounded-lg font-medium focus-ring">
        <i class="fas fa-user-plus mr-2"></i>Register
    </a>
    
    <!-- Mobile Menu Button -->
    <button class="md:hidden text-gray-300 hover:text-white p-2 rounded-lg focus-ring" onclick="toggleMobileMenu()">
        <i class="fas fa-bars text-xl" id="mobileMenuIcon"></i>
    </button>
</div>

            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-600" style="background-color: var(--bg-secondary);">
            <div class="px-4 py-6 space-y-4">
                <a href="#features" class="block text-gray-300 hover:text-white font-medium py-2" onclick="toggleMobileMenu()">Features</a>
                <a href="#security" class="block text-gray-300 hover:text-white font-medium py-2" onclick="toggleMobileMenu()">Security</a>
                <a href="#about" class="block text-gray-300 hover:text-white font-medium py-2" onclick="toggleMobileMenu()">About</a>
                <a href="#contact" class="block text-gray-300 hover:text-white font-medium py-2" onclick="toggleMobileMenu()">Contact</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center justify-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left fade-in">
                    <div class="mb-6">
                        <span class="security-pulse bg-gradient-to-r from-green-400 to-blue-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                            <i class="fas fa-shield-alt mr-2"></i>100% Blockchain Secured
                        </span>
                    </div>
                    
                    <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        The Future of
                        <span class="bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                            Digital Voting
                        </span>
                    </h1>
                    
                    <p class="hero-subtitle text-xl text-gray-300 mb-8 leading-relaxed">
                        Experience secure, transparent, and verifiable elections powered by blockchain technology. 
                        Every vote counts, every vote is protected, every vote is verified.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-8">
                        <button onclick="openRegisterModal()" class="btn-secondary text-white px-8 py-4 rounded-xl font-semibold text-lg focus-ring">
                            <i class="fas fa-user-plus mr-2"></i>Register to Vote
                        </button>
                        <button onclick="learnMore()" class="btn-outline text-blue-400 px-8 py-4 rounded-xl font-semibold text-lg focus-ring">
                            <i class="fas fa-play mr-2"></i>Learn More
                        </button>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6 text-sm text-gray-400">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lock text-green-400"></i>
                            <span>End-to-End Encrypted</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-shield-alt text-blue-400"></i>
                            <span>Blockchain Verified</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-eye text-purple-400"></i>
                            <span>Fully Transparent</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual -->
                <div class="relative slide-in-right">
                    <div class="blockchain-visual rounded-2xl p-8 text-center">
                        <div class="mb-6">
                            <i class="fas fa-vote-yea text-6xl text-white mb-4"></i>
                            <h3 class="text-2xl font-bold text-white mb-2">Secure Voting Dashboard</h3>
                            <p class="text-blue-100">Real-time blockchain verification</p>
                        </div>
                        
                        <!-- Mock Dashboard Preview -->
                        <div class="bg-white bg-opacity-10 rounded-xl p-6 backdrop-blur-sm">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                    <div class="text-2xl font-bold text-white">3</div>
                                    <div class="text-xs text-blue-100">Active Elections</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                    <div class="text-2xl font-bold text-white">100%</div>
                                    <div class="text-xs text-blue-100">Security Score</div>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-blue-100">Blockchain Status:</span>
                                    <span class="text-green-300 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="scale-in">
                    <div class="stat-number text-4xl font-bold mb-2">250K+</div>
                    <div class="text-gray-400">Registered Voters</div>
                </div>
                <div class="scale-in">
                    <div class="stat-number text-4xl font-bold mb-2">500+</div>
                    <div class="text-gray-400">Elections Completed</div>
                </div>
                <div class="scale-in">
                    <div class="stat-number text-4xl font-bold mb-2">99.9%</div>
                    <div class="text-gray-400">Uptime Guarantee</div>
                </div>
                <div class="scale-in">
                    <div class="stat-number text-4xl font-bold mb-2">0</div>
                    <div class="text-gray-400">Security Breaches</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Why Choose <span class="text-blue-400">TrueVote</span>?
                </h2>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                    Experience the most secure, transparent, and user-friendly digital voting platform ever created
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Blockchain Security -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Blockchain Security</h3>
                    <p class="text-gray-400 mb-4">
                        Every vote is cryptographically secured and stored on an immutable blockchain, ensuring complete transparency and preventing tampering.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Immutable vote records</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Real-time verification</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Cryptographic proof</li>
                    </ul>
                </div>

                <!-- User-Friendly Interface -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Easy to Use</h3>
                    <p class="text-gray-400 mb-4">
                        Intuitive design makes voting simple for everyone. Access from any device with our responsive, accessible interface.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Mobile responsive</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Accessibility compliant</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Multi-language support</li>
                    </ul>
                </div>

                <!-- Real-time Results -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Real-time Results</h3>
                    <p class="text-gray-400 mb-4">
                        Watch election results update in real-time with complete transparency. Every vote is counted and verified instantly.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Live vote counting</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Transparent analytics</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Instant notifications</li>
                    </ul>
                </div>

                <!-- Identity Verification -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Identity Verification</h3>
                    <p class="text-gray-400 mb-4">
                        Advanced biometric and multi-factor authentication ensures only eligible voters can participate in elections.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Biometric verification</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Two-factor authentication</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Fraud prevention</li>
                    </ul>
                </div>

                <!-- Audit Trail -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-search text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Complete Audit Trail</h3>
                    <p class="text-gray-400 mb-4">
                        Every action is logged and verifiable. Independent auditors can verify election integrity at any time.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Immutable logs</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Independent verification</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Public transparency</li>
                    </ul>
                </div>

                <!-- 24/7 Support -->
                <div class="feature-card rounded-2xl p-8 scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">24/7 Support</h3>
                    <p class="text-gray-400 mb-4">
                        Round-the-clock technical support ensures smooth elections. Our expert team is always ready to help.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Live chat support</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Phone assistance</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Video tutorials</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section id="security" class="py-20" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                        <span class="text-green-400">Military-Grade</span> Security
                    </h2>
                    <p class="text-xl text-gray-400 mb-8">
                        Our platform uses the same security standards trusted by governments and financial institutions worldwide.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lock text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">End-to-End Encryption</h3>
                                <p class="text-gray-400">AES-256 encryption protects your vote from the moment you cast it until it's recorded on the blockchain.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-fingerprint text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">Biometric Authentication</h3>
                                <p class="text-gray-400">Advanced biometric verification ensures only authorized voters can access the system.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-cube text-purple-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">Blockchain Immutability</h3>
                                <p class="text-gray-400">Once recorded, votes cannot be altered, deleted, or manipulated by anyone, including system administrators.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slide-in-right">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-gray-700">
                        <h3 class="text-xl font-bold mb-6 text-center" style="color: var(--text-primary);">Security Certifications</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-certificate text-green-400 text-xl"></i>
                                </div>
                                <div class="text-sm font-medium" style="color: var(--text-primary);">ISO 27001</div>
                                <div class="text-xs text-gray-400">Certified</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-shield-alt text-blue-400 text-xl"></i>
                                </div>
                                <div class="text-sm font-medium" style="color: var(--text-primary);">SOC 2 Type II</div>
                                <div class="text-xs text-gray-400">Compliant</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-16 h-16 bg-purple-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-eye text-purple-400 text-xl"></i>
                                </div>
                                <div class="text-sm font-medium" style="color: var(--text-primary);">GDPR</div>
                                <div class="text-xs text-gray-400">Compliant</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-16 h-16 bg-red-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-bug text-red-400 text-xl"></i>
                                </div>
                                <div class="text-sm font-medium" style="color: var(--text-primary);">Penetration</div>
                                <div class="text-xs text-gray-400">Tested</div>
                            </div>
                        </div>
                        
                        <div class="mt-8 p-4 bg-green-600 bg-opacity-10 rounded-lg border border-green-600 border-opacity-30">
                            <div class="flex items-center justify-center space-x-2 text-green-400">
                                <i class="fas fa-check-circle"></i>
                                <span class="font-medium">Zero Security Incidents Since Launch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20" style="background-color: var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    About <span class="text-blue-400">TrueVote</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                    Revolutionizing democracy through secure, transparent, and accessible digital voting technology.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <h3 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">Our Mission</h3>
                    <p class="text-gray-400 mb-6">
                        TrueVote was founded with a simple yet powerful mission: to make voting more secure, accessible, and trustworthy for everyone. We believe that every voice deserves to be heard, and every vote deserves to be protected.
                    </p>
                    <p class="text-gray-400 mb-6">
                        By leveraging cutting-edge blockchain technology and advanced security measures, we ensure that elections are not only fair but also verifiable by anyone, anywhere, at any time.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold" style="color: var(--text-primary);">Trusted by Millions</div>
                            <div class="text-sm text-gray-400">Over 250K registered voters worldwide</div>
                        </div>
                    </div>
                </div>

                <div class="slide-in-right">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-gray-700">
                        <h4 class="text-xl font-bold mb-6 text-center" style="color: var(--text-primary);">Why Choose Us?</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-shield-alt text-green-400"></i>
                                <span class="text-gray-400">Military-grade security</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clock text-blue-400"></i>
                                <span class="text-gray-400">Real-time verification</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-globe text-purple-400"></i>
                                <span class="text-gray-400">Global accessibility</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-green-400"></i>
                                <span class="text-gray-400">100% transparent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Get in <span class="text-blue-400">Touch</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Have questions about TrueVote? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="slide-in-left">
                    <div class="rounded-2xl p-8 border border-gray-700" style="background-color: var(--bg-secondary);">
                        <h3 class="text-xl font-bold mb-6" style="color: var(--text-primary);">Send us a Message</h3>
                        <form>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Name</label>
                                    <input type="text" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Your full name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email</label>
                                    <input type="email" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="your@email.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Subject</label>
                                    <input type="text" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="How can we help?">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Message</label>
                                    <textarea rows="4" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Tell us more..."></textarea>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 btn-primary text-white px-6 py-3 rounded-lg font-semibold focus-ring">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="slide-in-right">
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xl font-bold mb-6" style="color: var(--text-primary);">Contact Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-blue-400"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold" style="color: var(--text-primary);">Email Us</div>
                                        <div class="text-gray-400">support@truevote.com</div>
                                        <div class="text-sm text-gray-500">We respond within 24 hours</div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-green-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-phone text-green-400"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold" style="color: var(--text-primary);">Call Us</div>
                                        <div class="text-gray-400">+1 (555) 123-4567</div>
                                        <div class="text-sm text-gray-500">Mon-Fri, 9AM-6PM EST</div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-purple-600 bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-purple-400"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold" style="color: var(--text-primary);">Visit Us</div>
                                        <div class="text-gray-400">123 Democracy Street<br>Silicon Valley, CA 94000</div>
                                        <div class="text-sm text-gray-500">By appointment only</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700">
                            <h4 class="text-lg font-bold mb-4 text-center" style="color: var(--text-primary);">Follow Us</h4>
                            <div class="flex justify-center space-x-6">
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <i class="fab fa-twitter text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <i class="fab fa-linkedin text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <i class="fab fa-github text-2xl"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <i class="fab fa-discord text-2xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                    Ready to Experience the Future of Voting?
                </h2>
                <p class="text-xl text-gray-400 mb-8">
                    Join thousands of voters who trust TrueVote for secure, transparent elections
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="openRegisterModal()" class="btn-secondary text-white px-8 py-4 rounded-xl font-semibold text-lg focus-ring">
                        <i class="fas fa-user-plus mr-2"></i>Register Now - It's Free
                    </button>
                    <button onclick="scheduleDemo()" class="btn-outline text-blue-400 px-8 py-4 rounded-xl font-semibold text-lg focus-ring">
                        <i class="fas fa-calendar mr-2"></i>Schedule a Demo
                    </button>
                </div>
                
                <p class="text-sm text-gray-500 mt-6">
                    No credit card required • Setup in under 5 minutes • 24/7 support included
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-gradient border-t border-gray-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-vote-yea text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">TrueVote</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        The world's most secure digital voting platform, powered by blockchain technology and trusted by governments worldwide.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Platform</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Security</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">API Docs</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm mb-4 sm:mb-0">
                    &copy; 2024 TrueVote. All rights reserved.
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-shield-alt text-green-400"></i>
                        <span>Blockchain Secured</span>
                    </span>
                    <span>•</span>
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-lock text-blue-400"></i>
                        <span>256-bit Encrypted</span>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px);">
        <div class="rounded-2xl shadow-2xl max-w-md w-full scale-in" style="background-color: var(--bg-secondary);">
            <div class="p-6 border-b border-gray-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-sign-in-alt text-blue-500 mr-2"></i>Login to TrueVote
                    </h3>
                    <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form onsubmit="handleLogin(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Voter ID or Email</label>
                            <input type="text" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Enter your voter ID or email">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Password</label>
                            <input type="password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Enter your password">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-600 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm" style="color: var(--text-secondary);">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Forgot password?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full mt-6 btn-primary text-white px-6 py-3 rounded-lg font-semibold focus-ring">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Securely
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm" style="color: var(--text-muted);">
                        Don't have an account? 
                        <button onclick="switchToRegister()" class="text-blue-400 hover:text-blue-300 transition-colors font-medium">Register here</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px);">
        <div class="rounded-2xl shadow-2xl max-w-md w-full scale-in" style="background-color: var(--bg-secondary);">
            <div class="p-6 border-b border-gray-600">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">
                        <i class="fas fa-user-plus text-green-500 mr-2"></i>Register for TrueVote
                    </h3>
                    <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form onsubmit="handleRegister(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Full Name</label>
                            <input type="text" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Enter your full name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email Address</label>
                            <input type="email" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Enter your email">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Phone Number</label>
                            <input type="tel" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Enter your phone number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Password</label>
                            <input type="password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: var(--bg-tertiary); border-color: #374151; color: var(--text-primary);" placeholder="Create a strong password">
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" required class="rounded border-gray-600 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm" style="color: var(--text-secondary);">
                                    I agree to the <a href="#" class="text-blue-400 hover:text-blue-300">Terms of Service</a> and <a href="#" class="text-blue-400 hover:text-blue-300">Privacy Policy</a>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full mt-6 btn-secondary text-white px-6 py-3 rounded-lg font-semibold focus-ring">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm" style="color: var(--text-muted);">
                        Already have an account? 
                        <button onclick="switchToLogin()" class="text-blue-400 hover:text-blue-300 transition-colors font-medium">Login here</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🗳️ TrueVote Landing Page initialized');
            animateCounters();
        });

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

        // Modal functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchToRegister() {
            closeLoginModal();
            setTimeout(() => openRegisterModal(), 200);
        }

        function switchToLogin() {
            closeRegisterModal();
            setTimeout(() => openLoginModal(), 200);
        }

        // Form handlers
        function handleLogin(event) {
            event.preventDefault();
            const button = event.target.querySelector('button[type="submit"]');
            
            // Add loading state
            button.classList.add('loading');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Logging in...';
            button.disabled = true;
            
            // Simulate login process
            setTimeout(() => {
                showNotification('Login successful! Redirecting to dashboard...', 'success');
                setTimeout(() => {
                    // In a real app, this would redirect to the dashboard
                    alert('Login successful! You would now be redirected to your voter dashboard.');
                    closeLoginModal();
                    
                    // Reset button
                    button.classList.remove('loading');
                    button.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login Securely';
                    button.disabled = false;
                }, 1500);
            }, 2000);
        }

        function handleRegister(event) {
            event.preventDefault();
            const button = event.target.querySelector('button[type="submit"]');
            
            // Add loading state
            button.classList.add('loading');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            button.disabled = true;
            
            // Simulate registration process
            setTimeout(() => {
                showNotification('Account created successfully! Please check your email for verification.', 'success');
                setTimeout(() => {
                    closeRegisterModal();
                    
                    // Reset button
                    button.classList.remove('loading');
                    button.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
                    button.disabled = false;
                    
                    // Show verification message
                    alert('Registration successful! Please check your email to verify your account before logging in.');
                }, 1500);
            }, 2000);
        }

        // Utility functions
        function learnMore() {
            document.getElementById('features').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        function scheduleDemo() {
            alert('Demo scheduling feature would open here.\n\nYou would be able to:\n• Choose a convenient time\n• Select demo type (personal/group)\n• Specify your organization\n• Add special requirements\n\nOur team would then contact you to confirm the demo.');
        }

        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = counter.textContent;
                        const isPercentage = target.includes('%');
                        const isPlus = target.includes('+');
                        const numericValue = parseInt(target.replace(/[^\d]/g, ''));
                        
                        let current = 0;
                        const increment = numericValue / 50;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= numericValue) {
                                current = numericValue;
                                clearInterval(timer);
                            }
                            
                            let displayValue = Math.floor(current);
                            if (isPercentage) {
                                displayValue += '%';
                            } else if (isPlus) {
                                displayValue = displayValue >= 1000 ? Math.floor(displayValue / 1000) + 'K+' : displayValue + '+';
                            }
                            
                            counter.textContent = displayValue;
                        }, 20);
                        
                        observer.unobserve(counter);
                    }
                });
            });
            
            counters.forEach(counter => observer.observe(counter));
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const colors = {
                success: 'from-green-500 to-green-600',
                error: 'from-red-500 to-red-600',
                warning: 'from-yellow-500 to-yellow-600',
                info: 'from-blue-500 to-blue-600'
            };
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300 bg-gradient-to-r ${colors[type]}`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${icons[type]}"></i>
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
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const loginModal = document.getElementById('loginModal');
            const registerModal = document.getElementById('registerModal');
            
            if (event.target === loginModal) {
                closeLoginModal();
            }
            if (event.target === registerModal) {
                closeRegisterModal();
            }
        });

        // Keyboard navigation for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLoginModal();
                closeRegisterModal();
            }
        });

        console.log('✅ TrueVote Landing Page ready');
        console.log('🔒 Secure authentication system loaded');
        console.log('📱 Responsive design active');
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'97c5479b31c91191',t:'MTc1NzQwNjE2Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
