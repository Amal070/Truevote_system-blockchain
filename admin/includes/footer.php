<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TrueVote Admin Panel Footer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #0f172a;
            color: #cbd5e1;
        }

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

        /* Footer styles */
        footer {
            margin-top: 2.5rem;
            border-top: 1px solid #374151;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            color: var(--text-secondary);
            padding: 1rem 0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 0.6s ease-in-out;
        }

        .footer-left {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-align: center;
        }

        .footer-left .panel-name {
            font-weight: 600;
            color: #ffffff;
        }

        .footer-right {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .footer-right a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-right a:hover {
            color: #ffffff;
        }

        .blockchain-verified {
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%);
            animation: pulse 2s infinite;
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            color: #ffffff;
        }

        .blockchain-verified i {
            margin-right: 0.25rem;
            font-size: 0.625rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Responsive */
        @media (min-width: 768px) {
            .footer-container {
                flex-direction: row;
            }
            .footer-left {
                margin-bottom: 0;
                text-align: left;
            }
            .footer-right {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

<footer>
    <div class="footer-container">

        <!-- Left -->
        <div class="footer-left">
            © 2025 <span class="panel-name">TrueVote Admin Panel</span>. All rights reserved.
        </div>

        <!-- Right -->
        <div class="footer-right">
            <a href="#">Version 2.1.0</a>
            <a href="#">Documentation</a>
            <a href="#">Support</a>
            <span class="blockchain-verified">
                <i class="fas fa-circle"></i> Blockchain Connected
            </span>
        </div>

    </div>
</footer>

</body>
</html>
