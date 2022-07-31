<?php

use QXCoin\Pouch\PrivateKey\WIFPrivateKeyParser;
use QXCoin\Pouch\Scripts\P2PKHScriptPubKey;
use QXCoin\Pouch\Scripts\P2PKHScriptSig;
use QXCoin\Pouch\Transactions\BitcoinTransaction;
use QXCoin\Pouch\Transactions\BitcoinTransactionInput;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput;

require('../vendor/autoload.php');

// input 0:
// addr: mhv3MumosF2t32DHhH41VHQXW6ucxYn2nh
// private: cUQEneSX1CmXXpTpHeLLZLjebNkcYyRwE8ZBUmBzPivt8NPwMsWb
// public: 035619633de86ef9b3af4094919fbbe551a4fb3da30361a45074722e878a5a24a5

// input 1:
// addr: mtuybaEcnNwLYgvZHAPj1PrXpk2GLvXtuv
// private: cQhpYGwDp4izvGCXNHCKUNxZjC5TDnqTCSv9FrGc82c27ZkUC51H
// public: 027fa59df58616623ae0afa15656559b44ff721464e21358b1a5e19dd6e2406071

// output 0:
// addr: msnFQqVw2CJgxuQvXaSSuTWmXTG5mzsjqH
// private: cV7U9Zyq4VNFLxEt9G8snhp8JvNzTmZF4XeF6N1HhmyBM4XqayDe
// public: 0217ad31ee82e10694697bca66675f51eb28ba0681a0efd336f75cc6e6209dab98

$input0 = new BitcoinTransactionInput();
$input0->setTxId('e1d2b4e411445c85cb1ec421dde2cc99ed20f5c6689f1ed884b769b9422b6dc3');
$input0->setOutputIndex(1);
$input0->setOutputScriptPubKey('76a9141a4e0c48c0f06d113190eb6ae5bd19f7b6951ec988ac');

$input1 = new BitcoinTransactionInput();
$input1->setTxId('9a5c96f8c1da2f419202c26ba52f0d449d1de2b3a83235a6bbb03a250fe82252');
$input1->setOutputIndex(0);
$input1->setOutputScriptPubKey('76a91492f44a0a518e75b1ff5e99f7d599f80d8a5ddfb788ac');

$output0 = new BitcoinTransactionOutput();
$output0->setValue(19000 - 1000);

$transaction = new BitcoinTransaction();
$transaction->setInput(0, $input0);
$transaction->setInput(1, $input1);
$transaction->setOutput(0, $output0);

$p2pkhScriptPubKey = new P2PKHScriptPubKey($transaction);
$p2pkhScriptPubKey->apply(0, '0217ad31ee82e10694697bca66675f51eb28ba0681a0efd336f75cc6e6209dab98');

$p2pkhScriptSig = new P2PKHScriptSig($transaction);
$p2pkhScriptSig->apply(
    0,
    (new WIFPrivateKeyParser())->parse('cUQEneSX1CmXXpTpHeLLZLjebNkcYyRwE8ZBUmBzPivt8NPwMsWb')['private_key'],
    '035619633de86ef9b3af4094919fbbe551a4fb3da30361a45074722e878a5a24a5',
);
$p2pkhScriptSig->apply(
    1,
    (new WIFPrivateKeyParser())->parse('cQhpYGwDp4izvGCXNHCKUNxZjC5TDnqTCSv9FrGc82c27ZkUC51H')['private_key'],
    '027fa59df58616623ae0afa15656559b44ff721464e21358b1a5e19dd6e2406071',
);

// TXID: f114a339b8fb4d5f34161c828a8995966a65010098cd04e38d3c3590d2f08902
var_dump($transaction->getResult());
