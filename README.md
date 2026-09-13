🗳️ TrueVote — Blockchain-Based Online Voting System

<p align="center">
  <strong>A secure and transparent online voting platform that combines traditional web technologies with blockchain to improve election integrity, transparency, and tamper resistance.</strong>
</p><p align="center">
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Python-Blockchain%20Integration-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python">
  <img src="https://img.shields.io/badge/Solidity-Smart%20Contracts-363636?style=for-the-badge&logo=solidity&logoColor=white" alt="Solidity">
  <img src="https://img.shields.io/badge/Blockchain-Secure%20Voting-121212?style=for-the-badge&logo=ethereum&logoColor=white" alt="Blockchain">
</p><p align="center">
  <a href="https://github.com/Amal070/Truevote_system-blockchain">Repository</a>
  •
  <a href="#features">Features</a>
  •
  <a href="#architecture">Architecture</a>
  •
  <a href="#installation">Installation</a>
</p>---

📌 Overview

TrueVote is a blockchain-based online voting system designed to provide a more secure, transparent, and tamper-resistant approach to digital elections.

The application combines a traditional web-based voting platform with blockchain technology. The web application handles user interaction, authentication, administration, and database operations, while the blockchain component is intended to provide an additional layer of trust and integrity for election-related records.

The project brings together:

- 🌐 PHP-based web application
- 🗄️ MySQL database
- 🐍 Python blockchain integration
- ⛓️ Solidity smart contracts
- 👤 Voter management
- 🛡️ Administrative management
- 🔐 Authentication and password recovery

---

✨ Key Features

👤 Voter Module

The voter module provides the user-facing functionality of the voting system.

Features include:

- Voter registration/login workflow
- Voter authentication
- Access to voting functionality
- Election participation
- Voter-related information management
- Logout functionality

---

🛡️ Admin Module

The administrative module provides functionality for managing the voting system.

Administrators can manage election-related information and oversee the system through a dedicated admin area.

Administrative capabilities include:

- Admin authentication
- Voter management
- Election management
- Candidate-related management
- Voting oversight
- System administration

---

⛓️ Blockchain Integration

Blockchain is the core differentiating component of TrueVote.

The project contains a dedicated "blockchain/" module and Solidity/Python components for integrating blockchain technology with the voting workflow.

The objective is to provide:

- 🔒 Tamper-resistant records
- 🔍 Improved transparency
- 🧾 Verifiable voting transactions
- ⛓️ Decentralized record integrity
- 🛡️ Additional protection against unauthorized modification

«Important: Blockchain technology can improve integrity and auditability, but a production-grade election system requires significantly more security, privacy, identity verification, cryptographic design, infrastructure hardening, and independent auditing.»

---

🔐 Authentication

TrueVote includes a dedicated authentication workflow with:

- Login
- Logout
- Password recovery
- Password reset
- Session-based access control

Relevant authentication files include:

login.php
logout.php
forgot_password.php
reset_password.php

---

🏗️ System Architecture

TrueVote uses a hybrid architecture where the web application, database, and blockchain components work together.

                         ┌─────────────────────┐
                         │       Voter         │
                         └──────────┬──────────┘
                                    │
                                    ▼
                         ┌─────────────────────┐
                         │   Web Application   │
                         │        PHP          │
                         └──────────┬──────────┘
                                    │
                    ┌───────────────┼────────────────┐
                    │               │                │
                    ▼               ▼                ▼
             ┌────────────┐  ┌────────────┐  ┌──────────────┐
             │    Auth    │  │   MySQL    │  │ Blockchain   │
             │   System   │  │  Database  │  │ Integration  │
             └────────────┘  └────────────┘  └───────┬──────┘
                                                     │
                                                     ▼
                                             ┌──────────────┐
                                             │    Python    │
                                             │  Blockchain  │
                                             └───────┬──────┘
                                                     │
                                                     ▼
                                             ┌──────────────┐
                                             │   Solidity   │
                                             │Smart Contract│
                                             └──────────────┘

---

🔄 Voting Workflow

┌──────────────┐
│    Voter     │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Registration │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│    Login     │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Verify Access│
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Cast Vote    │
└──────┬───────┘
       │
       ├──────────────────┐
       │                  │
       ▼                  ▼
