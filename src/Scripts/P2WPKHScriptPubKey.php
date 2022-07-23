<?php

namespace QXCoin\Pouch\Scripts;

use QXCoin\Pouch\Transactions\BitcoinSegWitTransaction as Transaction;
use QXCoin\Pouch\Utils\Bitcoin;

final class P2WPKHScriptPubKey
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function apply(int $outputIndex, string $publicKey): void
    {
        $hash160 = Bitcoin::hash160(hex2bin($publicKey));

        $hex = '';
        $hex .= '00'; // OP_0
        $hex .= str_pad(dechex(strlen(hex2bin($hash160))), 2, '0', STR_PAD_LEFT); // OP_PUSHBYTES_20
        $hex .= $hash160;

        $output = $this->transaction->getOutput($outputIndex);
        $output->setScriptPubKey($hex);
    }
}
