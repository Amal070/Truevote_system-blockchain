<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ganache Transactions - TrueVote Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family:'Inter',sans-serif; margin:0; padding:0; background:#0f172a; color:#cbd5e1; }

        .container { max-width: 1100px; margin:2rem auto; padding:0 1rem; }
        h2 { text-align:center; font-weight:600; margin-bottom:2rem; color:#f8fafc; }

        .card {
            background:#1e293b;
            padding:1.5rem;
            border-radius:0.75rem;
            box-shadow:0 10px 20px rgba(0,0,0,0.3);
            transition:transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover { transform:translateY(-2px); box-shadow:0 15px 25px rgba(0,0,0,0.5); }

        .btn-primary {
            background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color:#fff; padding:0.6rem 1rem;
            border:none; border-radius:0.5rem;
            cursor:pointer; font-weight:600;
            text-decoration:none; display:inline-block;
        }
        .btn-primary:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(59,130,246,0.5); }

        .tx-card {
            background:#0f172a;
            border:1px solid #334155;
            border-radius:0.6rem;
            padding:1rem;
            margin:1rem 0;
        }
        .tx-card p { margin:0.4rem 0; font-size:0.9rem; }
        .tx-card strong { color:#f8fafc; }
    </style>
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="container">
    <h2>Transactions for Ganache Address</h2>
    <div class="card">
        <p><strong>Tracking Address:</strong> 
            <span style="color:#3b82f6;">0xAC5f5752325eFffF8380690c92648ED3E8de28b9</span>
        </p>
        <button id="loadTxs" class="btn-primary"><i class="fas fa-sync-alt"></i> Load Transactions</button>
        <div id="txList"></div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
<script>
    const web3 = new Web3("http://127.0.0.1:7545"); // Ganache RPC
    const targetAddress = "0xAC5f5752325eFffF8380690c92648ED3E8de28b9".toLowerCase();

    document.getElementById("loadTxs").addEventListener("click", async () => {
        const latestBlockNumber = await web3.eth.getBlockNumber();
        const txDiv = document.getElementById("txList");
        txDiv.innerHTML = "<p>Loading transactions...</p>";
        let found = false;
        txDiv.innerHTML = "";

        for (let i = 0; i <= latestBlockNumber; i++) {
            const block = await web3.eth.getBlock(i, true);
            for (let tx of block.transactions) {
                if (tx.from.toLowerCase() === targetAddress || (tx.to && tx.to.toLowerCase() === targetAddress)) {
                    found = true;
                    let receipt = await web3.eth.getTransactionReceipt(tx.hash);
                    let toAddress = tx.to ? tx.to : (receipt.contractAddress ? receipt.contractAddress : "N/A");

                    const txInfo = `
                        <div class="tx-card">
                            <p><strong>Block:</strong> ${i}</p>
                            <p><strong>Tx Hash:</strong> ${tx.hash}</p>
                            <p><strong>From:</strong> ${tx.from}</p>
                            <p><strong>To / Contract:</strong> ${toAddress}</p>
                            <p><strong>Gas Used:</strong> ${receipt.gasUsed}</p>
                        </div>
                    `;
                    txDiv.innerHTML += txInfo;
                }
            }
        }

        if (!found) {
            txDiv.innerHTML = "<p>No transactions found for this address.</p>";
        }
    });
</script>
</body>
</html>
