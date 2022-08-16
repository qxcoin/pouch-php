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

    /**
     * @param string $publicKeyHash Public Key or HASH160 of the Public Key
     */
    public function apply(int $outputIndex, string $publicKeyHash): void
    {
        if (40 === strlen($publicKeyHash)) {
            $hash160 = $publicKeyHash;
        } else {
            $hash160 = Bitcoin::hash160(hex2bin($publicKeyHash));
        }

        $hex = '';
        $hex .= '00'; // OP_0
        $hex .= str_pad(dechex(strlen(hex2bin($hash160))), 2, '0', STR_PAD_LEFT); // OP_PUSHBYTES_20
        $hex .= $hash160;

        $output = $this->transaction->getOutput($outputIndex);
        $output->setScriptPubKey($hex);
    }
}
