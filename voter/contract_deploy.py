from web3 import Web3
from solcx import compile_source, install_solc, set_solc_version
from eth_account import Account
from config import My_PRIVATE_KEY

# Ganache RPC endpoint & account private key
GANACHE_RPC = "http://127.0.0.1:7545"
PRIVATE_KEY = My_PRIVATE_KEY

# Solidity source code
hello_source = """
// SPDX-License-Identifier: MIT
pragma solidity ^0.8.17;

contract HelloWorld {
    string public greet = "Hello World!";
}
"""

# Step 1: Install and set compiler
install_solc("0.8.17")          # installs if not already
set_solc_version("0.8.17")      # force using this version

# Step 2: Compile contract
compiled = compile_source(hello_source, output_values=["abi", "bin"])
contract_id, contract_interface = compiled.popitem()
abi = contract_interface["abi"]
bytecode = contract_interface["bin"]

# Step 3: Connect to Ganache
w3 = Web3(Web3.HTTPProvider(GANACHE_RPC))
assert w3.is_connected(), "❌ Cannot connect to Ganache. Check GANACHE_RPC URL."

chain_id = w3.eth.chain_id

# Step 4: Setup account
acct = Account.from_key(PRIVATE_KEY)
my_address = acct.address
print("✅ Deploying from:", my_address)

# Step 5: Build transaction
nonce = w3.eth.get_transaction_count(my_address)
HelloWorld = w3.eth.contract(abi=abi, bytecode=bytecode)

construct_txn = HelloWorld.constructor().build_transaction({
    "from": my_address,
    "nonce": nonce,
    "chainId": chain_id,
    "gas": 2000000,
    "gasPrice": w3.eth.gas_price
})

# Step 6: Sign and send transaction
signed = acct.sign_transaction(construct_txn)

# ✅ FIX: use raw_transaction instead of rawTransaction
tx_hash = w3.eth.send_raw_transaction(signed.raw_transaction)
print("📌 Transaction hash:", tx_hash.hex())

# Step 7: Wait for receipt
tx_receipt = w3.eth.wait_for_transaction_receipt(tx_hash)
print("🎉 Contract deployed at:", tx_receipt.contractAddress)

# Step 8: Interact with the contract
hello_instance = w3.eth.contract(address=tx_receipt.contractAddress, abi=abi)
print("📢 Greeting from contract:", hello_instance.functions.greet().call())