┌──────────────┐   ┌──────────────┐
│    MySQL     │   │  Blockchain  │
│ Application  │   │   Record     │
│    Data      │   │              │
└──────────────┘   └──────┬───────┘
                          │
                          ▼
                  ┌──────────────┐
                  │ Verification │
                  │ / Integrity  │
                  └──────────────┘

---

🧩 Project Modules

The repository is organized into dedicated modules:

Truevote_system-blockchain/
│
├── admin/                 # Administrative functionality
│
├── voter/                 # Voter functionality
│
├── blockchain/            # Blockchain / smart-contract integration
│
├── db/                    # Database-related resources
│
├── includes/              # Shared PHP components
│
├── assets/                # CSS, JavaScript, images and UI assets
│
├── test/                  # Testing resources
│
├── uploads/               # Uploaded files/assets
│
├── vendor/                # External PHP dependencies
│
├── config.php             # Application configuration
├── db.php                 # Database connection
│
├── index.php              # Main entry point
├── login.php              # Login
├── logout.php             # Logout
│
├── forgot_password.php    # Password recovery
├── reset_password.php     # Password reset
│
├── req.txt                # Project requirements
└── README.md              # Project documentation

The repository currently contains these major modules and supporting files.

---

🛠️ Technology Stack

Technology| Role
PHP| Web application backend
MySQL| Relational database
Python| Blockchain-related integration
Solidity| Smart contract development
Blockchain| Tamper-resistant transaction/record layer
HTML/CSS| User interface
JavaScript| Client-side interaction
Composer/Vendor| PHP dependencies

---

📋 Requirements

Before running the project, install/configure:

- PHP
- MySQL
- Apache or another PHP-compatible web server
- Python 3
- Python dependencies from "req.txt"
- Solidity-compatible development environment
- Blockchain/Web3 environment required by the project's blockchain integration
- Composer dependencies, if required by the "vendor" setup

---

🚀 Installation

1. Clone the Repository

git clone https://github.com/Amal070/Truevote_system-blockchain.git

Move into the project:

cd Truevote_system-blockchain

---

2. Configure PHP/MySQL

Create the required MySQL database and configure the project's database credentials.

Review:

config.php
db.php

Set your local database configuration appropriately.

Example:

$host = "localhost";
$username = "root";
$password = "";
$database = "truevote";

«Use your actual database name and credentials. Do not commit production credentials to GitHub.»

---

3. Install Python Dependencies

If "req.txt" contains the required Python packages:

pip install -r req.txt

For a cleaner development environment:

python -m venv venv

Windows:

venv\Scripts\activate

Linux/macOS:

source venv/bin/activate

Then:

pip install -r req.txt

---

4. Configure Blockchain

The blockchain component is located under:

blockchain/

Configure the required blockchain network, smart contract deployment, account/provider settings, and contract address according to the project's blockchain implementation.

The Solidity smart contract should be deployed before connecting the application to a live blockchain network.

For development, use a local blockchain/test network rather than real production funds.

---

5. Start the Web Server

If using XAMPP:

1. Copy the project into:

C:\xampp\htdocs\

2. Start:

Apache
MySQL

3. Open:

http://localhost/Truevote_system-blockchain/

---

🧪 Testing

The repository contains a dedicated:

test/

directory for testing-related resources.

Before deployment, test:

- Registration
- Login
- Logout
- Password recovery
- Voter authorization
- Vote submission
- Duplicate voting prevention
- Blockchain transaction flow
- Database operations
- Admin functionality
- Invalid input handling

---

🔐 Security

Security is especially important for an online voting system.

The application should be hardened with:

- Strong password hashing
- Prepared SQL statements
- Server-side input validation
- CSRF protection
- XSS protection
- Secure session configuration
- Role-based authorization
- Rate limiting
- Secure password-reset tokens
- Protection of uploaded files
- Environment-based secrets
- HTTPS
- Secure blockchain key management
- Smart-contract security auditing

⚠️ Production Warning

This project should be considered an academic/portfolio implementation, not a production-ready public-election system without extensive independent security review.

A real election platform would require rigorous cryptographic, privacy, identity, accessibility, operational, legal, and infrastructure requirements.

---

