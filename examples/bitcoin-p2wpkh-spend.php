<?php

use QXCoin\Pouch\Scripts\P2WPKHScriptPubKey;
use QXCoin\Pouch\Witness\P2WPKHWitness;
use QXCoin\Pouch\Transactions\BitcoinSegWitTransaction;
use QXCoin\Pouch\Transactions\BitcoinTransactionInput;
use QXCoin\Pouch\Transactions\BitcoinTransactionOutput;

require('../vendor/autoload.php');

// input:
// addr: tb1qhg7j3xz78ya98mqr3u5ul2h70jaj8ffcjxvv9l
// private: cUyESMXqrB3KZFRR73ridbRyJ2fcDukXfcVEAAYqpa95bbmU99J8
// public: 03b48d2644fc58b98a32110fd332d5f7673b4bd500f9c5b90961ecbcdad105e68c

// output:
// addr: tb1q08ak3mpzglvpg9swfyu0yqx3grphl3nsdksmgw
// private: cQsNF2jp4HWTG1UJxA7NUCpBXn6RgUwKZdTfPtqHgfxUXFisVsxp
// public: 02177df402c46ab70f6734a873058a8c906a9241f9d4826e337be2b9e21ab39e50

$input0 = new BitcoinTransactionInput();
$input0->setTxId('f034fa68202e93ea6c30ab34ed2e1dd8cb26333832b493acaadb46a3b415485f');
$input0->setOutputIndex(1);
$input0->setOutputScriptPubKey('0014ba3d28985e393a53ec038f29cfaafe7cbb23a538');
$input0->setOutputValue(10000);

$output0 = new BitcoinTransactionOutput();
$output0->setValue(10000 - 1000);

$transaction = new BitcoinSegWitTransaction();
$transaction->setInput(0, $input0);
$transaction->setOutput(0, $output0);

$p2pkhScriptPubKey = new P2WPKHScriptPubKey($transaction);
$p2pkhScriptPubKey->apply(0, '02177df402c46ab70f6734a873058a8c906a9241f9d4826e337be2b9e21ab39e50');

$p2wpkhWitnessField = new P2WPKHWitness($transaction);
$p2wpkhWitnessField->apply(
    0,
    'dc73d6fd8ffb5c6ade611bd102a4f0b2340271caaa648e86946cb4fb91051efe',
    '03b48d2644fc58b98a32110fd332d5f7673b4bd500f9c5b90961ecbcdad105e68c',
);

// TXID: d763056e2abb4c35a625f66377e981e7a81bed72243adde161e15079744ef697
var_dump($transaction->getId());
var_dump($transaction->getHex());
