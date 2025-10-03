import sys, json
from web3 import Web3
from solcx import compile_source, install_solc, set_solc_version
from eth_account import Account
from config import My_PRIVATE_KEY  # Ganache private key

# Read args
if len(sys.argv) != 4:
    print(json.dumps({"error": "Usage: python contract_deploy.py <election_id> <candidate_id> <user_id>"}))
    sys.exit(1)

try:
    election_id = int(sys.argv[1])
    candidate_id = int(sys.argv[2])
    user_id = int(sys.argv[3])

    GANACHE_RPC = "http://127.0.0.1:7545"
    PRIVATE_KEY = My_PRIVATE_KEY

    election_source = """
    // SPDX-License-Identifier: MIT
    pragma solidity ^0.8.17;

    contract IndianElection {
        uint256 public election_id;
        uint256 public candidate_id;
        uint256 public user_id;

        constructor(uint256 _election_id, uint256 _candidate_id, uint256 _user_id) {
            election_id = _election_id;
            candidate_id = _candidate_id;
            user_id = _user_id;
        }
    }
    """

    install_solc("0.8.17")
    set_solc_version("0.8.17")

    compiled = compile_source(election_source, output_values=["abi", "bin"])
    _, contract_interface = compiled.popitem()
    abi, bytecode = contract_interface["abi"], contract_interface["bin"]

    w3 = Web3(Web3.HTTPProvider(GANACHE_RPC))
    assert w3.is_connected(), "❌ Cannot connect to Ganache"

    acct = Account.from_key(PRIVATE_KEY)
    my_address = acct.address
    chain_id = w3.eth.chain_id
    nonce = w3.eth.get_transaction_count(my_address)

    Election = w3.eth.contract(abi=abi, bytecode=bytecode)
    construct_txn = Election.constructor(election_id, candidate_id, user_id).build_transaction({
        "from": my_address,
        "nonce": nonce,
        "chainId": chain_id,
        "gas": 2000000,
        "gasPrice": w3.eth.gas_price
    })

    signed = acct.sign_transaction(construct_txn)
    tx_hash = w3.eth.send_raw_transaction(signed.raw_transaction)
    tx_receipt = w3.eth.wait_for_transaction_receipt(tx_hash)

    result = {
        "tx_hash": tx_hash.hex(),
        "contract_address": tx_receipt.contractAddress
    }
    print(json.dumps(result))  # ✅ print only JSON

except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)
