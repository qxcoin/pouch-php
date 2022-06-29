<?php

use QXCoin\BIP39\BIP39;
use QXCoin\Pouch\Networks\TronNetwork;
use QXCoin\Pouch\Wallet\WalletFactory;

require('../vendor/autoload.php');

$mnemonic = 'soap fit diesel safe chicken trash cute detect crazy crime increase move certain square vapor';

$walletFactory = new WalletFactory();
$bip39 = new BIP39();

$seed = $bip39->mnemonicToSeed($mnemonic);
$wallet = $walletFactory->createWallet($seed, new TronNetwork());
$account = $wallet->getAccount(0);

var_dump($wallet->getAddress(0, false, $account));
var_dump($wallet->getAddress(1, false, $account));