🎯 Project Objectives

TrueVote aims to explore how blockchain can be combined with conventional web technologies to improve trust in online voting.

Main objectives

- Build an online voting platform.
- Provide separate voter and administrator workflows.
- Maintain application data using MySQL.
- Integrate blockchain technology into the voting workflow.
- Explore smart contracts using Solidity.
- Use Python for blockchain-related processing/integration.
- Improve transparency and auditability.
- Reduce the possibility of unauthorized modification of voting records.

---

💡 Why Blockchain for Voting?

Traditional centralized voting applications rely heavily on a central database and trusted administrators.

Blockchain introduces a distributed record layer that can help provide:

Traditional System
        │
        ▼
┌─────────────────┐
│ Central Database│
└─────────────────┘
        │
        ▼
 Trusted Authority


TrueVote Approach
        │
        ▼
┌─────────────────┐
│ Web Application │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     MySQL       │
└─────────────────┘
         │
         ▼
┌─────────────────┐
│   Blockchain    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Smart Contract  │
└─────────────────┘

The blockchain layer can provide an additional mechanism for verifying that recorded transactions have not been silently altered.

---

📸 Screenshots

Screenshots will make this repository significantly more attractive to recruiters.

Recommended structure:

screenshots/
├── home.png
├── login.png
├── voter-dashboard.png
├── admin-dashboard.png
├── voting-page.png
├── blockchain.png
└── results.png

Then add them to this README:

## 📸 Screenshots

### 🏠 Home Page
![Home Page](screenshots/home.png)

### 🔐 Login
![Login](screenshots/login.png)

### 👤 Voter Dashboard
![Voter Dashboard](screenshots/voter-dashboard.png)

### 🗳️ Voting Page
![Voting Page](screenshots/voting-page.png)

### 🛡️ Admin Dashboard
![Admin Dashboard](screenshots/admin-dashboard.png)

### ⛓️ Blockchain Integration
![Blockchain](screenshots/blockchain.png)

---

📊 Key Learning Outcomes

This project provides practical experience in:

- Full-stack web development
- PHP backend development
- MySQL database management
- Authentication and authorization
- Form handling and validation
- Blockchain fundamentals
- Smart contract concepts
- Solidity
- Python blockchain integration
- Web3 concepts
- Secure application design
- Git/GitHub project management

---

🚀 Future Enhancements

Potential improvements include:

- 🔐 Multi-factor authentication
- 🪪 Strong digital identity verification
- ⛓️ Improved smart-contract architecture
- 🔎 Public transaction verification
- 📊 Real-time election analytics
- 📱 Mobile-responsive interface
- 🔔 Election notifications
- 📧 Email/SMS notifications
- 🧾 Blockchain-based audit reports
- 🗳️ Multiple election types
- 👥 Advanced role management
- 📈 Election result visualization
- 🧪 Automated test coverage
- 🐳 Docker deployment
- ☁️ Cloud deployment
- 🔒 Independent smart-contract security audit

---

🗂️ Documentation

The repository also contains project documentation and academic materials, including:

- Project abstract
- Base paper
- Mini project report
- TrueVote report
- Presentation
- TODO/documentation files

These materials provide additional context about the project's academic background and implementation.

---

🤝 Contributing

Contributions and improvements are welcome.

1. Fork the repository

2. Create a feature branch

git checkout -b feature/improvement

3. Make your changes

4. Commit

git add .
git commit -m "Improve voting workflow"

5. Push

git push origin feature/improvement

6. Open a Pull Request

---

⭐ Support

If you find TrueVote interesting, consider giving the repository a ⭐ on GitHub.

Your feedback, suggestions, and contributions are welcome.

---

👨‍💻 Author

Amal Shaji

Python | Django | PHP | Web Development | Blockchain

GitHub:
https://github.com/Amal070

Project:
https://github.com/Amal070/Truevote_system-blockchain

---

📄 License

This project is primarily intended for educational, academic, and portfolio purposes.

Before using the system for real-world elections, comprehensive security, privacy, legal, cryptographic, and infrastructure reviews are required.

---

<p align="center">🗳️ TrueVote

Secure • Transparent • Tamper-Resistant

Exploring the future of trustworthy digital elections with Blockchain.

</p>
